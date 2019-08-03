<?php
/**
 * Template Name: Checkout Page Template
 * This template will only works for SuperAdmin
 */

$the_ID = get_the_ID();
if(!$the_ID)
  return;
  $typo = get_post_meta( get_the_ID(), '_tes_layout_typo',true);
   //echo "<pre>"; print_r($typo); echo "</pre>";
  if(!is_array($typo)){
    $typo['font_family'] = 'Default Layout Font';
  }
  if( $typo['font_family'] == 'Default Layout Font' ){

      $value = get_post_meta( get_the_ID(), '_tes_layout_id',true) ? get_post_meta( get_the_ID(), '_tes_layout_id',true) :get_user_meta(get_current_user_id(), '_tes_temp_lay_id_', true );
      $font_family = $typo['font_family'];
      
    }else{
      $font_family = $typo['font_family'];
    }
    $font_family = 'Open Sans';
    echo '<link href="https://fonts.googleapis.com/css?family='.$font_family.'" rel="stylesheet">';

?>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?php echo TSRCT_CT_CSS; ?>/checkout-page-preview.css" media="screen">
   
    <?php wp_head(); ?>
    <title>Preview Checkout</title>
    
      <style>
       #tes-cc-template-page{
        font-family: <?php echo $font_family; ?>; !important;
       }
      </style>

</head>
<body class="tes-cc-template" id="tes-cc-template-page">
	<?php

    $layoutid = get_post_meta( get_the_ID(), '_tes_layout_id',true);
    $strc_value = get_post_meta( get_the_ID(), '_tes_layout_layoutContent_odersummary',true) ? get_post_meta( get_the_ID(), '_tes_layout_layoutContent_odersummary',true) : 'enable';

    $header_status = get_post_meta( get_the_ID(), '_tes_layout_hf_header_status',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_header_status',true) : 'no';
    $footer_status = get_post_meta( get_the_ID(), '_tes_layout_hf_footer_status',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_footer_status',true) : 'no';

    $header_text = get_post_meta( get_the_ID(), '_tes_layout_hf_header_text',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_header_text',true) : 'Checkout';

	 $header_text_align = get_post_meta( get_the_ID(), '_tes_layout_hf_header_text_alignment',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_header_text_alignment',true) : 'center';


    if( $header_status != 'site' ){
      ?>
     <style type="text/css">body header, .woocommerce-breadcrumb{ display: none !important; }</style>
      <?php
    }
    if( $footer_status != 'site' ){
      ?>
     <style type="text/css">body footer{ display: none !important; }</style>
      <?php
    }
    //get_header();

    $typo = get_post_meta( get_the_ID(), '_tes_layout_typo',true);
    if(!is_array($typo)){
      $typo['font_family'] = 'Default Layout Font';
      $typo['button_bck_color'] = '#dd3333';
      $typo['button_txt_color'] = '#ffffff';
      $typo['button_hover_color'] = '#dd4d4d';
    }

    $section_heading  = get_post_meta( $the_ID, '_tes_layout_section_heading',true);
    $section_bck_color  = isset($section_heading['bck_color']) ? $section_heading['bck_color'] : '#000';
    $section_txt_color  = isset($section_heading['txt_color']) ? $section_heading['txt_color'] : '#fff';

    if( $typo['font_family'] != 'Default Layout Font'){
      echo '<link href="https://fonts.googleapis.com/css?family='.$typo['font_family'].'" rel="stylesheet">';
      ?>
      <style type="text/css">
        .checkout-form-container,
        #tes-cc-template-page .right-sidebar .title-area,
        #tes-cc-template-page .layout-3 .header h2.header-title{
          font-family: <?php echo $typo['font_family']; ?> !important;
        }


      </style>
      <?php
    }
  ?>
  <style type="text/css">
    #tes-cc-template-page .layout-3 .panel-body{
      border-color: <?php echo Checkout_Page_Layout_Frontend::hex2rgba($section_bck_color,0.2); ?>;
    }
     #tes-cc-template-page .layout-3 .header h2.header-title{
      background: <?php echo $section_bck_color; ?>;
      color: <?php echo $section_txt_color; ?>;
    }
    #tes-cc-template-page .checkout-form-container .form-side .btn-block,
    #tes-cc-template-page .layout-3 a.btn-block,
    #tes-cc-template-page .layout-2 .fifth-part a,
    #tes-cc-template-page .layout-2 button#place_order{
      background-color: <?php echo $typo['button_bck_color']; ?>;
      color: <?php echo $typo['button_txt_color']; ?>
    }
    #tes-cc-template-page .checkout-form-container .form-side .btn-block:hover,
    #tes-cc-template-page .layout-3 a.btn-block:hover,
    #tes-cc-template-page .layout-2 .fifth-part a:hover,
    #tes-cc-template-page .layout-2 button#place_order:hover{
      background-color: <?php echo $typo['button_hover_color']; ?>;
    }
  </style>
  <?php
  if( $header_status == 'custom' ){
    $header_img = get_post_meta( get_the_ID(), '_tes_layout_hf_header_img',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_header_img',true) : '';
    if( $header_img ){
      $header_img_align = get_post_meta( get_the_ID(), '_tes_layout_hf_header_img_alignment',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_header_img_alignment',true) : 'center';
      ?>
      <div class="tes_cc_lay_custom_logo <?php echo "tes-cc-custom-header-logo-".$header_img_align; ?>">
        <img src="<?php echo $header_img; ?>" />
      </div>
      <?php
    }
  }
  	if( $header_status == 'text' ){
 		?>
 		<div class="woocommerce">
 			<h1 style="text-align: <?php echo $header_text_align; ?> "><?php echo $header_text; ?></h1>
 		</div>
 		<?php
 	}
  ?>


        <div class="checkout-form-container layout-<?php echo $layoutid; ?> layout-div">
        	<?php if( $layoutid == 1 || $layoutid == 2 ){ ?>
            <div class="info-side right">
              <?php if( $strc_value == 'enable' ){ ?>
                <div class="right-sidebar">
                        <div class="title-area">Summary</div>
                        <ul class="cart-area summery-area">
                          <li>
                        <div class="summarySubtotal">
                        <span class="pull-left">Subtotal</span>  <span class="pull-right">$1,020</span>
                        </div>

                        <div class="summaryShipping">
                        <span>Shipping</span>  <span>Flat rate: $0.00</span>
                        </div>

                        <div class="summaryTotal">
                        <span>Total</span>  <span>$1,020</span>
                        </div>


                        </li>
                        </ul>
                  </div>
                <div class="right-sidebar">
                  <div class="title-area"><a href="#">In Your Cart (1)</a><a href="#" style="float: right; font-size: 12px; text-decoration: underline; ">Edit</a></div>
                  <ul class="cart-area">
                  	<li>
                      	<div class="cartItem-image"><img src="<?php echo TSRCT_CT_IMG; ?>/product-placeholder.jpg" alt=""/></div>
                          <div class="cartItem-summary">
                          	<span class="cart-title">Sample Product</span>
                              <span class="cart-describe">
                							<b>Qty :</b> 1
                              </span>
                              <span class="cart-price">$1,100</span>
                          </div>
                      </li>
                  </ul>
                </div>
              <?php
                }
              ?>
              <div class="right-sidebar side2">
              <div class="cart-area">
                <?php
                  echo get_post_meta( $the_ID, '_tes_layout_content_',true);
                ?>
                </div>
                </div>
            </div>
          <?php } ?>
          <?php if( $layoutid == 1 || $layoutid == 2 ){ ?>
            <div class="form-side left">
                <?php if( $layoutid == 1)
                { ?>
                	<form action="" method="POST" autocomplete="on">
                    <div class="panel">
                      	<div class="panel-body">
                          	<div class="header header-active">
                            	<div class="media">
                                  <div class="media-left">
                                    <h4 class="header-number">1</h4>
                                  </div>
                                  <div class="media-body">
                                    <h2 class="header-title">
                                      Billing Details
                                    </h2>
                                  </div>
                              </div>
                            </div>



                            <div class="checkout-personal-info-container layout-1-billing-body-box">
                              <div class="form-group"><label class="control-label" for="checkout_offer_member_name">Full Name</label><input class="form-control" type="text"></div>

                              <div class="form-group"><label class="control-label" for="checkout_offer_member_email">Email Address</label><input class="form-control" type="email"></div>

                              <div class="form-group"><label class="control-label" for="checkout_offer_member_password">Create Password</label><input class="form-control" type="password"></div>

                              <div class="form-group"><label class="control-label" for="checkout_offer_member_password_confirmation">Confirm Password</label><input class="form-control" type="password"></div>
                              <a class="btn btn-lg btn-block btn-primary billing-continue" href="javascript:void(0);">Continue</a>
                            </div>



                        </div>
                    </div>

                  	<div class="panel">
                      	<div class="panel-body">
                          	<div class="header">
                            	<div class="media">
                                  <div class="media-left">
                                    <h4 class="header-number">2</h4>
                                  </div>
                                  <div class="media-body">
                                    <h2 class="header-title">
                                      Shipping Details
                                    </h2>
                                  </div>
                              </div>
                            </div>
                            <div class="checkout-personal-info-container layout-1-shipping-body-box">
                              <div class="form-group"><label class="control-label" for="checkout_offer_member_name">Full Name</label><input class="form-control" type="text"></div>

                              <div class="form-group"><label class="control-label" for="checkout_offer_member_email">Email Address</label><input class="form-control" type="email"></div>

                              <div class="form-group"><label class="control-label" for="checkout_offer_member_password">Create Password</label><input class="form-control" type="password"></div>

                              <div class="form-group"><label class="control-label" for="checkout_offer_member_password_confirmation">Confirm Password</label><input class="form-control" type="password"></div>
                              <a class="btn btn-lg btn-block btn-primary shipping-continue" href="javascript:void(0);">Continue</a>
                            </div>

                        </div>
                    </div>


                    <div class="panel">
                      	<div class="panel-body">
                          	<div class="header">
                            	<div class="media">
                                  <div class="media-left">
                                    <h4 class="header-number">3</h4>
                                  </div>
                                  <div class="media-body">
                                    <h2 class="header-title">
                                      Additional information
                                    </h2>
                                  </div>

                              </div>
                            </div>

                            <div class="checkout-personal-info-container layout-1-order-review-body-box topValueX">
                              <textarea class="form-control" name="name" placeholder="Order notes (optional)"></textarea>
                              <a class="btn btn-lg btn-block btn-primary place-order" href="javascript:void(0);">Continue</a>
                            </div>

                        </div>
                    </div>


                  	<div class="panel">
                      	<div class="panel-body">
                          	<div class="header">
                            	<div class="media">
                                  <div class="media-left">
                                    <h4 class="header-number">4</h4>
                                  </div>
                                  <div class="media-body">
                                    <h2 class="header-title">
                                      Order Review & Payment
                                    </h2>
                                  </div>

                              </div>
                            </div>



                            <div class="checkout-personal-info-container layout-1-order-review-body-box">
                              <!-- <div class="form-group"><label class="control-label" for="checkout_offer_member_name">Full Name</label><input class="form-control" type="text"></div>
                              <div class="form-group"><label class="control-label" for="checkout_offer_member_email">Email Address</label><input class="form-control" type="email"></div>
                              <div class="form-group"><label class="control-label" for="checkout_offer_member_password">Create Password</label><input class="form-control" type="password"></div>
                              <div class="form-group"><label class="control-label" for="checkout_offer_member_password_confirmation">Confirm Password</label><input class="form-control" type="password"></div> -->

                              <table class="table altTable">
                              <thead>
                                <tr>
                                  <th>Product</th>
                                  <th>Total</th>
                                </tr>
                              </thead>
                              <tbody>
                              <tr>
                                <td>Beanie with Logo &nbsp; <strong>× 5</strong>	</td>
                                <td><span><span>$</span>90.00</span></td>
                              </tr>
                              <tr>
                              <td>Beanie&nbsp;<strong>× 3</strong></td>
                              <td><span><span>$</span>54.00</span></td>
                              </tr>
                              </tbody>
                              <tfoot>
                              <tr>
                              <th>Subtotal</th>
                              <td><span><span>$</span>214.00</span></td>
                              </tr>
                              <tr>
                              <th>Shipping</th>
                              <td colspan="2" data-title="Shipping">Flat rate: <span><span>$</span>0.00</span></td>
                              </tr>
                              <tr>
                              <th>Total</th>
                              <td><strong><span><span >$</span>214.00</span></strong> </td>
                              </tr>
                              </tfoot>
                              </table>

                              <div class="fourth-partcc clearfix">
                              			<ul class="mtdPay">
                              			<li>
                                      <input id="payment_method_cheque" type="radio">
                              	      <label for="payment_method_cheque">Cash on delivery </label>
                                			<div class="payment_box payment_method_cheque">
                                			  <p>Pay with cash upon delivery.</p>
                                		  </div>
                              	   </li>
                              		</ul>

                              		<div class="form-row place-order">
                              			<div class="">
                              			<p>Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="#" target="_blank;">privacy policy</a></p>
                              			<p><input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" id="terms"> <span class="woocommerce-terms-and-conditions-checkbox-text">I accept all the <a href="#" target="_blank;">terms and conditions</a>.</span></p>
                              		</div>

                              		</div>
                              </div>

                              <a class="btn btn-lg btn-block btn-primary place-order" href="javascript:void(0);">Place Order</a>
                            </div>

                        </div>
                    </div>
                  </form>
                <?php
                }
                if( $layoutid == 2){
                ?>
                  <form action="" method="POST" autocomplete="on">
                    <h2>Billing Details</h2>
                    <div class="first-part">
                      <input id="firstName" name="firstName" size="10" type="text" required placeholder="First Name">
                      <input id="firstName" name="firstName" size="10" type="text" required placeholder="Last Name">
                      <input id="firstName" name="firstName" size="10" type="text" required placeholder="Email Address">
                    </div>
                    <div class="second-part">
                      <input id="firstName" name="firstName" size="10" type="text" required placeholder="Street Address">
                      <input id="firstName" name="firstName" size="10" type="text" required placeholder="Street Address Line 2 (optional)">
                      <input id="city" name="firstName" size="10" type="text" required placeholder="City">
                      <input id="firstName" name="firstName" size="10" type="text" required placeholder="State">
                      <input id="firstName" name="firstName" size="10" type="text" required placeholder="Zip/Postal Code">
                      <?php

                          global $woocommerce;
                          $countries_obj   = new WC_Countries();
                          $countries   = $countries_obj->__get('countries');

                          echo '<select class="checkout">';
                          foreach ($countries as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php
                          }
                          echo '</select>';
                      ?>
                    </div>
                    <h2>Order Review & Payment</h2>
                    <div class="third-part">
                      <table class="table">
                      <thead>
                        <tr>
                          <th>Product</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                      <tr>
                        <td>Beanie with Logo &nbsp; <strong>× 5</strong>	</td>
                        <td><span><span>$</span>90.00</span></td>
                      </tr>
                      <tr>
                      <td>Beanie&nbsp;<strong>× 3</strong></td>
                      <td><span><span>$</span>54.00</span></td>
                      </tr>
                      </tbody>
                      <tfoot>
                      <tr>
                      <th>Subtotal</th>
                      <td><span><span>$</span>214.00</span></td>
                      </tr>
                      <tr>
                      <th>Shipping</th>
                      <td colspan="2" data-title="Shipping">Flat rate: <span><span>$</span>0.00</span></td>
                      </tr>
                      <tr>
                      <th>Total</th>
                      <td><strong><span><span >$</span>214.00</span></strong> </td>
                      </tr>
                      </tfoot>
                      </table>

                    </div>

                   <h2>Payment Info</h2>

                    <div class="fourth-part">
                    			<ul>
                    			<li>
                            <input id="payment_method_cheque" type="radio">
                    	      <label for="payment_method_cheque">Check payments 	</label>
                      			<div class="payment_box payment_method_cheque">
                      			  <p>Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.</p>
                      		  </div>
                    	   </li>
                    		</ul>

                    		<div class="form-row place-order">
                    			<div class="">
                    			<p>Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="#" target="_blank;">privacy policy</a></p>
                    			<p><input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" id="terms"> <span class="woocommerce-terms-and-conditions-checkbox-text">I accept all the <a href="#" target="_blank;">terms and conditions</a>.</span></p>
                    		</div>
                    		<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="Place order" data-value="Place order">Place order</button>
                    		</div>
                    </div>

                  </form>
                <?php
                }
                ?>
            </div>
          <?php }
          if( $layoutid == 3 ){ ?>

            <div class="info-side1 right">
                <?php if( $strc_value == 'enable' ){ ?>
              		<div class="right-sidebar">
                        <div class="title-area">Summary</div>
                        <ul class="cart-area summery-area">
                        	<li>
                        <div class="summarySubtotal">
                        <span class="pull-left">Subtotal</span>  <span class="pull-right">$1,020</span>
                        </div>

                        <div class="summaryShipping">
                        <span>Shipping</span>  <span>Flat rate: $0.00</span>
                        </div>

                        <div class="summaryTotal">
                        <span>Total</span>  <span>$1,020</span>
                        </div>


                        </li>
                        </ul>
                  </div>
                <?php } ?>

              	<div class="right-sidebar">
                      <div class="title-area"><a href="#">In Your Cart (1)</a> <a href="#" style="float: right; font-size: 12px; text-decoration: underline; text-transform: none; ">Edit</a></div>
                      <ul class="cart-area">
                      	<li>
                          	<div class="cartItem-image"><img src="<?php echo TSRCT_CT_IMG; ?>/product-placeholder.jpg" alt=""/></div>
                              <div class="cartItem-summary">
                              	<span class="cart-title">Sample Product</span>
                                  <span class="cart-describe">
  								                Qty : 1
                                  </span>
                                  <span class="cart-price">$1,020</span>
                              </div>
                          </li>
                      </ul>
                </div>

                <div class="right-sidebar side2">
                      <ul class="cart-area return">
                      	<li>
                          <?php
                          echo get_post_meta( $the_ID, '_tes_layout_content_',true);
                        ?>
                      </li>
                      </ul>
                </div>
            </div>
            <div class="form-side1 left">
              <form action="" method="POST" autocomplete="on">
                <div class="panel">
                    <div class="panel-body">
                        <div class="header header-active">
                          <h2 class="header-title">1. Billing Details</h2>
                        </div>

                        <div class="checkout-personal-info-container layout-3-billing-body-box">

                          <input class="form-control" type="text" placeholder="First Name">

                          <input class="form-control" type="text" placeholder="Last Name">

                          <input class="form-control" type="text" placeholder="Address">

                          <input class="form-control" type="text" placeholder="City">

                          <input class="form-control" type="text" placeholder="Postal Code">

                          <?php

                          global $woocommerce;
                          $countries_obj   = new WC_Countries();
                          $countries   = $countries_obj->__get('countries');

                          echo '<select>';
                          foreach ($countries as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php
                          }
                          echo '</select>';

                          global $woocommerce;
                          $countries_obj   = new WC_Countries();
                          $countries   = $countries_obj->__get('countries');
                          $default_country = $countries_obj->get_base_country();
                          $default_county_states = $countries_obj->get_states( $default_country );

                            //echo '<pre>'; print_r($default_county_states); echo '</pre>';
                          echo '<select>';
                          foreach ($default_county_states as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php
                          }
                          echo '</select>';

                          ?>
                          <input class="form-control" type="text" placeholder="Email">
                          <input class="form-control" type="text" placeholder="Phone Number">
                          <input class="form-control" type="text" placeholder="Fax">
                          <a class="btn btn-lg btn-block btn-primary billing-continue" href="javascript:void(0);">Continue</a>

                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-body">
                        <div class="header">
                          <h2 class="header-title">2. Shipping Details</h2>
                        </div>

                        <div class="checkout-personal-info-container layout-3-shipping-body-box">

                          <input class="form-control" type="text" placeholder="First Name">

                          <input class="form-control" type="text" placeholder="Last Name">

                          <input class="form-control" type="text" placeholder="Address">

                          <input class="form-control" type="text" placeholder="City">

                          <input class="form-control" type="text" placeholder="Postal Code">

                          <?php
                            global $woocommerce;
                            $countries_obj   = new WC_Countries();
                            $countries   = $countries_obj->__get('countries');
                            $default_country = $countries_obj->get_base_country();
                            $default_county_states = $countries_obj->get_states( $default_country );

                            echo '<select>';
                            foreach ($default_county_states as $key => $value) {
                              ?>
                              <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                              <?php
                            }
                            echo '</select>';
                          ?>

                          <input class="form-control" type="text" placeholder="Email">

                          <input class="form-control" type="text" placeholder="Phone Number">

                          <a class="btn btn-lg btn-block btn-primary shipping-continue" href="javascript:void(0);">Continue</a>

                        </div>

                    </div>
                </div>


                <div class="panel">
                    <div class="panel-body">
                        <div class="header">
                          <h2 class="header-title">3. ADDITIONAL INFORMATION</h2>
                        </div>

                        <div class="checkout-personal-info-container layout-3-order-review-body-box topValueX">
                          <textarea class="form-control" name="name" placeholder="Order notes (optional)"></textarea>
                          <a class="btn btn-lg btn-block btn-primary place-order" href="javascript:void(0);">Continue</a>
                        </div>

                    </div>
                </div>


    		        <div class="panel">
                    <div class="panel-body">
                        <div class="header">
                          <h2 class="header-title">4. Order Review & Payment</h2>
                        </div>

                        <div class="checkout-personal-info-container layout-3-order-review-body-box">

                          <!-- <input class="form-control" type="text" placeholder="First Name">
                          <input class="form-control" type="text" placeholder="Last Name">
                          <input class="form-control" type="text" placeholder="Address">
                          <input class="form-control" type="text" placeholder="City">
                          <input class="form-control" type="text" placeholder="Postal Code">
                          <select placeholder="State">
                            <option>State1</option>
                            <option>State2</option>
                            <option>State3</option>
                            <option>State4</option>
                          </select>
                          <input class="form-control" type="text" placeholder="Email">
                          <input class="form-control" type="text" placeholder="Phone Number">
                          <p class="checkout-support">Your privacy is important to us. We will only contact you if there is an issue with your order.</p> -->

                          <table class="table altTable">
                          <thead>
                            <tr>
                              <th>Product</th>
                              <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                          <tr>
                            <td>Beanie with Logo &nbsp; <strong>× 5</strong>	</td>
                            <td><span><span>$</span>90.00</span></td>
                          </tr>
                          <tr>
                          <td>Beanie&nbsp;<strong>× 3</strong></td>
                          <td><span><span>$</span>54.00</span></td>
                          </tr>
                          </tbody>
                          <tfoot>
                          <tr>
                          <th>Subtotal</th>
                          <td><span><span>$</span>214.00</span></td>
                          </tr>
                          <tr>
                          <th>Shipping</th>
                          <td colspan="2" data-title="Shipping">Flat rate: <span><span>$</span>0.00</span></td>
                          </tr>
                          <tr>
                          <th>Total</th>
                          <td><strong><span><span >$</span>214.00</span></strong> </td>
                          </tr>
                          </tfoot>
                          </table>

                          <div class="fourth-partcc clearfix">
                                <ul class="mtdPay">
                                <li>
                                  <input id="payment_method_cheque" type="radio">
                                  <label for="payment_method_cheque">Cash on delivery </label>
                                  <div class="payment_box payment_method_cheque">
                                    <p>Pay with cash upon delivery.</p>
                                  </div>
                               </li>
                              </ul>

                              <div class="form-row place-order">
                                <div class="">
                                <p>Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="#" target="_blank;">privacy policy</a></p>
                                <p><input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" id="terms"> <span class="woocommerce-terms-and-conditions-checkbox-text">I accept all the <a href="#" target="_blank;">terms and conditions</a>.</span></p>
                              </div>

                              </div>
                          </div>

                          <a class="btn btn-lg btn-block btn-primary place-order" href="javascript:void(0);">Place Order</a>

                        </div>

                    </div>
                </div>


              </form>
            </div>
            <style type="text/css">

            </style>
          <?php } ?>
        </div>


	<?php //get_footer(); ?>
  <?php
    $footer_status = get_post_meta( get_the_ID(), '_tes_layout_hf_footer_status',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_footer_status',true) : 'no';

    $footer_custom_element = get_post_meta( get_the_ID(), '_tes_layout_hf_footer_custom_element',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_footer_custom_element',true) : 'menu';

    $footer_custom_element_align = get_post_meta( get_the_ID(), '_tes_layout_hf_footer_custom_element_alignment',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_footer_custom_element_alignment',true) : 'center';

    $footer_img = get_post_meta( get_the_ID(), '_tes_layout_hf_footer_img',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_footer_img',true) : '';

    $footer_menu = get_post_meta( get_the_ID(), '_tes_layout_hf_footer_custom_element_menu',true) ? get_post_meta( get_the_ID(), '_tes_layout_hf_footer_custom_element_menu',true) : '';

    if( $footer_status == 'custom' )
    {
  ?>
    <div id="tes_cc_lay_custom_footer">

        <?php if( $footer_custom_element == 'image' ){ ?>
          <div class="footer_logo footer_logo-<?php echo $footer_custom_element_align; ?>">
            <div class="center-vertical">
              <img src="<?php echo $footer_img; ?>" />
            </div>
        	</div>
        <?php } ?>
        <?php if( $footer_custom_element == 'menu' ){ ?>
     	  <div class="footer_menu menu-<?php echo $footer_custom_element_align; ?>">
          <div class="center-vertical">
            <?php echo wp_nav_menu( array('menu'=> $footer_menu)); ?>
          	<!-- <ul>
              	<li>
                  	<a href="#">Home</a>
                  </li>
                  <li>
                  	<a href="#">About</a>
                  </li>
                  <li>
                  	<a href="#">What to do</a>
                  </li>
                  <li>
                  	<a href="#">Contact</a>
                  </li>
              </ul> -->
          </div>
      	</div>
        <?php } ?>

    </div>
  <?php
    }
  ?>
  
<?php wp_footer(); ?>
<script type="text/javascript" src="<?php echo TSRCT_CT_JS; ?>/tes-ct-prev.js"></script>
</body>
</html>
