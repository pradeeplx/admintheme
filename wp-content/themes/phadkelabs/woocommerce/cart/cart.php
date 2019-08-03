<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="row">
	<div class="col-md-3 col-4">
		<div class="cart-table-title">
			<span>Items in cart</span>
		</div>
	</div> <!-- Single Title End -->
	<div class="col-md-3 col-4">
		<div class="cart-table-title">
			<span>Available At</span>
		</div>
	</div> <!-- Single Title End -->
	<div class="col-md-3 col-4">
		<div class="cart-table-title">
			<span>Price</span>
		</div>	
	</div> <!-- Single Title End -->
</div>
<div class="row">
	<div class="col-md-8">
		<div class="cart-test-table">
			<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
				<?php do_action( 'woocommerce_before_cart_table' ); ?>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>
				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<ul class="cart-table-info woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>" >
						<li class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>" ><a href="#"><?php $p_counter++; ?> 
							<?php
							if ( ! $product_permalink ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $p_counter.'.'.$_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
							} else {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $p_counter.'.'.$_product->get_name() ), $cart_item, $cart_item_key ) );
							}
							do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
							echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.
							// Backorder notification.
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
							}
							?>
						</a></li>
						<li><img src="<?php echo get_template_directory_uri();?>/images/cart_page/home.png" alt=""> Home Visit (2)</li>
						<li><img src="<?php echo get_template_directory_uri();?>/images/cart_page/lab.png" alt=""> Lab Visit</li>
						<li class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>" >		
							<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</li>	
						<li class="product-remove">
						<?php
							// @codingStandardsIgnoreLine
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
							'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-trash-alt"></i></a>',
							esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
							__( 'Remove this item', 'woocommerce' ),
							esc_attr( $product_id ),
							esc_attr( $_product->get_sku() )
							), $cart_item_key );
						?>
						</li>
						</ul>
						<?php
					}
				}
			?>
		</div>
		<?php do_action( 'woocommerce_cart_contents' ); ?>
		<div class="cart-test-more">
			<div class="add-more">		
				<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><i class="fa fa-plus"></i><?php esc_html_e( 'Add More', 'woocommerce' ); ?></a>
			</div>
			<div class="select-preference">
				<h6>Select Preference</h6>
				<div class="preferenceForm">
					<div class="form-check form-check-inline">
						<input class="form-check-input" checked type="radio" name="inlineRadioOptions" id="homeVisit" value="option1">
						<label class="form-check-label" for="homeVisit">Home Visit</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
						<label class="form-check-label" for="labVisit">Lab Visit</label>
					</div>
					<div class="form-check">
						<input class="form-check-input"  type="checkbox" value="" id="cartCheckbox">
						<label class="form-check-label" for="cartCheckbox">Courier Reports (Rs. 50/- Extra)</label>
					</div>
				</div>
			</div>
			<div class="cart-promo-apply actions">
				<?php if ( wc_coupons_enabled() ) { ?>
					<div class="coupon">
						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'ENTER PROMO HERE', 'woocommerce' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply', 'woocommerce' ); ?></button>
						<?php do_action( 'woocommerce_cart_coupon' ); ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php do_action( 'woocommerce_cart_actions' ); ?>
		<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>
</div>

<div class="col-md-4 cart-collaterals">
	<div class="cart-test-price <?php //echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">
		<?php do_action( 'woocommerce_before_cart_totals' ); ?>
		<table class="shop_table" >
			<tr>
				<td>Tests Subtotal: </td>
				<td><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>
			<tr>
				<td class="check_pref_hl">Home Visits Charges:</td>
				<td>Free</td>
			</tr>					
			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<td><?php echo 'Discount ';wc_cart_totals_coupon_label( $coupon ); ?></td>
					<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>
			<tr class="shipping_courier">
					<td>Courier Charges: </td>
					<td>₹50</td>
				</tr>
		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

				
		</table>
		
		
	</div>
							<div class="total-payable">
								<ul>
									<li><?php _e( 'Total Payable', 'woocommerce' ); ?></li>
									<li data-title="<?php esc_attr_e( 'Total Payable', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></li>
								</ul>
								
								
							</div>

							<?php do_action( 'woocommerce_after_cart_totals' ); ?>
									
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

</div>
<div class="cart-footer">
						<p>Blood collection at home facility available across Mumbai, Navi Mumbai & Thane disctrict</p>
						<a id="login-tap" href="#">continue</a>
					</div>	
<?php do_action( 'woocommerce_after_cart' ); ?>
