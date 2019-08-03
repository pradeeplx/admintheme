<?php
/*
Plugin Name: Custom Checkout Page
Plugin URI: https://customcheckoutplugin.com/custom-checkout-free.zip
Description: This is a Woocomerce based plugin that will allow you to create custom professional checkout pages. 
Version: 1.0.1
Author: CustomCheckoutPlugin.com
Author URI: https://customcheckoutplugin.com
*/
if ( ! defined( 'ABSPATH' ) ) {
	wp_die('Please Go Back');
	exit;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

function custom_checkout_free_dependency_check() {
  
    if( !class_exists( 'Woocommerce' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die( __( 'Please install and Activate WooCommerce.', 'woocommerce-addon-slug' ), 'Plugin dependency check', array( 'back_link' => true ) );
    }
}

//sets up activation hook
register_activation_hook(__FILE__, 'custom_checkout_free_dependency_check');

define( 'TSRCT_CT_BASE', plugin_basename( __FILE__ ) );
define( 'TSRCT_CT_DIR', plugin_dir_path( __FILE__ ) );
define( 'TSRCT_CT_URL', plugin_dir_url( __FILE__ ) );
define( 'TSRCT_CT_AST', plugin_dir_url( __FILE__ ).'assets/' );
define( 'TSRCT_CT_IMG', plugin_dir_url( __FILE__ ).'assets/images' );
define( 'TSRCT_CT_CSS', plugin_dir_url( __FILE__ ).'assets/css' );
define( 'TSRCT_CT_JS', plugin_dir_url( __FILE__ ).'assets/js' );

require 'includes/checkout-page-lay-admin.php';
new Checkout_Page_Layout_Admin;

require 'includes/checkout-page-lay-functions.php';
new Checkout_Page_Layout_Functions;

require 'includes/checkout-page-lay-content.php';
new Checkout_Page_Layout_Content;

require 'includes/checkout-page-lay-product-box.php';
new Checkout_Page_Layout_Product_Box;


add_filter('single_template', 'tes_checkout_redirection_tempate');
function tes_checkout_redirection_tempate( $template )
{
    if ('tes-cc-template' == get_post_type(get_queried_object_id()) ) {
        $template = dirname( __FILE__ ) .'/includes/checkout-page-preview.php';
    }
    return $template;
}

add_action( 'wp_enqueue_scripts', 'include_style_checkout', 99 );
function include_style_checkout() {
	if ( function_exists( 'is_woocommerce' ) ) {
		if (  is_woocommerce() && is_checkout() ) {
			wp_enqueue_style( 'woo-checkout-style', TSRCT_CT_CSS . '/slider.css',false,'1.1','all');

		}
	}

}



add_filter ( 'woocommerce_add_to_cart_redirect', 'tes_cc_pro_redirect_to_checkout' );
function tes_cc_pro_redirect_to_checkout($url) {

    if( isset($_POST['add-to-cart']) ){
		global $woocommerce;
		$product_id = (int) apply_filters( 'woocommerce_add_to_cart_product_id', intval($_POST['add-to-cart'] ));

		// Remove the default `Added to cart` message
		$skip_cart = get_post_meta( $product_id, '_tes_pr_skip_cart',true);
		if($skip_cart == 'enable'){
			wc_clear_notices();
			return $woocommerce->cart->get_checkout_url();
		}else{
			return $url;
		}
	}else{
			return $url;
		}


}


function cc_pro_template_override( $located, $template_name, $args, $template_path, $default_path ) {

    if( file_exists( plugin_dir_path(__FILE__) . 'woocommerce/' . $template_name ) ) {
        $located = plugin_dir_path(__FILE__) . 'woocommerce/' . $template_name;
    }

    return $located;
}


function cc_pro_woo_template_locate( $template , $slug , $name ) {

    if( empty( $name ) ) {
        if( file_exists( plugin_dir_path(__FILE__) . "/woocommerce/{$slug}.php" ) ) {
            $template = plugin_dir_path(__FILE__) . "/woocommerce/{$slug}.php";
        }
    } else {
        if( file_exists( plugin_dir_path(__FILE__) . "/woocommerce/{$slug}-{$name}.php" ) ) {
            $template = plugin_dir_path(__FILE__) . "/woocommerce/{$slug}-{$name}.php";
        }
    return $template;
    }
}
require 'includes/checkout-page-lay-header-footer.php';
new Checkout_Page_Layout_Header_Footer;

require 'includes/checkout-page-lay-typo.php';
new Checkout_Page_Layout_Typography;

if( is_cc_layout_active() ){

	add_filter( 'wc_get_template' , 'cc_pro_template_override' , 10 , 5 );

	add_filter( 'wc_get_template_part' , 'cc_pro_woo_template_locate' , 10 , 3 );



	require 'includes/checkout-page-lay-frontend.php';
	new Checkout_Page_Layout_Frontend;


}

function is_cc_layout_active(){
	if( get_option('_checkout_page_layout_') && get_post_status(get_option('_checkout_page_layout_')) == 'publish' ){
		return get_option('_checkout_page_layout_');
	}else{
		return false;
	}
}


add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links_free' );

function add_action_links_free ( $links ) {
	$template = array('<a href="' . admin_url( 'edit.php?post_type=tes-cc-template' ) . '">Templates</a>');
	$pro = array('<a style="color:green; font-weight:500" href="https://customcheckoutplugin.com" target="_blank;">Go Pro</a>');
	$doc = array('<a href="https://customcheckoutplugin.com" target="_blank;">Documentation</a>');

	$links = array_merge($doc, $links);
	$links = array_merge($pro, $links);
	$links = array_merge($template, $links);
	return $links;
}


