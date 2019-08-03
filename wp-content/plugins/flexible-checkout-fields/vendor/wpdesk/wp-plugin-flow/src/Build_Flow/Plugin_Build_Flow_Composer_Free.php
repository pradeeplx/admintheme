<?php

use WPDesk\PluginBuilder\BuildDirector\LegacyBuildDirector;
use WPDesk\PluginBuilder\Builder\InfoBuilder;

if ( ! class_exists( 'WPDesk_Plugin_Build_Flow' ) ) {
	require_once dirname( __FILE__ ) . '/Plugin_Build_Flow.php';
}

/**
 * Plugin is built. Class autoloading through composer for free plugins.
 */
class WPDesk_Plugin_Build_Flow_Composer_Free implements WPDesk_Plugin_Build_Flow {
	/** @var WPDesk_Plugin_Info */
	protected $plugin_info;

	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}

	public function run() {
		$builder        = new InfoBuilder( $this->plugin_info );
		$build_director = new LegacyBuildDirector( $builder );
		$build_director->build_plugin();
	}
}