<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * @var array $groups
 */
$items = array();
foreach ( $groups as $group ) {
    foreach ( $group['items'] as $key => $item ) {
        $items[$key] = $item;
    }
}
?>
<div>
    <h3><?php _e( 'Export tool', 'advanced-dynamic-pricing-for-woocommerce' ) ?></h3>
    <div>
        <p>
            <label for="wdp-export-select">
				<?php _e( 'Copy these settings and use it to migrate plugin to another WordPress install.',
					'advanced-dynamic-pricing-for-woocommerce' ) ?>
            </label>
            <select id="wdp-export-select">
	            <?php foreach ( $groups as $group ): ?>
                <optgroup label="<?php echo $group['label']; ?>">
		            <?php foreach ( $group['items'] as $key => $item ):?>
                        <option value="<?php echo $key ?>"><?php echo $item['label'] ?></option>
		            <?php endforeach; ?>
                </optgroup>
	            <?php endforeach; ?>
            </select>
        </p>
        <p>
            <textarea id="wdp-export-data" name="wdp-export-data" class="large-text" rows="15"></textarea>
        </p>
    </div>
</div>
<form method="post">
    <div>
        <h3><?php _e( 'Import tool', 'advanced-dynamic-pricing-for-woocommerce' ) ?></h3>
        <div>
            <label for="wdp-import-data">
				<?php _e( 'Paste text into this field to import settings into the current WordPress install.',
					'advanced-dynamic-pricing-for-woocommerce' ) ?>
            </label>
            <div>
                <textarea id="wdp-import-data" name="wdp-import-data" class="large-text" rows="15"></textarea>
            </div>
            <div>
				<input type="hidden" name="wdp-import-data-reset-rules" value="0">
                <input type="checkbox" id="wdp-import-data-reset-rules" name="wdp-import-data-reset-rules" value="1">
                <label for="wdp-import-data-reset-rules">
                  <?php _e( 'Clear all rules before import', 'advanced-dynamic-pricing-for-woocommerce' ) ?>
                 </label>
            </div>
        </div>
    </div>
    <p>
        <button type="submit" id="wdp-import" name="wdp-import"  class="button button-primary">
			<?php _e( 'Import', 'advanced-dynamic-pricing-for-woocommerce' ) ?></button>
    </p>
</form>
<script>
    var wdp_export_items = '<?php echo json_encode( $items ) ?>';
</script>