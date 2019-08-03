(function( $ ) {
 
   	jQuery('a[href^="post-new.php?post_type=tes-cc-template"]').each(function(){ 
        jQuery(this).attr("id", 'add-new-tes-template');
    });
    jQuery('#add-new-tes-template').on('click',function(){
    	jQuery('#tesLayoutModal').modal('show'); return false;
    });
    jQuery(".post-type-tes-cc-template .page-title-action").on('click',function(){
    	jQuery('#tesLayoutModal').modal('show'); return false;
    });
    jQuery(".temp-lay-cls").on('click',function(){
    	jQuery(".btn-proceed").removeAttr("disabled");
    });
    jQuery("#edit-layout").on('click',function(){
    	jQuery('#tesLayoutModalChange').modal('show');
    });
    jQuery(".btn-update").on('click',function(){
    	jQuery('#tesLayoutModalChange').modal('hide');
    	jQuery("#publish").click();
    });

    jQuery('div.product-chooser').not('.disabled').find('div.product-chooser-item').on('click', function(){
        jQuery(".one-item").parent().parent().find('div.product-chooser-item').removeClass('selected');
        jQuery(".one-item").addClass('selected');
        jQuery(".one-item").find('input[type="radio"]').prop("checked", true);
        
    });
    
	
    // jQuery("#add_upload_image").click(function() {
    //     openmediapopup();
    //     return false;
    // });

    
    
    jQuery("#tes-cc-upload-header-img").click(function(){
        header_openmediapopup();
        return false;
    });

    function header_openmediapopup(){
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Set Image'
            },
            multiple: false
        });
        

        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').toJSON();
            var url = attachment[0].url;
            jQuery("#header-upload-img").html('<img style="width:50%;" src="'+url+'" >');
            jQuery("#header_img_url").val( url );
        });
        custom_uploader.open();
    }

    jQuery("#tes-cc-upload-footer-img").click(function(){
        footer_openmediapopup();
        return false;
    });

    function footer_openmediapopup(){
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Set Image'
            },
            multiple: false
        });
        

        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').toJSON();
            var url = attachment[0].url;
            jQuery("#footer-upload-img").html('<img style="width:50%;" src="'+url+'" >');
            jQuery("#footer_img_url").val( url );
        });
        custom_uploader.open();
    }

    jQuery(function() {

        

        jQuery(".btn_heading_picture").on("click", function(e) {

            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Set Image'
                },
                multiple: false
            });
            

            custom_uploader.on('select', function() {
                attachment = custom_uploader.state().get('selection').toJSON();
                var url = attachment[0].url;
                jQuery("#heading_picture").val( url );
                jQuery(".heading-picture").prop("src", url);
                jQuery("#image_container").css("text-align", 'left');           
                jQuery("#image .alignment").show();
            });
            custom_uploader.open();
            

        });
    });
    jQuery("#publish").on('click',function(){
        var value = jQuery("#generated-content").html();
        jQuery("#main_container").val(value);
        
    });

})( jQuery );
jQuery(document).ready(function(){
    jQuery('#checkout_desc_pr_data_tab .sortable-list').sortable({
        connectWith: '#checkout_desc_pr_data_tab .sortable-list',
        placeholder: 'placeholder',
    });
});
jQuery(document).ready(function(){
    jQuery('.content_generation .sortable-list').sortable({
        connectWith: '.content_generation .sortable-list',
        placeholder: 'placeholder',
    });
});

jQuery(".add_new_content").on('click',function(){
    reset_modal();
    jQuery('#contentModal').modal('show');
});

jQuery("#contentModal .radio_img_list img").on('click',function(){
    jQuery("#contentModal .radio_img_list img").removeClass('active');
    jQuery(this).addClass('active');
    var id = jQuery(this).attr('data-id');
    jQuery("#contentModal #selected_bullet_id").val(id);
    jQuery("#contentModal .bullet_img_modal img").attr('src',jQuery(this).attr('src'));
});

jQuery("#content_type").on('change',function(){
    jQuery(".text-option").hide();
    var id =  jQuery(this).val();
    jQuery("#"+id).show();
});

jQuery("#checklist-text-add").on('click',function(){
    var htmlValue = '';
    var count = jQuery("#checklist-count").val();
    var bullet_img = jQuery(".radio_list img.active").attr('src');
    htmlValue += '<span class="checklist-span" id="area-'+count+'"><div class="bullet_img_modal"><img src="'+bullet_img+'" /></div><textarea class="checklist_point_text">Default Text</textarea><img src="'+wp_custom_js_url.TSRCT_CT_IMG_JS_URL+'/delete.png" class="delete" onclick="delete_checklist_test('+count+')" /></span>';
    jQuery("#contentModal .checklist_text").append(htmlValue);
    count = parseInt(count) + 1;
    jQuery("#checklist-count").val(count);
});

function delete_checklist_test(count){
    jQuery("#area-"+count).remove();
}

jQuery(".insert_content").on('click',function(){
    var content_sec_no = jQuery("#content_sec_no").val();
    var html = '';
    var type = jQuery("#content_type").val();

    html += '<section id="sec-'+content_sec_no+'" class="sortable-item">';
    
    switch ( type ) { 
        case 'wpEditor':
            html += '<div class="only-content">'; 
            html += tinyMCE.activeEditor.getContent();
            html += '</div>';
            break;
        case 'checklist':
            var bullet_id = jQuery("#selected_bullet_id").val();
            html += '<div class="only-content" data-bullet-id="'+bullet_id+'">';
            html += '<ul class="checklist_content">';
            jQuery("#contentModal .checklist_point_text").each(function(){
                html += '<li><img src="'+wp_custom_js_url.TSRCT_CT_IMG_JS_URL+'/bullet-points/bullet-'+bullet_id+'.png">';
                html += this.value+'</li>';
            })
            html += '</ul>';
            //html += '<input type="hidden" id="selected_radio_img_id" value="'+bullet_id+'">';
            html += '</div>';
            break;
        case 'image': 
            var get_parent_css = jQuery("#image_container").css('text-align');
            var get_src = jQuery("#image_parent_container img").attr('src');
            html += '<div class="only-content image-'+get_parent_css+'">';
            html += '<span><img src="'+get_src+'"></span>';
            html += '</div>';
            break;      
    }
    
    
    html += '<div class="section-previewer">';
    html += '<span class="dashicons dashicons-no"></span>';
    html += '<span style="margin-left:10px; margin-right:10px; color:white;"></span>'
    html += '<span data-section-id="'+content_sec_no+'" data-edit="'+type+'" class="dashicons dashicons-edit"></span>';
    html += '<span style="margin-left:10px; margin-right:10px; color:white;"></span>'
    html += '<span class="dashicons dashicons-move"></span>';
    html += '</div>';   
    
    html += '</section>';
    jQuery(".sortable-list").append(html);
    jQuery(".section-previewer .dashicons-no").on('click',function(){
        if(confirm("Are you sure to delete this element?")){
            jQuery(this).parents('section').remove();
        }
        else{
            return false;
        }
    });
    
    content_sec_no = parseInt(content_sec_no) + 1;
    jQuery("#content_sec_no").val(content_sec_no);
    jQuery('#contentModal').modal('hide');
    edit_section();
});
function reset_modal(){
    jQuery("#contentModal #content_type").val('wpEditor');
    
    jQuery("#contentModal .container .text-option").hide();
    tinyMCE.get('content-editor-1').setContent('This is the default text!');
    jQuery("#contentModal .container #wpEditor").show();
    jQuery("#contentModal #content-editor-1").html('This is the default text!');
    jQuery("#contentModal .radio_img_list img").removeClass('active');
    jQuery("#contentModal .checklist-span").remove();
    jQuery("#contentModal #heading_picture_preview").attr('src','');
}
jQuery("#image .dashicons-editor-alignleft").on('click',function(){
    jQuery("#image_container").css('text-align','left');
    jQuery("#image .dashicons").removeClass("active")
    jQuery(this).addClass('active');
});

jQuery("#image .dashicons-editor-alignright").on('click',function(){
    jQuery("#image_container").css('text-align','right');
    jQuery("#image .dashicons").removeClass("active")
    jQuery(this).addClass('active');
});

jQuery("#image .dashicons-editor-aligncenter").on('click',function(){
    jQuery("#image_container").css('text-align','center');
    jQuery("#image .dashicons").removeClass("active")
    jQuery(this).addClass('active');
});

jQuery(".section-previewer .dashicons-no").on('click',function(){
    if(confirm("Are you sure to delete this element?")){
        jQuery(this).parents('section').remove();
    }
    else{
        return false;
    }
});

edit_section();
function edit_section(){
    jQuery(".section-previewer .dashicons-edit").on('click',function(){
        var edit_type = jQuery(this).attr('data-edit');
        
        jQuery("#editContentModal .text-option").hide();
        jQuery("#editContentModal #"+edit_type).show();
        
        if( edit_type == 'wpEditor' ){
            var editFrame = jQuery("#content-editor-2_ifr").contents().find('body');
            var edit_elements = jQuery(this).parents('section').find('.only-content').html();
            editFrame.html(edit_elements);
        }

        if( edit_type == 'checklist' )
        {
            var radio_img_id = jQuery(this).parents('section').find(".only-content").attr('data-bullet-id');
            console.log(radio_img_id);
            jQuery("#editContentModal .radio_img_list img").removeClass('active');
            jQuery("#editContentModal .img-rounded").each(function(){
                var id = jQuery(this).attr("data-id");
                if( radio_img_id == id ){
                    jQuery(this).addClass('active');
                }
            });

            var edit_text = '';
            var bullet_img = jQuery("#editContentModal .radio_list img.active").attr('src');


            jQuery(this).parents('section').find(".checklist_content li").each(function(){
                
                var text_on_li = jQuery(this).text();
                var count = jQuery("#editContentModal #checklist-count").val();

                edit_text += '<span class="checklist-span" id="area-'+count+'"><div class="bullet_img_modal"><img src="'+bullet_img+'" /></div><textarea class="checklist_point_text">'+text_on_li+'</textarea><img src="'+wp_custom_js_url.TSRCT_CT_IMG_JS_URL+'/delete.png" class="delete" onclick="delete_checklist_test('+count+')" /></span>';
                count = parseInt(count) + 1;
                jQuery("#editContentModal #checklist-count").val(count);
            });
            jQuery("#editContentModal #selected_bullet_id").val(radio_img_id);
            jQuery("#editContentModal .checklist_text").html(edit_text);
            
        }
        if( edit_type == 'image' )
        {
            var img_html = jQuery(this).parents('section').find(".only-content img").attr('src');
            var span_html = jQuery(this).parents('section').find(".only-content span").attr('style');

            jQuery("#editContentModal #heading_picture_preview").attr('src',img_html);
            jQuery("#editContentModal #image_container").attr('style',span_html);
        }

        jQuery("#editContentModal #image .dashicons-editor-alignleft").on('click',function(){
            jQuery("#editContentModal #image_container").css('text-align','left');
            jQuery("#editContentModal #image .dashicons").removeClass("active")
            jQuery(this).addClass('active');
        });

        jQuery("#editContentModal #image .dashicons-editor-alignright").on('click',function(){
            jQuery("#editContentModal #image_container").css('text-align','right');
            jQuery("#editContentModal #image .dashicons").removeClass("active")
            jQuery(this).addClass('active');
        });

        jQuery("#editContentModal #image .dashicons-editor-aligncenter").on('click',function(){
            jQuery("#editContentModal #image_container").css('text-align','center');
            jQuery("#editContentModal #image .dashicons").removeClass("active")
            jQuery(this).addClass('active');
        });
        var section_id = jQuery(this).attr('data-section-id');
        jQuery("#editContentModal .update_content").attr('data-section-id',section_id);
        jQuery("#editContentModal .update_content").attr('data-section-type',edit_type);
        jQuery('#editContentModal').modal('show');
    });
}

jQuery("#checklist-text-add-on-edit").on('click',function(){
    var htmlValue = '';
    var count = jQuery("#editContentModal #checklist-count").val();
    var bullet_img = jQuery("#editContentModal .radio_list img.active").attr('src');
    htmlValue += '<span class="checklist-span" id="area-'+count+'"><div class="bullet_img_modal"><img src="'+bullet_img+'" /></div><textarea class="checklist_point_text">Default Text</textarea><img src="'+wp_custom_js_url.TSRCT_CT_IMG_JS_URL+'/delete.png" class="delete" onclick="delete_checklist_test('+count+')" /></span>';
    jQuery("#editContentModal .checklist_text").append(htmlValue);
    count = parseInt(count) + 1;
    jQuery("#editContentModal #checklist-count").val(count);
});

jQuery("#editContentModal .radio_img_list img").on('click',function(){
    jQuery("#editContentModal .radio_img_list img").removeClass('active');
    jQuery(this).addClass('active');
    var id = jQuery(this).attr('data-id');
    jQuery("#editContentModal #selected_bullet_id").val(id);
    jQuery("#editContentModal .bullet_img_modal img").attr('src',jQuery(this).attr('src'));
});

jQuery(".update_content").on('click',function(){
    var edit_type = jQuery(this).attr('data-section-type');
    var content_sec_no = jQuery(this).attr('data-section-id');
    var html = '';
    
    
    switch ( edit_type ) { 
        case 'wpEditor': 
            html += '<div class="only-content">';
            html += tinyMCE.activeEditor.getContent();
            html += '</div>';
            break;
        case 'checklist':
            var bullet_id = jQuery("#editContentModal #selected_bullet_id").val();
            html += '<div class="only-content" data-bullet-id="'+bullet_id+'">';
            html += '<ul class="checklist_content">';
            jQuery("#editContentModal .checklist_point_text").each(function(){
                html += '<li><img src="'+wp_custom_js_url.TSRCT_CT_IMG_JS_URL+'/bullet-points/bullet-'+bullet_id+'.png" />';
                html += this.value+'</li>';
            });
            html += '</ul>';

           // html += '<input type="hidden" id="selected_radio_img_id" value="'+bullet_id+'" />';
            html += '</div>';
            break;
        case 'image': 
            var get_parent_css = jQuery("#editContentModal #image_container").css('text-align');
            var get_src = jQuery("#editContentModal #image_parent_container img").attr('src');
            html += '<div class="only-content image-'+get_parent_css+'">';
            html += '<span><img src="'+get_src+'" /></span>';
            html += '</div>';
            
            break;      
    }
    
    
    html += '<div class="section-previewer">';
    html += '<span class="dashicons dashicons-no"></span>';
    html += '<span style="margin-left:10px; margin-right:10px; color:white;"></span>'
    html += '<span data-section-id="'+content_sec_no+'" data-edit="'+edit_type+'" class="dashicons dashicons-edit"></span>';
    html += '<span style="margin-left:10px; margin-right:10px; color:white;"></span>'
    html += '<span class="dashicons dashicons-move"></span>';
    html += '</div>';   
    
    
    jQuery("#sec-"+content_sec_no).html(html);
    
    jQuery(this).attr('data-section-type','');
    jQuery(this).attr('data-section-id','');
    
    jQuery('#editContentModal').modal('hide');
    edit_section();
});
(function($){
    $(window).on("load",function(){
        
        
        $("#tesLayoutModalChange .modal-body").mCustomScrollbar({
            setHeight:340,
            theme:"dark"
        });
        
        
    });
})(jQuery);
(function($){
    $(window).on("load",function(){
        
        
        $("#tesLayoutModal .modal-body").mCustomScrollbar({
            setHeight:340,
            theme:"dark"
        });
        
        
    });
})(jQuery);
jQuery(".btn-proceed").on('click', function () {
    var data = {
      action: 'set_layout_template',
      id: jQuery("input[name='template_layout_id']:checked"). val()
    };
    jQuery.post( ajaxurl, data, function( response ) {
      //console.log(response);
      window.location.replace('post-new.php?post_type=tes-cc-template');
    });
});
jQuery('input.tes_cc_radio').on('change', function() {
    jQuery('input.tes_cc_radio').not(this).prop('checked', false);
    var value = jQuery( 'input[name=tes_cc_temp_status]:checked' ).val();
    var data = {
      action: 'set_checkout_page_layout',
      id: value
    };
    jQuery("#loader-"+value).show();
    jQuery.post( ajaxurl, data, function( response ) {
      jQuery("#loader-"+value).hide();
      alert("Value is saved successfully");
      location.reload();
    });
});
generate_footer_element_option();
set_header_settings();
set_footer_settings();
function generate_footer_element_option(){
    var generateFooterElementOption = jQuery("#footer_custom_element_selection").val();
    jQuery(".footer-custom-elements").hide();
    jQuery("#footer-custom-element-"+generateFooterElementOption).show();
}
function set_header_settings(){
    var tes_header_status = jQuery("#tes_header_status").val();
    //console.log(tes_header_status);
    jQuery(".hf-status-desc").hide();
    jQuery("#hf-"+tes_header_status).show();
}
function set_footer_settings(){
    var tes_footer_status = jQuery("#tes_footer_status").val();
    //console.log(tes_header_status);
    jQuery(".hf-status-desc-footer").hide();
    jQuery("#hf-"+tes_footer_status+"-footer").show();
}
jQuery(document).ready(function(){
    jQuery('#checkout_desc_pr_data_tab .sortable-list').sortable({
        connectWith: '#checkout_desc_pr_data_tab .sortable-list',
        placeholder: 'placeholder',
    });
});
