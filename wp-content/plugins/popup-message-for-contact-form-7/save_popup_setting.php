<?php

/* Save Popup General Settings*/



// add the action for save popupdata

add_action( 'wpcf7_after_save', 'action_wpcf7_after_save', 10, 1 ); 



// Save popup general settings on post meta

function action_wpcf7_after_save( $instance) { 
	
	$formid = $instance->id;

	// check Enable or Disable Form popup if popup form is enable then formid store otherwise null value pass

	if(!empty($_POST['enabled_popup_val']))
	{
		$enabled_popup_id = $formid;
	}
	else
	{
		$enabled_popup_id = "";
	}

	if(!empty($_POST['enabled_failure_popup_val']))
	{
		$enabled_failure_popup_id = $formid;
	}
	else
	{
		$enabled_failure_popup_id = "";
	}

	// Add or Update popup general settings

	update_post_meta( $formid, 'enabled-popup', $enabled_popup_id );

	

	$popup_message_data = sanitize_textarea_field($_POST['popup_message']);
	update_post_meta( $formid, 'popup_message', $popup_message_data );

	$m_popup_width_data = sanitize_text_field($_POST['m_popup_width']);
	update_post_meta( $formid, 'm_popup_width', $m_popup_width_data );

	$m_popup_radius = sanitize_text_field($_POST['m_popup_radius']);
	update_post_meta( $formid, 'm_popup_radius', $m_popup_radius );

   	$m_popup_duration_data = sanitize_text_field($_POST['m_popup_duration']);
	update_post_meta( $formid, 'm_popup_duration', $m_popup_duration_data );

	$popup_templet_data = sanitize_text_field($_POST['popup_templet']);
	update_post_meta( $formid, 'popup_templet', $popup_templet_data );

	if($popup_templet_data == "templet1"){
		update_post_meta( $formid, 'popup_background_option', "bg_color");
		update_post_meta( $formid, 'popup_background_color', "#34495e" );
		update_post_meta( $formid, 'popup_text_color', "#ffffff" );
		update_post_meta( $formid, 'popup_button_background_color', "#27ad5f" );

	}
	if($popup_templet_data == "templet2"){
		update_post_meta( $formid, 'popup_background_option', "gradient_color");
		update_post_meta( $formid, 'popup_gradient_color', "#CD5C5C");
		update_post_meta( $formid, 'popup_gradient_color1', "#FFA07A");
		update_post_meta( $formid, 'popup_text_color', "#000000" );
		update_post_meta( $formid, 'popup_button_background_color', "#ffffff" );

	}
	if($popup_templet_data == "templet3"){
		update_post_meta( $formid, 'popup_background_option', "image");
		update_post_meta( $formid, 'popup_image_color', plugins_url( 'popup-message-for-contact-form-7/images/pexels-photo-1191710.jpeg'));
		update_post_meta( $formid, 'popup_text_color', "#ffffff" );
		update_post_meta( $formid, 'popup_button_background_color', "#51654e" );
		
	}
	if($popup_templet_data == "templet4"){
		update_post_meta( $formid, 'popup_background_option', "gradient_color");
		update_post_meta( $formid, 'popup_gradient_color', "#268717");
		update_post_meta( $formid, 'popup_gradient_color1', "#A6EF9B");
		update_post_meta( $formid, 'popup_text_color', "#000000" );
		update_post_meta( $formid, 'popup_button_background_color', "#ffffff" );

	}
	if($popup_templet_data == "templet5"){
		update_post_meta( $formid, 'popup_background_option', "image");
		update_post_meta( $formid, 'popup_image_color', plugins_url( 'popup-message-for-contact-form-7/images/background-brick-brickwork-268966.jpg'));
		update_post_meta( $formid, 'popup_text_color', "#ffffff" );
		update_post_meta( $formid, 'popup_button_background_color', "#FF9800" );
		
	}
	if($popup_templet_data == "custom_templet"){
		$popup_background_option = sanitize_text_field($_POST['popup_background_option']);
		update_post_meta( $formid, 'popup_background_option', $popup_background_option );

		$popup_background_color_data = sanitize_text_field($_POST['popup_background_color']);
		update_post_meta( $formid, 'popup_background_color', "#".$popup_background_color_data );

		$popup_image_color_data = sanitize_text_field($_POST['popup_image_color']);
		if(!empty($popup_image_color_data)){
			update_post_meta( $formid, 'popup_image_color', $popup_image_color_data );
		}

	   	$popup_gradient_color_data = sanitize_text_field($_POST['popup_gradient_color']);
		update_post_meta( $formid, 'popup_gradient_color', "#".$popup_gradient_color_data );

	   	$popup_gradient_color1_data = sanitize_text_field($_POST['popup_gradient_color1']);
		update_post_meta( $formid, 'popup_gradient_color1', "#".$popup_gradient_color1_data );

		$popup_text_color_data = sanitize_text_field($_POST['popup_text_color']);
	    update_post_meta( $formid, 'popup_text_color', "#".$popup_text_color_data );

	    $popup_button_background_color_data = sanitize_text_field($_POST['popup_button_background_color']);
	    update_post_meta( $formid, 'popup_button_background_color', "#".$popup_button_background_color_data );
		
	}

   	$popup_button_text_data = sanitize_text_field($_POST['popup_button_text']);
	update_post_meta( $formid, 'popup_button_text', $popup_button_text_data );
}; 

         





