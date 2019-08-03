<?php

if ( ! interface_exists( 'WPDesk_Plugin_Disabler' ) ) {
	require_once dirname( __FILE__ ) . '/Plugin_Disabler.php';
}

/**
 * Can remember is wanted to disable certain plugin
 */
class WPDesk_Plugin_Disabler_Variable implements WPDesk_Plugin_Disabler {
	const HOOK_ADMIN_NOTICES_ACTION = 'admin_notices';

	/** @var string */
	private $plugin_name;

	/** @var bool Tried to disable? */
	private $set_disabled = false;

	/**
	 * @param string $plugin_name
	 */
	public function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name;
	}

	/**
	 * @return void
	 */
	public function disable() {
		$this->set_disabled = true;
	}

	/**
	 * @return bool
	 */
	public function get_disable() {
		return $this->set_disabled;
	}
}