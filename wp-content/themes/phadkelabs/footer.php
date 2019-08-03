<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Phadkelabs
 */
 global $phadkelabs;

?>

<!--==============================================-->
	<!--========= Contact Area Start ================-->
	<!--==============================================-->
	
<?php 
	if(!is_home() && !is_404() && !is_page_template( array('template-about-us.php', 'template-team.php') ) && !is_page('contact-us') && !is_page('upload-prescription')  && !is_page('my-account') && !is_cart() && !is_checkout() && !is_checkout_pay_page()){
		if(function_exists('home_visiting')) {
			home_visiting();
		}
		if(function_exists('the_testimonial_section')) {
			the_testimonial_section(); 
		}
	}
?>

	<section class="contact-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 offset-lg-3 col-md-7 offset-md-2">
					<div class="form-area">
						<h2>Got a query? Get in touch with us</h2>
						
							<div class="row">
								<div class="col-md-12">
									<?php echo do_shortcode('[contact-form-7 id="2167" title="Contact us"]'); ?>
								</div>
							</div>
						
					</div>
				</div>
			</div>
		</div>
	</section>

	<!--==============================================-->
	<!--========= Contact Area End ==================-->
	<!--==============================================-->



	<!--==============================================-->
	<!--=========== Footer Area Start ================-->
	<!--==============================================-->

	<footer class="footer-area">
		<div class="container">
			<div class="row">
				<!-- Single Footer -->
				<div class="col-lg-3 col-sm-6 col-12">
					<div class="footer-contact">
						<ul>
							<li>
								<i class="fas fa-map-marker-alt"></i>
								<span>Udyam, Ranade Road, Shivaji Park Dadar (W), Mumbai 28</span>
							</li>
							<li>
								<i class="fa fa-envelope"></i>
								<a href="mailto:contact@phadkelabs.com">contact@phadkelabs.com</a></li>
							<li>
								<i class="fas fa-phone-alt"></i>
								<a href="callto:+022 4890 0114">+022 4890 0114</a></li>
							<li>
								<i class="fab fa-whatsapp"></i>
								<a href="https://api.whatsapp.com/send?phone=919819938916">+91 9819938916</a></li>
						</ul>
					</div>
				</div>
				<!-- Single Footer -->
				<div class="col-lg-3 col-sm-6 col-12">
					<div class="footer-contact">
						<ul>
							<li><a href="#">Log in</a>/<a href="#">Sign up</a>
							</li>
							<li><a href="/about-us/">about us</a></li>
							<li><a href="#">How to do standard test</a></li>
							<li><a href="#">Our partner doctors & hospitals</a></li>
						</ul>
					</div>
				</div>
				<!-- Single Footer -->
				<div class="col-lg-2 col-sm-3 col-6">
					<div class="footer-contact">
						<ul>
							<li><a href="#">Careers</a></li>
							<li><a href="#">Corporate Enquiries</a></li>
							<li><a href="#">News & Media</a></li>
							<li><a href="/contact-us/">Contact Us</a></li>
						</ul>
					</div>
				</div>
				<!-- Single Footer -->
				<div class="col-lg-2 col-sm-3 col-6">
					<div class="footer-contact">
						<ul>
							<li><a href="#">Blog</a></li>
							<li><a href="#">Faqs</a></li>
							<li><a href="https://www.srl.in/srlphadke/secure/CCLogin.aspx?AspxAutoDetectCookieSupport=1">Download Reports</a></li>
						</ul>
					</div>
				</div>
				<!-- Single Footer -->
				<div class="col-lg-2 col-sm-6 col-12">
					<div class="footer-newsletter">
						<div class="newslatter-wrap">
							<h6>Subscribe Newsletter</h6>
							<?php echo do_shortcode('[contact-form-7 id="168" title="Newsletter Subscribe"]'); ?>
						</div>
						<div class="footer-social">
							<h5>follow Us</h5>
							<?php echo  do_shortcode('[social_icons_group id="142"]'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="copyright-area">
				<ul>
					<li> &copy; All Rights Reserved</li> |
					<li><a href="#">Terms & Conditions</a></li> |
					<li><a href="#">Privacy Policy</a></li> |
					<li><a href="#">Refund Policy</a></li>
				</ul>
			</div>
		</div>
	</footer>

	<!-- Back To Top -->
	<a href="#" class="cd-top js-cd-top">
	    <i class="fas fa-angle-up"></i>
	</a>

	<!--==============================================-->
	<!--=========== Footer Area End ==================-->
	<!--==============================================-->

	
	<!-- Material Design Bootstrap -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.7/js/mdb.min.js"></script>


    <?php wp_footer(); ?>
	
	<script type="text/javascript">
		jQuery('.single_product_show_more_content').fadeOut();
		jQuery('.show-more').toggle(function(){
			jQuery('.single_product_show_more_content').fadeIn(2000);
		});
		jQuery('table').addClass('table table-bordered');
		jQuery('.button').addClass('btn btn-primary');
		jQuery('.checkout-button').addClass('btn btn-primary');
		jQuery('.wcppec-checkout-buttons__button').addClass('btn btn-primary');
		
		jQuery('#place_order').addClass('btn btn-primary');
		
		jQuery('.submit').addClass('btn btn-primary');
		
		
		jQuery('.submit_button').attr('value','→');
		
		
		jQuery('.cart').on('click', function(){
			jQuery(this).addClass('added_cart');
		});
		
		jQuery('.single_product_show_more_content').on('click', function(){
			//jQuery(this).addClass('added_cart');
			//alert("Works");
		});
		
		/* jQuery('.remove_from_cart_button').on('click', function(){
			//alert("ok");
		}); */
		
	/* 	jQuery("p").hover(function($){
			$('.remove_from_cart_button').css("background-color", "yellow");		  	
		}); */
		
	

	</script>

</body>

</html>