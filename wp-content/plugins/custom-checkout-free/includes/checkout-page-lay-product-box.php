<?php
class Checkout_Page_Layout_Product_Box
{
	public function __construct()
	{
		add_filter( 'woocommerce_product_data_tabs', array($this,'add_checkout_desc_product_data_tab') , 99 , 1 );
		add_action( 'woocommerce_product_data_panels', array($this,'add_checkout_desc_product_data_fields') );
	}
	
	public static function add_checkout_desc_product_data_tab($prodata_tabs){
		$prodata_tabs['checkout-page-desc'] = array(
	        'label' => __( 'Checkout Description', 'woocommerce' ),
	        'target' => 'checkout_desc_pr_data_tab',

	    );
	    return $prodata_tabs;

	}
	public static function add_checkout_desc_product_data_fields() {
	    global $woocommerce, $post;
	    ?>
	    <div id="checkout_desc_pr_data_tab" class="panel woocommerce_options_panel">
	        <div class="sortable-list ui-sortable" id="generated-content">
	        	<?php echo get_post_meta( $post->ID, 'product_checkout_desc_text', true ); ?>
	        	<input type="hidden" id="content_sec_no" value="1" />
	        </div>
	        <a type="button" href="javascript:void(0);" class="add_new_content add_new_content_btn btn">Add New Content</a>
	        <span class="pro-adv">
    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon.png';?>"/>To access this feature you will need to 
    			<a href="https://customcheckoutplugin.com" target="_blank;">purchase the Pro version.</a>
    		</span>
    		<p class="howto">Note: This is a Custom Checkout Pro plugin feature that allows you to add custom sidebar content specificaly for this product.</p>
	    </div>
	    <?php
	}
	
}

?>