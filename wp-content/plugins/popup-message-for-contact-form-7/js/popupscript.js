/*  Display Popup when Contact Form 7 successfully submitted  */



document.addEventListener( 'wpcf7mailsent', function( event ) {

	var currentformid = event.detail.contactFormId;

	var popup_id = event.detail.apiResponse.popup_id;
	//Store popup background color and search , is aveliable or not if not aveliable then it is normal color or gradient color
    if( event.detail.apiResponse.popup_background_option == "bg_color")
    {
    	var color_code = event.detail.apiResponse.popup_background_color;
    	
    }
    if(event.detail.apiResponse.popup_background_option  === "gradient_color")
	{
		var color_code = 'linear-gradient('+ event.detail.apiResponse.popup_gradient_color +','+ event.detail.apiResponse.popup_gradient_color1 +')';
	}
    if(event.detail.apiResponse.popup_background_option  === "image")
    {
    	var color_code = '  url("' + event.detail.apiResponse.popup_image_color + '")right center / cover no-repeat';
    }

	 if (popup_id != null && popup_id != '') {  
			//popup box

			swal({

			  // set popup background color and image	

			  background: color_code,

			  // set popup message

			  title: '<span style="color:' + event.detail.apiResponse.popup_text_color +'">'+event.detail.apiResponse.popup_message+'</span>',

			  confirmButtonColor: event.detail.apiResponse.popup_button_background_color,


			  confirmButtonText: '<span style="color:' + event.detail.apiResponse.popup_text_color +'">'+event.detail.apiResponse.popup_button_text+'</span>',

			  // set popup width

			  width: event.detail.apiResponse.m_popup_width,

			  //set popup duration time in seconds 

			  timer: event.detail.apiResponse.m_popup_duration,


			})

		jQuery('.swal2-modal').css('border-radius', event.detail.apiResponse.m_popup_radius+"px");
	 }


}, false );


