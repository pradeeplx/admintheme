<?php
/*
	Template Name: Comprehensive profile
 */

get_header();
global $phadkelabs;
?>
<div class="main_content_area">
	<section class="booktest-banner  margin-top">
		<div class="container">
			<div class="comprehensive-profile">
				<div class="comprehesive-profile-head">
					<h6>At Dr. Phadke labs, we have dedicated well researched profiles catering to different medical
						conditions</h6>
					<p>There are Comprehensive Test profiles dedicated to each and every disorder. These respective
						profiles will have the necessary individual tests needed to be done for that particular medical
						condition.</p>
				</div>
				<div class="comprehensive-profile-title">
					<h2>Comprehensive Profile</h2>
				</div>
				<div class="comprehensive-profile-info">
				
					<?php
						$cmp_profiles = get_terms(array(
							'taxonomy' => 'product_cat',
							'parent' => 19,
							'hide_empty' => false,
						));
						$count=1;
						foreach($cmp_profiles as $single_profile_cat){	
						 $term_name = $single_profile_cat->name;
						 $term_link = get_term_link($single_profile_cat->term_id, 'product_cat');
						 $p_term_image_id = get_woocommerce_term_meta($single_profile_cat->term_id,'thumbnail_id',true);
						 $term_thumb = wp_get_attachment_image_src($p_term_image_id,'shop_catalog');
						 
						 $child_terms = get_term_children($single_profile_cat->term_id,'product_cat');						 
						 $total_child = count($child_terms);
						
					?>
				
					<!-- Single Comprehensive Profile -->
					<div class="single-comprehensive-profile">
						<a href="<?php if($total_child>0) echo esc_url(home_url(''))."/comprehensive-profile-category?termid=".$single_profile_cat->term_id; else echo  $term_link; ?>">
							<div class="comprehensive-img">
								<img src="<?php if($term_thumb): echo $term_thumb[0]; else: echo get_template_directory_uri(); ?>/images/comprehensive/banner/<?php echo $count++; ?>.png<?php endif; ?>" alt="">
							</div><?php echo $term_name; ?>
						</a>
					</div>
					
					<?php } ?>
					
				</div> <!-- Comprehensive Profile Info End -->
			</div> <!-- Comprehensive Profile End -->
			<div class="booktest-banner-menu">
				<div class="row">
					<div class="col-lg-5 col-12">
						<div class="booktest-form">
							<div class="product-search-box">
								<form class="search-form" role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
									<input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field" placeholder="<?php echo esc_attr__( 'Search for test/profile&hellip;', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
									<button type="submit" value="<?php echo esc_attr_x( '', 'submit button', 'woocommerce' ); ?>">
										<i class="fa fa-search"><?php echo esc_html_x( '', 'submit button', 'woocommerce' ); ?></i>
									</button>
									<input type="hidden" name="post_type" value="product" />
								</form>
							</div>
						</div>
					</div>
					<div class="col-lg-7 col-12">
						<div class="booktest-menu">
							<ul>
								<li><a href="index.html">
										<img src="<?php echo get_template_directory_uri(); ?>/images/book-a-test/banner/home.png" alt="">
										Home Visit
									</a></li>
								<li><a href="#">
										<img src="<?php echo get_template_directory_uri(); ?>/images/book-a-test/banner/lab.png" alt="">
										Lab Visit
									</a></li>
								<li class="booktest-select">
									<select name="" id="test-select">
										<option value="all">All</option>
										<option value="test">Comprehensive Profiles</option>
										<option value="test">Test</option>
										<option value="test">Subscription</option>
									</select>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- Package Offer -->
			<div class="service-package-area">
				<div class="package-details">
					<div class="row">
						
						<?php
							/* $post_query = new WP_Query(array(
								'post_type' => 'product'
							)); */
							echo do_shortcode('[products limit="12" columns="4" category="comprehensive-profiles" orderby="date" ]');
						?>
					
					
					</div>
				</div>
			</div> <!-- Test Package End -->
			<div class="pagination-area">
				<ul>
					<li class="prev">Previous</li>
					<li class="active">1</li>
					<li>2</li>
					<li>3</li>
					<li class="dots">...</li>
					<li>8</li>
					<li class="next active">Next</li>
				</ul>
			</div>
		</div>
	</section>

	<!--==============================================-->
	<!--============= Service Area End ===============-->
	<!--==============================================-->


	<!--==============================================-->
	<!--========= Home Visit Area Star================-->
	<!--==============================================-->

		<?php //if(function_exists('home_visiting')) home_visiting(); ?>

	<!--==============================================-->
	<!--========= Home Visit Area End ================-->
	<!--==============================================-->

	
	<!--========= Testimonial Area Start =============-->
	
		<?php //if(function_exists('the_testimonial_section')) the_testimonial_section(); ?>
	<!--========= Testimonial Area End =============-->
		
</div>

<?php
get_footer();
