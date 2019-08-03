<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Cart_Item {
	/**
	 * @var int
	 */
	private $qty;

	/**
	 * @var float
	 */
	private $initial_price;

	/**
	 * @var float
	 */
	private $price;

	/**
	 * @var string
	 */
	private $hash;

	/**
	 * @var string
	 */
	private $calc_hash;

	/**
	 * @var boolean
	 */
	private $immutable;

	/**
	 * @var boolean
	 */
	private $readonly_price;

	/**
	 * @var boolean
	 */
	private $temporary = false;

	private $history = array();

	/**
	 * For convert exact rule discount to coupon/fee and revert discount from price
	 *
	 * @var array
	 */
	private $rules_to_replace = array();

	public function __clone() {
		$this->recalculate_hash();
	}

	public function __construct( $hash, $price, $qty = 1 ) {
		$this->hash  = (string)$hash;
		$this->initial_price = (float)$price;
		$this->price = (float)$price;
		$this->qty = (float)$qty;
		$this->immutable = false;
		$this->readonly_price = false;
	}

	public function inc_qty( $qty ) {
		$this->qty += $qty;
		$this->recalculate_hash();
	}


	public function set_qty( $qty ) {
		$this->qty = $qty;
		$this->recalculate_hash();
	}

	public function dec_qty( $qty ) {
		if ( $this->qty < $qty ) {
			throw new Exception( 'Negative item quantity.' );
		}

		$this->qty -= $qty;
		$this->recalculate_hash();
	}

	public function is_enough_qty( $qty ) {
		return $this->qty >= $qty;
	}

	public function make_immutable() {
		$this->immutable = true;
		$this->recalculate_hash();
	}

	public function make_readonly_price() {
		$this->readonly_price = true;
		$this->recalculate_hash();
	}

	public function mark_as_temporary() {
		$this->temporary = true;
		$this->recalculate_hash();
	}

	public function is_immutable() {
		return $this->immutable;
	}

	public function is_readonly_price() {
		return $this->readonly_price;
	}

	public function is_temporary() {
		return $this->temporary;
	}

	public function get_price() {
		return $this->price;
	}

	public function get_total_price() {
		return $this->price * $this->qty;
	}

	public function get_hash() {
		return $this->hash;
	}

	public function get_calc_hash() {
		return $this->calc_hash;
	}

	private function recalculate_hash() {
		$data = array(
			'prototype_hash' => $this->hash,
			'initial_price'  => $this->initial_price,
			'price'          => $this->price,
			'qty'            => $this->qty,
			'immutable'      => $this->immutable,
			'temporary'      => $this->temporary,
		);

		$this->calc_hash = md5( json_encode( $data ) );
	}

	public function get_qty() {
		return $this->qty;
	}

	public function get_initial_price() {
		return $this->initial_price;
	}

	public function set_price( $rule_id, $price ) {
		if ( $this->readonly_price ) {
			return;
		}

		if ( ! isset( $this->history[ $rule_id ] ) ) {
			$this->history[ $rule_id ] = array();
		}
		$this->history[$rule_id][] = $this->price - $price;
		$this->price     = $price;
		$this->recalculate_hash();
	}

	public function get_history() {
		return $this->history;
	}

	public function exclude_rule_adjustments( $rule_id, $adjustment_name ) {
		$this->rules_to_replace[ $rule_id ] = $adjustment_name;
	}

	public function get_exclude_rules_hash() {
		return md5( json_encode( $this->rules_to_replace ) );
	}

	/**
	 * @param bool $oblivion
	 * @param null|array $rule_ids_to_obliviation
	 *
	 * @return array
	 */
	public function replace_rules_adjustments( $oblivion = false, $rule_ids_to_obliviation = null ) {
		$list_of_coupons_data = array();

		foreach ( $this->rules_to_replace as $rule_id => $coupon_name ) {
			if ( ! isset( $this->history[ $rule_id ] ) ) {
				continue;
			}

			$discount_amount = array_sum( $this->history[ $rule_id ] );
			$this->price     += $discount_amount;
			$list_of_coupons_data[$rule_id] =array(
				'amount' => $discount_amount * $this->qty,
				'name' => $coupon_name,
			);

			if ( $oblivion ) {
				if ( is_null( $rule_ids_to_obliviation ) || ( is_array( $rule_ids_to_obliviation ) && in_array( $rule_id, $rule_ids_to_obliviation ) ) ) {
					unset( $this->history[ $rule_id ] );
				}
			}
		}

		return $list_of_coupons_data;
	}

	public function is_at_least_one_rule_changed_price() {
		$history_rule_ids     = array_keys( $this->history );
		$rules_to_replace_ids = array_keys( $this->rules_to_replace );

		return count( array_diff( $history_rule_ids, $rules_to_replace_ids ) );
	}

	public function is_price_changed() {
		return count( $this->history );
	}
}