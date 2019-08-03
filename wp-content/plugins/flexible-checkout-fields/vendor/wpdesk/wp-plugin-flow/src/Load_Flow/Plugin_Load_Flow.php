<?php

if (!interface_exists('WPDesk_Plugin_Load_Flow')) {

	/**
	 * Load flow is a flow to load all low level dependency (i.e. composer) for plugin to load.
	 *
	 * All class initialization should be through WPDesk_Wordpress_Class_Loader
	 */
	interface WPDesk_Plugin_Load_Flow {
		public function register_requirements();

		public function run();
	}
}
