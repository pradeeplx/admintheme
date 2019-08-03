<?php
if (is_array($_POST['ssf_wp_region_id'])==1) {
	$rplc_arr=array_fill(0, count($_POST['ssf_wp_region_id']), "%d");
	$id_string=implode(",", array_map(array($wpdb, "prepare"), $rplc_arr, $_POST['ssf_wp_region_id'])); 
} else { 
	$id_string=$wpdb->prepare("%d", $_POST['ssf_wp_region_id']); 
}
$wpdb->query("DELETE FROM ".SSF_WP_REGION_TABLE." WHERE ssf_wp_region_id IN ($id_string)");
?>