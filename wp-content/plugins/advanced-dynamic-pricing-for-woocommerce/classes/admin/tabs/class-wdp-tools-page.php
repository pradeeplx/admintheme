<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Admin_Tools_Page extends WDP_Admin_Abstract_Page {
	public $priority = 50;
	protected $tab = 'tools';
	protected $groups;

	public function __construct() {
		$this->title = 	__( 'Tools', 'advanced-dynamic-pricing-for-woocommerce' );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	protected function is_assoc( $arr ) {
		return array_keys( $arr ) !== range( 0, count( $arr ) - 1 );
	}

	public function action() {
		if ( isset( $_POST['wdp-import'] ) && ! empty( $_POST['wdp-import-data'] ) ) {
			$data = json_decode( $_POST['wdp-import-data'], true );
			if ( empty( $data ) ) {
				$data = json_decode( str_replace( '\"', '"', $_POST['wdp-import-data'] ), 1 );
			}

			$this->action_groups( $data );

			wp_redirect( $_SERVER['HTTP_REFERER'] );
		}
	}

	public function render() {
		$this->prepare_export_groups();
		$groups = $this->groups;

		$this->render_template(
			WC_ADP_PLUGIN_PATH . 'views/tabs/tools.php',
			compact( 'groups' )
		);
	}

	protected function action_groups( $data ) {
		if ( ! $this->action_options_group( $data ) ) {
			$this->action_rules_group( $data );
		};
	}

	protected function action_options_group( $data ) {
		if ( $this->is_assoc( $data ) && $data ) {
			WDP_Helpers::set_settings( filter_var_array( $data, WDP_Helpers::get_validate_filters() ) );
			return true;
		}

		return false;
	}

	protected function prepare_export_groups() {
		$this->prepare_options_group();
		$this->prepare_export_group();
	}

	protected function prepare_options_group(){
		$options = WDP_Helpers::get_settings();

		$options_group = array(
			'label' => __( 'Options', 'advanced-dynamic-pricing-for-woocommerce' ),
			'data'  => $options,
		);

		$this->groups['options'] = array(
			'label' => __( 'Options', 'advanced-dynamic-pricing-for-woocommerce' ),
			'items' => array( 'options' => $options_group ),
		);
	}

	public function admin_enqueue_scripts() {
		$is_settings_page = isset( $_GET['page'] ) && $_GET['page'] == 'wdp_settings';
		// Load backend assets conditionally
		if ( ! $is_settings_page ) {
			return;
		}
		wp_enqueue_script( 'wdp-tools', WC_ADP_PLUGIN_URL . '/assets/js/tools.js', array(), WC_ADP_VERSION, true );
	}

	protected function action_rules_group($data) {
		//array of rules ? each rule is array too!
		if ( is_array( $data ) && is_array( $data[0] ) && ! $this->is_assoc( $data ) ) {
			WDP_Importer::import( $data, $_POST['wdp-import-data-reset-rules'] );

			return true;
		}

		return false;
	}

	protected function prepare_export_group() {
		$export_items = array();

		$rules = WDP_Database::get_rules();

		foreach ( $rules as &$rule ) {
			unset( $rule['id'] );

			if ( ! empty( $rule['filters'] ) ) {
				foreach ( $rule['filters'] as &$item ) {
					$item['value'] = isset( $item['value'] ) ? $item['value'] : array();
					$item['value'] = $this->convert_elements_from_id_to_name( $item['value'], $item['type'] );
				}
				unset( $item );
			}

			if ( ! empty( $rule['get_products']['value'] ) ) {
				foreach ( $rule['get_products']['value'] as &$item ) {
					$item['value'] = isset( $item['value'] ) ? $item['value'] : array();
					$item['value'] = $this->convert_elements_from_id_to_name( $item['value'], $item['type'] );
				}
				unset( $item );
			}

			if ( ! empty( $rule['conditions'] ) ) {
				foreach ( $rule['conditions'] as &$item ) {
					foreach ( $item['options'] as &$option_item ) {
						if ( is_array( $option_item ) ) {
							$converted = null;
							try {
								$converted = $this->convert_elements_from_id_to_name( $option_item, $item['type'] );
							} catch ( Exception $e ) {

							}

							if ( $converted ) {
								$option_item = $converted;
							}
						}
					}
				}
				unset( $item );
			}
		}
		unset( $rule );

		$export_items['all'] = array(
			'label' => __( 'All', 'advanced-dynamic-pricing-for-woocommerce' ),
			'data'  => $rules,
		);

		foreach ( $rules as $rule ) {
			$export_items[] = array(
				'label' => "{$rule['title']}",
				'data'  => array( $rule ),
			);
		}

		$this->groups['rules'] = array(
			'label' => __( 'Rules', 'advanced-dynamic-pricing-for-woocommerce' ),
			'items' => $export_items
		);
	}

	private function convert_elements_from_id_to_name( $items, $type ) {
		if ( empty( $items ) ) {
			return $items;
		}
		foreach ( $items as &$value ) {
			if ( 'products' === $type ) {
				$value = WDP_Helpers::get_product_title( $value );
			} elseif ( 'product_categories' === $type ) {
				$value = WDP_Helpers::get_category_title( $value );
			} elseif ( 'product_tags' === $type ) {
				$value = WDP_Helpers::get_tag_title( $value );
			} elseif ( 'product_attributes' === $type ) {
				$value = WDP_Helpers::get_attribute_title( $value );
			}
		}

		return $items;
	}
}