<?php

defined( 'ABSPATH' ) || exit;

/**
 * Session handler class.
 */
class Digits_Session_Handler {

	/**
	 * Cookie name used for the session.
	 *
	 * @var string cookie name
	 */
	protected $_cookie;

	protected $_has_cookie = false;

	public function __construct() {

	}

	public function get($key) {
		return ! isset( $_COOKIE[ $key ] ) ? false : $_COOKIE[ $key ];

	}
	public function set($key, $value){
		setcookie($key, $value, strtotime('+1 day'));
	}


}
