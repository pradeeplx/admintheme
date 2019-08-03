<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Price_Display {
	private $options;

	/**
	 * @var WDP_Cart
	 */
	private $cart;

	/**
	 * @var WDP_Cart_Calculator
	 */
	private $calc;

	/**
	 * @var WDP_Product[]
	 */
	private $cached_products = array();

	/**
	 * WDP_Price_Display constructor.
	 *
	 */
	public function __construct() {
		$this->options = WDP_Helpers::get_settings();
	}

	public function is_enabled() {
		return ! ( ( is_admin() && ! wp_doing_ajax() ) || $this->is_request_to_rest_api() || defined( 'DOING_CRON' ) );
	}

	public function get_option( $option, $default = false ) {
		return isset( $this->options[ $option ] ) ? $this->options[ $option ] : $default;
	}

	public function init_hooks() {
		// for prices in catalog and single product mode
		add_filter( 'woocommerce_get_price_html', array( $this, 'hook_get_price_html' ), 10, 2 );
//		add_filter( 'woocommerce_variable_price_html', array( $this, 'hook_get_price_html' ), 100, 2 );

		if ( $this->get_option('show_onsale_badge') && ! $this->get_option('do_not_modify_price_at_product_page') ) {
			add_filter( 'woocommerce_product_is_on_sale', array( $this, 'hook_product_is_on_sale' ), 10, 2 );
			add_filter( 'woocommerce_product_get_sale_price', array( $this, 'hook_product_get_sale_price' ), 100, 2 );
			add_filter( 'woocommerce_product_get_regular_price', array( $this, 'hook_product_get_regular_price' ), 100, 2 );
		}

		do_action( 'wdp_price_display_init_hooks', $this );
	}

	/**
	 * Hook for create calculator and cart for frontend price calculation.
	 * We must do it as late as possible in wp_loaded hook for including (e.g.) items which added during POST.
	 */
	public function apply_cart_and_calc() {
		$this->apply_calc();
		$this->apply_cart();
	}

	public function apply_calc() {
		$rule_collection = WDP_Rules_Registry::get_instance()->get_active_rules();
		$this->calc      = new WDP_Cart_Calculator( $rule_collection );
	}

	public function apply_cart( $context = 'view' ) {
		if ( ! did_action( 'wp_loaded' ) ) {
			wc_doing_it_wrong( __FUNCTION__, __( 'Apply cart and calc should not be called before the wp_loaded action for including (e.g.) items which added during POST.', 'advanced-dynamic-pricing-for-woocommerce' ), '1.6.0' );
		}

		$cart_context = WDP_Frontend::make_wdp_cart_context_from_wc();
		$this->cart   = new WDP_Cart( $cart_context, WC()->cart );

		if ( 'view' === $context ) {
			$this->cart = apply_filters( 'wdp_apply_cart_to_price_display', $this->cart );
		}
	}

	public function apply_empty_cart( $context = 'view' ) {
		$cart_context = WDP_Frontend::make_wdp_cart_context_from_wc();
		$this->cart   = new WDP_Cart( $cart_context );
		if ( 'view' === $context ) {
			$this->cart = apply_filters( 'wdp_apply_empty_cart_to_price_display', $this->cart );
		}
	}

	/**
	 * @param $wdp_cart WDP_Cart
	 */
	public function attach_cart( $wdp_cart ) {
		$this->cart   = $wdp_cart;
	}

	/**
	 * @param $wdp_cart_calc WDP_Cart_Calculator
	 */
	public function attach_calc( $wdp_cart_calc ) {
		$this->calc   = $wdp_cart_calc;
	}

	/**
	 * @param $price_html string
	 * @param $product WC_Product
	 *
	 * @return string
	 */
	public function hook_get_price_html( $price_html, $product ) {
		if ( is_product() && $this->get_option( 'do_not_modify_price_at_product_page' ) ) {
			return $price_html;
		}

		$modify = apply_filters( 'wdp_modify_price_html', true, $price_html, $product, 1 );
		if ( ! $modify ) {
			return $price_html;
		}

		if ( is_a( $product, 'WC_Product' ) ) {
			$wdp_product = $this->process_product($product);

			if ( is_null( $wdp_product ) ) {
				return $price_html;
			}

			if ( ! $wdp_product->are_rules_applied() ) {
				return $wdp_product->get_wc_price_html();
			}

			if ( $wdp_product->is_variable() ) {
				if ( $wdp_product->is_range_valid() ) {
					$price_html = wc_format_price_range( $wdp_product->get_min_price(), $wdp_product->get_max_price() ) . $wdp_product->get_wc_product()->get_price_suffix();
				} elseif ( ( $wdp_product->get_min_price() === $wdp_product->get_max_price() ) && $wdp_product->is_on_wdp_sale() ) {
					$price_html = apply_filters( 'wdp_woocommerce_variable_discounted_price_html', wc_format_sale_price( wc_price( $wdp_product->get_price() ), wc_price( $wdp_product->get_min_price() ) ) . $wdp_product->get_wc_product()->get_price_suffix(), $wdp_product->get_price(), $wdp_product->get_min_price(), $product );
				}
			} else {
				if ( $wdp_product->is_on_wdp_sale() ) {
					$price_html = wc_format_sale_price( wc_price( $wdp_product->get_price() ), wc_price( $wdp_product->get_new_price() ) ) . $wdp_product->get_wc_product()->get_price_suffix();
				} else {
					$price_html = wc_price( $wdp_product->get_new_price() ) . $product->get_price_suffix();
				}
				$price_html = apply_filters( 'wdp_woocommerce_discounted_price_html', $price_html, $wdp_product->get_price(), $wdp_product->get_new_price(), $product );
			}
		}

		return $price_html;
	}

	/**
	 * @param $the_product WC_Product|int|WDP_Product
	 * @param $qty int
	 *
	 * @return WDP_Product|null
	 */
	public function process_product( $the_product, $qty = 1 ) {
		if ( is_null( $this->calc ) ) {
			global $wp;
			$logger = wc_get_logger(); // >Woocommerce>Status>Logs , file "log-2019-06-24-xxxx"
			$logger->error( sprintf( 'Calling null calc at %s', home_url( $wp->request ) ) );

			return null;
		}

		if ( ! $this->calc->at_least_one_rule_active() ) {
			return null;
		}

		if ( $the_product instanceof WDP_Product ) {
			$wdp_product = $the_product;
		} else {
			try {
				$wdp_product = new WDP_Product( $the_product );
			} catch ( Exception $e ) {
				return null;
			}

		}

		$hash = $wdp_product->get_id() . '_' . $qty;

		if ( ! isset( $this->cached_products[ $hash ] ) ) {
			$wdp_product                    = $this->calculate_product( $wdp_product, $qty );
			$this->cached_products[ $hash ] = $wdp_product;
		} else {
			$wdp_product = $this->cached_products[ $hash ];
		}

		return $wdp_product;
	}

	/**
	 * @param $product WDP_Product
	 * @param $qty int
	 *
	 * @return mixed|void
	 */
	public function get_product_price_html( $product, $qty ) {
		$product = $this->process_product( $product, $qty );
		if ( ! is_null( $product ) && $product->are_rules_applied() ) {
			if ( '' === $product->get_wc_product()->get_price() ) {
				$price_html = apply_filters( 'woocommerce_empty_price_html', '', $this );
			} elseif ( $product->is_on_wdp_sale() ) {
				$price_html = wc_format_sale_price( $product->get_price(), $product->get_new_price() ) . $product->get_price_suffix();
			} else {
				$price_html = wc_price( $product->get_price() ) . $product->get_price_suffix();
			}
		} else {
			remove_filter( 'woocommerce_get_price_html', array( $this, 'hook_get_price_html' ), 10 );
			$price_html = $product->get_wc_product()->get_price_html();
			add_filter( 'woocommerce_get_price_html', array( $this, 'hook_get_price_html' ), 10, 2 );
		}

		return $price_html;
	}

	/**
	 * @param $product WDP_Product
	 * @param $qty int
	 *
	 * @return null|WDP_Product
	 */
	private function calculate_product( &$product, $qty = 1 ) {
		if ( ! $this->cart ) {
			return null;
		}

		$cart = clone $this->cart;

		if ( $product->is_variable() && $product->get_children() ) {
			$min_price                 = - 1;
			$max_price                 = - 1;
			$min_price_initial         = - 1;
			$max_price_initial         = - 1;
			$at_least_one_rule_applied = false;
			foreach ( $product->get_children() as $child_id ) {
				try {
					$child = new WDP_Product( $child_id, $product->get_wc_product() );
				} catch ( Exception $e ) {
					continue;
				}

				if ( ! $child->is_price_defined() ) {
					continue;
				}

				$child = $this->process_product( $child, $qty );
				if ( is_null( $child ) ) {
					return null;
				}

				if ( $child->are_rules_applied() ) {
					$at_least_one_rule_applied = true;
				}

				$min_price = $min_price > - 1 ? min( $min_price, $child->get_new_price() ) : $child->get_new_price();
				$max_price = $max_price > - 1 ? max( $max_price, $child->get_new_price() ) : $child->get_new_price();

				$min_price_initial = $min_price_initial > - 1 ? min( $min_price_initial, $child->get_price() ) : $child->get_price();
				$max_price_initial = $max_price_initial > - 1 ? max( $max_price_initial, $child->get_price() ) : $child->get_price();
			}

			$product->set_min_price( $min_price );
			$product->set_max_price( $max_price );
			if ( $at_least_one_rule_applied ) {
				$product->rules_applied();
			}
			if ( $min_price === $max_price ) {
				$product->set_new_price( $min_price );
			}
			if ( $min_price_initial === $max_price_initial ) {
				$product->set_price( $min_price_initial );
			}

		} elseif ( ! $product->is_variable() ) {
			$cart->add_product_to_calculate( $product->get_wc_product(), $qty );
			$new_cart = $this->calc->process_cart_new( $cart );
			if ( $new_cart ) {
				$product  = $this->calc->apply_changes_to_product( $new_cart, $product, $qty );
				$product->update_prices( $new_cart->get_context() );
				$product = $this->prepare_product_to_display( $product, $new_cart->get_context() );
			} else {
				$product = $this->prepare_product_to_display( $product, $cart->get_context() );
			}
		}

		return $product;
	}

	/**
	 * @param $product WDP_Product
	 * @param $context WDP_Cart_Context
	 *
	 * @return WDP_Product
	 */
	public function prepare_product_to_display($product, $context) {
		$initial_num_decimals = wc_get_price_decimals();
		$set_price_decimals = function ( $num_decimals ) use ( $initial_num_decimals ) {
			return $initial_num_decimals + 1;
		};
		if ( ! $context->get_option( 'is_calculate_based_on_wc_precision' ) ) {
			add_filter( 'wc_get_price_decimals', $set_price_decimals );
		}

		$product->set_price( $this->get_price_to_display( $product->get_wc_product(), array( 'price' => $product->get_price() ) ) );
		$product->set_new_price( $this->get_price_to_display( $product->get_wc_product(), array( 'price' => $product->get_new_price() ) ) );

		if ( ! $context->get_option( 'is_calculate_based_on_wc_precision' ) ) {
			remove_filter( 'wc_get_price_decimals', $set_price_decimals );
		}

		return $product;
	}

	/**
	 * @param  WC_Product $product WC_Product object.
	 * @param  array      $args Optional arguments to pass product quantity and price.
	 *
	 * @return float
	 */
	private function get_price_to_display( $product, $args ) {
		return wc_get_price_to_display( $product, $args );
	}

	/**
	 * @param $on_sale boolean
	 * @param $product WC_Product
	 *
	 * @return boolean
	 */
	public function hook_product_is_on_sale( $on_sale, $product ) {
		$wdp_product = $this->process_product( $product );
		if ( is_null( $wdp_product ) ) {
			return $on_sale;
		}

		return $on_sale || $wdp_product->are_rules_applied();
	}

	/**
	 * @param $value string
	 * @param $product WC_Product
	 *
	 * @return string|float
	 */
	public function hook_product_get_sale_price( $value, $product ) {
		$wdp_product = $this->process_product( $product );
		if ( is_null( $wdp_product ) ) {
			return $value;
		}

		return $wdp_product->are_rules_applied() ? $wdp_product->get_new_price() : $value;
	}

	public function hook_product_get_regular_price( $value, $product ) {
		$wdp_product = $this->process_product( $product );
		if ( is_null( $wdp_product ) ) {
			return $value;
		}

		return $wdp_product->are_rules_applied() ? $wdp_product->get_price() : $value;
	}

	private function is_request_to_rest_api() {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$rest_prefix = trailingslashit( rest_get_url_prefix() );

		// Check if our endpoint.
		$woocommerce = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix . 'wc/' ) ); // @codingStandardsIgnoreLine

		// Allow third party plugins use our authentication methods.
		$third_party = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix . 'wc-' ) ); // @codingStandardsIgnoreLine

		return apply_filters( 'woocommerce_rest_is_request_to_rest_api', $woocommerce || $third_party );
	}

	/**
	 * @param WC_Product $product
	 * @param array      $args
	 *
	 * @return string
	 */
	public function get_cart_item_price_to_display( $product, $args = array() ) {
		$args = wp_parse_args( $args, array(
			'qty'   => 1,
			'price' => $product->get_price(),
		) );

		$price = $args['price'];
		$qty   = $args['qty'];

		$context = $this->cart->get_context();

		$initial_num_decimals = wc_get_price_decimals();
		$set_price_decimals = function ( $num_decimals ) use ( $initial_num_decimals ) {
			return $initial_num_decimals + 1;
		};

		if ( ! $context->get_option( 'is_calculate_based_on_wc_precision' ) ) {
			add_filter( 'wc_get_price_decimals', $set_price_decimals );
		}

		if ( 'incl' === get_option( 'woocommerce_tax_display_cart' ) ) {
			$new_price = wc_get_price_including_tax( $product, array( 'qty' => $qty, 'price' => $price ) );
		} else {
			$new_price = wc_get_price_excluding_tax( $product, array( 'qty' => $qty, 'price' => $price ) );
		}

		if ( ! $context->get_option( 'is_calculate_based_on_wc_precision' ) ) {
			remove_filter( 'wc_get_price_decimals', $set_price_decimals );
		}

		return $new_price;
	}

}