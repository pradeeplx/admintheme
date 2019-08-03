<?php
/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

	do_action( 'woocommerce_checkout_before_terms_and_conditions' );
	$privacy_txt_show  = get_option('custom-checkout-privacy-txt-show') ? get_option('custom-checkout-privacy-txt-show') : 'enable';

	$terms_txt_show  = get_option('custom-checkout-terms-txt-show') ? get_option('custom-checkout-terms-txt-show') : 'enable';

	if( $privacy_txt_show == 'enable' || $terms_txt_show == 'enable' )
	{
	?>
		<div class="woocommerce-terms-and-conditions-wrapper">
			<?php
			/**
			 * Terms and conditions hook used to inject content.
			 *
			 * @since 3.4.0.
			 * @hooked wc_privacy_policy_text() Shows custom privacy policy text. Priority 20.
			 * @hooked wc_terms_and_conditions_page_content() Shows t&c page content. Priority 30.
			 */
			//do_action( 'woocommerce_checkout_terms_and_conditions' );

			$privacy_policy_page_id = get_option('custom-checkout-privacy-policy-page');
			$terms_page_id = get_option('custom-checkout-terms-conditions-page');

			$privacy_text = get_option('custom-checkout-privacy-policy-text') ? get_option('custom-checkout-privacy-policy-text') : 'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our [cc_privacy_policy].';

			$privacy_page_link = get_page_link($privacy_policy_page_id);
			$privacy_text = str_replace('[cc_privacy_policy]','<a href="'.$privacy_page_link.'" target="_blank;">privacy policy</a>',$privacy_text);

			$terms_text = get_option('custom-checkout-terms-conditions-text') ? get_option('custom-checkout-terms-conditions-text') : 'I have read and agree to the website [cc_terms_condition]';
			$terms_page_link = get_page_link($terms_page_id);
			$terms_text = str_replace( '[cc_terms_condition]', '<a href="'.$terms_page_link.'" target="_blank;">terms and conditions</a>', $terms_text);

			$highlight_asterik  = get_option('custom-checkout-highlight-asterik') ? get_option('custom-checkout-highlight-asterik') : 'enable';
			?>

			<div class="woocommerce-privacy-policy-text">
				<?php if( $privacy_txt_show == 'enable'){ ?>
				<p>
					<?php echo $privacy_text; ?>
				</p>
				<?php } ?>
				<?php if( $terms_txt_show == 'enable'){ ?>
				<p class="validate-required">
					<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?> id="terms" />
						<span class="woocommerce-terms-and-conditions-checkbox-text"><?php echo $terms_text; ?></span>
						<?php if( $highlight_asterik == 'enable'){ ?>
							&nbsp;<span class="required">*</span>
						<?php } ?>
					<input type="hidden" name="terms-field" value="1" />
				</p>
				<?php }else{ ?>
					<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?> id="terms" checked='checked' style="display: none;" />
				<?php } ?>
			</div>
		</div>
	<?php
	}

	do_action( 'woocommerce_checkout_after_terms_and_conditions' );
