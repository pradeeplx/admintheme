<?php

/*WPbakery page builder custom extensions*/
vc_map(array(
	'name' => 'Single feature box',
	'base' => 'single_feature',
	'category' => 'Phadkelabs Extensions',
	'icon' => 'https://cdn0.iconfinder.com/data/icons/cosmo-layout/40/box-512.png',
	'params' => array(
		array(
			'heading' => __('Feature Logo','pillar'),
			'param_name' => 'feature_logo',
			'type' => 'attach_image',
		),
		array(
			'heading' => __('Logo width','pillar'),
			'param_name' => 'feature_logo_width',
			'type' => 'textfield', 
			'description' => __( 'Example: 100px', 'pillar' )
		),
		array(
			'heading' => __('Feature Title','pillar'),
			'param_name' => 'feature_title',
			'type' => 'textfield',
		),
		array(
			'heading' => __('Feature title color','pillar'),
			'param_name' => 'heading_text_color',
			'type' => 'colorpicker',
		),
		array(
			'heading' => __('Feature Title Font Size','pillar'),
			'param_name' => 'feature_title_font_size',
			'type' => 'textfield',
			'description'     => __('Example: 23px','pillar'),
		),
		array(
			'heading' => __('Feature Title Line Height','pillar'),
			'param_name' => 'feature_title_line_height',
			'type' => 'textfield',
			'description'     => __('Example: 25px','pillar'),
		),
		array(
			  'type'        => 'dropdown',
			  'heading'     => __('Feature Title Align'),
			  'param_name'  => 'pillar_caption_text_align',
			 // 'admin_label' => true,
			  'value'       => array(
				'left'   => 'left',
				'center'   => 'center',
				'justify'   => 'justify',
				'right' => 'right',
		)),
		array(
			'heading' => __('<h2 style="color:#0473aa">Feature Details</h2>','pillar'),
			'param_name' => 'content',
			'type' => 'textarea_html',
		),
		array(
			'heading' => __('Background color','pillar'),
			'param_name' => 'background_color',
			'type' => 'colorpicker',
		),

		array(
			'heading' => __('Paragraph Text color','pillar'),
			'param_name' => 'para_graph_text_color',
			'type' => 'colorpicker',
		),
		array(
			'heading' => __('Feature Link','pillar'),
			'param_name' => 'para_feature_link',
			'type' => 'vc_link',
		),
		array(
			'heading' => __('CSS Class','pillar'),
			'param_name' => 'pillar_css_class',
			'type' => 'textfield',
		),
	
	)


));

function pillar_single_feature_shortcode($atts,$content){
	$default = array(
		'feature_title' => '',
		'pillar_caption_text_align' => 'center',
		'feature_title_font_size' => '',
		'feature_title_line_height' => '',
		'background_color' => '#fff',
		'heading_text_color' => '#000',
		'para_graph_text_color' => '#000',
		'feature_logo' =>'https://cdn4.iconfinder.com/data/icons/single-width-stroke/24/oui-icons-37-512.png',
		'feature_logo_width' => '',
		'pillar_css_class' => '',
		'para_feature_link' => esc_url(home_url())
	);
	extract(shortcode_atts($default,$atts));
	
	ob_start();
	?>

	<a href="<?php $url = vc_build_link( $para_feature_link ); echo $url['url']; ?>" style="display:block">
	<div class="single_feature <?php echo $pillar_css_class; ?>" style="background-color: <?php echo $background_color; ?>">
			<div class="feature_logo">
				<img style="width: <?php echo $feature_logo_width; ?>" src="<?php echo wp_get_attachment_url($feature_logo); ?>" alt="" />
			</div>
			<div class="feature_title">
				<h3 style="color: <?php echo $heading_text_color; ?>;text-align: <?php echo $pillar_caption_text_align; ?>;font-size: <?php echo $feature_title_font_size; ?>;line-height: <?php echo $feature_title_line_height; ?>"><?php echo $feature_title; ?></h3>
			</div>
			<div class="feature_details">
				<p style="color: <?php echo $para_graph_text_color; ?>"><?php echo $content; ?></p>
			</div>		
	</div>
	</a>
	
	
	
	
	<?php	
	return ob_get_clean();
}
add_shortcode('single_feature','pillar_single_feature_shortcode');












/*single_service*/

vc_map(array(
	'name' => 'Single Service',
	'base' => 'pillar_single_service',
	'category' => 'Phadkelabs Extensions',
	'icon' => 'https://cdn0.iconfinder.com/data/icons/cosmo-layout/40/box-512.png',
	'params' => array(
		array(
			'heading' => 'Service Title',
			'param_name' => 'service_title',
			'type' => 'textfield',
		),
		array(
			  'type'        => 'dropdown',
			  'heading'     => __('Service Title Align','pillar'),
			  'param_name'  => 'pillar_service_title_align',
			 // 'admin_label' => true,
			  'value'       => array(
				'left'   => 'left',
				'center'   => 'center',
				'justify'   => 'justify',
				'right' => 'right',
		)),
		array(
			'heading' => __('Service Title Color','pillar'),
			'param_name' => 'service_title_color',
			'type' => 'colorpicker',
		),
		array(
			'heading' => __('Service Title Font Size','pillar'),
			'param_name' => 'service_title_font_size',
			'type' => 'textfield',
			'description'     => __('Example: 23px','pillar'),
		),
		array(
			'heading' => __('Service Title Line Height','pillar'),
			'param_name' => 'service_title_line_height',
			'type' => 'textfield',
			'description'     => __('Example: 26px','pillar'),
		),
		array(
			'heading' => 'Service photo',
			'param_name' => 'service_photo',
			'type' => 'attach_image',
		),
		array(
			  'type'        => 'dropdown',
			  'heading'     => __('Text Align'),
			  'param_name'  => 'pillar_text_align',
			 // 'admin_label' => true,
			  'value'       => array(
				'left'   => 'left',
				'center'   => 'center',
				'justify'   => 'justify',
				'right' => 'right',
		)),
		array(
			'heading' => '<h2 style="color:#0473aa">Service Details</h2>',
			'param_name' => 'content',
			'type' => 'textarea_html',
		),
		array(
			'heading' => __('Service Content Line Height','pillar'),
			'param_name' => 'service_content_line_height',
			'type' => 'textfield',
			'description'     => __('Example: 26px','pillar'),
		),
		array(
			'heading' => '<h2 style="color:#0473aa">Service Link</h2>',
			'param_name' => 'pillar_service_link',
			'type' => 'vc_link',
		),
		array(
			'heading' => __('CSS Class','pillar'),
			'param_name' => 'pillar_css_class',
			'type' => 'textfield',
		),
	
	)


));

function single_service_shortcode_func($atts, $content){
	$default = array(
		'service_title' => '',
		'pillar_service_title_align' => 'left',
		'service_title_color' => '#000000',
		'service_title_font_size' => '23px',
		'service_title_line_height' => '26px',
		'service_content_line_height' => '27px',
		'service_photo' => 'https://cdn0.iconfinder.com/data/icons/cosmo-layout/40/box-512.png',
		'pillar_text_align' => 'left',
		'pillar_service_link' => esc_url(home_url()),
		'pillar_css_class' => ''
		
	);
	extract(shortcode_atts($default,$atts));
	
	
	ob_start();
	?>
		<a href="<?php $url = vc_build_link( $pillar_service_link ); echo $url['url']; ?>" style="display:block">
		<div class="single_service <?php echo $pillar_css_class; ?> " style="text-align:<?php echo $pillar_text_align; ?>">
			<div class="service_image">
				<img src="<?php echo wp_get_attachment_url($service_photo); ?>" alt="" />
			</div>
			<div class="service_details">
				<div class="service_title">
					<h2 style="text-align:<?php echo $pillar_service_title_align; ?>;color:<?php echo $service_title_color; ?>;font-size:<?php echo $service_title_font_size; ?>;line-height:<?php echo $service_title_line_height; ?>" ><?php echo $service_title; ?></h2>
				</div>
				<div class="service_description">
					<p style="line-height:<?php echo $service_content_line_height; ?>"><?php echo $content; ?></p>
				</div>
			</div>
		</div>
		</a>
		
	<?php	
	return ob_get_clean();
}
add_shortcode('pillar_single_service','single_service_shortcode_func');




/*single_service v2*/
vc_map(array(
	'name' => __('Single Service V2','pillar'),
	'base' => 'pillar_single_service_v2',
	'category' => 'Phadkelabs Extensions',
	'icon' => 'https://cdn0.iconfinder.com/data/icons/cosmo-layout/40/box-512.png',
	'params' => array(
		array(
			'heading' => __('Service Title','pillar'),
			'param_name' => 'service_title',
			'type' => 'textfield',
		),
		array(
			  'type'        => 'dropdown',
			  'heading'     => __('Service Title Align','pillar'),
			  'param_name'  => 'pillar_service_title_align',
			 // 'admin_label' => true,
			  'value'       => array(
				'left'   => 'left',
				'center'   => 'center',
				'justify'   => 'justify',
				'right' => 'right',
		)),
		array(
			'heading' => __('Service Title Color','pillar'),
			'param_name' => 'service_title_color',
			'type' => 'colorpicker',
		),
		array(
			'heading' => __('Service Title Font Size','pillar'),
			'param_name' => 'service_title_font_size',
			'type' => 'textfield',
			'description'     => __('Example: 23px','pillar'),
		),
		array(
			'heading' => __('Service Title Line Height','pillar'),
			'param_name' => 'service_title_line_height',
			'type' => 'textfield',
			'description'     => __('Example: 26px','pillar'),
		),
		array(
			'heading' => __('Service photo','pillar'),
			'param_name' => 'service_photo',
			'type' => 'attach_image',
		),
		array(
			  'type'        => 'dropdown',
			  'heading'     => __('Service top choice?','pillar'),
			  'param_name'  => 'service_top_choice',
			 // 'admin_label' => true,
			  'value'       => array(
				'no'   => 'no',
				'yes'   => 'yes',
		)),
		array(
			  'type'        => 'dropdown',
			  'heading'     => __('Text Align','pillar'),
			  'param_name'  => 'pillar_text_align',
			 // 'admin_label' => true,
			  'value'       => array(
				'left'   => 'left',
				'center'   => 'center',
				'justify'   => 'justify',
				'right' => 'right',
		)),
		array(
			'heading' => __('<h2 style="color:#0473aa">Service Details</h2>','pillar'),
			'param_name' => 'content',
			'type' => 'textarea_html',
		),
		array(
			'heading' => __('Service Content Line Height','pillar'),
			'param_name' => 'service_content_line_height',
			'type' => 'textfield',
			'description'     => __('Example: 26px','pillar'),
		),
		array(
			'heading' => __('<h2 style="color:#0473aa">Service Link</h2>','pillar'),
			'param_name' => 'pillar_service_link',
			'type' => 'vc_link',
		),
		array(
			'heading' => __('CSS Class','pillar'),
			'param_name' => 'pillar_css_class',
			'type' => 'textfield',
		),
	
	)


));

function single_service_v2_shortcode_func($atts,$content){
	$default = array(
		'service_title' => '',
		'service_top_choice' => 'no',
		'service_photo' => '',
		'pillar_text_align' => 'left',
		'pillar_service_title_align' => 'left',
		'service_title_color' => '#000000',
		'service_title_font_size' => '23px',
		'service_title_line_height' => '26px',
		'service_content_line_height' => '27px',
		'pillar_service_link' => esc_url(home_url('')),
		'pillar_css_class' => ''
		
	);
	extract(shortcode_atts($default,$atts));
	
	
	ob_start();
	?>
		<a href="<?php $url = vc_build_link( $pillar_service_link ); echo $url['url']; ?>" style="display:block">
		<div class="single_service single_service2 <?php echo $pillar_css_class; ?>" style="text-align:<?php echo $pillar_text_align; ?>">
			<?php if($service_top_choice=='yes'): ?><div class="service_top_choice"><h5>top choice</h5></div><?php endif; ?>
			<div class="service_image" style="box-shadow:0 0 0px #fff">
				<?php if($service_photo): ?>
					<img src="<?php echo wp_get_attachment_url($service_photo); ?>" alt="" />
				<?php endif; ?>
			</div>
			<div class="service_details">
				<div class="service_title">
					<h2 style="text-align:<?php echo $pillar_service_title_align; ?>;color:<?php echo $service_title_color; ?>;font-size:<?php echo $service_title_font_size; ?>;line-height:<?php echo $service_title_line_height; ?>"><?php echo $service_title; ?></h2>
				</div>
				<div class="service_description">
					<p style="line-height:<?php echo $service_content_line_height; ?>" ><?php echo $content; ?></p>
				</div>
			</div>
		</div>
		</a>
		
	<?php	
	return ob_get_clean();
}
add_shortcode('pillar_single_service_v2','single_service_v2_shortcode_func');

?>