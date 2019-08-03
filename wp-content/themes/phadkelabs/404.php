<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Phadkelabs
 */

get_header();
?>

	<!--==============================================-->
	<!--============= Search Area Start =============-->
	<!--==============================================-->

	<section class="search-error margin-top margin_hide">
		<div class="container">
			<div class="breadcam-area">
				<span>
					<a href="<?php echo esc_url(home_url('')); ?>">Home</a>_
					<a href="<?php echo esc_url(home_url('')); ?>/booktest">Book a test</a>
				</span>
			</div>
			<div class="booktest-banner-menu">
				<div class="row">
					<div class="col-lg-5 col-12">
						<div class="booktest-form">
							<form class="search-form" action="" method="POST">
								<input type="text" name="s" placeholder="Search for test/profile">
								<button type="submit"><img src="<?php echo get_template_directory_uri(); ?>/images/header/Group 1435.png" alt=""></button>
							</form>
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
			<div class="search-error-img">
				<img src="<?php echo get_template_directory_uri(); ?>/images/search/error.png" alt="">
				<p>No search results found <br> Please try some different keywords</p>
			</div>
	</section>
	<!--==============================================-->
	<!--============= Search Area End ================-->
	<!--==============================================-->

<?php
get_footer();
