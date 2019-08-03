<?php



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function digits_forms( $is_elem = - 1, $type = 1 ) {
	$users_can_register = get_option( 'dig_enable_registration', 1 );
	$userCountryCode    = getUserCountryCode();

	$url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

	$url   = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
	$query = parse_url( $url, PHP_URL_QUERY );
	if ( $query ) {
		$url .= '&login=true';
	} else {
		$url .= '?login=true';
	}

	$dig_login_details = digit_get_login_fields();

	$emailaccep = $dig_login_details['dig_login_email'];
	$passaccep  = $dig_login_details['dig_login_password'];
	$mobileaccp = $dig_login_details['dig_login_mobilenumber'];
	$otpaccp    = $dig_login_details['dig_login_otp'];
	$captcha    = $dig_login_details['dig_login_captcha'];
	if ( $emailaccep == 1 && $mobileaccp == 1 ) {
		$emailaccep = 2;
	}

	if ( $emailaccep == 2 ) {
		$emailmob = __( "Email/Mobile Number", "digits" );
	} else if ( $mobileaccp == 1 ) {
		$emailmob = __( "Mobile Number", "digits" );
	} else if ( $emailaccep > 0 ) {
		$emailmob = __( "Email", "digits" );
	} else {
		$emailmob = __( "Username", "digits" );
	}
	$theme             = 'dark';
	$bgtype            = 'bgdark';
	$themee            = 'lighte';
	$bgtransbordertype = "bgtransborderdark";
	?>
    <div
            class="digloginpage" <?php if ( $is_elem == 3 ) {
		echo 'style="display:none"';
	} ?>>
        <form method="post" action="<?php echo $url; ?>">
            <div class="minput">
                <input type="text" class="mobile_field mobile_format dig-mobmail" name="mobmail"
                       value="<?php if ( isset( $username ) ) {
					       echo $username;
				       } ?>" required/>

                <div class="countrycodecontainer logincountrycodecontainer">
                    <input type="text" name="countrycode"
                           class="input-text countrycode logincountrycode <?php echo $theme; ?>"
                           value="<?php if ( isset( $countrycode ) ) {
						       echo $countrycode;
					       } else {
						       echo $userCountryCode;
					       } ?>"
                           maxlength="6" size="3" placeholder="<?php echo $userCountryCode; ?>"/>
                </div>

                <label><?php echo $emailmob; ?></label>
                <span class="<?php echo $bgtype; ?>"></span></div>


            <div class="minput dig_login_otp" style="display: none;">
                <input type="text" name="dig_otp" id="dig-login-otp"/>
                <label><?php _e( 'OTP', 'digits' ); ?></label>
                <span class="<?php echo $bgtype; ?>"></span>
            </div>
			<?php


			if ( $passaccep == 1 ) {
				?>
                <div class="minput">
                    <input type="password" name="password" required/>
                    <label><?php _e( 'Password', 'digits' ); ?></label>
                    <span class="<?php echo $bgtype; ?>"></span>
                </div>
				<?php
			}

			if ( $captcha == 1 ) {
				dig_show_login_captcha( 1, $bgtype );
			}


			?>


            <input type="hidden" name="dig_nounce" class="dig_nounce"
                   value="<?php echo wp_create_nonce( 'dig_form' ) ?>">

			<?php
			dig_rememberMe();

			if ( $passaccep == 1 ) { ?>
                <div class="logforb">
                    <input type="submit" class="<?php echo $themee; ?> <?php echo $bgtype; ?> button"
                           value="<?php _e( 'Log In', 'digits' ); ?>"/>
					<?php
					$digforgotpass = get_option( 'digforgotpass', 1 );
					if ( $digforgotpass == 1 ) {
						?>
                        <div class="forgotpasswordaContainer"><a
                                    class="forgotpassworda"><?php _e( 'Forgot your password?', 'digits' ); ?></a>
                        </div>
					<?php } ?>
                </div>
				<?php
			}
			if ( $mobileaccp == 1 && $otpaccp == 1 ) {
				?>

                <div id="dig_login_va_otp"
                     class=" <?php echo $themee; ?> <?php echo $bgtype; ?> button loginviasms"><?php _e( 'Login With OTP', 'digits' ); ?></div>
				<?php echo "<div  class=\"dig_resendotp dig_logof_log_resend\" id=\"dig_lo_resend_otp_btn\" dis='1'> " . __( 'Resend OTP', 'digits' ) . "<span>(00:<span>" . dig_getOtpTime() . "</span>)</span></div>"; ?>
				<?php
			}


			if ( $users_can_register == 1 ) { ?>
                <div class="signdesc"><?php _e( 'Don\'t have an account?', 'digits' ); ?></div>
                <div class="signupbutton transupbutton bgtransborderdark"><?php _e( 'Sign Up', 'digits' ); ?></div>
			<?php } ?>
			<?php

			global $dig_logingpage;
			$dig_logingpage = 1;
			do_action( 'login_form' );
			$dig_logingpage = 0;
			?>
        </form>
    </div>

	<?php

	if ( $users_can_register == 1 && $is_elem == - 1 ) {

		$dig_reg_details = digit_get_reg_fields();


		$nameaccep     = $dig_reg_details['dig_reg_name'];
		$usernameaccep = $dig_reg_details['dig_reg_uname'];
		$emailaccep    = $dig_reg_details['dig_reg_email'];
		$passaccep     = $dig_reg_details['dig_reg_password'];
		$mobileaccp    = $dig_reg_details['dig_reg_mobilenumber'];


		if ( $emailaccep == 1 && $mobileaccp == 1 ) {
			$emailmob = __( "Email/Mobile Number", "digits" );
		} else if ( $mobileaccp > 0 ) {
			$emailmob = __( "Mobile Number", "digits" );
		} else if ( $emailaccep > 0 ) {
			$emailmob = __( "Email", "digits" );
		} else if ( $usernameaccep == 0 ) {
			$usernameaccep = 1;
			$emailmob      = __( "Username", "digits" );
		}


		if ( $emailaccep == 0 ) {
			echo "<input type=\"hidden\" value=\"1\" class=\"disable_email_digit\" />";
		}
		if ( $passaccep == 0 ) {
			echo "<input type=\"hidden\" value=\"1\" class=\"disable_password_digit\" />";
		}
		?>
        <div class="register">

            <form method="post" class="digits_register" action="<?php echo $url; ?>">

                <div class="dig_reg_inputs">

					<?php
					if ( $nameaccep > 0 ) {
						?>

                        <div id="dig_cs_name" class="minput">
                            <input type="text" name="digits_reg_name" class="digits_reg_name"
                                   value="<?php if ( isset( $name ) ) {
								       echo $name;
							       } ?>" <?php if ( $nameaccep == 2 ) {
								echo "required";
							} ?>/>
                            <label><?php _e( "First Name", "digits" ); ?></label>
                            <span class="<?php echo $bgtype; ?>"></span>
                        </div>
					<?php }

					if ( $usernameaccep > 0 ) {
						?>

                        <div id="dig_cs_username" class="minput">
                            <input type="text" name="digits_reg_username" id="digits_reg_username"
                                   value="<?php if ( isset( $username ) ) {
								       echo $username;
							       } ?>" <?php if ( $usernameaccep == 2 ) {
								echo "required";
							} ?>/>
                            <label><?php _e( "Username", "digits" ); ?></label>
                            <span class="<?php echo $bgtype; ?>"></span>
                        </div>
					<?php }


					$reqoropt = "";


					if ( $emailaccep > 0 || $mobileaccp > 0 ) {

						?>
                        <div id="dig_cs_mobilenumber" class="minput">
                            <input type="text" class="mobile_field mobile_format digits_reg_email" name="digits_reg_mail"
                                   value="<?php if ( isset( $mob ) || $emailaccep == 2 || $mobileaccp == 2 ) {
								       if ( $mobileaccp == 1 ) {
									       $reqoropt = "(" . __( "Optional", 'digits' ) . ")";
								       }

							       } else if ( isset( $mail ) ) {
								       echo $mail;
							       } ?>" <?php if ( empty( $reqoropt ) )
								echo 'required' ?>/>
                            <div class="countrycodecontainer registercountrycodecontainer">
                                <input type="text" name="digregcode"
                                       class="input-text countrycode registercountrycode  <?php echo $theme; ?>"
                                       value="<?php echo $userCountryCode; ?>" maxlength="6" size="3"
                                       placeholder="<?php echo $userCountryCode; ?>" <?php if ( $emailaccep == 2 || $mobileaccp == 2 ) {
									echo 'required';
								} ?>/>
                            </div>
                            <label><?php if ( $emailaccep == 2 && $mobileaccp == 2 ) {
									echo __( 'Mobile Number', 'digits' );
								} else {
									echo $emailmob;
								} ?><?php echo $reqoropt; ?></label>
                            <span class="<?php echo $bgtype; ?>"></span>
                        </div>

						<?php
					}
					if ( $emailaccep > 0 && $mobileaccp > 0 ) {
						$emailmob = __( 'Email/Mobile Number', 'digits' );

						$reqoropt = "";
						if ( $emailaccep == 1 ) {
							$reqoropt = "(" . __( "Optional", 'digits' ) . ")";
						}
						if ( $emailaccep == 2 || $mobileaccp == 2 ) {
							$emailmob = __( 'Email', 'digits' );

						}

						?>
                        <div class="dig_cs_email minput dig-mailsecond" <?php if ( $emailaccep != 2 && $mobileaccp != 2 ) {
							echo 'style="display: none;"';
						} ?>>
                            <input type="text" class="mobile_field mobile_format dig-secondmailormobile" name="mobmail2"
                                   <?php if ( $emailaccep == 2 ) {
								echo "required";
							} ?>/>
                            <div class="countrycodecontainer secondregistercountrycodecontainer">
                                <input type="text" name="digregscode2"
                                       class="input-text countrycode registersecondcountrycode  <?php echo $theme; ?>"
                                       value="<?php echo $userCountryCode; ?>" maxlength="6" size="3"
                                       placeholder="<?php echo $userCountryCode; ?>"/>
                            </div>
                            <label><span
                                        class="dig_secHolder"><?php echo $emailmob; ?></span> <?php echo $reqoropt; ?>
                            </label>
                            <span class="<?php echo $bgtype; ?>"></span>
                        </div>
						<?php
					}

					if ( $passaccep > 0 ) {
						?>


                        <div id="dig_cs_password" class="minput" <?php if ( $passaccep == 1 ) {
							echo 'style="display: none;"';
						} ?>>
                            <input type="password" name="digits_reg_password"
                                   id="digits_reg_password" <?php if ( $passaccep == 2 ) {
								echo "required";
							} ?>/>
                            <label><?php _e( "Password", "digits" ); ?></label>
                            <span class="<?php echo $bgtype; ?>"></span>
                        </div>
					<?php }

					show_digp_reg_fields( 1, $bgtype );

					echo '</div>';

					?>
                    <div class="minput dig_register_otp" style="display: none;">
                        <input type="text" name="dig_otp" id="dig-register-otp"
                               value="<?php if ( isset( $_POST['dig_otp'] ) ) {
							       echo dig_filter_string( $_POST['dig_otp'] );
						       } ?>"/>
                        <label><?php _e( "OTP", "digits" ); ?></label>
                        <span class="<?php echo $bgtype; ?>"></span>
                    </div>


                    <input type="hidden" name="code" class="register_code"/>
                    <input type="hidden" name="csrf" class="register_csrf"/>
                    <input type="hidden" name="dig_reg_mail" class="dig_reg_mail">
                    <input type="hidden" name="dig_nounce" class="dig_nounce"
                           value="<?php echo wp_create_nonce( 'dig_form' ) ?>">
					<?php
					if ( $mobileaccp > 0 || $passaccep == 0 || $passaccep == 2 ) {
						if ( ( $passaccep == 0 && $mobileaccp == 0 ) || $passaccep == 2 || ( $passaccep == 0 && $mobileaccp > 0 ) ) {
							$subVal = __( "Signup", "digits" );
						} else {
							$subVal = __( "Signup With OTP", "digits" );
						}
						?>

                        <button class="<?php echo $themee . ' ' . $bgtype; ?> button dig-signup-otp registerbutton"
                                value="<?php echo $subVal; ?>" type="submit"><?php echo $subVal; ?></button>
						<?php echo "<div  class=\"dig_resendotp dig_logof_reg_resend\" id=\"dig_lo_resend_otp_btn\" dis='1'>" . __( "Resend OTP", "digits" ) . " <span>(00:<span>" . dig_getOtpTime() . "</span>)</span></div>"; ?>
					<?php } ?>

					<?php if ( $passaccep == 1 ) { ?>
                        <button class="dig_reg_btn_password <?php echo $themee . ' ' . $bgtype; ?> button registerbutton"
                                attr-dis="1"
                                value="<?php _e( "Signup With Password", "digits" ); ?>" type="submit">
							<?php _e( "Signup With Password", "digits" ); ?>
                        </button>


					<?php } ?>

                    <div class="backtoLoginContainer"><a
                                class="backtoLogin"><?php _e( "Back to login", "digits" ); ?></a>
                    </div>

					<?php
					do_action( 'register_form' );
					?>
            </form>
        </div>


		<?php
	}


	$digforgotpass = get_option( 'digforgotpass', 1 );

	if ( $digforgotpass == 1 && $dig_login_details['dig_login_password'] == 1 ) {

		$emailmob = __( "Email/Mobile Number", "digits" );

		?>
        <div class="forgot" <?php if ( $is_elem != 3 ) {
			echo 'style="display:none"';
		} ?>>
            <form method="post" action="<?php echo $url; ?>">
                <div class="minput forgotpasscontainer">
                    <input class="mobile_field mobile_format forgotpass" type="text" name="forgotmail"
                           required/>
                    <div class="countrycodecontainer forgotcountrycodecontainer">
                        <input type="text" name="countrycode"
                               class="input-text countrycode forgotcountrycode  <?php echo $theme; ?>"
                               value="<?php echo $userCountryCode; ?>"
                               maxlength="6" size="3" placeholder="<?php echo $userCountryCode; ?>"/>
                    </div>
                    <label><?php echo $emailmob; ?></label>
                    <span class="<?php echo $bgtype; ?>"></span>
                </div>


                <div class="minput dig_forgot_otp" style="display: none;">
                    <input type="text" name="dig_otp" id="dig-forgot-otp"/>
                    <label><?php _e( 'OTP', 'digits' ); ?></label>
                    <span class="<?php echo $bgtype; ?>"></span>
                </div>

                <input type="hidden" name="code" class="digits_code"/>
                <input type="hidden" name="csrf" class="digits_csrf"/>
                <input type="hidden" name="dig_nounce" class="dig_nounce"
                       value="<?php echo wp_create_nonce( 'dig_form' ) ?>">
                <div class="changepassword">
                    <div class="minput">
                        <input type="password" class="digits_password" name="digits_password" required/>
                        <label><?php _e( 'Password', 'digits' ); ?></label>
                        <span class="<?php echo $bgtype; ?>"></span>
                    </div>

                    <div class="minput">
                        <input type="password" class="digits_cpassword" name="digits_cpassword" required/>
                        <label><?php _e( 'Confirm Password', 'digits' ); ?></label>
                        <span class="<?php echo $bgtype; ?>"></span>
                    </div>
                </div>
                <button type="submit"
                        class="<?php echo $themee; ?> <?php echo $bgtype; ?> button forgotpassword"
                        value="<?php _e( 'Reset Password', 'digits' ); ?>"><?php _e( "Reset Password", "digits" ); ?></button>
				<?php echo "<div  class=\"dig_resendotp dig_logof_forg_resend\" id=\"dig_lo_resend_otp_btn\" dis='1'>" . __( 'Resend OTP', 'digits' ) . "<span>(00:<span>" . dig_getOtpTime() . "</span>)</span></div>"; ?>
				<?php if ( $is_elem != 3 ) { ?>
                    <div class="backtoLoginContainer"><a
                                class="backtoLogin"><?php _e( "Back to login", "digits" ); ?></a>
                    </div>
					<?php
				}
				?>
            </form>
        </div>

		<?php
	}
	?>

	<?php

}

/*
 * 'username'      => __( 'Username', 'digits' ),
			'mobmail'       => __( 'Email/Mobile Number', 'digits' ),
			'mobile_number' => __( 'Mobile Number', 'digits' ),
			'email'         => __( 'Email', 'digits' ),
			'password'      => __( 'Password', 'digits' ),
*/
function dig_show_elem_fields( $fields, $show_asterisk, $widget_type) {
	$userCountryCode    = getUserCountryCode();

	$show_otp          = false;
	$theme             = 'dark';
	$bgtype            = 'bgdark';
	$themee            = 'lighte';
	$bgtransbordertype = "bgtransborderdark";
	$mobileaccp = -1;
	$passaccep = -1;
	$emailaccep = -1;
	foreach ( $fields as $field ) {
		$type = strtolower( $field['type'] );
		if ( $type == 'username' ) {
			$usernameaccep = $field['required'];
			?>

            <div id="dig_cs_username" class="minput">
                <input type="text" name="digits_reg_username" id="digits_reg_username"
                       value="<?php if ( isset( $username ) ) {
					       echo $username;
				       } ?>" <?php if ( $usernameaccep == 1 ) {
					echo "required";
				} ?>/>
                <label><?php _e( "Username", "digits" ); ?></label>
                <span class="<?php echo $bgtype; ?>"></span>
            </div>
			<?php
			unset( $fields['Username'] );
		} else if ( $type == 'password' ) {
			$passaccep = $field['required'];
			?>
            <div id="dig_cs_password" class="minput" <?php if ( $passaccep == 0 ) {
				echo 'style="display: none;"';
			} ?>>
                <input type="password" name="digits_reg_password"
                       id="digits_reg_password" <?php if ( $passaccep == 1 ) {
					echo "required";
				} ?>/>
                <label><?php _e( "Password", "digits" ); ?></label>
                <span class="<?php echo $bgtype; ?>"></span>
            </div>

			<?php
		} else if ( in_array( $type, array( 'mobmail', 'mobile_number', 'email' ) ) ) {

			if ( in_array( $type, array( 'mobmail', 'mobile_number'))){
			    $mobileaccp = 1;
				$show_otp = true;
			}
			if ( in_array( $type, array( 'email', 'mobmail'))){
				$emailaccep = 1;
			}

			if ( $emailaccep == 1 && $mobileaccp == 1 ) {
				$emailmob = __( "Email/Mobile Number", "digits" );
			} else if ( $mobileaccp > 0 ) {
				$emailmob = __( "Mobile Number", "digits" );
			} else if ( $emailaccep > 0 ) {
				$emailmob = __( "Email", "digits" );
			}
			$reqoropt = '';
			if($type!='email') {
				?>
                <div id="dig_cs_mobilenumber" class="minput">
                    <input type="text" class="mobile_field mobile_format digits_reg_email" name="digits_reg_mail"

                           value="<?php if ( isset( $mob ) || $emailaccep == 2 || $mobileaccp == 2 ) {
						       if ( $mobileaccp == 1 ) {
							       $reqoropt = "(" . __( "Optional", 'digits' ) . ")";
						       }

					       } else if ( isset( $mail ) ) {
						       echo $mail;
					       } ?>" <?php if ( empty( $reqoropt ) )
						echo 'required' ?>/>
                    <div class="countrycodecontainer registercountrycodecontainer">
                        <input type="text" name="digregcode"
                               class="input-text countrycode registercountrycode  <?php echo $theme; ?>"
                               value="<?php echo $userCountryCode; ?>" maxlength="6" size="3"
                               placeholder="<?php echo $userCountryCode; ?>" <?php if ( $emailaccep == 2 || $mobileaccp == 2 ) {
							echo 'required';
						} ?>/>
                    </div>
                    <label><?php if ( $emailaccep == 2 && $mobileaccp == 2 ) {
							echo __( 'Mobile Number', 'digits' );
						} else {
							echo $emailmob;
						} ?><?php echo $reqoropt; ?></label>
                    <span class="<?php echo $bgtype; ?>"></span>
                </div>

				<?php
			}
			if ( $emailaccep > 0 && $mobileaccp > 0 && $type!='mobile_number' ) {

				$emailmob = __( 'Email/Mobile Number', 'digits' );

				$reqoropt = "";
				if ( $emailaccep == 1 ) {
					$reqoropt = "(" . __( "Optional", 'digits' ) . ")";
				}
				if ( $emailaccep == 2 || $mobileaccp == 2 ) {
					$emailmob = __( 'Email', 'digits' );

				}

				?>
                <div class="dig_cs_email minput dig-mailsecond" <?php if ( $emailaccep != 2 && $mobileaccp != 2 ) {
					echo 'style="display: none;"';
				} ?>>
                    <input type="text" class="mobile_field mobile_format dig-secondmailormobile" name="mobmail2"
                            <?php if ( $emailaccep == 2 ) {
						echo "required";
					} ?>/>
                    <div class="countrycodecontainer secondregistercountrycodecontainer">
                        <input type="text" name="digregscode2"
                               class="input-text countrycode registersecondcountrycode  <?php echo $theme; ?>"
                               value="<?php echo $userCountryCode; ?>" maxlength="6" size="3"
                               placeholder="<?php echo $userCountryCode; ?>"/>
                    </div>
                    <label><span
                                class="dig_secHolder"><?php echo $emailmob; ?></span> <?php echo $reqoropt; ?>
                    </label>
                    <span class="<?php echo $bgtype; ?>"></span>
                </div>
				<?php

			}

		}else {

			dig_show_fields( array( $field ), $show_asterisk, 1, 'bgdark', 0 );
		}
	}
	?>

    <input type="hidden" name="code" class="register_code"/>
    <input type="hidden" name="csrf" class="register_csrf"/>
    <input type="hidden" name="dig_reg_mail" class="dig_reg_mail">
    <input type="hidden" name="dig_nounce" class="dig_nounce"
           value="<?php echo wp_create_nonce( 'dig_form' ) ?>">
	<?php
	if ( $show_otp ) {
		?>
        <div class="minput dig_register_otp" style="display: none;">
            <input type="text" name="dig_otp" id="dig-register-otp"
                   value="<?php if ( isset( $_POST['dig_otp'] ) ) {
				       echo dig_filter_string( $_POST['dig_otp'] );
			       } ?>"/>
            <label><?php _e( "OTP", "digits" ); ?></label>
            <span class="<?php echo $bgtype; ?>"></span>
        </div>

		<?php
	}

	$show_second_button = true;
	if ( $mobileaccp > -1 || $passaccep > -1 ) {
		if ($passaccep == 1) {
			$subVal = __( "Signup", "digits" );
			$show_second_button = false;
		} else {
			$subVal = __( "Signup With OTP", "digits" );
		}
		?>

        <button class="<?php echo $themee . ' ' . $bgtype; ?> button dig-signup-otp registerbutton"
                value="<?php echo $subVal; ?>" type="submit"><?php echo $subVal; ?></button>
		<?php echo "<div  class=\"dig_resendotp dig_logof_reg_resend\" id=\"dig_lo_resend_otp_btn\" dis='1'>" . __( "Resend OTP", "digits" ) . " <span>(00:<span>" . dig_getOtpTime() . "</span>)</span></div>"; ?>
	<?php } ?>

	<?php if ( $passaccep == 1 && $show_second_button ) { ?>
        <button class="dig_reg_btn_password <?php echo $themee . ' ' . $bgtype; ?> button registerbutton"
                attr-dis="1"
                value="<?php _e( "Signup With Password", "digits" ); ?>" type="submit">
			<?php _e( "Signup With Password", "digits" ); ?>
        </button>


	<?php }
	if($widget_type!=2){
	    ?>
        <div class="backtoLoginContainer"><a
                    class="backtoLogin"><?php _e( "Back to login", "digits" ); ?></a>
        </div>
        <?php
    }
}