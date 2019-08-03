<?php


if ( ! interface_exists( 'WPDesk_Plugin_Disabler' ) ) {
	/**
	 * Can disable plugin before it's loaded
	 */
	interface WPDesk_Plugin_Disabler {

		/** @return void */
		public function disable();
	}
}