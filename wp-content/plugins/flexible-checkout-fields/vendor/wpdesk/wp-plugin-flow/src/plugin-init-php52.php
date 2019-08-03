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

if ( ! isset( $plugin_version, $plugin_release_timestamp, $plugin_name, $plugin_class_name, $plugin_text_domain, $plugin_dir, $plugin_file, $requirements ) ) {
	error_log( 'Critical init variables are missing' );
}

if ( ! isset( $product_id ) ) {
	error_log( 'A variable product_id doesn\'t exist for plugin: ' . $plugin_name );
	$product_id = null;
}

// Code in PHP >= 5.3 but understandable by older parsers
if ( PHP_VERSION_ID > 50300 ) {
	add_action( 'plugins_loaded',
		function () use (
			$plugin_version,
			$plugin_release_timestamp,
			$plugin_name,
			$plugin_class_name,
			$plugin_text_domain,
			$plugin_dir,
			$plugin_file,
			$requirements,
			$product_id
		) {
			if ( ! class_exists( 'WPDesk_Wordpress_Class_Loader' ) ) {
				require_once $plugin_dir . '/vendor/wpdesk/wp-class-loader/src/Wordpress_Class_Loader.php';
			}

			$plugin_wordpress_name = basename( $plugin_dir ) . DIRECTORY_SEPARATOR . basename( $plugin_file );

			$class_loader = apply_filters(
				'wpdesk_class_loader_instance',
				new WPDesk_Wordpress_Class_Loader( $plugin_wordpress_name, $plugin_release_timestamp )
			);

			$class_loader->register_class(
				$plugin_dir . '/vendor/wpdesk/wp-plugin-flow/src/Init_Flow/Plugin_Init_Flow_Factory.php',
				'WPDesk_Plugin_Init_Flow_Factory',
				'WPDesk_Plugin_Init_Flow_Factory'
			);

			do_action( 'wpdesk_after_init_flow_register_v2', $plugin_class_name, $class_loader );

			add_action( 'plugins_loaded',
				function () use (
					$class_loader,
					$plugin_version,
					$plugin_release_timestamp,
					$plugin_name,
					$plugin_class_name,
					$plugin_text_domain,
					$plugin_dir,
					$plugin_file,
					$requirements,
					$product_id
				) {
					/** @var WPDesk_Plugin_Init_Flow_Factory $plugin_flow_factory */
					$plugin_flow_factory = $class_loader->create_instance(
						'WPDesk_Plugin_Init_Flow_Factory',
						[ $plugin_class_name, $plugin_version ]
					);

					$init_flow = $plugin_flow_factory->create_flow(
						$class_loader,
						$plugin_version,
						$plugin_release_timestamp,
						$plugin_name,
						$plugin_class_name,
						$plugin_text_domain,
						$plugin_dir,
						$plugin_file,
						$requirements,
						$product_id
					);
					do_action( 'wpdesk_after_init_flow_creation_v2', $plugin_class_name, $class_loader, $init_flow );
					$init_flow->run();
				},
				- 30 ); // priority when flow is created and info about classes required for init flow is gathered
		},
		- 35 ); // priority where info about init flow is gathered


} else {
	/** @noinspection PhpDeprecationInspection */
	$php52_function = create_function( '',
		'echo sprintf( __("<p><strong style=\'color: red;\'>PHP version is older than 5.3 so no WP Desk plugins will work. Please contact your host and ask them to upgrade. </strong></p>", \'wp-plugin-flow\') );' );
	add_action( 'admin_notices', $php52_function );
}