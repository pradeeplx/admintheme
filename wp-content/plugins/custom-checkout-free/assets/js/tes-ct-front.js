jQuery( "header" ).addClass( 'custom-checkout-pro-header' );
jQuery( "footer" ).addClass( 'custom-checkout-pro-footer' );
jQuery( "body main" ).addClass( 'custom-checkout-pro-body' );


jQuery( ".menu-toggle" ).remove();
jQuery( ".main-navigation" ).remove();
jQuery( ".site-info-wrapper" ).remove();

jQuery( ".theme-slug-oceanwp" ).find( "#scroll-top" ).remove();
jQuery( ".theme-slug-oceanwp" ).find( "#sidr-close" ).remove();
jQuery( ".theme-slug-oceanwp" ).find( "#mobile-menu-search" ).remove();
jQuery( ".theme-slug-oceanwp" ).find( "#owp-qv-wrap" ).remove();
jQuery( ".theme-slug-oceanwp" ).find( "#oceanwp-cart-sidebar-wrap" ).remove();

jQuery( document ).ready( function(){
    jQuery("#ship-to-different-address-checkbox").on('click',function(){
        if( jQuery(this).is(':checked')) {
            jQuery(".shipping_address").slideDown('slow');
        } else {
            jQuery(".shipping_address").slideUp('slow');
        }
    });
});
( function( $ ) {
    $(".woocommerce-form-login").addClass("header-checkout-login");
    $(".woocommerce-checkout").addClass("checkout-layout-form");
    // $("#open-shipping").click(function(){
    //  $("#billing-panel .woocommerce-billing-fields").slideUp('slow');
    // });

    $(".layout-1 #billing-panel .panel-details-area").slideDown('slow');

    $(".layout-1 .checkout-layout-form .panel-body .header").click(function(){
        $('.layout-1 .checkout-layout-form .panel-body').find('.panel-details-area').slideUp('slow');
        $(this).parent().find('.panel-details-area').slideDown('slow');
    });


    $(".layout-1 #billing-panel .panel-details-area select, .layout-1 #billing-panel .panel-details-area input").each(function(){
        if(this.value){
            //console.log(this.value);
            $(this).parent().parent().find('label').addClass('active');
        }

    });

    $(".layout-1 #shipping-panel .panel-details-area input, .layout-1 #shipping-panel .panel-details-area select").each(function(){
        if(this.value){
            $(this).parent().parent().find('label').addClass('active');
        }else{
            $(this).parent().parent().find('label').removeClass('active');
        }
    });

    $(".layout-1 #payment .panel-details-area input, .layout-1 #payment .panel-details-area select").each(function(){
        if(this.value){
            //console.log(this.value);
            $(this).parent().parent().find('label').addClass('active');
        }
    });

    $(".layout-1 #shipping_state_field label").addClass('active');

    $(".layout-1 #billing_country_field label").addClass('active');
    $(".layout-1 #shipping_country_field label").addClass('active');




} )( jQuery );

jQuery('.form-fields-layout-1 input').each(function(){
    jQuery(this).focus(function(){
     jQuery(this).parent().parent().find("label").addClass('active');
    }).blur(function () {
      var hasValue = jQuery(this).val();
       if(hasValue!=''){
         jQuery(this).parent().parent().find("label").addClass('active');
       }else{
         jQuery(this).parent().parent().find("label").removeClass('active');
       }
    });
});
jQuery(".section-previewer").remove();
jQuery("#billing-panel .btn").on('click',function(){
    //jQuery("#shipping-panel .header").click();
    if( jQuery("#shipping-panel .header").length == 0){
        if( jQuery("#addtn-info .header").length == 0){
            jQuery("#order_review .header").click();
        }else{
            jQuery("#addtn-info .header").click();
        }

    }else{
        jQuery("#shipping-panel .header").click();
    }

});

jQuery("#shipping-panel .btn").on('click',function(){
    if( jQuery("#addtn-info .header").length == 0){
        jQuery("#order_review .header").click();
    }else{
        jQuery("#addtn-info .header").click();
    }
});

jQuery("#addtn-info .btn").on('click',function(){
    jQuery("#order_review .header").click();
});

jQuery(".elementor-type-footer").remove();
jQuery(".elementor-location-footer").remove();
jQuery("body.checkout-layout-active").append("<div class='buyme'>Page Created with: <a href='https://customcheckoutplugin.com' target='_blank;'>Custom Checkout Plugin</a></div>");

jQuery(".product-description").each(function(n) {
    if( !jQuery(this).find('.sortable-item').length){
        jQuery(this).remove();
    }
});
jQuery(".custom-checkout-pro-layout-desc").each(function(n) {
    if( !jQuery(this).find('.sortable-item').length){
        jQuery(this).remove();
    }
});
jQuery( document.body ).on( 'updated_checkout', function(){
    //console.log("Hello");
    //jQuery(".summary_right_box").find('table.shop_table').hide();
});
var $ =jQuery.noConflict();
$( document ).ajaxComplete(function(event, XMLHttpRequest, ajaxOptions) {
    var actual_url = ajaxOptions.url
    var url_arr = actual_url.split("?");
    if(url_arr[1] == 'wc-ajax=update_order_review')
    {
        jQuery(".summary_right_box").find('table.shop_table thead').remove();
        jQuery(".summary_right_box").find('table.shop_table tbody').remove();
        jQuery(".summary_right_box").find('table.shop_table tfoot').css('text-transform','uppercase');
        jQuery(".summary_right_box").find('table.shop_table').css('border','hidden');
        jQuery(".summary_right_box").find('table.shop_table tfoot tr').css('border','hidden');
        jQuery(".summary_right_box").find('table.shop_table tfoot td').css('border','hidden');
        jQuery(".summary_right_box").find('table.shop_table tfoot th').css('border','hidden');
    }
});