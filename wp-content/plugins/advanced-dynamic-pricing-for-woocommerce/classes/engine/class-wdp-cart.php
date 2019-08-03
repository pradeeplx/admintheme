<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Cart {

	/**
	 * @var WC_Cart
	 */
	private $wc_cart;

	/**
	 * @var WDP_Cart_Context
	 */
	private $context;

	/**
	 * @var WDP_Cart_Item[]
	 */
	private $items = array();

	/**
	 * @var array
	 */
	private $hash_item_mapping = array();

	/**
	 * @var array
	 */
	private $coupons = array();

	/**
	 * @var array WC_Coupon[]
	 */
	private $external_coupons = array();

	/**
	 * @var array
	 */
	private $fees = array();


	/**
	 * @var WDP_Cart_Adjustments_Shipping
	 */
	private $shipping_adjustments;

	/**
	 * @var WDP_Cart_Free_Products
	 */
	private $free_products;

	/**
	 * WDP_Cart constructor.
	 *
	 * @param $context WDP_Cart_Context
	 * @param $wc_cart WC_Cart|false
	 */
	public function __construct( $context, $wc_cart = false ) {
		$this->context = $context;
		$this->wc_cart = $wc_cart;

		if ( $this->wc_cart instanceof WC_Cart ) {
			foreach ( $this->wc_cart->get_cart_contents() as $wc_cart_item ) {
				if ( isset( $wc_cart_item['wdp_gifted'] ) ) {
					continue;
				}

				$hash                             = $this->calculate_hash( $wc_cart_item );
				$this->hash_item_mapping[ $hash ] = $wc_cart_item;

				$product = $wc_cart_item['data'];
				if ( apply_filters( 'wdp_skip_cart_item', false, $wc_cart_item, $product ) ) {
					continue;
				}

				$price = $this->get_original_price( $product, $wc_cart_item );
				$qty   = apply_filters( 'wdp_get_product_qty', $wc_cart_item['quantity'], $wc_cart_item );
				$item  = new WDP_Cart_Item( $hash, $price, $qty );
				if ( self::is_readonly_price( $product ) ) {
					$item->make_readonly_price();
				}

				$this->items[] = apply_filters( 'wdp_prepare_cart_item', $item, $wc_cart_item );
			}

			foreach ( $this->wc_cart->get_coupons() as $coupon ) {
				/**
				 * @var $coupon WC_Coupon
				 */
				$this->external_coupons[ $coupon->get_code() ] = $coupon;
			}
		}

		$this->free_products        = new WDP_Cart_Free_Products();
		$this->shipping_adjustments = new WDP_Cart_Adjustments_Shipping();
	}

	public function __clone() {
		$new_items = array();
		foreach ( $this->items as $item ) {
			$new_items[] = clone $item;
		}
		$this->items = $new_items;

		$this->free_products = clone $this->free_products;

		$new_shipping = new WDP_Cart_Adjustments_Shipping();
		if ( $this->shipping_adjustments->is_free() ) {
			$new_shipping->apply_free_shipping($this->shipping_adjustments->get_rule_id_applied_free_shipping());
		} else {
			foreach ( $this->shipping_adjustments->get_items() as $adjustment ) {
				$new_shipping->add( $adjustment['type'], $adjustment['value'], $adjustment['rule_id'] );
			}
		}
		$this->shipping_adjustments = $new_shipping;

	}

	/**
	 * @param             $product WC_Product
	 * @param array       $item_meta
	 *
	 * @return float
	 */
	public function get_original_price( $product, $item_meta = array() ) {
		return self::_get_original_price( $product, $this->context->get_price_mode(), $item_meta );
	}

	/**
	 * @param $product WC_Product
	 *
	 * @return boolean
	 */
	public function is_readonly_price( $product ) {
		return self::_is_readonly_price( $product, $this->context->get_price_mode() );
	}

	/**
	 * @param $product WC_Product
	 * @param $price_mode string
	 *
	 * @return bool
	 */
	private static function _is_readonly_price( $product, $price_mode ) {
		if ( $product->is_on_sale( 'edit' ) ) {
			if ( 'sale_price' === $price_mode ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param            $product WC_Product
	 * @param            $price_mode string
	 * @param array      $item_meta
	 *
	 * @return float
	 */
	private static function _get_original_price( $product, $price_mode, $item_meta = array() ) {
		if ( $product->is_on_sale( 'edit' ) ) {
			if ( 'sale_price' === $price_mode ) {
				$price = $product->get_sale_price( '' );
			} elseif ( 'discount_sale' === $price_mode ) {
				$price = $product->get_sale_price( '' );
			} else {
				$price = $product->get_regular_price( '' );
			}
		} else {
			$price = $product->get_price();
		}

		return apply_filters( "wdp_get_product_price", (float) $price, $product, $price_mode, $item_meta );
	}

	private function calculate_hash( $wc_cart_item ) {
		$qty = $wc_cart_item['quantity'];

		unset( $wc_cart_item['quantity'] );

		return md5( json_encode( $wc_cart_item ) );
	}

	public function get_item_data_by_hash( $hash ) {
		return isset( $this->hash_item_mapping[ $hash ] ) ? $this->hash_item_mapping[ $hash ] : null;
	}

	private function sort_items() {
		usort( $this->items, function ( $item_a, $item_b ) {
			/**
			 * @var $item_a WDP_Cart_Item
			 * @var $item_b WDP_Cart_Item
			 */
			if ( ! $item_a->is_temporary() && $item_b->is_temporary() ) {
				return - 1;
			}

			if ( $item_a->is_temporary() && ! $item_b->is_temporary() ) {
				return 1;
			}

			return 0;
		} );

	}

	public function get_mutable_items() {
		$this->sort_items();

		return array_filter( $this->items, function ( $item ) {
			return ! $item->is_immutable();
		} );
	}

	public function get_context() {
		return $this->context;
	}

	public function get_wc_cart() {
		return $this->wc_cart;
	}

	public function get_external_coupons() {
		return $this->external_coupons;
	}

	public function purge_mutable_items() {
		$this->items = array_filter( $this->items, function ( $item ) {
			/**
			 * @var $item WDP_Cart_Item
			 */
			return $item->is_immutable();
		} );
	}

	public function destroy_empty_items() {
		$this->items = array_values( array_filter( $this->items, function ( $item ) {
			/**
			 * @var $item WDP_Cart_Item
			 */
			return $item->get_qty() > 0;
		} ) );
	}

	/**
	 * @param $new_item WDP_Cart_Item|WDP_Cart_Item[]
	 */
	public function add_to_cart( $new_item ) {
		if ( is_array( $new_item ) ) {
			foreach ( $new_item as $item ) {
				$this->add_to_cart( $item );
			}
		}

		if ( ! ( $new_item instanceof WDP_Cart_Item ) ) {
			return;
		}

		foreach ( $this->items as &$item ) {
			if ( ! $item->is_immutable()
			     && ( $item->get_hash() === $new_item->get_hash() )
			     && ( $item->get_price() === $new_item->get_price() )
			     && ( md5( json_encode( $item->get_history() ) ) === md5( json_encode( $new_item->get_history() ) ) )
			     && ( $item->get_exclude_rules_hash() === $new_item->get_exclude_rules_hash() )
			) {
				$item->inc_qty( $new_item->get_qty() );

				return;
			}
		}

		// if unique item, create new
		$this->items[] = $new_item;


		return;
	}

	/**
	 * @param int|WC_Product $the_product
	 * @param int            $qty
	 */
	public function add_product_to_calculate( $the_product, $qty = 1 ) {
		if ( $the_product && is_numeric( $the_product ) ) {
			$the_product = wc_get_product( $the_product );
		}

		if ( ! ( $the_product instanceof WC_Product ) ) {
			return;
		}

		$hash = md5( $the_product );

		/** Prepare temporary cart item */
		$wc_cart_item               = array();
		$wc_cart_item['data']       = $the_product;
		$wc_cart_item['product_id'] = $the_product->get_id();

		$this->hash_item_mapping[ $hash ] = $wc_cart_item;

		$item = new WDP_Cart_Item( $hash, $this->get_original_price( $the_product, $wc_cart_item ), $qty );
		if ( self::is_readonly_price( $the_product ) ) {
			$item->make_readonly_price();
		}
		$item->mark_as_temporary();

		$this->add_to_cart( $item );
	}

	/** DISCOUNT AMOUNT AS COUPONS */

	public function add_coupon_amount( $coupon_amount, $rule_id, $coupon_name = '' ) {
		$this->coupons[] = array(
			'type'    => 'amount',
			'value'   => $coupon_amount,
			'rule_id' => $rule_id,
			'name'    => $coupon_name,
		);
	}

	public function add_coupon_percentage( $coupon_percentage, $rule_id, $coupon_name = '' ) {
		$this->coupons[] = array(
			'type'    => 'percentage',
			'value'   => $coupon_percentage,
			'rule_id' => $rule_id,
			'name'    => $coupon_name,
		);
	}

	public function add_coupon( $type, $value, $coupon_name ) {
		$this->coupons[] = array(
			'type'  => $type,
			'value' => $value,
			'name'  => $coupon_name,
		);
	}

	/** END DISCOUNT AMOUNT AS COUPONS */

	/** FEE */

	public function add_fee_amount( $fee_name, $fee_amount, $rule_id, $tax_class = "" ) {
		$this->fees[] = array(
			'type'      => 'amount',
			'value'     => $fee_amount,
			'rule_id'   => $rule_id,
			'name'      => $fee_name,
			'tax_class' => $tax_class,
		);
	}

	public function add_fee_percentage( $fee_name, $fee_percentage, $rule_id, $tax_class = "" ) {
		$this->fees[] = array(
			'type'      => 'percentage',
			'value'     => $fee_percentage,
			'rule_id'   => $rule_id,
			'name'      => $fee_name,
			'tax_class' => $tax_class,
		);
	}

	public function add_fee( $type, $fee_amount, $fee_name, $tax_class = "" ) {
		$this->fees[] = array(
			'type'      => $type,
			'value'     => $fee_amount,
			'name'      => $fee_name,
			'tax_class' => $tax_class,
		);
	}

	public function set_is_tax_exempt( $tax_exempt ) {
		$this->context->set_is_tax_exempt( $tax_exempt );
	}

	/** END FEE */

	/** SHIPPING */

	/**
	 * @param $shipping_amount
	 * @param $rule_id
	 */
	public function add_shipping_amount_adjustment( $shipping_amount, $rule_id ) {
		$this->shipping_adjustments->add_amount_discount( $shipping_amount, $rule_id );
	}

	/**
	 * @param $shipping_percentage
	 * @param $rule_id
	 */
	public function add_shipping_percentage_adjustment( $shipping_percentage, $rule_id ) {
		$this->shipping_adjustments->add_percentage_discount( $shipping_percentage, $rule_id );
	}

	/**
	 * @param $price
	 * @param $rule_id
	 */
	public function set_shipping_price( $price, $rule_id ) {
		$this->shipping_adjustments->set_fixed_price( $price, $rule_id );
	}

	/**
	 * @param $rule_id
	 */
	public function add_free_shipping( $rule_id ) {
		$this->shipping_adjustments->apply_free_shipping( $rule_id );
	}

	/** END SHIPPING */

	/**
	 * @param $rule_id int
	 * @param $product WC_Product
	 * @param $qty int
	 */
	public function gift_product( $rule_id, $product, $qty ) {
		$this->free_products->add( $rule_id, $product, $qty );
	}

	public function get_qty_used( $product_id ) {
		$temporary_qty = 0;
		foreach ( $this->items as $item ) {
			/**
			 * @var $item WDP_Cart_Item
			 */
			$original_item   = $this->get_item_data_by_hash( $item->get_hash() );
			$item_product_id = $original_item['product_id'];

			if ( $item->is_temporary() && $item_product_id === $product_id ) {
				$temporary_qty += $item->get_qty();
			}
		}

		return $product_id ? $this->free_products->get_qty( $product_id ) + $temporary_qty : null;
	}

	/**
	 * @return WDP_Cart_Item[]
	 */
	public function get_items() {
		return $this->items;
	}

	/**
	 * @return bool
	 */
	public function apply_to_wc_cart() {
		$wc_cart = WC()->cart;

		do_action( 'wdp_before_apply_to_wc_cart', $this, $wc_cart );

		/** Store removed_cart_contents to enable undo deleted items */
		$removed_cart_contents = $wc_cart->get_removed_cart_contents();
		$wc_cart->empty_cart();
		$wc_cart->set_removed_cart_contents( $removed_cart_contents );

		// Suppress total recalculation until finished.
		remove_action( 'woocommerce_add_to_cart', array( $wc_cart, 'calculate_totals' ), 20 );

		// show cart items prices such as they are not affected by WDP
		$cart_item_show_initial_price = apply_filters( 'wdp_is_item_price_changeable', true );

		/**
		 * Put to down items that are not filtered
		 */
		usort( $this->items, function ( $item_a, $item_b ) {
			/**
			 * @var $item_a WDP_Cart_Item
			 * @var $item_b WDP_Cart_Item
			 */
			if ( $item_a->get_history() && ! $item_b->get_history() ) {
				return - 1;
			}

			if ( ! $item_a->get_history() && $item_b->get_history() ) {
				return 1;
			}

			return 0;
		} );

		foreach ( $this->items as $item ) {
			$wc_cart_item = $this->get_item_data_by_hash( $item->get_hash() );
			/**
			 * @var $wc_product WC_Product
			 * @var $wdp_product WDP_Product
			 */

			$wc_product   = clone $wc_cart_item['data'];
			try {
				$wdp_product = new WDP_Product( $wc_product );
			} catch ( Exception $e ) {
				continue;
			}

			foreach ( $item->replace_rules_adjustments() as $adjustment_data ) {
				if ( ! empty( $adjustment_data['amount'] ) && ! empty( $adjustment_data['name'] ) ) {
					if ( $adjustment_data['amount'] > 0 ) {
						$this->add_coupon( 'item_adjustments', $adjustment_data['amount'], $adjustment_data['name'] );
					} else {
						$this->add_fee( 'item_adjustments', ( - 1 ) * $adjustment_data['amount'], $adjustment_data['name'] );
					}
				}
			}

			if ( $item->is_price_changed() ) {
				$wdp_product->set_price( $item->get_initial_price() );
				$wdp_product->set_new_price( $item->get_price() );
			}
			if ( $item->is_at_least_one_rule_changed_price() ) {
				$wdp_product->update_prices( $this->get_context() );
			}

			do_action( 'wdp_product_price_updated_before_apply_to_wc_cart', $wdp_product, $item->get_qty() );

			if ( $cart_item_show_initial_price ) {
				$product_price = $wdp_product->get_new_price();
				if ( $this->context->get_option( 'is_calculate_based_on_wc_precision' ) ) {
					$product_price = round( $product_price, wc_get_price_decimals() );
				}
			} else {
				$product_price = $wdp_product->get_price();
			}
			$wc_product->set_price( $product_price );

			$product_id = $wdp_product->get_id();
			if ( $wc_product instanceof WC_Product_Variation ) {
				/** @var WC_Product_Variation $wc_product */
				$variation_id = $product_id;
				$product_id   = $wc_product->get_parent_id();
				$variation    = $wc_cart_item['variation'];
			} else {
				$variation_id = null;
				$variation    = array();
			}

			$original_item  = $wc_cart_item;
			$default_keys = array(
				'key',
				'product_id',
				'variation_id',
				'variation',
				'quantity',
				'data',
				'data_hash',
				'line_tax_data',
				'line_subtotal',
				'line_subtotal_tax',
				'line_total',
				'line_tax',
			);
			foreach ( $default_keys as $key ) {
				unset($original_item[$key]);
			}

			$wdp_rules = array();
			foreach ( $item->get_history() as $rule_id => $amounts ) {
				$wdp_rules[ $rule_id ] = array_sum( $amounts );
			}
			$wdp_rules = apply_filters( 'wdp_rules_amount_for_item', $wdp_rules, $item, $wc_product );

			$cart_item_data = array(
//				'wdp_gifted'             => $cart_item['gifted'],
				'wdp_rules'              => $wdp_rules,
//				'wdp_rules_for_singular' => $cart_item['rules_for_singular'],
//				'wdp_history' => $item->get_history(),
			);

			//show old price?
			if ( $this->context->get_option( 'show_striked_prices' ) ) {
				$cart_item_data['wdp_original_price'] = $wdp_product->get_price();
			} else {
				if ( isset( $original_item['wdp_original_price'] ) ) {
					unset( $original_item['wdp_original_price'] );
				}
			}

			$original_cart_item_data = array_diff_key( $original_item, $cart_item_data );
//			$cart_item_data = array_merge($cart_item_data, $original_cart_item_data);
			$cart_item_data = apply_filters( 'wdp_cart_item_data_before_apply', $cart_item_data, $original_cart_item_data );


			$exclude_hooks = apply_filters( 'wdp_exclude_hooks_when_add_to_cart_calculated_items', array( 'woocommerce_add_cart_item_data' ) );
			$cart_item_key = WDP_Frontend::process_without_hooks( function () use ( $wc_cart, $product_id, $item, $variation_id, $variation, $cart_item_data ) {
				return $wc_cart->add_to_cart( $product_id, $item->get_qty(), $variation_id, $variation, $cart_item_data );
			}, $exclude_hooks );

			$original_cart_item_data = apply_filters( 'wdp_original_cart_item_data', $original_cart_item_data );

			//Must  replace the product in the cart!
			if ( $cart_item_key ) {
				$wc_cart->cart_contents[ $cart_item_key ] ['data'] = $wc_product;
				// restore cart item data after rules applied
				foreach ( $original_cart_item_data as $key => $value ) {
					$wc_cart->cart_contents[ $cart_item_key ][ $key ] = $value;
				}
			}

		}

		foreach ( $this->free_products->get_items() as $rule_id => $rule_free_products ) {
			foreach ( $rule_free_products as $product_id => $qty ) {
				try {
					$wdp_product = new WDP_Product( $this->free_products->get_product( $product_id ) );
				} catch ( Exception $e ) {
					continue;
				}

				$wdp_product->set_new_price( 0 );
				$wdp_product->update_prices( $this->get_context() );

				do_action( 'wdp_product_price_updated_before_apply_to_wc_cart', $wdp_product, $qty );

				$cart_item_data = array(
					'wdp_gifted' => $qty,
					'wdp_rules'  => array( $rule_id => $wdp_product->get_price() * $qty ),
				);

				//show old price?
				if ( $this->context->get_option( 'show_striked_prices' ) ) {
					$cart_item_data['wdp_original_price'] = $wdp_product->get_price();
				} else {
					if ( isset( $original_item['wdp_original_price'] ) ) {
						unset( $cart_item_data['wdp_original_price'] );
					}
				}

				$exclude_hooks = apply_filters( 'wdp_exclude_hooks_when_add_to_cart_calculated_items', array( 'woocommerce_add_cart_item_data' ) );
				$cart_item_key = WDP_Frontend::process_without_hooks( function () use ( $wc_cart, $product_id, $qty, $cart_item_data ) {
					return $wc_cart->add_to_cart( $product_id, $qty, 0, array(), $cart_item_data );
				}, $exclude_hooks );

				if ( $cart_item_key ) {
					if ( $cart_item_show_initial_price ) {
						$wc_cart->cart_contents[ $cart_item_key ] ['data']->set_price( 0 );
					} else {
						$wc_cart->cart_contents[ $cart_item_key ] ['data']->set_price( $wdp_product->get_price() );
					}
				}
			}
		}


		add_action( 'woocommerce_add_to_cart', array( $wc_cart, 'calculate_totals' ), 20, 0 );

		new WDP_Cart_Totals( $this, $wc_cart );

		do_action( 'wdp_after_apply_to_wc_cart', $this );

		return true;
	}

	/**
	 * @return array WDP_Cart_Item[]
	 */
	public function get_temporary_items() {
		$items = array();
		foreach ( $this->items as $cart_item ) {
			/**
			 * @var $item WDP_Cart_Item
			 */

			if ( $cart_item->is_temporary() ) {
				$items[] = clone $cart_item;
				break;
			}
		}

		return $items;
	}

	/**
	 * @param $product WC_Product
	 *
	 * @return array WDP_Cart_Item[]
	 */
	public function get_temporary_items_by_product( $product ) {
		$items = array();
		foreach ( $this->items as $cart_item ) {
			/**
			 * @var $item WDP_Cart_Item
			 */
			$original_item   = $this->get_item_data_by_hash( $cart_item->get_hash() );
			$item_product_id = $original_item['product_id'];

			if ( $cart_item->is_temporary() && $item_product_id === $product->get_id() ) {
				$items[] = clone $cart_item;
			}
		}

		return $items;
	}

	public function get_adjustments() {
		return array(
			'shipping' => $this->shipping_adjustments,
			'coupons' => $this->coupons,
			'fees' => $this->fees,
		);
	}

}