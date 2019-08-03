<?php

if (!defined('ABSPATH')) {
    exit;
}

function dig_wc_search_usr($found_customers){

    if ( ! current_user_can( 'edit_shop_orders' ) ) {
        wp_die( -1 );
    }
    $term    = wc_clean( stripslashes( $_GET['term'] ) );

    if(!is_numeric($term)) return $found_customers;




    $ids = getUserIDSfromPhone($term);

    if(empty($ids) || !is_array($ids)){
        return $found_customers;
    }

    if(count($ids)==0)return $found_customers;

    if ( ! empty( $_GET['exclude'] ) ) {
        $ids = array_diff( $ids, (array) $_GET['exclude'] );
    }
    foreach ( $ids as $id ) {
        $customer = new WC_Customer( $id );
        /* translators: 1: user display name 2: user ID 3: user email */
        $found_customers[ $id ] = sprintf(
            esc_html__( '%1$s (#%2$s &ndash; %3$s)', 'woocommerce' ),
            $customer->get_first_name() . ' ' . $customer->get_last_name(),
            $customer->get_id(),
            $customer->get_email()
        );
    }

    return $found_customers;


}

add_action('woocommerce_json_search_found_customers','dig_wc_search_usr');


add_filter('wp_get_nav_menu_items','dig_upg_menu_accd', 1, 3);
function dig_upg_menu_accd( $items, $menu, $args ){
    if (function_exists('get_current_screen')) {
        $screeen = get_current_screen();

        if ($screeen != null && $screeen->base == 'nav-menus') {
            return $items;
        }
    }

    if(is_user_logged_in()) {
        $hide_items = array("dm-login-page","dm-login-modal","[digits-registration]", "[digits-forgot-password]", "[digits-page-registration]", "[digits-page-forgot-password]"
        ,"[dm-registration-page]","[dm-registration-modal]","[dm-forgot-password-page]","[dm-forgot-password-modal]","dm-signup-modal","dm-signup-page");
    }else {
        $hide_items = array("[digits-logout]","[dm-logout]");
    }
    foreach($items as $i => $item){
        $menu_item = $item->post_title;
        if(in_array($menu_item,$hide_items)){
            unset($items[$i]);
        }
    }
    return $items;
}


function dig_verify_otp_box(){


    $otp_placeholder = dig_get_otp(true);
    $otp_size = strlen($otp_placeholder);

    ?>
    <div class="dig_verify_mobile_otp_container" style="display: none;">
        <div class="dig_verify_mobile_otp">
            <div class="dig_verify_code_head dig_sml_box_msg_head"><?php _e('Verification Code','digits');?></div>
            <div class="dig_verify_code_msg dig_sml_box_msg"><?php echo sprintf( __( 'Please type the verification code sent to %s.', 'digits' ), '<span></span>');?></div>
            <div class="dig_verify_code_contents">

                <div class="minput">
                    <?php

                    ?>
                    <input type="text" class="empty dig_verify_otp_input" required="" name="dig_otp" placeholder="Test" maxlength="<?php echo $otp_size; ?>">
                    <label><?php echo $otp_placeholder; ?></label>
                    <span class="bgdark"></span>
                </div>
                <div class="dig_verify_otp lighte bgdark button"><?php _e('SUBMIT','digits');?></div>

            </div>
        </div>
    </div>
    <?php
}




function dig_fp_changepass_redirect($user,$password){
    $user_id = $user->ID;
    dig_fp_remove_token($user_id);
    wp_set_password($password, $user_id);
    $current_url = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $current_url = dig_removeStringParameter($current_url, "login");
    $current_url = dig_removeStringParameter($current_url, "page");
    $current_url = dig_removeStringParameter($current_url, "user");
    $current_url = dig_removeStringParameter($current_url, "token");

    wp_set_current_user($user->ID, $user->user_login);
    wp_set_auth_cookie($user->ID);
    $t = get_option("digits_forgotred");
    if (!empty($t)) $current_url = $t;
    wp_safe_redirect($current_url);
    die();

}
function dig_fp_remove_token($user_id){
    delete_user_meta($user_id,'pr_token');
    delete_user_meta($user_id,'pr_token_time');

}
function dig_fp_create_token($user_id,$noTimeLimit = false,$returnToken = false){
    $user = get_user_by('ID',$user_id);
    $token = bin2hex(openssl_random_pseudo_bytes(48));
    $time = time();
    if($noTimeLimit) $time = -1;
    update_user_meta($user_id,'pr_token',$token);
    update_user_meta($user_id,'pr_token_time',$time);


    if($returnToken) return $token;
    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    }
    else {
        $protocol = 'http://';
    }

    update_user_meta($user_id,'pr_token_one_time',0);

    return $protocol.$_SERVER['SERVER_NAME'].'/?login=true&token='.$token.'&user='.$user->user_login;

}

function dig_fp_verify_one_time_token($token,$user_id){
    return dig_fp_verify_token($token,$user_id,600);
}
function dig_fp_verify_token($token,$user_id,$expiry_time = 86400){
    $db_token = get_user_meta($user_id,"pr_token",true);
    $db_token_time = get_user_meta($user_id,"pr_token_time",true);

    if($db_token==$token) {
        if ($db_token_time != -1) {
            $current_time = time();
            $time_difference = $current_time - $db_token_time;
            if ($time_difference > $expiry_time) {
                return false;
            }
        }
    }else{
        return false;
    }
    return true;
}
function dig_fp_refresh_token($user_id){
    dig_fp_remove_token($user_id);
    $db_one_time_token = get_user_meta($user_id,"pr_token_one_time",true);
    if($db_one_time_token==1){
        return false;
    }
    update_user_meta($user_id,'pr_token_one_time',1);
    return dig_fp_create_token($user_id,false,true);

}
function dig_get_template($type){
    if($type==3){
        return 'html/password-reset.html';
    }
}

function dig_curl($url){

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $return = curl_exec($ch);

    curl_close ($ch);
    return $return;
}
function digits_show_reg_check_disabled_show(){
    digits_show_reg_check_disabled(true);
}
function digits_show_reg_check_disabled($show_notice = true)
{
    $request_link = admin_url('options-general.php?page=digits_settings&tab=activate');
	$digpc = dig_get_option('dig_purchasecode');


	if(empty($digpc)) {
		$dig_hid_update_notice = get_site_option( 'dig_hid_update_notice', 0 );

		$current_time = time();
		if ( isset( $_POST['dig_hid_activate_notice'] ) ) {
			update_site_option( 'dig_nt_time', $current_time );
			update_site_option( 'dig_hid_activate_notice', min( $dig_hid_update_notice + 1, 3 ) );

		}


		if ( $dig_hid_update_notice < 1 ) {
			$time = get_site_option( 'dig_nt_time', -1 );


			if ( ($current_time - $time) > 2592000 ) {
				?>
                <div class="notice notice-error dig-new-activation-notice is-dismissible">
                    <p>
						<?php _e( '<b>Digits:</b> Please register digits with purchase code to enable automatic updates. Click <a href="' . $request_link . '">here</a>.<br />' ); ?>
                    </p>

                    <form method="post">
                        <input type="hidden" name="dig_hid_activate_notice"/>
                        <button type="submit" class="notice-dismiss" style="z-index: 99">
                            <span class="screen-reader-text">Dismiss this notice.</span>
                        </button>
                    </form>

                </div>
				<?php


			}
		}
	}
    /*

        $passaccep = get_option("digpassaccep", 1);

        if (class_exists('WooCommerce')) {
            if ($passaccep == 0 && get_option('woocommerce_registration_generate_password') === 'no') {

                $class = 'notice notice-warning';

                $message = __('<b>Digits:</b> Please enable <b>Automatically generate customer password</b> option in your WooCommerce settings (WooCommerce --> Settings --> Accounts) for disabling password.', 'digits');
                printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
            }
        }*/
}
function dig_lgr_custom(){
    dig_manual_custom_redirect();
}

//dig_allow_external_redirect


function dig_manual_custom_redirect(){
    if(isset($_POST['digits_redirect_page'])){
            $current_url = $_POST['digits_redirect_page'];
            if(empty($current_url))return;

    	    $current_url = preg_replace('/'. $_SERVER['SERVER_NAME'].'/','',$current_url,1);

            wp_safe_redirect($current_url);
            die();
    }
}


function dig_register_meta_boxes() {
    add_meta_box( 'digits_endpoints_nav_link', __( 'Digits Menu Items', 'digits' ),  'dig_nav_menu_links' , 'nav-menus', 'side', 'low' );
}
add_action( 'admin_head-nav-menus.php', 'dig_register_meta_boxes' );


function dig_nav_menu_links() {
    // Get items from account menu.
    $endpoints = array();

    // Remove dashboard item.
    if ( isset( $endpoints['dashboard'] ) ) {
        unset( $endpoints['dashboard'] );
    }


    $a = array(
        'dmpage' => '[dm-page]',
        'dmmodal' => '[dm-modal]',
        'loginpage' => '[dm-login-page]',
    'loginmodal'=> '[dm-login-modal]',
    'registerpage'=> '[dm-signup-page]',
    'registermodal'=> '[dm-signup-modal]',
    'forgotpasspage'=> '[dm-forgot-password-page]',
    'forgotpassmodal'=> '[dm-forgot-password-modal]',
    'logout'=> '[dm-logout]');


    $endpoints['dmpage'] = __( 'Login/Signup Page', 'digits' );
    $endpoints['dmmodal'] = __( 'Login/Signup Modal', 'digits' );
    $endpoints['loginpage'] = __( 'Login Page', 'digits' );
    $endpoints['loginmodal'] = __( 'Login Modal', 'digits' );
    $endpoints['registerpage'] = __( 'Signup Page', 'digits' );
    $endpoints['registermodal'] = __( 'Signup Modal', 'digits' );
    $endpoints['forgotpasspage'] = __( 'Forgot Password Page', 'digits' );
    $endpoints['forgotpassmodal'] = __( 'Forgot Password Modal', 'digits' );
    $endpoints['logout'] = __( 'Logout', 'digits' );

    ?>
    <div id="posttype-digits-endpoints" class="posttypediv">
        <div id="tabs-panel-digits-endpoints" class="tabs-panel tabs-panel-active">
            <ul id="digits-endpoints-checklist" class="categorychecklist form-no-clear">
                <?php
                $i = -1;
                foreach ( $endpoints as $key => $value ) :
                    ?>
                    <li>
                        <label class="menu-item-title">
                            <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-object-id]" value="<?php echo esc_attr( $i ); ?>" /> <?php echo esc_html( $value ); ?>
                        </label>
                        <input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-type]" value="custom" />
                        <input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-title]" value="<?php echo esc_html( $a[$key] ); ?>" />
                        <input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-url]" value="#">
                        <input type="hidden" class="menu-item-classes" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-classes]" />
                    </li>
                    <?php
                    $i--;
                endforeach;
                ?>
            </ul>
        </div>
        <p class="button-controls">
				<span class="list-controls">
					<a href="<?php echo esc_url( admin_url( 'nav-menus.php?page-tab=all&selectall=1#posttype-digits-endpoints' ) ); ?>" class="select-all"><?php esc_html_e( 'Select all', 'Digits' ); ?></a>
				</span>
            <span class="add-to-menu">
					<button type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to menu', 'digits' ); ?>" name="add-post-type-menu-item" id="submit-posttype-digits-endpoints"><?php esc_html_e( 'Add to menu', 'digits' ); ?></button>
					<span class="spinner"></span>
				</span>
        </p>
    </div>
    <?php
}



function dig_custom_wpwc_fields_hide($hide,$type,$meta_key){
    $dig_fields = array('last_name', 'user_role', 'display_name', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_country', 'billing_postcode');
    if(in_array($meta_key,$dig_fields)){
        return true;
    }
    return $hide;
}
add_filter('dig_show_field_to_loggedin_user','dig_custom_wpwc_fields_hide',10,3);


add_action('admin_notices', 'digits_show_reg_check_disabled_show');

function dig_timeConvert($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a');
}


/*
 * 2 - Registration
 * 4 - only verify
 * */
function dig_validateMobileNumber($countrycode,$mobile,$otp,$csrf,$type,$code){

    if (empty($mobile) || !is_numeric($mobile) || !is_numeric($countrycode) || empty($countrycode)) {
        return __('Please enter a valid Mobile Number','digits');
    }
    $user = getUserFromPhone($countrycode.$mobile);
    if ($user != null && $type==2) {
        return __('Mobile Number is already in use','digits');
    }
    $mobVerificationFailed = __('Please enter a valid OTP','digits');


    if (dig_gatewayToUse($countrycode) == 1) {
        if (empty($code)) {
            return $mobVerificationFailed;

        }
        $json = getUserPhoneFromAccountkit($code);
        $phoneJson = json_decode($json, true);
        if ($json == null) {
            return $mobVerificationFailed;


        }

        $mob = $countrycode . $mobile;

        if($phoneJson['phone']!=$mob){
            return $mobVerificationFailed;

        }


        $mob = $phoneJson['phone'];
        $phone = $phoneJson['nationalNumber'];
        $countrycode = $phoneJson['countrycode'];



    } else {
        if (empty($otp)) {
            return __('Please enter a valid OTP','digits');
        }
        if (verifyOTP($countrycode, $mobile, $otp, true)) {
            $mob = $countrycode . $mobile;
        } else {
            return $mobVerificationFailed;

        }
    }
}









function dig_is_gatewayEnabled($gatewayCode){


    $digit_tapp = get_option( 'digit_tapp', 1 );

    $isEnabled = false;

    if($gatewayCode==$digit_tapp){
	    $isEnabled = true;
    }

	$isEnabled = apply_filters('digit_gateway_check',$isEnabled,$gatewayCode);

	return $isEnabled;
}

function dig_gatewayToUse($countrycode){
    if(empty($countrycode) || !is_numeric($countrycode))return -1;

	$gatewayToUse = get_option( 'digit_tapp', 1 );


	$gatewayToUse = apply_filters('digit_gateway_to_use',$gatewayToUse,$countrycode);

	return $gatewayToUse;
}






function dig_rememberMe(){

	$rememberme = get_option('dig_login_rememberme',1);

	$class = '';
	$checked = '';
	if($rememberme==0){
		return;
	}else{
		if($rememberme==2 || (isset($_REQUEST['digits_login_remember_me']) && $_REQUEST['digits_login_remember_me']==1)   ) {
			$class   = 'selected';
			$checked = 'checked';
		}
	}
	$rand = rand();
	?>
    <div class="dig_login_rembe" <?php if($rememberme==2) echo 'style="display:none;"';?>>
        <label class="<?php echo $class;?>" for="digits_login_remember_me<?php echo $rand;?>">
            <input data-all="digits_login_remember_me" name="digits_login_remember_me" class="not-empty digits_login_remember_me" id="digits_login_remember_me<?php echo $rand;?>" type="checkbox" value="1" <?php echo $checked;?>>
			<?php _e('Remember Me','digits');?>
        </label>
    </div>
	<?php
}



function digits_filter_username($username){
	$username = str_replace(' ', '', $username);

	return $username;
}
function digits_update_username($username){

	$username = dig_generate_username();
    return $username;
}
add_filter('digits_username','digits_update_username');
function dig_generate_username(){

    $tname = dig_generate_random_number();
	$check = username_exists($tname);
	if (!empty($check)) {
		while (!empty($check)) {
			$alt_ulogin = dig_generate_random_number();
			$check = username_exists($alt_ulogin);

		}
		$ulogin = $alt_ulogin;
	} else {
		$ulogin = $tname;
	}

	return $ulogin;
}

function dig_generate_random_number(){
	$length = 12;
	$returnString = mt_rand(1, 9);
	while (strlen($returnString) < $length) {
		$returnString .= mt_rand(0, 9);
	}
	return $returnString;

}
function checkIfUsernameIsMobile_validate($countrycode,$mobile){
    $user_id = username_exists($mobile);
    if(!$user_id)return 1;
	if (substr($mobile, 0, 1) == '0'){
	    return 1;
    }

    $mobile = sanitize_mobile_field_dig($mobile);
    $request = $countrycode.$mobile;


    $digits_phone_no = get_user_meta($user_id,'digits_phone_no',true);

    if(empty($digits_phone_no)) return 1;


	$user = get_userdata($user_id);

    $user_phone = get_user_meta($user_id,'digits_phone',true);
    if($user_phone!=$request && $digits_phone_no==$user->user_login){

	        return -1;

    }else{
        return 1;
    }


}
function dig_get_option($key,$default = null){
    if(!empty(get_site_option($key))) return get_site_option($key);
    else if(!empty(get_option($key))) return get_option($key);
    else return $default;
}
