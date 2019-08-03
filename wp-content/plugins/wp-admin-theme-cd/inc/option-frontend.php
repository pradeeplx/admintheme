<?php

/*****************************************************************/
/* ADD FRONTEND BODY CLASSES */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_frontend_body_class' ) ) :

	function wp_admin_theme_cd_frontend_body_class( $classes ) {
		
		// Remove wp toolbar icon
		if( wpat_option('toolbar_wp_icon') ) {
			$classes[] = 'wpat-wp-toolbar-icon-remove';
		}
		
		// Custom toolbar icon
		if( wpat_option('toolbar_icon') ) {
			$classes[] = 'wpat-toolbar-icon';
		}
	
		return $classes;

	}

endif;

add_filter( 'body_class', 'wp_admin_theme_cd_frontend_body_class' );


/*****************************************************************/
/* ADD FRONTEND CSS */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_frontend_css' ) ) :

	function wp_admin_theme_cd_frontend_css() {
		
		if( ! is_admin() && is_user_logged_in() ) {
			
			wp_register_style( 
				'wpat-frontend-less',  wp_admin_theme_cd_path( 'css/less/frontend.less' ), array(), null, 'all'
			);
			wp_enqueue_style ( 'wpat-frontend-less' );
			
			// Add custom toolbar wp icon for frontend adminbar
			if( wpat_option('toolbar_icon') ) {
				$toolbar_wp_icon = "body.wpat-toolbar-icon #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before { background-image: url(" . wpat_option('toolbar_icon') . ") }";
				wp_add_inline_style( 'wpat-frontend-less', $toolbar_wp_icon );
			}
			
		}
		
	}

endif;

add_action( 'wp_enqueue_scripts', 'wp_admin_theme_cd_frontend_css', 30 );