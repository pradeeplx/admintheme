<?php
class Checkout_Page_Layout_Functions
{
	public function __construct()
	{
		add_action( 'wp_ajax_set_layout_template', array($this, 'set_layout_template_callback' ));
		add_action( 'wp_ajax_set_checkout_page_layout', array($this, 'set_checkout_page_layout_callback' ));
		add_filter( 'woocommerce_locate_template', array($this,'tes_cc_woo_template'), 1, 3 );
		add_action( 'wp_footer', array($this,'for_checkout_page_only'));
		add_action( 'woocommerce_thankyou', array($this,'thankyou_redirection'));

		add_filter( 'woocommerce_product_add_to_cart_text' , array($this,'custom_woocommerce_product_add_to_cart_text' ));
		add_filter( 'woocommerce_product_single_add_to_cart_text', array($this,'custom_woocommerce_product_add_to_cart_text' ));
	}
	public function  for_checkout_page_only(){
		if(is_checkout()){
			require 'checkout-page-lay-custom.php';
			new Checkout_Page_Layout_Customization;
		}
	}
	public function tes_cc_woo_template( $template, $template_name, $template_path ) {
		global $woocommerce;
		$_template = $template;
		if ( ! $template_path ) 
		$template_path = $woocommerce->template_url;

		$plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) )  . '/woocommerce/';

		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name
			)
		);

		if( ! $template && file_exists( $plugin_path . $template_name ) )
		$template = $plugin_path . $template_name;

		if ( ! $template )
		$template = $_template;

		return $template;
	}
	public function set_layout_template_callback(){
		update_user_meta( get_current_user_id(), '_tes_temp_lay_id_', intval($_POST['id'] ));
		exit;
	}
	public function set_checkout_page_layout_callback(){
		update_option('_checkout_page_layout_',intval($_POST['id']));
		exit;
	}
	
 
	public function thankyou_redirection( $order_id ){
	    $order = new WC_Order( $order_id );
	 	
	 	$selected_page = get_option("custom_checkout_page");
	 	if( !$selected_page )
	 		return;
	    $url = get_permalink($selected_page);
	 
	    if ( $order->status != 'failed' ) {
	        wp_redirect($url.'?key=wc_order_'.base64_encode($order_id));
	        exit;
	    }
	}
	

	public function custom_woocommerce_product_add_to_cart_text($text) {
		$btn_txt = get_post_meta( get_the_ID(), '_tes_pr_button_text',true) ? get_post_meta( get_the_ID(), '_tes_pr_button_text',true) : '';
		if($btn_txt){
			return $btn_txt;
		}else{
			return $text;
		}

	}
}
?>