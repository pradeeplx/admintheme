<?php

use WPDesk\Helper\HelperAsLibrary;
use WPDesk\Helper\HelperRemover;
use WPDesk\License\PluginRegistrator;
use WPDesk\Notice\Notice;
use WPDesk\PluginBuilder\BuildDirector\LegacyBuildDirector;
use WPDesk\PluginBuilder\Builder\InfoActivationBuilder;

if ( ! class_exists( 'WPDesk_Plugin_Build_Flow' ) ) {
	require_once dirname( __FILE__ ) . '/Plugin_Build_Flow.php';
}

/**
 * Plugin is built. Class autoloading through composer.
 */
class WPDesk_Plugin_Build_Flow_Composer implements WPDesk_Plugin_Build_Flow {
	/** @var WPDesk_Plugin_Info */
	protected $plugin_info;

	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}

	public function run() {
		$registrator = $this->register_plugin();
		$this->init_helper();

		if ( $this->is_plugin_compatible() ) {
			$is_plugin_subscription_active = class_exists( PluginRegistrator::class ) && $registrator instanceof PluginRegistrator && $registrator->is_active();

			$builder = new InfoActivationBuilder( $this->plugin_info, $is_plugin_subscription_active );
			$build_director = new LegacyBuildDirector( $builder );
			$build_director->build_plugin();
		}
	}

	/**
	 * Get running plugin name if it's possible from data
	 *
	 * @return string
	 */
	private function get_current_plugin_name() {
		if ( method_exists( $this->plugin_info, 'get_plugin_name' ) ) {
			$plugin_name = $this->plugin_info->get_plugin_name();
		}
		if ( empty( $plugin_name ) ) {
			$plugin_name = $this->plugin_info->get_plugin_file_name();
		}

		return $plugin_name;
	}

	/**
	 * Is starting plugin compatible with others
	 *
	 * @return bool
	 */
	private function is_plugin_compatible() {
		$checker = new WPDesk_Plugin_Compatibility_Checker();

		$guard = new WPDesk_Plugin_Compatibility_Guard_V2(
			$this->plugin_info->get_plugin_file_name(),
			$checker
		);
		$guard->guard_compatibility();

		if ( $guard->is_compatibility_error_found() ) {
			$this->show_compatibility_errors( $checker->get_compatibility_errors() );
		}

		return ! $guard->is_compatibility_error_found();
	}

	/**
	 * Show errors when plugin is not compatible with a running one
	 *
	 * @param WPDesk_Conflict_Info[] $compatibility_errors
	 */
	private function show_compatibility_errors( array $compatibility_errors ) {
		foreach ( $compatibility_errors as $conflict_info ) {

			/** @noinspection ForgottenDebugOutputInspection */
			error_log(
				sprintf( 'The &#8220;%s&#8221; plugin cannot start as there is a dependency conflict with other existing plugin &#8220;%s&#8221;. The required version of dependency %s is %s and the version used by plugin &#8220;%s&#8221; is %s. Please upgrade all plugins to the most recent version or disable the &#8220;%s&#8221; plugin.',
					$this->get_current_plugin_name(),
					$conflict_info->get_conflict_plugin_name(),
					$conflict_info->get_conflict_library_name(),
					$conflict_info->get_required_library_version(),
					$conflict_info->get_conflict_plugin_name(),
					$conflict_info->get_provided_library_version(),
					$conflict_info->get_conflict_plugin_name()
				)
			);

			new Notice(
				sprintf(
					__( 'The &#8220;%s&#8221; plugin cannot start as there is a dependency conflict with other existing plugin &#8220;%s&#8221;. Please upgrade all plugins to the most recent version or disable the &#8220;%s&#8221; plugin.',
						'wp-plugin-flow' ),
					$this->get_current_plugin_name(),
					$conflict_info->get_conflict_plugin_name(),
					$conflict_info->get_conflict_plugin_name()
				), 'error'
			);
		}
	}

	/**
	 * Register plugin for subscriptions and updates
	 *
	 * NOTE: @return PluginRegistrator|null
	 *
	 * @see init_helper note
	 *
	 */
	private function register_plugin() {
		if ( class_exists( PluginRegistrator::class ) ) {
			$registrator = new PluginRegistrator( $this->plugin_info );
			$registrator->add_plugin_to_installed_plugins();

			return $registrator;
		}

		return null;
	}

	/**
	 * Helper is a component that gives:
	 * - activation interface
	 * - automatic updates
	 * - logs
	 * - some other feats
	 *
	 * NOTE:
	 *
	 * It's possible for this method to not found classes embedded here.
	 * OTHER plugin in unlikely scenario that THIS plugin is disabled
	 * can use this class and do not have this library dependencies as
	 * these are loaded using composer.
	 *
	 * @return HelperAsLibrary|null
	 */
	private function init_helper() {
		if ( class_exists( HelperRemover::class ) ) {
			( new HelperRemover() )->hooks();
		}
		if ( class_exists( HelperAsLibrary::class ) ) {
			$helper = new HelperAsLibrary();
			$helper->hooks();

			return $helper;
		}

		return null;
	}
}