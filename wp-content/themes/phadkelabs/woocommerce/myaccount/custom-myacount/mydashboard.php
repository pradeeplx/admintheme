


	<!--==============================================-->
	<!--============== Profile Area Start ===============-->
	<!--==============================================-->
	
	<?php $current_user = wp_get_current_user(); ?>
	<section class="profile-area">
		<div class="container">
			<!-- Profile Head Area -->
			<div class="profile-head-area">
				<div class="row">
					<div class="col-md-8">
						<div class="profile-head-title">
							<h2>
								   <?php		
									/* translators: 1: user display*/
									printf(
										__( 'Hello! Welcome back %1$s ', 'woocommerce' ),
										'<strong>' . esc_html( $current_user->display_name ) . '</strong>'
									);
								?>
							</h2>
	
						</div>
					</div>
					<div class="col-md-4">
						<div class="log-out-button">
							
								
								<span>
									
									<?php		
										/* translators: 1: logout url */
										printf(
											__( '<a href="%1$s">%2$s Log out</a>', 'woocommerce' ),
											esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) ),
											'<img src='.get_template_directory_uri().'/images/sa/power.png alt="">'
										);
									?>
								</span>
						
						</div>
					</div>
				</div>
			</div>

			
<!-- Profile Head End -->



			<!-- Profile Tab Content Start -->
			<div class="profile-tab-content">
				<div class="row">
					<div class="col-md-3">
						<div class="profile-tab-menu">
							<ul>
								<li class="active"><a id="myPro" href="#">My Profile</a></li>
								<li><a id="myOrd" href="#">My Order History</a></li>
								<li><a id="myAddr" href="#">Saved Address</a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-9">
						<!-- My Profile Content Start -->
						<div class="myProfileContent pro-content">
						
<?php do_action( 'woocommerce_before_edit_account_form' ); ?>			
<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >
<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
					
								<div class="row">
									<div class="col-md-8">
										<div class="single-profile-input">
											<label for="account_first_name">
												<span><?php esc_html_e( 'First Name', 'woocommerce' ); ?></span>
												<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $current_user->first_name ); ?>" /> 
											</label>
										</div>
										<div class="single-profile-input">
											<label for="account_last_name">
												<span><?php esc_html_e( 'Last name', 'woocommerce' ); ?></span>
												
												<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name"  value="<?php echo esc_attr( $current_user->last_name ); ?>" /> 
											</label>
										</div>
										<div class="single-profile-input">
											<label for="account_display_name">
												<span><?php esc_html_e( 'Display Name', 'woocommerce' ); ?></span>
												<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" autocomplete="given-name" value="<?php echo esc_attr( $current_user->display_name ); ?>" /> 
											</label>
										</div>
										<div class="single-profile-input">
											<label for="account_email">
												<span><?php esc_html_e( 'Email', 'woocommerce' ); ?></span>
												<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $current_user->user_email ); ?>" />
												
												
											</label>
										</div>
									
									</div>
									<div class="col-md-4">
										<div class="profile-right-option">
											<button type="button" data-toggle="modal" data-target="#editPass">
												<img src="<?php echo get_template_directory_uri(); ?>/images/sa/secquerity.png" alt="">
												<span>Change Password</span>
											</button>
											
											<?php do_action( 'woocommerce_edit_account_form' ); ?>
											
											<div class="save-profile-right">
												<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
												<button type="submit" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
												
												<input type="hidden" name="action" value="save_account_details" />
											</div>
											
											
										</div>
									</div>
								</div>
								
<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>
<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
		
						</div>
						<!-- My Profile Content End -->

						
						
						
<?php
	$my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
	'order-number'  => __( 'Order', 'woocommerce' ),
	'order-date'    => __( 'Date', 'woocommerce' ),
	'order-status'  => __( 'Status', 'woocommerce' ),
	'order-total'   => __( 'Total', 'woocommerce' ),
	'order-actions' => '&nbsp;',
) );

$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => -1,
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() ),
) ) );
$total_records = count($customer_orders);
$posts_per_page = 3;
$total_pages = ceil($total_records / $posts_per_page);
$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
$customer_orders = get_posts(array(
    'meta_key' => '_customer_user',
    'meta_value' => get_current_user_id(),
    'post_type' => wc_get_order_types('view-orders'),
    'posts_per_page' => $posts_per_page,
    'paged' => $paged,
    'post_status' => array_keys(wc_get_order_statuses())
));
					
if ( $customer_orders ) : 
?>				
						<!-- History Content Start -->
						<div class="history-content-area pro-content">
							<div class="row align-center">
								<div class="col-md-8">
									<div class="history-tab-head">
										<h2><?php echo apply_filters( 'woocommerce_my_account_my_orders_title', __( 'Order History', 'woocommerce' ) ); ?></h2>
									</div>
								</div>
								<div class="col-md-4">
									<div class="history-tab-filter">
										<form action="">
											<select name="" id="">
												<option value="">Last 30 Days</option>
												<option value="">Last 3 Month</option>
												<option value="">Last 6 Month</option>
											</select>
										</form>
									</div>
								</div>
							</div>
							
							
<?php foreach ( $customer_orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count();
				$items			= $order->get_items();
				?>
							
							<div class="row">
							
								<div class="col-md-12">
									<?php //echo $order->status; ?>
								</div>
								<!-- Single Order -->
								<div class="col-md-12">
									<div class="single-ordered-item">
										<div class="ordered-heading">
											<p>Date: 05 Jul 2019</p>
											<p>Booking Id: APL<?php echo $order->get_id() ?></p>
											<p>Total: <?php echo $order->get_total(); ?>/-</p>
											<p>Status: <?php echo $order->status; ?></p>
										</div>
										<form action="<?php echo esc_url(home_url('/')); ?>" method="post">
											<div class="row align-end">
											
												<form action="" method="post">
											
												<div class="col-md-9">
													<div class="ordered-product">
														<table>
															<tr>
																<th>Items:</th>
																<th>Available at</th>
																<th>Price</th>
															</tr>
															
													<?php $p_count=1; foreach ( $items as $item ) : 
														$product = wc_get_product($item['product_id']);
														
													?>															
															<tr>
																<td> 
																	<input class="order_again" id="<?php echo $item['product_id']; ?>" type="checkbox" value="<?php echo $item['product_id']; ?>" name="productid[]" product_id="<?php echo $item['product_id']; ?>" >
																	<span><?php echo $p_count++; ?>. <?php echo $item['name']; ?></span>
																</td>
																<td>
																	<img src="<?php echo get_template_directory_uri(); ?>/images/sa/home.png" alt="">
																	<span>Home Visit (<?php if(get_field('home_visit')) echo get_field('home_visit'); ?>)</span>
																	<img class="lab-img" src="<?php echo get_template_directory_uri(); ?>/images/sa/lab.png" alt="">
																	<span>Lab Visit(<?php if(get_field('lab_visit')) echo get_field('home_visit'); ?>)</span>
																</td>
																<td><?php echo $product->get_price(); ?>/-</td>
															</tr>
													<?php endforeach; ?>		
														
														
														
														</table>
													</div>
												</div>
												<div class="col-md-3">
													<div class="reorder-button-area">
														<input type="submit" name="orderagain" value="REORDER">
													</div>
												</div>
											</form>
											
											<?php
												 if(isset($_REQUEST['orderagain'])){
												
													 foreach($_REQUEST['productid'] as $id){
														 
														  global $woocommerce;
					
														  $product_number = $id;
														  $phone_number = get_user_meta($current_user->ID,'phone_number',true);

														  $address = array(
															  'first_name' => $current_user->user_firstname,
															  'last_name'  => $current_user->user_lastname,
															  'company'    => ' ',
															  'email'      => $current_user->user_email,
															  'phone'      => $phone_number,
															  'address_1'  => ' ',
															  'address_2'  => ' ',
															  'city'       => ' ',
															  'state'      => ' ',
															  'postcode'   => ' ',
															  'country'    => ' '
														  );

														  // Now we create the order
														  $order = wc_create_order();

														  // The add_product() function below is located in /plugins/woocommerce/includes/abstracts/abstract_wc_order.php
														  $order->add_product( get_product( $product_number ), 1 ); // This is an existing SIMPLE product
														  $order->set_address( $address, 'billing' );
														  //
														  $order->calculate_totals();
														  $order->update_status("Completed", 'Imported order', TRUE);  
											
													}
													
												
												} 
											?>
												
											</div>
										</form>
									</div>
								</div>
							</div>
							
	<?php endforeach; ?>
	
	 

							<div class="row">
								<div class="col-md-12 d-flex justify-content-end">
									<div class="pagination">
										<?php
										$args = array(
											'base' => '%_%',
											'format' => '?page=%#%',
											'total' => $total_pages,
											'current' => $paged,
											'show_all' => False,
											'end_size' => 5,
											'mid_size' => 5,
											'prev_next' => True,
											'prev_text' => __('<<'),
											'next_text' => __('>>'),
											'type' => 'list',
											'add_args' => False,
											'add_fragment' => ''
										);
										echo paginate_links($args);
										?>
									</div>
								</div>
							</div>

						</div>
						<?php endif; ?>
						<!-- History Content End -->

						<!-- Save Address Content Start -->
						<div class="saveContentArea pro-content">
							<div class="row">
								<!-- Single Address -->
								<div class="col-md-6">
									<div class="single-saved-address">
										<div class="row align-center">
											<div class="col-6">
												<div class="sevedAddresTitle">
													<p>My Home</p>
												</div>
											</div>
											<div class="col-6">
												<div class="sevedAddresAction">
													<button data-toggle="modal" data-target="#editAddress">
														<img src="<?php echo get_template_directory_uri(); ?>/images/sa/edit.png" alt="">
													</button>
													<button data-toggle="modal" data-target="#del">
														<img src="<?php echo get_template_directory_uri(); ?>/images/sa/delete.png" alt="">
													</button>
												</div>
											</div>
											<div class="col-12">
												<div class="savedAddreses">
													<p>D103, Powai Park CHS, <br>
														High Street, Hiranandani Gardens, <br>
													Powai, Mumbai - 400076</p>
												</div>
											</div>
											<div class="col-7">
												<div class="savedAddresesBottom">
													<p>Contact: 8149548956</p>
												</div>
											</div>
											<div class="col-5">
												<div class="savedAddresesBottomInput">
													<label>
														<input type="radio">
														<span>Set as Default</span>
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- Single Address -->
								<div class="col-md-6">
									<div class="single-saved-address">
										<div class="row align-center">
											<div class="col-6">
												<div class="sevedAddresTitle">
													<p>My Home</p>
												</div>
											</div>
											<div class="col-6">
												<div class="sevedAddresAction">
													<button data-toggle="modal" data-target="#editAddress">
														<img src="<?php echo get_template_directory_uri(); ?>/images/sa/edit.png" alt="">
													</button>
													<button data-toggle="modal" data-target="#del">
														<img src="<?php echo get_template_directory_uri(); ?>/images/sa/delete.png" alt="">
													</button>
												</div>
											</div>
											<div class="col-12">
												<div class="savedAddreses">
													<p>D103, Powai Park CHS, <br>
														High Street, Hiranandani Gardens, <br>
													Powai, Mumbai - 400076</p>
												</div>
											</div>
											<div class="col-7">
												<div class="savedAddresesBottom">
													<p>Contact: 8149548956</p>
												</div>
											</div>
											<div class="col-5">
												<div class="savedAddresesBottomInput">
													<label>
														<input type="radio">
														<span>Set as Default</span>
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="add-addreses-button-area">
										<button data-toggle="modal" data-target="#addNewAddress">ADD NEW</button>
									</div>
								</div>
							</div>
						</div>
						<!-- Save Address Content End -->
					</div>
				</div>
			</div>
			<!-- Profile Tab Content End -->
		</div>
	</section>
	<!--==============================================-->
	<!--============== Profile Area End ===============-->
	<!--==============================================-->