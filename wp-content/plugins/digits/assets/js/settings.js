jQuery(function() {

    jQuery(".dig_multiselect_enable").select2();

    jQuery(".dig_multiselect_dynamic_enable").select2({
        tags: true,
        tokenSeparators: [',', ' ']
    });

    jQuery(".digits_admim_conf").find("select").niceSelect();


    var dig_sort_fields = jQuery(".dig-reg-fields").find('tbody');

    if(dig_sort_fields.length) {
        var dig_sortorder = jQuery("#dig_sortorder");


        var sortorder = dig_sortorder.val().split(',');

        dig_sort_fields.find('tr').sort(function (a, b) {
            var ap = jQuery.inArray(a.id, sortorder);
            var bp = jQuery.inArray(b.id, sortorder);
            return (ap < bp) ? -1 : (ap > bp) ? 1 : 0;


        }).appendTo(dig_sort_fields);


        dig_sort_fields.sortable({
            update: function (event, ui) {
                var sortOrder = jQuery(this).sortable('toArray').toString();
                dig_sortorder.val(sortOrder);

                allowUpdateSettings();
            }
        });

    }

    var offs = -1;


    var isBackEnabled = 0;
    var sb_back = jQuery(".dig_sb_back");
    var sb_head = jQuery(".dig_sb_head");
    var das = jQuery(".dig_ad_side");
    var btn = jQuery(".dig_op_wdz_btn");

    var dig_fields_options_main = jQuery(".dig_fields_options_main");

    var dpc = jQuery('#dig_purchasecode');

    function showDigMessage(message){

        if(jQuery(".dig_popmessage").length){
            jQuery(".dig_popmessage").find(".dig_lase_message").text(message);
        }else {
            jQuery("body").append("<div class='dig_popmessage'><div class='dig_firele'><img src='"+ digsetobj.face + "'></div><div class='dig_lasele'><div class='dig_lase_snap'>"+digsetobj.ohsnap+"</div><div class='dig_lase_message'>" + message + "</div></div><img class='dig_popdismiss' src='"+ digsetobj.cross + "'></div>");
            jQuery(".dig_popmessage").slideDown('fast');
        }

    }
    function hideDigMessage(){
        jQuery(".dig_popmessage").remove();
    }


    jQuery('.bg_color').wpColorPicker();


    jQuery("input[name='dig_page_type']").on('change',function(){
        var name = jQuery(this).attr('name');
        var v = jQuery("input[name='dig_page_type']:checked").val();

        jQuery(".dig_page_active").hide().removeClass("dig_page_active");
        jQuery("."+name+"_"+v).show().addClass("dig_page_active");

        var label = jQuery(".dig_page_type_1_2").find("th").find("label");
        var label_text = label.attr('data-type'+v);
        label.text(label_text);


    });



    jQuery("input[name='dig_modal_type']").on('change',function(){
        var name = jQuery(this).attr('name');
        var v = jQuery("input[name='dig_modal_type']:checked").val();

        jQuery(".dig_modal_active").hide().removeClass("dig_modal_active");
        jQuery("."+name+"_"+v).show().addClass("dig_modal_active");

        var label = jQuery(".dig_modal_type_1_2").find("th").find("label");
        var label_text = label.attr('data-type'+v);
        label.text(label_text);


    });

    jQuery(".dig_page_type_1").hide();
    jQuery(".dig_page_type_2").hide();
    jQuery("input[name='dig_page_type']").trigger('change');

    jQuery(".dig_modal_type_1").hide();
    jQuery(".dig_modal_type_2").hide();
    jQuery("input[name='dig_modal_type']").trigger('change');



    var dig_presets_modal = jQuery(".dig_presets_modal");
    var dig_presets_box = jQuery("#dig_presets_box");

    dig_presets_modal.prependTo('.dig_ad_left_side');


    jQuery("#dig_open_preset_box").on('click',function(){
        jQuery(".dig_ad_left_side_content").addClass('dig_blur_bg');
        dig_presets_box.fadeIn('fast');
        lockScroll();

        jQuery("#dig_presets_list").slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 3,
            centerMode: false,
            variableWidth: false,
            slidesToScroll: 1
        });

    })
    jQuery(".dig_presets_modal_head_close").on('click',function(){

        jQuery(".dig_ad_left_side_content").removeClass('dig_blur_bg');
        dig_presets_modal.fadeOut('fast');
        unlockScroll();
        hideDigMessage();
    })




    var dig_tab_wrapper = jQuery(".dig-tab-wrapper");
    if(dig_tab_wrapper.length) {
        var dig_ad_submit = jQuery(".dig_ad_submit");
        var width_dig_ad_submit = dig_ad_submit.outerWidth(true) + 24;
        var dig_left_side = jQuery(".dig_ad_left_side");
        jQuery(window).load(function () {
            update_tab_width();
        });
        jQuery(window).resize(function () {
            update_tab_width();
            update_tab_sticky();
            update_tb_line();

        });

        var respon_win = 822;
        var tb_top = dig_tab_wrapper.offset().top;
        var ad_bar_height = jQuery("#wpadminbar").outerHeight(true);
        jQuery(window).scroll(function () {
            update_tab_sticky();
        });

        function update_tab_sticky(){
            var w_top = jQuery(window).scrollTop();
            var sb = tb_top-w_top;
            if(sb<=ad_bar_height && jQuery(window).width()>=respon_win){
                dig_tab_wrapper.addClass("dig-tab-wrapper-fixed").css({'top':ad_bar_height});
            }else {
                dig_tab_wrapper.removeClass("dig-tab-wrapper-fixed");
            }
        }
        function update_tab_width(){
            var w = dig_left_side.width();
            dig_tab_wrapper.outerWidth(w);
            dig_ad_submit.css({'left': dig_left_side.offset().left + w - 168 });

        }
        jQuery(window).trigger('scroll');

    }

    $mainNav = jQuery(".dig-tab-ul");

    jQuery(document).on("click", ".dig_popmessage", function() {

        jQuery(this).closest('.dig_popmessage').slideUp('fast', function() { jQuery(this).remove(); } );
    })

    var $el, leftPos, newWidth;

    $mainNav.append("<li id='dig-tab-magic-line'></li>");
    var $magicLine = jQuery("#dig-tab-magic-line");


    jQuery(".dig_big_preset_show").on('click',function(){
        jQuery(this).fadeOut('fast');
    })
    jQuery('.dig_preset_big_img').on('click',function(){

        var src = jQuery(this).parent().find('img').attr('src');

        var p = jQuery(".dig_big_preset_show");
        p.find('img').attr('src',src);
        p.fadeIn('fast');
        return false;
    });
    update_tb_line();
    function update_tb_line() {
        var dig_active_tab = jQuery(".dig-nav-tab-active");

        if(!dig_active_tab.length)return;

        var dig_active_tab_par_pos = dig_active_tab.parent().position();
        $magicLine
            .width(dig_active_tab.parent().width())
            .css({
                "left": dig_active_tab_par_pos.left,
                "top": dig_active_tab_par_pos.top + 21
            })
            .data("origLeft", $magicLine.position().left)
            .data("origWidth", $magicLine.width());
        if (dig_active_tab.hasClass("dig_ngmc") && !dig_active_tab.hasClass("customfieldsNavTab")) {

            $magicLine.hide().css({'top': 45});
        }
    }
    jQuery(".updatetabview").click(function(){



        var c = jQuery(this).attr('tab');

        var acr = jQuery(this).attr('acr');

        var refresh = jQuery(this).attr('refresh');

        if (typeof refresh !== typeof undefined && refresh !== false) {
            location.reload();
            return true;
        }

        if (typeof acr !== typeof undefined && acr !== false) {
            var inv = dpc.attr('invalid');
            if (dpc.val().length!=36 || inv==1) {

                showDigMessage(digsetobj.plsActMessage);
                if(jQuery("#dig_activatetab").length){
                    jQuery("#dig_activatetab").click();
                    dpc.focus();
                }
                return false;
            }
        }

        if(!jQuery(this).hasClass("dig_ngmc")) {
            $magicLine.show();
            $el = jQuery(this).parent();
            leftPos = $el.position().left;
            newWidth = $el.width();
            $magicLine.stop().animate({
                left: leftPos,
                width: newWidth,
                top: $el.position().top + 21
            },'fast');
        }else{
            $magicLine.hide();
        }


        jQuery(".digcurrentactive").removeClass("digcurrentactive").hide();

        var tab = jQuery("."+c);
        tab.fadeIn(150).addClass("digcurrentactive");


        if(jQuery(".dig-tab-wrapper-fixed").length)
            jQuery('html, body').animate({scrollTop: tab.offset().top - 90}, 220);


        jQuery(".dig-nav-tab-active").removeClass("dig-nav-tab-active");
        jQuery(this).addClass("dig-nav-tab-active");



        updateURL("tab",c.slice(0,-3));

        return false;
    });

    function updateURL(key,val){
        var url = window.location.href;
        var reExp = new RegExp("[\?|\&]"+key + "=[0-9a-zA-Z\_\+\-\|\.\,\;]*");

        if(reExp.test(url)) {
            // update
            var reExp = new RegExp("[\?&]" + key + "=([^&#]*)");
            var delimiter = reExp.exec(url)[0].charAt(0);
            url = url.replace(reExp, delimiter + key + "=" + val);
        } else {
            // add
            var newParam = key + "=" + val;
            if(!url.indexOf('?')){url += '?';}

            if(url.indexOf('#') > -1){
                var urlparts = url.split('#');
                url = urlparts[0] +  "&" + newParam +  (urlparts[1] ?  "#" +urlparts[1] : '');
            } else {
                url += "&" + newParam;
            }
        }
        window.history.pushState(null, document.title, url);
    }







    var dig_gs_nmb_ovr_spn = jQuery(".dig_gs_nmb_ovr_spn");
    dig_gs_nmb_ovr_spn.find("span").on('click',function(){
        jQuery(this).parent().find("input").focus();
    })

    dig_gs_nmb_ovr_spn.find("input").on('keyup change',function(){
        var inp = jQuery(this).val();
        var size  = inp.length;
        var spn_lbl = jQuery(this).parent().find("span");

        spn_lbl.stop().animate({'left' : Math.max(size*9 + 33,jQuery(this).attr('dig-min'))},'fast');
    });

    dig_gs_nmb_ovr_spn.find("input").trigger('keyup');

    var chn = false;
    jQuery(".digits_admim_conf textarea,.digits_admim_conf input").on('keyup',function(){
        if(!jQuery(this).attr("readonly") && !jQuery(this).attr('dig-save')) {
            var pcheck = jQuery(this).closest('.digcon');
            if(!pcheck.length)enableSave();
        }

    });
    jQuery(".digits_admim_conf input,.digits_admim_conf select,.dig_activation_form input").on('change',function(){

        if(!jQuery(this).attr("readonly") && !jQuery(this).attr('dig-save')) enableSave();
    });


    var dig_pc = jQuery("#dig_purchasecode");

    var addon_tab = jQuery("#dig_addonstab");
    dig_pc.on('change',function(){
        if(jQuery(this).attr('readonly'))return;

        if(addon_tab.length){
            addon_tab.attr('refresh',1);
        }
        jQuery(".customfieldsNavTab").attr('refresh',1);
    });
    dig_pc.on('keyup',function(){

        if(jQuery(this).attr('readonly'))return;



       if(jQuery(this).val().length==36 || jQuery(this).val().length==0){
           jQuery(".dig_prc_ver").hide();
           jQuery(".dig_prc_nover").hide();
       }else{
           invPC(-1);
       }
    });
    jQuery(".wp-color-picker").wpColorPicker(
        'option',
        'change',
        function(event, ui) {
            enableSave();
        }
    );

    function enableSave(){
        if(!chn){
            chn = true;
            allowUpdateSettings();
        }
    }

    function allowUpdateSettings(){
        jQuery(".dig_ad_submit").removeAttr("disabled");

    }

    jQuery(".digits_shortcode_tbs").find("input").click(function(){

        copyShortcode(jQuery(this));
    });

    jQuery(".dig_copy_shortcode").click(function(){
       var a = jQuery(this).parent();
        var i = a.find("input");
        copyShortcode(i);
    });

    function copyShortcode(i){
        if(i.attr("nocop")) return;
        i.select();
        document.execCommand("copy");
        var v = i.val();
        i.val(digsetobj.Copiedtoclipboard);
        setTimeout(
            function() {
                i.val(v);
            }, 800);
    }

    jQuery('.dig_drop_doc_check').each(function( index ) {
        jQuery(this).click(function(){
            var a = jQuery(this).closest('li');
            a.find('.dig_conf_doc').toggle();
            var b = a.find('h2').find('.dig_tgb');
            b.text(b.text() == '+' ? '-' : '+');
        });

    });

    dpc.mask('AAAAAAAA-AAAA-AAAA-AAAA-AAAAAAAAAAAA');

    var digit_tapp = jQuery("#digit_tapp");

    var sgs = jQuery(".dig_load_overlay_gs");

    var se = sgs.length;


    var dig_test_api_status = 0;


    jQuery(".dig_request_server_addition").on('click',function(){
        var hr = jQuery(this).attr('href');
        window.open(hr,'_target');
    })

    var refreshCode = 0;
    jQuery(".dig_domain_type").find('button').on('click',function(){
        var value = jQuery(this).attr('val');
        jQuery("input[name='dig_license_type']").val(value);
        if(refreshCode!=1) {
            refreshCode = 0;
            jQuery("#dig_purchasecode").val('').removeAttr('readonly');
        }
        jQuery(".dig_prchcde").fadeIn('fast');
        jQuery(".dig_domain_type").hide();
        jQuery(".dig_btn_unregister").hide();

        if(value!=1){
            jQuery(".request_live_server_addition").show();
            jQuery(".request_testing_server_addition").hide();
        }else{
            jQuery(".request_live_server_addition").hide();
            jQuery(".request_testing_server_addition").show();
        }
    })
    jQuery(".dig_btn_unregister").on('click',function(){
        if(dig_test_api_status!=1) {
            sgs.find('.circle-loader').removeClass('load-complete');
            sgs.find('.checkmark').hide();
            sgs.fadeIn();
        }

        var code = dpc.val();
        jQuery.post('https://bridge.unitedover.com/updates/verify.php',
            {
                code: code,
                slug: 'digits',
                request_site: encodeURIComponent(jQuery("input[name='dig_domain']").val()),
                license_type: jQuery("input[name='dig_license_type']").val(),
                addons: jQuery("input[name='dig_addons_list']").val(),
                unregister: 1,
                version: jQuery("input[name='dig_version']").val(),
                settings: 1,
            },function(data, status) {
            if(data==1) {
                jQuery(".dig_domain_type").fadeIn('fast');
                jQuery(".dig_prchcde").fadeOut();
                jQuery(".dig_prc_ver").fadeOut();
                jQuery(".dig_prc_nover").hide();
                jQuery("#dig_purchasecode").val('').removeAttr('readonly').trigger('change');
            }else{
                showDigMessage(data);
            }
                jQuery(".dig_activation_form").submit();

                return false;
            }
        );

    })


    var dac;
    jQuery(".dig_activation_form").on("submit",function(){
        dac = jQuery(this);

        hideDigMessage();

        var isOpt = false;
        var isPassdisEmailEnab = false;
        var dig_custom_field_login_j = jQuery(".dig_custom_field_login_j");
        if(dig_custom_field_login_j.length) {
            jQuery(".dig_custom_field_login_j").each(function (a, b) {
                var o = jQuery(this).attr('data-opt');
                var v = jQuery(this).val();
                if (o) {

                    if (v == 1) {
                        isOpt = true;
                        return true;
                    }
                }
                if (v == 0) {
                    var c = jQuery(this).attr('data-disable');

                    if (c) {

                        var ch = jQuery("select[name=" + c + "]").val();

                        if (ch == 1) {
                            isPassdisEmailEnab = true;
                        }
                    }
                }
            });

            if (!isOpt || isPassdisEmailEnab) {
                invPC();
                if (isPassdisEmailEnab) showDigMessage((digsetobj.cannotUseEmailWithoutPass));
                else if (!isOpt) showDigMessage(digsetobj.bothPassAndOTPCannotBeDisabled);
                return false;
            }
        }
        var fd = dac.serialize();


        if(dig_test_api_status!=1) {
            sgs.find('.circle-loader').removeClass('load-complete');
            sgs.find('.checkmark').hide();
            sgs.fadeIn();
        }

        var code = dpc.val();
        if(code.length==0){

            jQuery(".dig_prc_ver").hide();
            jQuery(".dig_prc_nover").hide();

            updateSettings(fd,-1);
            return false;
        }else if(code.length!=36){
            showDigMessage(digsetobj.invalidpurchasecode);

            jQuery(".dig_prc_ver").hide();
            jQuery(".dig_prc_nover").show();
            updateSettings(fd,-1);
            return false;
        }


        jQuery.post('https://bridge.unitedover.com/updates/verify.php',
            {
                json: 1,
                code: code,
                slug: 'digits',
                request_site: encodeURIComponent(jQuery("input[name='dig_domain']").val()),
                addons: jQuery("input[name='dig_addons_list']").val(),
                settings: 1,
                license_type: jQuery("input[name='dig_license_type']").val(),
                version: jQuery("input[name='dig_version']").val(),
            },function(response, status) {


            var data = response.code;

            var type = response.type;
            refreshCode = 1;
            jQuery(".dig_domain_type").find('button[val='+type+']').trigger('click');
            fd = dac.serialize();

                if (data != 1) {
                    invPC(se);
                    dpc.attr('invalid', 1);


                } else {
                    jQuery(".dig_prc_ver").show();
                    jQuery(".dig_prc_nover").hide();
                    dpc.attr('invalid', 0);

                }

                if (data == 0) {
                    showDigMessage(digsetobj.invalidpurchasecode);
                    if (!sgs.attr("ajxsu")) {
                        updateSettings(fd, -1);
                    }

                } else if (data == 1) {

                    jQuery(".dig_btn_unregister").show();
                    jQuery("#dig_purchasecode").attr('readonly',true);

                    if (sgs.attr("ajxsu")) {
                        jQuery(".dig_activation_form").unbind("submit").submit();
                    } else {
                        updateSettings(fd, 1);
                        jQuery(".dig_pc_notice").hide();
                    }
                }
                else {
                    if(data==-1){
                        showDigMessage("This purchase code is already being used on another site.");
                    }else showDigMessage(response.msg);


                    if (!sgs.attr("ajxsu")) {
                        updateSettings(fd, -1);
                    }
                }


            }
        );



       return false;
    });


    function invPC(se){
        jQuery("#dig_purchasecode").removeAttr('readonly');
        jQuery(".dig_prc_ver").hide();
        jQuery(".dig_prc_nover").show();
        if(se>0) sgs.hide();
    }
    function updateSettings(fd,activate){




        jQuery.ajax({
            type:    "POST",
            url:     digsetobj.ajax_url,
            data:    fd + '&action=digits_save_settings&pca='+ activate,
            success: function(data) {

                sgs.find('.circle-loader').addClass('load-complete');
                sgs.find('.checkmark').show();
                setTimeout(
                    function() {
                        sgs.fadeOut();
                        chn = false;
                        jQuery(".dig_ad_submit").attr("disabled","disabled");
                        if(dig_test_api_status==1){
                            digCallTestApi();
                        }
                    }, 1500);


            },
            error: function() {
                invPC();
                showDigMessage(digsetobj.Error);
            }
        });

    }
    jQuery("#digits_setting_update button[type='submit']").click(function(e){
        var val = digit_tapp.value;
        var te = digit_tapp.find("option:selected").attr('han');

        if(te=="msg91"){
            if(jQuery("#msg91senderid").val().length<6){
                showDigMessage(digsetobj.Invalidmsg91senderid);
                return false;
            }
        }


        jQuery("."+te+"cred").find("input").each(function(){
            var input = jQuery(this);
            if(input.val().length==0){
                var optional = input.attr('dig-optional');
                if(optional && optional==1) return;

                showDigMessage(digsetobj.PleasecompleteyourAPISettings);
                e.preventDefault();
                return false;

            }
        });


        jQuery("#digits_setting_update").find("input").each(function(){
            var input = jQuery(this);
            if(input.val().length==0){
                var required = input.attr('required');
                if(!required) return;

                var tb = input.closest('.digtabview').attr('data-tab');
                jQuery("[tab='"+tb+"']").trigger('click');
                input.focus();
                showDigMessage(digsetobj.PleasecompleteyourSettings);
                e.preventDefault();
                return false;

            }
        });
        return true;
    });



    var rtl = jQuery("#is_rtl");


    var select_field_type = jQuery(".dig_sb_select_field");

    var field_options = jQuery(".dig_fields_options");
    jQuery(document).on("click", ".dig_sb_field_types", function() {
        show_field_options(jQuery(this).attr('data-val'),jQuery(this).attr('data-configure_fields'),null);
    });


    jQuery(document).on("click", ".dig_sb_field_wp_wc_types", function() {

        var data_val = jQuery(this).attr('data-val');
        var cff = jQuery(this).attr('data-configure_fields');
        var values = jQuery(this).attr('data-values');
        values = jQuery.parseJSON(values);

        show_field_options(data_val,cff,values);
        isUpdate = false;
    });







    var data_type = jQuery("#dig_custom_field_data_type");

    var dig_field_val_list = jQuery("#dig_field_val_list");

    var required_field_box = jQuery("#dig_field_required");
    var meta_key_box = jQuery("#dig_field_meta_key");
    var field_values = jQuery("#dig_field_options");
    var custom_class_box = jQuery("#dig_field_custom_class");


    var dig_field_label = jQuery("#dig_field_label");


    var isUpdate = false;
    var prevLabel;
    function show_field_options(type,options,values){
        isUpdate = false;
        show_create_new_field_panel();
        options = jQuery.parseJSON(options);


        sb_head.text(options.name);

        if(options.meta_key==1){
            meta_key_box.show();
        }else {
            meta_key_box.hide();
        }

        if(options.force_required==0){
            required_field_box.show();
        }else{
            required_field_box.hide();
        }

        if(options.options==1){
            field_values.show();
        }else{
            field_values.hide();
        }

        jQuery(".dig_sb_extr_fields").hide();
        if(options.slug!=null) jQuery(".dig_sb_field_"+options.slug).show();


        if(values!=null){
            isUpdate = true;
            prevLabel = values['label'];

            dig_field_label.find('input').val(values['label']);
            required_field_box.find('select').val(values['required']).niceSelect('update');
            meta_key_box.find('input').val(values['meta_key']);
            custom_class_box.find('input').val(values['custom_class']);
            if(values['options']!=null) {
                var dropValues = values['options'].toString();

                dropValues = dropValues.split(',');
                dig_field_val_list.empty();
            }

            if(options.slug!=null){
                jQuery(".dig_sb_field_"+options.slug).find('input,textarea').each(function(){
                    if(!jQuery(this).is(':checkbox')) {

                        var name = jQuery(this).attr('name');

                        var va = values[name];

                        if(va==undefined)return;

                        va = va.replace('/x22','"');
                        va = va.replace('/x27',"'");


                        jQuery(this).val(values[name]);
                    }else{

                        if(jQuery.inArray( jQuery(this).val(), values['options'] )!=-1){
                            jQuery(this).prop('checked',true);
                        }else{
                            jQuery(this).prop('checked',false);
                        }
                    }
                })
            }
            dig_cus_field_done.text('Done');

            isBackEnabled = 0;


            if(values['options']!=null) {
                for (var i = 0; i < dropValues.length; i++) {
                    addValueToValList(dropValues[i]);
                }
            }
        }else{


            var m = options.name+'_'+ jQuery.now();


            if(options.pref_label!=undefined){
                dig_field_label.find('input').val(options.pref_label);
            }else {
                dig_field_label.find('input').val('');
            }
            required_field_box.find('select').val(1).change();
            meta_key_box.find('input').val(m.toLowerCase());
            custom_class_box.find('input').val('');
            dig_field_val_list.empty();
            dig_cus_field_done.text('Add');
            isBackEnabled = 1;
            jQuery(".dig_chckbx_usrle").prop('checked',false);
        }

        data_type.val(type);
        dig_fields_options_main.show();
        dig_cus_field_done.show();
        select_field_type.slideUp('fast');
        field_options.fadeIn('fast');
    }



    function addValueToValList(value){
        dig_field_val_list.append('<li></li>').find("li:last-child").text(value).append('<div class="dig_delete_opt_custf"></div>').show();
    }

    var dig_field_sidebar = jQuery(".dig_side_bar");
    var dig_custom_foot = jQuery("#dig_cus_field_footer");
    var dig_ad_cancel = jQuery(".dig_ad_cancel");
    dig_ad_cancel.on('click',function(){


        if(isBackEnabled==1){
            isUpdate = false;
            show_create_new_field_panel();
        }else{
            hide_custom_panel();
        }

    });

    jQuery(".dig_sb_field_add_opt").on('click',function(){
        jQuery(".dig_sb_field_list_input").trigger('focusout');
    })
    jQuery(".dig_sb_field_list_input").keypress(function(event) {
        if (event.keyCode == 13 ) {
            event.preventDefault();
            jQuery(this).trigger('focusout');
        }
    });


    jQuery(document).keyup(function(e) {
        hideDigMessage();
        if (e.keyCode == 27) {
            hide_custom_panel()
        }
    });


    var dig_sb_field = jQuery(".dig_sb_field");
    var dig_cus_field_done = jQuery(".dig_cus_field_done");

    dig_sb_field.find('input').keydown(function(e) {
        if (e.keyCode == 13 && !jQuery(this).hasClass('dig_sb_field_list_input')) {
            dig_cus_field_done.click();
            e.preventDefault();
            return false;
        }
    });

    var customfieldstab = jQuery(".customfieldstab");
    function getFormData($form){
        var unindexed_array = $form.serializeArray();
        var indexed_array = {};

        jQuery.map(unindexed_array, function(n, i){
            var inp = customfieldstab.find('[name='+n['name']+']');
            var va;
            if(inp.is('textarea')) {
                va = n['value'].replace('"', '/x22');
                va = va.replace("'", '/x27');
            }else{
                va = n['value'].replace(/<(?:.|\n)*?>/gm, '');
            }

            indexed_array[n['name']] = va;
        });


        return indexed_array;
    }




    var reg_custom_field_input = jQuery("#dig_reg_custom_field_data");

    if(reg_custom_field_input.length) {
        var field_data = reg_custom_field_input.val();

        if (field_data.length == 0) {
            field_data = '{}';
        }
    }
    var dig_custom_field_data = jQuery.parseJSON(field_data);
    if(dig_cus_field_done==null){
        dig_custom_field_data = {};
    }
    var custom_field_table = jQuery("#dig_custom_field_table").find('tbody');
    var is_newfield;
    dig_cus_field_done.on('click',function () {
        var error_msg = false;

        var isCheckList = 0;
        dig_sb_field.each(function(){
            var sb_field = jQuery(this);
            if(sb_field.is(":visible")){
                if(sb_field.attr('data-req')==1){
                    var is_list = sb_field.attr('data-list');


                    if(is_list==2){
                        isCheckList = 1;
                        var sb_list = sb_field.find("input:checked");

                        if ( sb_list.length == 0 ){
                            error_msg = digsetobj.PleasecompleteyourCustomFieldSettings;
                            return false;

                        }

                    }else if(is_list==1){


                        var sb_list = sb_field.find("ul");
                        if ( sb_list.find('li').length == 0 ){
                            error_msg = digsetobj.PleasecompleteyourCustomFieldSettings;
                            return false;

                        }

                    }else{
                        var sb_input = sb_field.find("input");

                        if(sb_input.length>0){
                            if(jQuery.trim(sb_input.val()).length==0){

                                error_msg = digsetobj.PleasecompleteyourCustomFieldSettings;
                                return false;
                            }
                        }
                    }



                }
            }
        });

        if(error_msg){
            showDigMessage(error_msg);return false;
        }

        var fields = getFormData(dig_field_sidebar.find("input,select,textarea"));

        var opt = [];

        if(isCheckList==1){
            jQuery(".dig_chckbx_usrle").each(function(){
                if(jQuery(this).is(":checked")){
                    var t = jQuery(this).val();
                    opt.push(t.replace(/<(?:.|\n)*?>/gm, ''));


                }
            });
        }else {
            dig_field_val_list.find("li").each(function () {
                var t = jQuery(this).text();
                opt.push(t.replace(/<(?:.|\n)*?>/gm, ''));
            });
        }

        fields['options'] = opt;
        fields['type'] = data_type.val();



        if(!isUpdate && dig_custom_field_data.hasOwnProperty(fields['label'])){
            showDigMessage(digsetobj.fieldAlreadyExist);
            return false;
        }

        var dataString;
        if(isUpdate){
            dig_custom_field_data[prevLabel] = fields;
            dataString = JSON.stringify(dig_custom_field_data);
            dataString = dataString.replace('"'+prevLabel+'":{','"'+fields['label']+'":{');
            dig_custom_field_data = JSON.parse(dataString);

        }else {
            dig_custom_field_data[fields['label']] = fields;

            dataString = JSON.stringify(dig_custom_field_data);
        }
        reg_custom_field_input.val(dataString);
        hide_custom_panel();




        var row = '' +
            '<tr id="dig_cs_'+ removeSpacesAndLowerCase(fields['label']) +'" class="dig_field_type_'+fields['type'].toLowerCase()+'" dig-lab="'+ fields['label'] +'">\n' +
            '            <th scope="row"><label>'+fields['label']+' </label></th>\n' +
            '            <td>\n' +
            '                <div class="dig_custom_field_list">\n' +
            '                   <span>'+requireToString(fields['required']) +'</span>'+
            '                    <div class="dig_icon_customfield">\n' +
            '                        <div class="icon-shape icon-shape-dims dig_cust_field_delete"></div>\n' +
            '                        <div class="icon-gear icon-gear-dims dig_cust_field_setting"></div>\n' +
            '                        <div class="icon-drag icon-drag-dims dig_cust_field_drag"></div>\n' +
            '                    </div>\n' +
            '                </div>\n' +
            '            </td>\n' +
            '        </tr>' +
            '';


        if(isUpdate){
            jQuery('#dig_cs_'+ removeSpacesAndLowerCase(prevLabel)).replaceWith(row);
        }else custom_field_table.append(row);


        enableSave();

        var sortOrder = dig_sort_fields.sortable('toArray').toString();
        dig_sortorder.val(sortOrder);

    })


    function removeSpacesAndLowerCase(str){
        str = jQuery.trim(str.replace(/\s/g, ''));
        return str.toLowerCase();
    }

    jQuery(document).on("click", ".dig_cust_field_setting", function() {
        var row = jQuery(this).closest('tr');
        var label = row.attr('dig-lab');
        var ftype = dig_custom_field_data[label]['type'];
        show_field_options(ftype,jQuery("#dig_cust_list_type_"+ftype).attr('data-configure_fields'),dig_custom_field_data[label]);
        enableSave();
    });




    jQuery(document).on("click", ".dig_delete_opt_custf", function() {
        jQuery(this).closest('li').remove();
    });



    jQuery(document).on("click", ".dig_cust_field_delete", function() {
        var row = jQuery(this).closest('tr');
        var label = row.attr('dig-lab');
        row.slideUp().remove();
        delete dig_custom_field_data[label];
        reg_custom_field_input.val(JSON.stringify(dig_custom_field_data));

        var sortOrder = dig_sort_fields.sortable('toArray').toString();
        dig_sortorder.val(sortOrder);

        enableSave();
    });

    jQuery(".dig_hide_modal, .dig_addons_pop").on('click',function(){
        jQuery(".dig-addon-box").fadeOut('fast',function(){
          jQuery(this).remove();
        });
        jQuery('body').css('overflow','auto');
    });

    function requireToString(value){
        switch (value){
            case "0":
                return digsetobj.string_optional;
            case "1":
                return digsetobj.string_required;
            default:
                return null;
        }
    }



    jQuery("#dig_add_new_reg_field").click(function () {


        if(dig_field_sidebar.is(':visible') && !isUpdate) {
            dig_ad_cancel.trigger('click');
        }else {
            isUpdate = false;
            show_create_new_field_panel();
        }
    })

    function  show_create_new_field_panel() {
        sb_head.text(digsetobj.selectatype);
        isBackEnabled = 0;
        select_field_type.show();
        dig_fields_options_main.hide();
        dig_cus_field_done.hide();
        dig_field_sidebar.show().animate({right: 0}, 'fast',function(){
        field_options.show();
            dig_custom_foot.show();

        });

    }

    function hide_custom_panel(){
        hideDigMessage();
        jQuery(".dig_sb_field_list_input").val('');
        var w = dig_field_sidebar.outerWidth(true);

        dig_custom_foot.hide();
        dig_field_sidebar.animate({right:-w},function () {
            dig_field_sidebar.hide();

        })
    }



    var el = document.getElementById('dig_field_val_list');
    if(el) {
        var sortable = Sortable.create(el);
    }

    jQuery(".dig_sb_field_list_input").focusout(function () {
        hideDigMessage();
        var optval = jQuery(this).val();
        var error = false;
        if(optval.length>0){
            dig_field_val_list.find("li").each(function() {
                if(jQuery(this).text()==optval) {
                    error = true;
                    return false;
                }

            });
            if(!error) {
                addValueToValList(optval);

                jQuery(this).val('');
                dig_field_sidebar.scrollTop(dig_field_sidebar[0].scrollHeight);
            }else{
                showDigMessage(digsetobj.duplicateValue);
            }


        }
    })




    var dig_api_test;

    var loader = jQuery(".dig_load_overlay");

    jQuery(document).on("click", ".dig_call_test_api_btn" ,function(){


        dig_api_test = jQuery(this).closest(".dig_api_test");

        var dig_test_cont = dig_api_test.find(".digcon");
        var mobile = dig_test_cont.find(".mobile").val();
        var countrycode = dig_test_cont.find(".dig_wc_logincountrycode").val();

        if(mobile.length==0 || !jQuery.isNumeric(mobile) || countrycode.length==0 || !jQuery.isNumeric(countrycode) ){
            showDigMessage(digsetobj.validnumber);
            return false;
        }

        dig_test_api_status = 1;

        loader.show();

        if(jQuery(".dig_ad_submit").attr("disabled")){
            digCallTestApi();
        }else jQuery(".dig_activation_form").trigger("submit");




    });

    function digCallTestApi(){
        if(dig_test_api_status!=1)return;

        var dig_test_cont = dig_api_test.find(".digcon");
        var mobile = dig_test_cont.find(".mobile").val();
        var countrycode = dig_test_cont.find(".dig_wc_logincountrycode").val();

        var gatewayBox = dig_api_test.closest('.dig_gateway_box');

        var gateway;
        if(gatewayBox.length) {
            gateway = gatewayBox.find(".digit_gateway").val();
        }else{
            gateway = jQuery(".digit_gateway").val();
        }
        dig_test_api_status = 0;
        jQuery.ajax({
            type: 'post',
            async:true,
            url: digsetobj.ajax_url,
            data: {
                action: 'digits_test_api',
                digt_mobile: mobile,
                gateway: gateway,
                digt_countrycode: countrycode

            },
            success: function (res) {
                showTestResponse(res);
            },
            error: function (res){
                showTestResponse(res);
            }
        });
    }


    function showTestResponse(msg){
        dig_test_api_status = 0;
        dig_api_test.find(".dig_call_test_response").show();
        dig_api_test.find(".dig_call_test_response_msg").text(msg);
        loader.hide();

    }

    jQuery("#digpassaccep").on('change',function(){

       var val = this.value;

        if(val==0) jQuery("#enabledisableforgotpasswordrow").hide();
        else jQuery("#enabledisableforgotpasswordrow").show();

    });



    var dig_hide_countrycode = jQuery("#digits_hidecountrycode");
    jQuery(".whitelistcountrycodeslist").on('change',function(){
        var selected_length = jQuery(this).find("option:selected").length;
        if(selected_length==1){
            dig_hide_countrycode.slideDown('fast');
        }else {
            dig_hide_countrycode.slideUp('fast').find('select').val(0).niceSelect('update');
        }
    })

    digit_tapp.on('change', function() {
        var val = jQuery(this).val();

        var te = digit_tapp.find("option:selected").attr('han');

        te = te.replace(".", "_");

        jQuery('.dig_call_test_response').hide();
        if(val==1 || val==13){

            jQuery(".dig_api_test").hide();
            jQuery(".disotp").hide();
            jQuery(".dig_current_gateway").hide();
        }else{
            jQuery(".dig_api_test").show();
            jQuery(".disotp").show();
            jQuery(".dig_current_gateway").show().find("span").text(digit_tapp.find("option:selected").text());
        }


        digit_tapp.find('option').each(function(index,element){
            var hanc = jQuery(this).attr("han");
            if(hanc!=te) {
                jQuery("." + hanc + "cred").each(function () {
                    jQuery(this).hide().find("input").removeAttr("required");
                });

            }
        });
        jQuery("."+te+"cred").each(function(){
            var input = jQuery(this).show().find("input");
            var optional = input.attr('dig-optional');
            if(optional && optional==1) return;

            input.attr("required","required");
        });

    });
    chn = true;
    jQuery(".whitelistcountrycodeslist").trigger('change');
    digit_tapp.trigger('change');
    chn = false;

    var dig_export_import_box = jQuery("#dig_export_import_content");
    var dig_export_import_text = jQuery(".dig_export_import_values");
    var digits_setting_update = jQuery("#digits_setting_update");
    var export_import_stage = 0;
    jQuery("#digits_configuration_export").on('click',function(){
        var heading = jQuery(this).text();
        var configuration = [];
        loader.show();

        digits_setting_update.find('input, select, textarea').each(function () {
            export_import_stage = 1;
            var $this = jQuery(this);
            if($this.closest('.activatetab').length !== 1) {
                var option = {};
                option['name'] = $this.attr('name');
                option['value'] = $this.val();
                configuration.push(option)
            }
        })
        var configurationString = JSON.stringify(configuration);
        var encodeString = Base64.encode(Encrypt(configurationString));

        loader.hide();
        dig_export_import_box.fadeIn('fast');
        dig_export_import_box.find('.modal_head').text(heading);
        lockScroll();
        var btn = dig_export_import_box.find('button');
        btn.text(btn.attr('attr-export'));
        dig_export_import_text.val(encodeString).attr('readonly',true).select();
        document.execCommand("copy");
    })

    jQuery("#digits_configuration_import").on('click',function(){
        export_import_stage = 2;
        var heading = jQuery(this).text();
        dig_export_import_box.fadeIn('fast');
        dig_export_import_box.find('.modal_head').text(heading);
        var btn = dig_export_import_box.find('button');
        dig_export_import_text.removeAttr('readonly').val('');
        lockScroll();
        btn.text(heading);
    })

    jQuery(".imp_exp_btn_fn").on('click',function(){

        if(export_import_stage==1){
            copyShortcode(dig_export_import_text);
        }else{
            var configurationString = Decrypt(Base64.decode(dig_export_import_text.val()));
            try {
                var configuration = JSON.parse(configurationString);
                jQuery.each(configuration, function (key, data) {

                    var inp = digits_setting_update.find('[name="'+data['name']+'"]');
                    if(inp.length>0)inp.val(data['value']).trigger('change');
                })
                jQuery(".imp_exp_cancel").trigger('click');
                jQuery(".dig_activation_form").trigger("submit");
            } catch (e) {

                showDigMessage(digsetobj.invalidimportcode);
            }
        }
    })

    function Encrypt(str) {
        if (!str) str = "";
        str = (str == "undefined" || str == "null") ? "" : str;
        try {
            var key = 146;
            var pos = 0;
            ostr = '';
            while (pos < str.length) {
                ostr = ostr + String.fromCharCode(str.charCodeAt(pos) ^ key);
                pos += 1;
            }

            return ostr;
        } catch (ex) {
            return '';
        }
    }

    function Decrypt(str) {
        if (!str) str = "";
        str = (str == "undefined" || str == "null") ? "" : str;
        try {
            var key = 146;
            var pos = 0;
            ostr = '';
            while (pos < str.length) {
                ostr = ostr + String.fromCharCode(key ^ str.charCodeAt(pos));
                pos += 1;
            }

            return ostr;
        } catch (ex) {
            return '';
        }
    }

    var Base64 = {

        _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

        encode: function (input) {
            var output = "";
            var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
            var i = 0;

            input = Base64._utf8_encode(input);

            while (i < input.length) {

                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);

                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;

                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }

                output = output +
                    Base64._keyStr.charAt(enc1) + Base64._keyStr.charAt(enc2) +
                    Base64._keyStr.charAt(enc3) + Base64._keyStr.charAt(enc4);

            }

            return output;
        },


        decode: function (input) {
            var output = "";
            var chr1, chr2, chr3;
            var enc1, enc2, enc3, enc4;
            var i = 0;

            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

            while (i < input.length) {

                enc1 = Base64._keyStr.indexOf(input.charAt(i++));
                enc2 = Base64._keyStr.indexOf(input.charAt(i++));
                enc3 = Base64._keyStr.indexOf(input.charAt(i++));
                enc4 = Base64._keyStr.indexOf(input.charAt(i++));

                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;

                output = output + String.fromCharCode(chr1);

                if (enc3 != 64) {
                    output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                    output = output + String.fromCharCode(chr3);
                }

            }

            output = Base64._utf8_decode(output);

            return output;

        },

        _utf8_encode: function (string) {
            string = string.replace(/\r\n/g, "\n");
            var utftext = "";

            for (var n = 0; n < string.length; n++) {

                var c = string.charCodeAt(n);

                if (c < 128) {
                    utftext += String.fromCharCode(c);
                } else if ((c > 127) && (c < 2048)) {
                    utftext += String.fromCharCode((c >> 6) | 192);
                    utftext += String.fromCharCode((c & 63) | 128);
                } else {
                    utftext += String.fromCharCode((c >> 12) | 224);
                    utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                    utftext += String.fromCharCode((c & 63) | 128);
                }

            }

            return utftext;
        },

        _utf8_decode: function (utftext) {
            var string = "";
            var i = 0;
            var c = c1 = c2 = 0;

            while (i < utftext.length) {

                c = utftext.charCodeAt(i);

                if (c < 128) {
                    string += String.fromCharCode(c);
                    i++;
                } else if ((c > 191) && (c < 224)) {
                    c2 = utftext.charCodeAt(i + 1);
                    string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                    i += 2;
                } else {
                    c2 = utftext.charCodeAt(i + 1);
                    c3 = utftext.charCodeAt(i + 2);
                    string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                    i += 3;
                }

            }
            return string;
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


        var createCustomerEnabler = jQuery('#enable_createcustomeronorder');
    if(createCustomerEnabler.length) {
        updatesetBox(createCustomerEnabler.val());
        createCustomerEnabler.on('change', function () {
            updatesetBox(this.value);
        })

        function updatesetBox(val) {
            if (val == 1) {
                jQuery(".dig-ccor").each(function (index) {
                    jQuery(this).fadeIn();
                });
            } else {
                jQuery(".dig-ccor").each(function (index) {
                    jQuery(this).fadeOut();
                });
            }
        }


    }



    var ftpDetailsBox = jQuery('.request-filesystem-credentials-dialog');

    var form_sub;
    var ftpAvail = false;
    jQuery('.digmodifyaddon').on('click',function(event)
    {
        form_sub = jQuery(this);
        hideDigMessage();
        if(wp.updates.shouldRequestFilesystemCredentials && false===wp.updates.filesystemCredentials.available){
            wp.updates.maybeRequestFilesystemCredentials( event );
            return;
        }

        loader.show();
        var $this = jQuery(this);
        var t = $this.attr('type');
        var p = $this.closest('.dig_addon_int_btn');
        var addon = p.attr('data-plugin');
        var nounce = p.find('.dig_addon_nounce').val();
        var slug = p.find('.dig_plugin_slug').val();

        if(t==10){
            $this.addClass('dig_updating');
        }
        jQuery.ajax({
            type:    "POST",
            url:     digsetobj.ajax_url,
            data:    get_ftp_details()+'&nounce='+nounce+'&action=dig_modify_addon&type='+t+'&plugin='+ addon+"&slug="+slug,
            success: function(data) {

                if (data.success === true) {
                    location.reload();
                    return;
                }else{

                    $this.removeClass("dig_updating");
                    ftpAvail = false;
                    loader.hide();

                    if(wp.updates.shouldRequestFilesystemCredentials) {
                        wp.updates.filesystemCredentials.available = false;
                        wp.updates.maybeRequestFilesystemCredentials(event);
                        wp.updates.showErrorInCredentialsForm(data.data.errorMessage);
                    }else{
                        showDigMessage(data.data.errorMessage);
                    }
                }
            },
            error: function() {
                $this.removeClass("dig_updating");
                loader.hide();
                showDigMessage(digsetobj.Error);
            }
        });
    })



    jQuery( '#upgrade' ).on( 'click', function( event ) {
        loader.show();
        setTimeout(function () {
            loader.hide();
            form_sub.trigger('click');
        },100);

    });



    function get_ftp_details(){
        var hostname       = jQuery( '#hostname' ).val();
        var username       = jQuery( '#username' ).val();
        var password       = jQuery( '#password' ).val();
        var connection_type = jQuery( 'input[name="connection_type"]:checked' ).val();
        var fsNonce            = jQuery( '#_fs_nonce' ).val();



        var ftp_details = "_ajax_nonce="+wp.updates.ajaxNonce+"&_fs_nonce="+fsNonce+"&hostname="+hostname+"&username="+username+"&password="+password+"&connection_type="+connection_type;
        return ftp_details;

    }



    jQuery("#digits-update").find('p').each(function(){
        jQuery(this).find('.thickbox').attr({'href':'https://digits.unitedover.com/changelog','target':'_blank','class':''});
    })
    jQuery(".dig_slug_addon").each(function(){
        var slug = jQuery(this).attr('slug');
        var p = jQuery("#"+slug+"-update").find('p');

        p.find('.thickbox').remove();

        var content = p.html();
        content = content.replace("or", "");

        p.html(content).find('a').css("text-transform","capitalize");

    })



});
