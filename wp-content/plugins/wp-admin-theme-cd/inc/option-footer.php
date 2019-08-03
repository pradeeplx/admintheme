<?php

/*****************************************************************/
/* ADD LEFT FOOTER NOTICE */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_footer_notice' ) ) :

	if( ! wpat_option('credits') ) {
		function wp_admin_theme_cd_footer_notice( $text ) {
			$text = 'This theme was designed by <a target="_blank" href="https://www.creative-dive.de">Creative Dive</a>';
			return $text;
		}
	} else {
		function wp_admin_theme_cd_footer_notice( $text ) {
			return;
		}
	}	

endif;

add_filter('admin_footer_text', 'wp_admin_theme_cd_footer_notice');


/*****************************************************************/
/* ADD RIGHT FOOTER MEMORY NOTICE */
/*****************************************************************/

if( ! wpat_option('memory_usage') || ! wpat_option('memory_limit') || ! wpat_option('ip_address') || ! wpat_option('php_version') || ! wpat_option('wp_version') ) {

	if ( ! function_exists( 'wp_admin_theme_cd_memory_notice' ) ) :

		function wp_admin_theme_cd_memory_notice( $text ) {
			$text = wp_memory_data();
			return $text;
		}

	endif;

	add_filter('update_footer', 'wp_admin_theme_cd_memory_notice', 11);

}


/*****************************************************************/
/* ADD FOOTER INFORMATION */
/*****************************************************************/

if( ! wpat_option('memory_usage') ) {
	
	// get wp memory usage

	if ( ! function_exists( 'wp_memory_usage' ) ) : 

		function wp_memory_usage() {

			global $memory_limit, $memory_usage;
            
            if( ini_get( 'memory_limit' ) == '-1' ) {
                $memory_limit = '-1';
            } else { 
                $memory_limit = //(int)ini_get( 'memory_limit' ); 
                $memory_limit = (int)WP_MEMORY_LIMIT; 
            }
            
			$memory_usage = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage(true) / 1024 / 1024 ) : 0;
			

			if( $memory_usage != false && $memory_limit != false ) {

				global $memory_percent;

                if( ini_get( 'memory_limit' ) == '-1' ) {
                    $memory_percent = esc_html__( 'Unlimited', 'wp-admin-theme-cd' );
                } else {
				    $memory_percent = round( $memory_usage / $memory_limit * 100, 0 );
                }

			}

		}

	endif;
	
}

if( ! wpat_option('memory_limit') ) {

	// get wp memory limit

	if ( ! function_exists( 'wp_memory_limit' ) ) : 

		function wp_memory_limit( $size ) {

			global $wp_limit;

			$value  = substr( $size, -1 );
			$wp_limit = substr( $size, 0, -1 );

			$wp_limit = (int)$wp_limit;

			switch ( strtoupper( $value ) ) {
				case 'P' :
					$wp_limit*= 1024;
				case 'T' :
					$wp_limit*= 1024;
				case 'G' :
					$wp_limit*= 1024;
				case 'M' :
					$wp_limit*= 1024;
				case 'K' :
					$wp_limit*= 1024;
			}

			return $wp_limit;
		}  

	endif;
	
	// check memory limit
	
	if ( ! function_exists( 'wp_check_memory_limit' ) ) : 

		function wp_check_memory_limit() {

			global $check_memory;

			$check_memory = wp_memory_limit( WP_MEMORY_LIMIT );
			$check_memory = size_format( $check_memory );

			return ($check_memory) ? $check_memory : esc_html__( 'N/A', 'wp-admin-theme-cd' );

		}

	endif;
	
}

// output wp memory data

if ( ! function_exists( 'wp_memory_data' ) ) : 

	function wp_memory_data() {

		global $memory_limit, $memory_usage, $memory_percent, $check_memory, $wp_version;
        
        echo '<span class="wpat-footer-info">';
        
        // ip address
		if( ! wpat_option('ip_address') ) {
			
			// get ip address
			$server_ip_address = ( ! empty( $_SERVER[ 'SERVER_ADDR' ] ) ? $_SERVER[ 'SERVER_ADDR' ] : '' );
			if( $server_ip_address == '' || $server_ip_address == false ) { 
				$server_ip_address = ( ! empty( $_SERVER[ 'LOCAL_ADDR' ] ) ? $_SERVER[ 'LOCAL_ADDR' ] : '' );
			}
			
            echo '<span class="wpat-footer-info-sep">';
			if( is_rtl() ) {
				echo $server_ip_address . ' :' . esc_html__( 'IP', 'wp-admin-theme-cd' );
			} else {
				echo esc_html__( 'IP', 'wp-admin-theme-cd' ) . ' ' . $server_ip_address;
			}
            echo '</span>';
			
		}

		// php version
		if( ! wpat_option('php_version') ) {
			
            echo '<span class="wpat-footer-info-sep">';
			if( is_rtl() ) {
				echo PHP_VERSION . ' :' . esc_html__( 'PHP', 'wp-admin-theme-cd' );
			} else {
				echo esc_html__( 'PHP', 'wp-admin-theme-cd' ) . ' ' . PHP_VERSION;
			}
            echo '</span>';
			
			
		}

		// wp version
		if( ! wpat_option('wp_version') ) {
			
            echo '<span class="wpat-footer-info-sep">';
			if( is_rtl() ) {
				echo $wp_version . ' :' . esc_html__( 'WP', 'wp-admin-theme-cd' );
			} else {
				echo esc_html__( 'WP', 'wp-admin-theme-cd' ) . ' ' . $wp_version;
			}
            echo '</span>';
			
		}

		echo '</span><br><span class="wpat-footer-info">';
		
		// memory usage
		if( ! wpat_option('memory_usage') ) {
			
			wp_memory_usage();

			if ( $memory_percent <= 65 ) $memory_status = '#20bf6b';
            if ( $memory_percent > 65 ) $memory_status = '#f7b731';
            if ( $memory_percent > 85 ) $memory_status = '#eb3b5a';
            
            if ( $memory_percent == 'Unlimited' ) {
                $memory_unit = '';
            } else {
                $memory_unit = '%';
            }

            echo '<span class="wpat-footer-info-sep">';
			if( is_rtl() ) {
				echo '<span class="memory-status" style="background:' . $memory_status . '"><strong>' . $memory_unit . $memory_percent . '</strong></span>';
				echo ' MB ' . $memory_limit . esc_html__( ' of ', 'wp-admin-theme-cd' );
				echo $memory_usage . ': ' . esc_html__( 'Memory Usage', 'wp-admin-theme-cd' );
			} else {
				echo esc_html__( 'WP Memory Usage', 'wp-admin-theme-cd' ) . ': ' . $memory_usage;
				echo esc_html__( ' of', 'wp-admin-theme-cd' ) . ' ' . $memory_limit . ' MB';
				echo '<span class="memory-status" style="background:' . $memory_status . '"><strong>' . $memory_percent . $memory_unit . '</strong></span>';
			}
            echo '</span>';

		}
		
		// wp memory limit
		if( ! wpat_option('memory_limit') ) {
			
			wp_check_memory_limit();
			
            echo '<span class="wpat-footer-info-sep">';
			if( is_rtl() ) {
				echo $check_memory . ' :' . esc_html__( 'WP Memory Limit', 'wp-admin-theme-cd' );
			} else {
				echo esc_html__( 'WP Memory Limit', 'wp-admin-theme-cd' ) . ': ' . $check_memory;
			}
			echo '</span>';
            
		}
		
		// memory available
		if( ! wpat_option('memory_available') ) {
			
            echo '<span class="wpat-footer-info-sep">';
			if( is_rtl() ) {
				echo 'MB ' . (int)@ini_get( 'memory_limit' ) . ' :' . esc_html__( 'Memory Available', 'wp-admin-theme-cd' );
			} else {
				echo esc_html__( 'Memory Available', 'wp-admin-theme-cd' ) . ': ' . (int)@ini_get( 'memory_limit' ) . ' MB';
			}
			echo '</span>';
            
		}
        
        echo '</span>';

	}

endif;