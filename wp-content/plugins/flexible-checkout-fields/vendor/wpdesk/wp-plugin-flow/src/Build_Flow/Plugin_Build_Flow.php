<?php

if (!interface_exists('WPDesk_Plugin_Build_Flow')) {

	/**
	 * Plugin is built
	 */
	interface WPDesk_Plugin_Build_Flow {
		public function run();
	}
}