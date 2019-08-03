<?php

if ( ! class_exists( 'WPDesk_Plugin_Init_Flow_Plugins_Loaded' ) ) {
	require_once __DIR__ . '/Plugin_Init_Flow_Plugins_Loaded.php';
}

class WPDesk_Plugin_Init_Flow_Factory {
	public function __construct( $plugin_class_name, $plugin_version ) {
	}

	/**
	 * @param WPDesk_Wordpress_Class_Loader $loader
	 * @param string                        $plugin_version
	 * @param string                        $plugin_release_timestamp
	 * @param string                        $plugin_name
	 * @param string                        $plugin_class_name
	 * @param string                        $plugin_text_domain
	 * @param string                        $plugin_dir
	 * @param string                        $plugin_file
	 * @param array                         $requirements
	 *
	 * @return WPDesk_Plugin_Init_Flow_Plugins_Loaded
	 */
	public function create_flow(
		WPDesk_Wordpress_Class_Loader $loader,
		$plugin_version,
		$plugin_release_timestamp,
		$plugin_name,
		$plugin_class_name,
		$plugin_text_domain,
		$plugin_dir,
		$plugin_file,
		$requirements,
		$product_id = null
	) {

		$flow = new WPDesk_Plugin_Init_Flow_Plugins_Loaded(
			$loader,
			$plugin_version,
			$plugin_release_timestamp,
			$plugin_name,
			$plugin_class_name,
			$plugin_text_domain,
			$plugin_dir,
			$plugin_file,
			$requirements,
			$product_id
		);

		return $flow;
	}
}