<?php

/**
 * @var string $plugin_version
 * @var string $plugin_release_timestamp
 * @var string $plugin_name
 * @var string $plugin_class_name
 * @var string $plugin_text_domain
 * @var string $plugin_dir
 * @var string $plugin_file
 * @var array  $requirements
 * @var string $product_id
 */

add_action( 'wpdesk_after_init_flow_creation_v2',
	/**
	 * Set builder flow for current plugin to free plugin
	 *
	 * @param string                        $class_name
	 * @param WPDesk_Wordpress_Class_Loader $class_loader
	 * @param WPDesk_Plugin_Init_Flow       $init_flow
	 */
	function ( $class_name, $class_loader, $init_flow ) use ( $plugin_class_name, $plugin_dir ) {
		$load_flow_free = 'WPDesk_Plugin_Load_Flow_Composer_Free';
		$build_flow_free = 'WPDesk_Plugin_Build_Flow_Composer_Free';

		if ( $class_name === $plugin_class_name ) {
			$class_loader->register_class(
				$plugin_dir . '/vendor/wpdesk/wp-plugin-flow/src/Build_Flow/Plugin_Build_Flow_Composer_Free.php',
				$build_flow_free,
				$build_flow_free
			);
			$init_flow->set_build_flow_class( $build_flow_free );

			$class_loader->register_class(
				$plugin_dir . '/vendor/wpdesk/wp-plugin-flow/src/Load_Flow/Plugin_Load_Flow_Composer_Free.php',
				$load_flow_free,
				$load_flow_free
			);
			$init_flow->set_load_flow_class( $load_flow_free );
		}
	},
	10,
	3
);


require_once( dirname(__FILE__) . '/plugin-init-php52.php' );

