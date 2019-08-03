<?php 

/*****************************************************************/
/* IMPORT / EXPORT ADMIN PAGE */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_activation' ) ) :

	function wp_admin_theme_cd_activation() {
			
		add_submenu_page(
			NULL,
			esc_html__( 'WP Admin Theme CD - Activation', 'wp-admin-theme-cd' ),
			esc_html__( 'WPAT Activation', 'wp-admin-theme-cd' ),
			'manage_options',
			'wp-admin-theme-cd-purchase-code',
			'wp_admin_theme_cd_purchase_code_page'
		);
		
	}

	add_action( 'admin_menu', 'wp_admin_theme_cd_activation' );

	function wp_admin_theme_cd_purchase_code_page() { 

		// Get purchase data from the users purchase code
		$purchase_data = wp_admin_theme_cd_get_purchase_theme_details();

		// Get input value
		$input_value = '';
		if( wp_admin_theme_cd_activation_status() ) {
			$input_value = $purchase_data['purchase_code'];
		}

		// Manage input type
		$input_type = 'text';
		if( wp_admin_theme_cd_activation_status() ) {
			$input_type = 'password';
		}

		$activation_label = '<span style="color:#d63316">(' . esc_html__( 'Deactivated', 'wp-admin-theme-cd' ) . ')</span>';
		if( wp_admin_theme_cd_activation_status() ) {
			$activation_label = '<span style="color:#8db51e">(' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . ')</span>';							
		} ?>
	
		<div class="wrap about-wrap wpat-plugin-welcome">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="settings-wrapper">
							<div class="inside">
								
								<h1>
									<?php echo wp_admin_theme_cd_title(); ?>
								</h1> 

								<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'activation'; 
								echo wp_admin_theme_cd_tab_menu( $active_tab ); ?>

								<h3><?php echo esc_html__( 'Plugin Activation', 'wp-admin-theme-cd' ) . ' ' . wp_kses_post( $activation_label ); ?></h3>

								<?php 
		
								$show_license_box = ( ! wp_admin_theme_cd_plugin() );
								if( is_multisite() ) {
									$blog_id = 1; // <-- Option from main site
									$show_license_box = ( ! wp_admin_theme_cd_plugin() && get_current_blog_id() == $blog_id );
								}
		
								if( $show_license_box ) { ?>

									<p class="about-text">
										<?php echo esc_html__( 'You will need your Envato item purchase code to activate this plugin. If you do not activate this plugin, you will not be able to access the plugin settings page.', 'wp-admin-theme-cd' ); ?>
										<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">
											<?php echo esc_html__( 'How to find your purchase code?', 'wp-admin-theme-cd' ); ?>	
										</a>
									</p>

									<form method="post" id="purchase_verify">
										<div class="activation-notice">									
											<div class="activation-notice-content">

												<span id="license_notice" class="theme-notice" style="display: none">
													<span class="notice-holder"><?php // Placeholder for license notice. ?></span>
												</span>

												<div class="license-fields">
													<input id="purchase_code" name="purchase_code" type="<?php echo esc_html( $input_type ); ?>" placeholder="<?php echo esc_html__( 'Enter your Purchase Code', 'wp-admin-theme-cd' ); ?>" value="<?php echo esc_html( $input_value ); ?>" size="40" required />	
													<div id="purchase_code_show" class="button">
														<span class="dashicons dashicons-visibility"></span>
														<span class="dashicons dashicons-hidden" style="display: none"></span>
													</div>

													<input id="purchase_root_url" name="purchase_root_url" type="hidden" value="<?php echo wp_admin_theme_cd_root_url(); ?>" size="40" disabled />										
													<input id="purchase_client_mail" name="purchase_client_mail" type="hidden" size="40" placeholder="<?php echo esc_html__( 'E-mail address', 'wp-admin-theme-cd' ); ?>" />
												</div>

												<?php // Purchase details
												if( wp_admin_theme_cd_activation_status() ) { ?>
													<div class="license-details">
														<div class="license-details-label">
															<?php echo esc_html__( 'License Details', 'wp-admin-theme-cd' ); ?>:
														</div>
														<?php if( wp_admin_theme_cd_purchase_code_verify( $purchase_data['purchase_code'] ) == 'valid' ) { ?>
														<ul>
															<li><?php echo esc_html__( 'Plugin', 'wp-admin-theme-cd' ); ?>: <?php echo esc_html( $purchase_data['theme_name'] ); ?></li>
															<li><?php echo esc_html__( 'Buyer', 'wp-admin-theme-cd' ); ?>: <?php echo esc_html( $purchase_data['buyer'] ); ?></li>
															<li><?php echo esc_html__( 'License', 'wp-admin-theme-cd' ); ?>: <?php echo esc_html( $purchase_data['license'] ); ?></li>
															<li><?php echo esc_html__( 'Purchase Count', 'wp-admin-theme-cd' ); ?>: <?php echo esc_html( $purchase_data['purchase_count'] ); ?></li>
															<li><?php echo esc_html__( 'Sold at', 'wp-admin-theme-cd' ); ?>: <?php echo esc_html( $purchase_data['sold_at'] ); ?></li>
															<li><?php echo esc_html__( 'Support until', 'wp-admin-theme-cd' ); ?>: <strong><?php echo esc_html( $purchase_data['supported_until'] ); ?></strong></li>
														</ul>
														<?php } else { ?>
															<ul>
																<li><?php echo esc_html__( 'Plugin', 'wp-admin-theme-cd' ); ?>: <?php echo esc_html( $purchase_data['theme_name'] ); ?></li>
																<li><?php echo esc_html__( 'License', 'wp-admin-theme-cd' ); ?>: <?php echo esc_html( $purchase_data['license'] ); ?></li>
															</ul>
														<?php } ?>
													</div>
												<?php } ?>

												<input id="btn_purchase" type="submit" class="button button-primary" value="<?php echo esc_html__( 'Verify and install license', 'wp-admin-theme-cd' ); ?>"<?php if( wp_admin_theme_cd_activation_status() ) { ?> disabled<?php } ?> />
												<?php if( wp_admin_theme_cd_activation_status() ) { ?>
													<div id="btn_delete_license" class="button">
														<?php echo esc_html__( 'Unlock license', 'wp-admin-theme-cd' ); ?>
													</div>
													<div class="license-reset" style="display: none">
														<p>
															<?php $author_mail = '<a href="mailto:' . esc_html( WP_ADMIN_THEME_CD_AUTHOR_MAIL ) . '?subject=' . esc_html__( 'Request to unlock the plugin license for', 'wp-admin-theme-cd' ) . ' ' . WP_ADMIN_THEME_CD_PLUGIN_NAME . '&amp;body=' . esc_html__( 'Please unlock my purchase code for the following domain:', 'wp-admin-theme-cd' )  . ' ' . wp_admin_theme_cd_root_url() . '%0D%0A %0D%0A' . esc_html__( 'Purchase code:', 'wp-admin-theme-cd' ) . ' ' . $purchase_data['purchase_code'] . '">' . esc_html__( 'contact the author', 'wp-admin-theme-cd' ) . '</a>';
															printf( wp_kses_post( __( 'In some cases unlocking the license is not possible. Therefore, you can reset the license key. If you can not reactivate after resetting the license, %1$s of the plugin to manually unlock the license.', 'wp-admin-theme-cd' ) ), $author_mail ); ?>	
														</p>
														<div id="btn_reset_license" class="button">
															<?php echo '(!) ' . esc_html__( 'Reset license', 'wp-admin-theme-cd' ); ?>
														</div>
													</div>
												<?php } ?>
												
												<?php if( ! wp_admin_theme_cd_activation_status() ) { ?>
												<p><small>
													<?php echo esc_html__( 'You have five attempts to enter the purchase code. Otherwise, your website will be blocked to avoid too many server requests.', 'wp-admin-theme-cd' ); ?>
													<a href="<?php echo WP_ADMIN_THEME_CD_ENVATO_URL; ?>" target="_blank">
														<?php echo esc_html__( 'Contact the theme author for help.', 'wp-admin-theme-cd' ); ?>	
													</a>
												</small></p>
												<?php } ?>

												<p><small>
													<?php echo esc_html__( 'Why do you have to enter a purchase code? The license verification protects this premium plugin against unauthorized use without a license or multiple use with only one license purchased.', 'wp-admin-theme-cd' ); ?>
													<a href="https://codecanyon.net/licenses/standard" target="_blank">
														<?php echo esc_html__( 'Read more about the Envato Licenses.', 'wp-admin-theme-cd' ); ?>	
													</a>
												</small></p>

											</div>
										</div>
									</form>

									<?php if( ! wp_admin_theme_cd_activation_status() ) { ?>
										<p>
											<?php echo esc_html__( 'You do not have a plugin license? Then you can buy one anytime.', 'wp-admin-theme-cd' ); ?>
											<a href="<?php echo WP_ADMIN_THEME_CD_ENVATO_URL; ?>" target="_blank">
												<?php echo esc_html__( 'Get a license.', 'wp-admin-theme-cd' ); ?>
											</a>
										</p>
									<?php } ?>

									<h3><?php printf( wp_kses_post( __( 'How would you like to update the %1$s plugin in the future?', 'wp-admin-theme-cd' ) ), WP_ADMIN_THEME_CD_PLUGIN_NAME ); ?></h3>

									<p>
										<?php $envato_market_plugin_url = '<a href="https://envato.com/market-plugin/" target="_blank"><strong>Envato Market plugin</strong></a>';
										printf( wp_kses_post( __( 'There are two ways to update this plugin when a new version is released. In the first way, you can install updates manually after receiving an email notification from Envato about a new version. In the second and recommended way, you can update this plugin automatically by installing the %1$s.', 'wp-admin-theme-cd' ) ), $envato_market_plugin_url ); ?>
										<br><br>
										<img src="<?php echo wp_admin_theme_cd_path( 'img/envato-market-logo.svg' ); ?>" alt="Envato Market Logo" style="width: 200px">
										<br><br>
										<?php $envato_market_plugin_url = '<a href="https://envato.com/market-plugin/" target="_blank">https://envato.com/market-plugin/</a>';
										printf( wp_kses_post( __( 'To activate automatic updates for the %1$s plugin, follow the instructions on %2$s.', 'wp-admin-theme-cd' ) ), WP_ADMIN_THEME_CD_PLUGIN_NAME, $envato_market_plugin_url ); ?>
									</p>

								<?php } else {
									if( has_filter('wp_admin_theme_cd_accepted') ) {
										$permission = 'denied';	
										$apply = apply_filters('wp_admin_theme_cd_accepted', $permission);
										if( $apply === 'accepted_by_theme' || $apply === 'accepted' ) {
											echo esc_html__( 'This plugin can be used in combination with this theme for free.', 'wp-admin-theme-cd' );
										}
									} else {
										if( wp_admin_theme_cd_activation_status() ) {
											echo esc_html__( 'This plugin can be used on other multisite instances without the need for an additional license.', 'wp-admin-theme-cd' );
										} else {
											echo esc_html__( 'Entering the purchase code is required to access the settings. Please enter the purchase code on the main page of this multisite.', 'wp-admin-theme-cd' );
										}	
									}
								} ?>

							</div>
						</div>
					</div>		

					<div id="postbox-container-1" class="postbox-container">
						<div class="meta-box-sortables">
							<div class="postbox">
								<div class="inside">

									<img src="<?php echo wp_admin_theme_cd_path( 'img/screenshot.png' ); ?>" width="100%" alt="Plugin Screenshot">

									<p>
										<a href="https://themeforest.net/user/creativedive/portfolio" target="_blank">
											<?php echo esc_html__( 'Get to know my WordPress themes.', 'wp-admin-theme-cd' ); ?>
										</a>
									</p>

								</div>
							</div>

							<div class="postbox">
								<div class="inside">							

									<p>
										<img class="theme-author-img" src="<?php echo wp_admin_theme_cd_path( 'img/avatar-author.jpg' ); ?>" width="100%" alt="Theme Author">
										<strong><?php echo esc_html__( "Hey, I'm Martin, the plugin author from CreativeDive.", 'wp-admin-theme-cd' ); ?></strong>
										<br>
										<br>
										<?php echo esc_html__( 'This plugin already includes more than 1000 hours of work to redesign a impressive WordPress backend for users like you. Great new features are planned for the future.', 'wp-admin-theme-cd' ); ?>
									</p>

									<p>
										<?php echo esc_html__( 'Help me to develop a powerful plugin that will benefit you for a long time.', 'wp-admin-theme-cd' ); ?>
									</p>

									<p>
										<?php echo esc_html__( 'Please show your appreciation and rate the plugin.', 'wp-admin-theme-cd' ); ?>
									</p>

									<p>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
									</p>

									<p>
										<?php echo esc_html__( 'Thank you and best regards, Martin.', 'wp-admin-theme-cd' ); ?>
									</p>

									<a class="button" href="<?php echo WP_ADMIN_THEME_CD_ENVATO_THEME_REVIEW_URL; ?>" target="_blank">
										<?php echo esc_html__( 'Rate now!', 'wp-admin-theme-cd' ); ?>
									</a>

								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>
		
	<?php }

endif;