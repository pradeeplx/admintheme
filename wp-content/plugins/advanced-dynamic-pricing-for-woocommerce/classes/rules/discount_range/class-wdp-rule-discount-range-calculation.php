<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Rule_Discount_Range_Calculation {
	private $calculator;

	public function __construct( $type, $discount_type, $ranges ) {
		if ( 'bulk' === $type ) {
			$this->calculator = new WDP_Rule_Bulk_Calculator();
		} elseif ( 'tier' === $type ) {
			$this->calculator = new WDP_Rule_Tier_Calculator();
		}

		$this->calculator->set_discount_type( $discount_type );
		$this->calculator->set_ranges( $ranges );
	}
	
	public function is_type_tier() {
		return $this->calculator instanceof WDP_Rule_Tier_Calculator;
	}

	/**
	 * @param $items WDP_Rule_Discount_Range_Calculation_Item[]
	 * @param $custom_qty int
	 *
	 * @return array
	 */
	public function calculate_items_discounts( $items, $custom_qty = null ) {
		return $this->calculator->calculate_items_discounts( $items, $custom_qty );
	}

	/**
	 * @param $ranges array
	 *
	 * @return WDP_Rule_Discount_Range[]
	 */
	public static function make_ranges( $ranges ) {
		$ranges_objs = array();

		foreach ( $ranges as $range ) {
			$from  = ! empty( $range['from'] ) ? $range['from'] : 1;
			$to    = ! empty( $range['to'] ) ? $range['to'] : '';
			$value =  isset( $range['value'] ) ? (float)$range['value'] : null;

			if ( null === $value ) {
				continue;
			}

			$ranges_objs[] = new WDP_Rule_Discount_Range( $from, $to, $value );
		}

		return $ranges_objs;
	}

}
