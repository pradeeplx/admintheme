jQuery(function($){


    try {
        jQuery('.dig-custom-field-type-date').find('input').attr({'type': 'text','dtype':'date','date':1}).datepicker({
            language: 'en',
            timepicker: false,
            onSelect: function(formattedDate, date, inst) {
                jQuery(inst.el).trigger('change');
            }
        });
    }catch (e) {
    }


    function isEmpty( el ){
        return !jQuery.trim(el.text());
    }

    var tokenCon;

    var akCallback = -1;


    var dig_hide_ccode = dig_log_obj.dig_hide_ccode;
    if(dig_hide_ccode==1)jQuery('body').addClass('dig_hideccode');


    jQuery(".digits-login-modal").each(function(){

        var hr = jQuery(this).attr('href');
        var type = jQuery(this).attr('type');
        var cls = jQuery(this).attr('class');
        jQuery(this).closest("a").attr({'href':hr,'type':type,'class':cls});

    })


    var loader = jQuery(".dig_load_overlay");
    var modcontainer =  jQuery('.dig-box');

    jQuery('body').append(loader);
    jQuery('body').append(modcontainer);

    var opnmodcon = document.getElementsByClassName("digits-login-modal")[0];
    var modclos = document.getElementsByClassName("dig-cont-close")[0];


    jQuery(".dig-cont-close").click(function(){
        modcontainer.css({'display':'none'});
        unlockScroll();
        if(jQuery("#digits_redirect_page").length)
            jQuery("#digits_redirect_page").remove();
    });


    var isPlaceholder = 0;
    var leftPadding = 'unset';
    jQuery(".dig_pgmdl_2").find(".minput").each(function(){
        var inp = jQuery(this).find('input,textarea');
        if(inp.length){
            if(inp.attr('type')!="checkbox" && inp.attr('type')!="radio"){
                var lb = jQuery.trim(jQuery(this).find('label').text());
                inp.attr('placeholder',lb);
                isPlaceholder = 1;
                leftPadding = '1em';
            }
        }
    });
    var customLeftPadding = jQuery(".dig_leftpadding");
    if(customLeftPadding.length){
        leftPadding = customLeftPadding.val();
    }
    jQuery("#dig-ucr-container").on('click', function(event) {
        if(jQuery(this).attr('force'))return;
        if (jQuery(event.target).has('.dig-modal-con').length) {
            modcontainer.css({'display':'none'});
            unlockScroll();
            if(jQuery("#digits_redirect_page").length)
                jQuery("#digits_redirect_page").remove();
        }
    });


    var login = jQuery(".dig_lrf_box .digloginpage");
    var register = jQuery(".dig_lrf_box .register");
    var forgot = jQuery(".dig_lrf_box .forgot");

    var login_modal = jQuery(".dig_ma-box .digloginpage");
    var register_modal = jQuery(".dig_ma-box .register");
    var forgot_modal = jQuery(".dig_ma-box .forgot");
    var forgotpass_modal = jQuery(".dig_ma-box .forgotpass");

    var forgotpass = jQuery(".dig_lrf_box .forgotpass");

    register_modal.find('.dig_wp_bp_fields').remove();

    var dig_sortorder = dig_log_obj.dig_sortorder;

    if(dig_sortorder!=null) {
        if (dig_sortorder.length) {
            var sortorder = dig_sortorder.split(',');
            var digits_register_inputs = register_modal.find(".dig_reg_inputs");
            digits_register_inputs.each(function(){
                jQuery(this).find('.minput').sort(function (a, b) {
                    var ap = jQuery.inArray(a.id, sortorder);
                    var bp = jQuery.inArray(b.id, sortorder);
                    return (ap < bp) ? -1 : (ap > bp) ? 1 : 0;
                }).appendTo(jQuery(this));
            });
        }

    }

    var mailSecondLabel = jQuery(".dig_secHolder");
    var secondmailormobile = jQuery(".dig-secondmailormobile");


    var loginBoxTitle = jQuery(".dig-box-login-title");
    var isSecondMailVisible = false;
    var inftype = 0;

    var leftDis = dig_log_obj.left;



    var noanim = false;



    var triggered = 0;

    var dig_modal_conn = jQuery(".dig-modal-con");

    $.fn.digits_login_modal = function($this) {

        show_digits_login_modal($this);
        return false;
    };

    jQuery(document).on("click", ".digits-login-modal", function() {
        if(!jQuery(this).attr('attr-disclick')){
            show_digits_login_modal(jQuery(this));
        }
        return false;

    });
    function show_digits_login_modal($this){
        var windowWidth = jQuery(window).width();
        var type = $this.attr('type');


        jQuery(".minput").trigger('blur');
        if (typeof type === typeof undefined || type === false || type=="button") {
            type = 1;
        }

        if(type==10 || $this.attr('data-fal')==1){


            if($this.attr('href')) window.location.href = $this.attr('href');

            return true;
        }else {


            lockScroll();



            noanim = true;
            modcontainer.css({'display': 'block'});
            if(type==1 || type==4){


                modcontainer.find(".backtoLogin").click();
                register.find(".backtoLoginContainer").show();
                forgot.find(".backtoLoginContainer").show();

                updateModalHeight(login_modal);

                if(type==4) {
                    modcontainer.find(".signupbutton").hide();
                    modcontainer.find(".signdesc").hide();
                }else {
                    modcontainer.find(".signupbutton").show();
                    modcontainer.find(".signdesc").show();
                }
            } else if(type==2){
                if(register.length) {
                    modcontainer.find(".backtoLogin").click();
                    register.find(".backtoLoginContainer").hide();
                    modcontainer.find(".signupbutton").click();

                }else{
                    showDigMessage(dig_log_obj.Registrationisdisabled);
                    modcontainer.hide();
                    noanim = false;
                    return false;
                }
            } else if(type==3){
                if(forgot.length) {
                    modcontainer.find(".backtoLogin").click();
                    forgot.find(".backtoLoginContainer").hide();
                    modcontainer.find(".forgotpassworda").click();

                }else{
                    showDigMessage(dig_log_obj.forgotPasswordisdisabled);
                    modcontainer.hide();
                    noanim = false;
                    return false;
                }
            }

            noanim = false;

            jQuery("[tabindex='-1']").removeAttr('tabindex');

        }
        return false;
    };


    if(dig_log_obj.dig_dsb==1) return;


    var precode;
    function loginuser(response) {
        if(precode==response.code){
            return false;
        }

        precode = response.code;

        var rememberMe = 0;
        if(jQuery(".digits_login_remember_me").length){
            rememberMe = jQuery(".digits_login_remember_me:checked").length > 0;
        }

        jQuery.ajax({
            type: 'post',
            url: dig_log_obj.ajax_url,
            data: {
                action: 'digits_login_user',
                code: response.code,
                csrf: response.state,
                digits: 1,
                rememberMe: rememberMe,
            },
            success: function (res) {
                if(isJSON(res)){

                    if(!res.code){
                        res = res;
                    }else {
                        if (res.redirect) {
                            loader.show();
                            window.location.href = res.redirect;
                            return;
                        }
                        res = res.code;
                    }
                }else {
                    res = res.trim();
                }

                loader.hide();
                if (res == "1") {
                    loader.show();
                    if(jQuery("#digits_redirect_page").length) {
                        window.location.href = jQuery("#digits_redirect_page").val();
                    }else window.location.href = dig_log_obj.uri;

                } else if(res==-1){
                    showDigMessage(dig_log_obj.pleasesignupbeforelogginin);
                } else if(res==-9){
                    showDigMessage(dig_log_obj.invalidapicredentials)
                }else{
                    showDigMessage(dig_log_obj.invalidlogindetails);
                }

            }
        });

        return false;
    };


// login callback
    function loginCallback(response) {
        if (response.status === "PARTIALLY_AUTHENTICATED") {
            var code = response.code;
            var csrf = response.state;
            showDigitsModal(false);
            loginuser(response);

        }
        else {
            showDigitsModal(true);
        }

    }

    jQuery(document).on("click", "#dig_lo_resend_otp_btn", function() {
        var dbbtn = jQuery(this);
        if(!jQuery(this).hasClass("dig_resendotp_disabled")){
            loader.show();

            if(isFirebase==1) {
                dismissLoader = true;
                loader.show();
                var phone = dbbtn.attr("countrycode") + dbbtn.attr("mob");

                grecaptcha.reset(window.recaptchaWidgetId);

                var appVerifier = window.recaptchaVerifier;
                firebase.auth().signInWithPhoneNumber(phone, appVerifier)
                    .then(function (confirmationResult) {
                        isDigFbAdd = 1;
                        loader.hide();
                        window.confirmationResult = confirmationResult;
                        updateTime(dbbtn);
                    }).catch(function (error) {
                    loader.hide();
                    showDigMessage(dig_mdet.Invaliddetails);
                });



            }else {
                jQuery.ajax({
                    type: 'post',
                    url: dig_log_obj.ajax_url,
                    data: {
                        action: 'digits_resendotp',
                        countrycode: dbbtn.attr("countrycode"),
                        mobileNo: dbbtn.attr("mob"),
                        csrf: dbbtn.attr("csrf"),
                        login: dbbtn.attr("dtype")
                    },
                    success: function (res) {
                        res = res.trim();
                        loader.hide();
                        if (res == 0) {
                            showDigMessage(dig_log_obj.pleasetryagain);
                        } else if (res == -99) {
                            showDigMessage(dig_log_obj.invalidcountrycode);
                        } else {
                            updateTime(dbbtn);
                        }
                    }
                });
            }
        }
    });


    jQuery(document).on("click", ".dig_captcha", function() {
        var $this = jQuery(this);
        var cap = $this.parent().find(".dig_captcha_ses");
        var r = Math.random();
        $this.attr('src', $this.attr('cap_src')+'?r='+r+'&pr='+cap.val());
        cap.val(r);

    });

    jQuery('.dig_captcha').on('dragstart', function(event) { event.preventDefault(); });


    if(jQuery.isFunction(jQuery.fn.niceSelect)) jQuery(".dig-custom-field").find('select').niceSelect();

    var update_time_button;

    var resendTime = dig_log_obj.resendOtpTime;
    function updateTime(time){



        tokenCon = time.closest('form');
        if(update_time_button) update_time_button.attr('value',dig_log_obj.SubmitOTP).text(dig_log_obj.SubmitOTP);


        time.attr("dis",1).addClass("dig_resendotp_disabled").show().find("span").show();

        var time_spam = time.find("span");

        time_spam.text(convToMMSS(resendTime));
        var counter = 0;

        var interval = setInterval(function() {
            var rem = resendTime - counter;


            time_spam.text(convToMMSS(rem));
            counter++;

            if (counter >= resendTime) {
                clearInterval(interval);
                time.removeAttr("dis").removeClass("dig_resendotp_disabled").find("span").hide();
                counter = 0;
            }
        }, 1000,true);
    }


    function convToMMSS(timeInSeconds) {
        var sec_num = parseInt(timeInSeconds, 10); // don't forget the second param
        var hours   = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        var seconds = sec_num - (hours * 3600) - (minutes * 60);

        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}
        return "("+minutes+':'+seconds+")";
    }


    var dismissLoader = false;
    var lastcountrycode,lastmobileNo,lastDtype;
    var username_reg_field = '';
    var email_reg_field = '';
    var captcha_reg_field = '';
    var captcha_ses_reg_field = '';
    var isFirebase = 0;

    function verifyMobileNoLogin(countrycode,mobileNo,csrf,dtype){
        if(lastcountrycode==countrycode && lastmobileNo==mobileNo && lastDtype==dtype){
            loader.hide();
            return;
        }

        dismissLoader = false;
        hideDigMessage();
        loader.show();
        lastcountrycode = countrycode;
        lastmobileNo = mobileNo;
        lastDtype = dtype;
        jQuery.ajax({
            type: 'post',
            url: dig_log_obj.ajax_url,
            data: {
                action: 'digits_check_mob',
                countrycode: countrycode,
                mobileNo: mobileNo,
                csrf: csrf,
                login: dtype,
                username: username_reg_field,
                email: email_reg_field,
                captcha: captcha_reg_field,
                captcha_ses: captcha_ses_reg_field,
                digits: 1,
                json: 1
            },
            success: function (res) {
                username_reg_field = '';
                email_reg_field = '';
                captcha_reg_field = '';
                captcha_ses_reg_field = '';

                lastDtype=0;
                lastmobileNo=0;

                loader.hide();

                var ak = -1;
                if(isJSON(res)){
                    if(res.success===false) {
                        showDigMessage(res.data.message);
                        return;
                    }


                    ak = res.ak;
                    isFirebase = res.firebase;
                    res = res.code;
                }else{
                    res = res.trim();
                }


                if(res==-1 && dtype==11){
                    showDigMessage(dig_log_obj.MobileNumberalreadyinuse);
                    return;
                }


                if(res==-99){
                    showDigMessage(dig_log_obj.invalidcountrycode);
                    return;
                }
                if (res == -11) {
                    if(dtype==1) {
                        showDigMessage(dig_log_obj.pleasesignupbeforelogginin);
                        return;
                    }else if(dtype==3){
                        showDigMessage(dig_log_obj.Mobilenumbernotfound);
                        return;
                    }
                } else if (res == 0) {
                    showDigMessage(dig_log_obj.Error);
                    return;
                }

                if(res==-1 && dtype==2){
                    showDigMessage(dig_log_obj.MobileNumberalreadyinuse);
                    return;
                }

                if(mobileNo==null || countrycode==null){
                    registerStatus = 1;
                    regForm.find(".registerbutton").click();
                    return;
                }


                mobileNo = filter_mobile(mobileNo);
                countrycode = countrycode.replace(/^0+/, '');


                if(ak==1){
                    processAccountkitLogin(countrycode,mobileNo);

                }else if(isFirebase==1 ) {

                    var dig_verify_otp_input = jQuery(".dig_verify_otp_input");
                    if(dig_verify_otp_input.length){
                        dig_verify_otp_input.attr({'placeholder':'------','maxlength':6})
                    }
                    dismissLoader = true;
                    loader.show();

                    var phone = countrycode + mobileNo;


                    var appVerifier = window.recaptchaVerifier;
                    firebase.auth().signInWithPhoneNumber(phone, appVerifier)
                        .then(function (confirmationResult) {
                            loader.hide();
                            window.confirmationResult = confirmationResult;
                            verifyMobNo_success(res,countrycode,mobileNo,csrf,dtype);

                        }).catch(function (error) {
                        loader.hide();
                        showDigMessage(error.message);

                    });
                }else {
                    verifyMobNo_success(res,countrycode,mobileNo,csrf,dtype);
                }
            }
        });
    }

    loader.on('click',function(){
        if(dismissLoader) loader.hide();
    })


    function processAccountkit(countrycode,mobileNo){
        hideDigitsModal();
        AccountKit.login("PHONE",
            {countryCode: countrycode, phoneNumber: phoneNumber},
            eval(akCallback));
    }

    update_req_fields();
    function update_req_fields() {
        if (dig_log_obj.show_asterisk == 1) {
            jQuery(".minput").each(function () {
                var par = jQuery(this);
                if(par.hasClass("dig-custom-field")) return;
                var inpu = par.find("input");

                if (inpu.attr('required') && !inpu.attr('aster')) {
                    par.find("label").append(" *");
                    inpu.attr('aster',1);
                }
            });
        }
    }

    if(dig_log_obj.firebase==1 && jQuery('form').length) {

        try {
            if (firebase != null) {
                jQuery('form').first().append('<input type="hidden" value="1" id="dig_login_va_fr_otp" />');

                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('dig_login_va_fr_otp', {
                    'size': 'invisible',
                    'callback': function (response) {

                    },
                    'expired-callback': function () {
                        loader.hide();
                    },
                    'error-callback': function () {
                        loader.hide();
                    }

                });
                firebase.auth().signOut();
            }
        }catch(err) {
            
        }
    }

    var dig_otp_fields = jQuery("input[name='dig_otp']");
    dig_otp_fields.on('change',function(e){
        var $this = jQuery(this);
        $this.val($this.val().replace(/\D/g, ''));
    });
    dig_otp_fields.on('keydown',function(e){

        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
            (e.keyCode === 86 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            e.shiftKey === true ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    })

    var otp_box = 0;
    var otp_container = jQuery(".dig_verify_mobile_otp_container");
    var otp_submit_button = 0;
    function verifyMobNo_success(res,countrycode,mobileNo,csrf,dtype){

        dismissLoader = false;
        if(dtype==1 || dtype==11) {
            if (res == 1) {
                updateTime(jQuery(".dig_logof_log_resend").attr({"countrycode":countrycode,
                    "mob":mobileNo,"csrf":csrf,"dtype":dtype}));
                jQuery(".digloginpage .minput").find("input[type='password']").each(function () {
                    jQuery(this).closest(".minput").slideUp();
                });
                var otpin = submit_form.find(".dig_login_otp");
                jQuery(".logforb").hide();
                otpin.slideDown().find("input").attr("required", "required").focus();

                otp_submit_button.attr("verify", 1);


                if(otp_container.length){
                    login.hide();
                    otp_box = otpin.find("input");
                    show_mobile_in_element(otp_container.show().find(".dig_verify_code_msg span"), countrycode+mobileNo);
                    otp_container.find('input').focus();
                    otp_container.find(".dig_verify_otp").after(jQuery(".dig_logof_log_resend"));
                }
            }
        }else if(dtype==2){

            updateTime(jQuery(".dig_logof_reg_resend").attr({"countrycode":countrycode,
                "mob":mobileNo,"csrf":csrf,"dtype":dtype}));

            registerStatus = 1;
            regForm.find(".minput").find("input[type='password']").each(function () {
                jQuery(this).closest(".minput").slideUp();
            });
            var otpin = regForm.find(".dig_register_otp");
            otpin.slideDown().find("input").attr("required", "required").focus();
            regForm.find(".dig_reg_btn_password").hide();
            dig_otp_signup.show();

            regForm.find(".registerbutton").attr("verify", 1);


            otpin.closest(".dig-container").addClass("dig-min-het");

            if(otp_container.length){
                otp_submit_button = dig_otp_signup;
                register.hide();
                otp_box = otpin.find("input");
                show_mobile_in_element(otp_container.show().find(".dig_verify_code_msg span"), countrycode+mobileNo);
                otp_container.find('input').focus();
                otp_container.find(".dig_verify_otp").after(jQuery(".dig_logof_reg_resend"));
            }

        }else if(dtype==3) {

            updateTime(jQuery(".dig_logof_forg_resend").attr({"countrycode":countrycode,
                "mob":mobileNo,"csrf":csrf,"dtype":dtype}));

            var otpin = forgotForm.find(".dig_forgot_otp");
            otpin.slideDown().find("input").attr("required", "required").focus();

            otp_submit_button = forgotForm.find(".forgotpassword");
            otp_submit_button.attr("verify", 1);


            if(otp_container.length){
                forgot.hide();
                otp_box = otpin.find("input");
                show_mobile_in_element(otp_container.show().find(".dig_verify_code_msg span"), countrycode+mobileNo);
                otp_container.find('input').focus();
                otp_container.find(".dig_verify_otp").after(jQuery(".dig_logof_reg_resend"));
            }
        }
        setTimeout(function(){jQuery(window).trigger('resize');}, 350);
        update_req_fields();
        jQuery(window).trigger('resize');

    }

    function show_mobile_in_element(element, phone){
        var phone_obj = libphonenumber.parsePhoneNumberFromString(phone);

        if (typeof phone_obj != "undefined") {
            var countrycode = phone_obj.countryCallingCode;
            phone = (phone_obj.formatNational()).replace(/^0+/, '');
        }
        element.text('+'+countrycode+ ' '+phone);
    }

    jQuery(".dig_verify_otp_input").on('keyup',function(event){
        var keyCode = (event.keyCode ? event.keyCode : event.which);
        if (keyCode == 13) {
            jQuery(".dig_verify_otp").trigger('click');
        }

    });
    jQuery(".dig_verify_otp").on('click',function(){
        var dig_verify_otp = jQuery(".dig_verify_otp_input");
        var dig_verify_otp_input = dig_verify_otp.val();
        if(dig_verify_otp_input.length==0){
            dig_verify_otp.addClass("dig_input_error").closest('.minput').append(requiredTextElement);
            return false;
        }
        otp_box.val(dig_verify_otp_input);
        otp_submit_button.trigger('click');
    })



    jQuery(".dig_lrf_box .loginviasms").click(function(){

        otp_submit_button = jQuery(this);


        submit_form = jQuery(this).closest('form');
        update_time_button = jQuery(this);
        var countryCode = submit_form.find(".logincountrycode").val();
        var csrf = jQuery(".dig_nounce").val();
        var phoneNumber = submit_form.find('.dig-mobmail').val();

        /*
                if(dig_log_obj.captcha_accept==1)
                {
                    jQuery(".digloginpage").find("input[type='password']").closest(".minput").hide();
                }*/

        if(phoneNumber=="" || countryCode==""){
            showDigMessage(dig_log_obj.InvalidMobileNumber);
            return;
        }


        if(!is_mobile(phoneNumber) || !jQuery.isNumeric(countryCode)) {
            showDigMessage(dig_log_obj.InvalidMobileNumber);
            return;
        }


        if(dig_log_obj.captcha_accept==1){

            captcha_reg_field = submit_form.find("input[name='digits_reg_logincaptcha']").val();
            captcha_ses_reg_field = submit_form.find(".dig-custom-field-type-captcha").find(".dig_captcha_ses").val();
            if(captcha_reg_field.length==0){
                showDigMessage("Please enter a valid captcha!");
                return;
            }
        }

        if(jQuery(this).attr('verify')==1){
            var otpin = submit_form.find(".dig_login_otp");
            verifyOtp(countryCode,phoneNumber,csrf,otpin.find("input").val(),1);
            return;
        }


        if (is_mobile(phoneNumber)) {
            akCallback = 'loginCallback';
            verifyMobileNoLogin(countryCode,formatMobileNumber(phoneNumber),csrf,1);

        } else if(phoneNumber.length>0) {
            showDigMessage(dig_log_obj.Thisfeaturesonlyworkswithmobilenumber);
        }else{
            akCallback = 'loginCallback';
            verifyMobileNoLogin(countryCode,formatMobileNumber(phoneNumber),csrf);
        }
    });



    var submit_form;

    jQuery(".dig_verify_mobile_no").click(function(){

        update_time_button = jQuery(this);
        otp_submit_button = jQuery(this);
        submit_form = jQuery(this).closest('form');
        var countryCode = submit_form.find(".logincountrycode").val();
        var csrf = jQuery(".dig_nounce").val();
        var phoneNumber = submit_form.find('.dig-mobmail').val();


        if(phoneNumber=="" || countryCode==""){
            showDigMessage(dig_log_obj.InvalidMobileNumber);
            return;
        }


        if(!is_mobile(phoneNumber) || !jQuery.isNumeric(countryCode)) {
            showDigMessage(dig_log_obj.InvalidMobileNumber);
            return;
        }


        var dig_otp = submit_form.find(".dig_login_otp");

        if(jQuery(this).attr('verify')==1){
            verifyOtp(countryCode,phoneNumber,csrf,dig_otp.find("input").val(),11);
            return;
        }


        if (is_mobile(phoneNumber)) {

            akCallback = 'updateFormVerfication';
            verifyMobileNoLogin(countryCode,phoneNumber,csrf,11);


        }
    });



    function updateFormVerfication(response) {
        if (response.status === "PARTIALLY_AUTHENTICATED") {
            var code = response.code;
            var csrf = response.state;
            showDigitsModal(false);


            submit_form.find(".digits_code").val(code);
            submit_form.find(".digits_csrf").val(csrf);

            submit_form.submit();

        }
        else{
            showDigitsModal(true);
        }

    }




    var lastotpmobileNo,lastotpcountrycode,lastotpDtype;
    function verifyOtp(countryCode,phoneNumber,csrf,otp,dtype) {
        dismissLoader = false;
        hideDigMessage();
        loader.show();

        if(isFirebase==1) verify_firebase_otp(countryCode,phoneNumber,csrf,otp,dtype);
        else verify_cust_otp(countryCode,phoneNumber,csrf,otp,dtype,-1);

    }

    function verify_firebase_otp(countryCode,phoneNumber,csrf,otp,dtype) {
        phoneNumber = filter_mobile(phoneNumber);
        countryCode = countryCode.replace(/^0+/, '');

        if(otp==null || otp.length==0){
            loader.hide();
            showDigMessage(dig_log_obj.InvalidOTP);
            return;
        }

        window.confirmationResult.confirm(otp)
            .then(function (result) {

                firebase.auth().currentUser.getIdToken( true).then(function(idToken) {

                    window.verifyingCode = false;
                    window.confirmationResult = null;
                    jQuery("#dig_ftok_fbase").remove();
                    tokenCon.append("<input type='hidden' name='dig_ftoken' value='"+idToken+"' id='dig_ftok_fbase' />");
                    verify_cust_otp(countryCode,phoneNumber,csrf,otp,dtype,idToken);
                }).catch(function(error) {
                    loader.hide();
                    showDigMessage(error);
                });


            }).catch(function (error) {
            loader.hide();
            showDigMessage(error);
        });

    }

    function verify_cust_otp(countryCode,phoneNumber,csrf,otp,dtype,idToken) {
        if(lastotpcountrycode==countryCode && lastotpmobileNo==phoneNumber && lastotpDtype==otp){
            loader.hide();
            return;
        }

        lastotpcountrycode = countryCode;
        lastotpmobileNo = phoneNumber;
        lastotpDtype = otp;

        var rememberMe = 0;
        if(jQuery(".digits_login_remember_me").length){
            rememberMe = jQuery(".digits_login_remember_me:checked").length > 0;
        }


        jQuery.ajax({
            type: 'post',
            url: dig_log_obj.ajax_url,
            data: {
                action: 'digits_verifyotp_login',
                countrycode: countryCode,
                mobileNo: phoneNumber,
                otp:otp,
                dig_ftoken: idToken,
                csrf: csrf,
                dtype: dtype,
                digits: 1,
                rememberMe: rememberMe,
            },
            success: function (res) {


                if(isJSON(res)){

                    if(!res.code){
                        res = res;
                    }else {
                        if (res.redirect) {
                            window.location.href = res.redirect;
                            return;
                        }
                        res = res.code;
                    }
                }else {
                    res = res.trim();
                }

                if(res!=11)loader.hide();

                if(res==1011){
                    showDigMessage(dig_log_obj.error);
                    return;
                }

                if(res==1013){
                    showDigMessage(dig_log_obj.error);
                    return;
                }

                if(res==-99){
                    showDigMessage(dig_log_obj.invalidcountrycode);
                    return;
                }

                if(dtype==11){
                    submit_form.submit();
                    return;
                }


                if(res==0){
                    showDigMessage(dig_log_obj.InvalidOTP);
                    return;
                }else if(res==11){
                    if(jQuery("#digits_redirect_page").length) {
                        window.location.href = jQuery("#digits_redirect_page").val();
                    }else window.location.href = dig_log_obj.uri;

                    return;
                }else if(res==-1 && dtype!=2){
                    showDigMessage(dig_log_obj.ErrorPleasetryagainlater);
                    return;
                }else if(res==1 && dtype==2){
                    showDigMessage(dig_log_obj.MobileNumberalreadyinuse);
                    return;
                }


                if(dtype==2){
                    registerStatus = 1;
                    regForm.find(".registerbutton").attr("verify",3).click();

                }else if(dtype==3){
                    forgotForm.find(".changepassword .minput").each(function(){
                        jQuery(this).show();
                    });
                    forgotForm.find(".dig_forgot_otp").slideUp();
                    forgotForm.find(".forgotpasscontainer").slideUp();
                    forgotForm.find(".changepassword").slideDown();
                    forgotForm.find(".digits_csrf").val(csrf);
                    forgotForm.find(".dig_logof_forg_resend").hide();
                    update_time_button.val(prv);
                    passchange = 1;
                    if(otp_container.length){
                        otp_container.hide();
                        forgot.show();
                    }
                }
            }
        });
    }


    var prv = -1;
    var forgotpass = jQuery(".dig_lrf_box .forgotpass");
    var passchange = 0;


    if(jQuery("#digits_forgotPassChange").length){
        passchange = 1;
    }

    var forgotForm;
    jQuery(".dig_lrf_box .forgotpassword").click(function(){
        update_time_button = jQuery(this);
        forgotForm = jQuery(this).closest('form');
        if(prv==-1) prv = jQuery(this).val();
        var forgot = jQuery.trim(forgotForm.find('.forgotpass').val());
        var countryCode = forgotForm.find(".forgotcountrycode").val();
        var csrf = jQuery(".dig_nounce").val();

        if(jQuery(this).attr("verify")==1 && passchange!=1){
            var otpin = forgotForm.find(".dig_forgot_otp");
            verifyOtp(countryCode,forgot,csrf,otpin.find("input").val(),3);
            return false;

        }
        var passBox = forgotForm.find(".digits_password");
        var cpassBox = forgotForm.find(".digits_cpassword");
        if(passchange==1) {
            var pass = passBox.val();
            var cpass = cpassBox.val();
            if(pass!=cpass){
                showDigMessage(dig_log_obj.Passworddoesnotmatchtheconfirmpassword);
                return false;
            }


            return true;
        }

        if(validateEmail(forgot) && forgot!=""){
            passBox.removeAttr('required');
            cpassBox.removeAttr('required');
            return true;
        }else{


            var countryCode = forgotForm.find(".forgotcountrycode").val();

            if(forgot=="" || countryCode==""){
                return;
            }
            if (is_mobile(forgot)) {

                akCallback = 'forgotCallBack';
                verifyMobileNoLogin(countryCode, forgot, csrf, 3);


            }else{
                showDigMessage(dig_log_obj.Invaliddetails);
            }


        }

        return false;
    });


    var digPassReg = jQuery(".dig_lrf_box #digits_reg_password");
    var dig_pass_signup = jQuery(".dig_lrf_box .dig-signup-password");
    var dig_otp_signup = jQuery(".dig_lrf_box .dig-signup-otp");


    var dig_log_reg_button = 0;

    dig_pass_signup.click(function(){

        var dis = jQuery(this).attr('attr-dis');

        if(dis == 0){
            return false;
        }

        digPassReg.attr("required","");
        dig_otp_signup.hide();


        digPassReg.parent().show();
        digPassReg.parent().parent().fadeIn();


        jQuery(this).addClass('registerbutton');
        jQuery(this).attr('attr-dis',0);
        dig_log_reg_button = 0;

        return false;
    });


    var requiredTextElement = "<span class='dig_field_required_text'>Required</span>";
    var registerStatus = 0;


    jQuery(".dig_login_rembe, .dig_opt_mult").find('input[type="checkbox"],input[type="radio"]').on('change',function(){
        var $this = jQuery(this);


        if($this.is(':radio')) {
            $this.closest(".dig_opt_mult_con").find(".selected").removeClass('selected');
        }

        if(!$this.is(':checked')){
            $this.parent().removeClass('selected');
        }else{
            $this.parent().addClass('selected');
        }
        if(jQuery(this).attr('data-all')){
            jQuery("."+jQuery(this).attr('data-all')).each(function(){
                if(jQuery(this).is(':checked') !== $this.is(':checked')) {
                    jQuery(this).attr('checked', $this.is(':checked')).trigger('change');
                }
            });
        }
    })
    jQuery(document).on('keyup change', '.dig_input_error', function(){
        var minput = jQuery(this).closest('.minput');
        minput.find(".dig_input_error").removeClass('dig_input_error');
        minput.find(".dig_field_required_text").remove();
    })
    var regForm;
    jQuery(".dig_lrf_box .registerbutton").on('click', function(){

        regForm = jQuery(this).closest('form');


        update_time_button = jQuery(this);

        var name,mail,pass,secmail;
        name = jQuery.trim(regForm.find(".digits_reg_name").val());
        secmail = jQuery.trim(regForm.find('.dig-secondmailormobile').val());
        mail = jQuery.trim(regForm.find('.digits_reg_email').val());
        pass = jQuery.trim(digPassReg.val());


        if(dig_log_obj.strong_pass==1){
            if(dig_log_obj.pass_accept==2 || pass.length>0) {
                var strength = wp.passwordStrength.meter(pass, ['black', 'listed', 'word'], pass);
                if (strength != null && strength < 3) {
                    showDigMessage(dig_log_obj.useStrongPasswordString);
                    return false;
                }
            }
        }
        var dis = jQuery(this).attr('attr-dis');
        var csrf = jQuery(".dig_nounce").val();

        var error = false;



        regForm.find('input,textarea,select').each(function () {
            if(jQuery(this).attr('required') || jQuery(this).attr('data-req')){


                var $this = jQuery(this);

                var dtype = $this.attr('dtype');

                if(dtype && dtype=='range'){
                    var range = $this.val().split('-');
                    if(!range[1]){
                        error = true;
                        $this.addClass('dig_input_error').closest('.minput').append(requiredTextElement);
                        $this.val('');
                    }
                }
                if($this.attr('date')){

                    if(dtype!='range') {
                        var date = new Date($this.val());

                        if (!isDateValid(date)) {
                            error = true;
                            $this.addClass('dig_input_error').closest('.minput').append(requiredTextElement);
                            $this.val('');

                        }
                    }else{
                        var date1 = new Date(range[0]);
                        var date2 = new Date(range[1]);
                        if (!isDateValid(date1) || !isDateValid(date2)) {
                            error = true;
                            $this.addClass('dig_input_error').closest('.minput').append(requiredTextElement);
                            $this.val('');

                        }
                    }
                }else if($this.is(':checkbox') || $this.is(':radio')){

                    if(!$this.is(':checked') && !jQuery('input[name="'+$this.attr('name')+'"]:checked').val()){
                        error = true;
                        $this.addClass('dig_input_error').closest('.minput').append(requiredTextElement);
                    }
                }else {
                    var value = $this.val();
                    if(value==null || value.length==0){
                        error = true;
                        if($this.is("select"))
                            $this.next().addClass('dig_input_error');

                        $this.addClass('dig_input_error').closest('.minput').append(requiredTextElement);
                    }
                }

            }
        })


        if(regForm.find('.dig_input_error').length ==1) {
            if (regForm.find(".dig_opt_mult_con_tac").find('.dig_input_error').length > 0) {
                showDigMessage(dig_log_obj.accepttac);
                return false;
            }
        }

        if (error) {
            showDigMessage(dig_log_obj.fillAllDetails);
            return false;
        }

        if(regForm.attr('wait')){
            showDigMessage(regForm.attr('wait'));
            return false;
        }
        if(regForm.attr('error')){
            showDigMessage(regForm.attr('error'));
            return false;
        }


        if(dig_log_obj.mobile_accept==0 && dig_log_obj.mail_accept==0){
            return true;
        }



        if(dis == 1 && dig_otp_signup.length){
            digPassReg.attr("required","");
            dig_otp_signup.hide();


            digPassReg.parent().show();
            digPassReg.parent().parent().fadeIn();


            jQuery(this).attr('attr-dis',-1);
            dig_log_reg_button = 0;
            jQuery(window).trigger('resize');
            return false;
        }else if(!dis){

            if(dig_log_obj.pass_accept==2 && pass.length==0){
                showDigMessage(dig_log_obj.Invaliddetails);
                return false;
            }
            if(dig_log_obj.pass_accept>0 && pass.length==0 && validateEmail(mail) && validateEmail(secmail) && !is_mobile(mail) && !is_mobile(secmail)){
                showDigMessage(dig_log_obj.eitherenterpassormob);
                return false;
            }
        }



        if(jQuery(this).attr("verify")==1){
            var otp = regForm.find(".dig_register_otp").find("input").val();
            if(is_mobile(mail)){
                verifyOtp(regForm.find(".registercountrycode").val(),mail,csrf,otp,2);
                return false;
            }else if(is_mobile(secmail)){
                verifyOtp(regForm.find(".registersecondcountrycode").val(),secmail,csrf,otp,2);
                return false;
            }
            return false;
        }
        if(registerStatus==1){return true;}
        var dis = jQuery(this).attr('attr-dis');




        if (mail.length == 0 && (dig_log_obj.mobile_accept==2 || dig_log_obj.mobile_accept==1 && dig_log_obj.mail_accept==1)) {
            showDigMessage(dig_log_obj.Invaliddetails);
            return false;
        }



        if (is_mobile(mail) && is_mobile(secmail) && secmail.length > 0) {
            showDigMessage(dig_log_obj.InvalidEmail);
            return false;
        }

        if(jQuery(".disable_email_digit").length){
            if(!is_mobile(mail)){
                showDigMessage(dig_log_obj.Invaliddetails);
                return false;
            }

        }else{
            if (validateEmail(mail) && validateEmail(secmail) && secmail.length > 0) {
                showDigMessage(dig_log_obj.Invaliddetails);
                return false;
            }
            var dig_reg_mail = regForm.find(".dig_reg_mail");
            if (validateEmail(mail)) {
                dig_reg_mail.val(mail);
            } else if (validateEmail(secmail)) {
                dig_reg_mail.val(secmail);
            }


            if(dig_log_obj.mail_accept==2 && !validateEmail(secmail) && !validateEmail(mail)){
                showDigMessage(dig_log_obj.InvalidEmail);

                return false;
            }

        }

        if(!jQuery(".disable_password_digit").length) {
            if (!is_mobile(regForm.find('.digits_reg_email').val()) && !is_mobile(regForm.find('.dig-secondmailormobile').val())) {

                if (dig_log_obj.pass_accept>0 && pass.length == 0) {
                    showDigMessage(dig_log_obj.eitherenterpassormob);
                    return false;
                }

            }
        }



        if(dig_log_obj.mobile_accept==2 && !is_mobile(mail) && !is_mobile(secmail)){
            showDigMessage(dig_log_obj.InvalidMobileNumber);
            return false;
        }

        if(jQuery("#digits_reg_username").length){
            username_reg_field = jQuery("#digits_reg_username").val();
        }
        var curRegForm = jQuery(this).closest('form');
        if(curRegForm.find(".dig-custom-field-type-captcha").length){
            captcha_reg_field = curRegForm.find(".dig-custom-field-type-captcha").find("input[type='text']").val();
            captcha_ses_reg_field = curRegForm.find(".dig-custom-field-type-captcha").find(".dig_captcha_ses").val();
        }


        if(is_mobile(mail)){


            akCallback = 'registerCallBack';
            email_reg_field = secmail;
            verifyMobileNoLogin(jQuery(".dig_lrf_box .registercountrycode").val(),mail,csrf,2);

            return false;



        }else if(is_mobile(secmail)){

            akCallback = 'registerCallBack';
            email_reg_field = mail;
            verifyMobileNoLogin(jQuery(".dig_lrf_box .registersecondcountrycode").val(),secmail,csrf,2);



            return false;


        }




        if(validateEmail(mail)){
            email_reg_field = mail;
        }else {
            email_reg_field = secmail;
        }
        verifyMobileNoLogin(null,null,csrf,2);

        return false;

    });

    function registerCallBack(response){

        if (response.status === "PARTIALLY_AUTHENTICATED") {
            showDigitsModal(false);

            var code = response.code;
            var csrf = response.state;
            regForm.find(".register_code").val(code);
            regForm.find(".register_csrf").val(csrf);

            registerStatus = 1;
            loader.show();
            regForm.find(".registerbutton").click();
        }else{
            showDigitsModal(true);

        }
    }

    function forgotCallBack(response){
        showDigitsModal(true);
        if (response.status === "PARTIALLY_AUTHENTICATED") {
            passchange = 1;
            var code = response.code;
            var csrf = response.state;
            forgotForm.find(".forgotpasscontainer").slideUp();
            forgotForm.find(".changepassword").slideDown();
            forgotForm.find(".digits_code").val(code);
            forgotForm.find(".digits_csrf").val(csrf);
        }
    }
    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }





    var lef = leftDis*3;
    leftDis = lef*2-9;
    jQuery(".dig_lrf_box .backtoLogin").click(function () {
        if(loginBoxTitle){
            loginBoxTitle.text(dig_log_obj.login);
        }

        var box = jQuery(this).closest('.dig_lrf_box');
        var login = box.find('.digloginpage');


        if(!noanim) {
            login.fadeIn('fast');
        }else{
            login.show();
        }

        box.find('.forgot').hide();
        box.find('.register').hide();
        updateModalHeight(login_modal);


    });
    jQuery(".dig_lrf_box .signupbutton").click(function () {
        var box = jQuery(this).closest('.dig_lrf_box');

        if(loginBoxTitle){
            loginBoxTitle.text(dig_log_obj.signup);
        }

        box.find('.digloginpage').hide();


        if(!noanim) {
            box.find('.register').fadeIn('fast');
        }else{
            box.find('.register').show();
        }

        updateModalHeight(register_modal);


    });
    jQuery(window).on('resize', function () {

        if(register.is(":visible")){

            updateModalHeight(register_modal);
        }else if(dig_modal_conn.is(":visible")){
            updateModalHeight(login_modal);
            if(otp_container.length>0) otp_container.css({"height":login.outerHeight(true)});
        }

    });

    if(otp_container.length>0) {otp_container.css({"height":login.outerHeight(true)});}

    jQuery(".dig_lrf_box .forgotpassworda").click(function () {
        if(loginBoxTitle){
            loginBoxTitle.text(dig_log_obj.ForgotPassword);
        }
        var box = jQuery(this).closest('.dig_lrf_box');
        box.find('.digloginpage').hide();

        if(!noanim) {
            box.find('.forgot').fadeIn('fast');
        }else{
            box.find('.digloginpage').hide();
        }
        updateModalHeight(forgot_modal);
    });
    function hideLogin(){
        login.hide();

    }

    function updateModalHeight(box){
        dig_modal_conn.css({"height":box.outerHeight(true) + 90});
    }
    var usernameid = jQuery(".dig_lrf_box .dig-mobmail");

    var digits_reg_email = jQuery(".dig_lrf_box .digits_reg_email");

    var ew = 12;
    usernameid.bind("keyup change", function (e) {
        if(dig_log_obj.login_mobile_accept==0) return;
        var par = jQuery(this).closest('div');
        if (show_countrycode_field(jQuery(this).val())) {
            par.find(".logincountrycodecontainer").css({"display": "inline-block"}).find('.logincountrycode').trigger('keyup');
        } else {
            par.find(".logincountrycodecontainer").hide();
            jQuery(this).css({"padding-left": leftPadding});
        }
    });

    jQuery('.dig_lrf_box .logincountrycode').bind("keyup change", function (e) {

        var size = jQuery(this).val().length;
        size++;
        if (size < 2) size = 2;
        jQuery(this).attr('size', size);
        var code = jQuery(this).val();
        if (code.trim().length == 0) {
            jQuery(this).val("+");
        }
        var par = jQuery(this).closest('form');

        par.find('.dig-mobmail').stop().animate({"padding-left": jQuery(this).outerWidth() + ew/2 + "px"}, 'fast', function () {
        });

    });




    digits_reg_email.bind("keyup change", function (e) {
        var par = jQuery(this).closest('form');
        if(dig_log_obj.mobile_accept==0)return;
        if (show_countrycode_field(jQuery(this).val())) {
            par.find(".registercountrycodecontainer").css({"display": "inline-block"}).find('.registercountrycode').trigger('keyup');
        } else {
            par.find(".registercountrycodecontainer").hide();
            jQuery(this).css({"padding-left": leftPadding});
        }
        updateMailSecondLabel(par);
    });



    setTimeout(function() {
        usernameid.trigger("keyup");
        digits_reg_email.trigger("keyup");
    },10);


    jQuery('.registercountrycode').bind("keyup change", function (e) {

        var size = jQuery(this).val().length;
        size++;
        if (size < 2) size = 2;
        jQuery(this).attr('size', size);
        var code = jQuery(this).val();
        if (code.trim().length == 0) {
            jQuery(this).val("+");
        }
        var par = jQuery(this).closest('form');

        par.find('.digits_reg_email').stop().animate({"padding-left": jQuery(this).outerWidth() + ew/2 + "px"}, 'fast', function () {});

        updateMailSecondLabel(par);
    });


    secondmailormobile.bind("keyup change", function (e) {
        if(dig_log_obj.mail_accept==2 || dig_log_obj.mobile_accept==2) return;

        var par = jQuery(this).closest('form');

        if (show_countrycode_field(jQuery(this).val()) && !is_mobile(par.find('.digits_reg_email').val())){
            par.find(".secondregistercountrycodecontainer").css({"display": "inline-block"}).find(".registersecondcountrycode").trigger('keyup');

        } else {
            par.find(".secondregistercountrycodecontainer").hide();
            jQuery(this).css({"padding-left": leftPadding});
        }
        updateMailSecondLabel(par);
    });




    jQuery('.registersecondcountrycode').bind("keyup change", function (e) {
        var size = jQuery(this).val().length;
        size++;
        if (size < 2) size = 2;
        jQuery(this).attr('size', size);
        var code = jQuery(this).val();
        if (code.trim().length == 0) {
            jQuery(this).val("+");
        }
        var par = jQuery(this).closest('form');
        par.find('.dig-secondmailormobile').stop().animate({"padding-left": jQuery(this).outerWidth() + ew/2 + "px"}, 'fast', function () {});

        updateMailSecondLabel(par);
    });



    forgotpass.bind("keyup change", function (e) {
        var par = jQuery(this).closest('form');
        if (show_countrycode_field(jQuery(this).val())) {
            par.find(".forgotcountrycodecontainer").css({"display": "inline-block"}).find('.forgotcountrycode').trigger('keyup');

        } else {
            par.find(".forgotcountrycodecontainer").hide();
            jQuery(this).css({"padding-left": leftPadding});
        }

    });




    jQuery('.forgotcountrycode').bind("keyup change", function (e) {
        var size = jQuery(this).val().length;
        size++;
        if (size < 2) size = 2;
        jQuery(this).attr('size', size);
        var code = jQuery(this).val();
        if (code.trim().length == 0) {
            jQuery(this).val("+");
        }
        jQuery(this).closest('form').find('.forgotpass').stop().animate({"padding-left": jQuery(this).outerWidth() + ew/2 + "px"}, 'fast', function () {});
    });





    var prevInftype = 0;
    function updateMailSecondLabel(par) {
        var secondmailormobile = par.find('.dig-secondmailormobile');
        if(secondmailormobile==null)return;

        var con = par.find('.digits_reg_email').val();
        //if(!con)return;
        var cc = secondmailormobile.val();
        if(cc==undefined || cc.length!=0)return;

        if ((is_mobile(con) && inftype!=1) || dig_log_obj.mail_accept==2) {
            inftype = 1;

            par.find('.dig_secHolder').html(dig_log_obj.Email);
        } else if(!is_mobile(con) && inftype!=2 && dig_log_obj.mobile_accept!=2) {
            inftype = 2;
            par.find('.dig_secHolder').html(dig_log_obj.Mobileno);
        }

        if(secondmailormobile.attr('placeholder') && prevInftype!=inftype){
            prevInftype = inftype;
            secondmailormobile.attr('placeholder',par.find('.dig_secHolder').parent().text());
        }

        if(dig_log_obj.mail_accept!=2 && dig_log_obj.mobile_accept!=2) {

            if (con == "" || con.length == 0) {
                par.find('.dig-mailsecond').hide();
                if(isSecondMailVisible) jQuery(window).trigger('resize');
                isSecondMailVisible = false;
                return;
            }

            if (!isSecondMailVisible) {
                par.find('.dig-mailsecond').fadeIn();
                jQuery(window).trigger('resize');
                isSecondMailVisible = true;
            } else return;
        }
    }


    var minputs = jQuery('.minput').find("input,textarea");
    minputs.blur(function(){
        tmpval = jQuery(this).val();
        if(tmpval == '') {
            jQuery(this).addClass('empty').removeClass('not-empty');
        } else {
            jQuery(this).addClass('not-empty').removeClass('empty');
        }
    });



    function processAccountkitLogin(countrycode,phoneNumber){
        hideDigitsModal();
        AccountKit.login("PHONE",
            {countryCode: countrycode, phoneNumber: phoneNumber},
            eval(akCallback));

    }
    jQuery(window).on('load', function () {
        setTimeout(function(){
            minputs.each(function () {
                jQuery(this).triggerHandler('blur');
            });
        },500);
    })



    function formatMobileNumber(number){
        return filter_mobile(number);
    }

    var elem = jQuery(".digit_cs-list");
    var cur_countrycode = jQuery(".countrycode");

    var isShown = 0;
    jQuery(document).on("focus", ".countrycode", function() {
        var $this = jQuery(this);
        var parentForm = $this.parent('div');
        parentForm.append(elem);

        var nextNode = elem.find('li.selected');
        highlight(nextNode);


        var thisOset = $this.position();
        var parrentFormOset = parentForm.position();

        var olset = thisOset.left - parrentFormOset.left;



        var margin = parseInt( $this.css("marginBottom") );
        elem.css({'top': $this.outerHeight(true) - margin, 'left' : olset});

        elem.show();

        isShown = 1;
    });
    jQuery(document).on("focusout", ".countrycode", function(e) {

        elem.hide();
        isShown = 0;
    });

    jQuery(document).on("keydown", ".countrycode", function(e) {

        if(isShown==0)cur_countrycode.trigger('focus');
        switch (e.which) {
            case 38: // Up
                var visibles = elem.find('li.dig-cc-visible:not([disabled])');
                var nextNode = elem.find('li.selected').prev();
                var nextIndex = visibles.index(nextNode.length > 0 ? nextNode : visibles.last());
                highlight(nextIndex);
                e.preventDefault();
                return false;
                break;
            case 40:

                var visibles = elem.find('li.dig-cc-visible:not([disabled])');
                var nextNode = elem.find('li.selected').next();

                var nextIndex = visibles.index(nextNode.length > 0 ? nextNode : visibles.first());
                highlight(nextIndex);
                e.preventDefault();
                return false;
                break;
            case 13:
                selectCode();
                return false;
                break;
            case 9:  // Tab
            case 27: //ESC
                elem.hide();
                break;
            default:
                var hiddens = 0;
                var curInput = jQuery(document.activeElement);
                var input  = curInput.val().toLowerCase().trim().replace(/[^a-z]+/gi, "");

                jQuery(".digit_cs-list li").each(function(index){
                    var attr = jQuery(this).attr('country');
                    if(attr.startsWith(input)){
                        highlight(index);
                        return false;
                    }
                });



                break;
        }


    });


    function selectCode(){

        if (elem.is(':visible')) {
            var selEle = elem.find('li.selected');

            var curInput = jQuery(document.activeElement);
            curInput.val("+" + selEle.attr('value'));
            curInput.trigger('keyup');
            elem.hide();
            isShown = 0;
        }
    }
    function highlight(index) {
        setTimeout(function () {

            var visibles         = elem.find('li.dig-cc-visible');
            var oldSelected      = elem.find('li.selected').removeClass('selected');
            var oldSelectedIndex = visibles.index(oldSelected);

            if (visibles.length > 0) {
                var selectedIndex = (visibles.length + index) % visibles.length;
                var selected      = visibles.eq(selectedIndex);

                var top = 0;
                if(selected.length>0) {
                    top = selected.position().top;
                    selected.addClass('selected');
                }
                if (selectedIndex < oldSelectedIndex && top < 0) {
                    elem.scrollTop(elem.scrollTop() + top);
                }
                if (selectedIndex > oldSelectedIndex && top + selected.outerHeight() > elem.outerHeight()) {
                    elem.scrollTo(".selected");


                }

            }
        });
    };

    elem.on('mousemove', 'li:not([disabled])', function () {

        elem.find('.selected').removeClass('selected');
        jQuery(this).addClass('selected');

    })
        .on('mousedown', 'li', function (e) {
            if (elem.is('[disabled]')) e.preventDefault();
            else elem.select(jQuery(this));
            selectCode();
        })
        .on('mouseup', function () {
            elem.find('li.selected').removeClass('selected');
        });



    function hideDigitsModal(){
        jQuery('body').addClass('dig_low_overlay');
        loader.show();
        hideDigMessage();
        if(modcontainer.length){
            modcontainer.hide();
        }
    }
    function showDigitsModal(hideLoader){
        jQuery('body').removeClass('dig_low_overlay');

        if(hideLoader)loader.hide();
        if(modcontainer.length){
            modcontainer.show();
        }
    }



    function lockScroll(){
        $html = jQuery('html');
        $body = jQuery('body');
        var initWidth = $body.outerWidth();
        var initHeight = $body.outerHeight();

        var scrollPosition = [
            self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
            self.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop
        ];
        $html.data('scroll-position', scrollPosition);
        $html.data('previous-overflow', $html.css('overflow'));
        $html.css('overflow', 'hidden');
        window.scrollTo(scrollPosition[0], scrollPosition[1]);

        var marginR = $body.outerWidth()-initWidth;
        var marginB = $body.outerHeight()-initHeight;
        $body.css({'margin-right': marginR,'margin-bottom': marginB});
    }

    function unlockScroll(){
        $html = jQuery('html');
        $body = jQuery('body');
        $html.css('overflow', $html.data('previous-overflow'));
        var scrollPosition = $html.data('scroll-position');
        window.scrollTo(scrollPosition[0], scrollPosition[1]);

        $body.css({'margin-right': 0, 'margin-bottom': 0});
    }


    function showDigMessage(message){

        if(jQuery(".dig_error_message").length){
            jQuery(".dig_error_message").find(".dig_lase_message").text(message);
            if(!jQuery(".dig_error_message").is(":visible")) jQuery(".dig_error_message").slideDown('fast');
        }else {
            jQuery("body").append("<div class='dig_popmessage dig_error_message'><div class='dig_firele'><img src='"+ dig_log_obj.face + "'></div><div class='dig_lasele'><div class='dig_lase_snap'>"+dig_log_obj.ohsnap+"</div><div class='dig_lase_message'>" + message + "</div></div><img class='dig_popdismiss' src='"+ dig_log_obj.cross + "'></div>");
            jQuery(".dig_error_message").slideDown('fast');
        }

    }
    function hideDigMessage(){
            jQuery(".dig_error_message").slideUp('fast');

    }
    jQuery(document).on("click", ".dig_popmessage", function() {
        jQuery(this).closest('.dig_popmessage').slideUp('fast', function() { jQuery(this).remove(); } );
    })








    if(jQuery(".dig_bdy_container").length) {
        var reg;
        var ecd = jQuery(".dig_powrd");
        var b = jQuery(".dig_clg_bx");
        var c = jQuery(".logocontainer");
        var logp = jQuery(".digloginpage");
        var regp = jQuery(".register");
        var digc = jQuery(".dig-container");
        var digimgCon = jQuery(".dig_ul_left_side");
        var header = jQuery(".header");
        var dig_ma_box = jQuery(".dig_lrf_box");
        var otp_container = jQuery(".dig_verify_mobile_otp_container");

        jQuery(window).on('load', function () {
            updatePos();
        });
        jQuery(window).on('resize', function () {
            updatePos();
        });

        var updateLeftBx = function () {
            digimgCon.height(jQuery(document).height());
        };


        function updatePos() {

            if (regp.is(":visible")) {
                reg = 1;
            } else if (otp_container.length > 0 && otp_container.is(":visible")) {
                reg = 2;
            } else reg = 0;
            updatebox(reg);

        }


        function updatebox(upRegHe) {


            var f, at;
            var minTo = 90;
            if (c.length > 0) {
                f = c.height();
                at = 25;
            } else {
                f = 0;
                at = 0;
            }


            var h = jQuery(window).height();

            var boxh = logp.outerHeight(true) + 44;

            if (upRegHe == 1) {

                var regh = regp.outerHeight(true) + 44;
                if (regh > boxh) {
                    boxh = regh;
                }
            } else if (upRegHe == 2) {
                var regh = otp_container.outerHeight(true) + 44;
                if (regh > boxh) {
                    boxh = regh;
                }
            }

            var ecdH = 0;
            if (ecd.length) {
                ecdH = ecd.outerHeight(true);
            }
            var t = (h - f - boxh + at + ecdH + 28) / 2;


            var min_top = 70;

            if (!header.is(":visible")) {
                min_top = 60;
                minTo = min_top + 20;

            }


            if (c.length > 0) c.stop().animate({"top": Math.max(min_top, t - at), "opacity": 1}, 200);


            b.stop().animate({"top": Math.max(minTo, t), "opacity": 1}, 200);

            digc.height(boxh);

            if (ecd.length) {
                ecd.animate({"opacity": "1"});
            }
        }

        jQuery(".signupbutton").click(function () {
            updatebox(true);
        })
        jQuery(".backtoLogin").click(function () {
            updatebox(false);
        })

    }
    function isJSON (something) {
        if (typeof something != 'string')
            something = JSON.stringify(something);

        try {
            JSON.parse(something);
            return true;
        } catch (e) {
            return false;
        }
    }


    function isDateValid(date) {
        // An invalid date object returns NaN for getTime() and NaN is the only
        // object not strictly equal to itself.
        return date.getTime() === date.getTime();
    };

    var country_code_field;

    jQuery(document).on("focus",".mobile_field", function() {
        getCountryCodeField(jQuery(this));
    })
    function getCountryCodeField($this){
        var parent = $this.closest('div');
        country_code_field = parent.find('.countrycode');
    }
    jQuery(document).on("change",".minput .countrycode", function() {
        jQuery(this).closest('.minput').find('.mobile_field').trigger('keyup');
    });
    jQuery(document).on("keyup",".mobile_field", function() {
        if(!country_code_field) getCountryCodeField(jQuery(this));
        var $this = jQuery(this);
        var input = $this.val();
        if($this.hasClass('mobile_format')) {
            if (!dig_begins_with(input)) {
                if (country_code_field.length) {
                    input = country_code_field.val() + '' + input;
                }
            }
        }
        var phone_obj = libphonenumber.parsePhoneNumberFromString(input);

        if (typeof phone_obj != "undefined") {
            var countrycode = phone_obj.countryCallingCode;
            var phone_number = phone_obj.nationalNumber;
            if($this.hasClass('mobile_format')) {
                if(dig_log_obj.dig_mobile_no_formatting==1) {
                    phone_number = jQuery.trim((phone_obj.formatInternational()).replace("+"+countrycode,""));
                    phone_number = phone_number.replace(/^0+/, '');
                }else if(dig_log_obj.dig_mobile_no_formatting==2){
                    phone_number = (phone_obj.formatNational()).replace(/^0+/, '');
                }
            }
            $this.val(phone_number);
            if(country_code_field.length) {
                country_code_field.val('+' + countrycode);
                if(!country_code_field.is(":visible") && !$this.hasClass('dig-attr-cc-key')){
                    $this.addClass('dig-attr-cc-key');
                    $this.trigger('keyup');
                }

            }
        }else{
            $this.removeClass('dig-attr-cc-key')
        }

    })

});
function show_countrycode_field(mobile_number){
    if(is_mobile(mobile_number)){
        return !dig_begins_with(mobile_number);
    }
    return false;
}
function dig_begins_with(mobile_number){
    if (mobile_number.substring(0, 1) == "+"){
        return true;
    }
    return false;
};
function filter_mobile(mobile_number){
    mobile_number = mobile_number.replace(/[-+ )(]/g,'');
    return mobile_number.replace(/^0+/, '');
}
function is_mobile(mobile_number){
    mobile_number = mobile_number.replace(/[- )(]/g,'');
    return jQuery.isNumeric(mobile_number);
}