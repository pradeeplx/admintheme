<?php
/**
 * Plugin Name: Advanced Dynamic Pricing for WooCommerce
 * Plugin URI:
 * Description: Manage discounts/deals for WooCommerce
 * Version: 2.1.1
 * Author: AlgolPlus
 * Author URI: https://algolplus.com/
 * WC requires at least: 3.3
 * WC tested up to: 3.6
 *
 * Text Domain: advanced-dynamic-pricing-for-woocommerce
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Stop if another version is active!
if( defined( 'WC_ADP_PLUGIN_FILE' ) ) {
	add_action('admin_notices', function() {
		?>
		<div class="notice notice-warning is-dismissible">
		<p><?php _e( 'Please, <a href="plugins.php">deactivate</a> Free version of Advanced Dynamic Pricing For WooCommerce!', 'advanced-dynamic-pricing-for-woocommerce' ); ?></p>
		</div>
		<?php
	});
	return;
}

//main constants
define( 'WC_ADP_PLUGIN_FILE', basename( __FILE__ ) );
define( 'WC_ADP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_ADP_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'WC_ADP_MIN_PHP_VERSION', '5.4.0' );
define( 'WC_ADP_MIN_WC_VERSION', '3.3' );
define( 'WC_ADP_VERSION', '2.1.1' );

include_once WC_ADP_PLUGIN_PATH . 'classes/common/class-wdp-database.php';
include_once WC_ADP_PLUGIN_PATH . 'classes/class-wdp-loader.php';
register_activation_hook( __FILE__, array( 'WDP_Loader', 'install' ) );
register_deactivation_hook( __FILE__, array('WDP_Loader', 'deactivate') );
register_uninstall_hook( __FILE__, array('WDP_Loader', 'uninstall') );

//start
new WDP_Loader();