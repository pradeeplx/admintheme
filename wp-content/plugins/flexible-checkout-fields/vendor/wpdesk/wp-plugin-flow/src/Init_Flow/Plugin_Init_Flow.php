<?php

if (!interface_exists('WPDesk_Plugin_Init_Flow')) {

	/**
	 * Init flow is for requirements check and should work with any version of PHP.
	 * In this flow it's decided if plugin can be loaded at all and what further flow to load if so.
	 *
	 * All class initialization should be through WPDesk_Wordpress_Class_Loader
	 */
	interface WPDesk_Plugin_Init_Flow {
		/**
		 * @return void
		 */
		public function run();

		/**
		 * @param string $class_name
		 *
		 * @return void
		 */
		public function set_load_flow_class( $class_name );

		/**
		 * @param string $class_name
		 *
		 * @return void
		 */
		public function set_build_flow_class( $class_name );
	}
}