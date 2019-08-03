jQuery('.form-group input').each(function(){
   jQuery(this).focus(function(){
     jQuery(this).parent().find("label").addClass('active');
   }).blur(function () {
      var hasValue = jQuery(this).val();
       if(hasValue!=''){
         jQuery(this).parent().find("label").addClass('active');
       }else{
         jQuery(this).parent().find("label").removeClass('active');
       }
   });
 });
jQuery(".section-previewer").remove();
if( !jQuery(".side2").find('section').length ){
  jQuery(".side2").remove();
}
jQuery(".checkout-personal-info-container").hide();
jQuery(".layout-1-billing-body-box").slideDown('slow');

jQuery(".billing-continue").on('click',function(){
  jQuery(".checkout-personal-info-container").slideUp('slow');
  jQuery(".layout-1-shipping-body-box").slideDown('slow');
});

jQuery(".shipping-continue").on('click',function(){
  jQuery(".checkout-personal-info-container").slideUp('slow');
  jQuery(".layout-1-order-review-body-box").slideDown('slow');
});

jQuery(".header").on('click',function(){
  jQuery(".checkout-personal-info-container").slideUp('slow');
  jQuery(this).parent().find(".checkout-personal-info-container").slideDown('slow');
});
//jQuery("body").append("<div class='buyme'>Page Created with: <a href='https://customcheckoutplugin.com' target='_blank;'>Custom Checkout Plugin</a></div>");