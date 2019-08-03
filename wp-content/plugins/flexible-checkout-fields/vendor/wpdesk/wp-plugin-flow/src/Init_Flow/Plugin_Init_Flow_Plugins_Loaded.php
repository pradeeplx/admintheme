<?php

if ( ! class_exists( 'WPDesk_Plugin_Init_Flow' ) ) {
	require_once dirname( __FILE__ ) . '/Plugin_Init_Flow.php';
}

/**
 * Init flow is for requirements check and should work with any version of PHP.
 * In this flow it's decided if plugin can be loaded at all and what further flow to load if so.
 *
 * This version of flow is based on plugins loaded action.
 *
 * All class initialization should be through WPDesk_Wordpress_Class_Loader
 */
class WPDesk_Plugin_Init_Flow_Plugins_Loaded implements WPDesk_Plugin_Init_Flow {
	const LOADED_BEFORE_COMPOSER_MANAGER = - 20;
	const LIBRARY_TEXT_DOMAIN = 'wp-plugin-flow';

	/** @var WPDesk_Wordpress_Class_Loader */
	protected $loader;

	/** @var string */
	protected $plugin_version;

	/** @var string */
	protected $plugin_release_timestamp;

	/** @var string */
	protected $plugin_name;

	/** @var string */
	protected $plugin_class_name;

	/** @var string */
	protected $plugin_text_domain;

	/** @var string */
	protected $plugin_dir;

	/** @var string */
	protected $plugin_file;

	/** @var array */
	protected $requirements;

	/** @var string */
	protected $product_id;

	protected $load_flow_class = 'WPDesk_Plugin_Load_Flow';

	protected $build_flow_class = 'WPDesk_Plugin_Build_Flow';

	/**
	 * @param WPDesk_Wordpress_Class_Loader $loader
	 * @param string                        $plugin_version
	 * @param string                        $plugin_release_timestamp
	 * @param string                        $plugin_name
	 * @param string                        $plugin_class_name
	 * @param string                        $plugin_text_domain
	 * @param string                        $plugin_dir
	 * @param string                        $plugin_file
	 * @param array                         $requirements
	 */
	public function __construct(
		WPDesk_Wordpress_Class_Loader $loader,
		$plugin_version,
		$plugin_release_timestamp,
		$plugin_name,
		$plugin_class_name,
		$plugin_text_domain,
		$plugin_dir,
		$plugin_file,
		$requirements,
		$product_id = null
	) {
		$this->loader                   = $loader;
		$this->plugin_version           = $plugin_version;
		$this->plugin_release_timestamp = $plugin_release_timestamp;
		$this->plugin_name              = $plugin_name;
		$this->plugin_class_name        = $plugin_class_name;
		$this->plugin_text_domain       = $plugin_text_domain;
		$this->plugin_dir               = $plugin_dir;
		$this->plugin_file              = $plugin_file;
		$this->requirements             = $requirements;
		$this->product_id               = $product_id;

		$this->register_classes();
	}

	private function register_classes() {
		$this->loader->register_class(
			$this->plugin_dir . '/vendor/wpdesk/wp-basic-requirements/src/Basic_Requirement_Checker_Factory.php',
			'WPDesk_Basic_Requirement_Checker_Factory',
			'WPDesk_Requirement_Checker_Factory'
		);
		$this->loader->register_class(
			$this->plugin_dir . '/vendor/wpdesk/wp-basic-requirements/src/Plugin/Plugin_Info.php',
			'WPDesk_Plugin_Info',
			'WPDesk_Plugin_Info'
		);
		$this->loader->register_class(
			$this->plugin_dir . '/vendor/wpdesk/wp-plugin-flow/src/Load_Flow/Plugin_Load_Flow_Composer.php',
			'WPDesk_Plugin_Load_Flow_Composer',
			'WPDesk_Plugin_Load_Flow'
		);
	}

	public function set_load_flow_class( $class_name ) {
		$this->load_flow_class = $class_name;
	}

	public function set_build_flow_class( $class_name ) {
		$this->build_flow_class = $class_name;
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

		$lang_mo_file = __DIR__ . '/../../lang/' . self::LIBRARY_TEXT_DOMAIN . '-' . $locale . '.mo';
		if ( file_exists( $lang_mo_file ) ) {
			load_textdomain( self::LIBRARY_TEXT_DOMAIN, $lang_mo_file );
		}
	}

	public function run() {
		$this->init_translations();

		$this->hook_last_register_before_start();
		$this->hook_requirements_and_load();
		do_action( 'wpdesk_end_run_init_flow', $this->plugin_class_name, $this->loader );
	}

	private function hook_last_register_before_start() {
		add_action( 'plugins_loaded',
			[ $this, 'handle_last_register_before_start' ],
			self::LOADED_BEFORE_COMPOSER_MANAGER - 1 );
	}

	private function hook_requirements_and_load() {
		add_action( 'plugins_loaded', [ $this, 'requirements_and_load' ], self::LOADED_BEFORE_COMPOSER_MANAGER );
	}

	/**
	 * Factory method for load flow
	 *
	 * @return WPDesk_Plugin_Load_Flow
	 * @throws ReflectionException
	 */
	private function create_load_flow_instance() {
		/** @var WPDesk_Plugin_Load_Flow $load_flow */
		$load_flow = $this->loader->create_instance(
			$this->load_flow_class,
			[ $this->loader, $this->get_plugin_info(), $this->build_flow_class ]
		);

		return $load_flow;
	}

	/**
	 * @throws ReflectionException
	 */
	public function handle_last_register_before_start() {
		$load_flow = $this->create_load_flow_instance();
		$load_flow->register_requirements();

		do_action( 'wpdesk_last_register_init_flow',  $this->plugin_class_name, $this->loader );
	}

	/**
	 * @return WPDesk_Plugin_Info
	 * @throws ReflectionException
	 * @throws \Exception
	 */
	private function get_plugin_info() {

		if ( ! $this->product_id ) {
			$this->product_id = $this->plugin_name;
		}

		/** @var WPDesk_Plugin_Info $plugin_info */
		$plugin_info = $this->loader->create_instance( 'WPDesk_Plugin_Info' );
		$plugin_info->set_plugin_file_name( plugin_basename( $this->plugin_file ) );
		if (method_exists($plugin_info, 'set_plugin_name')) {
			$plugin_info->set_plugin_name( $this->plugin_name );
		}
		$plugin_info->set_plugin_dir( $this->plugin_dir );
		$plugin_info->set_class_name( $this->plugin_class_name );
		$plugin_info->set_version( $this->plugin_version );
		$plugin_info->set_product_id( $this->product_id );
		$plugin_info->set_text_domain( $this->plugin_text_domain );
		$plugin_info->set_release_date( new DateTime( $this->plugin_release_timestamp ) );
		$plugin_info->set_plugin_url( plugins_url( dirname( plugin_basename( $this->plugin_file ) ) ) );

		return $plugin_info;
	}

	/**
	 * @throws ReflectionException
	 */
	public function requirements_and_load() {
		$requirements_checker = $this->create_requirements_checker();

		do_action( 'requirements_and_load_init_flow', $this->plugin_class_name, $this->loader, $requirements_checker );

		if ( ! $requirements_checker->are_requirements_met() ) {
			$requirements_checker->render_notices();

			return;
		}

		$load_flow = $this->create_load_flow_instance();
		$load_flow->run();
	}

	/**
	 * @return WPDesk_Requirement_Checker
	 * @throws ReflectionException
	 */
	private function create_requirements_checker() {
		/** @var WPDesk_Requirement_Checker_Factory $requirements_checker_factory */
		$requirements_checker_factory = $this->loader->create_instance( 'WPDesk_Requirement_Checker_Factory' );
		$requirements_checker         = $requirements_checker_factory->create_requirement_checker(
			__FILE__,
			$this->plugin_name,
			$this->plugin_text_domain );

		$requirements_checker->set_min_php_require( $this->requirements['php'] );
		$requirements_checker->set_min_wp_require( $this->requirements['wp'] );

		if ( isset( $this->requirements['plugins'] ) ) {
			foreach ( $this->requirements['plugins'] as $requirement ) {
				$requirements_checker->add_plugin_require( $requirement['name'], $requirement['nice_name'] );
			}
		}

		if ( isset( $this->requirements['modules'] ) ) {
			foreach ( $this->requirements['modules'] as $requirement ) {
				$requirements_checker->add_php_module_require( $requirement['name'], $requirement['nice_name'] );
			}
		}


		return $requirements_checker;
	}
}