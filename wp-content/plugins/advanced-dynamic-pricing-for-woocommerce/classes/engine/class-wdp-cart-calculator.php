<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Cart_Calculator {
	/**
	 * @var WDP_Rules_Collection
	 */
	private $rule_collection;
	/**
	 * @var WDP_Cart
	 */
	private $cart;

	/**
	 * @param WDP_Rules_Collection $rule_collection
	 */
	public function __construct( $rule_collection ) {
		$this->rule_collection = $rule_collection;
	}

	/**
	 * @return WDP_Rule[]
	 */
	public function get_rule_array() {
		return $this->rule_collection->to_array();
	}

	/**
	 * @return bool
	 */
	public function at_least_one_rule_active() {
		$rule_array = $this->rule_collection->to_array();
		return ! empty( $rule_array );
	}

	/**
	 * @param WDP_Cart       $cart
	 * @param WC_Product|int $the_product
	 *
	 * @return WDP_Rules_Collection
	 */
	public function find_product_matches( $cart, $the_product ) {
		$matched = array();

		$rule_array = $this->rule_collection->to_array();
		foreach ( $rule_array as $rule ) {
			$is_matched = false;
			try {
				$is_matched = $rule->is_product_matched( $cart, $the_product );
			} catch ( Exception $e ) {
			}

			if ( $is_matched ) {
				$matched[] = $rule;
			}
		}

		return new WDP_Rules_Collection( $matched );
	}

	/**
	 * @param WDP_Cart $cart
	 *
	 * @return WDP_Cart|boolean
	 */
	public function process_cart_new( $cart ) {
		$applied_rules = 0;

		$rule_array = $this->rule_collection->to_array();
		foreach ( $rule_array as $rule ) {
			if ( $rule->apply_to_cart( $cart ) ) {
				$applied_rules ++;
			}
		}

		return $applied_rules ? $cart : false; // no new cart
	}

	/**
	 * @param WDP_Cart $cart
	 * @param WDP_Rule[] $rule_array
	 *
	 * @return WDP_Cart|boolean
	 */
	public function process_cart_use_exact_rules( $cart, $rule_array ) {
		$applied_rules = 0;

		foreach ( $rule_array as $rule ) {
			if ( $rule->apply_to_cart( $cart ) ) {
				$applied_rules ++;
			}
		}

		return $applied_rules ? $cart : false; // no new cart
	}

	/**
	 * @param $cart WDP_Cart
	 * @param $product WDP_Product|integer
	 * @param $qty
	 *
	 * @return null|WDP_Product
	 *
	 */
	public function apply_changes_to_product( $cart, $product, $qty ) {
		if ( is_null( $cart ) || ! $cart ) {
			return $product;
		}

		if ( ! is_a( $product, 'WDP_Product' ) ) {
			if ( is_integer( $product ) ) {
				try {
					$product = new WDP_Product( $product );
				} catch ( Exception $e ) {
					return null;
				}
			} else {
				return null;
			}
		}

		if ( ! $product ) {
			return null;
		}

		$items = $cart->get_temporary_items_by_product( $product->get_wc_product() );
		if ( ! $items ) {
			return $product;
		}

		$item = end($items);
		/**
		 * @var $item WDP_Cart_Item
		 */

		$product->set_price( $item->get_initial_price() );
		$product->set_new_price( $item->get_price() );
		if ( (boolean) count( $item->get_history() ) ) {
			$product->rules_applied();
		}


		return $product;
	}

	/**
	 * @param WDP_Cart $cart
	 * @param WC_Product|int $the_product
	 * @param int $qty
	 *
	 * @return array|bool
	 */
	public function get_active_rules_for_product( $cart, $the_product, $qty ) {
		$product = false;

		if ( ! is_a( $the_product, 'WC_Product' ) ) {
			if ( is_integer( $the_product ) ) {
				$product = wc_get_product( $the_product );
			}
		} else {
			$product = $the_product;
		}

		if ( ! $product ) {
			return false;
		}

		$cart->add_product_to_calculate( $product, $qty );
		$rules = $this->get_applied_rules( $cart );

		return $rules;
	}

	/**
	 * @param $cart WDP_Cart
	 *
	 * @return WDP_Rule[]
	 */
	public function get_applied_rules( $cart ) {
		$this->cart = $cart;

		$rule_array = $this->rule_collection->to_array();
		$applied_rules = array();
		foreach ( $rule_array as $rule ) {
			if( $rule->apply_to_cart( $cart ) ) {
				$applied_rules[] = $rule;
			}
		}

		return $applied_rules;
	}

}