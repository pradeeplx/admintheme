<?php

if ( ! class_exists( 'WPDesk_Plugin_Load_Flow' ) ) {
	require_once dirname( __FILE__ ) . '/Plugin_Load_Flow.php';
}

/**
 * Load flow is a flow to load all low level dependency (i.e. composer) for plugin to load.
 *
 * This version of flow is based on preparing composer to load further dependencies.
 *
 * All class initialization should be through WPDesk_Wordpress_Class_Loader
 */
class WPDesk_Plugin_Load_Flow_Composer implements WPDesk_Plugin_Load_Flow {
	/** @var WPDesk_Plugin_Info */
	protected $plugin_info;

	/** @var string */
	protected $build_flow_class;

	/** @var WPDesk_Wordpress_Class_Loader */
	protected $loader;

	public function __construct(
		WPDesk_Wordpress_Class_Loader $loader,
		WPDesk_Plugin_Info $plugin_info,
		$build_flow_class
	) {
		$this->loader           = $loader;
		$this->plugin_info      = $plugin_info;
		$this->build_flow_class = $build_flow_class;
	}

	public function register_requirements() {
		$this->loader->register_class(
			$this->plugin_info->get_plugin_dir() . '/vendor/wpdesk/wp-autoloader/src/Loader/Loader_Manager_Factory.php',
			'WPDesk_Loader_Manager_Factory',
			'WPDesk_Loader_Manager_Factory'
		);

		$this->loader->register_class(
			$this->plugin_info->get_plugin_dir() . '/vendor/wpdesk/wp-autoloader/src/Loader/Flow/Composer_Flow_Loader.php',
			'WPDesk_Composer_Flow_Loader',
			'WPDesk_Composer_Flow_Loader'
		);

		$this->loader->register_class(
			$this->plugin_info->get_plugin_dir() . '/vendor/wpdesk/wp-autoloader/src/Loader/Flow/Composer_Flow_Loader_Info.php',
			'WPDesk_Composer_Flow_Loader_Info',
			'WPDesk_Composer_Flow_Loader_Info'
		);

		$this->loader->register_class(
			$this->plugin_info->get_plugin_dir() . '/vendor/wpdesk/wp-plugin-flow/src/Build_Flow/Plugin_Build_Flow_Composer.php',
			'WPDesk_Plugin_Build_Flow_Composer',
			'WPDesk_Plugin_Build_Flow'
		);

		$this->loader->register_class(
			$this->plugin_info->get_plugin_dir() . '/vendor/wpdesk/wp-plugin-flow/src/Compatibility/Plugin_Disabler_By_File.php',
			'WPDesk_Plugin_Disabler_By_File',
			'WPDesk_Plugin_Disabler_By_File'
		);
	}

	public function run() {
		$plugin_loader = $this->prepare_plugin_loader();

		$this->loader->get_class_name( 'WPDesk_Loader_Manager_Factory' );
		$this->try_suppress_original_helper_load();

		$loader_manager = WPDesk_Loader_Manager_Factory::get_manager_instance();
		$loader_manager->attach_loader( $plugin_loader );
	}

	/**
	 * @return WPDesk_Loader
	 * @throws ReflectionException
	 */
	protected function prepare_plugin_loader() {
		/** @var WPDesk_Composer_Flow_Loader_Info $loader_info */
		$loader_info = $this->loader->create_instance( 'WPDesk_Composer_Flow_Loader_Info' );
		$loader_info->set_plugin_info( $this->plugin_info );
		$loader_info->set_autoload_file( new \SplFileInfo( realpath( $this->plugin_info->get_plugin_dir() . '/vendor/autoload.php' ) ) );
		$loader_info->set_load_priority( $this->plugin_info->get_release_date()->getTimestamp() );

		/** @var WPDesk_Plugin_Build_Flow $build_flow */
		$build_flow = $this->loader->create_instance( $this->build_flow_class, [ $this->plugin_info ] );
		$loader_info->set_build_flow( $build_flow );

		/** @var WPDesk_Composer_Flow_Loader $loader */
		$loader = $this->loader->create_instance( 'WPDesk_Composer_Flow_Loader', [ $loader_info ] );

		return $loader;
	}

	/**
	 * Tries to prevent original Helper from loading
	 *
	 * @return void
	 */
	private function try_suppress_original_helper_load() {
		static $supressed;

		if ( $supressed === null ) {
			$supressed = true;

			$this->loader->get_class_name( 'WPDesk_Plugin_Disabler_By_File' );
			( new WPDesk_Plugin_Disabler_By_File( 'wpdesk-helper/wpdesk-helper.php' ) )->disable();
		}
	}
}