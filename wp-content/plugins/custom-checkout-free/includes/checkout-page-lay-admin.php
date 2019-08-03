<?php
class Checkout_Page_Layout_Admin
{
	public function __construct()
	{
		add_action( 'init', array($this,'register_customizer'));
		add_action( 'admin_footer', array($this,'include_modal'));
		add_action( 'admin_footer', array($this,'include_font_style'));
		add_action( 'admin_enqueue_scripts', array($this,'register_css_js'), 10, 1 );

		add_action( 'add_meta_boxes', array($this,'add_metaboxes') );
		add_action('save_post', array($this, 'template_save_postdata'));
		add_action('save_post', array($this, 'template_save_postdata2'));

		if(get_posts(array('post_type'=>'tes-cc-template'))){
	        add_filter( 'manage_edit-tes-cc-template_columns' , array($this, 'add_tes_cc_template_pr_column') );
	        add_action( 'manage_tes-cc-template_posts_custom_column' , array($this, 'display_tes_cc_template_pr'), 10, 2 );
	    }

	    add_action( 'admin_menu', array($this,'add_thankyou_menu') );
	    add_action( 'admin_menu', array($this,'add_mini_cart_popup_menu') );
	    add_action( 'admin_menu', array($this,'add_field_settings_menu') );
	    add_action( 'admin_init', array($this,'register_thankyou_option' ) );
	    add_action( 'admin_init', array($this,'register_mini_popup_cart_option' ) );
	    add_action( 'admin_init', array($this,'register_field_settings_option' ) );

	}
	
	public static function display_tes_cc_template_pr( $column, $post_id ){
		global $post;

		if ( $post->post_type != 'tes-cc-template' ) return;
	    if ($column == 'tes_cc_status')
	    {
			?>
			<label class="switch">
			  <input <?php if( get_option('_checkout_page_layout_') == $post_id){ echo 'checked'; } ?> type="checkbox" class="tes_cc_radio" name="tes_cc_temp_status" value="<?php echo $post_id; ?>">
			  <span class="slider"></span>
			</label>
			<span class="loader" id="loader-<?php echo $post_id; ?>">Please wait, operation is in progress...<img src="<?php echo TSRCT_CT_IMG ?>/ajax-loader-2.gif" /></span>

			<?php
	    }
	    if ($column == 'tes_cc_layout')
	    {
	    	$value = get_post_meta( get_the_ID(), '_tes_layout_id',true);
	    	if($value){
		    	?>
		    	<img width="15%" src="<?php echo TSRCT_CT_IMG ?>/layout-<?php echo $value; ?>.png" />
		    	<?php
		    }else{
		    	echo 'None';
		    }
	    }
	}

	public static function add_tes_cc_template_pr_column( $columns ){
		global $post;
		if ( $post->post_type != 'tes-cc-template' ) return;
		return array( 'cb' => '<input type="checkbox" />','title' => __( 'Template Name', 'tesseract-cc' ), 'tes_cc_layout'=> 'Layout' ,'tes_cc_status'=>'Status','date' => 'Date' ) ;
	}

	public static function template_save_postdata2( $post_id ){
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			return $post_id;

		if ( !current_user_can( 'edit_post', $post_id ))
    		return;

		if ( get_post_type( $post_id ) != 'product' )
			return $post_id;

		update_post_meta( $post_id, '_tes_pr_skip_cart',sanitize_text_field($_POST['pr_skip_cart']));
		update_post_meta( $post_id, '_tes_pr_button_text',sanitize_text_field($_POST['pr_button_text']));
	}
	public static function template_save_postdata( $post_id ){

		// if ( !isset( $_POST['tes_layout_template_meta_box'] ) || !wp_verify_nonce( $_POST['tes_layout_template_meta_box'], basename( __FILE__ ) ) ){
		// 	return;
		// }

		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			return $post_id;

		if ( !current_user_can( 'edit_post', $post_id ))
    		return;

		if ( get_post_type( $post_id ) != 'tes-cc-template' )
			return $post_id;
		update_post_meta( $post_id, '_tes_layout_id',1);
		update_post_meta( $post_id, '_tes_layout_typo',sanitize_text_field($_POST['typo']));
		update_post_meta( $post_id, '_tes_layout_content_',wp_kses_post($_POST['main_container']));
		update_post_meta( $post_id, '_tes_layout_section_heading',sanitize_text_field($_POST['section_heading']));

		update_post_meta( $post_id, '_tes_layout_hf_header_img',sanitize_text_field($_POST['_tes_layout_hf_header_img']));
		update_post_meta( $post_id, '_tes_layout_hf_header_img_alignment',sanitize_text_field($_POST['_tes_layout_hf_header_img_alignment']));
		update_post_meta( $post_id, '_tes_layout_hf_header_text',sanitize_text_field($_POST['_tes_layout_hf_header_text']));
		update_post_meta( $post_id, '_tes_layout_hf_header_text_alignment',sanitize_text_field($_POST['_tes_layout_hf_header_text_alignment']));
		update_post_meta( $post_id, '_tes_layout_hf_header_status',sanitize_text_field($_POST['_tes_layout_hf_header_status']));


		update_post_meta( $post_id, '_tes_layout_layoutContent_odersummary',sanitize_text_field($_POST['layoutContent_odersummary']));
		//delete_user_meta( get_current_user_id(), '_tes_temp_lay_id_' );
	}

	public static function add_metaboxes(){
		add_meta_box(
	        'check-page-lay-status',
	        __( 'Status', 'tes-cc' ),
	        array('Checkout_Page_Layout_Content', 'status_column'),
	        'tes-cc-template',
	        'side',
	        'high'
	    );

		add_meta_box(
	        'check-page-lay-order-summary',
	        __( 'Order Summary Block', 'tes-cc' ),
	        array('Checkout_Page_Layout_Content', 'content_column'),
	        'tes-cc-template',
	        'side'
	    );

		// add_meta_box(
	 //        'check-page-lay',
	 //        __( 'Layout 100', 'tes-cc' ),
	 //        array('Checkout_Page_Layout_Admin', 'selected_layout_callback'),
	 //        'tes-cc-template',
	 //        'side',
	 //        'high'
	 //    );

	    add_meta_box(
	        'che-page-lay-typo',
	        __( 'Typography / Color', 'tes-cc' ),
	        array('Checkout_Page_Layout_Typography', 'typography_init'),
	        'tes-cc-template',
	        'side'
	    );

		add_meta_box(
	        'che-page-lay-content',
	        __( 'Sidebar Content', 'tes-cc' ),
	        array('Checkout_Page_Layout_Content', 'content_init'),
	        'tes-cc-template'
	    );

	    add_meta_box(
	        'che-page-lay-header-footer',
	        __( 'Header Settings', 'tes-cc' ),
	        array('Checkout_Page_Layout_Header_Footer', 'header_content_init'),
	        'tes-cc-template',
	        'side'
	    );
	    add_meta_box(
	        'che-page-lay-header-footer-2',
	        __( 'Footer Settings', 'tes-cc' ),
	        array('Checkout_Page_Layout_Header_Footer', 'footer_content_init'),
	        'tes-cc-template',
	        'side'
	    );
	    add_meta_box(
	        'che-page-lay-skip-cart',
	        __( 'Skip Cart', 'tes-cc' ),
	        array('Checkout_Page_Layout_Admin', 'skip_cart'),
	        'product',
	        'side'
	    );

	    add_meta_box(
	        'check-page-lay-20',
	        __( 'Layout', 'tes-cc' ),
	        array('Checkout_Page_Layout_Admin', 'selected_layout_callback'),
	        'tes-cc-template',
	        'side',
	        'high'
	    );


	    add_meta_box(
	        'che-page-lay-section-heading',
	        __( 'Section Heading', 'tes-cc' ),
	        array('Checkout_Page_Layout_Admin', 'section_heading_init'),
	        'tes-cc-template',
	        'side'
	    );
	}

	public static function section_heading_init(){
		global $post;
		$section_heading = get_post_meta( get_the_ID(), '_tes_layout_section_heading',true);
		$section_bck_color = isset($section_heading['bck_color']) ? $section_heading['bck_color'] : '#000';
		$section_txt_color = isset($section_heading['txt_color']) ? $section_heading['txt_color'] : '#fff';
		?>
		<p>Please Note: Below settings are only effected on layout "Bold"</p>
		<p>
			<label>Background Color: <a href="javascript:void(0);" data-placement="right" data-toggle="tooltip" title="Applied to background of each section header in layout Bold"><img src="<?php echo TSRCT_CT_IMG ?>/help.png" /></a></label>
			<input type="text" name="section_heading[bck_color]" class="button_color" value="<?php echo $section_bck_color;?>">
		</p>
		<p>
			<label>Text Color: <a href="javascript:void(0);" data-placement="right" data-toggle="tooltip" title="Applied to text of each section header in layout Bold"><img src="<?php echo TSRCT_CT_IMG ?>/help.png" /></a></label>
			<input type="text" name="section_heading[txt_color]" class="button_color" value="<?php echo $section_txt_color;?>">
		</p>

		<?php
	}

	public static function skip_cart(){
		global $post;
		$pr_skip_cart = get_post_meta(get_the_ID(),'_tes_pr_skip_cart',true) ? get_post_meta(get_the_ID(),'_tes_pr_skip_cart',true) : 'disable';

		$pr_button_text = get_post_meta(get_the_ID(),'_tes_pr_button_text',true) ? get_post_meta(get_the_ID(),'_tes_pr_button_text',true) : '';
		?>
		<p>
			<select style="width: 100%;" name="pr_skip_cart">
				<option value="enable" <?php if($pr_skip_cart == 'enable'){ echo 'selected'; } ?>>Enable</option>
				<option value="disable" <?php if($pr_skip_cart == 'disable'){ echo 'selected'; } ?>>Disable</option>
			</select>
		</p>
		<label><b>Add to Cart Button Text</b></label>
		<p>
			<input type="text" name="pr_button_text" style="width: 100%;height: 28px;" value="<?php echo $pr_button_text; ?>"/>
		</p>
		<p class="howto">The redirection only applicable on product details page.</p>
		<p class="howto">Note: This is a Custom Checkout Free plugin feature.</p>
		<?php
	}

	public static function selected_layout_callback(){

	    $value = get_post_meta( get_the_ID(), '_tes_layout_id',true) ? get_post_meta( get_the_ID(), '_tes_layout_id',true) :get_user_meta(get_current_user_id(), '_tes_temp_lay_id_', true );
	    wp_nonce_field( basename( __FILE__ ), 'tes_layout_template_meta_box' );
	   ?>
	    <p id="edit-layout">

	    	<?php if($value){ ?>
	    		Edit Layout
	    	<span id="selected-layout-img"><img width="30%" src="<?php echo TSRCT_CT_IMG ?>/layout-<?php echo $value; ?>.png" /></span>
	    	<?php }else{
	    		echo 'Click here, <span style="text-decoration:none;>to select layout and publish. Don\'t forget to make it live.</span>"';
	    	 } ?>
	    </p>
	    
	    <div class="modal fade" id="tesLayoutModalChange" role="dialog">
		    <div class="modal-dialog">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title">Change Layout Style</h4>
		        </div>
		        <div class="modal-body">
		          	<div class="row form-group product-chooser">
		          		<span style="font-size: 20px;font-weight: 200;display: block;text-align: center;padding: 20px;">Accordian Layouts</span>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item selected">

				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-1.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Clean">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Clean</span>
				    				<input type="radio" name="edit_template_layout_id" value="1" checked=checked >
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item <?php if($value==2){echo "selected";} ?>">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-2.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Modern Layout">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Modern</span>
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item <?php if($value==3){echo "selected";} ?>">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-3.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Bold">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Bold</span>
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<span style="font-size: 20px;font-weight: 200;display: block;text-align: center;padding: 20px;">Tab Layouts</span>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item <?php if($value==4){echo "selected";} ?>">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-4.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Clean">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Clean</span>
				    				<input type="radio" name="edit_template_layout_id" value="4">
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-5.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Modern">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Modern</span>
				    				<input type="radio" name="edit_template_layout_id" value="5">
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-6.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Bold">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Bold</span>
				    				<input type="radio" class="temp-lay-cls" name="edit_template_layout_id" value="6">
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<span style="font-size: 20px;font-weight: 200;display: block;text-align: center;padding: 20px;">Full Page Layouts</span>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-7.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Clean">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Clean</span>
				    				<input type="radio" name="edit_template_layout_id" value="7" >
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-8.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Modern">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Modern</span>
				    				<input type="radio" name="edit_template_layout_id" value="8">
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-9.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Bold">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Bold</span>
				    				<input type="radio" class="temp-lay-cls" name="edit_template_layout_id" value="9">
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    </div>
		        </div>
		        <div class="modal-footer">
		        	
		        	<button type="button" class="btn btn-primary btn-update">Update</button>
		        </div>
		      </div>
		    </div>
		</div>
	   <?php
	}

	public static function register_customizer()
    {
		$labels = array(
			'name'               => _x( 'Custom Checkout', 'tes-cc'),
			'singular_name'      => _x( 'Checkout Templates', 'tes-cc' ),
			'add_new'            => _x( 'Add New Checkout Template', 'tes-cc' ),
			'add_new_item'       => __( 'Add New Checkout Template' ),
			'edit_item'          => __( 'Edit Checkout Template', 'tes-cc' ),
			'new_item'           => __( 'New Checkout Template', 'tes-cc' ),
			'all_items'          => __( 'All Checkout Templates', 'tes-cc' ),
			'search_items'       => __( 'Search checkout temaplate', 'tes-cc' ),
			'not_found'          => __( 'No temaplate found', 'tes-cc' ),
			'not_found_in_trash' => __( 'No temaplate found in the trash', 'tes-cc' ),
			'parent_item_colon'  => '',

		);
		$args = array(
			'labels'      => $labels,
			'public'  => false,
			'show_ui' => true,
			'has_archive' => true,
			'publicly_queryable'  => true,
			'with_front' => false,
			'menu_icon'		=> 'dashicons-layout',
			'supports'           => array( 'title')
		);
		register_post_type( 'tes-cc-template', $args );

    }

    public static function include_modal()
    {
    	$ajax_nonce = wp_create_nonce( "tes-template-layout-nonce" );

    	?>
    	
    	<div class="modal fade" id="tesLayoutModal" role="dialog" style="display: none;">
		    <div class="modal-dialog">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title">Choose a Layout</h4>
		        </div>
		        <div class="modal-body">
		          	<div class="row form-group product-chooser">
		          		<span style="font-size: 20px;font-weight: 200;display: block;text-align: center;padding: 20px;">Accordian Layouts</span>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item one-item">
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-1.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Clean">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Clean</span>
				    				<input type="radio" name="template_layout_id" value="1">
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-2.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Modern Layout">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Modern</span>
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-3.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Bold">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Bold</span>
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<span style="font-size: 20px;font-weight: 200;display: block;text-align: center;padding: 20px;">Tab Layouts</span>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-4.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Clean">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Clean</span>
				    				<input type="radio" name="template_layout_id" value="4">
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-5.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Modern">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Modern</span>
				    				<input type="radio" name="template_layout_id" value="5">
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-6.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Bold">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Bold</span>
				    				<input type="radio" class="temp-lay-cls" name="template_layout_id" value="6">
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<span style="font-size: 20px;font-weight: 200;display: block;text-align: center;padding: 20px;">Full Page Layouts</span>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-7.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Clean">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Clean</span>
				    				<input type="radio" name="edit_template_layout_id" value="7">
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-8.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Modern">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Modern</span>
				    				<input type="radio" name="edit_template_layout_id" value="8">
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 temp-lay-cls">
				    		<div class="product-chooser-item">
				    			<span class="pro-adv alt">
					    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon2.png';?>"/>To access this layout style you will need to
					    			<a href="https://customcheckoutplugin.com/" target="_blank;">purchase the Pro version.</a>
					    		</span>
				    			<img src="<?php echo TSRCT_CT_IMG ?>/layout-9.png" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Bold">
				                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
				    				<span class="title">Bold</span>
				    				<input type="radio" class="temp-lay-cls" name="edit_template_layout_id" value="9">
				    			</div>
				    			<div class="clear"></div>
				    		</div>
				    	</div>
				    </div>
		        </div>
		        <div class="modal-footer">
		        	<button type="button" class="btn btn-primary btn-proceed" disabled>Proceed</button>
		        	<span style="text-align: center;float: left;">You can change the layout style later on.</span>
		        </div>
		      </div>
		    </div>
		</div>
    	<?php

    }
    public static function register_css_js( $hook )
    {
    	global $post;
    	if ( $hook == 'post-new.php' || $hook == 'edit.php' || $hook == 'post.php' ) {
	    	wp_enqueue_style( 'tesseract-ct-admin-css1',TSRCT_CT_CSS.'/bootstrap.min.css', array(), '1.1' );
	    	wp_enqueue_media();

	    	wp_register_script('tesseract-ct-admin-js1', TSRCT_CT_JS.'/tes-ct-admin.js', array('jquery'),'1.1', true);
	    	wp_enqueue_script('tesseract-ct-admin-js1');
	    	$wp_custom_js_url = array( 'TSRCT_CT_IMG_JS_URL' => TSRCT_CT_IMG, 'SITE_URL' => site_url() );
			wp_localize_script( 'tesseract-ct-admin-js1', 'wp_custom_js_url', $wp_custom_js_url );

			wp_register_script('tesseract-ct-admin-js2', TSRCT_CT_JS.'/bootstrap.min.js', array('jquery'),'1.1', true);
			wp_enqueue_script('tesseract-ct-admin-js2');
	    }
	    wp_enqueue_style( 'wp-color-picker' );
		wp_register_script( 'tesseract-ct-admin-js3', TSRCT_CT_JS.'/jquery.color.js', array( 'jquery', 'wp-color-picker' ), '', true  );
		wp_enqueue_script('tesseract-ct-admin-js3');

		wp_enqueue_style( 'tesseract-ct-admin-scroll-css1',TSRCT_CT_CSS.'/jquery.mCustomScrollbar.min.css', array(), '1.1' );
		wp_enqueue_style( 'tesseract-ct-admin-custom-css3',TSRCT_CT_CSS.'/custom-checkout-admin.css', array(), '1.1' );

		wp_register_script( 'tesseract-ct-admin-jscroll-js4', TSRCT_CT_JS.'/jquery.mCustomScrollbar.concat.min.js',array('jquery'),'',true );
		wp_enqueue_script('tesseract-ct-admin-jscroll-js4');
	}
	public static function register_thankyou_option(){
		register_setting( 'custom-thankyou-group', 'custom_checkout_page' );
	}

	public static function register_mini_popup_cart_option(){
		register_setting( 'custom-checkout-mini-popup-cart-group', 'custom-checkout-status' );
	}

	public static function add_thankyou_menu(){
		add_submenu_page(
        'edit.php?post_type=tes-cc-template',
        'Order Confirmation Page',
        'Order Confirmation Page',
        'manage_options',
        'custom-checkout-order-confirmation-page',
        array('Checkout_Page_Layout_Admin','custom_thank_you_callback') );
	}

	public static function add_mini_cart_popup_menu(){
		add_submenu_page(
        'edit.php?post_type=tes-cc-template',
        'Mini Cart Popup',
        'Mini Cart Popup',
        'manage_options',
        'mini-cart-popup-page',
        array('Checkout_Page_Layout_Admin','mini_cart_popup_callback') );
	}
	public static function add_field_settings_menu(){
		add_submenu_page(
        'edit.php?post_type=tes-cc-template',
        'Field Settings',
        'Field Settings',
        'manage_options',
        'field-settings-page',
        array('Checkout_Page_Layout_Admin','field_settings_callback') );
	}

	public static function register_field_settings_option(){

		register_setting( 'custom-checkout-field-settings-group', 'custom-checkout-privacy-policy-text' );
		register_setting( 'custom-checkout-field-settings-group', 'custom-checkout-terms-conditions-text' );

	}

	public static function field_settings_callback(){
		$privacy_text = 'Lorem ipsum dolor sit amet, elitr senserit instructior eos at. Ei mutat dicta mel. In eos elit tantas conceptam. No nec etiam virtute. Mea case molestie ea, gubergren vulputate vix ut, sale phaedrum no nam.';

		$terms_text = 'Lorem ipsum dolor sit amet, elitr senserit instructior eos at. ';

		?>
		<div class="wrap">
			<h1>Field Settings</h1>
			<span class="pro-adv">
    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon.png';?>"/>To access this feature you will need to
    			<a href="https://customcheckoutplugin.com" target="_blank;">purchase the Pro version.</a>
    		</span>
			<p style="font-style: italic; font-size: 15px;">These options are help you to change the appearance of the WooCommerce checkout.</p>

			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'custom-checkout-field-settings-group' ); ?>
	    		<?php do_settings_sections( 'custom-checkout-field-settings-group' ); ?>
	    		<table class="form-table">
	    			<tbody>
	    				<tr>
	    					<th style="width: 30%;">Company name field</th>
	    					<td>
	    						<select name="custom-checkout-company-status">
	    							<option value="disable">Disable</option>
	    							<option value="enable">Enable</option>
	    						</select>
	    					</td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Address line 2 field</th>
	    					<td>
		    					<select name="custom-checkout-address2-status">
	    							<option value="disable">Disable</option>
	    							<option value="enable">Enable</option>
	    						</select>
	    					</td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Phone Field</th>
	    					<td>
	    						<select name="custom-checkout-phone-status">
	    							<option value="disable">Disable</option>
	    							<option value="enable">Enable</option>
	    						</select>
	    					</td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Additional Information Block</th>
	    					<td>
	    						<select name="custom-checkout-adtnl-info-status">
	    							<option value="disable">Disable</option>
	    							<option value="enable">Enable</option>
	    						</select>
	    					</td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Highlight required fields with an asterisk</th>
	    					<td>
	    						<select name="custom-checkout-highlight-asterik">
	    							<option value="disable">Disable</option>
	    							<option value="enable">Enable</option>
	    						</select>
	    					</td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Privacy Policy text on checkout page</th>
	    					<td>
	    						<select name="custom-checkout-privacy-txt-show">
	    							<option value="disable">Disable</option>
	    							<option value="enable">Enable</option>

	    						</select>
	    					</td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Terms and Conditions text on checkout page</th>
	    					<td>
	    						<select name="custom-checkout-terms-txt-show">
	    							<option value="disable">Disable</option>
	    							<option value="enable">Enable</option>
	    						</select>
	    						<p>Note: If you choose "Disable", the Terms and Conditions checkbox on Checkout page will be auto checked and hided as well.</p>
	    					</td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Privacy Policy Page</th>
	    					<td>
	    						<select name="custom-checkout-privacy-policy-page">
	    							<option>Select page</option>
	    							<?php
	    								$args = array(
											'sort_order' => 'asc',
											'sort_column' => 'post_title',
											'post_type' => 'page',
											'post_status' => 'publish'
										);
										$pages = get_pages($args);
										foreach ( $pages as $page ) {
											$option = '<option value="' . $page->ID . '">';
											$option .= $page->post_title;
											$option .= '</option>';
											echo $option;
										}
	    							?>
	    						</select>
	    					</td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Terms and Conditions Page</th>
	    					<td>
	    						<select name="custom-checkout-terms-conditions-page">
	    							<option>Select page</option>
	    							<?php
	    								$args = array(
											'sort_order' => 'asc',
											'sort_column' => 'post_title',
											'post_type' => 'page',
											'post_status' => 'publish'
										);
										$pages = get_pages($args);
										foreach ( $pages as $page ) {

											$option = '<option value="' . $page->ID . '">';
											$option .= $page->post_title;
											$option .= '</option>';
											echo $option;
										}
	    							?>
	    						</select>
	    					</td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Privacy Policy Text</th>
	    					<td>
	    						<textarea style="width:  50%;height: 100px;" name="custom-checkout-privacy-policy-text"> <?php echo $privacy_text; ?></textarea>
	    						<p>[cc_privacy_policy]: Privacy Policy page link shortcode.</p>
	    					</td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Terms and conditions Text</th>
	    					<td>
	    						<textarea style="width:  50%;height: 100px;" name="custom-checkout-terms-conditions-text"> <?php echo $terms_text; ?></textarea>
	    						<p>[cc_terms_condition]: Terms and Conditions page link shortcode.</p>
	    					</td>
	    				</tr>

	    			</tbody>
	    		</table>
	    		<p style="font-style: italic; font-size: 15px;">Note: These options are may be ovrride your current settings of WooCommerce. If you have any other custom mini cart plugin or theme options within your website then you might need to disable those settings first so that this can work properly.</p>
	    		<span class="pro-adv">
	    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon.png';?>"/>To access this feature you will need to
	    			<a href="https://customcheckoutplugin.com" target="_blank;">purchase the Pro version.</a>
	    		</span>
	    		<?php submit_button(); ?>

	    	</form>
	    </div>
		<?php
	}

	public static function mini_cart_popup_callback(){
		$status  = 'disable';
		?>
		<div class="wrap">
			<h1>Mini Cart Popup Settings</h1>
			<span class="pro-adv">
    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon.png';?>"/>To access this feature you will need to
    			<a href="https://customcheckoutplugin.com" target="_blank;">purchase the Pro version.</a>
    		</span>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'custom-checkout-mini-popup-cart-group' ); ?>
	    		<?php do_settings_sections( 'custom-checkout-mini-popup-cart-group' ); ?>
	    		<table class="form-table">
	    			<tbody>
	    				<tr>
	    					<th style="width: 30%;">Status</th>
	    					<td>
	    						<select name="custom-checkout-status">
	    							<option value="disable">Disable</option>
	    							<option value="enable">Enable</option>
	    						</select>
	    					</td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Background Color</th>
	    					<td><input type="text" class="color-field" /></td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">General Text Color</th>
	    					<td><input type="text" class="color-field" /></td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Show Cart Product Count</th>
	    					<td>
	    						<select name="custom-checkout-pr-count">
	    							<option value="yes">Yes</option>
	    							<option value="no">No</option>
	    						</select>
	    					</td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">Visual time (in second)</th>
	    					<td><input type="number" value="6" /></td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">'View Cart' button background color</th>
	    					<td><input type="text" class="color-field" /></td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">'View Cart' button text color</th>
	    					<td><input type="text" class="color-field" /></td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">'View Cart' button hover background color</th>
	    					<td><input type="text" class="color-field" /></td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">'View Cart' button hover text color</th>
	    					<td><input type="text" class="color-field" /></td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">'Checkout' button background color</th>
	    					<td><input type="text" class="color-field" /></td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">'Checkout' button text color</th>
	    					<td><input type="text" class="color-field" /></td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">'Checkout' button hover background color</th>
	    					<td><input type="text" class="color-field" /></td>
	    				</tr>
	    				<tr>
	    					<th style="width: 30%;">'Checkout' button hover text color</th>
	    					<td><input type="text" class="color-field" /></td>
	    				</tr>


	    			</tbody>
	    		</table>
	    		<p style="font-style: italic; font-size: 15px;">Note: Make sure to "Enable AJAX add to cart buttons on archives" within your Woocommerce product settings.</p>

	    		<span class="pro-adv">
	    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon.png';?>"/>To access this feature you will need to
	    			<a href="https://customcheckoutplugin.com" target="_blank;">purchase the Pro version.</a>
	    		</span>


	    		<?php submit_button(); ?>

	    		<a class="preview button" href="javascript:void(0);" target="wp-preview-3065" id="post-preview">Preview<span class="screen-reader-text"> (opens in a new window)</span></a>
	    	</form>
	    </div>
		<?php
	}

	public static function custom_thank_you_callback(){

		$selected_page = get_option("custom_checkout_page");
		$args = array(
			'sort_order' => 'asc',
			'sort_column' => 'post_title',
			'post_type' => 'page',
			'post_status' => 'publish'
		);
		$pages = get_pages($args);
		?>
		

		<form method="post" action="options.php">
			<?php settings_fields( 'custom-thankyou-group' ); ?>
    		<?php do_settings_sections( 'custom-thankyou-group' ); ?>
			<h2>Which page do you want to use as Thank You / Order Confirmation page?</h2>
			<p>
				<select class="chosen" style="width:500px;" name="custom_checkout_page" id="custom_checkout_page">
					<option value="0">WooCommerce Default Page</option>
					<?php
						foreach ( $pages as $page ) {
							?>
							<option <?php if( $page->ID == $selected_page ){ echo 'selected=selected';} ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
							<?php
						}
					?>
				</select>
			</p>
			<span style="margin: 20px;"></span>
			<h2>Use below shortcode's on custom Thank You / Order Confirmation page </h2>
			<div class="box effect2">
	        	<h3><u>Shortcodes</u></h3>
	        	<div style="margin: 20px;">
		        	<p><b>[CUSTOM_CHECKEOUT_ORDER_ID]</b> - Used for showing Order ID.</p>
		        	<p><b>[CUSTOM_CHECKEOUT_ORDER_SUMMARY]</b> - Used for showing Order Summary.</p>
		        	<p><b>[CUSTOM_CHECKEOUT_ORDER_DETAILS]</b> - Used for showing Order Details.</p>
		        	<p><b>[CUSTOM_CHECKEOUT_ORDER_BILLING_DETAILS]</b> - Used for showing Order Billing Details.</p>
		        	<p><b>[CUSTOM_CHECKEOUT_ORDER_SHIPPING_DETAILS]</b> - Used for showing Order Shipping Details.</p>
		        </div>
		        <span class="pro-adv">
	    			<img src="<?php echo TSRCT_CT_IMG.'/Pro-Icon.png';?>"/>To access this shortcode feature you will need to
	    			<a href="https://customcheckoutplugin.com" target="_blank;">purchase the Pro version.</a>
	    		</span>
	    	</div>
	    	<?php submit_button(); ?>

	    </form>

		<?php
	}
	public static function include_font_style(){

		$typo = get_post_meta( get_the_ID(), '_tes_layout_typo',true);
		if(!is_array($typo)){
			$typo['font_family'] = 'Default Layout Font';
		}

		if( $typo['font_family'] == 'Default Layout Font' ){

			$value = get_post_meta( get_the_ID(), '_tes_layout_id',true) ? get_post_meta( get_the_ID(), '_tes_layout_id',true) :get_user_meta(get_current_user_id(), '_tes_temp_lay_id_', true );

			if( $value == 2 || $value == 3 ){
				$font_family = 'Open Sans';
			}else{
				$font_family = 'PT Serif';
			}
		}else{
			$font_family = $typo['font_family'];
		}
		echo '<link href="https://fonts.googleapis.com/css?family='.$font_family.'" rel="stylesheet">';
		?>

		<style type="text/css">
    		#generated-content .sortable-item .only-content{
    			font-family: <?php echo $font_family; ?>;
    		}
    	</style>

		<?php
	}
}?>
