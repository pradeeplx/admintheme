<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
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

/** @global WC_Checkout $checkout */
$template_ID = get_option('_checkout_page_layout_',true);
$layoutid = get_post_meta( $template_ID, '_tes_layout_id',true);
?>

<div class="header header-active">
	<div class="media">
	  	<div class="media-left">
	    	<h4 class="header-number">1</h4>
	  	</div>
	  	<div class="media-body">
			<?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>
					<h2 class="header-title"><?php _e( 'Billing &amp; Shipping', 'woocommerce' ); ?></h2>
				
			<?php else : ?>
				<h2 class="header-title"><?php _e( 'Billing details', 'woocommerce' ); ?></h2>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="woocommerce-billing-fields panel-details-area">
	

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<div class="woocommerce-billing-fields__field-wrapper">
		<?php
			$com_status  = get_option('custom-checkout-company-status') ? get_option('custom-checkout-company-status') : 'enable';
			$addr2_status  = get_option('custom-checkout-address2-status') ? get_option('custom-checkout-address2-status') : 'enable';
			$phone_status  = get_option('custom-checkout-phone-status') ? get_option('custom-checkout-phone-status') : 'enable';

			$fields = $checkout->get_checkout_fields( 'billing' );

			foreach ( $fields as $key => $field ) {
				//echo 'KEY=> '.$key;
				$skip_loop = false;
				if( $key == 'billing_company' && $com_status == 'disable' ){
					$skip_loop = true;
				}

				if( $key == 'billing_address_2' && $addr2_status == 'disable' ){
					$skip_loop = true;
				}

				if( $key == 'billing_phone' && $phone_status == 'disable' ){
					$skip_loop = true;
				}

				if(!$skip_loop){
					if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
						$field['country'] = $checkout->get_value( $field['country_field'] );
					}
					woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
				}
			}
		?>

	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
	<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
		<div class="woocommerce-account-fields">
			<?php if ( ! $checkout->is_registration_required() ) : ?>

				<p class="form-row form-row-wide create-account">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ) ?> type="checkbox" name="createaccount" value="1" /> <span><?php _e( 'Create an account?', 'woocommerce' ); ?></span>
					</label>
				</p>

			<?php endif; ?>

			<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

			<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

				<div class="create-account">
					<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
						<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
					<?php endforeach; ?>
					<div class="clear"></div>
				</div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
		</div>
	<?php endif; ?>
	<?php if( $layoutid == 3 || $layoutid == 1 ){ ?>
		<a class="btn btn-lg btn-block btn-primary" href="javascript:void(0);" id="open-shipping">Continue</a>
	<?php } ?>
</div>