<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Product {
	/**
	 * @var WC_Product
	 */
	private $wc_product;

	/**
	 * @var WC_Product|null
	 */
	private $wc_product_parent;

	private $data = array(
		'initial_price'      => '',
		'calculated_price'   => '',
		'rule_applied'       => false,
		'min_price'          => '',
		'max_price'          => '',
		'adjustments_amount' => 0,
	);

	/**
	 * WDP_Product constructor.
	 * @throws Exception
	 *
	 * @param $the_product int|WC_Product
	 * @param $parent null|WC_Product
	 */
	public function __construct( $the_product, $parent = null ) {
		if ( $parent instanceof WC_Product ) {
			$this->wc_product_parent = $parent;
		}

		if ( is_numeric( $the_product ) ) {
			add_filter( 'woocommerce_product-variation_data_store', array( $this, 'apply_data_store' ), 10 );
			$this->wc_product = WC()->product_factory->get_product( $the_product );
			if ( ! $this->wc_product ) {
				throw new Exception( __( 'Product does not exists', 'advanced-dynamic-pricing-for-woocommerce' ) );
			}
			remove_filter( 'woocommerce_product-variation_data_store', array( $this, 'apply_data_store' ), 10 );
		} elseif ( $the_product instanceof WC_Product ) {
			$this->wc_product = $the_product;
		}

		$this->set_prop( 'initial_price', $this->wc_product->get_price('nofilter') );
	}

	public function apply_data_store() {
		$data_store = new WDP_Product_Variation_Data_Store_CPT();
		if ( ! is_null( $this->wc_product_parent ) ) {
			$data_store->add_parent( $this->wc_product_parent );
		}

		return $data_store;
	}

	public function get_id() {
		return $this->wc_product->get_id();
	}

	public function get_children() {
		return $this->wc_product->get_children();
	}

	public function get_price() {
		return (float) $this->get_prop( 'initial_price' );
	}

	public function get_new_price() {
		if ( '' !== $this->get_prop( 'calculated_price' ) ) {
			$price = (float) $this->get_prop( 'calculated_price' );
		} else {
			if ( $this->wc_product->is_on_sale( '' ) ) {
				$price = $this->wc_product->get_sale_price( '' );
			} else {
				$price = $this->get_price();
			}
		}

		return $price;
	}

	public function get_min_price() {
		return (float) $this->get_prop( 'min_price' );
	}

	public function get_max_price() {
		return (float) $this->get_prop( 'max_price' );
	}

	public function are_rules_applied() {
		return (boolean) $this->get_prop( 'rule_applied' );
	}

	public function get_adjustments_amount() {
		return (float) $this->get_prop( 'adjustments_amount' );
	}

	public function set_price( $value ) {
		$this->set_prop( 'initial_price', (float) $value );
	}

	public function set_new_price( $value ) {
		$this->set_prop( 'calculated_price', (float) $value );
	}

	public function set_min_price( $value ) {
		$this->set_prop( 'min_price', (float) $value );
	}

	public function set_max_price( $value ) {
		$this->set_prop( 'max_price', (float) $value );
	}

	public function rules_applied() {
		$this->set_prop( 'rule_applied', true );
	}

	public function is_on_wdp_sale() {
		return $this->get_new_price() < $this->get_price();
	}

	public function is_range_valid() {
		return $this->get_min_price() < $this->get_max_price() && $this->get_max_price() !== floatval( - 1 ) && $this->get_min_price() !== floatval( - 1 );
	}

	/**
	 * @return WC_Product
	 */
	public function get_wc_product() {
		return $this->wc_product;
	}

	public function is_variable() {
		return $this->wc_product->is_type( 'variable' );
	}

	public function is_price_defined() {
		return "" !== $this->wc_product->get_price();
	}

	public function get_price_suffix($price = '', $qty = 1){
		return $this->wc_product->get_price_suffix($price, $qty);
	}

	/**
	 * @param $context WDP_Cart_Context
	 */
	public function update_prices( $context ) {
		$new_price            = $this->get_new_price();
		$is_override_cents    = $context->get_option( 'is_override_cents' );
		$price_ends_with      = $context->get_option( 'prices_ends_with' );
		$price_mode           = $context->get_price_mode();
		$price_includes_tax   = $context->is_prices_includes_tax();
		$is_maximize_discount = 'compare_discounted_and_sale' === $price_mode;

		$initial_num_decimals = wc_get_price_decimals();
		$set_price_decimals = function ( $num_decimals ) use ( $initial_num_decimals ) {
			return $initial_num_decimals + 1;
		};

		if ( ! $context->get_option( 'is_calculate_based_on_wc_precision' ) ) {
			add_filter( 'wc_get_price_decimals', $set_price_decimals );
		}

		if ( $is_maximize_discount || $is_override_cents ) {
			if ( ! $price_includes_tax ) {
				$new_price = $this->get_price_including_tax( $this->wc_product, array( 'price' => $new_price ) );
			}
			if ( $is_maximize_discount ) {
				$new_price = $this->maximize_discount( $new_price );
			}
			if ( $is_override_cents ) {
				$new_price = $this->override_cents( $new_price, $price_ends_with );
			}
			if ( ! $price_includes_tax ) {
				$new_price = $this->get_price_excluding_tax( $this->wc_product, array( 'price' => $new_price ) );
			}
		}

		$initial_price = $this->get_price();

		$new_price     = apply_filters( 'wdp_update_new_price', $new_price, $this->wc_product );
		$initial_price = apply_filters( 'wdp_update_initial_price', $initial_price, $this->wc_product );

		if ( ! $context->get_option( 'is_calculate_based_on_wc_precision' ) ) {
			remove_filter( 'wc_get_price_decimals', $set_price_decimals );
		}

		$this->set_new_price( $new_price );
		$this->set_price( $initial_price );
		$this->set_prop( 'adjustments_amount', (float) $new_price - (float) $initial_price );
	}

	private function maximize_discount( $new_price ) {
		$wc_sale_price = $this->wc_product->is_on_sale( 'unfiltered' ) ? $this->wc_product->get_sale_price( 'unfiltered' ) : "";
		if ( "" !== $wc_sale_price ) {
			$wc_sale_price = $this->get_price_including_tax( $this->wc_product, array( 'price' => $wc_sale_price ) );
			if ( $wc_sale_price < $new_price ) {
				$new_price = $wc_sale_price;
			}
		}

		return $new_price;
	}

	/**
	 * @param  WC_Product $product WC_Product object.
	 * @param  array      $args Optional arguments to pass product quantity and price.
	 *
	 * @return float
	 */
	private function get_price_including_tax( $product, $args ) {
		return wc_get_price_including_tax( $product, $args );
	}

	/**
	 * @param  WC_Product $product WC_Product object.
	 * @param  array      $args Optional arguments to pass product quantity and price.
	 *
	 * @return float
	 */
	private function get_price_excluding_tax( $product, $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'qty'   => '',
				'price' => '',
			)
		);

		$price = '' !== $args['price'] ? max( 0.0, (float) $args['price'] ) : $product->get_price();
		$qty   = '' !== $args['qty'] ? max( 0.0, (float) $args['qty'] ) : 1;

		if ( '' === $price ) {
			return '';
		} elseif ( empty( $qty ) ) {
			return 0.0;
		}

		$line_price = $price * $qty;

		if ( $product->is_taxable() && ! wc_prices_include_tax() ) {
			$tax_rates = WC_Tax::get_rates( $product->get_tax_class() );
			$remove_taxes     = WC_Tax::calc_tax( $line_price, $tax_rates, true );
			$return_price   = $line_price - array_sum( $remove_taxes ); // Unrounded since we're dealing with tax inclusive prices. Matches logic in cart-totals class. @see adjust_non_base_location_price.
		} else {
			$return_price = $line_price;
		}

		return apply_filters( 'woocommerce_get_price_excluding_tax', $return_price, $qty, $product );
	}

	private function override_cents( $price, $prices_ends_with ){
		$price_fraction = $price - intval( $price );
		$new_price_fraction = $prices_ends_with / 100;

		$round_new_price_fraction = round($new_price_fraction);


		if ( 0 == intval( $price ) AND 0 < $new_price_fraction ){
			$price = $new_price_fraction;
			return $price;
		}


		if ( $round_new_price_fraction ) {

			if ( $price_fraction <= $new_price_fraction - round(1/2, 2) ){
				$price = intval( $price ) - 1 + $new_price_fraction;
			} else {
				$price = intval( $price ) + $new_price_fraction;
			}

		} else {

			if ( $price_fraction >= $new_price_fraction + round(1/2, 2) ){
				$price = intval( $price ) + 1 + $new_price_fraction;
			} else {
				$price = intval( $price ) + $new_price_fraction;
			}

		}

		return $price;
	}

	protected function get_prop( $prop ) {
		return array_key_exists( $prop, $this->data ) ? $this->data[ $prop ] : null;
	}

	protected function set_prop( $prop, $value ) {
		if ( array_key_exists( $prop, $this->data ) ) {
			$this->data[ $prop ] = $value;
		}
	}

	public function get_wc_price_html() {
		return WDP_Frontend::process_without_hooks( function () {
			return $this->wc_product->get_price_html();
		}, array( 'woocommerce_get_price_html' ) );
	}
}