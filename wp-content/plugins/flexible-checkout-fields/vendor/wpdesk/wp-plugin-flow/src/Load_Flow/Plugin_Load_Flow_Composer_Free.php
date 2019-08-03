<?php

if ( ! class_exists( 'WPDesk_Plugin_Load_Flow' ) ) {
	require_once dirname( __FILE__ ) . '/Plugin_Load_Flow.php';
}

if ( ! class_exists( 'WPDesk_Plugin_Load_Flow_Composer' ) ) {
	require_once dirname( __FILE__ ) . '/Plugin_Load_Flow_Composer.php';
}

/**
 * Load flow is a flow to load all low level dependency (i.e. composer) for plugin to load.
 */
class WPDesk_Plugin_Load_Flow_Composer_Free extends WPDesk_Plugin_Load_Flow_Composer {

	public function run() {
		$plugin_loader = $this->prepare_plugin_loader();

		$this->loader->get_class_name( 'WPDesk_Loader_Manager_Factory' );

		$loader_manager = WPDesk_Loader_Manager_Factory::get_manager_instance();
		$loader_manager->attach_loader( $plugin_loader );
	}
}