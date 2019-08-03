<?php

namespace WPDesk\Helper;

use Psr\Log\LoggerInterface;
use WPDesk\Helper\Debug\LibraryDebug;
use WPDesk\Helper\Integration\LicenseIntegration;
use WPDesk\Helper\Integration\LogsIntegration;
use WPDesk\Helper\Integration\SettingsIntegration;
use WPDesk\Helper\Integration\TrackerIntegration;
use WPDesk\Helper\Logs\LibraryInfoProcessor;
use WPDesk\Helper\Page\LibraryDebugPage;
use WPDesk\Helper\Page\SettingsPage;
use WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Manager all functionalities understood as helper
 *
 * @package WPDesk\Helper
 */
class HelperAsLibrary implements Hookable {
	const LIBRARY_TEXT_DOMAIN = 'wpdesk-helper';
	const MAIN_WPDESK_MENU_POSITION = 99.99941337;

	const PRIORITY_AFTER_WPDESK_MENU_REMOVAL = 15;
	const PRIORITY_AFTER_ALL = 200;

	/**
	 * Prevents attaching hooks more than once when used in many plugins
	 *
	 * @var bool
	 */
	private static $already_hooked;

	/** @var LoggerInterface */
	private static $logger;

	/** @var \WPDesk_Tracker */
	private static $tracker;

	public function hooks() {
		if ( is_admin() && ! self::$already_hooked ) {
			self::$already_hooked = true;

			$this->initialize();
		}
	}

	private function initialize() {
		$this->init_translations();
		$this->add_wpdesk_menu();

		$subscription_integration = new LicenseIntegration();
		$subscription_integration->hooks();

		$settingsPage         = new SettingsPage();
		$settings_integration = new SettingsIntegration( $settingsPage );
		$tracker_integration  = new TrackerIntegration( $settingsPage );
		$logger_integration   = new LogsIntegration( $settingsPage );

		$settings_integration->add_hookable( $logger_integration );
		$settings_integration->add_hookable( $tracker_integration );
		$settings_integration->hooks();

		self::$tracker = $tracker_integration->get_tracker();
		self::$logger  = $logger_integration->get_logger();

		$this->clean_wpdesk_menu();

		$library_debug_info = new LibraryDebug();
		(new LibraryDebugPage($library_debug_info))->hooks();
		self::$logger->pushProcessor(new LibraryInfoProcessor($library_debug_info));
	}

	/**
	 * Adds text domain used in a library
	 */
	private function init_translations() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else { // before WP 5.0 compatibility
			$locale = get_locale();
		}
		$locale = apply_filters( 'plugin_locale', $locale, self::LIBRARY_TEXT_DOMAIN );

		$mo_file_tracker = __DIR__ . '/../lang/' . self::LIBRARY_TEXT_DOMAIN . '-' . $locale . '.mo';
		if ( file_exists( $mo_file_tracker ) ) {
			load_textdomain( self::LIBRARY_TEXT_DOMAIN, $mo_file_tracker );
		}
		$tracker_domain = 'wpdesk-tracker';
		$mo_file_tracker = __DIR__ . '/../lang/' . $tracker_domain . '-' . $locale . '.mo';
		if ( file_exists( $mo_file_tracker ) ) {
			load_textdomain( $tracker_domain, $mo_file_tracker );
		}
	}

	/**
	 * Adds WP Desk to main menu
	 */
	private function add_wpdesk_menu() {
		add_action( 'admin_menu',
			function () {
				$this->handle_add_wpdesk_menu();
			},
			self::PRIORITY_AFTER_WPDESK_MENU_REMOVAL );
	}

	/**
	 * @return void
	 */
	private function handle_add_wpdesk_menu() {
		add_menu_page( 'WP Desk',
			'WP Desk',
			'manage_options',
			'wpdesk-helper',
			function () {
			},
			'dashicons-controls-play',
			self::MAIN_WPDESK_MENU_POSITION );
	}

	/**
	 * Removed unnecessary submenu item for WP Desk
	 */
	private function clean_wpdesk_menu() {
		add_action( 'admin_menu',
			static function () {
				remove_submenu_page( 'wpdesk-helper', 'wpdesk-helper' );
			},
			self::PRIORITY_AFTER_ALL );
	}

	/**
	 * @return \WPDesk_Tracker
	 */
	public function get_tracker() {
		$this->exception_if_not_initialized();

		return self::$tracker;
	}

	/**
	 * @throws \RuntimeException
	 */
	private function exception_if_not_initialized() {
		if ( ! self::$already_hooked ) {
			throw new \RuntimeException( "Helper library is not initialized" );
		}
	}

	/**
	 * @return LoggerInterface
	 */
	public function get_logger() {
		$this->exception_if_not_initialized();

		return self::$logger;
	}
}