<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Settings {
	/** @var WDP_Admin_Abstract_Page[] */
	private $tabs;
	static $activation_notice_option = 'advanced-dynamic-pricing-for-woocommerce-activation-notice-shown';
	public static $disabled_rules_option_name = 'wdp_rules_disabled_notify';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		if ( isset( $_GET['page'] ) && $_GET['page'] == 'wdp_settings' ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_backend_assets' ) );
			add_filter( 'script_loader_src', array( $this, 'script_loader_src' ), PHP_INT_MAX, 2 );

			if ( isset($_GET['from_notify']) ) {
				$is_exclusive   = $_GET['tab'] == 'exclusive';
				$disabled_rules = get_option( self::$disabled_rules_option_name, array() );

				foreach ( $disabled_rules as $index => $disabled_rule ) {
					if ( $disabled_rule['is_exclusive'] === $is_exclusive ) {
						unset( $disabled_rules[ $index ] );
					}
				}

				update_option( self::$disabled_rules_option_name, $disabled_rules );
			}
		}

		include_once WC_ADP_PLUGIN_PATH . 'classes/admin/class-wdp-ajax.php';
		add_action( 'wp_ajax_wdp_ajax', array( new WDP_Ajax(), 'ajax_requests' ) );

		WDP_Loader::load_core();// init
		$this->prepare_tabs();

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );

		include_once WC_ADP_PLUGIN_PATH . 'classes/admin/class-wdp-preview-order-applied-discount-rules.php';
		add_action('woocommerce_admin_order_preview_end', array( $this, 'print_applied_discounts_order_preview' ));
		add_filter('woocommerce_admin_order_preview_get_order_details', array( $this, 'add_applied_discounts_data' ), 10, 2);

		//do once
		if( !get_option( self::$activation_notice_option ) )
			add_action('admin_notices', array( $this,'display_plugin_activated_message'));

		add_action( 'admin_notices', array( $this, 'notify_rule_disabled' ), 10 );

	}

	public static function print_applied_discounts_order_preview(){
		WDP_Preview_Order_Applied_Discount_Rules::render();
	}

	public static function add_applied_discounts_data($export_data, $order){
		$export_data = WDP_Preview_Order_Applied_Discount_Rules::add_data($export_data, $order);
		return $export_data;
    }

	public function display_plugin_activated_message() {
		?>
		<div class="notice notice-success is-dismissible">
        <p><?php _e( 'Advanced Dynamic Pricing for WooCommerce is available <a href="admin.php?page=wdp_settings">on this page</a>.', 'advanced-dynamic-pricing-for-woocommerce' ); ?></p>
		</div>
		<?php
		update_option( self::$activation_notice_option, true );
	}

	public function add_meta_boxes() {
		include_once WC_ADP_PLUGIN_PATH . 'classes/admin/class-wdp-meta-box-order-applied-discount-rules.php';
		WDP_Meta_Box_Order_Applied_Discount_Rules::init();
	}

	public function admin_menu() {
		add_submenu_page( 'woocommerce', __( 'Pricing Rules', 'advanced-dynamic-pricing-for-woocommerce' ), __( 'Pricing Rules', 'advanced-dynamic-pricing-for-woocommerce' ), 'manage_woocommerce', 'wdp_settings', array( $this, 'show_settings_page' ) );
	}

	public function show_settings_page() {
		$current_tab = $this->get_current_tab();
		$tabs        = $this->get_tabs();
		$handler     = isset( $tabs[ $current_tab ] ) ? $tabs[ $current_tab ] : $tabs[ $this->get_default_tab() ];

		$handler->action();

		include WC_ADP_PLUGIN_PATH . 'views/menupage.php';
	}

	private function prepare_tabs() {
		include_once WC_ADP_PLUGIN_PATH . 'classes/admin/tabs/class-wdp-abstract-page.php';
		foreach ( glob( WC_ADP_PLUGIN_PATH . 'classes/admin/tabs/class-*.php' ) as $filename ) {
			include_once $filename;
		}

		$tabs = array(
			'common'     => new WDP_Admin_Common_Page(),
			'exclusive'  => new WDP_Admin_Exclusive_Page(),
			'statistics' => new WDP_Admin_Statistics_Page(),
			'options'    => new WDP_Admin_Options_Page(),
			'tools'      => new WDP_Admin_Tools_Page(),
			'help'       => new WDP_Admin_Help_Page(),
		);
		
		$tabs = apply_filters( 'wdp_admin_tabs', $tabs );

		uasort( $tabs, function ( $tab1, $tab2 ) {
			$priority1 = (int) isset( $tab1->priority ) ? $tab1->priority : 1000;
			$priority2 = (int) isset( $tab2->priority ) ? $tab2->priority : 1000;

			if ( $priority1 <= $priority2 ) {
				return - 1;
			} else {
				return 1;
			}
		} );

		$this->tabs = $tabs;
	}

	private function get_tabs() {
		return $this->tabs;
	}

	private function get_current_tab() {
		return isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : $this->get_default_tab();
	}

	private function get_default_tab() {
		return 'common';
	}

	public function enqueue_backend_assets() {
		$tab = $this->get_current_tab();

		// Enqueue script for handling the meta boxes
		wp_enqueue_script( 'wdp_postbox', WC_ADP_PLUGIN_URL . '/assets/js/postbox.js', array( 'jquery', 'jquery-ui-sortable' ), WC_ADP_VERSION );

		// jQuery UI Datepicker
		wp_enqueue_script( 'jquery-ui-datepicker' );

		// jQuery UI Datepicker styles
		wp_enqueue_style( 'wdp_jquery-ui', WC_ADP_PLUGIN_URL . '/assets/jquery-ui/jquery-ui.min.css', array(), '1.11.4' );

		// Enqueue Select2 related scripts and styles
		wp_enqueue_script( 'wdp_select2', WC_ADP_PLUGIN_URL . '/assets/js/select2/select2.full.min.js', array( 'jquery' ), '4.0.3' );
		wp_enqueue_style( 'wdp_select2', WC_ADP_PLUGIN_URL . '/assets/css/select2/select2.css', array(), '4.0.3' );

		if ( "options" !== $tab ) {
			// Enqueue jquery mobile related scripts and styles (for flip switch)
			wp_enqueue_script( 'jquery-mobile-scripts', WC_ADP_PLUGIN_URL . '/assets/jquery.mobile/jquery.mobile.custom.min.js', array( 'jquery' ) );
			wp_enqueue_style( 'jquery-mobile-styles', WC_ADP_PLUGIN_URL . '/assets/jquery.mobile/jquery.mobile.custom.structure.min.css' );
			wp_enqueue_style( 'jquery-mobile-theme-styles', WC_ADP_PLUGIN_URL . '/assets/jquery.mobile/jquery.mobile.custom.theme.css' );

			// Backend scripts
			wp_enqueue_script( 'wdp_settings-scripts', WC_ADP_PLUGIN_URL . '/assets/js/rules.js', array(
				'jquery',
				'jquery-ui-sortable',
				'wdp_select2',
			), WC_ADP_VERSION );
        } else {
			wp_enqueue_script( 'wdp_options-scripts', WC_ADP_PLUGIN_URL . '/assets/js/options.js', array( 'jquery'), WC_ADP_VERSION );

			wp_enqueue_style( 'wdp_options-styles', WC_ADP_PLUGIN_URL . '/assets/css/options.css', array(), WC_ADP_VERSION );
        }

		// Backend styles
		wp_enqueue_style( 'wdp_settings-styles', WC_ADP_PLUGIN_URL . '/assets/css/settings.css', array(), WC_ADP_VERSION );

		// DateTime Picker
		wp_enqueue_script('wdp_datetimepicker-scripts', WC_ADP_PLUGIN_URL . '/assets/datetimepicker/jquery.datetimepicker.full.min.js', array('jquery'));
		wp_enqueue_style('wdp_datetimepicker-styles', WC_ADP_PLUGIN_URL . '/assets/datetimepicker/jquery.datetimepicker.min.css', array() );

		if ( ! empty( $this->tabs[ $tab ] ) && method_exists( $this->tabs[ $tab ], 'get_tab_rules' ) ) {
			$rules = $this->tabs[$tab]->get_tab_rules();
		} else {
			$rules = array();
        }

		$paged = $this->tabs[$tab]->get_pagenum();

		$preloaded_lists = array(
			'payment_methods'   => WDP_Helpers::get_payment_methods(),
			'shipping_methods'  => WDP_Helpers::get_shipping_methods(),
			'countries'         => WDP_Helpers::get_countries(),
			'states'            => WDP_Helpers::get_states(),
			'user_roles'        => WDP_Helpers::get_user_roles(),
			'user_capabilities' => WDP_Helpers::get_user_capabilities(),
			'weekdays'          => WDP_Helpers::get_weekdays(),
        );

		foreach ( $preloaded_lists as $list_key => &$list ) {
			$list = apply_filters( 'wdp_preloaded_list_' . $list_key, $list );
        }

		$options = WDP_Helpers::get_settings();

		$wdp_data = array(
			'page'          => $tab,
			'rules'         => $rules,
			'titles'        => $this->get_filter_titles( $this->get_ids_for_filter_titles( $rules ) ),
			'labels'        => array(
				'select2_no_results'     => __( 'no results', 'advanced-dynamic-pricing-for-woocommerce' ),
				'confirm_remove_rule'    => __( 'Remove rule?', 'advanced-dynamic-pricing-for-woocommerce' ),
				'currency_symbol'        => get_woocommerce_currency_symbol(),
				'fixed_discount'         => __( 'Fixed discount for item', 'advanced-dynamic-pricing-for-woocommerce' ),
				'fixed_price'            => __( 'Fixed price for item', 'advanced-dynamic-pricing-for-woocommerce' ),
				'fixed_discount_for_set' => __( 'Fixed discount for set', 'advanced-dynamic-pricing-for-woocommerce' ),
				'fixed_price_for_set'    => __( 'Fixed price for set', 'advanced-dynamic-pricing-for-woocommerce' ),
			),
			'lists'         => $preloaded_lists,
			'selected_rule' => isset( $_GET['rule_id'] ) ? (int) $_GET['rule_id'] : - 1,
			'bulk_rule'     => WDP_Rule_Range_Adjustments_Qty_Based_Calculator::get_all_available_types(),
			'options'       => array(
				'enable_product_exclude' => isset( $options['allow_to_exclude_products'] ) ? $options['allow_to_exclude_products'] : false,
				'rules_per_page'         => isset( $options['rules_per_page'] ) ? $options['rules_per_page'] : false,
			),
            'paged' => $paged,
		);
		wp_localize_script( 'wdp_settings-scripts', 'wdp_data', $wdp_data );
	}

	public function get_ids_for_filter_titles( $rules ){
		// make array of filters splitted by type
		$filters_by_type = array(
			'products'              => array(),
			'giftable_products'              => array(),
			'product_tags'          => array(),
			'product_categories'    => array(),
			'product_category_slug'    => array(),
			'product_attributes'    => array(),
			'product_sku'           => array(),
			'product_custom_fields' => array(),
			'users_list'            => array(),
			'coupons'               => array(),
			'subscriptions'         => array(),
		);
		foreach ( array_keys( WDP_Helpers::get_custom_product_taxonomies() ) as $tax_name ) {
			$filters_by_type[ $tax_name ] = array();
		}
		$filters_by_type = apply_filters('wdp_ids_for_filter_titles', $filters_by_type, $rules);
		foreach ( $rules as $rule ) {
			foreach ( $rule['filters'] as $filter ) {
				if ( empty( $filter[ 'value' ] ) ) continue;
				$type  = $filter['type'];
				$value = $filter['value'];

				$filters_by_type[ $type ] = array_merge( $filters_by_type[ $type ], (array) $value );

				if ( isset( $filter['product_exclude']['values'] ) ) {
					foreach ( $filter['product_exclude']['values'] as $product_id ) {
						$filters_by_type['products'][] = $product_id;
					}
				}
			}

			if (isset($rule['get_products']['value']) ) {
				foreach ( $rule['get_products']['value'] as $filter ) {
					if ( ! isset( $filter[ 'value' ] ) ) continue;
					$type  = $filter['type'];
					$value = $filter['value'];

					$filters_by_type[ $type ] = array_merge( $filters_by_type[ $type ], (array) $value );
				}
			}

			if ( isset( $rule['bulk_adjustments']['selected_categories'] ) ) {
				$filters_by_type['product_categories'] = array_merge( $filters_by_type['product_categories'], (array) $rule['bulk_adjustments']['selected_categories'] );
			}

			if ( isset( $rule['conditions'] ) ) {
				foreach ($rule['conditions'] as $condition) {
					if ( $condition['type'] === 'specific' && isset( $condition['options'][2] ) ) {
						$value = $condition['options'][2];
						$filters_by_type[ 'users_list' ] = array_merge( $filters_by_type[ 'users_list' ], (array) $value );
					} elseif ( $condition['type'] === 'product_attributes' && isset( $condition['options'][2] ) ) {
						$value = $condition['options'][2];
						$filters_by_type[ 'product_attributes' ] = array_merge( $filters_by_type[ 'product_attributes' ], (array) $value );
					} elseif ( $condition['type'] === 'product_custom_fields' && isset( $condition['options'][2] ) ) {
						$value = $condition['options'][2];
						$filters_by_type[ 'product_custom_fields' ] = array_merge( $filters_by_type[ 'product_custom_fields' ], (array) $value );
					} elseif ( $condition['type'] === 'product_categories' && isset( $condition['options'][2] ) ) {
						$value = $condition['options'][2];
						$filters_by_type[ 'product_categories' ] = array_merge( $filters_by_type[ 'product_categories' ], (array) $value );
					} elseif ( $condition['type'] === 'product_category_slug' && isset( $condition['options'][2] ) ) {
						$value = $condition['options'][2];
						$filters_by_type[ 'product_category_slug' ] = array_merge( $filters_by_type[ 'product_category_slug' ], (array) $value );
					} elseif ( $condition['type'] === 'product_tags' && isset( $condition['options'][2] ) ) {
						$value = $condition['options'][2];
						$filters_by_type[ 'product_tags' ] = array_merge( $filters_by_type[ 'product_tags' ], (array) $value );
					} elseif ( $condition['type'] === 'products' && isset( $condition['options'][2] ) ) {
						$value = $condition['options'][2];
						$filters_by_type[ 'products' ] = array_merge( $filters_by_type[ 'products' ], (array) $value );
					} elseif ( $condition['type'] === 'cart_coupons' && isset( $condition['options'][1] ) ) {
						$value = $condition['options'][1];
						$filters_by_type[ 'coupons' ] = array_merge( $filters_by_type[ 'coupons' ], (array) $value );
					} elseif ( $condition['type'] === 'subscriptions' && isset( $condition['options'][1] ) ) {
						$value = $condition['options'][1];
						$filters_by_type[ 'subscriptions' ] = array_merge( $filters_by_type[ 'subscriptions' ], (array) $value );
					} elseif ( in_array( $condition['type'], array_keys( WDP_Helpers::get_custom_product_taxonomies() ) ) && isset( $condition['options'][2] ) ) {
						$value                                 = $condition['options'][2];
						$filters_by_type[ $condition['type'] ] = array_merge( $filters_by_type[ $condition['type'] ], (array) $value );
					}
				}

			}

		}
		return $filters_by_type;
    }




	/**
	 * Retrieve from get_ids_for_filter_titles function filters all products, tags, categories, attributes and return titles
	 *
	 * @param array $filters_by_type
	 *
	 * @return array
	 */
	private function get_filter_titles( $filters_by_type ) {
		$result = array();

		// type 'products'
		$result['products'] = array();
		foreach ( $filters_by_type['products'] as $id ) {
			$result['products'][ $id ] = '#' . $id . ' ' . WDP_Helpers::get_product_title( $id );
		}

		// type 'giftable_products'
		$result['giftable_products'] = array();
		foreach ( $filters_by_type['giftable_products'] as $id ) {
			$result['giftable_products'][ $id ] = '#' . $id . ' ' . WDP_Helpers::get_product_title( $id );
		}

		$result['product_sku'] = array();
		foreach ( $filters_by_type['product_sku'] as $sku ) {
            $result['product_sku'][ $sku ] = 'SKU: ' . $sku;
		}

		// type 'product_tags'
		$result['product_tags'] = array();
		foreach ( $filters_by_type['product_tags'] as $id ) {
			$result['product_tags'][ $id ] = WDP_Helpers::get_tag_title( $id );
		}

		// type 'product_categories'
		$result['product_categories'] = array();
		foreach ( $filters_by_type['product_categories'] as $id ) {
			$result['product_categories'][ $id ] = WDP_Helpers::get_category_title( $id );
		}

		// type 'product_category_slug'
		$result['product_category_slug'] = array();
		foreach ( $filters_by_type['product_category_slug'] as $slug ) {
			$result['product_category_slug'][ $slug ] = __('Slug', 'advanced-dynamic-pricing-for-woocommerce' ) . ': ' . $slug;
		}

		// product_taxonomies
		foreach ( WDP_Helpers::get_custom_product_taxonomies() as $tax ) {
			$result[$tax->name] = array();
			foreach ( $filters_by_type[$tax->name] as $id ) {
				$result[$tax->name][ $id ] = WDP_Helpers::get_product_taxonomy_term_title( $id, $tax->name );
			}
        }

		// type 'product_attributes'
		$attributes = WDP_Helpers::get_product_attributes( array_unique( $filters_by_type['product_attributes'] ) );
		$result['product_attributes'] = array();
		foreach ( $attributes as $attribute ) {
			$result['product_attributes'][ $attribute['id'] ] = $attribute['text'];
		}
		
		// type 'product_custom_fields'
		$customfields = array_unique( $filters_by_type['product_custom_fields'] ); // use as is!
		$result['product_custom_fields'] = array();
		foreach ( $customfields as $customfield ) {
			$result['product_custom_fields'][ $customfield ] = $customfield;
		}

		// type 'users_list'
		$attributes = WDP_Helpers::get_users( $filters_by_type['users_list'] );
		$result['users_list'] = array();
		foreach ( $attributes as $attribute ) {
			$result['users_list'][ $attribute['id'] ] = $attribute['text'];
		}
		
		// type 'cart_coupons'
		$result['coupons'] = array();
		foreach ( array_unique( $filters_by_type['coupons'] ) as $code ) {
			$result['coupons'][ $code ] = $code;
		}
		
		// type 'subscriptions' 
		$result['subscriptions'] = array();
		foreach ( $filters_by_type['subscriptions'] as $id ) {
			$result['subscriptions'][ $id ] = '#' . $id . ' ' . WDP_Helpers::get_product_title( $id );
		}


		return apply_filters( 'wdp_filter_titles', $result );
	}

	public function script_loader_src( $src, $handle ) {
		// don't load ANY select2.js / select2.min.js  and OUTDATED select2.full.js
		if ( ! preg_match( '/\/select2\.full\.js\?ver=[1-3]/', $src ) && ! preg_match( '/\/select2\.min\.js/', $src ) && ! preg_match( '/\/select2\.js/', $src ) ) {
			return $src;
		}

		return "";
	}

	public function notify_rule_disabled() {
		$disabled_rules = get_option( self::$disabled_rules_option_name, array() );

		if ( $disabled_rules ) {
			$disabled_count_common    = 0;
			$disabled_count_exclusive = 0;
			foreach ( $disabled_rules as $rule ) {
				$is_exclusive = $rule['is_exclusive'];

				if ( $is_exclusive ) {
					$disabled_count_exclusive ++;
				} else {
					$disabled_count_common ++;
				}
			}

			$rule_edit_url      = add_query_arg( array(
				'page'        => 'wdp_settings',
				'from_notify' => '1'
			), admin_url( 'admin.php' ) );
			$common_edit_url    = add_query_arg( 'tab', 'common', $rule_edit_url );
			$exclusive_edit_url = add_query_arg( 'tab', 'exclusive', $rule_edit_url );

			$format = "<p>%s %s <a href='%s'>%s</a></p>";

			if ( $disabled_count_common ) {
				$notice_message = "";
				$notice_message .= '<div class="notice notice-success is-dismissible">';
				if ( 1 === $disabled_count_common ) {
					$notice_message .= sprintf( $format, "", __( "The common rule was turned off, it was running too slow.", 'advanced-dynamic-pricing-for-woocommerce' ), $common_edit_url, __( "Edit rule", 'advanced-dynamic-pricing-for-woocommerce' ) );
                } else {
					$notice_message .= sprintf( $format, $disabled_count_common, __( "common rules were turned off, it were running too slow.", 'advanced-dynamic-pricing-for-woocommerce' ), $common_edit_url, __( "Edit rule", 'advanced-dynamic-pricing-for-woocommerce' ) );
				}

				$notice_message .= '</div>';

				echo $notice_message;
			}

			if ( $disabled_count_exclusive ) {
				$notice_message = "";
				$notice_message .= '<div class="notice notice-success is-dismissible">';
				if ( 1 === $disabled_count_exclusive ) {
					$notice_message .= sprintf( $format, "", __( "The exclusive rule was turned off, it was running too slow.", 'advanced-dynamic-pricing-for-woocommerce' ), $exclusive_edit_url, __( "Edit rule", 'advanced-dynamic-pricing-for-woocommerce' ) );
				} else {
					$notice_message .= sprintf( $format, $disabled_count_exclusive, __( "exclusive rules were turned off, it were running too slow.", 'advanced-dynamic-pricing-for-woocommerce' ), $exclusive_edit_url, __( "Edit rule", 'advanced-dynamic-pricing-for-woocommerce' ) );
				}
				$notice_message .= '</div>';

				echo $notice_message;
			}
		}
	}

}