<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Cart_Free_Products {
	/**
	 * @var array rule_id => product_id => [qty, price]
	 */
	private $products = array();

	/**
	 * @var array product_id => wc_product
	 */
	private $cached_products = array();

	public function __construct() {}

	/**
	 * @param int $rule_id
	 * @param WC_Product $product
	 * @param int $qty
	 */
	public function add( $rule_id, $product, $qty = 1 ) {
		if ( ! isset( $this->products[ $rule_id ] ) ) {
			$this->products[ $rule_id ] = array();
		}

		if ( ! isset( $this->products[ $rule_id ][ $product->get_id() ] ) ) {
			$this->products[ $rule_id ][ $product->get_id() ] = $qty;
			$this->cached_products[ $product->get_id() ] = $product;
		} else {
			$this->products[ $rule_id ][ $product->get_id() ] += $qty;
		}
	}

	public function get_qty( $product_id = null ) {
		if ( ! $product_id ) {
			return 0;
		}

		$qty = 0;
		foreach ( $this->products as $rule_id => $rule_free_products ) {
			foreach ( $rule_free_products as $rule_product_id => $product_qty ) {
				if ( $product_id == $rule_product_id ) {
					$qty += $product_qty;
				}
			}
		}

		return $qty;
	}

	public function get_items() {
		return $this->products;
	}

	public function get_product( $product_id ) {
		return isset( $this->cached_products[ $product_id ] ) ? $this->cached_products[ $product_id ] : false;
	}
}