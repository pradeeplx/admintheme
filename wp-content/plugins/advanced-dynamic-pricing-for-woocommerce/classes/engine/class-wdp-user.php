<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_User_Impl implements WDP_User {
	/** @var WP_User|null */
	private $wp_user;
	private $shipping_country;
	private $shipping_state;
	private $payment_method;
	private $shipping_methods;
	private $is_vat_exempt;

	public function __construct( $wp_user = null ) {
		$this->wp_user = $wp_user;
	}

	public function is_logged_in() {
		return is_user_logged_in() && get_current_user_id() === $this->get_id();
	}

	public function get_id() {
		return ! $this->is_empty_wp_user() ? $this->wp_user->ID : null;
	}

	public function get_roles() {
		if ( $this->is_empty_wp_user() ) {
			return array();
		}
		$roles = ( array ) $this->wp_user->roles;

		return apply_filters( 'wdp_current_user_roles', $roles, $this );
	}

	public function convert_for_strtotime( $time ) {
		if ( ! $time OR ! is_string( $time ) ) {
			return false;
		}

		if ( 'all_time' == $time ) {
			$time = 0;
		} elseif ( 'now' == $time ) {
			$time = 'today';
		} elseif ( 'this week' == $time ) {
			$time = 'last monday';
		} elseif ( 'this month' == $time ) {
			$time = 'first day of ' . date( 'F Y', current_time( 'timestamp' ) );
		} elseif ( 'this year' == $time ) {
			$time = 'first day of January ' . date( 'Y', current_time( 'timestamp' ) );
		}

		return $time;
	}
	
	public function get_order_count( $time_range ) {
		if ( $this->is_empty_wp_user() ) {
			return 0;
		}
		
		$user_id = $this->wp_user->ID;
		$time = $this->convert_for_strtotime($time_range);

		$orders = get_posts( array(
			'numberposts' => - 1,
			'meta_key'    => '_customer_user',
			'meta_value'  => $user_id,
			'post_type'   => wc_get_order_types(),
			'post_status' => array_keys( wc_get_order_statuses() ),
			'fields'      => 'ids',
			'date_query'  => array(
				array(
					'column' => 'post_date',
					'after'  => $time,
				),
			),
		) );

		return $orders ? count($orders) : 0;
	}

	public function is_empty_wp_user() {
		return ! $this->wp_user instanceof WP_User || empty( $this->wp_user->ID );
	}

	public function get_shipping_country() {
		return $this->shipping_country;
	}

	public function set_shipping_country( $country ) {
		$this->shipping_country = $country;
	}

	public function get_shipping_state() {
		return $this->shipping_state;
	}

	public function set_shipping_state( $state ) {
		$this->shipping_state = $state;
	}

	public function get_payment_method() {
		return $this->payment_method;
	}

	public function set_payment_method( $method ) {
		$this->payment_method = $method;
	}

	public function get_shipping_methods() {
		return $this->shipping_methods;
	}

	public function set_shipping_methods( $method ) {
		$this->shipping_methods = $method;
	}

	public function set_is_vat_exempt( $is_vat_exempt ) {
		$this->is_vat_exempt = wc_string_to_bool( $is_vat_exempt );
	}

	public function get_tax_exempt() {
		return $this->is_vat_exempt;
	}

	/**
	 * @param $customer WC_Customer
	 *
	 */
	public function apply_wc_customer( $customer ) {
		$this->shipping_country = $customer->get_shipping_country( '' );
		$this->shipping_state   = $customer->get_shipping_state( '' );
	}

	/**
	 * @param $session WC_Session
	 *
	 */
	public function apply_wc_session( $session ) {
		if ( is_checkout() ) {
			$this->payment_method = $session->get( 'chosen_payment_method' );
		}

		if ( is_checkout() OR ! WDP_Frontend::is_catalog_view() ) {
			$this->shipping_methods = $session->get( 'chosen_shipping_methods' );
		};
	}
}