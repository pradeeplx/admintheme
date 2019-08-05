<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Phadkelabs
 */

?>
<!DOCTYPE HTML>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class=
"ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
   <?php global $phadkelabs; ?>
	<!-- Meta Data -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="author" content="Phadkelabs">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<!-- Favicon / Title Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/images/hero-area/logo.png" />
	
	<!-- Material Design Bootstrap -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.7/css/mdb.min.css" rel="stylesheet">
	<!-- Bact To Top -->
	<link rel="stylesheet" type="text/css" href="css/backtotp.css" media="all" />
	<!-- Fontawesome CDN -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css" media="all" />
	<!-- Material-icons -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,600i,700,700i,800,800i|Rubik:400,400i,500,500i,700&display=swap" rel="stylesheet">
	
	<style type="text/css">
		<?php if(!is_home() || !is_front_page()): ?>
		.main_content_area{
			background-image:url('<?php echo get_template_directory_uri(); ?>/images/cart_page/bg.jpg');
			overflow: hidden;
			background-size: 100%;
			background-repeat: repeat-y;
			
		}
		<?php echo "YYY"; endif; ?>
	</style>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php global $woocommerce; global $phadkelabs; ?>
	<!--[if lt IE 8]>
       <div style=' clear: both; text-align:center; position: relative;'>
         <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
           <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
        </a>
      </div>
    <![endif]-->

	<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->



	<!--==============================================-->
	<!--============== Header Area Start =============-->
	<!--==============================================-->
	<div class="canvas-overlay"></div>
	<header class="header-area">
		<div class="topbar-area">
			<div class="container">
				<div class="row">
					<?php
						$top_bar_title = $phadkelabs['header_top_bar_title'];
						$topbar_email = $phadkelabs['topbar_email'];
						$topbar_phone = $phadkelabs['topbar_phone'];
						$topbar_whatsapp = $phadkelabs['topbar_whatsapp'];
					?>
					<div class="col-lg-6">
						<div class="topbar-title">
							<span><?php if($top_bar_title) echo $top_bar_title; ?></span>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="topbar-contact">							
							<ul>
								<li>
									<i class="fa fa-envelope"></i>
									<a href="mailto:<?php if($topbar_email) echo $topbar_email; ?>"><?php if($topbar_email) echo $topbar_email; ?></a>
								</li>
								<li>
									<i class="fas fa-phone-alt"></i>
									<a href="tel:<?php if($topbar_phone) echo $topbar_phone; ?>"><?php if($topbar_phone) echo $topbar_phone; ?></a>
								</li>
								<li>
									<i class="fab fa-whatsapp"></i>
									<a href="tel:<?php if($topbar_whatsapp) echo $topbar_whatsapp; ?>"><?php if($topbar_whatsapp) echo $topbar_whatsapp; ?></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- End Topbar -->
		<div class="header-body search-header">
			<div class="container">
				<div class="row">
					<div class="col-lg-4 col-md-3 col-sm-4 col-4">
						<div class="header-logo">
							<a href="<?php echo esc_url(home_url('')); ?>"><img src="<?php if($phadkelabs['header_logo']['url']) echo $phadkelabs['header_logo']['url'];  ?>" alt=""></a>
						</div>
					</div>
					<div class="col-lg-4 col-md-1 col-sm-1 col-1">
						<div class="search-header-form">
							<form class="search-form" role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
								<input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field" placeholder="<?php echo esc_attr__( 'Search for test/profile&hellip;', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
								<button type="submit" value="<?php echo esc_attr_x( '', 'submit button', 'woocommerce' ); ?>">
									<i class="fa fa-search"><?php echo esc_html_x( '', 'submit button', 'woocommerce' ); ?></i>
								</button>
								<input type="hidden" name="post_type" value="product" />
							</form>
						</div>
						<div class="search-form2">
							<div class="hiding-search">
								<form class="hiding-search-form" role="search"  action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
									<input type="hidden" name="post_type" value="product" />
									<input type="search" placeholder="Search for test/profile" name="s" value="<?php echo get_search_query(); ?>">
									<button type="submit"><i class="material-icons">search</i></button>
									<i class="material-icons search-close active">close</i>
								</form>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-8 col-sm-7 col-7">
						<div class="header-login">
							<ul>
								<?php if(!is_user_logged_in()): ?>
								<div id="mf-header-login-toggle"><?php echo do_shortcode("[dm-modal]"); ?></div>
								<li class="login-hide">
									<img src="<?php echo get_template_directory_uri(); ?>/images/header/Group 2184.png" alt="">
									<a href="#">Log in</a>
									/
									<a href="#">Sign up</a>
								</li>
								<?php else: ?>
								<li><a href="<?php echo esc_url(home_url('/my-account')); ?>">My Account</a></li>
								<?php endif; ?>
								<li class="d-md-inline-block d-lg-none">
									<button class="popup-search show" type="submit">
										<i class="material-icons">search</i>
									</button>
								</li>
								<li><a class="cart-notify" href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/header/Group 2189.png"
											alt=""><span class="badge"><?php echo WC()->cart->get_cart_contents_count(); ?></span></a></li>
								<li><a class="bars" href="#"><i class="fa fa-bars"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- End Header Body -->
		<div class="header-menu">
			<div class="container">
				<nav class="menu-area canvas">
					<ul>
						<li class="active"><a href="<?php echo esc_url(home_url('/shop')); ?>">BOOK A TEST</a></li>
						<li><a href="<?php echo esc_url(home_url('/comprehensive-profile')); ?>">COMPREHENSIVE PROFILES</a></li>
						<li><a href="<?php echo esc_url(home_url('/contact-us/')); ?>">FIND A LAB</a></li>
						<li><a href="<?php echo esc_url(home_url('/upload-prescription/')); ?>">UPLOAD PRESCRIPTION</a></li>
					</ul>
				</nav>
			</div>
		</div>
	</header> <!-- End Header -->
		
	<!-- Responsive Header -->
	<div class="responsive-header">
		<div class="header-menu">
			<div class="container">
				<nav class="menu-area canvas">
					<ul>
						<li class="active"><a href="booktest.html">BOOK A TEST</a></li>
						<li><a href="comprehensive-profile.html">COMPREHENSIVE PROFILES</a></li>
						<li><a href="#">FIND A LAB</a></li>
						<li><a href="#">UPLOAD PRESCRIPTION</a></li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
    <!-- Responsive Header -->


	<!-- PROMO CODE MODAL AREA -->
	<a class="promocode" href="#">PROMO <br> CODES</a>
	<div class="promocode-area">
		<button class="promo-close"><i class="material-icons">close</i></button>
		<div class="container">
			<div class="row">
				<!-- Single Offer -->
				<div class="col-md-3">
					<div class="single-promo-offer">
						<h2>OFFER</h2>
					</div>
				</div> <!-- Single Offer End -->

				<!-- Single Offer -->
				<div class="col-md-3">
					<div class="single-promo-offer">
						<h2>OFFER</h2>
					</div>
				</div> <!-- Single Offer End -->

				<!-- Single Offer -->
				<div class="col-md-3">
					<div class="single-promo-offer">
						<h2>OFFER</h2>
					</div>
				</div> <!-- Single Offer End -->

				<!-- Single Offer -->
				<div class="col-md-3">
					<div class="single-promo-offer">
						<h2>OFFER</h2>
					</div>
				</div> <!-- Single Offer End -->
			</div>
		</div>
	</div>

	
	<!-- PROMO CODE MODAL AREA -->


	<!-- Off Canvas Menu Area -->

	<div class="canvas-menu-area">
		<button class="canvas-close"><i class="material-icons">close</i></button>
		<div class="canvas-menu">
			<?php wp_nav_menu(array(
				'theme_location' => 'main_menu'
			)); 
			?>
			<ul>
				<li class="login-hide"><a href="#" data-toggle="modal" data-target="#modalLoginForm">Log In</a>-<a href="#" data-toggle="modal" data-target="#modalRegisterForm">Sign Up</a></li>
			</ul>

			<div class="footer-newsletter">
				<h6>Subscribe Newsletter</h6>
				<?php echo do_shortcode('[contact-form-7 id="168" title="Newsletter Subscribe"]'); ?>
				<div class="footer-social">
					<h5>follow Us</h5>
					<?php echo do_shortcode('[social_icons_group id="142"]');?>
				</div>
			</div>
		</div>
	</div>

	<!-- Off Canvas Menu Area -->
	

	<!-- LOGIN POPUP START -->
<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Log in</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
        <div class="md-form mb-5">
          <i class="fas fa-envelope prefix grey-text"></i>
          <input type="email" id="defaultForm-email" class="form-control validate">
          <label data-error="wrong" data-success="right" for="defaultForm-email">Your email</label>
        </div>

        <div class="md-form mb-4">
          <i class="fas fa-lock prefix grey-text"></i>
          <input type="password" id="defaultForm-pass" class="form-control validate">
          <label data-error="wrong" data-success="right" for="defaultForm-pass">Your password</label>
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-default">Login</button>
      </div>
    </div>
  </div>
</div>
<!-- LOGIN POPUP END -->

<!-- SIGN UP POPUP START -->

<div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Sign up</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
        <div class="md-form mb-5">
          <i class="fas fa-user prefix grey-text"></i>
          <input type="text" id="orangeForm-name" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-name">Your name</label>
        </div>
        <div class="md-form mb-5">
          <i class="fas fa-envelope prefix grey-text"></i>
          <input type="email" id="orangeForm-email" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-email">Your email</label>
        </div>

        <div class="md-form mb-4">
          <i class="fas fa-lock prefix grey-text"></i>
          <input type="password" id="orangeForm-pass" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-pass">Your password</label>
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-deep-orange">Sign up</button>
      </div>
    </div>
  </div>
</div>

<!-- SIGN UP END -->


	
	<!-- Add To Cart Area Start -->

	<div class="add-to-cart-area">
			<?php 
			$cartVal = $woocommerce->cart->cart_contents_total; 
			if($cartVal == '0') { 
				echo '<div class="add-to-cart-head empty-cart-head">';
				echo '<h2 class="empty-cart">Hey, Your cart is empty.</h2><button class="canvas-close bg-none text-right"><i class="material-icons">close</i></button>
				</div>
				<div class="add-to-cart-details  cart-table-info woocommerce-cart-form__cart-item ">
					
					<div class="cart-info">
						
					</div>
				</div>
				</div>
				</div> ';
			}
			else { ?>
			<div class="add-to-cart-head">

				<a href="<?php echo esc_url(home_url('/cart')); ?>"> <i class="material-icons">add_shopping_cart</i> <span class="badge"><?php  echo $woocommerce->cart->cart_contents_count; ?></span></a><button class="canvas-close bg-none text-right"><i class="material-icons">close</i></button>
				<p>Cart Total: <span>Rs. <?php $totalamount = $woocommerce->cart->cart_contents_total;echo $totalamount; ?>/-</span></p>
			<?php 
			} 
			echo '</div>';
			?>
			<?php
			if($cartVal == '0') { ?>
				<div class="add-to-cart-body"></div>
			<?php }
			else { ?>
				<div class="add-to-cart-body">
			
				<?php
					 do_action( 'woocommerce_before_cart_contents' ); 
					$items = $woocommerce->cart->get_cart();

						foreach($items as $item => $values) { 
							$_product =  wc_get_product( $values['data']->get_id() );
							//product image
							$getProductDetail = wc_get_product( $values['product_id'] );
							//echo $getProductDetail->get_image(); // accepts 2 arguments ( size, attr )

							//echo "<b>".$_product->get_title() .'</b>  <br> Quantity: '.$values['quantity'].'<br>'; 
							$price = get_post_meta($values['product_id'] , '_price', true);
							//echo "  Price: ".$price."<br>";
							/*Regular Price and Sale Price*/
							//echo "Regular Price: ".get_post_meta($values['product_id'] , '_regular_price', true)."<br>";
							//echo "Sale Price: ".get_post_meta($values['product_id'] , '_sale_price', true)."<br>";
						
				?>
				
				<div class="add-to-cart-details  cart-table-info woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> ">
					<a href="#"><?php echo $_product->get_title(); ?></a>
					<div class="cart-info">
						<h6>Rs. <?php echo $price; ?>/-</h6>
						<p>Quantity: <span><?php //echo $values['quantity']; ?></span></p>
					</div>
					<button><i class="material-icons">

							<?php
								// @codingStandardsIgnoreLine
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fas fa-trash-alt"></i></a>',
									esc_url( wc_get_cart_remove_url( $item ) ),
									__( 'Remove this item', 'woocommerce' ),
									esc_attr( $values['product_id'] ),
									esc_attr( $_product->get_sku() )
								), $item );
							?>
					
					</i></button>
					
				</div>
				
				<?php } ?>
				</div>
				<a class="checkout" href="<?php echo wc_get_page_permalink( 'checkout' ); ?>">Check Out</a>
				</div> 
				<?php } ?>
				<!-- Add To Cart Area End -->

	<!--==============================================-->
	<!--============== Header Area End ===============-->
	<!--==============================================-->
