<?php

namespace WPDesk\Helper\Integration;


use Monolog\Logger;
use WPDesk\Helper\Page\SettingsPage;
use WPDesk\Logger\WPDeskLoggerFactory;
use WPDesk\Notice\Notice;
use WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Integrates WP Desk log with WordPress
 *
 * @package WPDesk\Helper
 */
class LogsIntegration implements Hookable {
	const PRIORITY_AFTER_SETTINGS = 30;
	const DEBUG_LOG_SETTING_KEY = 'debug_log';

	/** @var SettingsPage */
	private $settings_page;

	/** @var Logger */
	private $logger;

	public function __construct( SettingsPage $settings_page ) {
		$this->settings_page = $settings_page;
	}

	public function hooks() {
		add_action( 'admin_init',
			function () {
				$this->handle_page_settings_logs_section();
			} );
		$this->wpdesk_log_init();
		$this->maybe_show_logging_notice();
	}

	private function handle_page_settings_logs_section() {
		add_settings_section( 'wpdesk_helper_debug',
			__( 'Debug', 'wpdesk-helper' ),
			null,
			$this->settings_page->get_page_name() );
		add_settings_field( self::DEBUG_LOG_SETTING_KEY,
			__( 'WP Desk Debug Log', 'wpdesk-helper' ),
			function () {
				$this->handle_render_page_settings_log_section();
			},
			$this->settings_page->get_page_name(),
			'wpdesk_helper_debug' );
	}

	private function handle_render_page_settings_log_section() {
		$options = $this->settings_page->get_saved_options();
		if ( empty( $options[ self::DEBUG_LOG_SETTING_KEY ] ) ) {
			$options[ self::DEBUG_LOG_SETTING_KEY ] = '0';
		}
		?>
        <input type="checkbox" id="wpdesk_helper_options[<?php echo self::DEBUG_LOG_SETTING_KEY; ?>]"
               name="wpdesk_helper_options[<?php echo self::DEBUG_LOG_SETTING_KEY; ?>]"
               value="1" <?php checked( 1, $options[ self::DEBUG_LOG_SETTING_KEY ], true ); ?>>
        <label for="wpdesk_helper_options[<?php echo self::DEBUG_LOG_SETTING_KEY; ?>]"><?php _e( 'Enable',
				'wpdesk-helper' ); ?></label>
        <p class="description" id="admin-email-description">
			<?php echo sprintf( __( 'Writes error log to %s.', 'wpdesk-helper' ),
				'<a target="_blank" href="' . content_url( 'uploads/wpdesk-logs/wpdesk_debug.log' ) . '">' . content_url( 'uploads/wpdesk-logs/wpdesk_debug.log' ) . '</a>' ); ?>
        </p>
		<?php
	}

	private function is_logger_active() {
		$options = $this->settings_page->get_saved_options();

		return isset( $options[ self::DEBUG_LOG_SETTING_KEY ] ) && '1' === $options[ self::DEBUG_LOG_SETTING_KEY ];
	}

	private function wpdesk_log_init() {
		$logger_factory = new WPDeskLoggerFactory();

		/** support for very old FS*/
		if ( property_exists( $logger_factory, 'shouldLoggerBeActivated' ) ) {
			$logger_factory::$shouldLoggerBeActivated = $this->is_logger_active();
			$this->logger                             = $logger_factory->createWPDeskLogger();
		}
	}

	private function maybe_show_logging_notice() {
		$debug_log_enabled = $this->is_logger_active();
		if ( $debug_log_enabled ) {
			if ( apply_filters( 'wpdesk_helper_show_log_notices_library', true ) ) {
				new Notice(
					sprintf(
					// Translators: link.
						__(
							'WP Desk Debug Log is enabled. %1$sPlease disable it after testing%2$s.',
							'wpdesk-helper'
						),
						'<a href="' . admin_url( 'admin.php?page=wpdesk-helper-settings' ) . '">',
						'</a>'
					),
					Notice::NOTICE_TYPE_INFO
				);
			}
		}
	}

	public function get_logger() {
		return $this->logger;
	}
}
