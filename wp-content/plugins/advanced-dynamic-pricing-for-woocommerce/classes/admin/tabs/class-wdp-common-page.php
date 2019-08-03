<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Admin_Common_Page extends WDP_Admin_Abstract_Rules_Page {
	public $title;
	public $priority = 10;
	protected $tab = 'common';

	public function __construct() {
		$this->title = __( 'Rules', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	public function action() {

	}

	protected function get_template_path() {
		return WC_ADP_PLUGIN_PATH . 'views/tabs/common.php';
	}

	protected function make_get_rules_args() {
		$args = parent::make_get_rules_args();
		$args['exclusive'] = 0;

		return $args;
	}

}