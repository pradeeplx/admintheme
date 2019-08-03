<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Cart_Adjustment_Discount_Amount implements WDP_Cart_Adjustment {
	private $data;

	public function __construct( $data ) {
		$this->data = $data;
	}

	/**
	 * @param WDP_Cart $cart
	 * @param          $set_collection WDP_Cart_Set_Collection
	 * @param int      $rule_id
	 *
	 * @return bool
	 */
	public function apply_to_cart( $cart, $set_collection, $rule_id ) {
		$options = $this->data['options'];

		$cart->add_coupon_amount( (float) $options[0], $rule_id, $options[1] );

		return true;
	}
}