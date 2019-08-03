<?php
class Checkout_Page_Layout_Header_Footer
{
	public function __construct()
	{
		
		
	}
	public static function header_content_init() {
		$header_status = get_post_meta( get_the_ID(), '_tes_layout_hf_header_status',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_header_status',true) : 'no';
		$header_img = get_post_meta( get_the_ID(), '_tes_layout_hf_header_img',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_header_img',true) : '';
		$header_img_align = get_post_meta( get_the_ID(), '_tes_layout_hf_header_img_alignment',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_header_img_alignment',true) : 'center';
		
		$header_text = get_post_meta( get_the_ID(), '_tes_layout_hf_header_text',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_header_text',true) : 'Checkout';
		$header_text_align = get_post_meta( get_the_ID(), '_tes_layout_hf_header_text_alignment',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_header_text_alignment',true) : 'center';
		?>
		<p>
			<label>Header Status:</label>
			<select name="_tes_layout_hf_header_status" id="tes_header_status" onchange="set_header_settings();">
				<option value='no' <?php if( $header_status == 'no' ){ echo 'selected=selected'; } ?>>No Header</option>
				<!-- <option value='site' <?php //if( $header_status == 'site' ){ echo 'selected=selected'; } ?>>Site Header</option> -->
				<option value='custom' <?php if( $header_status == 'custom' ){ echo 'selected=selected'; } ?>>Custom Header</option>
				<option value='text' <?php if( $header_status == 'text' ){ echo 'selected=selected'; } ?>>Text Header</option>
			</select>
		</p>
		<p class="hf-status-desc" id="hf-no"><span class="message">Header will be invivisble on your checkout page.</span></p>
		<p class="hf-status-desc" id="hf-site"><span class="message">Default site header will be displayed on your checkout page.</span></p>
		<div class="hf-status-desc" id="hf-custom">
			<span id="header-upload-img"><img style="width:50%;" src="<?php echo $header_img; ?>" ></span>
			<input type="hidden" name="_tes_layout_hf_header_img" id="header_img_url" value="<?php echo $header_img; ?>"/>
			<a href="javascript:void(0);" class="btn btn-primary" id="tes-cc-upload-header-img">Upload New Image</a>
			<br/>
			<label>Alignment:</label>
			<br/>
			<select name="_tes_layout_hf_header_img_alignment">
				<option value="left" <?php if( $header_img_align=='left'){ echo 'selected=selected';} ?>>Left</option>
				<option value="center" <?php if( $header_img_align=='center'){ echo 'selected=selected';} ?>>Center</option>
				<option value="right" <?php if( $header_img_align=='right'){ echo 'selected=selected';} ?>>Right</option>
				
			</select>
			<br/>
			<span class="message">Uploaded image will be displayed on your checkout page Header section as Logo.</span>
		</div>
		<div class="hf-status-desc" id="hf-text">
			<label>Text:</label>
			<br/>
			<input type="text" name="_tes_layout_hf_header_text" value="<?php echo $header_text; ?>">
			<br/>
			<label>Alignment:</label>
			<br/>
			<select name="_tes_layout_hf_header_text_alignment">
				<option value="left" <?php if( $header_text_align == 'left' ){ echo 'selected=selected';} ?>>Left</option>
				<option value="center" <?php if( $header_text_align =='center' ){ echo 'selected=selected';} ?>>Center</option>
				<option value="right" <?php if( $header_text_align =='right' ){ echo 'selected=selected';} ?>>Right</option>
				
			</select>
			<br/>
			<span class="message">Text will be displayed on your checkout page Header section as Logo Text.</span>
		</div>
		<?php
	}
	public static function footer_content_init() {
		$footer_status = 'no';

		$footer_custom_element = 'menu';

		$footer_custom_element_align = 'center';

		$footer_img = '';

		$footer_menu = '';

		
		?>
		<p>
			<label>Footer Status:</label>
			<select name="_tes_layout_hf_footer_status" id="tes_footer_status" onchange="set_footer_settings();">
				<option value='no' <?php if( $footer_status == 'no' ){ echo 'selected=selected'; } ?>>No Footer</option>
				<option value='custom' <?php if( $footer_status == 'custom' ){ echo 'selected=selected'; } ?>>Custom Footer</option>
				<option value='text' <?php if( $footer_status == 'text' ){ echo 'selected=selected'; } ?>>Text Footer</option>
			</select>
		</p>
		<p class="hf-status-desc-footer" id="hf-no-footer"><span class="message">Footer will be invivisble on your checkout page.</span></p>
		<p class="hf-status-desc-footer" id="hf-site-footer"><span class="message">Default site footer will be displayed on your checkout page.</span></p>
		<div class="hf-status-desc-footer" id="hf-custom-footer">
			<label>Alignment:</label>
			<br/>
			<select name="_tes_layout_hf_footer_custom_element_alignment">
				<option value="left" <?php if( $footer_custom_element_align=='left'){ echo 'selected=selected';} ?>>Left</option>
				<option value="center" <?php if( $footer_custom_element_align=='center'){ echo 'selected=selected';} ?>>Center</option>
				<option value="right" <?php if( $footer_custom_element_align=='right'){ echo 'selected=selected';} ?>>Right</option>
				
			</select>
			<br/>
			<label>Element:</label>
			<br/>
			<select name="_tes_layout_hf_footer_custom_element" id="footer_custom_element_selection" onchange="generate_footer_element_option();">
				<option value='menu' <?php if( $footer_custom_element == 'menu' ){ echo 'selected=selected'; } ?>>Menu</option>
				<option value='image' <?php if( $footer_custom_element == 'image' ){ echo 'selected=selected'; } ?>>Image</option>
			</select>
			<div id="footer-custom-element">
				<div class="footer-custom-elements" id="footer-custom-element-image">
					<span id="footer-upload-img"><img style="width:50%;" src="<?php echo $footer_img; ?>" ></span>
					<input type="hidden" name="_tes_layout_hf_footer_img" id="footer_img_url" value="<?php echo $footer_img; ?>"/>
					<a href="javascript:void(0);" class="btn btn-primary" id="tes-cc-upload-footer-img">Upload Image</a>
				</div>
				<div class="footer-custom-elements" id="footer-custom-element-menu">
					<label>Select Menu:</label>
					<br/>
					<?php 
						$get_all_menus = get_terms( 'nav_menu' );
						$menus = get_nav_menu_locations();
						if ( empty( $get_all_menus ) ) {
							$get_all_menus_items = array( 'none' => "You haven't made/assign any menus!" );
						} else {
							$get_all_menus_items = array();
							$item_keys = array( 'none' ); $item_values = array( 'None' );
							foreach ( $get_all_menus as $items ) {
								array_push( $item_keys, $items->slug);
								array_push( $item_values, $items->name);
							}
							$get_all_menus_items = array_combine( $item_keys, $item_values );
						}
					?>
						<select name="_tes_layout_hf_footer_custom_element_menu" id="footer_custom_element_menu">
							<?php
							foreach ( $get_all_menus_items as $get_all_menus_item => $value) {
								if( $footer_menu == $get_all_menus_item){
							    	echo '<option value="'.$get_all_menus_item.'" selected>'.$value.'</option>';
								}else{
									echo '<option value="'.$get_all_menus_item.'">'.$value.'</option>';
								}
							}
							?>
						</select>

				</div>
			</div>

		</div>
		<div class="hf-status-desc-footer" id="hf-text-footer">
			<label>Text:</label>
			<br/>
			<input type="text" name="_tes_layout_hf_footer_text" value="<?php echo $footer_text; ?>">
			<br/>
			<label>Alignment:</label>
			<br/>
			<select name="_tes_layout_hf_footer_text_alignment">
				<option value="left" <?php if( $footer_text_align == 'left' ){ echo 'selected=selected';} ?>>Left</option>
				<option value="center" <?php if( $footer_text_align =='center' ){ echo 'selected=selected';} ?>>Center</option>
				<option value="right" <?php if( $footer_text_align =='right' ){ echo 'selected=selected';} ?>>Right</option>
				
			</select>
			<br/>
		</div>
		<span class="pro-adv">
    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon.png';?>"/>To access this feature you will need to 
    			<a href="https://customcheckoutplugin.com" target="_blank;">purchase the Pro version.</a>
    		</span>
		<?php
	}
	

}

?>