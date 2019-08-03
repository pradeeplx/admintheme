<?php
/**
 * Show messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/notice.php.
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
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! $messages ) {
	return;
}
$layout_id 	= get_option('_checkout_page_layout_',true);
$value	 	= get_post_meta( $layout_id, '_tes_layout_id',true);
?>

<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-info <?php echo 'notice-custom-checkout-pro-layout-'.$value; ?>"><?php echo wp_kses_post( $message ); ?></div>
<?php endforeach; ?>
