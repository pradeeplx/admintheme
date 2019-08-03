<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Clockwork' ) ) {
	require_once plugin_dir_path( __DIR__ ) . 'gateways/clockwork/wordpress/class-clockwork-plugin.php';
}


if(!class_exists('Melipayamak\MelipayamakApi')) {
	require_once plugin_dir_path( __DIR__ ) . 'gateways/melipayamak/autoload.php';
}
use Melipayamak\MelipayamakApi;

require_once plugin_dir_path( __DIR__ ) . 'Twilio/autoload.php';

use Twilio\Rest\Client;

if(!class_exists('ComposerAutoloaderInit90fceaf4b778149483bc47bcb466a797')) {
	require_once plugin_dir_path( __DIR__ ) . 'gateways/alibaba/autoload.php';
}
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

if(!class_exists('AdnSms\AdnSmsNotification')) {
	require_once plugin_dir_path( __DIR__ ) . 'gateways/AdnSms/AdnSmsNotification.php';
}
use AdnSms\AdnSmsNotification;


add_action( "wp_ajax_nopriv_digits_resendotp", "digits_resendotp" );

add_action( "wp_ajax_digits_resendotp", "digits_resendotp" );

function digits_resendotp() {

	$countrycode = sanitize_text_field( $_REQUEST['countrycode'] );
	$mobileno    = sanitize_mobile_field_dig( $_REQUEST['mobileNo'] );
	$csrf        = $_REQUEST['csrf'];
	$login       = $_REQUEST['login'];

	if ( dig_gatewayToUse( $countrycode ) == 1 ) {
		die();
	}
	if ( ! checkwhitelistcode( $countrycode ) ) {
		echo "-99";
		die();
	}

	if ( ! wp_verify_nonce( $csrf, 'dig_form' ) ) {
		echo '0';
		die();
	}

	$users_can_register = get_option( 'dig_enable_registration', 1 );
	$digforgotpass      = get_option( 'digforgotpass', 1 );
	if ( $users_can_register == 0 && $login == 2 ) {
		echo "0";
		die();
	}
	if ( $digforgotpass == 1 && $login == 3 ) {
		echo "0";
		die();
	}

	if ( OTPexists( $countrycode, $mobileno, true ) ) {
		digits_check_mob();
	}
	echo "0";
	die();

}


add_action( "wp_ajax_nopriv_digits_verifyotp_login", "digits_verifyotp_login",10 );

add_action( "wp_ajax_digits_verifyotp_login", "digits_verifyotp_login" ,10);

function dig_checkblacklist($code){
	$blacklistcountrycodes = get_option("dig_blacklistcountrycodes");
	if(!empty($blacklistcountrycodes)){
		if(is_array($blacklistcountrycodes) && sizeof($blacklistcountrycodes)>0){
			$countryarray = getCountryList();
			$code         = str_replace( "+", "", $code );

			foreach ( $countryarray as $key => $value ) {
				if ( $value == $code ) {
					if ( in_array( $key, $blacklistcountrycodes ) ) {
						return true;
					}
				}

			}
		}
	}
	return false;
}
function checkwhitelistcode( $code ) {


	$whiteListCountryCodes = get_option( "whitelistcountrycodes" );

	if ( empty( $whiteListCountryCodes ) ) {
		return true;
	}

	$check_blacklist = dig_checkblacklist($code);
	if($check_blacklist) return false;

	$size = sizeof( $whiteListCountryCodes );
	if ( $size > 0 && is_array( $whiteListCountryCodes ) ) {

		$countryarray = getCountryList();
		$code         = str_replace( "+", "", $code );

		foreach ( $countryarray as $key => $value ) {
			if ( $value == $code ) {
				if ( in_array( $key, $whiteListCountryCodes ) ) {
					return true;
				}
			}

		}

		return false;
	}

	return true;

}

function digits_verifyotp_login() {

	$countrycode = sanitize_text_field( $_REQUEST['countrycode'] );

	if ( dig_gatewayToUse( $countrycode ) == 1 ) {
		die();
	}


	if ( ! checkwhitelistcode( $countrycode ) ) {
		echo "-99";
		die();
	}


	$mobileno = sanitize_mobile_field_dig( $_REQUEST['mobileNo'] );
	$csrf     = $_REQUEST['csrf'];
	$otp      = sanitize_text_field( $_REQUEST['otp'] );
	$del      = false;


	$users_can_register = get_option( 'dig_enable_registration', 1 );
	$digforgotpass      = get_option( 'digforgotpass', 1 );
	if ( $users_can_register == 0 && $_REQUEST['dtype'] == 2 ) {
		echo "1013";
		die();
	}
	if ( $digforgotpass == 0 && $_REQUEST['dtype'] == 3 ) {
		echo "0";
		die();
	}

	if ( ! wp_verify_nonce( $csrf, 'dig_form' ) ) {
		echo '1011';
		die();
	}


	if ( $_REQUEST['dtype'] == 1 ) {
		$del = true;
	}

	$rememberMe = false;
	if ( isset( $_REQUEST['rememberMe'] ) && $_REQUEST['rememberMe'] == 'true' ) {
		$rememberMe = true;
	}


	if ( verifyOTP( $countrycode, $mobileno, $otp, $del ) ) {

		$user1 = getUserFromPhone( $countrycode . $mobileno );
		if ( $user1 ) {

			if ( $_REQUEST['dtype'] == 1 ) {
				wp_set_current_user( $user1->ID, $user1->user_login );
				wp_set_auth_cookie( $user1->ID, $rememberMe );
				do_action( 'wp_login', $user1->user_login, $user1 );
				echo '11';
			} else {
				echo '1';
			}

			die();
		} else {
			echo '-1';
			die();
		}


	} else {
		echo '0';
		die();
	}

}

add_action( "wp_ajax_nopriv_digits_check_mob", "digits_check_mob" ,10);
add_action( "wp_ajax_digits_check_mob", "digits_check_mob" ,10);


function sanitize_mobile_field_dig( $mobile ) {
	$pl = '';
	if ( substr( $mobile, 0, 1 ) == '+' ) {
		$pl = '+';
	}
	$mobile = $pl . preg_replace( '/[\s+()-]+/', '', $mobile );

	return ltrim( sanitize_text_field( $mobile ), '0' );
}

function digits_check_mob() {

	if (session_id() == '') {session_start();}

	$data = array();

	$dig_login_details = digit_get_login_fields();
	$mobileaccp        = $dig_login_details['dig_login_mobilenumber'];
	$otpaccp           = $dig_login_details['dig_login_otp'];
	$countrycode       = sanitize_text_field( $_REQUEST['countrycode'] );

	$digit_gateway = dig_gatewayToUse( $countrycode );
	if ( $digit_gateway == 1 ) {
		$data['ak'] = 1;
	} else {
		$data['ak'] = 0;
	}

	if ( $digit_gateway == 13 ) {
		$data['firebase'] = 1;
	} else {
		$data['firebase'] = 0;
	}

	$mobileno = sanitize_mobile_field_dig( $_REQUEST['mobileNo'] );
	$csrf     = $_REQUEST['csrf'];
	$login    = $_REQUEST['login'];


	if ( ! wp_verify_nonce( $csrf, 'dig_form' ) ) {
		$data['code'] = '0';
		digit_send_json_status( $data );
		die();
	}


	if ( isset( $_POST['captcha'] ) && isset( $_POST['captcha_ses'] ) ) {
		$ses = filter_var( $_POST['captcha_ses'], FILTER_SANITIZE_NUMBER_FLOAT );
		if ( isset( $_SESSION[ 'dig_captcha' . $ses ] ) ) {
			if ( $_POST['captcha'] != $_SESSION[ 'dig_captcha' . $ses ] ) {
				wp_send_json_error( array( 'message' => __( 'Please enter a valid captcha!', 'digits' ) ) );
				die();
			}
		}
	}
	$users_can_register = get_option( 'dig_enable_registration', 1 );
	$digforgotpass      = get_option( 'digforgotpass', 1 );
	if ( $users_can_register == 0 && $login == 2 ) {
		$data['code'] = '0';
		digit_send_json_status( $data );
		die();
	}
	if ( $digforgotpass == 0 && $login == 3 ) {
		$data['code'] = '0';
		digit_send_json_status( $data );
		die();
	}

	if ( $login == 2 || $login == 11 ) {
		$result = false;
		if ( isset( $_POST['username'] ) && ! empty( $_POST['username'] ) ) {
			$username = sanitize_text_field( $_POST['username'] );
			if ( username_exists( $username ) ) {
				wp_send_json_error( array( 'message' => __( 'Username is already in use!', 'digits' ) ) );
				die();
			}
			$result = true;
		}
		if ( isset( $_POST['email'] ) && ! empty( $_POST['email'] ) ) {
			$email = sanitize_text_field( $_POST['email'] );

			$validation_error = new WP_Error();
			$validation_error = apply_filters( 'digits_validate_email', $validation_error, $email );

			if ( $validation_error->get_error_code() ) {
				wp_send_json_error( array( 'message' => $validation_error->get_error_message() ) );
				die();
			}


			if ( email_exists( $email ) ) {
				if ( $login == 11 ) {
					$user = get_user_by( 'email', $email );
					if ( $user->ID != get_current_user_id() ) {
						wp_send_json_error( array( 'message' => __( 'Email is already in use!', 'digits' ) ) );
						die();
					}

				} else {
					wp_send_json_error( array( 'message' => __( 'Email is already in use!', 'digits' ) ) );
					die();
				}
			}
			$result = true;

		}

		if ( empty( $mobileno ) && $result = true ) {
			$data['code'] = 1;
			digit_send_json_status( $data );
			die();
		}


	}


	if ( ( $otpaccp == 0 && $login == 1 ) || ( $mobileaccp == 0 && $login == 1 ) ) {
		$data['code'] = '-99';
		digit_send_json_status( $data );
		die();
	}

	if ( ! checkwhitelistcode( $countrycode ) ) {
		$data['code'] = '-99';
		digit_send_json_status( $data );
		die();
	}


	$user1 = getUserFromPhone( $countrycode . $mobileno );
	if ( ( $user1 != null && $login == 11 ) || ( $user1 != null && $login == 2 ) ) {

		$data['code'] = '-1';
		digit_send_json_status( $data );
		die();
	}
	if ( $user1 != null || $login == 2 || $login == 11 ) {


		if ( $digit_gateway == 1 || $digit_gateway == 13 ) {
			$result = 1;
		} else {
			$result = digit_create_otp( $countrycode, $mobileno );
		}
		$data['code'] = $result;
		digit_send_json_status( $data );


		die();
	} else {
		digit_send_json_status( array( 'code' => "-11" ) );

		die();
	}

	digit_send_json_status( array( 'code' => "0" ) );
	die();

}


function digit_send_json_status( $data ) {
	if ( isset( $_REQUEST['json'] ) ) {
		wp_send_json( $data );
	} else {
		echo $data['code'];
	}
	die();
}

function digit_create_otp( $countrycode, $mobileno ) {
	$digit_gateway = dig_gatewayToUse( $countrycode );


	if ( $digit_gateway != 13 ) {

		if ( OTPexists( $countrycode, $mobileno ) ) {
			return "1";

		}

		$code = dig_get_otp();


		if ( ! digit_send_otp( $digit_gateway, $countrycode, $mobileno, $code ) ) {
			return "0";
		}


		$mobileVerificationCode = md5( $code );

		global $wpdb;
		$table_name = $wpdb->prefix . "digits_mobile_otp";

		$db = $wpdb->replace( $table_name, array(
			'countrycode' => $countrycode,
			'mobileno'    => $mobileno,
			'otp'         => $mobileVerificationCode,
			'time'        => date( "Y-m-d H:i:s", strtotime( "now" ) )
		), array(
				'%d',
				'%s',
				'%s',
				'%s'
			)
		);

		if ( ! $db ) {
			return "0";

		}

	}

	return "1";

}


if ( ! function_exists( 'digit_send_otp' ) ) {

	function digit_send_otp( $digit_gateway, $countrycode, $mobile, $otp, $testCall = false ) {


		$dig_messagetemplate = get_option( "dig_messagetemplate", "Your OTP for %NAME% is %OTP%" );
		$dig_messagetemplate = apply_filters( '$dig_messagetemplate', $dig_messagetemplate );
		$dig_messagetemplate = str_replace( "%NAME%", get_option( 'blogname' ), $dig_messagetemplate );
		$dig_messagetemplate = str_replace( "%OTP%", $otp, $dig_messagetemplate );

		return digit_send_message( $digit_gateway, $countrycode, $mobile, $otp, $dig_messagetemplate, $testCall );

	}
}
function digit_send_message( $digit_gateway, $countrycode, $mobile, $otp, $dig_messagetemplate, $testCall = false ) {


	switch ( $digit_gateway ) {
		case 2:


			$tiwilioapicred = get_option( 'digit_twilio_api' );


			$twiliosenderid = $tiwilioapicred['twiliosenderid'];


			$sid   = $tiwilioapicred['twiliosid'];
			$token = $tiwilioapicred['twiliotoken'];


			try {
				$client = new Client( $sid, $token );
				$result = $client->messages->create(
					$countrycode . $mobile,
					array(
						'From' => $twiliosenderid,
						'Body' => $dig_messagetemplate
					)
				);
			} catch ( Exception $e ) {
				if ( $testCall ) {
					return $e->getMessage();
				}

				return false;
			}

			if ( $testCall ) {
				return $result;
			}

			return true;
		case 3:

			$msg91apicred = get_option( 'digit_msg91_api' );


			$authKey    = $msg91apicred['msg91authkey'];
			$senderId   = $msg91apicred['msg91senderid'];
			$msg91route = $msg91apicred['msg91route'];

			if ( empty( $msg91route ) ) {
				$msg91route = 2;
			}
			$message = urlencode( $dig_messagetemplate );

			if ( $msg91route == 1 ) {


				$postData = array(
					'authkey'    => $authKey,
					'mobile'     => str_replace( "+", "", $countrycode ) . $mobile,
					'message'    => $message,
					'sender'     => $senderId,
					'otp'        => $otp,
					'otp_expiry' => 10
				);


				$url = "https://control.msg91.com/api/sendotp.php?" . http_build_query( $postData );
				$ch  = curl_init();
				curl_setopt_array( $ch, array(
					CURLOPT_URL            => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_CUSTOMREQUEST  => 'GET'

				) );
				curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
				curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );

				$result = curl_exec( $ch );


				if ( $testCall ) {
					return $result;
				}

				if ( curl_errno( $ch ) ) {

					if ( $testCall ) {
						return "curl error:" . curl_errno( $ch );
					}

					return false;
				}

				curl_close( $ch );

			} else {


				$postData = array(
					'authkey'  => $authKey,
					'mobiles'  => $mobile,
					'message'  => $message,
					'sender'   => $senderId,
					'route'    => 4,
					'&country' => $countrycode
				);


				$url = "https://control.msg91.com/api/sendhttp.php";
				$ch  = curl_init();
				curl_setopt_array( $ch, array(
					CURLOPT_URL            => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_POST           => true,
					CURLOPT_POSTFIELDS     => $postData

				) );
				curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
				curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );

				$result = curl_exec( $ch );


				if ( curl_errno( $ch ) ) {
					if ( $testCall ) {
						return "curl error:" . curl_errno( $ch );
					}

					return false;
				}
				curl_close( $ch );

				if ( $testCall ) {
					return $result;
				}

				return true;
			}

			return true;

		case 4:
			$apikey = get_option( 'digit_yunpianapi' );

			$data = array( 'text' => $dig_messagetemplate, 'apikey' => $apikey, 'mobile' => $mobile );


			$ch = curl_init();

			curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				'Accept:text/plain;charset=utf-8',
				'Content-Type:application/x-www-form-urlencoded',
				'charset=utf-8'
			) );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

			curl_setopt( $ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json' );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $data ) );
			$result = curl_exec( $ch );


			if ( curl_errno( $ch ) ) {
				if ( $testCall ) {
					return "curl error:" . curl_errno( $ch );
				}

				return false;
			}
			curl_close( $ch );

			if ( $testCall ) {
				return $result;
			}

			if ( $result === false ) {
				return false;
			}

			return true;
		case 5:

			$clickatell = get_option( 'digit_clickatell' );

			$apikey = $clickatell['api_key'];
			$from   = $clickatell['from'];


			$toarray   = array();
			$toarray[] = $countrycode . $mobile;

			$cs_array            = array();
			$cs_array['content'] = $dig_messagetemplate;
			if ( ! empty( $from ) ) {
				$cs_array['from'] = $from;
			}
			$data        = $cs_array;
			$data['to']  = $toarray;
			$data_string = json_encode( $data );


			$ch = curl_init();


			curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Accept: application/json',
				'Authorization: ' . $apikey,

			) );


			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );

			curl_setopt( $ch, CURLOPT_URL, 'https://platform.clickatell.com/messages' );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string );
			$result = curl_exec( $ch );


			if ( curl_errno( $ch ) ) {
				if ( $testCall ) {
					return "curl error:" . curl_errno( $ch );
				}

				return false;
			}
			curl_close( $ch );

			if ( $testCall ) {
				return $result;
			}

			if ( $result === false ) {
				return false;
			}


			return true;
		case 6:
			$clicksend = get_option( 'digit_clicksend' );
			$username  = $clicksend['apiusername'];
			$apikey    = $clicksend['apikey'];
			$from      = $clicksend['from'];


			$data             = array();
			$message          = array();
			$message[0]       = array(
				'body' => $dig_messagetemplate,
				'from' => $from,
				'to'   => $countrycode . $mobile
			);
			$data['messages'] = $message;

			$ch = curl_init();

			curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: Basic ' . base64_encode( "$username:$apikey" )
			) );

			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

			curl_setopt( $ch, CURLOPT_URL, 'https://rest.clicksend.com/v3/sms/send' );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
			$result = curl_exec( $ch );


			if ( curl_errno( $ch ) ) {
				if ( $testCall ) {
					return "curl error:" . curl_errno( $ch );
				}

				return false;
			}
			curl_close( $ch );

			if ( $result === false ) {
				return false;
			}

			if ( $testCall ) {
				return $result;
			}

			return true;
		case 7:

			try {


				$clockwork = get_option( 'digit_clockwork' );


				$clockworkapi = $clockwork['clockworkapi'];
				$from         = $clockwork['from'];


				$clockwork = new WordPressClockwork( $clockworkapi );

				// Setup and send a message
				$message = array(
					'from'    => $from,
					'to'      => str_replace( "+", "", $countrycode ) . $mobile,
					'message' => $dig_messagetemplate
				);
				$result  = $clockwork->send( $message );

				// Check if the send was successful
				if ( $result['success'] ) {

					if ( $testCall ) {
						return $result;
					}

					return true;

				} else {
					return false;
				}
			} catch ( ClockworkException $e ) {
				if ( $testCall ) {
					return $e->getMessage();
				}

				return false;

			}
		case 8:

			$messagebird = get_option( 'digit_messagebird' );
			$accesskey   = $messagebird['accesskey'];
			$originator  = $messagebird['originator'];
			$data        = array(
				'body'       => $dig_messagetemplate,
				'originator' => $originator,
				'recipients' => str_replace( "+", "", $countrycode ) . $mobile
			);


			$ch = curl_init();

			curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				'Accept: application/json',
				'Content-Type: application/json',
				'Authorization: AccessKey ' . $accesskey
			) );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

			curl_setopt( $ch, CURLOPT_URL, 'https://rest.messagebird.com/messages?access_key=' . $accesskey );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
			$result = curl_exec( $ch );
			curl_close( $ch );

			if ( curl_errno( $ch ) ) {
				if ( $testCall ) {
					return "curl error:" . curl_errno( $ch );
				}

				return false;
			}

			if ( $testCall ) {
				return $result;
			}

			if ( $result === false ) {
				return false;
			}

			return true;

		case 9:
			$mobily = get_option( 'digit_mobily_ws' );

			$mobily_mobile = $mobily['mobile'];
			$password      = $mobily['password'];
			$sender        = $mobily['sender'];

			$data = array(
				'msg'             => convertToUnicode( $dig_messagetemplate ),
				'mobile'          => $mobily_mobile,
				'password'        => $password,
				'sender'          => $sender,
				'applicationType' => '68',
				'numbers'         => str_replace( "+", "", $countrycode ) . $mobile
			);


			$ch = curl_init();

			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

			curl_setopt( $ch, CURLOPT_URL, 'http://mobily.ws/api/msgSend.php?' . http_build_query( $data ) );
			$result = curl_exec( $ch );


			if ( curl_errno( $ch ) ) {
				if ( $testCall ) {
					return "curl error:" . curl_errno( $ch );
				}

				return false;
			}
			curl_close( $ch );

			if ( $testCall ) {
				return $result;
			}

			if ( $result === false ) {
				return false;
			}

			return true;
		case 10:
			$nexmo     = get_option( 'digit_nexmo' );
			$from      = $nexmo['from'];
			$apikey    = $nexmo['api_key'];
			$apisecret = $nexmo['api_secret'];

			$data = array(
				'text'       => $dig_messagetemplate,
				'to'         => $countrycode . $mobile,
				'from'       => $from,
				'type'       => 'unicode',
				'api_key'    => $apikey,
				'api_secret' => $apisecret
			);


			$ch = curl_init();


			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

			curl_setopt( $ch, CURLOPT_URL, 'https://rest.nexmo.com/sms/json' );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $data ) );
			$result = curl_exec( $ch );


			if ( curl_errno( $ch ) ) {
				if ( $testCall ) {
					return "curl error:" . curl_errno( $ch );
				}

				return false;
			}
			curl_close( $ch );

			if ( $testCall ) {
				return $result;
			}

			if ( $result === false ) {
				return false;
			}

			return true;
		case 11:
			$pilvo     = get_option( 'digit_pilvo' );
			$authid    = $pilvo['auth_id'];
			$authtoken = $pilvo['auth_token'];
			$sender_id = $pilvo['sender_id'];

			$data = array(
				'text' => $dig_messagetemplate,
				'src'  => $sender_id,
				'dst'  => $countrycode . $mobile,
			);


			$ch = curl_init();

			curl_setopt( $ch, CURLOPT_USERPWD, $authid . ":" . $authtoken );

			curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ) );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

			curl_setopt( $ch, CURLOPT_URL, 'https://api.plivo.com/v1/Account/' . $authid . '/Message/' );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
			$result = curl_exec( $ch );


			if ( curl_errno( $ch ) ) {
				if ( $testCall ) {
					return "curl error:" . curl_errno( $ch );
				}

				return false;
			}
			curl_close( $ch );

			if ( $testCall ) {
				return $result;
			}

			if ( $result === false ) {
				return false;
			}

			return true;
		case 12:

			$smsapi = get_option( 'digit_smsapi' );
			$token  = $smsapi['token'];
			$from   = $smsapi['from'];
			$params = array(
				'to'      => str_replace( "+", "", $countrycode ) . $mobile,
				'from'    => $from,
				'message' => $dig_messagetemplate,
			);

			$url = 'https://api.smsapi.com/sms.do';
			$c   = curl_init();
			curl_setopt( $c, CURLOPT_URL, $url );
			curl_setopt( $c, CURLOPT_POST, true );
			curl_setopt( $c, CURLOPT_POSTFIELDS, $params );
			curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $c, CURLOPT_HTTPHEADER, array(
				"Authorization: Bearer $token"
			) );

			$content     = curl_exec( $c );
			$http_status = curl_getinfo( $c, CURLINFO_HTTP_CODE );


			if ( $testCall ) {
				return $content;
			}

			if ( curl_errno( $c ) ) {
				if ( $testCall ) {
					return "curl error:" . curl_errno( $c );
				}

				return false;
			}

			curl_close( $c );

			if ( $http_status != 200 ) {
				return false;
			}

			return true;
		case 13:
			return true;
		case 14:
			$unifonic  = get_option( 'digit_unifonic' );
			$app_sid   = $unifonic['appsid'];
			$sender_id = $unifonic['senderid'];

			$params = 'AppSid=' . $app_sid . '&Recipient=' . str_replace( "+", "", $countrycode ) . $mobile . '&Body=' . $dig_messagetemplate;
			if ( ! empty( $sender_id ) ) {
				$params = $params . "&SenderID=" . $sender_id;
			}


			$c = curl_init();
			curl_setopt( $c, CURLOPT_URL, "http://api.unifonic.com/rest/Messages/Send" );
			curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $c, CURLOPT_HEADER, false );
			curl_setopt( $c, CURLOPT_POST, true );
			curl_setopt( $c, CURLOPT_POSTFIELDS, $params );


			curl_setopt( $c, CURLOPT_HTTPHEADER, array( "Content-Type: application/x-www-form-urlencoded" ) );
			$result = curl_exec( $c );


			if ( $testCall ) {
				return $result;
			}
			if ( curl_errno( $c ) ) {
				if ( $testCall ) {
					return "curl error:" . curl_errno( $c );
				}

				return false;
			}

			curl_close( $c );

			if ( $result === false ) {
				return false;
			}

			return true;
		case 15:

			$kaleyra   = get_option( 'digit_kaleyra' );
			$api_key   = $kaleyra['api_key'];
			$sender_id = $kaleyra['sender_id'];
			$curl      = curl_init();


			$url = "http://api-alerts.solutionsinfini.com/v4/?method=sms&sender=" . $sender_id . "&to=" . str_replace( "+", "", $countrycode ) . $mobile . "&message=" . urlencode( $dig_messagetemplate ) . "&api_key=" . $api_key;

			curl_setopt_array( $curl, array(
				CURLOPT_URL            => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => "",
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => "GET",
			) );
			$result = curl_exec( $curl );

			if ( curl_errno( $curl ) ) {
				$result = curl_error( $curl );
				if ( ! $testCall ) {
					return false;
				}
			}
			curl_close( $curl );


			if ( $testCall ) {
				return $result;
			}

			return true;
		case 16:
			$melipayamak = get_option( 'digit_melipayamak' );

			$username = $melipayamak['username'];
			$password = $melipayamak['password'];
			$from     = $melipayamak['from'];
			$api      = new MelipayamakApi( $username, $password );
			$sms      = $api->sms();
			$to       = '0' . $mobile;
			$result   = $sms->send( $to, $from, $dig_messagetemplate );
			if ( $testCall ) {
				return $result;
			}

			return true;

		case 17:
			$textlocal = get_option( 'digit_textlocal' );
			$apiKey    = $textlocal['api_key'];
			$sender    = $textlocal['sender'];


			$apiKey  = urlencode( $apiKey );
			$sender  = urlencode( $sender );
			$message = rawurlencode( $dig_messagetemplate );
			$numbers = str_replace( "+", "", $countrycode ) . $mobile;


			$data = array( 'apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message );


			$ch = curl_init( 'https://api.textlocal.in/send/' );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			$result = curl_exec( $ch );


			if ( curl_errno( $ch ) ) {
				$result = curl_error( $ch );
				if ( ! $testCall ) {
					return false;
				}
			}


			curl_close( $ch );

			if ( $testCall ) {
				return $result;
			}


			return true;
		case 18:

			$alibaba       = get_option( 'digit_alibaba' );
			$access_key    = $alibaba['access_key'];
			$access_secret = $alibaba['access_secret'];
			$from          = $alibaba['from'];

			$number = str_replace( "+", "", $countrycode ) . $mobile;

			try {
				AlibabaCloud::accessKeyClient( $access_key, $access_secret )
				            ->regionId( 'ap-southeast-1' )
				            ->asDefaultClient();

				$result = AlibabaCloud::rpc()
				                      ->product( 'Dysmsapi' )
				                      ->host( 'dysmsapi.ap-southeast-1.aliyuncs.com' )
				                      ->version( '2018-05-01' )
				                      ->action( 'SendMessageToGlobe' )
				                      ->method( 'POST' )
				                      ->options( [
					                      'query' => [
						                      "To"      => $number,
						                      "From"    => $from,
						                      "Message" => $dig_messagetemplate,
					                      ],
				                      ] )
				                      ->request();

			} catch ( ClientException $e ) {
				if ( $testCall ) {
					return $e->getErrorMessage();
				}

				return false;
			} catch ( ServerException $e ) {
				if ( $testCall ) {
					return $e->getErrorMessage();
				}

				return false;
			} catch ( Exception $e ) {
				if ( $testCall ) {
					return $e->getErrorMessage();
				}

				return false;
			}

			return true;
		case 19:
			$adnsms      = get_option( 'digit_adnsms' );
			$api_key     = $adnsms['api_key'];
			$api_secret  = $adnsms['api_secret'];
			$requestType = 'OTP';
			$messageType = 'UNICODE';
			$number      = str_replace( "+", "", $countrycode ) . $mobile;

			$sms    = new AdnSmsNotification( $api_key, $api_secret );
			$result = $sms->sendSms( $requestType, $dig_messagetemplate, $number, $messageType );
			if ( $testCall ) {
				return $result;
			}

			return true;
		case 20:
			$netgsm      = get_option( 'digit_netgsm' );
			$username    = $netgsm['username'];
			$password    = $netgsm['password'];
			$from        = $netgsm['from'];
			$phone       = str_replace( "+", "", $countrycode ) . $mobile;
			$request_url = 'https://api.netgsm.com.tr/sms/send/otp';
			$xml         = array(
				'body' => '<?xml version="1.0"?>
                                <mainbody>
                                    <header>
                                        <usercode>' . $username . '</usercode>
                                        <password>' . $password . '</password>
                                        <msgheader>' . $from . '</msgheader>
                                    </header>
                                    <body>
                                        <msg><![CDATA[' . $dig_messagetemplate . ']]></msg>
                                        <no>' . $phone . '</no>
                                    </body>
                                </mainbody>'
			);
			$result      = wp_remote_post( $request_url, $xml );
			if ( $testCall ) {
				return $result;
			}

			return true;
		case 21:
			$smsc   = get_option( 'digit_smsc_ru' );
			$login  = $smsc['login'];
			$psw    = $smsc['password'];
			$sender = $smsc['sender'];
			$phone  = $countrycode . $mobile;

			$data = array(
				'mes'    => $dig_messagetemplate,
				'sender' => $sender,
				'login'  => $login,
				'psw'    => $psw,
				'phones' => $phone
			);


			$ch = curl_init();

			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_URL, 'https://mcpn.us/sendsmsapi?' . http_build_query( $data ) );
			$result = curl_exec( $ch );


			curl_close( $ch );

			if ( $result === false ) {
				return false;
			}

			if ( $testCall ) {
				return $result;
			}

			return true;

		case 22:
			$targetSms   = get_option( 'digit_targetsms' );
			$login  = $targetSms['login'];
			$pwd    = $targetSms['password'];
			$sender = $targetSms['sender'];

			$phone      = str_replace( "+", "", $countrycode ) . $mobile;
			$src = '<?xml version="1.0" encoding="utf-8"?>
<message><sender>'.$sender.'</sender><text>'.$dig_messagetemplate.'</text><abonent phone="'.$phone.'" /></message>
<request><security><login value="'.$login.'" /><password value="'.$pwd.'" /></security></request>';

			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-type: text/xml; charset=utf-8' ) );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_CRLF, true );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $src );
			curl_setopt( $ch, CURLOPT_URL, 'https://sms.targetsms.ru/xml/' );
			$result = curl_exec( $ch );
			curl_close( $ch );

			if ( $result === false ) {
				return false;
			}

			if ( $testCall ) {
				return $result;
			}

			return true;
		case 23:
			$ghasedak   = get_option( 'digit_ghasedak' );

			$api_key = $ghasedak['api_key'];

			$headers = array(
				'apikey:' . $api_key,
				'Accept: application/json',
				'Content-Type: application/x-www-form-urlencoded',
				'charset: utf-8'
			);

			$params = array(
				"receptor" => $mobile,
				"message" => $dig_messagetemplate
			);


			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_CRLF, true );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_URL, 'http://api.ghasedak.io/v2/sms/send/simple' );
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

			$result = curl_exec( $ch );
			curl_close( $ch );

			if ( $result === false ) {
				return false;
			}

			if ( $testCall ) {
				return $result;
			}


			return true;
		case 24:
			$farapayamak   = get_option( 'digit_farapayamak' );
			$username  = $farapayamak['username'];
			$password    = $farapayamak['password'];
			$from = $farapayamak['sender'];

			$phone      = str_replace( "+", "", $countrycode ) . $mobile;

			$params = array(
				"UserName" => $username,
				"PassWord" => $password,
				"From" => $from,
				"To" => $phone,
				"Text" => $dig_messagetemplate,
			);

			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/x-www-form-urlencoded; charset=utf-8' ) );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_CRLF, true );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($params) );
			curl_setopt( $ch, CURLOPT_URL, 'http://api.payamak-panel.com/post/Send.asmx' );
			$result = curl_exec( $ch );
			curl_close( $ch );

			if ( $result === false ) {
				return false;
			}

			if ( $testCall ) {
				return $result;
			}

			return true;

		case 999:
			$LimeCellular = get_option( 'digit_lime_cellular' );

			$user       = $LimeCellular['user'];
			$api_id     = $LimeCellular['api_id'];
			$short_code = $LimeCellular['short_code'];

			$data = array(
				'message'   => $dig_messagetemplate,
				'user'      => $user,
				'api_id'    => $api_id,
				'shortcode' => $short_code,
				'mobile'    => str_replace( "+", "", $countrycode ) . $mobile
			);


			$ch = curl_init();

			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_URL, 'https://mcpn.us/sendsmsapi?' . http_build_query( $data ) );
			$result = curl_exec( $ch );


			curl_close( $ch );

			if ( $result === false ) {
				return false;
			}

			return true;
		default:
			return false;
	}


}

add_action( "wp_ajax_nopriv_digits_login_user", "digits_login_user" ,10);


function digits_login_user() {


	$code = sanitize_text_field( $_REQUEST['code'] );
	$csrf = sanitize_text_field( $_REQUEST['csrf'] );


	$dig_login_details = digit_get_login_fields();
	$mobileaccp        = $dig_login_details['dig_login_mobilenumber'];
	$otpaccp           = $dig_login_details['dig_login_otp'];


	if ( ! wp_verify_nonce( $csrf, 'crsf-otp' ) || $mobileaccp == 0 || $otpaccp == 0 ) {
		echo '0';
		die();
	}


	$json = getUserPhoneFromAccountkit( $code );

	$phoneJson = json_decode( $json, true );


	$phone = $phoneJson['phone'];


	$rememberMe = false;

	if ( isset( $_REQUEST['rememberMe'] ) && $_REQUEST['rememberMe'] == 'true' ) {
		$rememberMe = true;
	}


	if ( $json != null ) {
		$user1 = getUserFromPhone( $phone );
		if ( $user1 ) {
			wp_set_current_user( $user1->ID, $user1->user_login );
			wp_set_auth_cookie( $user1->ID, $rememberMe );

			do_action( 'wp_login', $user1->user_login, $user1 );

			echo '1';
			die();
		} else {
			echo '-1';
			die();
		}
	} else {
		echo '-9';
		die();
	}


	echo '0';
	die();
}


function dig_get_otp( $isPlaceHolder = false ) {
	$dig_otp_size = get_option( "dig_otp_size", 5 );


	$code = "";
	for ( $i = 0; $i < $dig_otp_size; $i ++ ) {
		if ( ! $isPlaceHolder ) {
			$code .= rand( 0, 9 );
		} else {
			$code .= '-';
		}

	}


	return $code;
}

function digits_test_api() {

	if ( ! current_user_can( 'manage_options' ) ) {
		echo '0';
		die();
	}

	$mobile      = sanitize_text_field( $_POST['digt_mobile'] );
	$countrycode = sanitize_text_field( $_POST['digt_countrycode'] );
	if ( empty( $mobile ) || ! is_numeric( $mobile ) || empty( $countrycode ) || ! is_numeric( $countrycode ) ) {
		_e( 'Invalid Mobile Number', 'digits' );
		die();
	}

	$gateway = sanitize_text_field( $_POST['gateway'] );

	$code = dig_get_otp();

	$result = digit_send_otp( $gateway, $countrycode, $mobile, $code, true );
	if ( ! $result ) {
		_e( 'Error', 'digits' );
		die();
	}
	print_r( $result );
	die();

}

add_action( 'wp_ajax_digits_test_api', 'digits_test_api' );


function dig_validate_login_captcha() {
	if (session_id() == '')
	{session_start();}

	$ses = filter_var( $_POST['dig_captcha_ses'], FILTER_SANITIZE_NUMBER_FLOAT );
	if ( $_POST['digits_reg_logincaptcha'] != $_SESSION[ 'dig_captcha' . $ses ] ) {
		return false;
	} else if ( isset( $_SESSION[ 'dig_captcha' . $ses ] ) ) {
		unset( $_SESSION[ 'dig_captcha' . $ses ] );

		return true;
	}

}


?>
