<?php

/*****************************************************************/
/* REMOVE USER THEME OPTION */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_remove_theme_option' ) ) :

	function wp_admin_theme_cd_remove_theme_option() {
        
		global $_wp_admin_css_colors;

		/* Get fresh color data */
		$fresh_color_data = $_wp_admin_css_colors['fresh'];

		/* Remove everything else */
		$_wp_admin_css_colors = array( 'fresh' => $fresh_color_data );
	}

endif;

add_action( 'admin_init', 'wp_admin_theme_cd_remove_theme_option', 1 );


/*****************************************************************/
/* SET ALL USER ADMIN THEME OPTION TO DEFAULT */
/*****************************************************************/
 
if ( ! function_exists( 'wp_admin_theme_cd_set_default_theme' ) ) :

	function wp_admin_theme_cd__set_default_theme( $color ){
		return 'fresh';
	}

endif; 

add_filter( 'get_user_option_admin_color', 'wp_admin_theme_cd__set_default_theme' );


/*****************************************************************/
/* SVG SUPPORT */
/*****************************************************************/

if( wpat_option('wp_svg') ) {
	
	if ( ! function_exists( 'wp_admin_theme_cd_svg_support' ) ) : 
	
		function wp_admin_theme_cd_svg_support( $svg_mime ) {
			$svg_mime['svg'] = 'image/svg+xml';		
			return $svg_mime;
		}
	
	endif;

	add_filter('upload_mimes', 'wp_admin_theme_cd_svg_support', 10, 4);
	
}


/*****************************************************************/
/* ICO SUPPORT */
/*****************************************************************/

if( wpat_option('wp_ico') ) {
	
	if ( ! function_exists( 'wp_admin_theme_cd_ico_support' ) ) : 
	
		function wp_admin_theme_cd_ico_support( $ico_mime ) {
			$ico_mime['ico'] = 'image/x-icon';
			return $ico_mime;
		}
	
	endif;

	add_filter('upload_mimes', 'wp_admin_theme_cd_ico_support', 10, 5);

}


/*****************************************************************/
/* UPLOAD MIMETYPE FIX */
/*****************************************************************/

if( wpat_option('wp_svg') || wpat_option('wp_ico') ) {
	
	if ( ! function_exists( 'wp_admin_theme_cd_mimetype_fix' ) ) : 
	
		function wp_admin_theme_cd_mimetype_fix( $data, $file, $filename, $mimes ) {
			$wp_filetype = wp_check_filetype( $filename, $mimes );	
			$ext = $wp_filetype['ext'];
			$type = $wp_filetype['type'];
			$proper_filename = $data['proper_filename'];
			return compact( 'ext', 'type', 'proper_filename' );
		}	
	
	endif;
	
	add_filter( 'wp_check_filetype_and_ext', 'wp_admin_theme_cd_mimetype_fix', 10, 4 );

}


/*****************************************************************/
/* REMOVE WP VERSION META TAG */
/*****************************************************************/

if( wpat_option('wp_version_tag') ) {

	remove_action('wp_head', 'wp_generator');

}


/*****************************************************************/
/* REMOVE WP EMOTICONS */
/*****************************************************************/

if( wpat_option('wp_emoji') ) {

	if ( ! function_exists( 'remove_emoji' ) ) : 
	
		function remove_emoji() {
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('admin_print_scripts', 'print_emoji_detection_script');
			remove_action('admin_print_styles', 'print_emoji_styles');
			remove_action('wp_print_styles', 'print_emoji_styles');
			remove_filter('the_content_feed', 'wp_staticize_emoji');
			remove_filter('comment_text_rss', 'wp_staticize_emoji');
			remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
			add_filter('tiny_mce_plugins', 'remove_tinymce_emoji');
		}
	
	endif;

	add_action('init', 'remove_emoji');

	if ( ! function_exists( 'remove_tinymce_emoji' ) ) : 
	
		function remove_tinymce_emoji( $plugins ) {
			if (!is_array( $plugins )) {
				return array();
			}
			return array_diff( $plugins, array( 'wpemoji' ));
		}
	
	endif;

}


/*****************************************************************/
/* REMOVE RSS FEED LINKS */
/*****************************************************************/

if( wpat_option('wp_feed_links') ) {
	
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	
	if ( ! function_exists( 'wp_admin_theme_cd_disable_rss' ) ) : 
	
		function wp_admin_theme_cd_disable_rss() {
			wp_die( 
				esc_html__( 'No feed available, please visit our', 'wp-admin-theme-cd' ) . ' <a href="'. esc_url( home_url( '/' ) ) .'">' . esc_html__( 'homepage', 'wp-admin-theme-cd' ) . '</a>!'
			);
		}
	
	endif;

	add_action('do_feed', 'wp_admin_theme_cd_disable_rss', 1);
	add_action('do_feed_rdf', 'wp_admin_theme_cd_disable_rss', 1);
	add_action('do_feed_rss', 'wp_admin_theme_cd_disable_rss', 1);
	add_action('do_feed_rss2', 'wp_admin_theme_cd_disable_rss', 1);
	add_action('do_feed_atom', 'wp_admin_theme_cd_disable_rss', 1);
	add_action('do_feed_rss2_comments', 'wp_admin_theme_cd_disable_rss', 1);
	add_action('do_feed_atom_comments', 'wp_admin_theme_cd_disable_rss', 1);
		
}


/*****************************************************************/
/* REMOVE RSD LINK */
/*****************************************************************/

if( wpat_option('wp_rsd_link') ) {
	
	remove_action('wp_head', 'rsd_link');
	
}


/*****************************************************************/
/* REMOVE WLWMANIFEST LINK */
/*****************************************************************/

if( wpat_option('wp_wlwmanifest') ) {
	
	remove_action('wp_head', 'wlwmanifest_link');
	
}


/*****************************************************************/
/* REMOVE SHORTLINK */
/*****************************************************************/

if( wpat_option('wp_shortlink') ) {
	
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'wp_shortlink_header', 10, 0);
}


/*****************************************************************/
/* REMOVE REST API */
/*****************************************************************/

if( wpat_option('wp_rest_api') ) {
	
	remove_action('wp_head','rest_output_link_wp_head',10);
	add_filter('rest_enabled','_return_false');
	add_filter('rest_jsonp_enabled','_return_false'); 
	
}


/*****************************************************************/
/* REMOVE oEMBED */
/*****************************************************************/

if( wpat_option('wp_oembed') ) {

	remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
	
	if ( ! function_exists( 'wp_admin_theme_cd_block_wp_embed' ) ) : 
	
		function wp_admin_theme_cd_block_wp_embed() {
			wp_deregister_script('wp-embed'); 
		}

    endif;

	add_action('init', 'wp_admin_theme_cd_block_wp_embed');

}


/*****************************************************************/
/* REMOVE XML-RPC */
/*****************************************************************/

if( wpat_option('wp_xml_rpc') ) {
	
	add_filter( 'xmlrpc_enabled', '__return_false' );
	
	if ( ! function_exists( 'wp_admin_theme_cd_remove_x_pingback' ) ) : 
	
		function wp_admin_theme_cd_remove_x_pingback( $headers ) {
			unset( $headers['X-Pingback'] );
			return $headers;
		}

    endif;

	add_filter( 'wp_headers', 'wp_admin_theme_cd_remove_x_pingback' );

}


/*****************************************************************/
/* STOP WP HEARTBEAT */
/*****************************************************************/

if( wpat_option('wp_heartbeat') ) {
	
	if ( ! function_exists( 'wp_admin_theme_cd_stop_heartbeat' ) ) : 
	
		function wp_admin_theme_cd_stop_heartbeat() {
			wp_deregister_script('heartbeat');
		}

    endif;

	add_action('init', 'wp_admin_theme_cd_stop_heartbeat', 1);

}


/*****************************************************************/
/* REMOVE REL LINKS PREV/NEXT  */
/*****************************************************************/

if( wpat_option('wp_rel_link') ) {
	
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'index_rel_link');
	
}


/*****************************************************************/
/* DISABLE SELF PINGBACKS  */
/*****************************************************************/

if( wpat_option('wp_self_pingback') ) {
    
	if ( ! function_exists( 'wp_admin_theme_cd_disable_self_pingback' ) ) : 
	
		function wp_admin_theme_cd_disable_self_pingback( &$links ) {
			$home = get_option( 'home' );
			foreach( $links as $l => $link ) {
				if( 0 === strpos( $link, $home ) ) {
					unset($links[$l]);  
				}
			}
		}

    endif;

    add_action( 'pre_ping', 'wp_admin_theme_cd_disable_self_pingback' );

}  


/*****************************************************************/
/* SET REFERRER POLICY META TAG */
/*****************************************************************/

if( wpat_option('meta_referrer_policy') && wpat_option('meta_referrer_policy') != 'none' ) {

    if ( ! function_exists( 'wp_admin_theme_cd_meta_referrer_policy' ) ) : 

        function wp_admin_theme_cd_meta_referrer_policy() {
            echo '<meta name="referrer" content="' . wpat_option('meta_referrer_policy') . '">';
        }

    endif;

    add_action('wp_head', 'wp_admin_theme_cd_meta_referrer_policy');

}


/*****************************************************************/
/* ADD CUSTOM CODE TO WP HEAD */
/*****************************************************************/

if( wpat_option('wp_header_code') ) {

    if ( ! function_exists( 'wp_admin_theme_cd_add_code_to_wphead' ) ) :

        function wp_admin_theme_cd_add_code_to_wphead() {
            echo wpat_option('wp_header_code');
        }

    endif;

    add_action( 'wp_head', 'wp_admin_theme_cd_add_code_to_wphead' );
    
}


/*****************************************************************/
/* ADD CUSTOM CODE TO WP FOOTER */
/*****************************************************************/

if( wpat_option('wp_footer_code') ) {
    
    if ( ! function_exists( 'wp_admin_theme_cd_add_code_to_wpfooter' ) ) :

        function wp_admin_theme_cd_add_code_to_wpfooter() {
            echo wpat_option('wp_footer_code');
        }

    endif;
	
    add_action( 'wp_footer', 'wp_admin_theme_cd_add_code_to_wpfooter', 999 );
    
}

    
/*****************************************************************/
/* REMOVE WP ADMIN META BOX  */
/*****************************************************************/

if( ! function_exists('wp_admin_theme_cd_remove_metaboxes') ) :

    function wp_admin_theme_cd_remove_metaboxes() {
        
        if( wpat_option('mb_custom_fields') ) {    
            remove_meta_box( 'postcustom', '', 'normal' );	
        }

        if( wpat_option('mb_commentstatus') ) {  
            remove_meta_box( 'commentstatusdiv', '', 'normal' );
        }

        if( wpat_option('mb_comments') ) {  
            remove_meta_box( 'commentsdiv', '', 'normal' );
        }

        if( wpat_option('mb_author') ) {  
            remove_meta_box( 'authordiv', '', 'normal' );
        }

        if( wpat_option('mb_category') ) {  
            remove_meta_box( 'categorydiv', '', 'side' );
        }

        if( wpat_option('mb_format') ) {  
            remove_meta_box( 'formatdiv', '', 'side' );
        }

        if( wpat_option('mb_pageparent') ) {  
            remove_meta_box( 'pageparentdiv', '', 'side' );
        }

        if( wpat_option('mb_postexcerpt') ) {  
            remove_meta_box( 'postexcerpt', '', 'normal' );
        }

        if( wpat_option('mb_postimage') ) {  
            remove_meta_box( 'postimagediv', '', 'side' );
        }

        if( wpat_option('mb_revisions') ) {  
            remove_meta_box( 'revisionsdiv', '', 'normal' );
        }

        if( wpat_option('mb_slug') ) {  
            remove_meta_box( 'slugdiv', '', 'normal' );
        }

        if( wpat_option('mb_tags') ) {  
            remove_meta_box( 'tagsdiv-post_tag', '', 'side' );
        }

        if( wpat_option('mb_trackbacks') ) {  
            remove_meta_box( 'trackbacksdiv', '', 'normal' );
        }

    }

endif;

add_action( 'do_meta_boxes' , 'wp_admin_theme_cd_remove_metaboxes' );


/*****************************************************************/
/* REMOVE WP ADMIN DASHBOARD WIDGETS  */
/*****************************************************************/

if( ! function_exists('wp_admin_theme_cd_remove_db_widgets') ) :

    function wp_admin_theme_cd_remove_db_widgets() {
        
        if( wpat_option('dbw_quick_press') ) {
            remove_meta_box ( 'dashboard_quick_press', 'dashboard', 'side' ); // Quick Draft
        }
        
        if( wpat_option('dbw_right_now') ) {
            remove_meta_box ( 'dashboard_right_now', 'dashboard', 'normal' ); // At the Glance
            if( is_multisite() ) {
                remove_meta_box ( 'network_dashboard_right_now', 'dashboard-network', 'normal' );
            } 
        }
        
        if( wpat_option('dbw_activity') ) {
            remove_meta_box ( 'dashboard_activity', 'dashboard', 'normal' ); // Activity
        }
        
        if( wpat_option('dbw_primary') ) {
            remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); // WordPress Events and News
            if( is_multisite() ) {
                remove_meta_box( 'dashboard_primary', 'dashboard-network', 'side' );
            }
        }
        
        if( wpat_option('dbw_welcome') ) {
            remove_action('welcome_panel', 'wp_welcome_panel'); // Welcome
        }

    }

endif;

add_action( 'wp_dashboard_setup' , 'wp_admin_theme_cd_remove_db_widgets' );

if( is_multisite() ) {
    add_action( 'wp_network_dashboard_setup' , 'wp_admin_theme_cd_remove_db_widgets' );
}

/*****************************************************************/
/* REMOVE WP ADMIN WIDGETS  */
/*****************************************************************/

if( ! function_exists('wp_admin_theme_cd_remove_widgets') ) :

    function wp_admin_theme_cd_remove_widgets() {
        
        if( wpat_option('wt_pages') ) {
            unregister_widget('WP_Widget_Pages');
        }
        
        if( wpat_option('wt_calendar') ) {
            unregister_widget('WP_Widget_Calendar');
        }
        
        if( wpat_option('wt_archives') ) {
            unregister_widget('WP_Widget_Archives');
        }
        
        if( wpat_option('wt_meta') ) {
            unregister_widget('WP_Widget_Meta');
        }
        
        if( wpat_option('wt_search') ) {
            unregister_widget('WP_Widget_Search');
        }
        
        if( wpat_option('wt_text') ) {
            unregister_widget('WP_Widget_Text');
        }
        
        if( wpat_option('wt_categories') ) {
            unregister_widget('WP_Widget_Categories');
        }
        
        if( wpat_option('wt_recent_posts') ) {
            unregister_widget('WP_Widget_Recent_Posts');
        }
        
        if( wpat_option('wt_recent_comments') ) {
            unregister_widget('WP_Widget_Recent_Comments');
        }
        
        if( wpat_option('wt_rss') ) {
            unregister_widget('WP_Widget_RSS');
        }
        
        if( wpat_option('wt_tag_cloud') ) {
            unregister_widget('WP_Widget_Tag_Cloud');
        }
        
        if( wpat_option('wt_nav') ) {
            unregister_widget('WP_Nav_Menu_Widget');
        }
        
        if( wpat_option('wt_image') ) {
            unregister_widget('WP_Widget_Media_Image');
        }
        
        if( wpat_option('wt_audio') ) {
            unregister_widget('WP_Widget_Media_Audio');
        }
        
        if( wpat_option('wt_video') ) {
            unregister_widget('WP_Widget_Media_Video');
        }
        
        if( wpat_option('wt_gallery') ) {
            unregister_widget('WP_Widget_Media_Gallery');
        }
        
        if( wpat_option('wt_html') ) {
            unregister_widget('WP_Widget_Custom_HTML');
        }

    }

endif;

add_action( 'widgets_init' , 'wp_admin_theme_cd_remove_widgets' );


/*****************************************************************/
/* REMOVE WP SCREEN OPTIONS  */
/*****************************************************************/

if( wpat_option('hide_screen_option') ) {

    if( ! function_exists('wp_admin_theme_cd_remove_screen_options') ) :

        function wp_admin_theme_cd_remove_screen_options() {
            return false; 
        }

    endif;

    add_filter('screen_options_show_screen', 'wp_admin_theme_cd_remove_screen_options');

}


/*****************************************************************/
/* REMOVE WP CONTEXTUAL HELP  */
/*****************************************************************/

if( wpat_option('hide_help') ) {

    if( ! function_exists('wp_admin_theme_cd_remove_contextual_help') ) :
    
        function wp_admin_theme_cd_remove_contextual_help( $old_help, $screen_id, $screen ) {
            $screen->remove_help_tabs();
            return $old_help;
        }
    
    endif;
    
    add_filter( 'contextual_help', 'wp_admin_theme_cd_remove_contextual_help', 999, 3 );
    
}


/*****************************************************************/
/* REMOVE COMMENTS MENU FROM ADMIN BAR  */
/*****************************************************************/

if( wpat_option('hide_adminbar_comments') ) {

    if( ! function_exists('wp_admin_remove_adminbar_comments') ) :
    
        function wp_admin_remove_adminbar_comments() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('comments');
        }
    
    endif;
    
    add_action( 'wp_before_admin_bar_render', 'wp_admin_remove_adminbar_comments' );
    
}


/*****************************************************************/
/* REMOVE NEW CONTENT MENU FROM ADMIN BAR  */
/*****************************************************************/

if( wpat_option('hide_adminbar_new') ) {

    if( ! function_exists('wp_admin_theme_cd_remove_adminbar_new') ) :

        function wp_admin_theme_cd_remove_adminbar_new() {
            global $wp_admin_bar;   
            $wp_admin_bar->remove_menu('new-content');   
        }
    
    endif;

    add_action( 'wp_before_admin_bar_render', 'wp_admin_theme_cd_remove_adminbar_new', 999 );
    
}


/*****************************************************************/
/* REMOVE WP (LOGO) MENU FROM ADMIN BAR  */
/*****************************************************************/

if( wpat_option('toolbar_wp_icon') ) {

    if( ! function_exists('wp_admin_theme_cd_remove_adminbar_wp_logo') ) :
    
        function wp_admin_theme_cd_remove_adminbar_wp_logo() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('wp-logo');
        }
    
    endif;
    
    add_action('wp_before_admin_bar_render', 'wp_admin_theme_cd_remove_adminbar_wp_logo', 0);
    
}


/*****************************************************************/
/* REMOVE CUSTOMIZE LINK FROM ADMIN BAR  */
/*****************************************************************/

if( wpat_option('hide_adminbar_customize') ) {

    if( ! function_exists('wp_admin_theme_cd_remove_adminbar_customize') ) :
    
        function wp_admin_theme_cd_remove_adminbar_customize() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('customize');
        }
    
    endif;
    
    add_action('wp_before_admin_bar_render', 'wp_admin_theme_cd_remove_adminbar_customize', 0);
    
}


/*****************************************************************/
/* REMOVE SEARCH FROM ADMIN BAR  */
/*****************************************************************/

if( wpat_option('hide_adminbar_search') ) {

    if( ! function_exists('wp_admin_theme_cd_remove_adminbar_search') ) :
    
        function wp_admin_theme_cd_remove_adminbar_search() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('search');
        }
    
    endif;
    
    add_action('wp_before_admin_bar_render', 'wp_admin_theme_cd_remove_adminbar_search', 0);
    
}


/*****************************************************************/
/* REMOVE ADMIN BAR COMPLETE */
/*****************************************************************/
/*
if( wpat_option('toolbar') ) {

    if( ! function_exists('wp_admin_theme_cd_remove_adminbar_complete') ) :

        function wp_admin_theme_cd_remove_adminbar_complete() {
            wp_deregister_script('admin-bar');
            wp_deregister_style('admin-bar');  
            remove_action('admin_init', '_wp_admin_bar_init');
            remove_action('in_admin_header', 'wp_admin_bar_render', 0);
        }

    endif;

    add_action('admin_head', 'wp_admin_theme_cd_remove_adminbar_complete', 0);
    
}*/