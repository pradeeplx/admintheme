<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * @var boolean $hide_inactive
 * @var string $hide_inactive Pagination HTML
 */
?>

    <div id="poststuff">

	<?php if ($options['support_shortcode_products_on_sale']): ?>
	    <div style="clear: both;">
		<p style="margin: 5px">
		    <button type="button" class="button wdp-btn-rebuild-onsale-list">
			<?php _e('Rebuild Onsale List', 'advanced-dynamic-pricing-for-woocommerce'); ?>
		    </button>
		</p>
	    </div>
	<?php endif; ?>
        <div style="clear: both;">
            <p style="float: left; margin: 5px">
                <label>
                    <input type="checkbox" class="hide-disabled-rules" <?php checked($hide_inactive); ?>>
			        <?php _e( 'Hide inactive rules', 'advanced-dynamic-pricing-for-woocommerce' ) ?>
                </label>
            </p>

            <form id="rules-filter" method="get" style="float: right; margin: 5px">
                <input type="hidden" name="page" value="<?php echo $page; ?>" />
                <input type="hidden" name="tab" value="<?php echo $tab; ?>" />
		        <?php echo $pagination; ?>
            </form>
        </div>

        <div id="post-body" class="metabox-holder">
            <div id="postbox-container-2" class="postbox-container">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    <div id="rules-container" class="sortables-container group-container loading"></div>
                    <p id="no-rules" class="loading"><?php _e( 'No common rules defined', 'advanced-dynamic-pricing-for-woocommerce' ) ?></p>
                    <p>
                        <button class="button add-rule loading">
							<?php _e( 'Add rule', 'advanced-dynamic-pricing-for-woocommerce' ) ?></button>
                    </p>
                    <div id="progress_div" style="">
                        <div id="container"><span class="spinner is-active" style="float:none;"></span></div>
                    </div>

                </div>
            </div>

            <div style="clear: both;"></div>
        </div>
    </div>

<?php include WC_ADP_PLUGIN_PATH.'/views/rules/templates.php'; ?>