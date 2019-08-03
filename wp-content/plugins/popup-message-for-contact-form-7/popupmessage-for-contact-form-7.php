<?php

/**

 * Plugin Name: Popup Message for Contact Form 7

 * Description: This plugin will show the popup when Contact Form 7 has been submitted.

 * Version: 2.0 

 * Author: Ocean Infotech

 * Author URI: Author's website

 */



// deactivate plugin if Contact Form 7 are not active

add_action( 'admin_notices', 'pmfcf_show_notice' );



function pmfcf_show_notice() {

	if ( get_transient( get_current_user_id() . 'cf7error' ) ) {

		deactivate_plugins( plugin_basename( __FILE__ ) );

		delete_transient( get_current_user_id() . 'cf7error' );

		echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=contact+form+7">Contact Form 7</a> plugin installed and activated.</p></div>';

	}

}





register_activation_hook( __FILE__, 'pmfcf_plugin_activate' );



function pmfcf_plugin_activate() {

	if ( ! ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) ) {

		set_transient( get_current_user_id() . 'cf7error', 'message' );

	}

}





// Register style and script 



add_action( 'wp_enqueue_scripts', 'pmfcf_add_scripts' );

add_action( 'admin_enqueue_scripts', 'pmfcf_add_scripts' );



function pmfcf_add_scripts() {



	wp_enqueue_script( 'pmfcf-script-popupscript', plugins_url( '/js/popupscript.js', __FILE__ ) );

	wp_enqueue_script( 'pmfcf-script-sweetalert2', plugins_url( '/js/sweetalert2.all.min.js', __FILE__ ) );

	wp_enqueue_script( 'pmfcf-jscolor', plugins_url( '/js/jscolor.js', __FILE__ ) );

	wp_enqueue_style( 'pmfcf-sweetalert2-style', plugins_url( '/css/sweetalert2.min.css', __FILE__ ) );

	wp_enqueue_style( 'pmfcf-style', plugins_url( '/css/style.css', __FILE__ ) );

}


function load_admin_libs() {
    wp_enqueue_media();
    wp_enqueue_script( 'pmfcf-wp-media-uploader', plugins_url( 'popup-message-for-contact-form-7/js/wp_media_uploader.js', __DIR__ ) );
}
add_action( 'admin_enqueue_scripts', 'load_admin_libs' );

// add popup setting panel in contact form 7 editor

require_once dirname(__FILE__) . '/popup_panel.php';



// save popup settings 

require_once dirname(__FILE__) . '/save_popup_setting.php';



// submit popup settings

require_once dirname(__FILE__) . '/submit_popup_settings.php';



