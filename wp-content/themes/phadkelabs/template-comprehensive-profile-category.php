<?php
/*
	Template Name: Comprehensive profile Category
 */

get_header();
?>

	<!--==============================================-->
	<!--============= Service Area Start =============-->
	<!--==============================================-->
	<?php
		if(isset($_REQUEST['termid'])){
			$termid = $_REQUEST['termid'];
		}
		else{
			$termid = 0;
			echo "No term found!";
		}
		$childs = get_immediate_child_terms($termid,'product_cat');
	?>

	<section class="booktest-banner margin-top margin_hide">
		<div class="container">
			<div class="breadcam-area">
				<span>
					<a href="<?php echo esc_url(home_url('')); ?>">Home</a>_
					<a href="<?php echo esc_url(home_url('/')); ?>">Comprehensive Profile</a>_
					<a href="<?php echo esc_url(home_url('/')); ?>comprehensive-profile-category">Allergy</a>
				</span>
			</div>
		
			<div class=" com-profile-category  profile_sub_category">
				<div class="left-category">
					<div class="category-img">
						<img src="<?php echo get_template_directory_uri(); ?>/images/comprehensive/banner/9.png" alt="">
					</div>
				</div>
				<div class="category-intro">
					<h6>Comprehensive test profile for Allergy</h6>
					<p>There are Comprehensive allergy test profiles dedicated to each and every disorder. These
						respective
						allergy profiles will have the necessary individual tests needed to be done for that particular
						medical condition.</p>
				</div>
			</div>

			<!-- Recommended For Allergy Package Offer -->
			<?php
				foreach($childs as $single_term_id){
					$p_query = new WP_Query(array(
						'post_type' => 'product',
						'posts_per_page' => 4,
						'tax_query' => array(array(
							'taxonomy' => 'product_cat',
							'field' => 'term_id',
							'terms' => $single_term_id
						)),
					));
			
			?>
			<div class="service-package-area">
				<div class="package-head">
					<h2><?php echo get_term_name_by_id($single_term_id,'product_cat'); ?></h2>
				</div>
				<div class="package-details">
					<div class="row jusify-content-center">
						
						<?php 	while($p_query->have_posts()): $p_query->the_post(); ?>
						
						<!-- Single Package -->
						<div class="col-lg-3 col-md-6">
							<?php echo get_single_product_package(get_the_ID()); ?>
						</div>
						
						<?php endwhile; ?>
					
					</div>
				</div>
			</div> 
			<?php } ?>
			<!-- Recommended For Allergy Package Offer End -->


			<div class="comprehensive-profile">
				<div class="comprehensive-profile-title">
					<h2>Popular comprehensive profiles categories</h2>
				</div>
				<div class="comprehensive-profile-info">
					<!-- Single Comprehensive Profile -->
					<div class="single-comprehensive-profile">
						<a href="#">
							<div class="comprehensive-img">
								<img src="<?php echo get_template_directory_uri(); ?>/images/comprehensive/banner/1.png" alt="">
							</div>Heart
						</a>
					</div>
					<!-- Single Comprehensive Profile -->
					<div class="single-comprehensive-profile">
						<a href="#">
							<div class="comprehensive-img">
								<img src="<?php echo get_template_directory_uri(); ?>/images/comprehensive/banner/2.png" alt="">
							</div>Hormones
						</a>
					</div>
					<!-- Single Comprehensive Profile -->
					<div class="single-comprehensive-profile">
						<a href="#">
							<div class="comprehensive-img">
								<img src="<?php echo get_template_directory_uri(); ?>/images/comprehensive/banner/3.png" alt="">
							</div>Infertility
						</a>
					</div>
					<!-- Single Comprehensive Profile -->
					<div class="single-comprehensive-profile">
						<a href="#">
							<div class="comprehensive-img">
								<img src="<?php echo get_template_directory_uri(); ?>/images/comprehensive/banner/4.png" alt="">
							</div>Kidney
						</a>
					</div>
					<!-- Single Comprehensive Profile -->
					<div class="single-comprehensive-profile">
						<a href="#">
							<div class="comprehensive-img">
								<img src="<?php echo get_template_directory_uri(); ?>/images/comprehensive/banner/5.png" alt="">
							</div>Liver
						</a>
					</div>
				</div> <!-- Comprehensive Profile Info End -->
				<div class="com-profile-view">
					<a href="/comprehensive-profile/">view all</a>
				</div>
			</div> <!-- Comprehensive Profile End -->
		</div>
	</section>

	<!--==============================================-->
	<!--============= Service Area End ===============-->
	<!--==============================================-->


	<!--==============================================-->
	<!--========= Home Visit Area Star================-->
	<!--==============================================-->

	<section class="home-visiting section-padding">
		<div class="container">
			<div class="visit">
				<h2>All home visits above the billing of Rs. 500
					will be free.</h2>
				<p>Home visits charges below the billing of Rs. 500 will be as follows</p>
				<ul>
					<li>Rs. 100 per visit (in-case of a test that requires 2 visits, each visit will be charged Rs.100)
					</li>
					<li>Per visit charges will be Rs. 200 on Sundays. Sunday visits will be taken subject to
						availability of technician.</li>
					<li>These rates are applicable for Mumbai, Navi Mumbai & Thane district.</li>
				</ul>
			</div>
		</div>
	</section>

	<!--==============================================-->
	<!--========= Home Visit Area End ================-->
	<!--==============================================-->

	<!--==============================================-->
	<!--========= Testimonial Area Start =============-->
	<!--==============================================-->

	<section class="testimonial-area section-padding">
		<div class="container">
			<div class="section-head">
				<h2>Testimonials</h2>
			</div>
			<div class="testimonial owl-carousel">
				<!-- Single Testimonial -->
				<div class="single-testimonial">
					<h6>-Swanand Dabholkar</h6>
					<span>Patient, Mumbai.</span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
						the industry's standard dummy text ever since the 1500s, when an unknown printer Lorem Ipsum is
						simply . . . </p>
					<span class="read-btn"><button>Read More</button></span>
				</div>
				<!-- Single Testimonial -->
				<div class="single-testimonial">
					<h6>-Swanand Dabholkar</h6>
					<span>Patient, Mumbai.</span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
						the industry's standard dummy text ever since the 1500s, when an unknown printer Lorem Ipsum is
						simply . . . </p>
					<span class="read-btn"><button>Read More</button></span>
				</div>
				<!-- Single Testimonial -->
				<div class="single-testimonial">
					<h6>-Swanand Dabholkar</h6>
					<span>Patient, Mumbai.</span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
						the industry's standard dummy text ever since the 1500s, when an unknown printer Lorem Ipsum is
						simply . . . </p>
					<span class="read-btn"><button>Read More</button></span>
				</div>
				<!-- Single Testimonial -->
				<div class="single-testimonial">
					<h6>-Swanand Dabholkar</h6>
					<span>Patient, Mumbai.</span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
						the industry's standard dummy text ever since the 1500s, when an unknown printer Lorem Ipsum is
						simply . . . </p>
					<span class="read-btn"><button>Read More</button></span>
				</div>
				<!-- Single Testimonial -->
				<div class="single-testimonial">
					<h6>-Swanand Dabholkar</h6>
					<span>Patient, Mumbai.</span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
						the industry's standard dummy text ever since the 1500s, when an unknown printer Lorem Ipsum is
						simply . . . </p>
					<span class="read-btn"><button>Read More</button></span>
				</div>
				<!-- Single Testimonial -->
				<div class="single-testimonial">
					<h6>-Swanand Dabholkar</h6>
					<span>Patient, Mumbai.</span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
						the industry's standard dummy text ever since the 1500s, when an unknown printer Lorem Ipsum is
						simply . . . </p>
					<span class="read-btn"><button>Read More</button></span>
				</div>
				<!-- Single Testimonial -->
				<div class="single-testimonial">
					<h6>-Swanand Dabholkar</h6>
					<span>Patient, Mumbai.</span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
						the industry's standard dummy text ever since the 1500s, when an unknown printer Lorem Ipsum is
						simply . . . </p>
					<span class="read-btn"><button>Read More</button></span>
				</div>
				<!-- Single Testimonial -->
				<div class="single-testimonial">
					<h6>-Swanand Dabholkar</h6>
					<span>Patient, Mumbai.</span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
						the industry's standard dummy text ever since the 1500s, when an unknown printer Lorem Ipsum is
						simply . . . </p> 
					<span class="read-btn"><button>Read More</button></span>
				</div>
				<!-- Single Testimonial -->
				<div class="single-testimonial">
					<h6>-Swanand Dabholkar</h6>
					<span>Patient, Mumbai.</span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
						the industry's standard dummy text ever since the 1500s, when an unknown printer Lorem Ipsum is
						simply . . . </p>
					<span class="read-btn"><button>Read More</button></span>
				</div>
			</div>
		</div>
	</section>


<?php
get_footer();
