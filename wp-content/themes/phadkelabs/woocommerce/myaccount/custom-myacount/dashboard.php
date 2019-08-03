<?php

function custom_dashboard(){

	require 'mydashboard.php';
}
add_action('woocommerce_before_account_navigation','custom_dashboard');

function my_account_menu_order() {
 	$menuOrder = array(
 		'home'          => __( 'Home', 'woocommerce' ),
 		'dashboard'          => __( 'Dashboard', 'woocommerce' ),
 		'orders'             => __( 'Orders', 'woocommerce' ),
 		'downloads'          => __( 'Download', 'woocommerce' ),
 		'edit-address'       => __( 'Addresses', 'woocommerce' ),
 		'edit-account'    	=> __( 'Account Details', 'woocommerce' ),
 		'customer-logout'    => __( 'Logout', 'woocommerce' ),
 	);
 	return $menuOrder;
 }
 add_filter ( 'woocommerce_account_menu_items', 'my_account_menu_order' );
 
 

/**
  * Register new endpoints to use inside My Account page.
  */

 add_action( 'init', 'my_account_new_endpoints' );

 function my_account_new_endpoints() {
 	add_rewrite_endpoint( 'awards', EP_ROOT | EP_PAGES );
 }


/**
  * Get new endpoint content
  */

  // Awards
 add_action( 'woocommerce_account_awards_endpoint', 'awards_endpoint_content' );
 function awards_endpoint_content() {
     //get_template_part('woocommerce/myaccount/custom-myaccount/my-account-awards');
 }
