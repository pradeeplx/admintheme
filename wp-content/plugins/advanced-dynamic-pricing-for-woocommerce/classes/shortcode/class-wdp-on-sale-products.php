<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Shortcode_Products_On_Sale extends WC_Shortcode_Products {

    const NAME		= 'adp_products_on_sale';
    const STORAGE_KEY	= 'wdp_products_onsale';

    public static function register() {
	add_shortcode(static::NAME, array('WDP_Shortcode_Products_On_Sale', 'create'));
    }

    public static function create($atts) {

    // apply legacy [sale_products] attributes
	$atts = array_merge( array(
		'limit'        => '12',
		'columns'      => '4',
		'orderby'      => 'title',
		'order'        => 'ASC',
		'category'     => '',
		'cat_operator' => 'IN',
	), (array) $atts );

	$shortcode = new static( $atts, 'adp_products_on_sale' );

	return $shortcode->get_content();
    }

    protected function set_adp_products_on_sale_query_args( &$query_args ) {
	$query_args['post__in'] = array_merge( array( 0 ), static::get_cached_products_ids_on_sale() );
    }

    public static function get_cached_products_ids_on_sale() {

	// Load from cache.
	$product_ids_on_sale = get_transient( static::STORAGE_KEY );

	// Valid cache found.
	if ( false !== $product_ids_on_sale ) {
		return $product_ids_on_sale;
	}

	return static::update_cached_products_ids_on_sale();
    }

    public static function update_cached_products_ids_on_sale() {

	$product_ids_on_sale = static::get_products_ids_on_sale();

	set_transient( static::STORAGE_KEY, $product_ids_on_sale, DAY_IN_SECONDS * 30 );

	return $product_ids_on_sale;
    }

    public static function get_products_ids_on_sale() {

	global $wpdb;

	$rule_array = WDP_Rules_Registry::get_instance()->get_active_rules()->to_array();
	$sql_generator = new WDP_Rule_SQL_Generator();

	    foreach ( $rule_array as $rule ) {
		    if ( $rule->is_simple_on_sale_rule() ) {
			    $sql_generator->apply_rule_to_query( $rule );
		    }
	    }

	if ( $sql_generator->is_empty() ) {
	    return array();
	}

	$sql_joins = $sql_generator->get_join();
	$sql_where = $sql_generator->get_where();

	$sql = "SELECT post.ID as id, post.post_parent as parent_id FROM `$wpdb->posts` AS post
		".implode(" ", $sql_joins)."
		WHERE post.post_type IN ( 'product', 'product_variation' )
			AND post.post_status = 'publish'
		". ($sql_where ? " AND " : "") . implode(" OR ", array_map(function ($v) { return "(".$v.")"; }, $sql_where))."
		GROUP BY post.ID";

	$on_sale_products = $wpdb->get_results($sql);

	$product_ids_on_sale = wp_parse_id_list(array_merge(
	    wp_list_pluck( $on_sale_products, 'id' ),
	    array_diff( wp_list_pluck( $on_sale_products, 'parent_id' ), array( 0 ) )
	));

	return $product_ids_on_sale;
    }

}
