<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$template_ID = get_option('_checkout_page_layout_',true);
$layoutid = get_post_meta( $template_ID, '_tes_layout_id',true);
?>
<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

	<div class="header header-active">
		<div class="media">
		  	<div class="media-left">
		    	<h4 class="header-number">2</h4>
		  	</div>
		  	<div class="media-body">
				<h2 class="header-title">
					Shipping details
				</h2>
			</div>
		</div>
	</div>

<div class="shipping-area panel-details-area">
	<div class="woocommerce-shipping-fields">
		<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>
		<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
				<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php _e( 'Ship to a different address?', 'woocommerce' ); ?></span>
			</label>
			<div class="shipping_address">

				<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

				<div class="woocommerce-shipping-fields__field-wrapper">
					<?php
						$com_status  = get_option('custom-checkout-company-status') ? get_option('custom-checkout-company-status') : 'enable';
						$addr2_status  = get_option('custom-checkout-address2-status') ? get_option('custom-checkout-address2-status') : 'enable';
						$phone_status  = get_option('custom-checkout-phone-status') ? get_option('custom-checkout-phone-status') : 'enable';

						$fields = $checkout->get_checkout_fields( 'shipping' );

						foreach ( $fields as $key => $field ) {
							$skip_loop2 = false;
							if( $key == 'shipping_company' && $com_status == 'disable' ){
								$skip_loop2 = true;
							}

							if( $key == 'shipping_address_2' && $addr2_status == 'disable' ){
								$skip_loop2 = true;
							}

							if( $key == 'shipping_phone' && $phone_status == 'disable' ){
								$skip_loop2 = true;
							}

							if(!$skip_loop2){
								if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
									$field['country'] = $checkout->get_value( $field['country_field'] );
								}
								woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
							}
						}
					?>
				</div>

				<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>
			</div>
		<?php endif; ?>
	</div>
	
	<?php if( $layoutid == 3 || $layoutid == 1 ){ ?>
		<a class="btn btn-lg btn-block btn-primary" href="javascript:void(0);">Continue</a>
	<?php } ?>
</div>
<?php endif; ?>

