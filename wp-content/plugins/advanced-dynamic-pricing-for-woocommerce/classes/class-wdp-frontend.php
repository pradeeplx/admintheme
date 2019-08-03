<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Frontend {
	private $options;
	private $price_display;

	public function __construct() {
		//TODO: check if need load our scripts
		WDP_Loader::load_core();

		$options = WDP_Helpers::get_settings();
		$this->options = $options;

		add_action( 'wp_print_styles', array( $this, 'load_frontend_assets' ) );

		$this->price_display = new WDP_Price_Display();
		if ( $this->price_display->is_enabled() ) {
			$this->price_display->init_hooks();
			add_action( 'wp_loaded', array( $this, 'wp_loaded_process_cart' ), PHP_INT_MAX );
		}

		if ( $options['show_matched_bulk_table'] ) {
			$product_bulk_table_action = isset( $options['product_bulk_table_action'] ) ? $options['product_bulk_table_action'] : "";
			add_action( 'wp_loaded', function () use ( $product_bulk_table_action ) {
				$product_bulk_table_actions = (array) apply_filters( 'wdp_product_bulk_table_action', $product_bulk_table_action );

				if ( is_array( $product_bulk_table_actions ) && $product_bulk_table_actions ) {
					foreach ( $product_bulk_table_actions as $action ) {
						add_action( $action, array( $this, 'print_table_with_product_bulk_rules' ), 50, 2 );
					}
				}
			} );
		}

		add_action( 'woocommerce_checkout_order_processed', array( $this, 'checkout_order_processed' ), 10, 3 );

		add_action( 'woocommerce_checkout_update_order_review', array( $this, 'woocommerce_checkout_update_order_review' ), 100 );

		// strike prices for items
		if ( $options['show_striked_prices'] ) {
			add_filter( 'woocommerce_cart_item_price', array( $this, 'woocommerce_cart_item_price_and_price_subtotal' ), 10, 3 );
			add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'woocommerce_cart_item_price_and_price_subtotal' ), 10, 3 );
		}

		if ( $options['show_category_bulk_table'] ) {
			$category_bulk_table_action = isset( $options['category_bulk_table_action'] ) ? $options['category_bulk_table_action'] : "";

			add_action( 'wp_loaded', function () use ( $category_bulk_table_action ) {
				$category_bulk_table_action = apply_filters( 'wdp_category_bulk_table_action',
					$category_bulk_table_action );
				if ( $category_bulk_table_action ) {
					add_action( $category_bulk_table_action, array( $this, 'print_table_with_category_bulk_rules' ), 50,
						2 );
				}
			} );
		}

		if ( $options['is_show_amount_saved_in_mini_cart'] ) add_action( 'woocommerce_mini_cart_contents', array( $this, 'output_amount_save' ) );
		if ( $options['is_show_amount_saved_in_cart'] ) add_action( 'woocommerce_cart_totals_before_order_total', array( $this, 'output_amount_save' ) );
		if ( $options['is_show_amount_saved_in_checkout_cart'] ) add_action( 'woocommerce_review_order_after_cart_contents', array( $this, 'output_amount_save' ) );

		//SHORTCODES
		add_shortcode( 'adp_product_bulk_rules_table', function () {
			ob_start();
			$this->print_table_with_product_bulk_rules();
			$content = ob_get_clean();

			return $content;
		} );

		add_shortcode( 'adp_category_bulk_rules_table', function () {
			ob_start();
			$this->print_table_with_category_bulk_rules();
			$content = ob_get_clean();

			return $content;
		} );

		if ( $options['support_shortcode_products_on_sale'] ) {
		    WDP_Shortcode_Products_On_Sale::register();
		}

		// hooking nopriv ajax methods
		foreach ( self::get_nopriv_ajax_actions() as $ajax_action_name ) {
			add_action( "wp_ajax_nopriv_{$ajax_action_name}", array( $this, "ajax_{$ajax_action_name}" ) );
			add_action( "wp_ajax_{$ajax_action_name}", array( $this, "ajax_{$ajax_action_name}" ) );
		}

		if ( $options['suppress_other_pricing_plugins'] AND !is_admin() ) {
			add_action( "wp_loaded", array( $this, 'remove_hooks_set_by_other_plugins' ) );
		}

		add_filter( 'woocommerce_add_to_cart_sold_individually_found_in_cart', array($this, 'woocommerce_add_to_cart_sold_individually_found_in_cart'), 10 ,5 );


		/** Additional css class for free item line */
		add_filter( 'woocommerce_cart_item_class', function ( $str_classes, $cart_item, $cart_item_key ) {
			$classes = explode( ' ', $str_classes );
			if ( ! empty( $cart_item['wdp_gifted'] ) ) {
				$classes[] = 'wdp_free_product';
			}

			if ( ! empty( $cart_item['wdp_original_price'] ) && (float) $cart_item['data']->get_price() == 0 ) {
				$classes[] = 'wdp_zero_cost_product';
			}

			return implode( ' ', $classes );
		}, 10, 3 );

		/** PHONE ORDER HOOKS START */
		add_action( 'wdp_force_process_wc_cart', function ( $wc_cart ) {
			WDP_Functions::process_cart_manually();
		} );

		if ( ! $options['allow_to_edit_prices_in_po'] ) {
			add_filter( 'wpo_set_original_price_after_calculation', function ( $price, $cart_item ) {
				return ! empty( $cart_item["wdp_original_price"] ) ? $cart_item["wdp_original_price"] : false;
			}, 10, 2 );
			add_filter( 'wpo_cart_item_is_price_readonly', '__return_true', 10, 1 );
		} else {
			add_filter( 'wpo_prepare_item', function ( $item, $product ) {
				/**
				 * @var $product WC_Product
				 */
				if ( empty( $item['cost_updated_manually'] ) && ( $item['item_cost'] != $product->get_price() ) ) {
					$item['item_cost'] = $product->get_price();
				}

				return $item;
			}, 10, 2 );


			add_filter( 'wdp_prepare_cart_item', function ( $cart_item, $wc_cart_item ) {
				/**
				 * @var $product WC_Product
				 */
				if ( ! empty( $wc_cart_item['cost_updated_manually'] ) ) {
					$cart_item->make_immutable();
				}

				return $cart_item;
			}, 10, 2 );

			add_filter( 'wpo_update_cart_cart_item_meta', function ( $cart_item_meta, $item ) {
				if ( ! empty( $item['cost_updated_manually'] ) ) {
					$cart_item_meta['immutable'] = true;
				}

				return $cart_item_meta;
			}, 10, 2 );

			add_filter( 'wdp_cart_item_before_insert', function ( $item, $wc_cart_item ) {
				/**
				 * @var $item WDP_Cart_Item
				 */
				if ( ! empty( $wc_cart_item['immutable'] ) ) {
					$item->make_immutable();
				}

				return $item;
			}, 10, 2 );

			add_filter( 'wpo_cart_item_is_price_readonly', '__return_false', 10, 1 );
		}

		add_filter( 'wpo_must_switch_cart_user', '__return_true', 10, 1 );

		add_filter( 'wpo_skip_add_to_cart_item', function ( $skip, $item ) {
			return ! empty( $item['wdp_gifted'] ) ? (boolean) $item['wdp_gifted'] : $skip;
		}, 10, 2 );
		/** PHONE ORDER HOOKS FINISH */
	}

	public static function is_catalog_view() {
		return is_product_tag() || is_product_category() || is_shop();
	}

	public function ajax_get_table_with_product_bulk_table() {
		$product_id = ! empty( $_REQUEST['product_id'] ) ? $_REQUEST['product_id'] : false;
		if ( ! $product_id ) {
			wp_send_json_error();
		}

		wp_send_json_success( $this->make_table_with_product_bulk_table( $product_id ) );
	}

	public function ajax_get_price_product_with_bulk_table() {
		$product_id = ! empty( $_REQUEST['product_id'] ) ? $_REQUEST['product_id'] : false;
		$qty        = ! empty( $_REQUEST['qty'] ) ? (int) $_REQUEST['qty'] : false;
		if ( ! $product_id || ! $qty ) {
			wp_send_json_error();
		}
		wp_send_json_success( array( 'price_html' => $this->get_price_html_product_with_bulk_table( $product_id, $qty ) ) );
	}

	private function get_price_html_product_with_bulk_table( $product_id, $qty ) {
		try {
			$wdp_product = new WDP_Product( $product_id );
		} catch ( Exception $e ) {
			return "";
		}

		return $this->price_display->get_product_price_html( $wdp_product, $qty );
	}

	public function print_table_with_product_bulk_rules() {
		/**
		 * @var $product WC_Product
		 */
		global $product;
		if ( ! empty( $product ) ) {
			$available_products_ids   = array();
			$available_products_ids[] = $product->get_id();
			$available_products_ids   = array_merge( $available_products_ids, $product->get_children() );

			echo '<span class="wdp_bulk_table_content" data-available-ids="' . json_encode( $available_products_ids ) . '">';
			echo $this->make_table_with_product_bulk_table( $product->get_id() );
			echo '</span>';
		}
	}

	/**
	 * @param $product_id integer
	 *
	 * @return string
	 */
	private function make_table_with_product_bulk_table( $product_id ) {
		$calc = self::make_wdp_calc_from_wc();
		$cart = self::make_wdp_cart_from_wc();

		$matched_rules = $calc->find_product_matches( $cart, $product_id );

		$bulk_rules = $matched_rules->with_bulk();
		if ( $bulk_rules->is_empty() ) {
			return "";
		}

		$rule = $bulk_rules->get_first();
		$data = $rule->get_bulk_details($cart->get_context());

		$data['discount'] = str_replace( "set_", "", $data['discount'] );

		$options    = WDP_Helpers::get_settings();
		$price_mode = $options['discount_for_onsale'];
		$product    = wc_get_product( $product_id );
		if ( ! $product ) {
			return "";
		}

		if ( $product->is_type( 'variable' ) ) {
			foreach ( $data['ranges'] as &$line ) {
				if ( 'price__fixed' === $data['discount'] ) {
					$line['value'] = wc_get_price_to_display( $product, array( 'price' => $line['value'] ) );
				}
				unset( $line['discounted_price'] );
			}
		} else {
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

			$price = apply_filters( "wdp_get_product_price", (float) $price, $product, $price_mode, array() );

			foreach ( $data['ranges'] as &$line ) {
				if ( 'price__fixed' === $data['discount'] ) {
					$line['discounted_price'] = (float) $price - (float) $line['value'];
					$line['value']            = wc_get_price_to_display( $product, array( 'price' => $line['value'] ) );
				} elseif ( 'discount__amount' === $data['discount'] ) {
					$line['discounted_price'] = (float) $price - (float) $line['value'];
				} elseif ( 'discount__percentage' === $data['discount'] ) {
					$line['discounted_price'] = (float) $price - (float) $price * (float) $line['value'] / 100;
				}
				$line['discounted_price'] = wc_price( wc_get_price_to_display( $product, array( 'price' => $line['discounted_price'] ) ) ) /*. $product->get_price_suffix()*/;
			}
		}

		if ( empty( $data['table_message'] ) ) {
			$dependencies = $rule->get_product_dependencies();
			foreach ( $dependencies as &$dependency ) {
				foreach ( $dependency['values'] as $id ) {
					$dependency['titles'][] = WDP_Helpers::get_title_by_type($id, $dependency['type']);
					$dependency['links'][]  = WDP_Helpers::get_permalink_by_type($id, $dependency['type']);
				}
			}
			unset( $dependency );
		}

		$args = apply_filters( 'wdp_args_before_product_bulk_table_template', array(
			'data'         => $data,
			'dependencies' => isset( $dependencies ) ? $dependencies : array(),
			'table_type'   => 'product',
		) );

		$content = $this->wdp_get_template( 'bulk-table.php', $args );

		return $content;
	}

	public function print_table_with_category_bulk_rules() {
		if ( is_tax() ) {
			global $wp_query;


			if ( isset( $wp_query->queried_object->term_id ) ) {
				$term_id = $wp_query->queried_object->term_id;
			} else {
				return false;
			}

			$active_rules = WDP_Rules_Registry::get_instance()->get_active_rules()->with_bulk()->to_array();

			foreach ( $active_rules as $index => $rule ) {
				$data = $rule->get_bulk_details( self::make_wdp_cart_context_from_wc() );

				$dependencies = $rule->get_product_dependencies();

				$delete = true;
				foreach ( $dependencies as &$dependency ) {
					foreach ( $dependency['values'] as $id ) {
						$dependency['titles'][] = WDP_Helpers::get_title_by_type($id, $dependency['type']);
						$dependency['links'][]  = WDP_Helpers::get_permalink_by_type($id, $dependency['type']);
						if ( 'product_categories' === $dependency['type'] AND $term_id == $id) {
								$delete = false;
						}
					}
				}

				if ( $delete ) {
					unset( $active_rules[ $index ] );
					continue;
				}


				$content = $this->wdp_get_template(
					'bulk-table.php',
					array(
						'data'         => $data,
						'dependencies' => isset( $dependencies ) ? $dependencies : null,
						'table_type'   => 'category',
					)
				);

				echo  '<span class="wdp_bulk_table_content">' . $content . '</span>';
				break;

			}
		}
	}

	/**
	 * @param int $product_id
	 *
	 * @return WDP_Rules_Collection
	 */
	public function get_matched_offers( $product_id ) {
		$calc    = self::make_wdp_calc_from_wc();
// 		$context = $this->make_wdp_cart_context_from_wc();
// 		$coupons = $this->get_wc_cart_coupons();


// 		$cart = new WDP_Cart( array(), $coupons, $context );
		$cart = self::make_wdp_cart_from_wc();

		$rules = $calc->find_product_matches( $cart, $product_id );

		return $rules;
	}

	/**
	 * Change cart item display price
	 *
	 * @access public
	 *
	 * @param string $price_html
	 * @param array  $cart_item
	 * @param string $cart_item_key
	 *
	 * @return string
	 */
	public function cart_item_price( $price_html, $cart_item, $cart_item_key ) {

		if ( isset( $cart_item['wdp_data']['initial_price'] ) ) {

			/** @var WC_Product $product */
			$product = $cart_item['data'];

			$intial_price    = $cart_item['wdp_data']['initial_price'];
			$processed_price = $product->get_price();

			if ( $intial_price != $processed_price ) {
				$price_html = '<del>' . wc_price( $intial_price ) . '</del>';
				$price_html .= '<ins>' . wc_price( $processed_price ) . '</ins>';
			}
		}

		return $price_html;
	}

	public function wp_loaded_process_cart() {
		if ( ! empty( $_GET['wc-ajax'] ) ) {
			if ( $_GET['wc-ajax'] === "update_order_review" ) {
				return;
			} else if ( $_GET['wc-ajax'] === "checkout" ) {
				add_action( 'woocommerce_checkout_process', array( $this, 'process_cart' ), PHP_INT_MAX );
				return;
			} else {
				/**
				 *  Move cart processing after all actions with WC cart ended
				 */
				add_action( 'woocommerce_before_mini_cart', array( $this, 'process_cart' ), PHP_INT_MAX );
				return;
			}
		}

		$this->process_cart();
	}

	public function woocommerce_checkout_update_order_review() {
		add_action( 'woocommerce_before_data_object_save', array( $this, 'process_cart' ), 100 );
	}

	public function process_cart() {
		remove_action( 'woocommerce_before_data_object_save', array( $this, 'process_cart' ), 100 );

		$wc_customer = WC()->cart->get_customer();
		$wc_session = WC()->session;

		// store tax exempt value
		if ( ! isset( $wc_session->wdp_old_tax_exempt ) ) {
			$wc_session->set( 'wdp_old_tax_exempt', $wc_customer->get_is_vat_exempt() );
		} else {
			$wc_customer->set_is_vat_exempt( $wc_session->wdp_old_tax_exempt );
		}

		$calc = self::make_wdp_calc_from_wc();
		$cart = self::make_wdp_cart_from_wc();

		$this->price_display->attach_calc( $calc );

		$newcart = $calc->process_cart_new( $cart );
		if( $newcart ) {
			$newcart->apply_to_wc_cart();
		} else {
			unset( $wc_session->wdp_old_tax_exempt );

			//try delete gifted products ?
			$wc_cart_items = WC()->cart->get_cart();
			$store_keys = apply_filters( 'wdp_save_cart_item_keys', array() );

			foreach ( $wc_cart_items as $wc_cart_item_key => $wc_cart_item ) {
				$changed = false;

				if ( isset( $wc_cart_item['wdp_gifted'] ) ) {
					$wdp_gifted = $wc_cart_item['wdp_gifted'];
					unset( $wc_cart_item['wdp_gifted'] );
					$changed = true;
					if ( $wdp_gifted ) {
						WC()->cart->remove_cart_item( $wc_cart_item_key );
						continue;
					}
				}

				if ( isset( $wc_cart_item['wdp_original_price'] ) ) {
					unset( $wc_cart_item['wdp_original_price'] );
					$changed = true;
				}

				if ( isset( $wc_cart_item['wdp_history'] ) ) {
					unset( $wc_cart_item['wdp_history'] );
					$changed = true;
				}

				if ( isset( $wc_cart_item['wdp_rules'] ) ) {
					unset( $wc_cart_item['wdp_rules'] );
					$changed = true;
				}

				if ( isset( $wc_cart_item['rules'] ) ) {
					unset( $wc_cart_item['rules'] );
					$changed = true;
				}

				if ( isset( $wc_cart_item['wdp_rules_for_singular'] ) ) {
					unset( $wc_cart_item['wdp_rules_for_singular'] );
					$changed = true;
				}

				$product_id   = $wc_cart_item['product_id'];
				$qty          = $wc_cart_item['quantity'];
				$variation_id = $wc_cart_item['variation_id'];
				$variation    = $wc_cart_item['variation'];

				$cart_item_data = array();
				foreach ( $store_keys as $key ) {
					if ( isset( $wc_cart_item[ $key ] ) ) {
						$cart_item_data[ $key ] = $wc_cart_item[ $key ];
					}
				}

				if ( $changed ) {
					WC()->cart->remove_cart_item( $wc_cart_item_key );

					$exclude_hooks = apply_filters('wdp_exclude_hooks_when_add_to_cart_after_disable_pricing', array(), $wc_cart_item);
					self::process_without_hooks( function () use ( $product_id, $qty, $variation_id, $variation, $cart_item_data ) {
						WC()->cart->add_to_cart( $product_id, $qty, $variation_id, $variation, $cart_item_data );
					}, $exclude_hooks );
				}
			}

			// clear shipping in session for triggering full calculate_shipping to replace 'wdp_free_shipping' when needed
			foreach ( WC()->session->get_session_data() as $key => $value ) {
				if ( preg_match( '/(shipping_for_package_).*/', $key, $matches ) === 1 ) {
					if ( ! isset( $matches[0] ) ) {
						continue;
					}
					$stored_rates = WC()->session->get( $matches[0] );

					if ( ! isset( $stored_rates['rates'] ) ) {
						continue;
					}
					if ( is_array( $stored_rates['rates'] ) ) {
						foreach ( $stored_rates['rates'] as $rate ) {
							if ( isset( $rate->get_meta_data()['wdp_free_shipping'] ) ) {
								unset( WC()->session->$key );
								break;
							}
						}
					}
				}
			}
		}// if no rules


		$this->price_display->apply_cart();
	}

	/**
	 * @return WDP_Cart_Calculator
	 */
	public static function make_wdp_calc_from_wc() {
		$rule_collection = WDP_Rules_Registry::get_instance()->get_active_rules();
		$calc            = new WDP_Cart_Calculator( $rule_collection );

		return $calc;
	}

	/**
	 * @param bool $use_empty_cart
	 *
	 * @return WDP_Cart
	 */
	public static function make_wdp_cart_from_wc( $use_empty_cart = false ) {
		$context = self::make_wdp_cart_context_from_wc();

		if ( $use_empty_cart ) {
			$cart    = new WDP_Cart( $context );
		} else {
			$cart    = new WDP_Cart( $context, WC()->cart );
		}

		return $cart;
	}

	/**
	 * @return array
	 */
	private function get_wc_cart_coupons() {
		$external_coupons = array();
		foreach ( WC()->cart->get_coupons() as $coupon ) {
			/**
			 * @var $coupon WC_Coupon
			 */
			if ( $coupon->get_id() ) {
				$external_coupons[] = $coupon->get_code();
			}
		}

		return $external_coupons;
	}

	/**
	 * @return WDP_Cart_Context
	 */
	public static function make_wdp_cart_context_from_wc() {
		//test code
		$environment = array(
			'timestamp' => current_time( 'timestamp' ),
			'prices_includes_tax' => wc_prices_include_tax(),
		);

		$settings = WDP_Helpers::get_settings();

		if ( ! is_null( WC()->customer ) ) {
			$customer = new WDP_User_Impl( new WP_User( WC()->customer->get_id() ) );
			$customer->set_shipping_country( WC()->customer->get_shipping_country( '' ) );
			$customer->set_shipping_state( WC()->customer->get_shipping_state( '' ) );
			$customer->set_is_vat_exempt( WC()->customer->get_is_vat_exempt() );
		} else {
			$customer = new WDP_User_Impl( new WP_User() );
		}

		if ( ! is_null( WC()->session ) ) {
			if ( is_checkout() ) $customer->set_payment_method( WC()->session->get('chosen_payment_method') );
			if ( is_checkout() OR !self::is_catalog_view() ) $customer->set_shipping_methods( WC()->session->get('chosen_shipping_methods') );
		}
		$context = new WDP_Cart_Context( $customer, $environment, $settings );

		return $context;
	}


	public function checkout_order_processed( $order_id, $posted_data, $order ) {
		list( $order_stats, $product_stats ) = $this->collect_wc_cart_stats( WC() );

		$order_date = current_time( 'mysql' );

		foreach ( $order_stats as $rule_id => $stats_item ) {
			$stats_item = array_merge(
				array(
					'order_id'         => $order_id,
					'rule_id'          => $rule_id,
					'amount'           => 0,
					'extra'            => 0,
					'shipping'         => 0,
					'is_free_shipping' => 0,
					'gifted_amount'    => 0,
					'gifted_qty'       => 0,
					'date'             => $order_date,
				),
				$stats_item
			);
			WDP_Database::add_order_stats( $stats_item );
		}

		foreach ( $product_stats as $product_id => $by_rule ) {
			foreach ( $by_rule as $rule_id => $stats_item ) {
				$stats_item = array_merge( array(
					'order_id'      => $order_id,
					'product_id'    => $product_id,
					'rule_id'       => $rule_id,
					'qty'           => 0,
					'amount'        => 0,
					'gifted_amount' => 0,
					'gifted_qty'    => 0,
					'date'          => $order_date,
				), $stats_item );

				WDP_Database::add_product_stats( $stats_item );
			}
		}
	}

	/**
	 * @param WooCommerce $wc
	 *
	 * @return array
	 */
	private function collect_wc_cart_stats( $wc ) {
		$order_stats   = array();
		$product_stats = array();

		$wc_cart = $wc->cart;

		$cart_items = $wc_cart->get_cart();
		foreach ( $cart_items as $cart_item ) {
			$rules = isset( $cart_item['wdp_rules'] ) ? $cart_item['wdp_rules'] : '';

			if ( empty( $rules ) ) {
				continue;
			}

			$product_id = $cart_item['product_id'];
			foreach ( $rules as $rule_id => $amount ) {
				//add stat rows 
				if( !isset( $order_stats[ $rule_id ] ) ) {
					$order_stats[ $rule_id ] = array( 'amount'=>0, 'qty'=>0, 'gifted_qty'=>0, 'gifted_amount'=>0, 'shipping'=>0, 'is_free_shipping'=>0, 'extra'=>0 );
				}
				if( !isset( $product_stats[ $product_id ][ $rule_id ] ) ) {
					$product_stats[ $product_id ][ $rule_id ] = array( 'amount'=>0, 'qty'=>0, 'gifted_qty'=>0, 'gifted_amount'=>0 );
				}

				$prefix =   !empty( $cart_item['wdp_gifted'] ) ? 'gifted_' : "";
				// order 
				$order_stats[ $rule_id ][$prefix . 'qty'] += $cart_item['quantity'];
				$order_stats[ $rule_id ][$prefix . 'amount'] += $amount;
				// product
				$product_stats[ $product_id ][ $rule_id ][$prefix . 'qty']    += $cart_item['quantity'];
				$product_stats[ $product_id ][ $rule_id ][$prefix . 'amount'] += $amount;
			}
		}

		$this->inject_wc_cart_coupon_stats( $wc_cart, $order_stats );
		$this->inject_wc_cart_fee_stats( $wc_cart, $order_stats );
		$this->inject_wc_cart_shipping_stats( $wc, $order_stats );

		return array( $order_stats, $product_stats );
	}

	/**
	 * @param WC_Cart $wc_cart
	 * @param array   $order_stats
	 */
	private function inject_wc_cart_coupon_stats( $wc_cart, &$order_stats ) {
		$totals      = $wc_cart->get_totals();
		$wdp_coupons = isset( $totals['wdp_coupons'] ) ? $totals['wdp_coupons'] : array();
		if ( empty( $wdp_coupons ) ) {
			return;
		}

		foreach ( $wc_cart->get_coupon_discount_totals() as $coupon_code => $amount ) {
			if ( isset( $wdp_coupons['grouped'][ $coupon_code ] ) ) {
				foreach ( $wdp_coupons['grouped'][ $coupon_code ] as $rule_id => $amount_per_rule ) {
					if ( ! isset( $order_stats[ $rule_id ] ) ) {
						$order_stats[ $rule_id ] = array();
					}

					if ( ! isset( $order_stats[ $rule_id ]['extra'] ) ) {
						$order_stats[ $rule_id ]['extra'] = 0.0;
					}

					$order_stats[ $rule_id ]['extra'] += $amount_per_rule;
				}
			} elseif ( isset( $wdp_coupons['single'][ $coupon_code ] ) ) {
				$rule_id = $wdp_coupons['single'][ $coupon_code ];
				if ( ! isset( $order_stats[ $rule_id ] ) ) {
					$order_stats[ $rule_id ] = array();
				}

				if ( ! isset( $order_stats[ $rule_id ]['extra'] ) ) {
					$order_stats[ $rule_id ]['extra'] = 0.0;
				}

				$order_stats[ $rule_id ]['extra'] += $amount;
			}
		}
	}

	/**
	 * @param WC_Cart $wc_cart
	 * @param array   $order_stats
	 */
	private function inject_wc_cart_fee_stats( $wc_cart, &$order_stats ) {
		$totals   = $wc_cart->get_totals();
		$wdp_fees = isset( $totals['wdp_fees'] ) ? $totals['wdp_fees'] : '';
		if ( empty( $wdp_fees ) ) {
			return;
		}

		foreach ( $wc_cart->get_fees() as $fee ) {
			$fee_name = $fee->name;
			if ( isset( $wdp_fees[ $fee_name ] ) ) {
				foreach ( $wdp_fees[ $fee_name ] as $rule_id => $fee_amount_per_rule ) {
					$order_stats[ $rule_id ]['extra'] -= $fee_amount_per_rule;
				}
			}
		}
	}

	/**
	 * @param WooCommerce $wc
	 * @param array       $order_stats
	 */
	private function inject_wc_cart_shipping_stats( $wc, &$order_stats ) {
		$shippings = $wc->session->get( 'chosen_shipping_methods' );
		if ( empty( $shippings ) ) {
			return;
		}

		foreach ( $shippings as $package_id => $shipping_rate_key ) {
			$packages = $wc->shipping()->get_packages();
			if ( isset( $packages[ $package_id ]['rates'][ $shipping_rate_key ] ) ) {
				/** @var WC_Shipping_Rate $sh_rate */
				$sh_rate      = $packages[ $package_id ]['rates'][ $shipping_rate_key ];
				$sh_rate_meta = $sh_rate->get_meta_data();
				if ( isset( $sh_rate_meta['wdp_rule'] ) ) {
					$rule_id          = $sh_rate_meta['wdp_rule'];
					$amount           = $sh_rate_meta['wdp_amount'];
					$is_free_shipping = $sh_rate_meta['wdp_free_shipping'];

					$order_stats[ $rule_id ]['shipping']         += $amount;
					$order_stats[ $rule_id ]['is_free_shipping'] = $is_free_shipping;
				}

			}
		}
	}

	private function wdp_get_template( $template_name , $args = array(), $template_path = '' ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$full_template_path = trailingslashit( WC_ADP_PLUGIN_PATH . 'templates' );

		if ( $template_path ) {
			$full_template_path .= trailingslashit( $template_path );
		}

		$full_external_template_path = locate_template( array(
			'advanced-dynamic-pricing-for-woocommerce/' . trailingslashit( $template_path ) . $template_name,
			'advanced-dynamic-pricing-for-woocommerce/' . $template_name,
		) );

		if ( $full_external_template_path ) {
			$full_template_path = $full_external_template_path;
		} else {
			$full_template_path .= $template_name;
		}

		ob_start();
		include $full_template_path;
		$template_content = ob_get_clean();

		return $template_content;
	}

	public function load_frontend_assets() {
		$options    = WDP_Helpers::get_settings();
		wp_enqueue_style( 'wdp_pricing-table', WC_ADP_PLUGIN_URL . '/assets/css/pricing-table.css', array(), WC_ADP_VERSION );
		wp_enqueue_style( 'wdp_deals-table', WC_ADP_PLUGIN_URL . '/assets/css/deals-table.css', array(), WC_ADP_VERSION );

		if ( is_product() || woocommerce_product_loop() ) {
			wp_enqueue_script( 'wdp_deals', WC_ADP_PLUGIN_URL . '/assets/js/frontend.js', array(), WC_ADP_VERSION );
		}

		if ( WDP_Database::is_condition_type_active( array( 'customer_shipping_method' ) ) ) {
			wp_enqueue_script( 'wdp_update_cart', WC_ADP_PLUGIN_URL . '/assets/js/update-cart.js' , array( 'wc-cart' ), WC_ADP_VERSION );
		}

		$script_data = array(
			'ajaxurl'               => admin_url( 'admin-ajax.php' ),
			'update_price_with_qty' => wc_string_to_bool( $options['update_price_with_qty'] ) && ! wc_string_to_bool($options['do_not_modify_price_at_product_page']),
			'js_init_trigger'       => apply_filters( 'wdp_bulk_table_js_init_trigger', "" ),
		);

		wp_localize_script( 'wdp_deals', 'script_data', $script_data );
	}

	public function output_amount_save() {
		$amount_saved = self::get_amount_save_from_items();
		if ( 0 >= $amount_saved ) {
			return null;
		}
		$options    = WDP_Helpers::get_settings();

		$templates = array(
			'woocommerce_mini_cart_contents'               => 'mini-cart.php',
			'woocommerce_cart_totals_before_order_total'   => 'cart-totals.php',
			'woocommerce_review_order_after_cart_contents' => 'cart-totals-checkout.php',
		);

		$template_content = '';
		foreach ( $templates as $hook_name => $template_name ) {
			if ( current_action() == $hook_name ) {
				$template_content = $this->wdp_get_template( $template_name, array(
					'amount_saved' => $amount_saved,
					'title'        => $options['amount_saved_label'],
				), 'amount-saved' );
			}
		}

		echo $template_content;
	}

	public static function get_amount_save_from_items() {
		$cart_items   = WC()->cart->cart_contents;
		$amount_saved = 0;

		foreach ( $cart_items as $cart_item_key => $cart_item ) {
			if ( ! isset( $cart_item['rules'] ) AND ! isset( $cart_item['wdp_rules'] ) ) {
				return 0;
			}
			$cart_item_rules = isset( $cart_item['rules'] ) ? $cart_item['rules'] : $cart_item['wdp_rules'];
			foreach ( $cart_item_rules as $id => $amount_saved_by_rule ) {
				$amount_saved += $amount_saved_by_rule * $cart_item['quantity'];
			}
		}

		return $amount_saved;
	}

	/**
	 * @param string $price formatted price after wc_price()
	 * @param array $cart_item
	 * @param string $cart_item_key
	 *
	 * @return string
	 */
	public function woocommerce_cart_item_price_and_price_subtotal( $price, $cart_item, $cart_item_key ) {
		$product = wc_get_product( $cart_item['product_id'] );
		if ( ! $product ) {
			return $price;
		}

		$new_price = (float) $cart_item['data']->get_price( 'edit' );
		$new_price = $this->price_display->get_cart_item_price_to_display( $product, array( 'price' => $new_price ) );

		$new_price_html = $price;
		if ( ! isset( $cart_item['wdp_original_price'] ) ) {
			return $price;
		}
		$old_price = $cart_item['wdp_original_price'];
		$old_price = $this->price_display->get_cart_item_price_to_display( $product, array( 'price' => $old_price ) );

		if ( 'woocommerce_cart_item_subtotal' == current_filter() ) {
			$new_price = $new_price * $cart_item['quantity'];
			$old_price = $old_price * $cart_item['quantity'];
		}

		if ( $new_price !== false AND $old_price !== false ) {
			if ( $new_price < $old_price ) {
				$price_html = wc_format_sale_price( $old_price, $new_price );
			} else {
				$price_html = $new_price_html;
			}
		} else {
			$price_html = $new_price_html;
		}

		return $price_html;
	}

	function remove_hooks_set_by_other_plugins() {
		global $wp_filter;

		$allowed_hooks = array(
			//Filters
			"woocommerce_get_price_html"            => array( "WDP_Price_Display|hook_get_price_html" ),
			"woocommerce_product_is_on_sale"        => array( "WDP_Price_Display|hook_product_is_on_sale" ),
			"woocommerce_product_get_sale_price"    => array( "WDP_Price_Display|hook_product_get_sale_price" ),
			"woocommerce_product_get_regular_price" => array( "WDP_Price_Display|hook_product_get_regular_price" ),
			"woocommerce_variable_price_html"       => array(),
			"woocommerce_cart_item_price"           => array( "WDP_Frontend|woocommerce_cart_item_price_and_price_subtotal" ),
			"woocommerce_cart_item_subtotal"        => array( "WDP_Frontend|woocommerce_cart_item_price_and_price_subtotal" ),
			//Actions
			"woocommerce_checkout_order_processed"  => array( "WDP_Frontend|checkout_order_processed" ),
			"woocommerce_before_calculate_totals"   => array(), //nothing allowed!
		);

		foreach ( $wp_filter as $hook_name => $hook_obj ) {
			if ( preg_match( '#^woocommerce_#', $hook_name ) ) {
				if ( isset( $allowed_hooks[ $hook_name ] ) ) {
					$wp_filter[ $hook_name ] = $this->remove_wrong_callbacks( $hook_obj, $allowed_hooks[ $hook_name ] );
				} else {
				}
			}
		}
	}

	public static function remove_callbacks( $hook_obj, $hooks ) {
		$new_callbacks = array();
		foreach ( $hook_obj->callbacks as $priority => $callbacks ) {
			$priority_callbacks = array();
			foreach ( $callbacks as $idx => $callback_details ) {
				if ( ! self::is_callback_match( $callback_details, $hooks ) ) {
					$priority_callbacks[ $idx ] = $callback_details;
				}
			}
			if ( $priority_callbacks ) {
				$new_callbacks[ $priority ] = $priority_callbacks;
			}
		}
		$hook_obj->callbacks = $new_callbacks;

		return $hook_obj;
	}

	function remove_wrong_callbacks( $hook_obj, $allowed_hooks ) {
		$new_callbacks = array();
		foreach ( $hook_obj->callbacks as $priority => $callbacks ) {
			$priority_callbacks = array();
			foreach ( $callbacks as $idx => $callback_details ) {
				if ( self::is_callback_match( $callback_details, $allowed_hooks ) ) {
					$priority_callbacks[ $idx ] = $callback_details;
				}
			}
			if ( $priority_callbacks ) {
				$new_callbacks[ $priority ] = $priority_callbacks;
			}
		}
		$hook_obj->callbacks = $new_callbacks;

		return $hook_obj;
	}

	//check class + function name!
	public static function is_callback_match( $callback_details, $allowed_hooks ) {
		$result = false;
		foreach ( $allowed_hooks as $callback_name ) {
			list( $class_name, $func_name ) = explode( "|", $callback_name );
			if ( count( $callback_details['function'] ) != 2 ) {
				continue;
			}
			if ( $class_name == get_class( $callback_details['function'][0] ) AND $func_name == $callback_details['function'][1] ) {
				$result = true;
				break;// done!
			}
		}

		return $result;
	}

	public function woocommerce_add_to_cart_sold_individually_found_in_cart( $found, $product_id, $variation_id, $cart_item_data, $cart_id ) {
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			if ( $cart_item['product_id'] == $product_id ) {
				return true;
			}
		}

		return false;
	}

	private static function get_nopriv_ajax_actions() {
		return array(
			'get_table_with_product_bulk_table',
			'get_price_product_with_bulk_table',
		);
	}

	public static function is_nopriv_ajax_processing() {
		return wp_doing_ajax() && ! empty( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], self::get_nopriv_ajax_actions() );
	}

	public static function get_gifted_cart_products() {
		return WDP_Functions::get_gifted_cart_products();
	}

	public static function get_active_rules_for_product( $product_id, $qty = 1, $use_empty_cart = false ) {
		return WDP_Functions::get_active_rules_for_product( $product_id, $qty, $use_empty_cart );
	}

	public static function get_discounted_products_for_cart( $array_of_products, $plain = false ) {
		return WDP_Functions::get_discounted_products_for_cart( $array_of_products, $plain );
	}

	public static function get_discounted_product_price( $the_product, $qty, $use_empty_cart = true ) {
		return WDP_Functions::get_discounted_product_price( $the_product, $qty, $use_empty_cart );
	}

	public static function process_without_hooks( $callback, $hooks_list ) {
		return WDP_Functions::process_without_hooks( $callback, $hooks_list );
	}
}