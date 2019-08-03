<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
/* Default comment here */ 

jQuery(document).ready(function() {
  // Overall
  jQuery("#dig-ucr-container .dig-content.dig-modal-con").prepend('<div class="mf-popup-tabs"><div class="mf-popup-login-tab">Log In</div><div class="mf-popup-signup-tab">Sign Up</div><div class="mf-clearfix"></div></div>');
  jQuery("#dig-ucr-container .mf-popup-tabs > div:first").addClass("active");
  
  // Login
  jQuery("#dig-ucr-container .dig-log-par .digloginpage").prepend('<div class="login-description popup-description">Hello There!<br>Log in to your account</div>');
  jQuery("#dig-ucr-container .dig-log-par .digloginpage > form").wrapInner('<div id="mf-login-left-half"></div>');
  jQuery('<div id="mf-login-middle">or</div><div id="mf-login-right-half"></div>').insertAfter("#dig-ucr-container .dig-log-par .digloginpage > form #mf-login-left-half");
  var loginotpfield = jQuery("#dig-ucr-container .dig-log-par .digloginpage #mf-login-left-half .dig_login_otp");
  loginotpfield.appendTo("#dig-ucr-container .dig-log-par .digloginpage #mf-login-right-half");
  var loginotpbutton = jQuery("#dig-ucr-container .dig-log-par .digloginpage #mf-login-left-half .button.loginviasms");
  loginotpbutton.appendTo("#dig-ucr-container .dig-log-par .digloginpage #mf-login-right-half");
  var loginresendotp = jQuery("#dig-ucr-container .dig-log-par .digloginpage #mf-login-left-half .dig_resendotp");
  loginresendotp.appendTo("#dig-ucr-container .dig-log-par .digloginpage #mf-login-right-half");
  var loginbutton = jQuery("#dig-ucr-container .dig-log-par .digloginpage #mf-login-left-half input.button[type=submit]");
  loginbutton.insertAfter("#dig-ucr-container .dig-log-par .digloginpage #mf-login-right-half");
  loginbutton.before('<div class="mf-clearfix"></div>');
  jQuery("#dig-ucr-container .dig-log-par .digloginpage #mf-login-left-half .forgotpassworda").text("Forgot Password?");
  jQuery("#dig-ucr-container .dig-log-par .digloginpage .minput > label").each(function() {
  	var label = jQuery(this);
    label.prependTo(label.parent());
  });
  jQuery("#dig-ucr-container .dig-log-par .digloginpage #mf-login-left-half .minput:first-child label").text("Email id");
  jQuery("#dig-ucr-container .dig-log-par .digloginpage #mf-login-left-half .minput:first-child input").attr("placeholder","Enter Registered Email");
  jQuery("#dig-ucr-container .dig-log-par .digloginpage #mf-login-left-half .minput:nth-child(2) input").attr("placeholder","Enter Password");
  jQuery("#dig-ucr-container .dig-log-par .digloginpage .minput.dig_login_otp input").attr("placeholder","Enter OTP");
  //jQuery("#dig-ucr-container .dig-log-par .digloginpage #dig_login_va_otp").text("→");
  var loginbutton = jQuery("#dig-ucr-container .dig-log-par .digloginpage #dig_login_va_otp");
  loginbutton.parent().append(loginbutton);//insertAfter("#dig-ucr-container .dig-log-par .digloginpag #dig_lo_resend_otp_btn");
  var phoneelement = jQuery( "#dig-ucr-container .dig-log-par .digloginpage #mf-login-left-half .minput:first-child" );
  var emailelement = phoneelement.clone();
  emailelement.insertAfter(phoneelement);
  emailelement.children("input[type=text]").removeAttr("class").removeAttr("name");
  emailelement.find(".countrycodecontainer.logincountrycodecontainer").remove();
  phoneelement.find("label").text("Mobile No");
  phoneelement.find("input").attr("placeholder","Enter Mobile Number");
  phoneelement.prependTo( "#dig-ucr-container .dig-log-par .digloginpage #mf-login-right-half" );
  
  // Register
  jQuery("#dig-ucr-container .dig-log-par .register").prepend('<div class="signup-description popup-description">Don\'t have an account?<br>Let\'s get started</div>');
  jQuery('<div class="condition-login-area"><p>By clicking on sign up, you agree to our <a href="#">Conditions of Use and Privacy Policy.</a></p></div>').insertBefore("#dig-ucr-container .dig-log-par .register .registerbutton");
  jQuery("#dig-ucr-container .dig-log-par .register div.dig_reg_inputs").append('<div class="mf-clearfix"></div>');
  jQuery("#dig-ucr-container .dig-log-par .register .dig_reg_inputs .minput > label").each(function() {
  	var label = jQuery(this);
    label.prependTo(label.parent());
  });
  jQuery("#dig-ucr-container .dig-log-par .register .dig_register_otp label").each(function() {
  	var label = jQuery(this);
    label.prependTo(label.parent());
  });
  var regemailfield = jQuery("#dig-ucr-container .dig-log-par .register .dig_reg_inputs .dig_cs_email.minput");
  regemailfield.insertAfter("#dig-ucr-container .dig-log-par .register .dig_reg_inputs #dig_cs_lastname");
});
jQuery(document).on('click', '.mf-popup-login-tab', function() {
  jQuery(this).addClass("active").siblings().removeClass("active");
  jQuery("#dig-ucr-container .dig-log-par").children().hide();
  jQuery("#dig-ucr-container .dig-log-par .digloginpage").show();
});
jQuery(document).on('click', '.mf-popup-signup-tab', function() {
  jQuery(this).addClass("active").siblings().removeClass("active");
  jQuery("#dig-ucr-container .dig-log-par").children().hide();
  jQuery("#dig-ucr-container .dig-log-par .register").show();
});

// Replacing labels and place holders
jQuery(".minput#dig_cs_mobilenumber input").attr("placeholder","Enter Contact No");
jQuery(".minput#dig_cs_mobilenumber label").text("Contact");
jQuery(".minput#dig_cs_name input").attr("placeholder","First Name");
jQuery(".minput#dig_cs_name label").text("Name");
jQuery(".minput#dig_cs_lastname input").attr("placeholder","Last Name");
jQuery(".minput#dig_cs_password input").attr("placeholder","Create Password");
jQuery(".minput#dig_cs_confirmpassword input").attr("type","password").attr("placeholder","Re-enter Password");
jQuery("#dig-ucr-container .dig-log-par .register .dig_register_otp input").attr("placeholder","Enter OTP Code");
setTimeout(function() {
  jQuery(".dig_cs_email.minput input").attr("placeholder","Enter Email id");
  jQuery("#dig-ucr-container .dig-log-par .digloginpage .minput .mobile_field").css("padding-left","20px");
  jQuery(".minput#dig_cs_mobilenumber input").css("padding-left","20px");
}, 500);

jQuery(".header-login li.login-hide").click(function() {
	jQuery('this').digits_login_modal(jQuery(this));return false;
});
/*jQuery("body").on('DOMSubtreeModified', "#dig_login_va_otp", function() {
  setTimeout(function() {
    var thestr = jQuery(this).text();
    if (thestr.toLowerCase().indexOf("submit otp") >= 0) {
      alert("found");
      jQuery(this).addClass("mf-yellow");
      alert("class added");
      //jQuery("#dig-ucr-container .dig-log-par .digloginpage input.btn[type=submit]").replaceWith(this);
    }
  }, 500);
});*/</script>
<!-- end Simple Custom CSS and JS -->
