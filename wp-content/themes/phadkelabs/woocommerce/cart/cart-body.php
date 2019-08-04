<!--==============================================-->
	<!--============= CART Area Start ================-->
	<!--==============================================-->
	<?php global $woocommerce; ?>
	<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents 33cart-area">
		<div class="container-fluid">
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

			<!-- Multi Step Content -->
			<section class="cart staps">
				<!-- MY CART SECTION START -->
				<div class="cartSection bg-white">
					<div class="row">
						<div class="col-md-3 col-4">
							<!-- Single Title -->
							<div class="cart-table-title">
								<span>Items in cart</span>
							</div>
						</div> <!-- Single Title End -->
						
						<div class="col-md-3 col-4">
							<!-- Single Title -->
							<div class="cart-table-title">
								<span>Available At</span>
							</div>
						</div> <!-- Single Title End -->
						
						<div class="col-md-3 col-4">
							<!-- Single Title -->
							<div class="cart-table-title">
								<span>Price</span>
							</div>
						</div> <!-- Single Title End -->
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="cart-test-table">
							
							
							
							
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>					
		<?php
			$p_counter=0;
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
						
					<ul class="cart-table-info woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>" >
							<li class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>" ><a href="#"><?php $p_counter++; ?> 
							<?php
								if ( ! $product_permalink ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $p_counter.'.'.$_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
								} else {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $p_counter.'.'.$_product->get_name() ), $cart_item, $cart_item_key ) );
								}

								do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

								// Meta data.
								echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

								// Backorder notification.
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
								}
								?>
							</a></li>
							
							
							<li><img src="<?php echo get_template_directory_uri();?>/images/cart_page/home.png" alt=""> Home Visit (2)</li>
							<li><img src="<?php echo get_template_directory_uri();?>/images/cart_page/lab.png" alt=""> Lab Visit</li>
							<li class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>" >
								
								<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</li>
							<li class="product-remove">
								<?php
									// @codingStandardsIgnoreLine
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-trash-alt"></i></a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									), $cart_item_key );
								?>
							</li>
					</ul>
					
				<?php
				}
			}
			?>
			
				</div>
				
				<?php do_action( 'woocommerce_cart_contents' ); ?>
							<div class="cart-test-more">
								<div class="add-more">		
									<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><i class="fa fa-plus"></i><?php esc_html_e( 'Add More', 'woocommerce' ); ?></a>
								</div>
								<div class="select-preference">
									<h6>Select Preference</h6>
									<div class="preferenceForm">
										<form action="" method="POST">
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="radio" name="inlineRadioOptions" id="homeVisit" value="option1">
												<label class="form-check-label" for="homeVisit">Home Visit</label>
											</div>
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
												<label class="form-check-label" for="labVisit">Lab Visit</label>
											</div>
											<div class="form-check">
												<input class="form-check-input"  onclick=(add_extra_cost_to_total()) type="checkbox" value="" id="cartCheckbox">
												<label class="form-check-label" for="cartCheckbox">Courier Reports (Rs. 50/- Extra)</label>
											</div>
											</div>
										</form>
									</div>
					<div class="cart-promo-apply actions">
					
	<?php if ( wc_coupons_enabled() ) { ?>
		<div class="coupon">
						
							<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'ENTER PROMO CODE', 'woocommerce' ); ?>" >
							<input type="submit" class="button2" name="apply_coupon"  value="<?php esc_attr_e( 'Apply', 'woocommerce' ); ?>">
							
							
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			
		
							
							
		<p class="coupon_success_notice">Promo Code Applied Successfully</p>
		<span style="padding: 7px 10px;height: 35px;">
			<span style="border:none;height:35px;padding:0">
				<?php wc_cart_totals_coupon_label( $coupon ); ?>
			</span>
			
			<a href="<?php echo wc_get_cart_url(); ?>?remove_coupon=<?php echo esc_attr( sanitize_title( $code ) ); ?>" class="woocommerce-remove-coupon" data-coupon="<?php echo esc_attr( sanitize_title( $code ) ); ?>"><i class="material-icons">close</i></a>
		
		</span>
						
		<?php endforeach; ?>
						
		<?php do_action( 'woocommerce_cart_coupon' ); ?>
		
		
		</div>
					
					
					
	<?php } ?>
						
						
						<!-- New Code for coupon starts -->
											
										
					

					<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
										
										<!-- New Code for coupon ends -->
										
										
										
										
									</div>
								</div>
								
						<?php do_action( 'woocommerce_after_cart_contents' ); ?>
							</div>
						<div class="col-md-4">
							<div class="cart-test-price <?php //echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">
							<?php do_action( 'woocommerce_before_cart_totals' ); ?>
								<table class="shop_table" >
									<tr>
										<td>Tests Subtotal (<?php  echo  $woocommerce->cart->cart_contents_count; ?>): </td>
										<td><?php wc_cart_totals_subtotal_html(); ?></td>
									</tr>
									<tr>
										<td>Home Visits Charges:</td>
										<td>Free</td>
									</tr>
									
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<td><?php echo 'Discount ';wc_cart_totals_coupon_label( $coupon ); ?></td>
				<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<tr class="shipping">
				<td><?php _e( 'Shipping', 'woocommerce' ); ?></td>
				<td data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<td><?php echo esc_html( $fee->name ); ?></td>
				<td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) :
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
					? sprintf( ' <small>' . __( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
					: '';

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<td><?php echo esc_html( $tax->label ) . $estimated_text; ?></td>
						<td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<td><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></td>
					<td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

				
		</table>
		
		
	</div>
							<div class="total-payable">
								<ul>
									<li><?php _e( 'Total Payable', 'woocommerce' ); ?></li>
									<li data-title="<?php esc_attr_e( 'Total Payable', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></li>
								</ul>
								
								
							</div>

							<?php do_action( 'woocommerce_after_cart_totals' ); ?>
						</div>
					</div>
					<div class="cart-footer">
						<p>Blood collection at home facility available across Mumbai, Navi Mumbai & Thane disctrict</p>
						<a id="login-tap" href="#">continue</a>
					</div>	
				</div>
			</section> <!-- MY CART SECTION END -->

			
			
			
	<!-- MY LOGIN SECTION START -->
		<section class="staps login-steps">
			<div class="loginSection bg-white">
				<?php //require_once 'extra/register_form.php'; ?>
				<?php echo do_shortcode('[login-with-ajax]'); ?>
				
				<?php //require 'customer_login.php'; ?>
				<?php //require_once 'extra/login_partition.php'; ?>	
			 <input id="logincheck" type="submit" value="LOG IN and Continue">	
			</div>
		</section> <!-- MY LOGIN SECTION END -->


		<!-- User Login Checkout Start -->
		<section class="staps user-login-checkout bg-white">
			<?php //require_once 'extra/register_form.php'; ?>			
			<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
				<?php do_action( 'woocommerce_register_form_start' ); ?>
				<?php do_action( 'woocommerce_register_form_end' ); ?>
			</form>
		</section><!-- User Login Checkout End -->


			<!-- User SignUp Checkout Start -->

			<section class="staps user-signup-checkout bg-white">
				<div class="login-checkout-title">
					<h2>Checkout as a guest</h2>
				</div>
				<div class="loginCheckout-area">
				<div class="row">
					<div class="col-md-6">
						<div class="chechout-patient-details">
							<form action="" method="">
								<div class="patient-name patientInput">
									<label for="name">Patients Name</label>
									<input class="mr-3" type="text" id="name" placeholder="First Name">
									<input type="text" id="name" placeholder="Last Name">
								</div>
								<div class="patient-DOB patientInput">
									<label for="DOB">Date of Birth</label>
									<input type="text" id="DOB" placeholder="Select Birth Date">
									<i class="far fa-calendar-alt selectDate"></i>
									<div class="patient-gender">
											<span>Gender</span>
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="radio" name="inlineRadioOptions" id="homeVisit" value="option1">
												<label class="form-check-label" for="homeVisit">Male</label>
											</div>
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
												<label class="form-check-label" for="labVisit">Female</label>
											</div>
										</div>
								</div>
								<div class="patient-email patientInput">
									<label for="email">Email</label>
									<input type="email" id="email" placeholder="Enter your Email">
								</div>
								<div class="patient-contact patientInput">
									<label for="contact">Contact</label>
									<input type="text" id="contact" placeholder="Enter Contact No">
								</div>
								<div class="patient-preference patientInput">
									<label for="preference">Preference</label>
									<input type="text" id="preference" placeholder="Select Date for Test">
									<i class="far fa-calendar-alt selectDate"></i>
									<div class="preferenceSelect">
										<select class="patientInput" name="" id="time-slot">
											<option value="">Select Time Slot</option>
											<option value="">10.00</option>
											<option value="">11.00</option>
											<option value="">12.00</option>
										</select>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="col-md-6 bl">
						<div class="chechout-patient-address">
							<div class="patient-adress-title">
								<h6>Select your address for Home Collection</h6>
								<p>Blood collection at home facilities available across Mumbai, Navi Mumbai & Thane district</p>
							</div>
							<div class="home-checkbox">
								<ul>
									<li>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
											<label class="form-check-label" for="labVisit">My Home</label>
										</div>
										<span>A601, Kailash tower...</span>
									</li>
									<li>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
											<label class="form-check-label" for="labVisit">Dad's Home</label>
										</div>
										<span>A601, Kailash tower...</span>
									</li>
									<li>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
											<label class="form-check-label" for="labVisit">My Home 2</label>
										</div>
										<span>A601, Kailash tower...</span>
									</li>
								</ul>
							</div>
							<div class="checkout-address-form">
								<span>or Add new address</span>
								<div class="patient-address patientInput">
									<label for="house">House No, Society</label>
									<input type="text" id="house" placeholder="ie. B-202, XYZ Co Operative Housing Society">
								</div>
								<div class="patient-street patientInput">
									<label for="street">Street Name</label>
									<input type="text" id="street" placeholder="ie. XYZ Street">
								</div>
								<div class="patient-pincode-area">
									<div class="patient-pincode">
										<label for="street">Pincode</label>
										<input type="text" id="street" placeholder="ie. 400020">
									</div>
									<div class="patient-locality">
										<label for="street">Locality</label>
										<input type="text" id="street" placeholder="Your Locality">
									</div>
								</div>
								<div class="patient-city patientInput">
									<label for="city">City</label>
									<input type="text" id="city" placeholder="Your City">
								</div>
								<div class="patient-save-address patientInput">
									<input type="checkbox" name="" id="">
									<label for="city">Save address as</label>
									<input type="text" id="city" placeholder="ie. My Home">
								</div>
							</div>
							<div class="select-payment-mode">
								<div class="payment-mode-title">
									<h6>Select Payment Mode</h6>
									<p>(<span>Extra 5% Discount </span> for all online payments)</p>
								</div>
								<div class="payment-mode-checkbox">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
										<label class="form-check-label" for="labVisit">Online Payment</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
										<label class="form-check-label" for="labVisit">Pay Cash at Checkin</label>
									</div>
								</div>
							</div>
							<div class="checkout-price">
								<div class="total-price">
									<table>
										<tr>
											<td>Cart Total:</td>
											<td>Rs.2850/-</td>
										</tr>
										<tr>
											<td>Extra 5% Discount: </td>
											<td>- Rs.142/-</td>
										</tr>
									</table>
									<div class="total-payable">
										<ul>
											<li>Total Payable:</li>
											<li>Rs.2707/-</li>
										</ul>
									</div>
								</div>
								<div class="confirm-checkout">
									<button id="confirmCheck">CONFIRM & PROCEED</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section><!-- User SignUp Checkout End -->


		<!-- Guest Checkout Start -->

		<section class="staps user-guest-checkout bg-white">
			<div class="login-checkout-title">
				<h2>Checkout as a guest</h2>
			</div>
			<div class="loginCheckout-area">
			<div class="row">
				<div class="col-md-6">
					<div class="chechout-patient-details">
						<form action="" method="">
							<div class="patient-name patientInput">
								<label for="name">Patients Name</label>
								<input class="mr-3" type="text" id="name" placeholder="First Name">
								<input type="text" id="name" placeholder="Last Name">
							</div>
							<div class="patient-DOB patientInput">
								<label for="DOB">Date of Birth</label>
								<input type="text" id="DOB" placeholder="Select Birth Date">
								<i class="far fa-calendar-alt selectDate"></i>
							<div class="patient-gender">
								<span>Gender</span>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="homeVisit" value="option1">
										<label class="form-check-label" for="homeVisit">Male</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
										<label class="form-check-label" for="labVisit">Female</label>
									</div>
								</div>
							</div>
							<div class="patient-email patientInput">
								<label for="email">Email</label>
								<input type="email" id="email" placeholder="Enter your Email">
							</div>
							<div class="patient-contact patientInput">
								<label for="contact">Contact</label>
								<input type="text" id="contact" placeholder="Enter Contact No">
							</div>
							<div class="patient-preference patientInput">
								<label for="preference">Preference</label>
								<input type="text" id="preference" placeholder="Select Date for Test">
								<i class="far fa-calendar-alt selectDate"></i>
								<div class="preferenceSelect">
									<select class="patientInput" name="" id="time-slot">
										<option value="">Select Time Slot</option>
										<option value="">10.00</option>
										<option value="">11.00</option>
										<option value="">12.00</option>
									</select>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="col-md-6 bl">
					<div class="chechout-patient-address">
						<div class="patient-adress-title">
							<h6>Select your address for Home Collection</h6>
							<p>Blood collection at home facilities available across Mumbai, Navi Mumbai & Thane district</p>
						</div>
						<div class="home-checkbox">
							<ul>
								<li>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
										<label class="form-check-label" for="labVisit">My Home</label>
									</div>
									<span>A601, Kailash tower...</span>
								</li>
								<li>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
										<label class="form-check-label" for="labVisit">Dad's Home</label>
									</div>
									<span>A601, Kailash tower...</span>
								</li>
								<li>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
										<label class="form-check-label" for="labVisit">My Home 2</label>
									</div>
									<span>A601, Kailash tower...</span>
								</li>
							</ul>
						</div>
						<div class="checkout-address-form">
							<span>or Add new address</span>
							<div class="patient-address patientInput">
								<label for="house">House No, Society</label>
								<input type="text" id="house" placeholder="ie. B-202, XYZ Co Operative Housing Society">
							</div>
							<div class="patient-street patientInput">
								<label for="street">Street Name</label>
								<input type="text" id="street" placeholder="ie. XYZ Street">
							</div>
							<div class="patient-pincode-area">
								<div class="patient-pincode">
									<label for="street">Pincode</label>
									<input type="text" id="street" placeholder="ie. 400020">
								</div>
								<div class="patient-locality">
									<label for="street">Locality</label>
									<input type="text" id="street" placeholder="Your Locality">
								</div>
							</div>
							<div class="patient-city patientInput">
								<label for="city">City</label>
								<input type="text" id="city" placeholder="Your City">
							</div>
							<div class="patient-save-address patientInput">
								<input type="checkbox" name="" id="">
								<label for="city">Save address as</label>
								<input type="text" id="city" placeholder="ie. My Home">
							</div>
						</div>
						<div class="select-payment-mode">
							<div class="payment-mode-title">
								<h6>Select Payment Mode</h6>
								<p>(<span>Extra 5% Discount </span> for all online payments)</p>
							</div>
							<div class="payment-mode-checkbox">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
									<label class="form-check-label" for="labVisit">Online Payment</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="inlineRadioOptions" id="labVisit" value="option2">
									<label class="form-check-label" for="labVisit">Pay Cash at Checkin</label>
								</div>
							</div>
						</div>
						<div class="checkout-price">
							<div class="total-price">
								<table>
									<tr>
										<td>Cart Total:</td>
										<td>Rs.2850/-</td>
									</tr>
									<tr>
										<td>Extra 5% Discount: </td>
										<td>- Rs.142/-</td>
									</tr>
								</table>
								<div class="total-payable">
									<ul>
										<li>Total Payable:</li>
										<li>Rs.2707/-</li>
									</ul>
								</div>
							</div>
							<div class="confirm-checkout">
								<button id="confirmCheck">CONFIRM & PROCEED</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section> <!-- Guest Checkout End -->



			<!-- Payment Section Start --> 

			<section class="staps payment-steps">
				<div class="paymentSection">
					<div class="cart-payment">
						<h1>REDIRECTED TO <br> PAYMENT GATEWAY</h1>
						<a id="paymentDone" href="#">PAYMENT DONE</a>
					</div>
				</div>
			</section><!-- Payment Section End -->

		<!-- Booking Status Start -->

<section class="staps booking-status">
	<section class="staps booking-status bg-white">
			<div class="login-checkout-title">
				<h2>Your order placed successfully!</h2>
			</div>
		<div class="booking-status-area">
			<div class="booking-status-head">
				<ul>
					<li>Booking Id: <span>APL83625</span></li>
					<li>Date of booking: <span> 08 Jul 2019</span></li>
					<li>Invoice No: <span>INVPL2033</span></li>
					<li>Status: <span>Confirmed</span></li>
				</ul>
			</div>
			<div class="booking-status-body">
				<div class="row">
					<div class="col-md-4">
						<div class="status-patients-details">
							<h6>Patient's Details</h6>
							<table>
								<tr>
									<td>Patient's Name:</td>
									<td><span>Rakesh Kumar</span></td>
								</tr>
								<tr>
									<td>Gender:</td>
									<td><span>Male</span></td>
								</tr>
								<tr>
									<td>Date of Birth:</td>
									<td><span>03 Jun 1988</span></td>
								</tr>
								<tr>
									<td>Email Id:</td>
									<td><span>rakesh.kumar@gmail.com</span></td>
								</tr>
								<tr>
									<td>Contact:</td>
									<td><span>8149548950</span></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="col-md-8">
						<div class="booking-order-details">
							<h6>Your Order Details</h6>
							<div class="order-title">
								<ul>
									<li>Items in Cart</li>
									<li>Available at</li>
									<li>Price</li>
								</ul>
							</div>
							<div class="order-info">
								<ul>
									<li><a href="#">1. Thyroid Profile Test</a></li>
									<li><img src="<?php echo get_template_directory_uri();?>/images/cart_page/home.png" alt=""> Home Visit (2)</li>
									<li><img src="<?php echo get_template_directory_uri();?>/images/cart_page/lab.png" alt=""> Lab Visit</li>
									<li>Rs. 1800/-</li>
								</ul>
								<ul>
									<li><a href="#">2. Thyroid Profile Test</a></li>
									<li><img src="<?php echo get_template_directory_uri();?>/images/cart_page/home.png" alt=""> Home Visit</li>
									<li><img src="<?php echo get_template_directory_uri();?>/images/cart_page/lab.png" alt=""> Lab Visit</li>
									<li>Rs. 1800/-</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="booking-status-bottom">
				<div class="row">
					<div class="col-md-4">
						<div class="home-collection-details">
							<h6>Home Collection Details</h6>
							<div class="home-collection-details-info">
								<span>Address:</span>
								<p>D103, Powai Park CHS,</p>
								<p>High Street, Hiranandani Gardens,</p>
								<p>Powai, Mumbai - 400076</p>
							</div>
							<div class="home-collection-details-info mt-4">
								<span>Date & Time:</span>
								<p>Monday, 15 Jul, 2019</p>
								<p>11.30 am - 12.30 pm</p>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="billing-details">
							<h6>Billing Details</h6>
							<table>
								<tr>
									<td>Tests Subtotal (33): </td>
									<td><span>Rs.3000/-</span></td>
								</tr>
								<tr>
									<td>Home Visits Charges:</td>
									<td><span>Free</span></td>
								</tr>
								<tr>
									<td>Courier Charges:</td>
									<td><span>Rs.50/-</span></td>
								</tr>
								<tr>
									<td>Coupon Discount: </td>
									<td><span>Rs.200/-</span></td>
								</tr>
								<tr>
									<td>Extra 5% Discount: </td>
									<td><span>Rs.142/-</span></td>
								</tr>
								<tr>
									<td>Total Payable:</td>
									<td><span>Rs.2715/-</span></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="col-md-4">
						<div class="billing-details payment-details">
						<h6>Payment Details</h6>
							<table>
								<tr>
									<td>Payment Status:</td>
									<td><span>PAID</span></td>
								</tr>
								<tr>
									<td>Mode of Payment:</td>
									<td><span>Netbanking</span></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
			<p class="status-note">Note: The order confirmation and  invoice has been sent to your email ID. For help & support email us at <a href="mailto:contact@phadkelabs.com">contact@phadkelabs.com</a>  or call us at <a href="callto:+022 4890 0114">+022 4890 0114</a></p>
		</div>
	</section>
	<div class="status-done">
		<a id="checkoutDone" href="#">Done</a>
	</div>
</section> <!-- Booking Status End -->
			
			<!-- User Booked Package -->
		<div class="service-package-area user-booked">
				<div class="package-head">
					<h2>Users who booked this test also booked</h2>
				</div>
				<div class="package-details">
					<div class="row">
						<!-- Single Package -->
						<div class="col-lg-3 col-md-6">
							<div class="single-package">
								<a href="#">
									<h4>Acute Hepatitis <br> Profile</h4>
								</a>
								<h5>Rs. 2,000/-</h5>
								<div class="package-meta">
									<ul>
										<li><a href="/"><img src="<?php echo get_template_directory_uri();?>/images/service/home2.png" alt="">home visit</a>
										</li>
										<li><a href="/contact-us/"><img src="<?php echo get_template_directory_uri();?>/images/service/lab2.png" alt="">lab visit</a></li>
									</ul>
									<div class="cart"><a href="#"><i class="material-icons">add_shopping_cart</i></a>
									</div>
								</div>
							</div>
						</div>
						<!-- Single Package -->
						<div class="col-lg-3 col-md-6">
							<div class="single-package">
								<a href="#">
									<h4>Acute Hepatitis <br> Profile</h4>
								</a>
								<h5>Rs. 2,000/-</h5>
								<div class="package-meta">
									<ul>
										<li><a href="/"><img src="<?php echo get_template_directory_uri();?>/images/service/home2.png" alt="">home visit</a>
										</li>
										<li><a href="/contact-us/"><img src="<?php echo get_template_directory_uri();?>/images/service/lab2.png" alt="">lab visit</a></li>
									</ul>
									<div class="cart"><a href="#"><i class="material-icons">add_shopping_cart</i></a>
									</div>
								</div>
								<span class="package-tag">New</span>
							</div>
						</div>
						<!-- Single Package -->
						<div class="col-lg-3 col-md-6">
							<div class="single-package">
								<a href="#">
									<h4>Acute Hepatitis <br> Profile</h4>
								</a>
								<h5>Rs. 2,000/-</h5>
								<div class="package-meta">
									<ul>
										<li><a href="/"><img src="<?php echo get_template_directory_uri();?>/images/service/home2.png" alt="">home visit</a>
										</li>
										<li><a href="/contact-us/"><img src="<?php echo get_template_directory_uri();?>/images/service/lab2.png" alt="">lab visit</a></li>
									</ul>
									<div class="cart"><a href="#"><i class="material-icons">add_shopping_cart</i></a>
									</div>
								</div>
							</div>
						</div>
						<!-- Single Package -->
						<div class="col-lg-3 col-md-6">
							<div class="single-package">
								<a href="#">
									<h4>Acute Hepatitis <br> Profile</h4>
								</a>
								<h5>Rs. 2,000/-</h5>
								<div class="package-meta">
									<ul>
										<li><a href="/"><img src="<?php echo get_template_directory_uri();?>/images/service/home2.png" alt="">home visit</a>
										</li>
										<li><a href="/contact-us/"><img src="<?php echo get_template_directory_uri();?>/images/service/lab2.png" alt="">lab visit</a></li>
									</ul>
									<div class="cart"><a href="#"><i class="material-icons">add_shopping_cart</i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--==============================================-->
	<!--================ CART Area End ===============-->
	<!--==============================================-->