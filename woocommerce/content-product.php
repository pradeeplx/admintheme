<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	//do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * Hook: woocommerce_before_shop_loop_item_title.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	

	/**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	

	/**
	 * Hook: woocommerce_after_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */

	
	?>
		<div class="single-package">
		
			<?php
				//$product = wc_get_product( the_ID() );
				//do_action( 'woocommerce_before_shop_loop_item_title' ); 
			?>
			<a href="<?php the_permalink(); ?>"> <?php do_action( 'woocommerce_shop_loop_item_title' ); ?></a>
			<?php //do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
			<?php the_content(); ?>
			<h5>Rs. <?php $price = get_post_meta( get_the_ID(), '_regular_price', true); echo $price;?>/-</h5>
			<div class="package-meta">
				<ul>
					<li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/service/home2.png" alt="">home visit <?php if(get_field('home_visit')): ?>(<?php  echo get_field('home_visit');?>)<?php endif; ?></a>
					</li>
					<li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/service/lab2.png" alt="">lab visit<?php if(get_field('lab_visit')): ?>(<?php  echo get_field('lab_visit');?>)<?php endif; ?></a></li>
				</ul>
				<div class="cart">
					<?php $in_cart = woo_is_already_in_cart(get_the_ID()); ?>
					<a class="product_type_simple add_to_cart add_to_cart add_to_cart_button ajax_add_to_cart"  data-product_id="<?php the_ID(); ?>" href="<?php the_permalink(); ?>?add-to-cart=<?php the_ID(); ?>"><i class="material-icons">add_shopping_cart</i>
					</a>
					 
			
				</div>
			</div>
			<?php if(get_field('product_status')): ?><span class="package-tag"><?php echo get_field('product_status'); ?></span><?php endif; ?>
		</div>
	<?php

	/**
	 * Hook: woocommerce_after_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	//do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
