<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package medirashed
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function medirashed_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'medirashed_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function medirashed_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'medirashed_pingback_header' );

function get_immediate_child_terms($term_id,$taxonomy){
	return get_term_children($term_id, $taxonomy);
}
function get_single_product_package($product_id){
	ob_start();
	?>
	<div class="single-package">
		<a href="<?php the_permalink(); ?>">
			<h4><?php the_title(); ?></h4>
		</a>
		<?php the_content(); ?>
		<h5>Rs. <?php $price = get_post_meta( get_the_ID(), '_regular_price', true); echo $price;?>/-</h5>
		<div class="package-meta">
			<ul>
				<li><a href="/"><img src="<?php echo get_template_directory_uri(); ?>/images/service/home2.png" alt="">home visit</a>
				</li>
				<li><a href="/contact-us/"><img src="<?php echo get_template_directory_uri(); ?>/images/service/lab2.png" alt="">lab visit</a></li>
			</ul>
			<div class="cart">
				<a class="product_type_simple add_to_cart add_to_cart add_to_cart_button ajax_add_to_cart"  data-product_id="<?php the_ID(); ?>" href="<?php the_permalink(); ?>?add-to-cart=<?php the_ID(); ?>"><i class="material-icons">add_shopping_cart</i></a>
			</div>
		</div>
		<span class="package-tag">Recommended</span>
	</div>
	<?php
	return ob_get_clean();
}

function get_term_name_by_id($termid,$taxonomy){
	$term_obj = get_term_by('id',$termid,$taxonomy);
	return $term_obj->name;
}

function woo_is_already_in_cart($product_id) {
	global $woocommerce;         
	foreach($woocommerce->cart->get_cart() as $key => $val ) {
		$_product = $val['data'];

		if($product_id == $_product->id ) {
			return true;
		}
	}         
	return false;
}
/* function action_woocommerce_add_to_cart( ) { 
    echo "Purchase"; 
}  
add_action( 'woocommerce_add_to_cart', 'action_woocommerce_add_to_cart', 10, 3 );  */


/* Testimonial custom post type*/
$testimonial_labels = array(
	'name'               => _x( 'Testimonial', 'post type general name', 'phadkelabs' ),
	'singular_name'      => _x( 'Testimonials', 'post type singular name', 'phadkelabs' ),
	'menu_name'          => _x( 'Testimonials', 'admin menu', 'medi-testimonial' ),
	'name_admin_bar'     => _x( 'Testimonials', 'add new on admin bar', 'phadkelabs' ),
	'add_new'            => _x( 'Add New', 'Testimonial', 'phadkelabs' ),
	'add_new_item'       => __( 'Add New', 'phadkelabs' ),
	'new_item'           => __( 'New testimonial', 'phadkelabs' ),
	'edit_item'          => __( 'Edit testimonial', 'phadkelabs' ),
	'view_item'          => __( 'View testimonial', 'phadkelabs' ),
	'all_items'          => __( 'All testimonials', 'phadkelabs' ),
	'search_items'       => __( 'Search testimonials', 'phadkelabs' ),
	'parent_item_colon'  => __( 'Parent testimonial:', 'phadkelabs' ),
	'not_found'          => __( 'No testimonials Found.', 'phadkelabs' ),
	'not_found_in_trash' => __( 'No Testimonials Found in Trash.', 'phadkelabs' )
	);
	$testimonial_args = array(
		'labels'             => $testimonial_labels,
		'description'        => __( 'Description.', 'phadkelabs' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'medi-testimonial', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-format-status',
		'supports'           => array( 'title','thumbnail','editor' )
	);
	register_post_type( 'medi-testimonial', $testimonial_args );

 // Add new taxonomy for testimonial
	$testimonial_cat_labels = array(
		'name'              => _x( 'Category', 'taxonomy general name', 'phadkelabs' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name', 'phadkelabs' ),
		'search_items'      => __( 'Search Categories', 'phadkelabs' ),
		'all_items'         => __( 'All Categories', 'phadkelabs' ),
		'parent_item'       => __( 'Parent Category', 'phadkelabs' ),
		'parent_item_colon' => __( 'Parent Category:', 'phadkelabs' ),
		'edit_item'         => __( 'Edit Category', 'phadkelabs' ),
		'update_item'       => __( 'Update Category', 'phadkelabs' ),
		'add_new_item'      => __( 'Add New Category', 'phadkelabs' ),
		'new_item_name'     => __( 'New Category Name', 'phadkelabs' ),
		'menu_name'         => __( 'Category', 'phadkelabs' ),
	);

	$testimonial_cat_args = array(
		'hierarchical'      => true,
		'labels'            => $sports_cat_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'testimonial-category' ),
	);

	register_taxonomy( 'testimonial-cat', array( 'medi-testimonial' ), $testimonial_cat_args );
	
	
	
	
	
	
	
/* Offer slider custom post type*/
$slider_labels = array(
	'name'               => _x( 'Offer Slider', 'post type general name', 'phadkelabs' ),
	'singular_name'      => _x( 'Offer Slider', 'post type singular name', 'phadkelabs' ),
	'menu_name'          => _x( 'Offer Slider', 'admin menu', 'medi-testimonial' ),
	'name_admin_bar'     => _x( 'Offer Slider', 'add new on admin bar', 'phadkelabs' ),
	'add_new'            => _x( 'Add New', 'Testimonial', 'phadkelabs' ),
	'add_new_item'       => __( 'Add New Slide', 'phadkelabs' ),
	'new_item'           => __( 'New slide', 'phadkelabs' ),
	'edit_item'          => __( 'Edit slide', 'phadkelabs' ),
	'view_item'          => __( 'View slide', 'phadkelabs' ),
	'all_items'          => __( 'All slides', 'phadkelabs' ),
	'search_items'       => __( 'Search slides', 'phadkelabs' ),
	'parent_item_colon'  => __( 'Parent slide:', 'phadkelabs' ),
	'not_found'          => __( 'No slides Found.', 'phadkelabs' ),
	'not_found_in_trash' => __( 'No slides Found in Trash.', 'phadkelabs' )
	);
	$slider_args = array(
		'labels'             => $slider_labels,
		'description'        => __( 'Description.', 'phadkelabs' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'offer-slider', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-megaphone',
		'supports'           => array( 'title','thumbnail','editor' )
	);
	register_post_type( 'offer-slider', $slider_args );
	
	// Add new taxonomy for offer slider
	$offer_slider_cat_labels = array(
		'name'              => _x( 'Category', 'taxonomy general name', 'phadkelabs' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name', 'phadkelabs' ),
		'search_items'      => __( 'Search Categories', 'phadkelabs' ),
		'all_items'         => __( 'All Categories', 'phadkelabs' ),
		'parent_item'       => __( 'Parent Category', 'phadkelabs' ),
		'parent_item_colon' => __( 'Parent Category:', 'phadkelabs' ),
		'edit_item'         => __( 'Edit Category', 'phadkelabs' ),
		'update_item'       => __( 'Update Category', 'phadkelabs' ),
		'add_new_item'      => __( 'Add New Category', 'phadkelabs' ),
		'new_item_name'     => __( 'New Category Name', 'phadkelabs' ),
		'menu_name'         => __( 'Category', 'phadkelabs' ),
	);

	$offer_slider_cat_args = array(
		'hierarchical'      => true,
		'labels'            => $offer_slider_cat_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'offer-category' ),
	);

	register_taxonomy( 'offer-cat', array( 'offer-slider' ), $offer_slider_cat_args );
	

	
	
	
	/* Offer slider custom post type*/
$team_labels = array(
	'name'               => _x( 'Our Team', 'post type general name', 'phadkelabs' ),
	'singular_name'      => _x( 'Our Team', 'post type singular name', 'phadkelabs' ),
	'menu_name'          => _x( 'Our Team', 'admin menu', 'medi-testimonial' ),
	'name_admin_bar'     => _x( 'Our Team', 'add new on admin bar', 'phadkelabs' ),
	'add_new'            => _x( 'Add New', 'Testimonial', 'phadkelabs' ),
	'add_new_item'       => __( 'Add New member', 'phadkelabs' ),
	'new_item'           => __( 'New member', 'phadkelabs' ),
	'edit_item'          => __( 'Edit member', 'phadkelabs' ),
	'view_item'          => __( 'View member', 'phadkelabs' ),
	'all_items'          => __( 'All team members', 'phadkelabs' ),
	'search_items'       => __( 'Search team members', 'phadkelabs' ),
	'parent_item_colon'  => __( 'Parent team member:', 'phadkelabs' ),
	'not_found'          => __( 'No team members Found.', 'phadkelabs' ),
	'not_found_in_trash' => __( 'No team members Found in Trash.', 'phadkelabs' )
	);
	$team_args = array(
		'labels'             => $team_labels,
		'description'        => __( 'Description.', 'phadkelabs' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'our-team', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-groups',
		'supports'           => array( 'title','thumbnail','editor' )
	);
	register_post_type( 'our-team', $team_args );
		
	/* Offer slider custom post type*/
	$journey_labels = array(
	'name'               => _x( 'Our Journey', 'post type general name', 'phadkelabs' ),
	'singular_name'      => _x( 'Our Journey', 'post type singular name', 'phadkelabs' ),
	'menu_name'          => _x( 'Our Journey', 'admin menu', 'medi-testimonial' ),
	'name_admin_bar'     => _x( 'Our Journey', 'add new on admin bar', 'phadkelabs' ),
	'add_new'            => _x( 'Add New', 'Testimonial', 'phadkelabs' ),
	'add_new_item'       => __( 'Add New Journey', 'phadkelabs' ),
	'new_item'           => __( 'New Journey', 'phadkelabs' ),
	'edit_item'          => __( 'Edit Journey', 'phadkelabs' ),
	'view_item'          => __( 'View Journey', 'phadkelabs' ),
	'all_items'          => __( 'All Our Journey', 'phadkelabs' ),
	'search_items'       => __( 'Search Our Journey', 'phadkelabs' ),
	'parent_item_colon'  => __( 'Parent Our Journey:', 'phadkelabs' ),
	'not_found'          => __( 'No Journey Found.', 'phadkelabs' ),
	'not_found_in_trash' => __( 'No Journey Found in Trash.', 'phadkelabs' )
	);
	$journey_args = array(
		'labels'             => $journey_labels,
		'description'        => __( 'Description.', 'phadkelabs' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'our-journey', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-image-rotate',
		'supports'           => array( 'title','thumbnail','editor' )
	); 
	register_post_type( 'our-journey', $journey_args );
	
	/* Offer slider custom post type*/
$slider_labels = array(
	'name'               => _x( 'Offer Services', 'post type general name', 'phadkelabs' ),
	'singular_name'      => _x( 'Offer Service', 'post type singular name', 'phadkelabs' ),
	'menu_name'          => _x( 'Offer Services', 'admin menu', 'medi-testimonial' ),
	'name_admin_bar'     => _x( 'Offer Services', 'add new on admin bar', 'phadkelabs' ),
	'add_new'            => _x( 'Add New', 'Testimonial', 'phadkelabs' ),
	'add_new_item'       => __( 'Add New Service', 'phadkelabs' ),
	'new_item'           => __( 'New service', 'phadkelabs' ),
	'edit_item'          => __( 'Edit service', 'phadkelabs' ),
	'view_item'          => __( 'View service', 'phadkelabs' ),
	'all_items'          => __( 'All services', 'phadkelabs' ),
	'search_items'       => __( 'Search services', 'phadkelabs' ),
	'parent_item_colon'  => __( 'Parent service:', 'phadkelabs' ),
	'not_found'          => __( 'No services Found.', 'phadkelabs' ),
	'not_found_in_trash' => __( 'No services Found in Trash.', 'phadkelabs' )
	);
	$slider_args = array(
		'labels'             => $slider_labels,
		'description'        => __( 'Description.', 'phadkelabs' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'offer-service', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-buddicons-pm',
		'supports'           => array( 'title','thumbnail' )
	);
	register_post_type( 'offer-service', $slider_args );
	
	
	
function the_testimonial_section(){ 
global $phadkelabs;
	?>
	<!--==============================================-->
	<!--========= Testimonial Area Start =============-->
	<!--==============================================-->
	<section class="testimonial-area section-padding">
		<div class="container">
			<div class="section-head">
				<h2>Testimonials</h2>
			</div>
			<?php 
			$p_query = new WP_Query(array(
					'post_type' => 'medi-testimonial',
					'posts_per_page' => -1,
					'tax_query' => array(array(
						'taxonomy' => 'testimonial-cat',
						'field' => 'slug',
						'terms' => array('home-testimonial'),
					))
				));
			?>
			<div class="testimonial owl-carousel">
			
			<?php
				while($p_query->have_posts()): $p_query->the_post();
				$testimonial_author = get_field('testimonial_author');
			?>
			
				<!-- Single Testimonial -->
				<div class="single-testimonial">
					<h6><?php the_title(); ?></h6>
					<span> <?php if($testimonial_author) echo $testimonial_author; ?> </span>
					<p><?php echo wp_trim_words(get_the_content(),30,'...'); ?></p>
					<span class="read-btn"><a href="#" data-toggle="modal" data-target="#testimonial-<?php echo get_the_ID(); ?>">Read More</a></span>
					
				</div>
				<!-- Single Testimonial Modal  -->
							
				<?php endwhile; ?>
			</div>

			<?php
				while($p_query->have_posts()): $p_query->the_post();
				$testimonial_author = get_field('testimonial_author');
			?>
			<!-- Modal -->
			<div class="modal fade" id="testimonial-<?php echo get_the_ID();  ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
				<div class="modal-dialog testimonial-modal modal-dialog-scrollable" role="document">
					<div class="modal-content">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-12">
									<div class="single-testimonial">
										<h6><?php the_title(); ?></h6>
										<span><?php echo esc_html( $testimonial_author ); ?></span>
										<?php the_content(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- Single Testimonial Modal End  -->	
			<?php endwhile; ?>
		</div>
	</section>
	
	<!--==============================================-->
	<!--========= Testimonial Area End ===============-->
	<!--==============================================-->
	
	<?php 
	
}


function home_visiting(){
	global $phadkelabs;
	?>
	<section class="home-visiting section-padding">
		<div class="container">
			<div class="visit">
				<?php
					$home_visiting_section_caption = $phadkelabs['home_visiting_section_caption'];
					$home_visiting_section_subtitle = $phadkelabs['home_visiting_section_subtitle'];
					$home_visiting_section_details = $phadkelabs['home_visiting_section_details'];
				?>
			
				<h2><?php if($home_visiting_section_caption) echo $home_visiting_section_caption; ?></h2>
				<p><?php if($home_visiting_section_subtitle) echo $home_visiting_section_subtitle; ?></p>
				<?php if($home_visiting_section_details) echo $home_visiting_section_details; ?>
			</div>
		</div>
	</section>
	<?php
}
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );