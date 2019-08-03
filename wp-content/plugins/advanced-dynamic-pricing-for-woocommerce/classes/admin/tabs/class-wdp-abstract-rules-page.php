<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class WDP_Admin_Abstract_Rules_Page extends WDP_Admin_Abstract_Page {
	protected function get_template_path() {
		return false;
	}

	public function render() {
		$condition_registry   = WDP_Condition_Registry::get_instance();
		$conditions_templates = $condition_registry->get_templates_content();
		$conditions_titles    = $condition_registry->get_titles();

		$limit_registry   = WDP_Limit_Registry::get_instance();
		$limits_templates = $limit_registry->get_templates_content();
		$limits_titles    = $limit_registry->get_titles();

		$cart_registry  = WDP_Cart_Adj_Registry::get_instance();
		$cart_templates = $cart_registry->get_templates_content();
		$cart_titles    = $cart_registry->get_titles();

		$options = WDP_Helpers::get_settings();

		$pagination = $this->make_pagination_html();
		$page = 'wdp_settings';
		$tab = $this->tab;
		$hide_inactive = $this->get_is_hide_inactive();

		$this->render_template(
			$this->get_template_path(),
			compact(
				'conditions_templates',
				'conditions_titles',
				'limits_templates',
				'limits_titles',
				'cart_templates',
				'cart_titles',
				'options',
				'pagination',
				'page',
				'hide_inactive',
				'tab'
			)
		);
	}

	public function get_pagenum() {
		$page = 1;
		if ( ! empty( $_GET['paged'] ) ) {
			$page = (int) stripslashes_deep( $_GET['paged'] );
		}

		return $page;
	}

	protected function get_is_hide_inactive() {
		return ! empty( $_GET['hide_inactive'] );
	}

	protected function make_get_rules_args() {
		$args           = array();

		if ( $this->get_is_hide_inactive() ) {
			$args['active_only'] = true;
		}

		$page = $this->get_pagenum();
		if ( $page < 1 ) {
			return array();
		}

		$options        = WDP_Helpers::get_settings();
		$rules_per_page = $options['rules_per_page'];
		$args['limit']  = array( ( $page - 1 ) * $rules_per_page, $rules_per_page );

		return $args;
	}

	public function get_tab_rules() {
		return WDP_Database::get_rules( $this->make_get_rules_args() );
	}

	private function get_pagination_args() {
		$options        = WDP_Helpers::get_settings();
		$rules_per_page = $options['rules_per_page'];
		$rules_count = WDP_Database::get_rules_count( $this->make_get_rules_args() );
		$total_pages = (int)ceil( $rules_count / $rules_per_page );

		$pagination_args                = array();
		$pagination_args['total_items'] = $rules_count;
		$pagination_args['total_pages'] = $total_pages;

		return $pagination_args;
	}


	protected function make_pagination_html() {
		$pagination_args = $this->get_pagination_args();
		if ( ! isset( $pagination_args['total_items'], $pagination_args['total_items'] ) ) {
			return "";
		}

		$which  = 'top';
		$total_items     = $pagination_args['total_items'];
		$total_pages     = $pagination_args['total_pages'];

		$output = '<span class="displaying-num">' . sprintf( _n( '%s item', '%s items', $total_items ), number_format_i18n( $total_items ) ) . '</span>';

		$current              = $this->get_pagenum();
		$removable_query_args = wp_removable_query_args();

		$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

		$current_url = remove_query_arg( $removable_query_args, $current_url );

		$page_links = array();

		$total_pages_before = '<span class="paging-input">';
		$total_pages_after  = '</span></span>';

		$disable_first = $disable_last = $disable_prev = $disable_next = false;

		if ( $current == 1 ) {
			$disable_first = true;
			$disable_prev  = true;
		}
		if ( $current == 2 ) {
			$disable_first = true;
		}
		if ( $current == $total_pages ) {
			$disable_last = true;
			$disable_next = true;
		}
		if ( $current == $total_pages - 1 ) {
			$disable_last = true;
		}

		if ( $disable_first ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&laquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='first-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( remove_query_arg( 'paged', $current_url ) ),
				__( 'First page' ),
				'&laquo;'
			);
		}

		if ( $disable_prev ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&lsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='prev-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', max( 1, $current - 1 ), $current_url ) ),
				__( 'Previous page' ),
				'&lsaquo;'
			);
		}

		if ( 'bottom' === $which ) {
			$html_current_page  = $current;
			$total_pages_before = '<span class="screen-reader-text">' . __( 'Current Page' ) . '</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">';
		} else {
			$html_current_page = sprintf(
				"%s<input class='current-page' id='current-page-selector' type='text' name='paged' value='%s' size='%d' aria-describedby='table-paging' /><span class='tablenav-paging-text'>",
				'<label for="current-page-selector" class="screen-reader-text">' . __( 'Current Page' ) . '</label>',
				$current,
				strlen( $total_pages )
			);
		}
		$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );
		$page_links[]     = $total_pages_before . sprintf( _x( '%1$s of %2$s', 'paging' ), $html_current_page, $html_total_pages ) . $total_pages_after;

		if ( $disable_next ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='next-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', min( $total_pages, $current + 1 ), $current_url ) ),
				__( 'Next page' ),
				'&rsaquo;'
			);
		}

		if ( $disable_last ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&raquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='last-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', $total_pages, $current_url ) ),
				__( 'Last page' ),
				'&raquo;'
			);
		}

		$pagination_links_class = 'pagination-links';
		if ( ! empty( $infinite_scroll ) ) {
			$pagination_links_class .= ' hide-if-js';
		}
		$output .= "\n<span class='$pagination_links_class'>" . join( "\n", $page_links ) . '</span>';

		if ( $total_pages ) {
			$page_class = $total_pages < 2 ? ' one-page' : '';
		} else {
			$page_class = ' no-pages';
		}

		return "<div class='tablenav-pages{$page_class}'>$output</div>";
	}
}