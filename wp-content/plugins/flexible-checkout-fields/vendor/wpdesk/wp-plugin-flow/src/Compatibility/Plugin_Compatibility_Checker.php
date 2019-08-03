<?php

/**
 * Class tries to check used composer.lock files for major incompatibilities between plugins
 */
class WPDesk_Plugin_Compatibility_Checker {

	const COMPOSER_KEY_VERSION = 'version';
	const COMPOSER_KEY_NAME = 'name';
	const COMPOSER_KEY_PACKAGES = 'packages';

	const ALWAYS_COMPATIBLE_VERSION = 'dev-master';

	const VERSION_DELIMITER = '.';

	const INTERNAL_VERSION_KEY = 'version';
	const INTERNAL_PLUGIN_KEY = 'plugin';

	/** @var WPDesk_Conflict_Info[] */
	private $compatibility_errors;

	public function __construct() {
		$this->compatibility_errors = [];
	}

	/**
	 * Checks if two versions are compatible.
	 * 3.1.1 and 3.3.1 are compatible.
	 * 3.1.1 and 4.0.0 are not compatible.
	 *
	 * Ignore self::ALWAYS_COMPATIBLE_VERSION versions.
	 *
	 * @param string $version1
	 * @param string $version2
	 *
	 * @return bool
	 */
	private function is_versions_compatible( $version1, $version2 ) {
		return ( explode( self::VERSION_DELIMITER, $version1 )[0] === explode( self::VERSION_DELIMITER, $version2 )[0] )
		       || $version1 === self::ALWAYS_COMPATIBLE_VERSION || $version2 === self::ALWAYS_COMPATIBLE_VERSION;
	}

	/**
	 * Checks if currently loading libs are compatible with already loaded
	 *
	 * @param array $currently_loading_libs Libs in format from get_plugin_libs_versions()
	 * @param array $already_loaded_libs Libs in format from get_plugin_libs_versions()
	 *
	 * @return bool
	 */
	private function is_compatible( array $currently_loading_libs, array $already_loaded_libs ) {
		foreach ( $currently_loading_libs as $name => $needed_version ) {
			$library_info = isset( $already_loaded_libs[ $name ] ) ? $already_loaded_libs[ $name ] : [ [ self::INTERNAL_VERSION_KEY => $needed_version ] ];
			foreach ( $library_info as $version ) {
				if ( ! $this->is_versions_compatible( $needed_version, $version[ self::INTERNAL_VERSION_KEY ] ) ) {
					$this->add_compatibility_error( $version[ self::INTERNAL_PLUGIN_KEY ], $name, $needed_version, $version[ self::INTERNAL_VERSION_KEY ] );
				}
			}
		}

		return count( $this->get_compatibility_errors() ) === 0;
	}

	/**
	 * Adds error info about conflict to compatibility_errors table
	 *
	 * @param string $conflicted_plugin
	 * @param string $library_name
	 * @param string $required_version
	 * @param string $version_found
	 *
	 * @return WPDesk_Conflict_Info
	 */
	private function add_compatibility_error( $conflicted_plugin, $library_name, $required_version, $version_found ) {
		$this->compatibility_errors[ $library_name ] = $info = new WPDesk_Conflict_Info(
			$this->get_plugin_nice_name_from_file( $conflicted_plugin ),
			$library_name,
			$required_version,
			$version_found
		);

		return $info;
	}

	/**
	 * Returns
	 *
	 * @param string $plugin_wp_name
	 *
	 * @return string
	 */
	private function get_plugin_nice_name_from_file( $plugin_wp_name ) {
		$plugin_data = get_file_data( WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $plugin_wp_name,
			[
				'Plugin Name' => 'Plugin Name',
			],
			'plugin' );

		return $plugin_data['Plugin Name'];
	}

	/**
	 * Get libs version for given plugin
	 *
	 * @param string $plugin_name like 'some-dir/plugin-file.php'
	 *
	 * @return array versions in format [lib name][]
	 */
	private function get_plugin_libs_versions( $plugin_name ) {
		$plugin_dir                = str_replace( basename( $plugin_name ), '', $plugin_name );
		$plugin_composer_lock_path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $plugin_dir . 'composer.lock';
		if ( ! file_exists( $plugin_composer_lock_path ) || ! is_readable( $plugin_composer_lock_path ) ) {
			return [];
		}

		try {
			$composer_lock_data = json_decode( file_get_contents( $plugin_composer_lock_path ), true );

			$library_versions = array_reduce( $composer_lock_data[ self::COMPOSER_KEY_PACKAGES ],
				static function ( $carry, $package_info ) {
					$carry[ $package_info[ self::COMPOSER_KEY_NAME ] ] = $package_info[ self::COMPOSER_KEY_VERSION ];

					return $carry;
				},
				[] );
		} catch ( Exception $e ) {
			/** @noinspection ForgottenDebugOutputInspection */
			error_log( "Could not load libraries for plugin {$plugin_name} from {$plugin_composer_lock_path} file." );

			return [];
		}


		return is_array( $library_versions ) ? $library_versions : [];
	}

	/**
	 * Get libs versions for loaded plugins.
	 *
	 * @param string[] $loaded_plugins Paths(array) like 'some-dir/plugin-file.php'
	 *
	 * @return array versions in format [lib name][version, plugin]
	 */
	private function get_already_loaded_libs_versions( $loaded_plugins ) {
		$array_of_libs = [];
		foreach ( $loaded_plugins as $loaded_plugin ) {
			foreach ( $this->get_plugin_libs_versions( $loaded_plugin ) as $library_name => $version ) {
				$array_of_libs[ $library_name ][] = [ self::INTERNAL_VERSION_KEY => $version, self::INTERNAL_PLUGIN_KEY => $loaded_plugin ];
			}
		}

		return $array_of_libs;
	}

	/**
	 * Checks if given plugin to load is compatible with already loaded
	 *
	 * @param string   $plugin_to_load_path Path like 'some-dir/plugin-file.php'
	 * @param string[] $loaded_plugins Paths(array) like like 'some-dir/plugin-file.php'
	 *
	 * @return bool
	 */
	public function is_plugin_compatible( $plugin_to_load_path, array $loaded_plugins ) {
		return $this->is_compatible(
			$this->get_plugin_libs_versions( $plugin_to_load_path ),
			$this->get_already_loaded_libs_versions( apply_filters( 'wpdesk_compatibility_guard_plugins_to_check',
				$loaded_plugins ) )
		);
	}

	/**
	 * @return WPDesk_Conflict_Info[]
	 */
	public function get_compatibility_errors() {
		return $this->compatibility_errors;
	}
}