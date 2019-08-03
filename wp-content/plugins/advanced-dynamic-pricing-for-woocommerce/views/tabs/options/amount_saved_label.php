<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr valign="top">
    <th scope="row" class="titledesc"><?php _e( 'Label "Amount Saved"', 'advanced-dynamic-pricing-for-woocommerce' ) ?></th>
    <td class="forminp forminp-checkbox">
        <fieldset>
            <legend class="screen-reader-text">
                <span><?php _e( 'Label "Amount Saved"', 'advanced-dynamic-pricing-for-woocommerce' ) ?></span></legend>
            <label for="amount_saved_label">
                <input value="<?php echo $options['amount_saved_label'] ?>"
                       name="amount_saved_label" id="amount_saved_label" type="text">

            </label>
        </fieldset>
    </td>
</tr>
