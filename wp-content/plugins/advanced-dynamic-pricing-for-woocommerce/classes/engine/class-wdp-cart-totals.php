<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Cart_Totals {
	/**
	 * @var WDP_Cart_Context
	 */
	private $context;

	/**
	 * @var array
	 */
	private $fees;

	/**
	 * @var array
	 */
	private $coupons;

	/**
	 * @var array [coupon_name] => $coupon_ids[]
	 */
	private $grouped_coupons;

	/**
	 * @var array [coupon_name] => $coupon_id
	 */
	private $single_coupons;

	private $applied_coupons;
	private $external_coupons;

	/**
	 * @var WDP_Cart_Adjustments_Shipping
	 */
	private $shipping_adjustments;

	/**
	 * WDP_Cart_Totals constructor.
	 *
	 * @param $cart WDP_Cart
	 * @param $wc_cart WC_Cart
	 */
	public function __construct( $cart, $wc_cart ) {
		if ( is_null( $wc_cart ) ) {
			$wc_cart = WC()->cart;
		}

		$cart = apply_filters( 'wdp_cart_before_totals', $cart, $wc_cart );

		$this->context = $cart->get_context();
		$wc_cart->get_customer()->set_is_vat_exempt( $this->context->get_tax_exempt() );

		$this->external_coupons = $this->context->get_option('disable_external_coupons') ? array() : $cart->get_external_coupons();

		$cart_adjustments           = $cart->get_adjustments();
		$this->shipping_adjustments = isset( $cart_adjustments['shipping'] ) ? $cart_adjustments['shipping'] : null;
		$this->coupons              = isset( $cart_adjustments['coupons'] ) ? $cart_adjustments['coupons'] : array();
		$this->fees                 = isset( $cart_adjustments['fees'] ) ? $cart_adjustments['fees'] : array();

		add_filter( 'woocommerce_coupon_message', array( $this, 'no_coupon_msg' ), 10, 3 );
		add_filter( 'woocommerce_coupon_error', array( $this, 'no_coupon_msg' ), 10, 3 );

		// Calculate totals to handle fee, coupons and shipping which depends on cart contents(totals)
		// e.g. coupon with min/max spend
		$wc_cart->calculate_totals();

		$this->install_hooks( $wc_cart );
		$wc_cart->calculate_totals();

		remove_filter( 'woocommerce_coupon_message', array( $this, 'no_coupon_msg' ), 10 );
		remove_filter( 'woocommerce_coupon_error', array( $this, 'no_coupon_msg' ), 10 );
	}

	public function no_coupon_msg( $msg, $msg_code, $wc_coupon ) {
		return "";
	}

	public function install_hooks( $wc_cart ) {
		// fee
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'woocommerce_cart_calculate_fees' ), 10, 1 );

		// coupons
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'woocommerce_cart_calculate_coupons' ), 10, 1 );
		add_filter( 'woocommerce_get_shop_coupon_data', array( $this, 'woocommerce_get_shop_coupon_data' ), 10, 2 );
		$this->apply_coupons_to_wc_cart( $wc_cart );
		// delete [Remove] for coupons
		if ( $this->applied_coupons ) {
			add_filter( 'woocommerce_cart_totals_coupon_html', array(
				$this,
				'woocommerce_cart_totals_coupon_html'
			), 10, 3 );
		}

		do_action( 'wdp_cart_totals_install_hooks', $this );

		// apply shipping
		add_filter( 'woocommerce_package_rates', array( $this, 'woocommerce_package_rates' ), 10, 2 );
		add_filter( 'woocommerce_cart_shipping_method_full_label', array(
			$this,
			'woocommerce_cart_shipping_method_full_label'
		), 10, 2 );


		// To apply shipping we have to clear stored packages in session to allow 'woocommerce_package_rates' filter run
		foreach ( WC()->session->get_session_data() as $key => $value ) {
			if ( preg_match( '/(shipping_for_package_).*/', $key ) ) {
				unset( WC()->session->$key );
			}
		}
	}

	/**
	 * @param WC_Cart $wc_cart
	 */
	public function woocommerce_cart_calculate_fees( $wc_cart ) {
		$applied_fees         = array();
		$applied_fees_no_rule = array(); // external?
		$fees_tax             = array();
		$fees_tax_no_rule     = array();
		$cart_total           = $this->context->is_prices_includes_tax() ? $wc_cart->get_cart_contents_total() + $wc_cart->get_cart_contents_tax() : $wc_cart->get_cart_contents_total();
		foreach ( $this->fees as $i => $fee ) {
			$fee_amount = 0;
			$fee_type   = $fee['type'];
			$tax_class  = ! empty( $fee['tax_class'] ) ? $fee['tax_class'] : "";
			$taxable    = (boolean) $tax_class;

			if ( 'amount' === $fee_type ) {
				$fee_amount = $fee['value'];
			} elseif ( 'percentage' === $fee_type ) {
				$fee_amount = $cart_total * $fee['value'] / 100;
			} elseif ( 'item_adjustments' === $fee_type ) {
				$fee_amount = $fee['value'];
			}

			if ( ! empty( $fee_amount ) ) {
				if ( isset( $fee['rule_id'] ) ) {
					if ( $this->context->is_combine_multiple_fees() || empty( $fee['name'] ) ) {
						$fee_name = $this->context->get_option( 'default_fee_name' );
					} else {
						$fee_name = $fee['name'];
					}

					$fees_tax[ $fee_name ] = array(
						'taxable'   => $taxable,
						'tax_class' => $tax_class,
					);

					if ( ! isset( $applied_fees[ $fee_name ][ $fee['rule_id'] ] ) ) {
						$applied_fees[ $fee_name ][ $fee['rule_id'] ] = 0;
					}

					$applied_fees[ $fee_name ][ $fee['rule_id'] ] += $fee_amount;
				} else {
					$fee_name = $fee['name'];

					$fees_tax_no_rule[ $fee_name ] = array(
						'taxable'   => $taxable,
						'tax_class' => $tax_class,
					);

					if ( ! isset( $applied_fees_no_rule[ $fee_name ] ) ) {
						$applied_fees_no_rule[ $fee_name ] = 0;
					}

					$applied_fees_no_rule[ $fee_name ] += $fee_amount;
				}
			}
		}

		foreach ( $applied_fees as $fee_name => $amount_per_rule ) {
			$wc_cart->add_fee( $fee_name, array_sum( $amount_per_rule ), $fees_tax[ $fee_name ]['taxable'], $fees_tax[ $fee_name ]['tax_class'] );
		}

		foreach ( $applied_fees_no_rule as $fee_name => $fee_amount ) {
			$wc_cart->add_fee( $fee_name, $fee_amount, $fees_tax_no_rule[ $fee_name ]['taxable'], $fees_tax_no_rule[ $fee_name ]['tax_class'] );
		}

		$totals             = $wc_cart->get_totals();
		$totals['wdp_fees'] = $applied_fees;
		$wc_cart->set_totals( $totals );
	}

	/** APPLY COUPONS */


	/**
	 * @param WC_Cart $wc_cart
	 */
	private function apply_coupons_to_wc_cart( $wc_cart ) {
		$this->grouped_coupons = array();
		$this->single_coupons  = array();

		foreach ( $this->coupons as $coupon_id => $coupon ) {
			if ( empty( $coupon['value'] ) ) // skip zero coupons?
			{
				continue;
			}

			if ( 'amount' === $coupon['type'] ) {
				if ( $this->context->is_combine_multiple_discounts() || empty( $coupon['name'] ) ) {
					$coupon_name = $this->context->get_option( 'default_discount_name' );
				} else {
					$coupon_name = $coupon['name'];
				}
				$coupon_name = strtolower( $coupon_name );

				if ( ! isset( $this->grouped_coupons[ $coupon_name ] ) ) {
					$this->grouped_coupons[ $coupon_name ] = array();
				}
				$this->grouped_coupons[ $coupon_name ][] = $coupon_id;
			} elseif ( 'percentage' === $coupon['type'] ) {
				$template = ! empty( $coupon['name'] ) ? $coupon['name'] : $this->context->get_option( 'default_discount_name' );
				$template = strtolower( $template );

				$count = 1;
				do {
					$coupon_name = "{$template} #{$count}";
					$count ++;
				} while ( isset( $this->single_coupons[ $coupon_name ] ) );

				$this->single_coupons[ $coupon_name ] = $coupon_id;
			} elseif ( 'item_adjustments' === $coupon['type'] ) {
				$coupon_name = strtolower( $coupon['name'] );

				if ( ! isset( $this->grouped_coupons[ $coupon_name ] ) ) {
					$this->grouped_coupons[ $coupon_name ] = array();
				}
				$this->grouped_coupons[ $coupon_name ][] = $coupon_id;
			}
		}

		// remove postfix for single %% discount
		if ( count( $this->single_coupons ) == 1 ) {
			$keys                 = array_keys( $this->single_coupons );
			$values               = array_values( $this->single_coupons );
			$this->single_coupons = array( str_replace( ' #1', '', $keys[0] ) => $values[0] );
		}

		$this->applied_coupons = array();
		add_filter( 'woocommerce_coupon_message', '__return_empty_string', 10, 3 );

		// temporary disable 'woocommerce_applied_coupon' hook
		global $wp_filter;
		if ( isset( $wp_filter['woocommerce_applied_coupon'] ) ) {
			$stored_actions = $wp_filter['woocommerce_applied_coupon'];
			unset( $wp_filter['woocommerce_applied_coupon'] );
		} else {
			$stored_actions = array();
		}

		foreach ( array_keys( $this->external_coupons ) as $code ) {
			/**
			 * @var $coupon WC_Coupon
			 */
			$wc_cart->apply_coupon( $code );
		}

		// restore hook
		if ( ! empty( $stored_actions ) ) {
			$wp_filter['woocommerce_applied_coupon'] = $stored_actions;
		}

		foreach ( array_keys( $this->grouped_coupons ) as $coupon_name ) {
			$this->applied_coupons[] = $coupon_name;
			$wc_cart->apply_coupon( $coupon_name );
		}

		foreach ( array_keys( $this->single_coupons ) as $coupon_name ) {
			$this->applied_coupons[] = $coupon_name;
			$wc_cart->apply_coupon( $coupon_name );
		}
		remove_filter( 'woocommerce_coupon_message', '__return_empty_string', 10 );
	}


	/**
	 * Trigger an action to add custom fees.
	 *
	 * @param WC_Cart $wc_cart
	 */
	public function woocommerce_cart_calculate_coupons( $wc_cart ) {
		$applied_grouped_coupons = array();
		foreach ( $this->grouped_coupons as $coupon_name => $coupon_ids ) {
			if ( ! isset( $applied_grouped_coupons[ $coupon_name ] ) ) {
				$applied_grouped_coupons[ $coupon_name ] = array();
			}
			foreach ( $coupon_ids as $coupon_id ) {
				$coupon  = $this->coupons[ $coupon_id ];
				$rule_id = isset( $coupon['rule_id'] ) ? $coupon['rule_id'] : null;
				$amount  = $coupon['value'];

				if ( ! is_null( $rule_id ) ) {
					if ( ! isset( $applied_grouped_coupons[ $coupon_name ][ $rule_id ] ) ) {
						$applied_grouped_coupons[ $coupon_name ][ $rule_id ] = 0;
					}
					$applied_grouped_coupons[ $coupon_name ][ $rule_id ] += $amount;
				}
			}
		}

		$applied_single_coupons = array();
		foreach ( $this->single_coupons as $coupon_name => $coupon_id ) {
			$coupon  = $this->coupons[ $coupon_id ];
			$rule_id = isset( $coupon['rule_id'] ) ? $coupon['rule_id'] : null;

			if ( ! is_null( $rule_id ) ) {
				$applied_single_coupons[ $coupon_name ] = $rule_id;
			}
		}

		$applied_coupons = array(
			'grouped' => $applied_grouped_coupons,
			'single'  => $applied_single_coupons,
		);

		$totals                = $wc_cart->get_totals();
		$totals['wdp_coupons'] = $applied_coupons;
		$wc_cart->set_totals( $totals );
	}

	/**
	 * This filter allows custom coupon objects to be created on the fly.
	 *
	 * @param mixed $coupon
	 * @param mixed $data Coupon name
	 *
	 * @return array|mixed
	 */
	public function woocommerce_get_shop_coupon_data( $coupon, $data ) {
		if ( isset( $this->grouped_coupons[ $data ] ) ) {
			$grouped_coupon = array(
				'id'            => rand( 0, 1000 ),
				'discount_type' => 'fixed_cart',
				'amount'        => 0,
			);
			foreach ( $this->grouped_coupons[ $data ] as $coupon_id ) {
				if ( ! empty( $this->coupons[ $coupon_id ] ) ) {
					$grouped_coupon['amount'] += (float) $this->coupons[ $coupon_id ]['value'];
				}
			}
			if ( ! empty( $grouped_coupon['amount'] ) ) {
				$coupon = $grouped_coupon;
			}
		} elseif ( isset( $this->single_coupons[ $data ] ) ) {
			$coupon_id = $this->single_coupons[ $data ];

			if ( isset( $this->coupons[ $coupon_id ] ) ) {
				$coupon_data   = $this->coupons[ $coupon_id ];
				$coupon_type   = ( 'percentage' === $coupon_data['type'] ? 'percent' : 'fixed_cart' );
				$coupon_amount = (float) $coupon_data['value'];

				if ( ! empty( $coupon_amount ) ) {
					$coupon = array(
						'id'            => $coupon_id + 1,
						'discount_type' => $coupon_type,
						'amount'        => $coupon_amount,
					);
				}
			}
		}

		return $coupon;
	}

	/**
	 * Hide [Remove] link
	 *
	 * @param string    $coupon_html
	 * @param WC_Coupon $coupon
	 * @param string    $discount_amount_html
	 *
	 * @return string
	 */
	public function woocommerce_cart_totals_coupon_html( $coupon_html, $coupon, $discount_amount_html ) {
		if ( in_array( $coupon->get_code(), $this->applied_coupons ) ) {
			$coupon_html = $discount_amount_html;
		}

		return $coupon_html;
	}



	/** APPLY SHIPPING */

	/**
	 * @param WC_Shipping_Rate[] $rates
	 * @param array              $package
	 *
	 * @return WC_Shipping_Rate[]
	 */
	public function woocommerce_package_rates( $rates, $package ) {
		if ( is_null( $this->shipping_adjustments ) ) {
			return $rates;
		}

		if ( $this->shipping_adjustments->is_free() ) {
			foreach ( $rates as &$rate ) {
				$meta_data = $rate->get_meta_data();
				if ( isset( $meta_data['wdp_initial_cost'] ) ) {
					$cost = $meta_data['wdp_initial_cost'];
				} else {
					$cost = $rate->get_cost();
				}

				$rate->add_meta_data( 'wdp_initial_cost', $cost );
				$rate->add_meta_data( 'wdp_rule', $this->shipping_adjustments->get_rule_id_applied_free_shipping() );
				$rate->add_meta_data( 'wdp_amount', $cost );
				$rate->add_meta_data( 'wdp_free_shipping', true );
				$rate->set_cost( 0 );
				$rate->set_taxes( array() ); // no taxes
			}
		} else {
			$applied_shipping = array();
			foreach ( $rates as &$rate ) {
				$rate_id   = $rate->get_id();
				$meta_data = $rate->get_meta_data();
				if ( isset( $meta_data['wdp_initial_cost'] ) ) {
					$cost = $meta_data['wdp_initial_cost'];
				} else {
					$cost = $rate->get_cost();
				}
				foreach ( $this->shipping_adjustments->get_items() as $id => $item ) {
					$amount = 0;
					if ( 'amount' === $item['type'] ) {
						$amount = $item['value'];
					} elseif ( 'percentage' === $item['type'] ) {
						$amount = $cost * $item['value'] / 100;
					} elseif ( 'fixed' === $item['type'] ) {
						$amount = $cost - $item['value'];
					}

					if ( empty( $amount ) ) {
						continue;
					}
					if ( ! isset( $applied_shipping[ $rate_id ] ) || $amount > $applied_shipping[ $rate_id ]['amount'] ) {
						$applied_shipping[ $rate_id ] = array(
							'adjustment_id' => $id,
							'amount'        => $amount,
						);
					}
				}
				if ( ! empty( $applied_shipping[ $rate_id ] ) ) {
					$shipping = $this->shipping_adjustments->get_item( $applied_shipping[ $rate_id ]['adjustment_id'] );
					if ( is_null( $shipping ) ) {
						break;
					}
					$amount = $applied_shipping[ $rate_id ]['amount'];

					$rate->add_meta_data( 'wdp_initial_cost', $cost );
					$rate->add_meta_data( 'wdp_initial_tax', is_array( $rate->get_shipping_tax() ) ? array_sum( $rate->get_shipping_tax() ) : $rate->get_shipping_tax() );
					$rate->add_meta_data( 'wdp_rule', $shipping['rule_id'] );
					$rate->add_meta_data( 'wdp_amount', $amount );
					$rate->add_meta_data( 'wdp_free_shipping', false );
					$newcost = $cost - $amount;
					if ( $newcost < 0 ) {
						$newcost = 0;
					}
					$rate->set_cost( $newcost );

					// recalc taxes
					if ( $cost > 0 ) {
						$perc  = $newcost / $cost;
						$taxes = $rate->get_taxes();
						foreach ( $taxes as $k => $v ) {
							$taxes[ $k ] = $v * $perc;
						}
						$rate->set_taxes( $taxes );
					}
				}
			}//each not free shipping!
		}

		return $rates;
	}


	/**
	 * @param string           $label
	 * @param WC_Shipping_Rate $method
	 *
	 * @return mixed
	 */
	public function woocommerce_cart_shipping_method_full_label( $label, $method ) {
		if ( false !== strpos( $label, 'wdp-amount' ) ) {
			return $label;
		}

		$meta_data = $method->get_meta_data();
		if ( ! isset( $meta_data['wdp_initial_cost'] ) ) {
			return $label;
		}

		$initial_cost = $meta_data['wdp_initial_cost'];
		$initial_tax  = 0.0;

		if ( isset( $meta_data['wdp_initial_tax'] ) ) {
			$initial_tax = $meta_data['wdp_initial_tax'];
		}

		if ( WC()->cart->display_prices_including_tax() ) {
			$initial_cost_html = '<del>' . wc_price( $initial_cost + $initial_tax ) . '</del>';
		} else {
			$initial_cost_html = '<del>' . wc_price( $initial_cost ) . '</del>';
		}
		$initial_cost_html = preg_replace( '/\samount/is', 'wdp-amount', $initial_cost_html );

//		if ( $method->get_cost() > 0 ) {
		$label = preg_replace( '/(<span[^>]*>)/is', $initial_cost_html . ' $1', $label, 1 );
//		} else {
//			$label .= ': ' . $initial_cost_html . ' ' . wc_price( 0 );
//		}

		return $label;
	}

	public function get_context() {
		return $this->context;
	}

	public function get_external_coupons() {
		return $this->external_coupons;
	}
}