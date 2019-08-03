<?php
class Checkout_Page_Layout_Content
{
	public function __construct()
	{
		add_action('admin_footer',array($this,'add_content_modal'));
		add_action('admin_footer',array($this,'edit_content_modal'));
	}
	public static function status_column(){
		if( get_post_status( get_the_ID() ) == 'publish' ){
		?>
			<label class="custom-checkout-pro-switch switch">
			  <input <?php if( get_option('_checkout_page_layout_') == get_the_ID()){ echo 'checked'; } ?> type="checkbox" class="tes_cc_radio" name="tes_cc_temp_status" value="<?php echo get_the_ID(); ?>">
			  <span class="slider"></span>
			</label>
			<span style="padding-top: 8px;" class="loader" id="loader-<?php echo get_the_ID(); ?>">Please wait, operation is in progress...<img src="<?php echo TSRCT_CT_IMG ?>/ajax-loader-2.gif" /></span>
			<style type="text/css">#check-page-lay-status{ height: 109px; }</style>
		<?php
		}else{
			?>
			<p style="font-size: 13px;"><label>It seems, your template is not published yet, please "Publish" the template at first, then access the status.</p>
			<?php
		}
	} 

	public static function content_column(){
		$strc_value = get_post_meta( get_the_ID(), '_tes_layout_layoutContent_odersummary',true) ? get_post_meta( get_the_ID(), '_tes_layout_layoutContent_odersummary',true) : 'enable';
		?>
		<p>
			<select name="layoutContent_odersummary" id="layoutContent_odersummary">
				<option value="enable" <?php if( $strc_value == 'enable' ){ echo 'selected=selected'; } ?>>Enable</option>
				<option value="disable" <?php if( $strc_value == 'disable' ){ echo 'selected=selected'; } ?>>Disable</option>
			</select>
		</p>
		<?php
	}


	public static function content_init(){
		$value = get_post_meta( get_the_ID(), '_tes_layout_id',true) ? get_post_meta( get_the_ID(), '_tes_layout_id',true) :get_user_meta(get_current_user_id(), '_tes_temp_lay_id_', true );
		$content = get_post_meta(get_the_ID(),'_tes_layout_content_',true);
		if( $content ){
		?>
			<div class="content_prepopulated">
				
			</div>
		<?php
		}
		?>
		<div class="content_generation">
			<div class="column">
				<div class="sortable-list" id="generated-content">
					<?php print_r($content); ?>
					<input type="hidden" id="content_sec_no" value="1" />
				</div>
			</div>
			
		</div>
		<textarea id="main_container" name="main_container"></textarea>
		<div class="add_new_content"><a type="button" href="javascript:void(0);" class="add_new_content_btn btn">Add New Content</a><div>
		<?php
	}
	public static function add_content_modal(){
		?>
		<div class="modal fade" id="contentModal" role="dialog" style="display: none;">
		    <div class="modal-dialog">
		      	<div class="modal-content">
		        	<div class="modal-header">
		          		<button type="button" class="close" data-dismiss="modal">&times;</button>
		          		<h4 class="modal-title">Content Generation</h4>
		        	</div>
		        	<div class="modal-body">
		        		<label>Select Content Type:</label>
		        		<p>
		        			<select name="layoutContent[type]" id="content_type">
			        			<option value="wpEditor">WordPress Editor</option>
			        			<option value="checklist">Checklist Text</option>
			        			<option value="image">Image</option>
			        		</select>
			        	</p>
		        		<div class="container">
					        <div class="text-option" id="wpEditor" style="display: block;" >
					        	<label>Enter Your Text:</label>
								<p>
									<?php 
										$args = array(
										    'textarea_rows' => 5,
										    'textarea_name' => 'editor_content',
										    'teeny' => false,
										    'quicktags' => true
										);
										 
										wp_editor( 'This is the default text!', 'content-editor-1', $args );
									?>
								</p>
					        </div>
					        <div class="text-option" id="checklist">
					        	Please choose an image used for Checklist bullet point. 
					        	<div class="row form-group radio_img_list">
				        			<?php for($i=1; $i<=24; $i++){ ?>
				        			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 radio_list">
						    			<img data-id="<?php echo $i; ?>" src="<?php echo TSRCT_CT_IMG ?>/bullet-points/bullet-<?php echo $i; ?>.png" class="img-rounded col-xs-3 col-sm-3 col-md-12 col-lg-12" alt="Bullet <?php echo $i; ?>">
						    			<div class="clear"></div>
							    	</div>
							    	<?php } ?>
							    	<input type="hidden" id="selected_bullet_id" />
				        		</div>
				        		<div class="checklist_text"></div>
				        		<a href="javascript:void(0)" id="checklist-text-add">Add Text</a>
				        		<input type="hidden" id="checklist-count" value="1" />
					        </div>
					        
					        <div class="text-option" id="image">
					        	<input type="hidden" name="heading_picture" id="heading_picture" value="" />
					        	<div class="alignment">
						        	<span class="dashicons dashicons-editor-alignleft"></span>
									<span style="margin-left:10px; margin-right:10px; color:white;"></span>
									<span class="dashicons dashicons-editor-aligncenter"></span>
									<span style="margin-left:10px; margin-right:10px; color:white;"></span>
									<span class="dashicons dashicons-editor-alignright"></span>
									<span style="margin-left:10px; margin-right:10px; color:white;"></span>
								</div>
								<div id="image_parent_container">
						        	<div id="image_container">
								  		<img id="heading_picture_preview" class="heading-picture" src="" />
								  	</div>
								</div>
							  	<a class="btn_heading_picture" name="btn_heading_picture" >Upload Image</a>
					        </div>
				      	</div>
		        	</div>
		        	<div class="modal-footer">
			        	<a class="btn btn-primary insert_content">Insert</a>
			        </div>
		        </div>
		    </div>
		</div>
		<?php
	}
	
	public static function edit_content_modal(){
		?>
		<div class="modal fade" id="editContentModal" role="dialog" style="display: none;">
		    <div class="modal-dialog">
		      	<div class="modal-content">
		        	<div class="modal-header">
		          		<button type="button" class="close" data-dismiss="modal">&times;</button>
		          		<h4 class="modal-title">Content Updation</h4>
		        	</div>
		        	<div class="modal-body">
		        		<div class="container">
					        <div class="text-option" id="wpEditor">
					        	<label>Enter Your Text:</label>
								<p>
									<?php 
										$args = array(
										    'textarea_rows' => 5,
										    'textarea_name' => 'editor_content',
										    'teeny' => false,
										    'quicktags' => true
										);
										 
										wp_editor( 'This is the default text!', 'content-editor-2', $args );
									?>
								</p>
					        </div>
					        <div class="text-option" id="checklist">
					        	Please choose an image used for Checklist bullet point. 
					        	<div class="row form-group radio_img_list">
				        			<?php for($i=1; $i<=24; $i++){ ?>
				        			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 radio_list">
						    			<img data-id="<?php echo $i; ?>" src="<?php echo TSRCT_CT_IMG ?>/bullet-points/bullet-<?php echo $i; ?>.png" class="img-rounded col-xs-3 col-sm-3 col-md-12 col-lg-12" alt="Bullet <?php echo $i; ?>">
						    			<div class="clear"></div>
							    	</div>
							    	<?php } ?>
							    	<input type="hidden" id="selected_bullet_id" name="selected_bullet_id" />
				        		</div>
				        		<div class="checklist_text"></div>
				        		<a href="javascript:void(0)" id="checklist-text-add-on-edit">Add Text</a>
				        		<input type="hidden" id="checklist-count" value="1" />
					        </div>
					        
					        <div class="text-option" id="image">
					        	<input type="hidden" name="heading_picture" id="heading_picture" value="" />
					        	<div class="alignment">
						        	<span class="dashicons dashicons-editor-alignleft"></span>
									<span style="margin-left:10px; margin-right:10px; color:white;"></span>
									<span class="dashicons dashicons-editor-aligncenter"></span>
									<span style="margin-left:10px; margin-right:10px; color:white;"></span>
									<span class="dashicons dashicons-editor-alignright"></span>
									<span style="margin-left:10px; margin-right:10px; color:white;"></span>
								</div>
								<div id="image_parent_container">
						        	<div id="image_container">
								  		<img id="heading_picture_preview" class="heading-picture" src="" />
								  	</div>
								</div>
							  	<a class="btn_heading_picture" name="btn_heading_picture" >Upload New Image</a>
					        </div>
				      	</div>
		        	</div>
		        	<div class="modal-footer">
			        	<a class="btn btn-primary update_content" data-section-id="" data-section-type="">Update The Content</a>
			        </div>
		        </div>
		    </div>
		</div>
		<?php
	}
}?>