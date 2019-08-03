<?php
class Checkout_Page_Layout_Frontend
{
	public function __construct()
	{
		add_action( 'wp',function(){
			if(is_checkout()){

				add_filter( 'show_admin_bar', '__return_false' );

				add_filter( 'body_class', function( $classes ) {
				    return array_merge( $classes, array( 'checkout-layout-active') );
				} );
				add_action('wp_head', array($this, 'typo_settings'));
				add_action('wp_head', array($this, 'header_element_settings'));

				add_action('wp_head', array($this, 'add_header_footer_css'));

				add_action('wp_footer', array($this, 'footer_element_settings'));

				add_action( 'wp_enqueue_scripts', array($this,'add_custom_layout_style' ));
				add_action('wp_head', array($this, 'add_inline_css_checkout'));

				add_action('wp_footer', array($this, 'add_inline_js_checkout'));

				add_filter('woocommerce_checkout_fields', array($this, 'add_field_class_layout' ));

				add_action('wp_footer',array($this, 'cc_remove_css_from_footer'));

				add_action('wp_head',array($this, 'cc_remove_css_from_header'));

			}else{
				return;
			}

		});
		//add_action('wp_head',array($this,'add_minicart_popup'));
		//add_action( 'wp_enqueue_scripts', array($this,'add_custom_mini_cart_style'));

		
		add_action( 'wp_enqueue_scripts', array($this,'enqueue_script_checkout' ));


	}

	public static function add_field_class_layout($fields) {
		$activated_template_id = get_option('_checkout_page_layout_',true);
	    $layoutid = get_post_meta( $activated_template_id, '_tes_layout_id',true);
	    foreach ($fields as &$fieldset) {
	    	$count = 0;
	        foreach ($fieldset as &$field) {
	        	$count++;
	        	$field['class'][] = ( $count % 2 ) ? 'checkout_left_field' : 'checkout_right_field';
	            $field['class'][] = 'form-fields-layout-'.$layoutid;
	            if( $layoutid != 1 ){
	            	if(isset($field['label'])){
		            	$field['placeholder'] = $field['label'];
		            }
	            }else{
	            	if(isset($field['label'])){
		            	$field['placeholder'] = ' ';
		            }
	            }

	        }
	    }
	    return $fields;
	}
	public static function cc_remove_css_from_footer(){
		Checkout_Page_Layout_Frontend::remove_external_css_js();
	}

	public static function cc_remove_css_from_header(){
		Checkout_Page_Layout_Frontend::remove_external_css_js();
	}
	public static function remove_external_css_js(){
		global $wp;
		if ( !is_checkout() || !empty( $wp->query_vars['order-received'] ) ) {
			return;
		}
		global $wp_scripts;
		global $wp_styles;

		// Runs through the queue scripts
		foreach( $wp_scripts->queue as $handle ) :
			$scripts_list_array[] = $handle;
		endforeach;

		// Runs through the queue styles
		foreach( $wp_styles->queue as $handle ) :
			$all_styles_arr[] = $handle;
		endforeach;
			$all_styles_arr[] = 'storefront-woocommerce-style-css';

		// echo '<pre>';
		// print_r($scripts_list_array);
		// print_r($all_styles_arr);
		// echo '</pre>';

		$skipped_css = array('custom-checkout-layout', 'storefront-woocommerce-style','checkout-layout-minicart-font-awesome','checkout-layout-minicart','select2','googleFontsLogoHeader','googleFontsFooter','googleFontsUpperHeader');

		$skipped_js = array('checkout-layout-minicart-js','wc-add-to-cart','selectWoo','wc-checkout','woocommerce','wc-cart-fragments');

		if( $all_styles_arr ){
			foreach($all_styles_arr as $style){
				if( !in_array($style,$skipped_css) ){
					wp_dequeue_style($style);
    				wp_deregister_style($style);
				}
			}
		}

		// if( $scripts_list_array ){
		// 	foreach($scripts_list_array as $script){
		// 		if( !in_array($script,$skipped_js) ){
		// 			wp_dequeue_script($script);
		// 		}
		// 	}
		// }
	}
	public static function add_custom_layout_style(){

		wp_enqueue_style( 'custom-checkout-layout', TSRCT_CT_CSS . '/checkout-page-woocommerce.css',true,'1.1','all');

		Checkout_Page_Layout_Frontend::remove_external_css_js();

	}

	
	
	public function add_header_footer_css(){
		$layout_id = get_option('_checkout_page_layout_',true);

		$strc_value = get_post_meta( $layout_id, '_tes_layout_layoutContent_odersummary',true) ? get_post_meta( $layout_id, '_tes_layout_layoutContent_odersummary',true) : 'enable';
    	$header_status = get_post_meta( $layout_id, '_tes_layout_hf_header_status',true) ? get_post_meta( $layout_id, '_tes_layout_hf_header_status',true) : 'no';
    	$footer_status = 'no';

    	if( $header_status != 'site' ){
	      ?>
	     <style type="text/css">body.checkout-layout-active header, .woocommerce-breadcrumb{ display: none !important; }</style>
	      <?php
	    }
	    if( $footer_status != 'site' ){
	      ?>
	     <style type="text/css">body.checkout-layout-active footer{ display: none !important; }</style>
	      <?php
	    }

	}
	public static function typo_settings(){
		$layout_id = get_option('_checkout_page_layout_',true);
		$typo = get_post_meta( $layout_id, '_tes_layout_typo',true);
	    if(!is_array($typo)){
	      $typo['button_bck_color'] = '#dd3333';
	      $typo['button_txt_color'] = '#ffffff';
	      $typo['button_hover_color'] = '#dd4d4d';
	    }
	    ?>
	    <style type="text/css">
		    .checkout-layout-active #place_order{
		      background-color: <?php echo $typo['button_bck_color']; ?> !important;
		      color: <?php echo $typo['button_txt_color']; ?>
		    }
		    .checkout-layout-active #place_order:hover{
		      background-color: <?php echo $typo['button_hover_color']; ?> !important;
		    }
	  	</style>
	  	<?php
	}
	public static function header_element_settings(){
		$layout_id = get_option('_checkout_page_layout_',true);

		$header_status = get_post_meta( $layout_id, '_tes_layout_hf_header_status',true) ? get_post_meta( $layout_id, '_tes_layout_hf_header_status',true) : 'no';

		$header_text = get_post_meta( $layout_id, '_tes_layout_hf_header_text',true) ? get_post_meta( $layout_id, '_tes_layout_hf_header_text',true) : 'Checkout';

		$header_text_align = get_post_meta( $layout_id, '_tes_layout_hf_header_text_alignment',true) ? get_post_meta( $layout_id, '_tes_layout_hf_header_text_alignment',true) : 'center';

		if( $header_status == 'custom' ){
	    	$header_img = get_post_meta( $layout_id, '_tes_layout_hf_header_img',true) ? get_post_meta( $layout_id, '_tes_layout_hf_header_img',true) : '';
	    	if( $header_img ){
	      		$header_img_align = get_post_meta( $layout_id, '_tes_layout_hf_header_img_alignment',true) ? get_post_meta( $layout_id, '_tes_layout_hf_header_img_alignment',true) : 'center';
	   	?>
			    <div style="width:200px;" class="tes_cc_lay_custom_logo <?php echo "tes-cc-custom-header-logo-".$header_img_align; ?>">
			        <img src="<?php echo $header_img; ?>" />
			    </div>
	      <?php
	    	}
	 	}
	 	if( $header_status == 'text' ){
	 		?>
	 		<div class="woocommerce">
	 			<h1 style="text-align: <?php echo $header_text_align; ?> "><?php echo $header_text; ?></h1>
	 		</div>
	 		<?php
	 	}
	}
	public static function footer_element_settings(){

		$layout_id = get_option('_checkout_page_layout_',true);
		$footer_status = 'no';

    	$footer_custom_element = 'menu';

    	$footer_custom_element_align = 'center';

    	$footer_img = '';

    	$footer_menu = '';

    	if( $footer_status == 'custom' )
    	{
  		?>
  		    <div id="tes_cc_lay_custom_footer">
  		    	<?php if( $footer_custom_element == 'image' ){ ?>
  		    		<div class="footer_logo footer_logo-<?php echo $footer_custom_element_align; ?>">
			            <div class="center-vertical">
			              <img src="<?php echo $footer_img; ?>" />
			            </div>
		        	</div>
  		    	<?php } ?>
  		    	<?php if( $footer_custom_element == 'menu' ){ ?>
  		    		<div class="footer_menu menu-<?php echo $footer_custom_element_align; ?>">
          				<div class="center-vertical">
          					<?php echo wp_nav_menu( array('menu'=> $footer_menu)); ?>
          				</div>
          			</div>
  		    	<?php } ?>
  		    </div>
  		<?php
  		}

	}

	public static function add_inline_css_checkout(){
		$the_ID = get_option('_checkout_page_layout_',true);
		$typo = get_post_meta( $the_ID, '_tes_layout_typo',true);
		

		if(!is_array($typo)){
			$typo['font_family'] = 'Default Layout Font';
			$typo['button_bck_color'] = '#dd3333';
			$typo['button_txt_color'] = '#ffffff';
			$typo['button_hover_color'] = '#dd4d4d';
		}
		if( $typo['font_family'] != 'Default Layout Font'){
			echo '<link href="https://fonts.googleapis.com/css?family='.$typo['font_family'].'" rel="stylesheet">';
			$font_family = $typo['font_family'];
		}else{

			$layout_value = get_post_meta( $the_ID, '_tes_layout_id',true);
			if( $layout_value == 2 || $layout_value == 3 ){
				$font_family = 'Open Sans';
			}else{
				$font_family = 'PT Serif';
			}
			echo '<link href="https://fonts.googleapis.com/css?family='.$font_family.'" rel="stylesheet">';
		}
		?>
		<style type="text/css">
			.checkout-layout-active .woocommerce-form-coupon button,
			.checkout-layout-active,
			.checkout-layout-active .layout-3 .header h2.header-title,
			.checkout-layout-active .layout-2 .header-title,
			.checkout-layout-active .layout-2 .title-area,
			.checkout-layout-active .layout-2 #payment .place-order .button,
			.checkout-layout-active .layout-1 .header h2.header-title,
			table th,
			.checkout-layout-active .layout-1,
			.checkout-layout-active .coupon-custom-checkout-pro-layout-1 .woocommerce-info,
			.checkout-layout-active .layout-1 .custom-checkout-pro-left .woocommerce-error, .checkout-layout-active .login-custom-checkout-pro-layout-1 .woocommerce-message,
			.checkout-layout-active .layout-1,
			.checkout-layout-active .coupon-custom-checkout-pro-layout-1 .woocommerce-info,
			.checkout-layout-active .layout-1 .custom-checkout-pro-left .woocommerce-error,
			.checkout-layout-active .login-custom-checkout-pro-layout-1 .woocommerce-message,
			.checkout-layout-active .coupon-custom-checkout-pro-layout-1 + .woocommerce-form-coupon,
			.checkout-layout-active .login-custom-checkout-pro-layout-1 .woocommerce-info,
			.checkout-layout-active .woocommerce-message.success-custom-checkout-pro-layout-1,
			.checkout-layout-active .login-custom-checkout-pro-layout-1 + .header_checkout_login > .header-checkout-login,
			table td,
			.checkout-layout-active #payment .place-order .button,
			.checkout-layout-active .login-custom-checkout-pro-layout-3 + .header_checkout_login > .header-checkout-login button,
			.checkout-layout-active .login-custom-checkout-pro-layout-1 + .header_checkout_login > .header-checkout-login button,
			.checkout-layout-active .login-custom-checkout-pro-layout-2 + .header_checkout_login > .header-checkout-login button{
				font-family: <?php echo $font_family; ?> !important;
			}
		</style>
		<?php

		$section_heading 	= get_post_meta( $the_ID, '_tes_layout_section_heading',true);
		$section_bck_color 	= isset($section_heading['bck_color']) ? $section_heading['bck_color'] : '#000';
		$section_txt_color 	= isset($section_heading['txt_color']) ? $section_heading['txt_color'] : '#fff';

		?>
		<style type="text/css">
			.checkout-layout-active .layout-3 .btn,
			.checkout-layout-active .woocommerce-message.success-custom-checkout-pro-layout-2 a,
			.checkout-layout-active .woocommerce-message.success-custom-checkout-pro-layout-1 a,
			.checkout-layout-active .woocommerce-message.success-custom-checkout-pro-layout-3 a,
			.checkout-layout-active .layout-2 .btn,
			.checkout-layout-active .login-custom-checkout-pro-layout-3 + .header_checkout_login > .header-checkout-login button,
			.checkout-layout-active .woocommerce-form-coupon .button,
			.checkout-layout-active .login-custom-checkout-pro-layout-2 + .header_checkout_login > .header-checkout-login button,
			.checkout-layout-active .login-custom-checkout-pro-layout-1 + .header_checkout_login > .header-checkout-login button,
			.checkout-layout-active .layout-1 .btn-block,
			.checkout-layout-active .layout-3 a.btn-block,
			.checkout-layout-active .layout-3 #payment .place-order .button,
			.checkout-layout-active .layout-1 #payment .place-order .button,
			#tes-cc-template-page .layout-2 button#place_order,
			.checkout-layout-active #payment .place-order .button{
				background: <?php echo $typo['button_bck_color']; ?>;
				color: <?php echo $typo['button_txt_color']; ?>;
			}

			.checkout-layout-active .layout-1 .btn-block:hover,
			.checkout-layout-active .layout-3 a.btn-block:hover,
			.checkout-layout-active .layout-2 a.btn-block:hover,
			.checkout-layout-active .woocommerce-message.success-custom-checkout-pro-layout-2 a:hover,
			.checkout-layout-active .woocommerce-message.success-custom-checkout-pro-layout-3 a:hover,
			.checkout-layout-active .woocommerce-message.success-custom-checkout-pro-layout-1 a:hover,
			.checkout-layout-active .login-custom-checkout-pro-layout-3 + .header_checkout_login > .header-checkout-login button:hover,
			.checkout-layout-active .login-custom-checkout-pro-layout-2 + .header_checkout_login > .header-checkout-login button:hover,
			.checkout-layout-active .login-custom-checkout-pro-layout-1 + .header_checkout_login > .header-checkout-login button:hover,
			.checkout-layout-active .woocommerce-form-coupon .button:hover,
			.checkout-layout-active .woocommerce-message.success-custom-checkout-pro-layout-3:hover,
			.checkout-layout-active .layout-3 #payment .place-order .button:hover,
			.checkout-layout-active .layout-1 #payment .place-order .button:hover{
				background: <?php echo $typo['button_hover_color']; ?>;
			}

			.checkout-layout-active .layout-3 .header h2.header-title,
			.checkout-layout-active .coupon-custom-checkout-pro-layout-3 .woocommerce-info,
			.checkout-layout-active .login-custom-checkout-pro-layout-3 .woocommerce-info,
			.checkout-layout-active .layout-3 .custom-checkout-pro-left .media .header-number,
			.checkout-layout-active .layout-3 .custom-checkout-pro-left .media .header-number::after
			{
				background: <?php echo $section_bck_color; ?>;
				color: <?php echo $section_txt_color; ?>;
			}

			.checkout-layout-active #payment .place-order .button{
				color: <?php echo $typo['button_txt_color']; ?>;
			}


			.checkout-layout-active .layout-3 .panel-body,
			.checkout-layout-active .coupon-custom-checkout-pro-layout-3 + form.checkout_coupon,
			.checkout-layout-active .login-custom-checkout-pro-layout-3 + .header_checkout_login > .header-checkout-login{
				border-color: <?php echo Checkout_Page_Layout_Frontend::hex2rgba($section_bck_color,0.2); ?>;
			}


		</style>
		<?php
	}

	public static function hex2rgba($color, $opacity = false) {

		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if(empty($color))
	          return $default;

		//Sanitize $color if "#" is provided
	        if ($color[0] == '#' ) {
	        	$color = substr( $color, 1 );
	        }

	        //Check if color has 6 or 3 characters and get values
	        if (strlen($color) == 6) {
	                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	        } elseif ( strlen( $color ) == 3 ) {
	                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	        } else {
	                return $default;
	        }

	        //Convert hexadec to rgb
	        $rgb =  array_map('hexdec', $hex);

	        //Check if opacity is set(rgba or rgb)
	        if($opacity){
	        	if(abs($opacity) > 1)
	        		$opacity = 1.0;
	        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
	        } else {
	        	$output = 'rgb('.implode(",",$rgb).')';
	        }

	        //Return rgb(a) color string
	        return $output;
	}

	public static function add_inline_js_checkout(){
		$activated_theme = wp_get_theme();
		$theme_slug = 'theme-slug-'.$activated_theme->get( 'TextDomain' );
		$highlight_asterik  = get_option('custom-checkout-highlight-asterik') ? get_option('custom-checkout-highlight-asterik') : 'enable';
		if( $highlight_asterik == 'disable' )
		{
			?>
			<style type="text/css">.woocommerce form .form-row .required{ visibility: hidden !important; }</style>
			<?php
		}else{
			?>
			<style type="text/css">.woocommerce form .form-row .required{ visibility: visible; !important; }</style>
			<?php
		}


			?>
		<?php
	}

	public static function enqueue_script_checkout() {
   		wp_register_script( 'tesseract-ct-front-js', TSRCT_CT_JS.'/tes-ct-front.js', array( 'jquery' ), '', true  );
		wp_enqueue_script('tesseract-ct-front-js');
	}
	

}