<?php
class Checkout_Page_Layout_Customization
{
	public function __construct()
	{
		
		add_filter( 'body_class', function( $classes ) {
		    return array_merge( $classes, array( 'layout-active' ) );
		} );
		//echo "<pre>"; print_r(get_body_class()); echo "</pre>";

		add_action( 'wp_print_styles', array($this,'wp_67472455'), 100 );
	}
	public function wp_67472455() {
	   wp_dequeue_style( 'storefront-style-css' );
	}

}

?>