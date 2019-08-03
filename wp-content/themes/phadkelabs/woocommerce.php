<?php
/*
	This is WooCommerce page template
 */

get_header();
?>
<div class="main_content_area">
	<section class="booktest-banner  margin-top">
		<div class="container">
			<div class="breadcam-area">
		
				<span>
						<?php
							$args = array(
									'delimiter' => '_',
							);
							woocommerce_breadcrumb( $args );
						?>
				</span>
			</div>
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
								<li><a href="<?php echo esc_url(home_url('')); ?>">
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
			<div class="row">
				<div class="col-md-12">
					<?php //echo get_product_search_form(); ?>
					<div class="woocommerce_content woocommerce">
						<?php woocommerce_content(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php
get_footer();
