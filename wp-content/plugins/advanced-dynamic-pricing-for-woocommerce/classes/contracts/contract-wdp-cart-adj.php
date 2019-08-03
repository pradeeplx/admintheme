<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

interface WDP_Cart_Adjustment {

	/**
	 * @param array $data
	 */
	public function __construct( $data );

	/**
	 * @param WDP_Cart $cart
	 * @param          $set_collection WDP_Cart_Set_Collection
	 * @param int      $rule_id
	 *
	 * @return bool
	 */
	public function apply_to_cart( $cart, $set_collection, $rule_id );
}