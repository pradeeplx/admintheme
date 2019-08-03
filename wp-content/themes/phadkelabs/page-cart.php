<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package medirashed
 */

get_header();
?>

<div class="main_content_area">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
                <article id="post-7" class="post-7 page type-page status-publish hentry">
            	    <div class="entry-content">
                        <?php global $woocommerce; ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="cart-menu">
	    					        <ul>
		    				    	    <li id="cart" class="active done"><a href="#">My Cart</a></li>
			    				        <li id="login"><a href="#">Log in & Checkout</a></li>
				    		    	    <li id="pay"><a href="#">Payment</a></li>
					    		       <li id="booking"><a href="#">Booking Status</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <section class="cart staps">
				            <div class="cartSection bg-white">
                                <?php echo do_shortcode("[woocommerce_cart]"); ?>
                            </div>
                        </section>
                        <section class="staps login-steps">
				<div class="loginSection bg-white <?php echo $class?>">
					<div class="row">
						<div class="col-md-8">
							<div class="cart-login-area">
							
								<div class="header-login cart-login-title">
									<h2>Log In to your account & proceed</h2>
								</div>
								<div id="mf-header-login-toggle"><span href="?login=true" onclick="jQuery('this').digits_login_modal(jQuery(this));return false;" attr-disclick="1" class="digits-login-modal" type="1"><span>Login / Register</span></span></div>
								
								<li class="login-hide">
									<img src="http://149.28.141.144/wp-content/themes/phadkelabs/images/header/Group 2184.png" alt="">
									<a href="#">Log in</a>
									
									<a href="#">Sign up</a>
								</li>
								<div class="cart-login-form">
                                    <?php echo do_shortcode("[wc_login_form_bbloomer]"); ?>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="new-register-area">
								<!-- Single register -->
								<div class="new-register">
                                    <h6>Not registered yet?</h6>
                                    <p>Sign Up While Check Out</p>
									<a id="signupcheck" href="#">Sign Up</a>
									<p>or</p>
								</div>
								<!-- Single register -->
								<div class="new-register">
									<h6>Not ready to sign up/ register? <br> Don't even worry about it.</h6>
                                    <p>Sign Up as Guest</p>
                                    <a id="guestSignup" href="#">Sign Up</a>
								</div>
							</div>
						</div>
					</div>
                </div> 
            </section>
            <section class="staps user-login-checkout bg-white">
				            <div class="login-checkout-title">
					            <h2>Checkout</h2>
				            </div>
				            <div class="loginCheckout-area">    
                                <?php do_action("woocommerce_checkout_login_form"); ?>
                                <?php echo do_shortcode("[woocommerce_checkout]"); ?>
                            </div>
            </section>
			<div class="status-done">
			<?php $url = site_url( '/cart/' );?>
		<a id="checkoutDone" href="<?php echo $url?>">Done</a>
	</div>
                    </diV>
                </article>
			</div>
		</div>
	</div>
</div>
<script>
    jQuery(function($){
        $("#login-tap").click(function(){
            setTimeout(() => {
                if($('body').hasClass('logged-in')){
                    console.log("sad");
                    $('article section').css('display','none');
                    $('article section.user-login-checkout').css('display','block');
            }
            }, 100);
        });
		if ($('body').hasClass('woocommerce-order-received')){
			$('article section').css('display','none');
			$('article section.user-login-checkout').css('display','block');
		}
		$("input[name='inlineRadioOptions']").change(function(){
			selected_value = this.value;
			console.log(selected_value);
			if(selected_value == 'option2'){
				$('.check_pref_hl').text('Lab Visits Charges:');
			}
			else{
				$('.check_pref_hl').text('Home Visits Charges:');
			}
			
		});
		var ship = $("input[name='shipping_method[0]']:checked").val();
		if(ship == 'flat_rate:1'){
			$("#cartCheckbox").prop('checked',true);
		}
		else{
			$('.shipping_courier').css('display','none');
		}
		$('#cartCheckbox').change(function(){
			if($(this).is(":checked")) {
				$("#shipping_method_0_flat_rate1").prop('checked',true).trigger('change');
			}
			else{
				$("#shipping_method_0_local_pickup3").prop('checked',true).trigger('change');
			}
			setTimeout(() => {
				location.reload();
			}, 200);
		});
		$('.woocommerce-remove-coupon').click(function(){
			setTimeout(() => {
				location.reload();
			}, 200);
		});
		setTimeout(() => {
			console.log("wow");
			$('.login-hide a').trigger('click');
			setTimeout(() => {
				$('.modal.fade').css('display','none');
			}, 100);
			
		}, 500);
        //console.log("nice"+ship);
    });
</script>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php
get_footer();
