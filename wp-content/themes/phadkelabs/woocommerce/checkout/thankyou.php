<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="woocommerce-order>
	<?php if ( $order ) : ?>
		<?php if ( $order->has_status( 'failed' ) ) : ?>
			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>
		<?php else : ?>
			<div class="booking-status-area">
				<div class="booking-status-head">
					<ul>
						<li>Order Id: <span><?php echo $order->get_order_number();?></span></li>
						<li>Date of booking: <span><?php echo wc_format_datetime( $order->get_date_created() ); ?></span></li>
						<li>Status: <span>Confirmed</span></li>
					</ul>
					</div>
				<div class="booking-status-body">
					<div class="row">
						<div class="col-md-4">
							<div class="status-patients-details">
								<h6>Patient's Details</h6>
								<?php
									if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() ){
									$user_id = $order->get_user_id();
									$Name =	get_user_meta( $user_id, 'billing_first_name', true );
									$Name .=" ".get_user_meta( $user_id, 'billing_last_name', true );
									$gender = get_user_meta( $user_id, 'billing_myfield13', true );
									$bod = get_user_meta( $user_id, 'billing_myfield12', true );
									$email = $order->get_billing_email();
									$phone = get_user_meta( $user_id, 'billing_phone', true );
									} 
									?>
								<table class>
									<tbody>
										<tr>
											<td>Patient's Name:</td>
											<td><span><?php echo $Name?></span></td>
										</tr>
										<tr>
											<td>Gender:</td>
											<td><span><?php echo $gender?></span></td>
										</tr>
										<tr>
											<td>Date of Birth:</td>
											<td><span><?php echo $bod?></span></td>
										</tr>
										<tr>
											<td>Email Id:</td>
											<td><span><?php echo $email?></span></td>
										</tr>
										<tr>
											<td>Contact:</td>
											<td><span><?php echo $phone?></span></td>
										</tr>
									</tbody>
									</table>
								</div>
							</div>
						<div class="col-md-8">
							<div class="booking-order-details">
								<h6>Your Order Details</h6>
								<div class="order-title">
									<ul>
										<li>Items in Cart</li>
										<li>Available at</li>
										<li>Price</li>
									</ul>
									</div>
								<div class="order-info">
									<?php
										$items = $order->get_items();
										foreach ( $items as $item ) {
										?>	
											<ul>
												<li><?php echo $item['name']; ?></li>
												<li><img src="<?php echo get_template_directory_uri();?>/images/cart_page/home.png" alt=""> Home Visit (2)</li>
												<li><img src="<?php echo get_template_directory_uri();?>/images/cart_page/lab.png" alt=""> Lab Visit</li>
												<?php 
													$id = $item['product_id'];
													$product = wc_get_product( $id );
												?>
												<li><?php echo $product->get_price(); ?></li>
												</ul>
									<?php
											}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
		
				<p class="status-note">Note: The order confirmation and  invoice has been sent to your email ID. For help &amp; support email us at <a href="mailto:contact@phadkelabs.com">contact@phadkelabs.com</a>  or call us at <a href="callto:+022 4890 0114">+022 4890 0114</a></p>
				</div>
		<?php endif; ?>
	<?php else : ?>
		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>
	<?php endif; ?>
</div>
</div>
