<?php

namespace WPDesk\Helper\Integration;

use WPDesk\Helper\Page\SettingsPage;
use WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\PluginBuilder\Plugin\HookableCollection;
use WPDesk\PluginBuilder\Plugin\HookableParent;

/**
 * Integrates WP Desk main settings page with WordPress
 *
 * @package WPDesk\Helper
 */
class SettingsIntegration implements Hookable, HookableCollection {
	use HookableParent;

	/** @var SettingsPage */
	private $settings_page;

	public function __construct( SettingsPage $settingsPage ) {
		$this->add_hookable( $settingsPage );
	}

	/**
	 * @return void
	 */
	public function hooks() {
		$this->hooks_on_hookable_objects();
	}
}
