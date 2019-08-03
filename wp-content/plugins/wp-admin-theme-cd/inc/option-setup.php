<?php

/*****************************************************************/
/* LICENSE CHECK (APL) */
/*****************************************************************/

require_once( wp_admin_theme_cd_dir( 'inc/option-setup-config.php' ) );
require_once( wp_admin_theme_cd_dir( 'inc/option-setup-functions.php' ) );


/*****************************************************************/
/* INCLUDE LICENSE DE/ACTIVATION JS */
/*****************************************************************/

function wp_admin_theme_cd_activation_js( $hook ) {

	// method to get the page hook
	//wp_die($hook);

	// Load the following JS files only on plugin page
	if( $hook != 'tools_page_wp-admin-theme-cd-purchase-code' ) {
		return;
	}

	wp_enqueue_script( 
		'plugin-activation-cd',  wp_admin_theme_cd_path( 'js/jquery.setup.js' ), array( 'jquery' ), null, true 
	);

	wp_localize_script( 
		'plugin-activation-cd', 'wp_ajax_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ),
	));

	// Localize the script with new data
	$activation_js_array = array(
		// --> !!! For security check and verify wp_nonce() before sending AJAX request
		'verify_security_nonce' => wp_create_nonce( 'verify_sec_request' ),
		'label_try' => esc_html__( 'Try again', 'wp-admin-theme-cd' ) . '!',
		'label_done' => esc_html__( 'Done', 'wp-admin-theme-cd' ) . '!',
		'label_unlocked' => esc_html__( 'Unlocked', 'wp-admin-theme-cd' ) . '!',
		'label_check' => esc_html__( 'Checking', 'wp-admin-theme-cd' ) . ' ...',
		'label_unlock' => esc_html__( 'Unlocking', 'wp-admin-theme-cd' ) . ' ...',
		'label_reset' => esc_html__( 'Will be reset', 'wp-admin-theme-cd' ) . ' ...',
	);
	wp_localize_script( 'plugin-activation-cd', 'WP_JS_Plugin_Activation', $activation_js_array );

}

add_action( 'admin_enqueue_scripts', 'wp_admin_theme_cd_activation_js', 30 );


/*****************************************************************/
/* ENVATO API CONNECTION */
/*****************************************************************/

function wp_admin_theme_cd_envato_purchase_validation( $get_code ) {

	$code = trim( $get_code );

	// Make sure the code is valid before sending it to Envato
	if( ! preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $code) ) {
		return esc_html__( 'Invalid Purchase Code', 'wp-admin-theme-cd' );
	}
	
	// Envato API url
	$url = 'https://api.envato.com/v3/market/author/sale?code=' . $code;	
	$curl = curl_init( $url );
	
	// Base64 encode key
	$str = 'Z0hZS25tMkJyZnJIQWJuVzNOcGtIWGUzNGNGSEQ0TGI=';
	$token = base64_decode( $str );
	
	$header = array();
	$header[] = 'Authorization: Bearer ' . $token;
	//$header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
	$header[] = 'User-Agent: Plugin purchase code verification';
	$header[] = 'timeout: 20';
	
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );	
	$envatoData = curl_exec( $curl );
	curl_close( $curl );
	$envatoData = json_decode( $envatoData );
	
	return $envatoData;
	
}


/*****************************************************************/
/* ENVATO API - PURCHASE CODE VERIFICATION */
/*****************************************************************/

function wp_admin_theme_cd_purchase_code_verify( $code ) {
	
	// Connect to Envato API
	$envatoData = wp_admin_theme_cd_envato_purchase_validation( $code );
	
	// Check if Envato API data is accessible
	if( isset( $envatoData->item->name ) ) {
		
		// Check if the purchase code is valid for a different theme
		if( $envatoData->item->id != WP_ADMIN_THEME_CD_ENVATO_ID ) {
			
			// Purchase code is valid, but for a different theme
			return 'different';
			
		}		
			
		// Purchase Code is valid
		return 'valid';		
	}
		
	// Purchase Code is invalid
	return 'invalid';
	
}


/*****************************************************************/
/* ENVATO PURCHASE CODE SYNTAX */
/*****************************************************************/

function wp_admin_theme_cd_envato_purchase_code() {
			
	return 'envato_purchase_code_' . WP_ADMIN_THEME_CD_ENVATO_ID;
	
}


/*****************************************************************/
/* ENVATO PURCHASE CODE TRANSIENT SYNTAX */
/*****************************************************************/

function wp_admin_theme_cd_envato_purchase_code_transient() {
			
	return 'envato_purchase_code_transient_' . WP_ADMIN_THEME_CD_ENVATO_ID;
	
}


/*****************************************************************/
/* ACTIVATION ADMIN NOTICE */
/*****************************************************************/

function wp_admin_theme_cd_activation_admin_notice() { 
		
	if( wp_admin_theme_cd_activation_status() ) {
		return false;
	}

	$theme_activate_link = '<a href="' . admin_url("tools.php?page=wp-admin-theme-cd-purchase-code&tab=activation") . '">' . esc_html__( 'Enter your purchase code here', 'wp-admin-theme-cd' ) . '</a>.'; ?>
	<div class="notice notice-error theme-activate-notice">
		<p><?php printf( wp_kses_post( __( 'This plugin is not activated. Please enter your Envato purchase code to enable the plugin settings. %1$s', 'wp-admin-theme-cd' ) ), $theme_activate_link ); ?></p>
	</div>

<?php }

add_action('admin_notices', 'wp_admin_theme_cd_activation_admin_notice');


/*****************************************************************/
/* GET AND SAVE PURCHASE PLUGIN DETAILS */
/*****************************************************************/

function wp_admin_theme_cd_purchase_details( $code ) {
	
	// Connect to Envato API
	$envatoData = wp_admin_theme_cd_envato_purchase_validation( $code );
	
	// Define theme purchase transient
	$transient_id = wp_admin_theme_cd_envato_purchase_code_transient();

	// Create transient if theme purchase transient not exists
	if( false === ( $get_data = get_transient( $transient_id ) ) ) {

		// Get data from Envato API
		$data = $envatoData;
		
		if( isset( $data ) && wp_admin_theme_cd_purchase_code_verify( $code ) == 'valid' ) {
			
			// Define theme purchase user data
			$get_data = array();

			$get_data['theme_id'] = $data->item->id;
			$get_data['theme_name'] = $data->item->name;
			$get_data['amount'] = $data->amount;
			$get_data['license'] = $data->license;
			$get_data['purchase_count'] = $data->purchase_count;
			$get_data['sold_at'] = $data->sold_at;
			$get_data['support_amount'] = $data->support_amount;
			$get_data['supported_until'] = $data->supported_until;
			$get_data['buyer'] = $data->buyer;
			$get_data['purchase_code'] = $code;

			// Set theme purchase transient, only if purchase code is valid
			// Will automatically resaved each 14 days by aplVerifyLicense() function
			set_transient( $transient_id, $get_data, 15 * DAY_IN_SECONDS );

		} else {
			$get_data = 'Data Error';
		}				

	}
	
}


/*****************************************************************/
/*  GET PURCHASE DETAILS DATA */
/*****************************************************************/

function wp_admin_theme_cd_get_purchase_theme_details() {
	
	// Get purchase data from the users purchase code
	$purchase_code = get_option( wp_admin_theme_cd_envato_purchase_code() );						
	$transient_id = wp_admin_theme_cd_envato_purchase_code_transient();
	$purchase_data = get_transient( $transient_id );
	
	$wp_date_format = get_option('date_format');
	$current_date = current_time( 'mysql' );
	$supported = date( $wp_date_format, strtotime( $purchase_data['supported_until'] ) );
	$purchased = date( $wp_date_format, strtotime( $purchase_data['sold_at'] ) );
	
	$data = array();
	
	// Check Envato purchase details are available
	if( wp_admin_theme_cd_purchase_code_verify( $purchase_code ) == 'valid' ) {
	
		// Theme license details from Envato
		$data['theme_name'] = isset( $purchase_data['theme_name'] ) ? $purchase_data['theme_name'] : 'n/a';
		$data['buyer'] = isset( $purchase_data['buyer'] ) ? $purchase_data['buyer'] : 'n/a'; 
		$data['license'] = isset( $purchase_data['license'] ) ? $purchase_data['license'] : 'n/a';
		$data['purchase_count'] = isset( $purchase_data['purchase_count'] ) ? $purchase_data['purchase_count'] : 'n/a';
		$data['sold_at'] = isset( $purchase_data['sold_at'] ) ? $purchased : 'n/a';
		$data['supported_until'] = isset( $purchase_data['supported_until'] ) ? $supported : 'n/a';	
		$data['purchase_code'] = isset( $purchase_data['purchase_code'] ) ? $purchase_data['purchase_code'] : 'n/a';
	
	// Otherwise use purchase details from manually created license
	} else {
		
		// Theme license details from personal issued license
		$data['theme_name'] = WP_ADMIN_THEME_CD_PLUGIN_NAME;
		$data['license'] = esc_html__( 'You are using a personally issued license.', 'wp-admin-theme-cd' );
		$data['purchase_code'] = isset( $purchase_code ) ? $purchase_code : 'n/a';
		$data['supported_until'] = 'n/a';
		
	}

	return $data;
	
}

		
/*****************************************************************/
/* PURCHASE CODE AJAX CHECK */
/*****************************************************************/

function wp_admin_theme_cd_license_install_process() {

	// --> !!! For security check and verify wp_nonce() before saving new values from AJAX request
	check_ajax_referer( 'verify_sec_request', 'security' );

	if( $_POST ) {		

		// Get user purchase code from theme validation check
		$request = json_decode( stripslashes( $_POST['fieldData'] ) );

		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$code = $request[0]->purchase_code;
			$root_url = $request[0]->purchase_root_url;
			$client_mail = $request[0]->purchase_client_mail;
		}
		
		$license_action = esc_html__( 'Something went wrong', 'wp-admin-theme-cd' );
		
		// 1. Check if there is a connection to the Envato API
		if( wp_admin_theme_cd_license_action( 'envato', $code )['status'] == 'success' ) {	

			// 2. Check if there is a connection to the license server
			if( wp_admin_theme_cd_license_action( 'license_server' )['status'] == 'success' ) {	

				// 3. Send install license notice (if process is successfully set theme license to active)
				$license_action = wp_admin_theme_cd_license_action( 'install', $code, $root_url, $client_mail );
				
			} else {

				// 3. License Server connection failed
				// Cancel license installation and send server connection notice
				$license_action = wp_admin_theme_cd_license_action( 'license_server' );

			}

		} else {

			// 2. Evato API connection failed
			// Cancel license installation and send Evato connection notice
			$license_action = wp_admin_theme_cd_license_action( 'envato' );

		}
		
		wp_send_json( array(
			//'success' => 'SUCCESS',
			//'fieldData' => $request,
			//'verify_status' => wp_admin_theme_cd_purchase_code_verify( $code, $root_url, $client_mail ),
			'license_action' => $license_action['notice'],
			'license_status' => $license_action['status']
		) );			

	}

	die();

}

add_action( 'wp_ajax_wp_admin_theme_cd_license_install_process', 'wp_admin_theme_cd_license_install_process' );
add_action( 'wp_ajax_nopriv_wp_admin_theme_cd_license_install_process', 'wp_admin_theme_cd_license_install_process' );


/*****************************************************************/
/* UNLOCK PURCHASE CODE AJAX */
/*****************************************************************/

function wp_admin_theme_cd_license_uninstall_process() {

	// --> !!! For security check and verify wp_nonce() before saving new values from AJAX request
	check_ajax_referer( 'verify_sec_request', 'security' );

	if( $_POST ) {		

		// Get client action to uninstall license
		$request = json_decode( stripslashes( $_POST['fieldData'] ) );

		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$action = $request[0]->command;
		}
		
		$license_action = esc_html__( 'Something went wrong', 'wp-admin-theme-cd' );
		
		// 1. Check for client unlocking license request
		if( $action == 'unlock_purchase_code' ) {

			// 2. Check if there is a connection to the license server
			if( wp_admin_theme_cd_license_action( 'license_server' )['status'] == 'success' ) {	
				
				// 3. Send uninstall license notice (if process is successfully set theme license to inactive)
				$license_action = wp_admin_theme_cd_license_action( 'uninstall' );				
			
			} else {			
			
				// 3. License Server connection failed
				// Cancel license uninstallation and send server connection notice
				$license_action = wp_admin_theme_cd_license_action( 'license_server' );
				
			}
		}

		wp_send_json( array(
			//'success' => 'SUCCESS',
			'license_action' => $license_action['notice'],
			'license_status' => $license_action['status'],
		) );			

	}

	die();

}

add_action( 'wp_ajax_wp_admin_theme_cd_license_uninstall_process', 'wp_admin_theme_cd_license_uninstall_process' );
add_action( 'wp_ajax_nopriv_wp_admin_theme_cd_license_uninstall_process', 'wp_admin_theme_cd_license_uninstall_process' );


/*****************************************************************/
/* UNLOCK PURCHASE CODE AFTER PLUGIN DEACTIVATION */
/*****************************************************************/

function wp_admin_theme_cd_license_uninstall_after_plugin_deactivation() {
	
	// Check current user can manage plugins
    if( ! current_user_can( 'activate_plugins' ) ) {
		// Stop here, if that's not the case
		return;
	}
        
	// Check for WP plugin request
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
	
	// Check for WP admin referer
	check_admin_referer( "deactivate-plugin_{$plugin}" );
	
	// Uninstall license automatically after deactivating the plugin
	wp_admin_theme_cd_license_action( 'uninstall' );	
	
}

register_deactivation_hook( WP_ADMIN_THEME_CD_PLUGIN, 'wp_admin_theme_cd_license_uninstall_after_plugin_deactivation' );


/*****************************************************************/
/* UNLOCK PURCHASE CODE AFTER PLUGIN UNINSTALLATION */
/*****************************************************************/

function wp_admin_theme_cd_license_uninstall_after_plugin_uninstallation() {
	
	// Check current user can manage plugins
    if( ! current_user_can( 'activate_plugins' ) ) {
		// Stop here, if that's not the case
		return;
	}

	// Uninstall license automatically after uninstalling the plugin
	wp_admin_theme_cd_license_action( 'uninstall' );	
	
}

register_uninstall_hook( WP_ADMIN_THEME_CD_PLUGIN, 'wp_admin_theme_cd_license_uninstall_after_plugin_uninstallation' );


/*****************************************************************/
/* RESET LICENSE / PURCHASE CODE AJAX */
/*****************************************************************/

function wp_admin_theme_cd_license_reset_process() {

	// --> !!! For security check and verify wp_nonce() before saving new values from AJAX request
	check_ajax_referer( 'verify_sec_request', 'security' );

	if( $_POST ) {		

		// Get client action to reset license
		$request = json_decode( stripslashes( $_POST['fieldData'] ) );

		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$action = $request[0]->command;
		}
		
		$license_action = esc_html__( 'Something went wrong', 'wp-admin-theme-cd' );		
		
		// 1. Check for client reset license request
		if( $action == 'reset_purchase_code' ) {		
			
			// Reset the license and send reset notice
			$license_action = wp_admin_theme_cd_license_action( 'reset' );
		
		}

		wp_send_json( array(
			//'success' => 'SUCCESS',
			'license_action' => $license_action['notice'],
			'license_status' => $license_action['status'],
		) );			

	}

	die();

}

add_action( 'wp_ajax_wp_admin_theme_cd_license_reset_process', 'wp_admin_theme_cd_license_reset_process' );
add_action( 'wp_ajax_nopriv_wp_admin_theme_cd_license_reset_process', 'wp_admin_theme_cd_license_reset_process' );


/*****************************************************************/
/* PLUGIN LICENSE UNINSTALLATION (TODO) */
/*****************************************************************/

function wp_admin_theme_cd_theme_license_uninstall_todo() {
	
	// Set theme license status to inactive
	update_option( 'wp_admin_theme_cd_license', 'inactive' );

	// Send activation message to theme author
	//wp_admin_theme_cd_activation_message('unlock');

	// Delete theme purchase details transient
	delete_transient( wp_admin_theme_cd_envato_purchase_code_transient() );

	// Delete theme purchase code
	delete_option( wp_admin_theme_cd_envato_purchase_code() );	
	
}


/*****************************************************************/
/* LICENSE INSTALLATION / LOCKING (APL) */
/*****************************************************************/

function wp_admin_theme_cd_license_add( $code, $root_url, $client_mail = '' ) {
	
	// MySQL connect
	$mysqli = wp_admin_theme_cd_mysqli_connect();
	
	// Connect to Envato server and get purchase code
	$envato_verify = wp_admin_theme_cd_aplVerifyEnvatoPurchase( $code );
	
	// Check Envato purchase code is valid
	if( empty( $envato_verify ) ) {		
			
		// Install license on license server
		return wp_admin_theme_cd_aplInstallLicense( $root_url, $client_mail, $code, $mysqli );
					
	}
	
	// License installation aborted
	return false;	
	
}


/*****************************************************************/
/* PLUGIN LICENSE INSTALLATION (TODO) */
/*****************************************************************/

function wp_admin_theme_cd_license_add_todo( $code ) {
	
	// Save theme license status active
	update_option( 'wp_admin_theme_cd_license', 'active' );

	// Save theme purchase code
	update_option( wp_admin_theme_cd_envato_purchase_code(), $code );

	// Save theme purchase details
	wp_admin_theme_cd_purchase_details( $code );

	// Send activation message to theme author
	//wp_admin_theme_cd_activation_message();	
	
}


/*****************************************************************/
/* VERIFY PLUGIN LICENSE PERIODICALLY */
/*****************************************************************/

function wp_admin_theme_cd_license_verify() {
	
	$activation_status = get_option( 'wp_admin_theme_cd_license' );
	
	// Check for plugin license has not been activated yet or is "inactive"
	if( empty( $activation_status ) || $activation_status === 'inactive' ) {
		// So this function can only be fired if the plugin is already activated
		// --> This avoids MySQL connections on every backend request
		return false;
	}
	
	// Get transient name
	$transient_name = 'wp_admin_theme_cd_license_verify';
	
	// Check if license has been verificated in the last 14 days
	if( get_transient( $transient_name ) != 'verificated' ) {
		// Verificate license
		wp_admin_theme_cd_license_action( 'verify' );
	}
	
	// Transient does not exist, so create a new transient
	// --> Asking for transient avoids MySQL connections on every backend request, if the plugin license is already "active"
	if( false === ( $data = get_transient( $transient_name ) ) ) {

		// Transient key
		$data = 'verificated';

		// Save the new transient for 14 days
		// --> After creating the new transient, verify the plugin license again
		set_transient( $transient_name, $data, 14 * DAY_IN_SECONDS );
	
	}

}

add_action('admin_init', 'wp_admin_theme_cd_license_verify');


/*****************************************************************/
/* UPDATE PLUGIN LICENSE IF IP ADDRESS HAS BEEN CHANGED */
/*****************************************************************/

function wp_admin_theme_cd_license_update_ip() {
	
	$activation_status = get_option( 'wp_admin_theme_cd_license' );
	
	// Check for plugin license has not been activated yet or is "inactive"
	if( empty( $activation_status ) || $activation_status === 'inactive' ) {
		// So this function can only be fired if the plugin is already activated
		// --> This avoids MySQL connections on every backend request
		return false;
	}
	
	// Fallback ip address
	$ip_fallback = '0.0.0.0';
	
	// Get server ip address
	$ip_address = isset( $_SERVER['SERVER_ADDR'] ) ? $_SERVER['SERVER_ADDR'] : $ip_fallback;
	
	// Get transient name
	$option_name = 'wp_admin_theme_cd_license_update';
	
	// Check plugin license update option exist
	if( empty( get_option( $option_name ) ) ) {
		update_option( $option_name, $ip_address, 'yes' );
	}
	
	// Check if the ip address has been changed
	// --> Then update plugin license ip address
	// --> This avoids MySQL connections on every backend request
	if( get_option( $option_name ) != $ip_address ) {
		
		// Update license
		wp_admin_theme_cd_license_action( 'update' );
		
		// Save the new server ip address in plugin license update option
		update_option( $option_name, $ip_address, 'yes' );
	}	
	
	return false;

}

add_action('admin_init', 'wp_admin_theme_cd_license_update_ip');


/*****************************************************************/
/* LICENSE SERVER ACTIONS + NOTICES (APL) */
/*****************************************************************/

function wp_admin_theme_cd_license_action( $action, $code = '', $root_url = '', $client_mail = '' ) {
	
	// MySQL connect
	$mysqli = wp_admin_theme_cd_mysqli_connect();
	
	$request = array();
	
	// Envato connection
	if( $action === 'envato' ) {
		
		// Action to check connection
		$envato_verify = wp_admin_theme_cd_aplVerifyEnvatoPurchase( $code );

		// Connection successful
		$request['status'] = 'success';
		$request['notice'] = esc_html__( 'Connection to Envato server successfull', 'wp-admin-theme-cd' );
		
		// Connection failed
		if( ! empty( $envato_verify ) ) {
			$request['status'] = 'error';
			$request['notice'] = esc_html__( 'Connection to Envato server failed', 'wp-admin-theme-cd' );
		}
		
	}
	
	// License server connection
	if( $action === 'license_server' ) {
		
		// Action to check license server connection
		$license_server = wp_admin_theme_cd_aplCheckConnection();

		// Connection successful
		$request['status'] = 'success';
		$request['notice'] = esc_html__( 'Connection to license server successfull', 'wp-admin-theme-cd' );
		
		// Connection failed
		if( ! empty( $license_server ) ) {
			$request['status'] = 'error';
			$request['notice'] = esc_html__( 'Connection to license server failed', 'wp-admin-theme-cd' ) . ': ' . $license_server['notification_text'];
		}
		
	}
	
	// License verification
	if( $action === 'verify' ) {
		
		// Action to verify license
		$license_verify = wp_admin_theme_cd_aplVerifyLicense( $mysqli );
		
		// Verification failed
		$request['status'] = 'error';
		$request['notice'] = $license_verify['notification_text'];

		// Verification successful
		if( $license_verify['notification_case'] == "notification_license_ok" ) {
			$request['status'] = 'success';
			$request['notice'] = esc_html__( 'License successfully verified', 'hannah-cd' );

			// Call Envato user data from purchase code each 14 days and resave it to trasient
			$purchase_code = get_option( wp_admin_theme_cd_envato_purchase_code() );
			$verify_code = '';
			if( $purchase_code ) {
				$verify_code = $purchase_code;
			}			
			wp_admin_theme_cd_purchase_details( $verify_code );
		}
		
	}
	
	// License update
	if( $action === 'update' ) {
		
		// Action to update license ip address
		$license_update = wp_admin_theme_cd_aplUpdateLicense( $mysqli );

		// Update failed
		$request['status'] = 'error';
		$request['notice'] = $license_update['notification_text'];

		// Update successful
		if( $license_update['notification_case'] == "notification_license_ok" ) {
			$request['status'] = 'success';
			$request['notice'] = esc_html__( 'License has been successfully updated', 'hannah-cd' );
		}
		
	}
	
	// License installation
	if( $action === 'install' ) {
		
		// Action to install license (if process is successfully)
		$license_add = wp_admin_theme_cd_license_add( $code, $root_url, $client_mail );

		// Installation failed
		$request['status'] = 'error';
		$request['notice'] = esc_html__( 'License installing failed', 'wp-admin-theme-cd' ) . ': ' . $license_add['notification_text'];
		
		// Installation successful
		if( $license_add['notification_case'] == "notification_license_ok" ) {
			$request['status'] = 'success';
			$request['notice'] = esc_html__( 'License has been successfully installed', 'wp-admin-theme-cd' );			
			
			// ToDo after installing license
			wp_admin_theme_cd_license_add_todo( $code );			
		}
		
	}
	
	// License uninstallation
	if( $action === 'uninstall' ) {
		
		// Action to uninstall license (if process is successfully)
		$license_unlock = wp_admin_theme_cd_aplUninstallLicense( $mysqli );

		// Uninstallation failed
		$request['status'] = 'error';
		$request['notice'] = esc_html__( 'License unlocking failed', 'wp-admin-theme-cd' ) . ': ' . $license_unlock['notification_text'];
		
		// Uninstallation successful
		if( $license_unlock['notification_case'] == "notification_license_ok" ) {
			$request['status'] = 'success';
			$request['notice'] = esc_html__( 'License has been successfully unlocked', 'wp-admin-theme-cd' );
			
			// ToDo after uninstalling license
			wp_admin_theme_cd_theme_license_uninstall_todo();
		}
		
	}
	
	// License reset
	if( $action === 'reset' ) {
		
		// Check for license table
		global $wpdb;
		$license_table = WP_ADMIN_THEME_CD_APL_DATABASE_TABLE;
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $license_table ) );

		// Check if license table exist
		if( $wpdb->get_var( $query ) == $license_table ) {

			$request['notice'] = esc_html__( 'The license table was found and the license was reset.', 'wp-admin-theme-cd' );
			$request['status'] = 'success';

			// Action to delete license table manually
			mysqli_query( $mysqli, "DELETE FROM " . $license_table );
			mysqli_query( $mysqli, "DROP TABLE " . $license_table );

		} else {

			$request['notice'] = esc_html__( 'License table not found. Please contact the author of the plugin and ask him to unlock the license manually.', 'wp-admin-theme-cd' );
			$request['status'] = 'error';
		}

		// ToDo after reset the license
		wp_admin_theme_cd_theme_license_uninstall_todo();
		
	}
	
	return $request;

}


/*****************************************************************/
/* ACTIVATION STATUS */
/*****************************************************************/

function wp_admin_theme_cd_activation_status() {
	
	if( wp_admin_theme_cd_plugin() ) {
		// Unlock
		return true;
	}
			
	$activation_status = get_option( 'wp_admin_theme_cd_license' );
	if( is_multisite() ) {
		$blog_id = 1; // <-- Option from main site
		$activation_status = get_blog_option( $blog_id, 'wp_admin_theme_cd_license', array() );
	}
	
	if( ! $activation_status || $activation_status == 'inactive' ) {
		// Lock
		return false;
	}
	
	// Unlock
	return true;
	
}


/*****************************************************************/
/* ACTIVATION MESSAGE */
/*****************************************************************/

function wp_admin_theme_cd_activation_message( $status = '' ) {
	
	// Get the users domain name
	$domain = wp_admin_theme_cd_root_url();
	
	// Get purchase data from the users purchase code
	$purchase_data = wp_admin_theme_cd_purchase_details();
	
	// Get license status
	$activation_status = get_option( 'wp_admin_theme_cd_license' );
	$license_status = '<strong style="color:green">' . esc_html__( 'Activation succesfull', 'wp-admin-theme-cd' ) . '</strong>';
	if( ! $activation_status || $activation_status == 'inactive' ) {
		$license_status = '<strong style="color:red">' . esc_html__( 'Activation denied', 'wp-admin-theme-cd' ) . '</strong>';
	}
	
	if( $status == 'unlock' ) {
		$license_status = '<strong style="color:orange">' . esc_html__( 'License has been unlocked', 'wp-admin-theme-cd' ) . '</strong>';
	}
	
	// Message subject
	$subject = esc_html__( 'Plugin Activation for', 'wp-admin-theme-cd' ) . ' ' . WP_ADMIN_THEME_CD_PLUGIN_NAME . ' - ' . $purchase_data['purchase_code'];
		
	// Message body
	$message = '<strong>' . $subject . '</strong><br><br>';
	$message .= esc_html__( 'Domain', 'wp-admin-theme-cd' ) . ': <a href="' . $domain . '">' . $domain . '</a><br>';
	$message .= esc_html__( 'Status', 'wp-admin-theme-cd' ) . ': ' . $license_status . '<br><br>';
	$message .= '<small>';
	$message .= esc_html__( 'Plugin', 'wp-admin-theme-cd' ) . ': ' . $purchase_data['theme_name'] . '<br>';
	$message .= esc_html__( 'Buyer', 'wp-admin-theme-cd' ) . ': ' . $purchase_data['buyer'] . '<br>';
	$message .= esc_html__( 'License', 'wp-admin-theme-cd' ) . ': ' . $purchase_data['license'] . '<br>';
	$message .= esc_html__( 'Purchase Count', 'wp-admin-theme-cd' ) . ': ' . $purchase_data['purchase_count'] . '<br>';
	$message .= esc_html__( 'Sold at', 'wp-admin-theme-cd' ) . ': ' . $purchase_data['sold_at'] . '<br>';
	$message .= esc_html__( 'Support until', 'wp-admin-theme-cd' ) . ': ' . $purchase_data['supported_until'];
	$message .= '</small>';
	
	// Message headers
	$headers = array('Content-Type: text/html; charset=UTF-8');

	// Create message
	wp_mail( WP_ADMIN_THEME_CD_AUTHOR_MAIL, $subject, $message, $headers );

}

/*****************************************************************/
/* RENAME OLD PLUGIN LICENSE TABLE */
/*****************************************************************/

function wp_admin_theme_cd_rename_old_plugin_license_db_table() {
	
	$old_license_table = 'wp_admin_theme_cd_license_data';
	
	// Check for the old license table
	global $wpdb;
	$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $old_license_table ) );

	// Check if the old license table exist
	if( $wpdb->get_var( $query ) == $old_license_table ) {
				
		// MySQL connect
		$mysqli = wp_admin_theme_cd_mysqli_connect();
	
		// Define new license table name
		$new_license_table = $wpdb->prefix . $old_license_table;
		
		// Search for old table name and rename to the new table name
		mysqli_query( $mysqli, "ALTER TABLE " . $old_license_table . " RENAME TO " . $new_license_table );
		
	}
	
	// Old license table not found
	return false;
	
}

add_action('admin_init', 'wp_admin_theme_cd_rename_old_plugin_license_db_table');

