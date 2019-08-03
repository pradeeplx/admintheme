<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Product_Variation_Data_Store_CPT extends WC_Product_Variation_Data_Store_CPT implements WC_Object_Data_Store_Interface {
	/**
	 * @var WC_Product|null
	 */
	private $product_parent = null;

	/**
	 * Reads a product from the database and sets its data to the class.
	 *
	 * @since 3.0.0
	 * @param WC_Product_Variation $product Product object.
	 * @throws WC_Data_Exception If WC_Product::set_tax_status() is called with an invalid tax status (via read_product_data).
	 */
	public function read( &$product ) {
		$product->set_defaults();

		if ( ! $product->get_id() ) {
			return;
		}

		$post_object = get_post( $product->get_id() );

		if ( ! $post_object || ! in_array( $post_object->post_type, array( 'product', 'product_variation' ), true ) ) {
			return;
		}

		$product->set_props(
			array(
				'name'              => $post_object->post_title,
				'slug'              => $post_object->post_name,
				'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
				'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
				'status'            => $post_object->post_status,
				'menu_order'        => $post_object->menu_order,
				'reviews_allowed'   => 'open' === $post_object->comment_status,
				'parent_id'         => $post_object->post_parent,
				'attribute_summary' => $post_object->post_excerpt,
			)
		);

		// The post parent is not a valid variable product so we should prevent this.
		if ( $product->get_parent_id( 'edit' ) && 'product' !== get_post_type( $product->get_parent_id( 'edit' ) ) ) {
			$product->set_parent_id( 0 );
		}

//		$this->read_downloads( $product );
		$this->read_product_data( $product );
		$this->read_extra_data( $product );
		$product->set_attributes( wc_get_product_variation_attributes( $product->get_id() ) );

//		$updates = array();
		/**
		 * If a variation title is not in sync with the parent e.g. saved prior to 3.0, or if the parent title has changed, detect here and update.
		 */
//		$new_title = $this->generate_product_title( $product );

//		if ( $post_object->post_title !== $new_title ) {
//			$product->set_name( $new_title );
//			$updates = array_merge( $updates, array( 'post_title' => $new_title ) );
//		}

		/**
		 * If the attribute summary is not in sync, update here. Used when searching for variations by attribute values.
		 * This is meant to also cover the case when global attribute name or value is updated, then the attribute summary is updated
		 * for respective products when they're read.
		 */
//		$new_attribute_summary = $this->generate_attribute_summary( $product );
//
//		if ( $new_attribute_summary !== $post_object->post_excerpt ) {
//			$product->set_attribute_summary( $new_attribute_summary );
//			$updates = array_merge( $updates, array( 'post_excerpt' => $new_attribute_summary ) );
//		}

//		if ( ! empty( $updates ) ) {
//			$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $updates, array( 'ID' => $product->get_id() ) );
//		clean_post_cache( $product->get_id() );
//		}

		// Set object_read true once all data is read.
		$product->set_object_read( true );
	}

	/**
	 * Generates a title with attribute information for a variation.
	 * Products will get a title of the form "Name - Value, Value" or just "Name".
	 *
	 * @since 3.0.0
	 * @param WC_Product $product Product object.
	 * @return string
	 */
//	protected function generate_product_title( $product ) {
//		$title_base                = get_post_field( 'post_title', $product->get_parent_id() );
//
//		return apply_filters( 'woocommerce_product_variation_title', $title_base, $product, $title_base, "" );
//	}

	/**
	 * @param $parent WC_Product
	 */
	public function add_parent( $parent ) {
		if ( $parent instanceof WC_Product ) {
			$this->product_parent = $parent;
		}
	}

	/**
	 * Read post data.
	 *
	 * @since 3.0.0
	 * @param WC_Product_Variation $product Product object.
	 * @throws WC_Data_Exception If WC_Product::set_tax_status() is called with an invalid tax status.
	 */
	protected function read_product_data( &$product ) {
		global $wpdb;

		$id = $product->get_id();

		$meta_keys = array(
			'_variation_description' => 'description',
			'_regular_price' => 'regular_price',
			'_sale_price' => 'sale_price',
			'_sale_price_dates_from' => 'date_on_sale_from',
			'_sale_price_dates_to' => 'date_on_sale_to',
			'_manage_stock' => 'manage_stock',
			'_stock_status' => 'stock_status',
			'_virtual' => 'virtual',
			'_downloadable' => 'downloadable',
			'_product_image_gallery' => 'gallery_image_ids',
			'_download_limit' =>'download_limit',
			'_download_expiry' => 'download_expiry',
			'_thumbnail_id' => 'image_id',
			'_backorders' => 'backorders',
			'_sku' => 'sku',
			'_stock' => 'stock_quantity',
			'_weight' => 'weight',
			'_length' => 'length',
			'_width' => 'width',
			'_height' => 'height',
			'_tax_class' => 'tax_class',
		);

		if ( $meta_keys ) {
			end($meta_keys);
			$last_meta_key = key($meta_keys);

			$where = " meta_key IN ( ";
			foreach ( $meta_keys as $meta_key => $prop_name ) {
				$where .= " '{$meta_key}' ";
				if  ($meta_key !== $last_meta_key) {
					$where .= " , ";
				}
			}
			$where .= " ) ";

			$meta_pairs = $wpdb->get_results("
				SELECT meta_key, meta_value
				FROM {$wpdb->postmeta}
				WHERE ({$where})
				AND post_id = {$id}
			 " );

			$props = array();

			foreach ( $meta_pairs as $meta ) {
				$props[ $meta_keys[ $meta->meta_key ] ] = $meta->meta_value;
			}

			if ( ! isset( $props['tax_class'] ) ) {
				$props['tax_class'] = 'parent';
			}

			$product->set_props( $props );
		} else {
			$product->set_props(
				array(
					'description'       => get_post_meta( $id, '_variation_description', true ),
					'regular_price'     => get_post_meta( $id, '_regular_price', true ),
					'sale_price'        => get_post_meta( $id, '_sale_price', true ),
					'date_on_sale_from' => get_post_meta( $id, '_sale_price_dates_from', true ),
					'date_on_sale_to'   => get_post_meta( $id, '_sale_price_dates_to', true ),
					'manage_stock'      => get_post_meta( $id, '_manage_stock', true ),
					'stock_status'      => get_post_meta( $id, '_stock_status', true ),
					'virtual'           => get_post_meta( $id, '_virtual', true ),
					'downloadable'      => get_post_meta( $id, '_downloadable', true ),
					'gallery_image_ids' => array_filter( explode( ',', get_post_meta( $id, '_product_image_gallery', true ) ) ),
					'download_limit'    => get_post_meta( $id, '_download_limit', true ),
					'download_expiry'   => get_post_meta( $id, '_download_expiry', true ),
					'image_id'          => get_post_thumbnail_id( $id ),
					'backorders'        => get_post_meta( $id, '_backorders', true ),
					'sku'               => get_post_meta( $id, '_sku', true ),
					'stock_quantity'    => get_post_meta( $id, '_stock', true ),
					'weight'            => get_post_meta( $id, '_weight', true ),
					'length'            => get_post_meta( $id, '_length', true ),
					'width'             => get_post_meta( $id, '_width', true ),
					'height'            => get_post_meta( $id, '_height', true ),
					'tax_class'         => ! metadata_exists( 'post', $id, '_tax_class' ) ? 'parent' : get_post_meta( $id, '_tax_class', true ),
				)
			);
		}

//		$product->set_shipping_class_id( current( $this->get_term_ids( $id, 'product_shipping_class' ) ) );

		if ( $product->is_on_sale( 'edit' ) ) {
			$product->set_price( $product->get_sale_price( 'edit' ) );
		} else {
			$product->set_price( $product->get_regular_price( 'edit' ) );
		}

		if ( is_null( $this->product_parent ) ) {
			$parent_object   = get_post( $product->get_parent_id() );
			$terms           = get_the_terms( $product->get_parent_id(), 'product_visibility' );
			$term_names      = is_array( $terms ) ? wp_list_pluck( $terms, 'name' ) : array();
			$exclude_search  = in_array( 'exclude-from-search', $term_names, true );
			$exclude_catalog = in_array( 'exclude-from-catalog', $term_names, true );

			if ( $exclude_search && $exclude_catalog ) {
				$catalog_visibility = 'hidden';
			} elseif ( $exclude_search ) {
				$catalog_visibility = 'catalog';
			} elseif ( $exclude_catalog ) {
				$catalog_visibility = 'search';
			} else {
				$catalog_visibility = 'visible';
			}

			$product->set_parent_data(
				array(
					'title'              => $parent_object ? $parent_object->post_title : '',
					'status'             => $parent_object ? $parent_object->post_status : '',
					'sku'                => get_post_meta( $product->get_parent_id(), '_sku', true ),
					'manage_stock'       => get_post_meta( $product->get_parent_id(), '_manage_stock', true ),
					'backorders'         => get_post_meta( $product->get_parent_id(), '_backorders', true ),
					'low_stock_amount'   => get_post_meta( $product->get_parent_id(), '_low_stock_amount', true ),
					'stock_quantity'     => wc_stock_amount( get_post_meta( $product->get_parent_id(), '_stock', true ) ),
					'weight'             => get_post_meta( $product->get_parent_id(), '_weight', true ),
					'length'             => get_post_meta( $product->get_parent_id(), '_length', true ),
					'width'              => get_post_meta( $product->get_parent_id(), '_width', true ),
					'height'             => get_post_meta( $product->get_parent_id(), '_height', true ),
					'tax_class'          => get_post_meta( $product->get_parent_id(), '_tax_class', true ),
					'shipping_class_id'  => absint( current( $this->get_term_ids( $product->get_parent_id(), 'product_shipping_class' ) ) ),
					'image_id'           => get_post_thumbnail_id( $product->get_parent_id() ),
					'purchase_note'      => get_post_meta( $product->get_parent_id(), '_purchase_note', true ),
					'catalog_visibility' => $catalog_visibility,
				)
			);
		} else {
			$product->set_parent_data(
				array(
					'title'              => $this->product_parent->get_title(),
					'status'             => $this->product_parent->get_status('nofilter'),
					'sku'                => $this->product_parent->get_sku('nofilter'),
					'manage_stock'       => $this->product_parent->managing_stock(),
					'backorders'         => $this->product_parent->backorders_allowed(),
					'low_stock_amount'   => $this->product_parent->get_low_stock_amount('nofilter'),
					'stock_quantity'     => $this->product_parent->get_stock_quantity('nofilter'),
					'weight'             => $this->product_parent->get_weight('nofilter'),
					'length'             => $this->product_parent->get_length('nofilter'),
					'width'              => $this->product_parent->get_width('nofilter'),
					'height'             => $this->product_parent->get_height('nofilter'),
					'tax_class'          => $this->product_parent->get_tax_class('nofilter'),
					'shipping_class_id'  => $this->product_parent->get_shipping_class_id('nofilter'),
					'image_id'           => $this->product_parent->get_image_id('nofilter'),
					'purchase_note'      => $this->product_parent->get_purchase_note('nofilter'),
					'catalog_visibility' => $this->product_parent->get_catalog_visibility('nofilter'),
				)
			);
		}


		// Pull data from the parent when there is no user-facing way to set props.
		$product->set_sold_individually( get_post_meta( $product->get_parent_id(), '_sold_individually', true ) );
		$product->set_tax_status( get_post_meta( $product->get_parent_id(), '_tax_status', true ) );
		$product->set_cross_sell_ids( get_post_meta( $product->get_parent_id(), '_crosssell_ids', true ) );
	}
}