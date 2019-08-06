<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Phadkelabs
 */

get_header();
?>



	<!--==============================================-->
	<!--============== Banner Area Start =============-->
	<!--==============================================-->
	
	<?php
		$banner_intro_caption = $phadkelabs['banner_intro_caption'];
		$banner_intro_details = $phadkelabs['banner_intro_details'];
		$banner_intro_banner = $phadkelabs['banner_intro_banner']['url'];
	?>					
	
	<div class="banner-area margin_hide" <?php if($banner_intro_banner): ?> style="background-image:url('<?php echo $banner_intro_banner; ?>')" <?php endif; ?> >
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="banner-info">						
						<h1><?php if($banner_intro_caption) echo $banner_intro_caption; ?></h1>
						<p><?php if($banner_intro_details) echo $banner_intro_details; ?></p>
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
				<div class="col-md-6">
					<div class="responsive-img">
						<img src="<?php echo get_template_directory_uri(); ?>/images/banner/res-img.png" alt="">
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--==============================================-->
	<!--============== Banner Area End ===============-->
	<!--==============================================-->



	<!--==============================================-->
	<!--============== Offer Area Start =============-->
	<!--==============================================-->

	<div class="offer-area">
		<div class="offer owl-carousel">
		
			<?php
				$offer_query = new WP_Query(array(
					'post_type' => 'offer-slider',
					'posts_per_page' => -1,
					'tax_query' => array(array(
						'taxonomy' => 'offer-cat',
						'field' => 'slug',
						'terms' => array('homepage-offer'),
					))
				));
				
				while($offer_query->have_posts()): $offer_query->the_post();
					$booking_button_text = get_field('booking_button_text');
					$booking_button_link = get_field('booking_button_link');
					$offer_validity = get_field('offer_validity');
					$terms_and_conditions = get_field('terms_and_conditions');
				?>
					<!-- Single Content -->
					<div class="single-offer">
						<?php the_content(); ?>
						<a href="<?php if($booking_button_link) echo $booking_button_link; ?>"><?php if($booking_button_text) echo $booking_button_text; ?></a>
						<p><?php if($offer_validity) echo $offer_validity; ?></p>
						<span class="tarms"><?php if($terms_and_conditions) echo $terms_and_conditions; ?></span>
					</div>
				<?php endwhile; ?>
			
		</div>
	</div>

	<!--==============================================-->
	<!--============== Offer Area End ===============-->
	<!--==============================================-->

	<!--==============================================-->
	<!--============= Service Area Start =============-->
	<!--==============================================-->

	<section class="service-area">
		<div class="container">
			<!-- Health Package Offer -->
			<div class="service-package-area health">
				<div class="package-head">
					<h2>The health packages we offer</h2>
					<?php $term_link = get_term_link('health-packages-we-offer','product_cat'); ?>
					<a href="<?php echo $term_link; ?>">view all</a>
				</div>
				<div class="package-details">
					<div class="row jusify-content-center">
					
					
				<?php
				$pro_query = new WP_Query(array(
					'post_type' => 'product',
					'posts_per_page' => 4,
					'tax_query' => array(array(
						'taxonomy' => 'product_cat',
						'field' => 'slug',
						'terms' => array('health-packages-we-offer'),
					))
				));
				
				while($pro_query->have_posts()): $pro_query->the_post();

				?>					
						<!-- Single Package -->
						<div class="col-lg-3 col-md-6">
							<div class="single-package">
								<a href="<?php the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>
								<p><?php the_content(); ?></p>
								<h5>Rs. <?php $price = get_post_meta( get_the_ID(), '_regular_price', true); echo $price;?>/-</h5>
								<div class="package-meta">
									<ul>
										<li><a href="/"><img src="<?php echo get_template_directory_uri(); ?>/images/service/home2.png" alt="">home visit <?php if(get_field('home_visit')): ?> (<?php echo get_field('home_visit');?>)<?php endif; ?></a>
										</li>
										<li><a href="/contact-us/"><img src="<?php echo get_template_directory_uri(); ?>/images/service/lab2.png" alt="">lab visit <?php if(get_field('lab_visit')): ?>(<?php echo get_field('lab_visit');?>)<?php endif; ?></a></li>
									</ul>
									<div class="cart">
										<?php $in_cart = woo_is_already_in_cart(get_the_ID()); ?>
										<a class="product_type_simple add_to_cart add_to_cart add_to_cart_button ajax_add_to_cart"  data-product_id="<?php the_ID(); ?>" href="<?php the_permalink(); ?>?add-to-cart=<?php the_ID(); ?>"><i class="material-icons">add_shopping_cart</i>
										</a>
									</div>
								</div>
								<?php if(get_field('product_status')): ?><span class="package-tag"><?php echo get_field('product_status'); ?></span> <?php endif; ?>
							</div>
						</div>
				<?php endwhile; ?>
						
						
					</div>
				</div>
			</div> <!-- Health Package End -->

			<!-- Test Package Offer -->
			<div class="service-package-area test">
				<div class="package-head">
					<h2>The tests we offer</h2>
					<?php $term_link = get_term_link('tests-we-offer','product_cat'); ?>
					<a href="<?php echo $term_link; ?>">view all</a>
				</div>
				<div class="package-details">
					<div class="row jusify-content-center">
						<?php
							$pro_query = new WP_Query(array(
								'post_type' => 'product',
								'posts_per_page' => 4,
								'tax_query' => array(array(
									'taxonomy' => 'product_cat',
									'field' => 'slug',
									'terms' => array('tests-we-offer'),
								))
							));
							
							while($pro_query->have_posts()): $pro_query->the_post();

							?>					
									<!-- Single Package -->
									<div class="col-lg-3 col-md-6">
										<div class="single-package">
											<a href="<?php the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>
											<p><?php the_content(); ?></p>
											<h5>Rs. <?php $price = get_post_meta( get_the_ID(), '_regular_price', true); echo $price;?>/-</h5>
											<div class="package-meta">
												<ul>
													<li><a href="/"><img src="<?php echo get_template_directory_uri(); ?>/images/service/home2.png" alt="">home visit <?php if(get_field('home_visit')): ?>(<?php echo get_field('home_visit');?>)<?php endif; ?></a>
													</li>
													<li><a href="/contact-us/"><img src="<?php echo get_template_directory_uri(); ?>/images/service/lab2.png" alt="">lab visit <?php if(get_field('lab_visit')): ?>(<?php echo get_field('lab_visit');?>)<?php endif; ?></a></li>
												</ul>
												<div class="cart">
													<?php $in_cart = woo_is_already_in_cart(get_the_ID()); ?>
													<a class="product_type_simple add_to_cart add_to_cart add_to_cart_button ajax_add_to_cart"  data-product_id="<?php the_ID(); ?>" href="<?php the_permalink(); ?>?add-to-cart=<?php the_ID(); ?>"><i class="material-icons">add_shopping_cart</i>
													</a>
												</div>
											</div>
											<?php if(get_field('product_status')): ?><span class="package-tag"><?php echo get_field('product_status'); ?></span> <?php endif; ?>
										</div>
									</div>
							<?php endwhile; ?>
					</div>
				</div>
			</div> <!-- Test Package End -->
		</div>
	</section>

	<!--==============================================-->
	<!--============= Service Area End ===============-->
	<!--==============================================-->


	<!--==============================================-->
	<!--============= Why Choose Us Area Star=========-->
	<!--==============================================-->

	<section class="why-choose-area section-padding">
		<div class="container">
			<div class="section-head">
				<h2>Looking for a reason, why Phadke Labs?</h2>
			</div>
			<div class="why-choose-us">
				<div class="row">
					<!-- Single Info -->
					<div class="col-lg-6">
						<div class="single-info">
							<div class="info-img">
								<img src="<?php echo get_template_directory_uri(); ?>/images/choose-area/1.png" alt="">
							</div>
							<div class="info-text">
								<h6>Unique expertise; Rich Experience</h6>
								<p>Pioneers in fertility Pathology with special expertise in Male and Female Infertility
									diagnosis. Latest state of the art equipment and on top of the technological
									development curve</p>
							</div>
						</div>
					</div>
					<!-- Single Info -->
					<div class="col-lg-6">
						<div class="single-info">
							<div class="info-img">
								<img src="<?php echo get_template_directory_uri(); ?>/images/choose-area/2.png" alt="">
							</div>
							<div class="info-text">
								<h6>Focus on ethics; Dedicated to integrity</h6>
								<p>We have built our Organisation with strong moral principles and a dedicated team of
									highly qualified professionals. No compromises and/or biases in quality of our
									testing and outcomes</p>
							</div>
						</div>
					</div>
					<!-- Single Info -->
					<div class="col-lg-6">
						<div class="single-info">
							<div class="info-img">
								<img src="<?php echo get_template_directory_uri(); ?>/images/choose-area/3.png" alt="">
							</div>
							<div class="info-text">
								<h6>Doctor's supervision; Informed service</h6>
								<p>Experienced panel of 35+ doctors and 500+ highly trained, qualified and sincere staff
									focusing on excellent service.
								</p>
							</div>
						</div>
					</div>
					<!-- Single Info -->
					<div class="col-lg-6">
						<div class="single-info">
							<div class="info-img">
								<img src="<?php echo get_template_directory_uri(); ?>/images/choose-area/4.png" alt="">
							</div>
							<div class="info-text">
								<h6>Home collection facility; Quick reporting</h6>
								<p>Experienced technicians with more than 10,000 blood collections. Equipped with the
									latest state-of-the-art equipment delivering high quality outcomes and prompt
									reporting.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="whatsapp-connect">
		<i class="fab fa-whatsapp"></i>
		<p>Connect with us on <a href="https://api.whatsapp.com/send?phone=919819938916">WhatsApp at 9819938916</a> to book a home visit!</p>
	</div>

	<!--==============================================-->
	<!--============= Why Choose Us Area End =========-->
	<!--==============================================-->


	<!--==============================================-->
	<!--========= Testimonial Area Start =============-->
	<!--==============================================-->

	<?php if(function_exists('the_testimonial_section')) the_testimonial_section(); ?>

	<!--==============================================-->
	<!--========= Testimonial Area End ===============-->
	<!--==============================================-->


	<!--==============================================-->
	<!--========= Blood Collection Area Start ========-->
	<!--==============================================-->

	<section class="blood-collection-area section-padding">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-4 col-12 offset-0">
					<div class="blood-collection">
						<h2>The first lab in the city <br> to introduce home blood collections</h2>
						<h6>It works so simple</h6>
						<div class="collection-info">
							<div class="row">
								<!-- Single Collection -->
								<div class="col-lg-3 col-sm-3 col-6 single-collection">
									<img src="<?php echo get_template_directory_uri(); ?>/images/blood-collection/1.png" alt="">
									<p>Select the test available for home collection</p>
								</div>
								<!-- Single Collection -->
								<div class="col-lg-3 col-sm-3 col-6 single-collection">
									<img src="<?php echo get_template_directory_uri(); ?>/images/blood-collection/2.png" alt="">
									<p>Enter your address & time for home collection</p>
								</div>
								<!-- Single Collection -->
								<div class="col-lg-3 col-sm-3 col-6 single-collection">
									<img src="<?php echo get_template_directory_uri(); ?>/images/blood-collection/3.png" alt="">
									<p>Our Technician collect the samples as per your scheduled</p>
								</div>
								<!-- Single Collection -->
								<div class="col-lg-3 col-sm-3 col-6 single-collection">
									<img src="<?php echo get_template_directory_uri(); ?>/images/blood-collection/4.png" alt="">
									<p>Receive the reports of the test in your inbox</p>
								</div>
							</div>
							<a href="/shop/">Book a test now</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<img class="collection-mockup" src="<?php echo get_template_directory_uri(); ?>/images/blood-collection/First Lab in the city 1x.png" alt="">
	</section>

	<!--==============================================-->
	<!--========= Blood Collection Area End ==========-->
	<!--==============================================-->



	<!--==============================================-->
	<!--========= Award Section Area Start ===========-->
	<!--==============================================-->

	<div class="award-section section-padding">
		<div class="container">
			<div class="row">
				<div class="col-lg-7 col-12">
					<div class="award-info">
						<h6>Dr. Avinash Phadke Labs has recievied NABL Accreditation from the Govt. of India.</h6>
						<p>This accreditation is given on the basis of participation in international quality control
							programmes, technical evaluation and the latest ISO guidelines. This means that our reports
							are valid all over the world since the board is affiliated to all the major international
							bodies. We are also a ISO certified company.</p>
					</div>
				</div>
				<div class="col-lg-4 col-12">
					<div class="award-img">
						<ul>
							<li><img src="<?php echo get_template_directory_uri(); ?>/images/award/1.png" alt=""></li>
							<li><img src="<?php echo get_template_directory_uri(); ?>/images/award/2.png" alt=""></li>
							<li><img src="<?php echo get_template_directory_uri(); ?>/images/award/3.png" alt=""></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--==============================================-->
	<!--========= Award Section Area End =============-->
	<!--==============================================-->

	<!--==============================================-->
	<!--============= Blog Area Start ================-->
	<!--==============================================-->

	<div class="bolg-area section-padding">
		<div class="container">
			<div class="blog-heading">
				<h2>What's New</h2>
			</div>
			<div class="row">
				<!-- Single Blog -->
				<div class="col-lg-4 col-md-6 col-12">
					<div class="single-blog">
						<img src="<?php echo get_template_directory_uri(); ?>/images/blog-area/1.jpg" alt="">
						<div class="blog-info">
							<a href="#">Fasting Before a Blood Test? This is
								what you must know!</a>
							<ul>
								<li>By Dr. Ajay Phadke</li>
								<li>June 03, 2019</li>
							</ul>
						</div>
						<span class="blog-tag">new read</span>
					</div>
				</div>
				<!-- Single Blog -->
				<div class="col-lg-4 col-md-6 col-12">
					<div class="single-blog">
						<img src="<?php echo get_template_directory_uri(); ?>/images/blog-area/2.jpg" alt="">
						<div class="blog-info">
							<a href="#">Free RWA Health Check-up Camp,
								Dadar.</a>
							<ul>
								<li>By Dr. Ajay Phadke</li>
								<li>June 03, 2019</li>
							</ul>
						</div>
						<span class="blog-tag">upcoming event</span>
					</div>
				</div>
				<!-- Single Blog -->
				<div class="col-lg-4 col-md-6 col-12">
					<div class="single-blog">
						<img src="<?php echo get_template_directory_uri(); ?>/images/blog-area/3.jpg" alt="">
						<div class="blog-info">
							<a href="#">Free RWA Health Check-up Camp,
								Thane. </a>
							<ul>
								<li>By Dr. Ajay Phadke</li>
								<li>June 03, 2019</li>
							</ul>
						</div>
						<span class="blog-tag">upcoming event</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--==============================================-->
	<!--============= Blog Area End ==================-->
	<!--==============================================-->


<?php
get_footer();
