<?php

/**
 * Class to guard that given plugin is compatible with running plugins
 */
class WPDesk_Plugin_Compatibility_Guard_V2 {
	/** @var string */
	private $plugin_file;

	/** @var WPDesk_Plugin_Compatibility_Checker */
	private $checker;

	/** @var bool */
	private $is_conflict_found;

	public function __construct(
		$plugin_file,
		WPDesk_Plugin_Compatibility_Checker $checker
	) {
		$plugin_dir              = basename( plugin_dir_path( $plugin_file ) );
		$this->plugin_file       = $plugin_dir . DIRECTORY_SEPARATOR . basename( $plugin_file );
		$this->checker           = $checker;
		$this->is_conflict_found = false;
	}

	/**
	 * Get plugin version using WordPress methods
	 *
	 * @return string
	 */
	private function get_current_plugin_version() {
		$plugin_data = get_file_data( WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->plugin_file,
			[
				'Version' => 'Version',
			],
			'plugin' );

		return $plugin_data['Version'];
	}

	/**
	 * Get remembered in cache last valid plugin version
	 *
	 * @return string
	 */
	private function get_last_version() {
		return get_transient( $this->get_last_version_cache_key() );
	}

	/**
	 * Get cache key for version
	 *
	 * @return string
	 */
	private function get_last_version_cache_key() {
		return $this->plugin_file . '_version';
	}

	/**
	 * Update version cache
	 *
	 * @return void
	 */
	private function touch_last_version() {
		$version = $this->get_current_plugin_version();
		set_transient( $this->get_last_version_cache_key(), $version );
	}

	/**
	 * Get list of active plugins
	 *
	 * @return string[]
	 */
	private function get_active_plugins() {
		return get_option( 'active_plugins' );
	}

	/**
	 * Check if versions are compatible using checker
	 *
	 * @return bool
	 */
	private function is_compatible() {
		return $this->checker->is_plugin_compatible( $this->plugin_file, $this->get_active_plugins() );
	}

	/**
	 * Clear version cache
	 */
	private function clear_cache() {
		delete_transient( $this->get_last_version_cache_key() );
	}

	/**
	 * Check if checked plugin version changed
	 *
	 * @return bool
	 */
	private function something_changes_in_plugin() {
		$current_version = $this->get_current_plugin_version();
		$last_version    = $this->get_last_version();

		return $current_version !== $last_version;
	}

	/**
	 * Check if plugin will be compatible with already active plugins and disable it if not.
	 */
	public function guard_compatibility() {
		register_activation_hook( $this->plugin_file,
			function () {
				$this->clear_cache();
			} );

		register_deactivation_hook( $this->plugin_file,
			function () {
				$this->clear_cache();
			} );


		if ( $this->something_changes_in_plugin() ) {
			if ( ! $this->is_compatible() ) {
				$this->is_conflict_found = true;
			} else {
				$this->touch_last_version();
			}
		}
	}

	/**
	 * Guard found an error using checker
	 *
	 * @return bool
	 */
	public function is_compatibility_error_found() {
		return $this->is_conflict_found;
	}
}