<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package medirashed
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @return void
 */
function medirashed_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'medirashed_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function medirashed_woocommerce_scripts() {
	wp_enqueue_style( 'medirashed-woocommerce-style', get_template_directory_uri() . '/woocommerce.css' );

	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'medirashed-woocommerce-style', $inline_font );
}
add_action( 'wp_enqueue_scripts', 'medirashed_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function medirashed_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'medirashed_woocommerce_active_body_class' );

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function medirashed_woocommerce_products_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'medirashed_woocommerce_products_per_page' );

/**
 * Product gallery thumnbail columns.
 *
 * @return integer number of columns.
 */
function medirashed_woocommerce_thumbnail_columns() {
	return 4;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'medirashed_woocommerce_thumbnail_columns' );

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function medirashed_woocommerce_loop_columns() {
	return 4;
}
add_filter( 'loop_shop_columns', 'medirashed_woocommerce_loop_columns' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function medirashed_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'medirashed_woocommerce_related_products_args' );

if ( ! function_exists( 'medirashed_woocommerce_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper.
	 *
	 * @return  void
	 */
	function medirashed_woocommerce_product_columns_wrapper() {
		$columns = medirashed_woocommerce_loop_columns();
		echo '<div class="columns-' . absint( $columns ) . '">';
	}
}
add_action( 'woocommerce_before_shop_loop', 'medirashed_woocommerce_product_columns_wrapper', 40 );

if ( ! function_exists( 'medirashed_woocommerce_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close.
	 *
	 * @return  void
	 */
	function medirashed_woocommerce_product_columns_wrapper_close() {
		echo '</div>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'medirashed_woocommerce_product_columns_wrapper_close', 40 );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'medirashed_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function medirashed_woocommerce_wrapper_before() {
		?>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
			<?php
	}
}
add_action( 'woocommerce_before_main_content', 'medirashed_woocommerce_wrapper_before' );

if ( ! function_exists( 'medirashed_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function medirashed_woocommerce_wrapper_after() {
			?>
			</main><!-- #main -->
		</div><!-- #primary -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'medirashed_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
	<?php
		if ( function_exists( 'medirashed_woocommerce_header_cart' ) ) {
			medirashed_woocommerce_header_cart();
		}
	?>
 */

if ( ! function_exists( 'medirashed_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function medirashed_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		medirashed_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'medirashed_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'medirashed_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function medirashed_woocommerce_cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'medirashed' ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'medirashed' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo esc_html( $item_count_text ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'medirashed_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function medirashed_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php medirashed_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}

/******************************************New added code***********************************************/
/* add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 4; // 3 products per row
	}
} */

    // define the woocommerce_before_shop_loop_item_title callback 
    function action_woocommerce_before_shop_loop_item_title(  ) { 
        // make action magic happen here... 
		//echo "OK";
    }; 
             
    // add the action 
    add_action( 'woocommerce_before_shop_loop_item_title', 'action_woocommerce_before_shop_loop_item_title', 10, 0 ); 
// Handle cart in header fragment for ajax add to cart
add_filter('add_to_cart_fragments', 'header_add_to_cart_fragment');
function header_add_to_cart_fragment( $fragments ) {
    global $woocommerce;
 
    ob_start();
 
    woocommerce_cart_link();
 
    $fragments['a.cart-button'] = ob_get_clean();
 
    return $fragments."OOOO";
 
}
 
function woocommerce_cart_link() {
    global $woocommerce;
    ?>
    <a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> <?php _e('in your shopping cart', 'woothemes'); ?>" class="cart-button ">
    <span class="label"><?php _e('My Basket:', 'woothemes'); ?></span>
    <?php echo $woocommerce->cart->get_cart_total();  ?>
    <span class="items"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count); ?></span>
    </a>
    <?php
}

/* add_action('woocommerce_after_shop_loop_item','replace_add_to_cart');
add_action('woocommerce_after_shop_loop_item','replace_add_to_cart');
function replace_add_to_cart() {
    global $product;
    $link = $product->get_permalink();
    echo do_shortcode('<a href="'.$link.'" class="button addtocartbutton">Learn more</a>');
} */

/**
 * WooCommerce Extra Feature
 * --------------------------
 *
 * Add custom fee to cart automatically
 *
 */

 
 function register_rml_scripts() {
	wp_register_script( 'rml-script', get_template_directory_uri().'/js/my_ajax.js', array('jquery'),'', true);
	
	wp_enqueue_script( 'rml-script' );
	wp_localize_script( 'rml-script', 'readmelater_ajax', array( 'ajax_url' => admin_url('admin-ajax.php')) );
	
	
} 
add_action('wp_enqueue_scripts','register_rml_scripts');


function add_extra_charge(){

	die();
}
add_action( 'wp_ajax_add_extra_charge_to_total', 'add_extra_charge' );
add_action( 'wp_ajax_nopriv_add_extra_charge_to_total', 'add_extra_charge' );


/*  
function woo_add_cart_fee(){
	global $woocommerce;
	
  $woocommerce->cart->add_fee( __('Courier Charges:', 'woocommerce'), 50 );
}
add_action( 'woocommerce_cart_calculate_fees', 'woo_add_cart_fee' ); */


function wooc_extra_register_fields() {?>
		
				<div class="login-checkout-title">
					<h2>User log in & Checkout</h2>
				</div>
				
	    <p class="form-row form-row-wide">
		   <label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?></label>
		   <input required type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php esc_attr_e( $_POST['billing_phone'] ); ?>" />
       </p>
				
			<div class="loginCheckout-area">
				<div class="row">
					<div class="col-md-6">
						<div class="chechout-patient-details">
							<form action="" method="">
								<div class="patient-name patientInput">
									<label for="reg_billing_first_name"><?php _e( 'Patients Name', 'woocommerce' ); ?></label>
									<input required class="mr-3" type="text" name="billing_first_name" id="reg_billing_first_name" placeholder="First Name"  value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>"  />
									<input required type="text" id="name" placeholder="Last Name" name="billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
								</div>
								<div class="patient-DOB patientInput">
									<label for="DOB">Date of Birth</label>
									<input type="text" id="DOB" placeholder="Select Birth Date">
									<i class="far fa-calendar-alt selectDate"></i>
									<div class="patient-gender">
											<span>Gender</span>
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="radio" name="inlineRadioOptions" id="homeVisit" value="option1">
												<label class="form-check-label" for="homeVisit">Male</label>
											</div>
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
												<label class="form-check-label" for="labVisit">Female</label>
											</div>
										</div>
								</div>
								<div class="patient-email patientInput">
									<label for="email">Email</label>
									<input type="email" id="email" placeholder="Enter your Email">
								</div>
								<div class="patient-contact patientInput">
									<label for="contact">Contact</label>
									<input type="text" id="contact" placeholder="Enter Contact No">
								</div>
								<div class="patient-preference patientInput">
									<label for="preference">Preference</label>
									<input type="text" id="preference" placeholder="Select Date for Test">
									<i class="far fa-calendar-alt selectDate"></i>
									<div class="preferenceSelect">
										<select class="patientInput" name="" id="time-slot">
											<option value="">Select Time Slot</option>
											<option value="">10.00</option>
											<option value="">11.00</option>
											<option value="">12.00</option>
										</select>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="col-md-6 bl">
						<div class="chechout-patient-address">
							<div class="patient-adress-title">
								<h6>Select your address for Home Collection</h6>
								<p>Blood collection at home facilities available across Mumbai, Navi Mumbai & Thane district</p>
							</div>
							<div class="home-checkbox">
								<ul>
									<li>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
											<label class="form-check-label" for="labVisit">My Home</label>
										</div>
										<span>A601, Kailash tower...</span>
									</li>
									<li>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
											<label class="form-check-label" for="labVisit">Dad's Home</label>
										</div>
										<span>A601, Kailash tower...</span>
									</li>
									<li>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
											<label class="form-check-label" for="labVisit">My Home 2</label>
										</div>
										<span>A601, Kailash tower...</span>
									</li>
								</ul>
							</div>
							<div class="checkout-address-form">
								<span>or Add new address</span>
								<div class="patient-address patientInput">
									<label for="house">House No, Society</label>
									<input type="text" id="house" placeholder="ie. B-202, XYZ Co Operative Housing Society">
								</div>
								<div class="patient-street patientInput">
									<label for="street">Street Name</label>
									<input type="text" id="street" placeholder="ie. XYZ Street">
								</div>
								<div class="patient-pincode-area">
									<div class="patient-pincode">
										<label for="street">Pincode</label>
										<input type="text" id="street" placeholder="ie. 400020">
									</div>
									<div class="patient-locality">
										<label for="street">Locality</label>
										<input type="text" id="street" placeholder="Your Locality">
									</div>
								</div>
								<div class="patient-city patientInput">
									<label for="city">City</label>
									<input type="text" id="city" placeholder="Your City">
								</div>
								<div class="patient-save-address patientInput">
									<input type="checkbox" name="" id="">
									<label for="city">Save address as</label>
									<input type="text" id="city" placeholder="ie. My Home">
								</div>
							</div>
							<div class="select-payment-mode">
								<div class="payment-mode-title">
									<h6>Select Payment Mode</h6>
									<p>(<span>Extra 5% Discount </span> for all online payments)</p>
								</div>
								<div class="payment-mode-checkbox">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
										<label class="form-check-label" for="labVisit">Online Payment</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
										<label class="form-check-label" for="labVisit">Pay Cash at Checkin</label>
									</div>
								</div>
							</div>
							<div class="checkout-price">
								<div class="total-price">
									<table>
										<tr>
											<td>Cart Total:</td>
											<td>Rs.2850/-</td>
										</tr>
										<tr>
											<td>Extra 5% Discount: </td>
											<td>- Rs.142/-</td>
										</tr>
									</table>
									<div class="total-payable">
										<ul>
											<li>Total Payable:</li>
											<li>Rs.2707/-</li>
										</ul>
									</div>
								</div>
								<div class="confirm-checkout">
									<button id="confirmCheck">CONFIRM & PROCEED</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
      
  
       <div class="clear"></div>
       <?php
 }
// add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );
 
 
 
 /*     function woocommerce_edit_my_account_page() {
        return apply_filters( 'woocommerce_forms_field', array(
            'woocommerce_my_account_page' => array(
                'type'        => 'text',
                'label'       => __( 'Socail Media Profile Link', ' cloudways' ),
                'placeholder' => __( 'Profile Link', 'cloudways' ),
                'required'    => false,
            ),
        ) );
    }
    function edit_my_account_page_woocommerce() {
        $fields = woocommerce_edit_my_account_page();
        foreach ( $fields as $key => $field_args ) {
            woocommerce_form_field( $key, $field_args );
        }
    } */
//add_action( 'woocommerce_register_form', 'edit_my_account_page_woocommerce', 15 );
 
 
 
 
 
     /**
    * register fields Validating.
    */
    /* function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
          if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
                 $validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
          }
          if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
                 $validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
          }
             return $validation_errors;
    } */
 //add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );
 
 
 
 /******************************* Adding custom sorting option*******************************/
 
 function cw_add_postmeta_ordering_args( $args_sort_cw ) {

	$cw_orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) :
        apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	switch( $cw_orderby_value ) {
		case 'home_visit':
			$args_sort_cw['orderby'] = 'meta_value_num';
			$args_sort_cw['order'] = 'desc';
			$args_sort_cw['meta_key'] = 'home_visit';
			break;
       case 'lab_visit':
            $args_sort_cw['orderby'] = 'meta_value';
            $args_sort_cw['order'] = 'asc';
            $args_sort_cw['meta_key'] = 'lab_visit';
            break;

	}

	return $args_sort_cw;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'cw_add_postmeta_ordering_args' );
function cw_add_new_postmeta_orderby( $sortby ) {
   $sortby['home_visit'] = __( 'Sort By Home Visit', 'woocommerce' );
   $sortby['lab_visit'] = __( 'Sort By Lab Visit', 'woocommerce' );
   return $sortby;
}
add_filter( 'woocommerce_default_catalog_orderby_options', 'cw_add_new_postmeta_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'cw_add_new_postmeta_orderby' );
