<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();
do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}
$template_ID = get_option('_checkout_page_layout_',true);
$strc_value = get_post_meta( $template_ID, '_tes_layout_layoutContent_odersummary',true) ? get_post_meta( $template_ID, '_tes_layout_layoutContent_odersummary',true) : 'enable';

$layoutid = get_post_meta( $template_ID, '_tes_layout_id',true);
?>
<div id="custom-checkout-pro-active" class="checkout-form-container layout-<?php echo $layoutid; ?> layout-div">
	<div class="info-side1 right custom-checkout-pro-right">
		
		<?php if( $strc_value == 'enable' ){ ?>
			<div class="right-sidebar">
	          	<div class="title-area"><?php if( $layoutid == 3){ echo 'SUMMARY'; }else{ echo 'Summary'; } ?></div>
	      		<ul class="cart-area summery-area">
	          		<li>
						<!-- <div class="summarySubtotal">
						<span class="pull-left"><?php if( $layoutid == 3){ echo 'SUBTOTAL'; }else{ echo 'Subtotal'; } ?></span>  <span class="pull-right"><?php echo get_woocommerce_currency_symbol().WC()->cart->subtotal; ?></span>
						</div> -->
						<?php /*if ( true === WC()->cart->needs_shipping_address() ){ ?>
						<div class="summaryShipping">
							<span><?php if( $layoutid == 3){ echo 'SHIPPING'; }else{ echo 'Shipping'; } ?></span>  
							<span><?php echo WC()->cart->get_cart_shipping_total(); ?></span>
						</div>
						<?php } */?>

						<?php /*foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
							<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
								<span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
								<span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
							</div>
						<?php endforeach;*/ ?>
						<div class="summaryShipping summary_right_box">
							<table class="shop_table woocommerce-checkout-review-order-table">
								<tfoot>

									<tr class="cart-subtotal">
										<th><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
										<td><?php wc_cart_totals_subtotal_html(); ?></td>
									</tr>

									<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
										<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
											<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
											<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
										</tr>
									<?php endforeach; ?>

									<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

										<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

										<?php wc_cart_totals_shipping_html(); ?>

										<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

									<?php endif; ?>

									<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
										<tr class="fee">
											<th><?php echo esc_html( $fee->name ); ?></th>
											<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
										</tr>
									<?php endforeach; ?>

									<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
										<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
											<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
												<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
													<th><?php echo esc_html( $tax->label ); ?></th>
													<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr class="tax-total">
												<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
												<td><?php wc_cart_totals_taxes_total_html(); ?></td>
											</tr>
										<?php endif; ?>
									<?php endif; ?>

									<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

									<tr class="order-total">
										<th><?php _e( 'Total', 'woocommerce' ); ?></th>
										<td><?php wc_cart_totals_order_total_html(); ?></td>
									</tr>

									<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
								</tfoot>
							</table>
						</div>

						<!-- <div class="summaryTotal">
							<span><?php if( $layoutid == 3){ echo 'TOTAL'; }else{ echo 'Total'; } ?></span>  
							<span><?php echo get_woocommerce_currency_symbol().WC()->cart->total; ?></span>
						</div> -->
					</li>
	      		</ul>
			</div>
		<?php } ?>
      
      	<div class="right-sidebar">
            <div class="title-area">
            		In Your Cart (<?php echo WC()->cart->get_cart_contents_count(); ?>)
            		<a style="float: right; font-size: 10px; text-decoration: underline;" href="<?php echo wc_get_cart_url(); ?>">Edit</a>
            </div>
            <ul class="cart-area">
              	
            	<?php
            		foreach ( WC()->cart->get_cart() as $cart_item ) {
				        $product = $cart_item['data'];
				        if(!empty($product)){
				            ?>
				            <li>
			                  	<div class="cartItem-image">
			                  		<?php echo $product->get_image(); ?>
			                  	</div>
			                    <div class="cartItem-summary">
			                      	<span class="cart-title"><?php echo $product->get_title(); ?></span>
			                        <span class="cart-describe">
						                Qty : <?php echo $cart_item['quantity']; ?>
			                        </span>
			                        <span class="cart-price"><?php echo get_woocommerce_currency_symbol().$product->get_price(); ?></span>
			                    </div>
			                </li>
				            <?php
				        }
				    }
            	?>

              	
            </ul>
        </div>
        <?php
        	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$product = $cart_item['data'];
        		$id = $product->get_id();
        		if( get_post_meta( $id, 'product_checkout_desc_text', true ) ){
        			$have_desc[] = $id;
        		}
        	}
        	//print_r($have_desc);
        	if( !empty($have_desc) ){
        		for($i=0; $i<count($have_desc); $i++){
        			$id = $have_desc[$i];
        ?>

	        		<div class="right-sidebar side2 product-description" id="pr#<?php echo $id; ?>">
			        	<div class="cart-area">
			            <?php
			              echo get_post_meta( $id, 'product_checkout_desc_text',true);
			            ?>
			            </div>
			        </div>
        <?php
        		}
        	}
        ?>
        <div class="right-sidebar side2">
        	<div class="cart-area custom-checkout-pro-layout-desc">
            <?php
              echo get_post_meta( $template_ID, '_tes_layout_content_',true);
            ?>
            </div>
        </div>
    </div>
	<div class="form-side1 left custom-checkout-pro-left">
		<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

				<?php if ( $checkout->get_checkout_fields() ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<!-- <div id="customer_details"> -->
					<div class="panel">
						<div class="panel-body" id="billing-panel">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>
					</div>

					<?php if ( true === WC()->cart->needs_shipping_address() ){ ?>
					<div class="panel">
						<div class="panel-body" id="shipping-panel">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					</div>
					<?php } ?>

							<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
					

				<?php endif; ?>

				<?php 
					$adtnl_status  = get_option('custom-checkout-adtnl-info-status') ? get_option('custom-checkout-adtnl-info-status') : 'enable';
					if( $adtnl_status == 'enable' ){

						if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
						
						<div class="panel">
							<div class="panel-body" id="addtn-info">
								<?php //if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>
									<div class="header header-active">
										
											<div class="header header-active">
												<div class="media">
												  	<div class="media-left">
												  		<?php if ( true === WC()->cart->needs_shipping_address() ) { ?>
												    		<h4 class="header-number">3</h4>
												    	<?php }else{ ?>
												    		<h4 class="header-number">2</h4>
												    	<?php } ?>
												  	</div>
												  	<div class="media-body">
														<h2 class="header-title">
															<?php _e( 'Additional information', 'woocommerce' ); ?>
														</h2>
													</div>
												</div>
											</div>
										
									</div>

								<?php //endif; ?>
								<div class="woocommerce-additional-fields panel-details-area">
									<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

									
										<div class="woocommerce-additional-fields__field-wrapper">
											<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
												<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
											<?php endforeach; ?>
										</div>

									<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
									<?php if( $layoutid == 3 || $layoutid == 1){ ?>
									<div class="checkout-support">
									    <?php //echo wc_privacy_policy_text( 'checkout' ); ?>
									</div>
									<a class="btn btn-lg btn-block btn-primary" href="javascript:void(0);">Continue</a>
								<?php } ?>
								</div>
								
							</div>
						</div>
						
				<?php 
						endif;
					}

						if( has_action('woocommerce_checkout_before_order_review') ){
							do_action( 'woocommerce_checkout_before_order_review' ); 
						}
						?>	
						<?php if( $layoutid == 3 || $layoutid == 1){ ?>
							<div class="panel">
								<div class="panel-body">
									<div id="order_review" class="woocommerce-checkout-review-order">
										<div class="header header-active">
											<div class="media">
			                                  	<div class="media-left">
			                                    	<h4 class="header-number">
			                                    		<?php 
			                                    			// if ( true === WC()->cart->needs_shipping_address() ) {
			                                    			// 	echo '3';
			                                    			// }else{
			                                    			// 	echo '2';
			                                    			// }

			                                    			if( $adtnl_status == 'enable' && true === WC()->cart->needs_shipping_address()){
			                                    				echo '4';
			                                    			}
			                                    			if( $adtnl_status == 'disable' && true === WC()->cart->needs_shipping_address()){
			                                    				echo '3';
			                                    			}

			                                    			if( $adtnl_status == 'disable' && false === WC()->cart->needs_shipping_address()){
			                                    				echo '2';
			                                    			}

			                                    			if( $adtnl_status == 'enable' && false === WC()->cart->needs_shipping_address()){
			                                    				echo '3';
			                                    			}
			                                    		?>
			                                    	</h4>
			                                  	</div>
			                                  	<div class="media-body">
			                                    	<h2 class="header-title"><?php _e( 'Order Review &amp; Payment', 'woocommerce' ); ?></h2>
			                                  	</div>
			                              	</div>
										</div>
										<div class="panel-details-area">
											<?php do_action( 'woocommerce_checkout_order_review' ); ?>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>

						<?php if( $layoutid == 2 ){ ?>
							
							<div class="panel">
								<div class="panel-body">
									<div id="order_review" class="woocommerce-checkout-review-order">
										<div class="header header-active">
											<div class="media">
			                                  	<div class="media-left">
			                                    	<h4 class="header-number">
			                                    		<?php 
			                                    			if ( true === WC()->cart->needs_shipping_address() ) {
			                                    				echo '3';
			                                    			}else{
			                                    				echo '2';
			                                    			}
			                                    		?>
			                                    	</h4>
			                                  	</div>
			                                  	<div class="media-body">
			                                    	<h2 class="header-title"><?php _e( 'Order Review &amp; Payment', 'woocommerce' ); ?></h2>
			                                  	</div>
			                              	</div>
										</div>
										<?php 
										remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 ); 
										add_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
										do_action( 'woocommerce_checkout_order_review' ); ?>
									</div>
								</div>
							</div>
							<div class="panel">
								<div class="panel-body">
									<div id="order_review" class="woocommerce-checkout-review-order">
										<div class="header header-active">
											<div class="media">
			                                  	<div class="media-left">
			                                    	<h4 class="header-number">
			                                    		<?php 
			                                    			if ( true === WC()->cart->needs_shipping_address() ) {
			                                    				echo '4';
			                                    			}else{
			                                    				echo '3';
			                                    			}
			                                    		?>
			                                    	</h4>
			                                  	</div>
			                                  	<div class="media-body">
			                                    	<h2 class="header-title"><?php _e( 'Payment Info', 'woocommerce' ); ?></h2>
			                                  	</div>
			                              	</div>
										</div>
										<?php
										remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 ); 
										add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
										do_action( 'woocommerce_checkout_order_review' );
											
										?>
										<?php //do_action( 'woocommerce_checkout_order_review' ); ?>
									</div>
								</div>
							</div>
							<?php //do_action( 'woocommerce_checkout_order_review' ); ?>
						<?php } ?>

                        <?php if (has_action('woocommerce_checkout_after_order_review')) { ?>
    						<div class="panel">
    							<div class="panel-body">
    								<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
    							</div>
    						</div>
    					<?php } ?>
		</form>
				<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
	</div>
</div>