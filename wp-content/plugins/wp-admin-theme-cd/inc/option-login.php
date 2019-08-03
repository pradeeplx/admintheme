<?php

/*****************************************************************/
/* ADD LOGIN BODY CLASSES */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_login_body_class' ) ) :

	function wp_admin_theme_cd_login_body_class( $classes ) {
		
		if( wpat_option('login_bg') ) {
			$classes[] = 'wpat-login-bg';
		}
		
		if( wpat_option('logo_upload') ) {
			$classes[] = 'wpat-login-logo';
		}
		
		if( wpat_option('logo_size') ) {
			$classes[] = 'wpat-login-logo-size';
		}
	
		return $classes;

	}

endif;

add_filter( 'login_body_class', 'wp_admin_theme_cd_login_body_class' );


/*****************************************************************/
/* WP LOGIN / REGISTER PAGE CHECK */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_is_login_page' ) ) :

	function wp_admin_theme_cd_is_login_page() {
		
		// Check for WP login + WP register page
		return in_array( $GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php') );
		
	}

endif;


/*****************************************************************/
/* CUSTOMIZED LOGIN PAGE */
/*****************************************************************/

if( ! wpat_option('login_disable') ) {

	/*****************************************************************/
	/* ADD LOGIN STYLE */
	/*****************************************************************/

	if ( ! function_exists( 'wp_admin_theme_cd_login_style' ) ) :

		function wp_admin_theme_cd_login_style() {
			
			// Add custom user css for wp login
			if( wpat_option('css_login') ) {
				wp_enqueue_style( 'custom-login', wp_admin_theme_cd_path('css/login.css'), array(), filemtime( wp_admin_theme_cd_dir('css/login.css') ), 'all' );
			}
			
			wp_enqueue_style( 
				'wpat-login-less',  wp_admin_theme_cd_path( 'css/less/login.less' ), array(), null, 'all'
			);			

		}

	endif;

    add_action('login_enqueue_scripts', 'wp_admin_theme_cd_login_style');
	
	
	/*****************************************************************/
	/* CHANGE LOGIN LOGO URL */
	/*****************************************************************/

	if ( ! function_exists( 'wp_admin_theme_cd_logo_url' ) ) :

		function wp_admin_theme_cd_logo_url() {
			return home_url();
		}

	endif;

    add_filter( 'login_headerurl', 'wp_admin_theme_cd_logo_url' );


	/*****************************************************************/
	/* ADD LOGIN MESSAGE */
	/*****************************************************************/

	if( wpat_option('login_title') ) {

		if ( ! function_exists( 'wp_admin_theme_cd_login_message' ) ) :

			function wp_admin_theme_cd_login_message( $message ) {

				if( empty( $message ) ){
					return '<div class="login-message">' . esc_html( wpat_option('login_title') ) . '</div>';
				}
				
				return $message;
				
			}

		endif;

        add_filter( 'login_message', 'wp_admin_theme_cd_login_message' );

	}

}