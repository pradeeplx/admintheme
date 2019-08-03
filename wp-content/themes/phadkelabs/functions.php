<?php
/**
 * Phadkelabs functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Phadkelabs
 */

if ( ! function_exists( 'phadkelabs_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function phadkelabs_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Phadkelabs, use a find and replace
		 * to change 'phadkelabs' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'phadkelabs', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		add_theme_support('woocommerce');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'main_menu' => esc_html__( 'Main Menu(Sliding Menu)', 'phadkelabs' ),
			'footer_menu' => esc_html__( 'Footer Menu', 'phadkelabs' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'phadkelabs_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'phadkelabs_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function phadkelabs_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'phadkelabs_content_width', 640 );
}
add_action( 'after_setup_theme', 'phadkelabs_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function phadkelabs_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'phadkelabs' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'phadkelabs' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'phadkelabs_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function phadkelabs_scripts() {
wp_enqueue_style( 'bootstrap-style', get_template_directory_uri().'/css/bootstrap.min.css', array(), '20151215', false );
	wp_enqueue_style( 'bootstrap-style');
	
	wp_enqueue_style( 'fontawesome-style', get_template_directory_uri().'/css/fontawesome-5.min.css', array(), '20151215', false );
	wp_enqueue_style( 'fontawesome-style');
	
	wp_enqueue_style( 'normalize-style', get_template_directory_uri().'/css/normalize.css', array(), '20151215', false );
	wp_enqueue_style( 'normalize-style');
	
	wp_enqueue_style( 'jquery-ui-style', get_template_directory_uri().'/css/jquery-ui.min.css', array(), '20151215', false );
	wp_enqueue_style( 'jquery-ui-style');
	
	wp_enqueue_style( 'owl-carousel-style', get_template_directory_uri().'/css/owl.carousel.min.css', array(), '20151215', false );
	wp_enqueue_style( 'owl-carousel-style');
	
	wp_enqueue_style( 'owl-theme-style', get_template_directory_uri().'/css/owl.theme.default.min.css', array(), '20151215', false );
	wp_enqueue_style( 'owl-theme-style');
	
	wp_enqueue_style( 'backtotp-style', get_template_directory_uri().'/css/backtotp.css', array(), '20151215', false );
	wp_enqueue_style( 'backtotp-style');
	
	wp_enqueue_style( 'woocommerce-style', get_template_directory_uri().'/woocommerce.css', array(), '20151215', false );
	wp_enqueue_style( 'woocommerce-style');
	
	wp_enqueue_style( 'medirashed-style', get_stylesheet_uri() );	
	
	wp_enqueue_style( 'extra-style', get_template_directory_uri().'/css/extra.css', array(), '20151215', false );
	wp_enqueue_style( 'extra-style');
	
	wp_enqueue_style( 'responsive-style', get_template_directory_uri().'/css/responsive.css', array(), '20151215', false );
	wp_enqueue_style( 'responsive-style');
	
	
	
	
	//wp_enqueue_script( 'jquery-script', get_template_directory_uri().'/js/jquery.js', array(), '20151215', true );
	wp_enqueue_script( 'jquery');
	
	wp_enqueue_script( 'popper-script', get_template_directory_uri().'/js/popper.min.js', array(), '20151215', true );
	wp_enqueue_script( 'popper-script');

	wp_enqueue_script( 'bootstrap-script', get_template_directory_uri().'/js/bootstrap.min.js', array(), '20151215', true );
	wp_enqueue_script( 'bootstrap-script');

	wp_enqueue_script( 'modernizr-script', get_template_directory_uri().'/js/modernizr.js', array(), '20151215', true );
	wp_enqueue_script( 'modernizr-script');

	wp_enqueue_script( 'jquery-ui-script', get_template_directory_uri().'/js/jquery-ui.min.js', array(), '20151215', true );
	wp_enqueue_script( 'jquery-ui-script');
	
	wp_enqueue_script( 'counterup-script', get_template_directory_uri().'/js/counterup.min.js', array(), '20151215', true );
	wp_enqueue_script( 'counterup-script');
	
	wp_enqueue_script( 'waypoints-script', get_template_directory_uri().'/js/waypoints.js', array(), '20151215', true );
	wp_enqueue_script( 'waypoints-script');
	
	wp_enqueue_script( 'owl-carousel-script', get_template_directory_uri().'/js/owl.carousel.min.js', array(), '20151215', true );
	wp_enqueue_script( 'owl-carousel-script');
	
//	wp_enqueue_script( 'multiStepsJS-script', get_template_directory_uri().'/multiStepsJS/js/jquery.steps.min.js', array(), '20151215', true );
//	wp_enqueue_script( 'multiStepsJS-script');
	
	wp_enqueue_script( 'datePicker-script', get_template_directory_uri().'/js/datePicker.js', array(), '20151215', true );
	wp_enqueue_script( 'datePicker-script');
	
	//sad

	wp_enqueue_script( 'backtotop-script', get_template_directory_uri().'/js/backtotop.js', array(), '20151215', true );
	wp_enqueue_script( 'backtotop-script');
	
	wp_enqueue_script( 'readmore-scripts', get_template_directory_uri().'/js/readmore.min.js', array(), '20151215', true );
	wp_enqueue_script( 'readmore-scripts');
	
	 
	
	/* wp_enqueue_script( 'my_ajax_script', get_template_directory_uri().'/js/my_ajax.js', array(), '20151215', true );
	wp_enqueue_script( 'my_ajax_script');
	wp_localize_script( 'my_ajax_script', 'add_price_ajax', array( 'ajax_url' => admin_url('admin-ajax.php')) ); */
	
	wp_enqueue_script( 'main-scripts', get_template_directory_uri().'/js/scripts.js', array(), '20151215', true );
	wp_enqueue_script( 'main-scripts');

	wp_enqueue_script( 'medirashed-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'medirashed-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'phadkelabs_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

 $dashboard = get_template_directory() .'/woocommerce/myaccount/custom-myacount/dashboard.php';
if(file_exists($dashboard))
require get_template_directory() .'/woocommerce/myaccount/custom-myacount/dashboard.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/* add_filter( 'woocommerce_cart_item_remove_link', 'custom_filter_wc_cart_item_remove_link', 10, 2 );
function custom_filter_wc_cart_item_remove_link( $sprintf, $cart_item_key ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return $sprintf;

    // HERE Define your additional text
    $add_text = __('Delete item', 'woocommerce');

    // HERE Define the style of the text
    $styles = 'font-size:0.8em; display:block;';

    $sprintf = str_replace('</a>', '</a><span class="remove-text" style="'.$styles.'">'.$add_text.'</span>', $sprintf);

    return $sprintf;
}; */

require get_template_directory() . '/inc/theme-options/ReduxCore/framework.php';
require get_template_directory() . '/inc/theme-options/sample/theme-options.php'; 

/**
 * @snippet       WooCommerce User Login Shortcode
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.6.2
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
// THIS WILL CREATE A NEW SHORTCODE: [wc_login_form_bbloomer]
  
add_shortcode( 'wc_login_form_bbloomer', 'bbloomer_separate_login_form' );
  
function bbloomer_separate_login_form() {
if ( is_admin() ) return;
ob_start();
if ( ! is_user_logged_in() ) {
     
   // NOTE: THE FOLLOWING <FORM> IS COPIED FROM woocommerce\templates\myaccount\form-login.php
   // IF WOOCOMMERCE RELEASES AN UPDATE TO THAT TEMPLATE, YOU MUST CHANGE THIS ACCORDINGLY
   ?>
    
<form class="woocommerce-form woocommerce-form-login login firstForm" method="post">
  
	<?php do_action( 'woocommerce_login_form_start' ); ?>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>  
			</div>
			<div class="form-group">
				<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
           	 	<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox" value="" id="loginCheckbox">
				<label class="form-check-label" for="loginCheckbox">Remember me</label>
			</div>
			<a href="#">Forgot Password?</a>
		</div>
		<div class="col-md-6">
			<div class="mobileVerify">
				<div class="form-group">
					<label for="mobile">Mobile No</label>
					<input type="text" name="" id="mobile"
					placeholder="Enter Mobile Number">
				</div>
				<button name="" type="submit"><i class="fa fa-arrow-right"></i></button>
				<div class="form-group">
					<label for="otp">OTP</label>
					<input type="text" id="otp" name="" placeholder="Enter OTP">
				</div>
				<a href="#">Resend OTP</a>
			</div>
		</div>
	</div>
         <?php do_action( 'woocommerce_login_form' ); ?>
  
            <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
            <button type="submit" class="woocommerce-Button button" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
               <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
            </label>
  
            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
           
         <?php do_action( 'woocommerce_login_form_end' ); ?>
  
      </form>
  
   <?php
   // END OF COPIED HTML
   // ------------------
     
}
return ob_get_clean();
}
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 ); 


add_action('woocommerce_review_order_before_submit','order_place_before_text',10);
function order_place_before_text(){
	?><div class="total-payable">
	<ul>
		<li>Total Payable:</li>
		<li><?php wc_cart_totals_order_total_html() ?></li>
	</ul>
</div>
	<?php
}

function phadkelabs_delete_remove_product_notice(){
	$notices = WC()->session->get( 'wc_notices', array() );
	if(isset($notices['success'])){
		for($i = 0; $i < count($notices['success']); $i++){
			if (strpos($notices['success'][$i], __('removed','woocommerce')) !== false) {
				array_splice($notices['success'],$i,1);
			}
		}
		WC()->session->set( 'wc_notices', $notices['success'] );
	}
}

add_action( 'woocommerce_before_shop_loop', 'phadkelabs_delete_remove_product_notice', 5 );