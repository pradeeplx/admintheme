<?php /*
Plugin Name: WP Admin Theme CD
Plugin URI: http://www.creative-dive.de
Description: A clean and modern WordPress Admin Theme.
Version: 1.9
Author: Martin Jost
Author URI: http://www.creative-dive.de
Text Domain: wp-admin-theme-cd
Domain Path: /languages
*/

define( 'WP_ADMIN_THEME_CD_VER', '1.9' ); // update plugin main file version number manually
define( 'WP_ADMIN_THEME_CD_PLUGIN_NAME', 'WP Admin Theme CD' );
define( 'WP_ADMIN_THEME_CD_ENVATO_ID', '20354956' );
define( 'WP_ADMIN_THEME_CD_ENVATO_URL', 'https://codecanyon.net/item/wp-admin-theme-cd-a-clean-and-modern-wordpress-admin-theme/20354956' );
define( 'WP_ADMIN_THEME_CD_ENVATO_THEME_REVIEW_URL', 'http://codecanyon.net/downloads#item-' . WP_ADMIN_THEME_CD_ENVATO_ID );
define( 'WP_ADMIN_THEME_CD_AUTHOR_MAIL', 'info@creative-dive.de' );

define( 'WP_ADMIN_THEME_CD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_ADMIN_THEME_CD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_ADMIN_THEME_CD_PLUGIN', __FILE__ );

update_option( 'wp_admin_theme_cd_license', 'active');
/*****************************************************************/
/* CREATE PLUGIN PATHS */
/*****************************************************************/

/* INFO: By adding custom wp filter, this plugin can be called from theme folder without installing it manually */

if ( ! function_exists( 'wp_admin_theme_cd_path' ) ) :

    function wp_admin_theme_cd_path( $path ) {
        
		// Get custom filter path	
        if( has_filter( 'wp_admin_theme_cd_path' ) ) {
			return apply_filters( 'wp_admin_theme_cd_path', $path );
		}
        
		// Get plugin path
		return plugins_url( $path , __FILE__ );
        
    }

endif;


if ( ! function_exists( 'wp_admin_theme_cd_dir' ) ) :

    function wp_admin_theme_cd_dir( $path ) {

		// Get custom filter dir path
        if( has_filter( 'wp_admin_theme_cd_dir' ) ) {
			return apply_filters( 'wp_admin_theme_cd_dir', $path );	
		}
        
		// Get plugin dir path
		return plugin_dir_path( __FILE__ ) . $path;
        
    }

endif;


/*****************************************************************/
/* CREATE THE PLUGIN */
/*****************************************************************/

if( ! class_exists('WP_Admin_Theme_CD_Options') ) :

	class WP_Admin_Theme_CD_Options {

		/*****************************************************************/
		/* ATTRIBUTES */
		/*****************************************************************/

		// Refers to a single instance of this class.
		private static $instance = null;

		// Saved options
		public $options;


		/*****************************************************************/
		/* CONSTRUCTOR */
		/*****************************************************************/

		// Creates or returns an instance of this class.
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;

		} // end get_instance;


		/*****************************************************************/
		/* INITIALIZES THE PLUGIN */
		/*****************************************************************/

		public function __construct() {

			if( is_admin() ) {

				require_once( ABSPATH . 'wp-admin/includes/screen.php' );

				// Add textdomain
				load_plugin_textdomain( 'wp-admin-theme-cd', null, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
				
				// Add the page to the admin menu
				add_action( 'admin_menu', array( $this, 'wp_admin_theme_cd_add_page' ) );

				// Register settings options
				add_action( 'admin_init', array( $this, 'wp_admin_theme_cd_register_settings') );

				// Register page options
				add_action( 'admin_init', array( $this, 'wp_admin_theme_cd_register_page_options') );
				
				// Register plugin page scripts
				add_action( 'admin_enqueue_scripts', array( $this, 'wp_admin_theme_cd_load_plugin_page_specific_scripts') ); 

				// Register global javascript and stylesheets
				add_action( 'admin_enqueue_scripts', array( $this, 'wp_admin_theme_cd_enqueue_admin_js' ) );

				// Register generate admin css files
				add_action( 'admin_init', array( $this, 'wp_admin_theme_cd_generate_custom_admin_css' ) );
				
				// Register generate login css files
				add_action( 'admin_init', array( $this, 'wp_admin_theme_cd_generate_custom_login_css' ) );
				
			}

			// Set all option field names
			$this->option_fields = array(
				'user_box' => esc_html__( 'User Box', 'wp-admin-theme-cd' ),
				'company_box' => esc_html__( 'Company Box', 'wp-admin-theme-cd' ),
				'company_box_logo' => esc_html__( 'Company Box Logo', 'wp-admin-theme-cd' ),
				'company_box_logo_size' => esc_html__( 'Company Box Logo Size', 'wp-admin-theme-cd' ),
				'thumbnail' => esc_html__( 'Thumbnails', 'wp-admin-theme-cd' ),
				'post_page_id' => esc_html__( 'Post/Page IDs', 'wp-admin-theme-cd' ),
				'hide_help' => esc_html__( 'Contextual Help', 'wp-admin-theme-cd' ),
				'hide_screen_option' => esc_html__( 'Screen Options', 'wp-admin-theme-cd' ),
				'left_menu_width' => esc_html__( 'Left Menu Width', 'wp-admin-theme-cd' ),
				'left_menu_expand' => esc_html__( 'Left Menu Expandable', 'wp-admin-theme-cd' ),
				'spacing' => esc_html__( 'Spacing', 'wp-admin-theme-cd' ),
				'spacing_max_width' => esc_html__( 'Spacing Max Width', 'wp-admin-theme-cd' ),
				'credits' => esc_html__( 'Credits', 'wp-admin-theme-cd' ),
				'google_webfont' => esc_html__( 'Custom Web Font', 'wp-admin-theme-cd' ),
				'google_webfont_weight' => esc_html__( 'Custom Web Font Weight', 'wp-admin-theme-cd' ),
				'toolbar' => esc_html__( 'Toolbar', 'wp-admin-theme-cd' ),
				'hide_adminbar_comments' => esc_html__( 'Toolbar Comments Menu', 'wp-admin-theme-cd' ),
				'hide_adminbar_new' => esc_html__( 'Toolbar New Content Menu', 'wp-admin-theme-cd' ),
				'hide_adminbar_customize' => esc_html__( 'Toolbar Customize Link', 'wp-admin-theme-cd' ),
				'hide_adminbar_search' => esc_html__( 'Toolbar Search', 'wp-admin-theme-cd' ),
				'toolbar_wp_icon' => esc_html__( 'Toolbar WP Icon', 'wp-admin-theme-cd' ),            
				'toolbar_icon' => esc_html__( 'Custom Toolbar Icon', 'wp-admin-theme-cd' ),         
				'toolbar_color' => esc_html__( 'Toolbar Color', 'wp-admin-theme-cd' ),
				'theme_color' => esc_html__( 'Theme Color', 'wp-admin-theme-cd' ),
				'theme_background' => esc_html__( 'Background Gradient Start Color', 'wp-admin-theme-cd' ),
				'theme_background_end' => esc_html__( 'Background Gradient End Color', 'wp-admin-theme-cd' ),
				'login_disable' => esc_html__( 'Customized Login Page', 'wp-admin-theme-cd' ),
				'login_title' => esc_html__( 'Login Title', 'wp-admin-theme-cd' ),
				'logo_upload' => esc_html__( 'Login Logo', 'wp-admin-theme-cd' ),
				'logo_size' => esc_html__( 'Login Logo Size', 'wp-admin-theme-cd' ),
				'login_bg' => esc_html__( 'Login Background Image', 'wp-admin-theme-cd' ),
				'memory_usage' => esc_html__( 'Memory Usage', 'wp-admin-theme-cd' ),
				'memory_limit' => esc_html__( 'WP Memory Limit', 'wp-admin-theme-cd' ),
				'memory_available' => esc_html__( 'Memory Available', 'wp-admin-theme-cd' ),
				'php_version' => esc_html__( 'PHP Version', 'wp-admin-theme-cd' ),
				'ip_address' => esc_html__( 'IP Address', 'wp-admin-theme-cd' ),
				'wp_version' => esc_html__( 'WP Version', 'wp-admin-theme-cd' ),
				'css_admin' => esc_html__( 'WP Admin CSS', 'wp-admin-theme-cd' ),
				'css_login' => esc_html__( 'WP Login CSS', 'wp-admin-theme-cd' ),
				'wp_svg' => esc_html__( 'SVG Support', 'wp-admin-theme-cd' ),
				'wp_ico' => esc_html__( 'ICO Support', 'wp-admin-theme-cd' ),
				'disable_page_system' => esc_html__( 'WPAT System Info Page', 'wp-admin-theme-cd' ),
				'disable_page_export' => esc_html__( 'WPAT Im- / Export Page', 'wp-admin-theme-cd' ),
				'disable_page_ms' => esc_html__( 'WPAT Multisite Sync Page', 'wp-admin-theme-cd' ),
				'disable_theme_options' => esc_html__( 'Network Theme Options', 'wp-admin-theme-cd' ),
				'wp_version_tag' => esc_html__( 'WP Version Meta-Tag', 'wp-admin-theme-cd' ),
				'wp_emoji' => esc_html__( 'WP Emoji', 'wp-admin-theme-cd' ),
				'wp_feed_links' => esc_html__( 'WP RSS Feed', 'wp-admin-theme-cd' ),
				'wp_rsd_link' => esc_html__( 'WP RSD', 'wp-admin-theme-cd' ),
				'wp_wlwmanifest' => esc_html__( 'WP Wlwmanifest', 'wp-admin-theme-cd' ),
				'wp_shortlink' => esc_html__( 'WP Shortlink', 'wp-admin-theme-cd' ),
				'wp_rest_api' => esc_html__( 'WP REST API', 'wp-admin-theme-cd' ),
				'wp_oembed' => esc_html__( 'WP oEmbed', 'wp-admin-theme-cd' ),
				'wp_xml_rpc' => esc_html__( 'WP XML-RPC / X-Pingback', 'wp-admin-theme-cd' ),
				'wp_heartbeat' => esc_html__( 'WP Heartbeat', 'wp-admin-theme-cd' ),
				'wp_rel_link' => esc_html__( 'WP Rel Links', 'wp-admin-theme-cd' ),
				'wp_self_pingback' => esc_html__( 'WP Self Pingbacks', 'wp-admin-theme-cd' ),
				'mb_custom_fields' => esc_html__( 'Custom Fields Meta Box', 'wp-admin-theme-cd' ),
				'mb_commentstatus' => esc_html__( 'Comments Status Meta Box', 'wp-admin-theme-cd' ),
				'mb_comments' => esc_html__( 'Comments Meta Box', 'wp-admin-theme-cd' ),
				'mb_author' => esc_html__( 'Author Meta Box', 'wp-admin-theme-cd' ),
				'mb_category' => esc_html__( 'Categories Meta Box', 'wp-admin-theme-cd' ),
				'mb_format' => esc_html__( 'Post Format Meta Box', 'wp-admin-theme-cd' ),
				'mb_pageparent' => esc_html__( 'Page Parent Meta Box', 'wp-admin-theme-cd' ),
				'mb_postexcerpt' => esc_html__( 'Post Excerpt Meta Box', 'wp-admin-theme-cd' ),
				'mb_postimage' => esc_html__( 'Post Image Meta Box', 'wp-admin-theme-cd' ),
				'mb_revisions' => esc_html__( 'Revisions Meta Box', 'wp-admin-theme-cd' ),
				'mb_slug' => esc_html__( 'Slug Meta Box', 'wp-admin-theme-cd' ),
				'mb_tags' => esc_html__( 'Tags Meta Box', 'wp-admin-theme-cd' ),
				'mb_trackbacks' => esc_html__( 'Trackbacks Meta Box', 'wp-admin-theme-cd' ),
				'dbw_quick_press' => esc_html__( 'Qick Draft Widget', 'wp-admin-theme-cd' ),
				'dbw_right_now' => esc_html__( 'At the Glance Widget', 'wp-admin-theme-cd' ),
				'dbw_activity' => esc_html__( 'Activity Widget', 'wp-admin-theme-cd' ),
				'dbw_primary' => esc_html__( 'WP Events & News Widget', 'wp-admin-theme-cd' ),
				'dbw_welcome' => esc_html__( 'Welcome Widget', 'wp-admin-theme-cd' ),
				'dbw_wpat_user_log' => esc_html__( 'WPAT User Activities Widget', 'wp-admin-theme-cd' ),
				'dbw_wpat_sys_info' => esc_html__( 'WPAT System Info Widget', 'wp-admin-theme-cd' ),
				'dbw_wpat_count_post' => esc_html__( 'WPAT Post Count Widget', 'wp-admin-theme-cd' ),
				'dbw_wpat_count_page' => esc_html__( 'WPAT Page Count Widget', 'wp-admin-theme-cd' ),
				'dbw_wpat_count_comment' => esc_html__( 'WPAT Comment Count Widget', 'wp-admin-theme-cd' ),
				'dbw_wpat_recent_post' => esc_html__( 'WPAT Recent Posts Widget', 'wp-admin-theme-cd' ),
				'dbw_wpat_recent_page' => esc_html__( 'WPAT Recent Pages Widget', 'wp-admin-theme-cd' ),
				'dbw_wpat_recent_comment' => esc_html__( 'WPAT Recent Comments Widget', 'wp-admin-theme-cd' ),
				'dbw_wpat_memory' => esc_html__( 'WPAT Memory Usage Widget', 'wp-admin-theme-cd' ),
				'wt_pages' => esc_html__( 'Pages Widget', 'wp-admin-theme-cd' ),
				'wt_calendar' => esc_html__( 'Calendar Widget', 'wp-admin-theme-cd' ),
				'wt_archives' => esc_html__( 'Archives Widget', 'wp-admin-theme-cd' ),
				'wt_meta' => esc_html__( 'Meta Widget', 'wp-admin-theme-cd' ),
				'wt_search' => esc_html__( 'Search Widget', 'wp-admin-theme-cd' ),
				'wt_text' => esc_html__( 'Text Widget', 'wp-admin-theme-cd' ),
				'wt_categories' => esc_html__( 'Categories Widget', 'wp-admin-theme-cd' ),
				'wt_recent_posts' => esc_html__( 'Recent Posts Widget', 'wp-admin-theme-cd' ),
				'wt_recent_comments' => esc_html__( 'Recent Comments Widget', 'wp-admin-theme-cd' ),
				'wt_rss' => esc_html__( 'RSS Widget', 'wp-admin-theme-cd' ),
				'wt_tag_cloud' => esc_html__( 'Tag Cloud Widget', 'wp-admin-theme-cd' ),
				'wt_nav' => esc_html__( 'Navigation Menu Widget', 'wp-admin-theme-cd' ),
				'wt_image' => esc_html__( 'Image Widget', 'wp-admin-theme-cd' ),
				'wt_audio' => esc_html__( 'Audio Widget', 'wp-admin-theme-cd' ),
				'wt_video' => esc_html__( 'Video Widget', 'wp-admin-theme-cd' ),
				'wt_gallery' => esc_html__( 'Gallery Widget', 'wp-admin-theme-cd' ),
				'wt_html' => esc_html__( 'Custom HTML Widget', 'wp-admin-theme-cd' ),
				'wp_header_code' => esc_html__( 'Header Code', 'wp-admin-theme-cd' ),
				'wp_footer_code' => esc_html__( 'Footer Code', 'wp-admin-theme-cd' ),
				'meta_referrer_policy' => esc_html__( 'Meta Referrer Policy', 'wp-admin-theme-cd' ),
			);

			$this->plugin_pages_option_fields = array(
				'disable_page_system',
				'disable_page_export',
				'disable_page_ms',      
			);

			$this->optimization_option_fields = array(
				array(
					'wp_version_tag',
					esc_html__( 'Remove the WordPress Version Meta-Tag from wp head.', 'wp-admin-theme-cd' ),
					esc_html__( 'Show the version number of your currently installed WordPress in the source code.', 'wp-admin-theme-cd' ),
				),
				array(
					'wp_emoji',
					esc_html__( 'Remove the WordPress Emoticons from your source code.', 'wp-admin-theme-cd' ),
					esc_html__( 'Display a textual portrayals like ";-)" as a emoticon icon.', 'wp-admin-theme-cd' ),
				),
				array(
					'wp_feed_links',
					esc_html__( 'Disable the RSS feed functionality and remove the WordPress page and comments RSS feed links from wp head.', 'wp-admin-theme-cd' ),
					esc_html__( 'RSS (Really Simple Syndication) is a type of web feed which allows users to access updates to online content in a standardized, computer-readable format.', 'wp-admin-theme-cd' ),
				),
				array(
					'wp_rsd_link',
					esc_html__( 'Remove the RSD link from wp head.', 'wp-admin-theme-cd' ),
					esc_html__( 'Really Simple Discovery (RSD) is an XML format and a publishing convention for making services exposed by a blog, or other web software, discoverable by client software.', 'wp-admin-theme-cd' ),
				),
				array(
					'wp_wlwmanifest',
					esc_html__( 'Remove the Wlwmanifest link from wp head.', 'wp-admin-theme-cd' ),
					esc_html__( 'Needed to enable tagging support for Windows Live Writer.', 'wp-admin-theme-cd' ),
				),
				array(
					'wp_shortlink',
					esc_html__( 'Remove the shortlink link from wp head.', 'wp-admin-theme-cd' ),
					esc_html__( 'Shortlink is a shorten version of a web page’s URL.', 'wp-admin-theme-cd' ),
				),
				array(
					'wp_rest_api',
					esc_html__( 'Disable the REST API and remove the wp json link from wp head.', 'wp-admin-theme-cd' ),
					esc_html__( 'The API makes it super easy to retrieve data using GET requests, which is useful for those building apps with WordPress.', 'wp-admin-theme-cd' ),
				),
				array(
					'wp_oembed',
					esc_html__( 'Disable wp embed and remove the oEmbed links from wp head.', 'wp-admin-theme-cd' ),
					esc_html__( 'oEmbed feature which allows others to embed your WordPress posts into their own site by adding the post URL.', 'wp-admin-theme-cd' ),
				),
				array(
					'wp_xml_rpc',
					esc_html__( 'Disable remote access.', 'wp-admin-theme-cd' ),
					esc_html__( 'XML-RPC is a remote procedure call which uses XML to encode its calls and HTTP as a transport mechanism. If you want to access and publish to your blog remotely, then you need XML-RPC enabled. XML-RPC protocol is used by WordPress as API for Pingbacks and third-party applications, such as mobile apps, inter-blog communication and popular plugins like JetPack.', 'wp-admin-theme-cd' ),
				),
				array(
					'wp_heartbeat',
					esc_html__( 'Stop the heartbeat updates.', 'wp-admin-theme-cd' ),
					esc_html__( 'The Heartbeat API is a simple server polling API built in to WordPress, allowing near-real-time frontend updates. The heartbeat API allows for regular communication between the users browser and the server. One of the original motivations was to allow for locking posts and warning users when more than one user is attempting to edit a post, or warning the user when their log-in has expired.', 'wp-admin-theme-cd' ),
				),
				array(
					'wp_rel_link',
					esc_html__( 'Remove the post rel index / start / parent / prev / next links from wp head.', 'wp-admin-theme-cd' ),
					esc_html__( 'This feature display the URL of the index, start, parent, previous and next post in the source code.', 'wp-admin-theme-cd' ),
				),    
				array(
					'wp_self_pingback',
					esc_html__( 'Disable WordPress self pingbacks / trackbacks.', 'wp-admin-theme-cd' ),
					esc_html__( 'This will allow you to disable self-pingbacks (messages and comments), which are linking back to your own blog.', 'wp-admin-theme-cd' ),
				),     
			);

			$this->meta_box_option_fields = array(
				array(
					'mb_custom_fields',
					esc_html__( 'Remove the Custom Fields Box for posts and pages.', 'wp-admin-theme-cd' ),
					'',
				),
				array(
					'mb_commentstatus',
					esc_html__( 'Remove the Discussion Box for posts and pages.', 'wp-admin-theme-cd' ),
					'',
				),
				array(
					'mb_comments',
					esc_html__( 'Remove the Comments Box for posts and pages.', 'wp-admin-theme-cd' ),
					'',
				),
				array(
					'mb_author',
					esc_html__( 'Remove the Author Box for posts and pages.', 'wp-admin-theme-cd' ),
					'',
				),
				array(
					'mb_category',
					esc_html__( 'Remove the Category Box for posts.', 'wp-admin-theme-cd' ),
					'',
				),
				array(
					'mb_format',
					esc_html__( 'Remove the Post Format Box for posts.', 'wp-admin-theme-cd' ),
					'',
				),
				array(
					'mb_pageparent',
					esc_html__( 'Remove the Page Attributes Box for pages.', 'wp-admin-theme-cd' ),
					'',
				),
				array(
					'mb_postexcerpt',
					esc_html__( 'Remove the Excerpt Box for posts.', 'wp-admin-theme-cd' ),
					'',
				),
				array(
					'mb_postimage',
					esc_html__( 'Remove the Featured Image Box for posts and pages.', 'wp-admin-theme-cd' ),
					'',
				),
				array(
					'mb_revisions',
					esc_html__( 'Remove the Revisions Box for posts and pages.', 'wp-admin-theme-cd' ),
					'',
				),
				array(
					'mb_slug',
					esc_html__( 'Remove the Slug Box for posts and pages.', 'wp-admin-theme-cd' ),
					esc_html__( 'Caution: Disabling the slug box does not allow you to customize the post or page URL.', 'wp-admin-theme-cd' ),
				),
				array(
					'mb_tags',
					esc_html__( 'Remove the Tags Box for posts.', 'wp-admin-theme-cd' ),
					'',
				),
				array(
					'mb_trackbacks',
					esc_html__( 'Remove the Send Trackbacks Box for posts and pages.', 'wp-admin-theme-cd' ),
					'',
				),            
			);

			$this->db_widget_option_fields = array(
				'dbw_quick_press',
				'dbw_right_now',
				'dbw_activity',
				'dbw_primary',
				'dbw_welcome', 
				'dbw_wpat_user_log', 
				'dbw_wpat_sys_info',
				'dbw_wpat_count_post',
				'dbw_wpat_count_page',
				'dbw_wpat_count_comment',
				'dbw_wpat_recent_post',
				'dbw_wpat_recent_page',
				'dbw_wpat_recent_comment',
				'dbw_wpat_memory',
			);

			$this->widget_option_fields = array(
				'wt_pages',
				'wt_calendar',
				'wt_archives',
				'wt_meta',
				'wt_search',
				'wt_text',
				'wt_categories',
				'wt_recent_posts',
				'wt_recent_comments',
				'wt_rss',
				'wt_tag_cloud',
				'wt_nav',
				'wt_image',
				'wt_audio',
				'wt_video',
				'wt_gallery',
				'wt_html',
			);

			$this->frontend_option_fields = array(
				array(
					'wp_header_code',
					esc_html__( 'Add custom code to the frontend header.', 'wp-admin-theme-cd' ),
					esc_html__( 'Will be inserted into the wp_head hook.', 'wp-admin-theme-cd' ),
				),
				array(
					'wp_footer_code',
					esc_html__( 'Add custom code to the frontend footer.', 'wp-admin-theme-cd' ),
					esc_html__( 'Will be inserted into the wp_footer hook.', 'wp-admin-theme-cd' ),
				),
				array(
					'meta_referrer_policy',
					esc_html__( 'Add the meta referrer tag and select your value.', 'wp-admin-theme-cd' ),
					esc_html__( 'If you use SSL for your website, analytics tools like Google Analytics can not see the referrer by default. For example, if you select "Origin", your referrer will be visible again.', 'wp-admin-theme-cd' ),
				),        
			);

			$this->option_heads = array(
				'head_theme' => esc_html__( 'Theme Design', 'wp-admin-theme-cd' ),
				'head_toolbar' => esc_html__( 'Toolbar', 'wp-admin-theme-cd' ),
				'head_login' => esc_html__( 'Login Page', 'wp-admin-theme-cd' ),
				'head_footer' => esc_html__( 'Footer', 'wp-admin-theme-cd' ),
				'head_media' => esc_html__( 'Media', 'wp-admin-theme-cd' ),
				'head_pages' => esc_html__( 'Pages', 'wp-admin-theme-cd' ),
				'head_ms' => esc_html__( 'Multisite', 'wp-admin-theme-cd' ),
				'head_optimize' => esc_html__( 'Optimization & Security', 'wp-admin-theme-cd' ),
				'head_metabox' => esc_html__( 'Meta Boxes', 'wp-admin-theme-cd' ),
				'head_dashboard' => esc_html__( 'Dashboard Widgets', 'wp-admin-theme-cd' ),
				'head_widget' => esc_html__( 'Widgets', 'wp-admin-theme-cd' ),
				'head_frontend' => esc_html__( 'Frontend', 'wp-admin-theme-cd' ),
			);

			// Exception fields are not restorable
			$css_admin = isset( $this->options['css_admin'] ) ? $this->options['css_admin'] : null;
			$css_login = isset( $this->options['css_login'] ) ? $this->options['css_login'] : null;
			$wp_header_code = isset( $this->options['wp_header_code'] ) ? $this->options['wp_header_code'] : null;
			$wp_footer_code = isset( $this->options['wp_footer_code'] ) ? $this->options['wp_footer_code'] : null;

			// Define pre option values (used for initial plugin load and restore options)
			$this->pre_options = array(
				'user_box' => false,
				'company_box' => false,
				'company_box_logo' => '',
				'company_box_logo_size' => '140',
				'thumbnail' => false,
				'post_page_id' => false,
				'hide_help' => false,
				'hide_screen_option' => false,
				'left_menu_width' => '160',
				'left_menu_expand' => false,
				'spacing' => false,
				'spacing_max_width' => '2000',
				'credits' => false,
				'google_webfont' => false,
				'google_webfont_weight' => false,
				'toolbar' => false,
				'hide_adminbar_comments' => false,
				'hide_adminbar_new' => false,
				'hide_adminbar_customize' => false,
				'hide_adminbar_search' => false,
				'toolbar_wp_icon' => false,
				'toolbar_icon' => '',
				'toolbar_color' => '#32373c',
				'theme_color' => '#4777CD',
				'theme_background' => '#545c63',
				'theme_background_end' => '#32373c',
				'login_disable' => false,
				'login_title' => esc_html__( 'Welcome Back.', 'wp-admin-theme-cd' ),
				'logo_upload' => '',
				'logo_size' => '200',
				'login_bg' => '',
				'memory_usage' => false,
				'memory_limit' => false,
				'memory_available' => false,
				'php_version' => false,
				'ip_address' => false,
				'wp_version' => false,
				'css_admin' => esc_html( $css_admin ),
				'css_login' => esc_html( $css_login ),
				'wp_svg' => false,
				'wp_ico' => false,
				'disable_page_system' => false,
				'disable_page_export' => false,
				'disable_page_ms' => false,
				'disable_theme_options' => false,
				'wp_version_tag' => false,
				'wp_emoji' => false,
				'wp_feed_links' => false,
				'wp_rsd_link' => false,
				'wp_wlwmanifest' => false,
				'wp_shortlink' => false,
				'wp_rest_api' => false,
				'wp_oembed' => false,
				'wp_xml_rpc' => false,
				'wp_heartbeat' => false,
				'wp_rel_link' => false,
				'wp_self_pingback' => false,
				'mb_custom_fields' => false,
				'mb_commentstatus' => false,
				'mb_comments' => false,
				'mb_author' => false,
				'mb_category' => false,
				'mb_format' => false,
				'mb_pageparent' => false,
				'mb_postexcerpt' => false,
				'mb_postimage' => false,
				'mb_revisions' => false,
				'mb_slug' => false,
				'mb_tags' => false,
				'mb_trackbacks' => false,
				'dbw_quick_press' => false,
				'dbw_right_now' => false,
				'dbw_activity' => false,
				'dbw_primary' => false,
				'dbw_welcome' => false,
				'dbw_wpat_user_log' => false,
				'dbw_wpat_sys_info' => false,
				'dbw_wpat_count_post' => false,
				'dbw_wpat_count_page' => false,
				'dbw_wpat_count_comment' => false,
				'dbw_wpat_recent_post' => false,
				'dbw_wpat_recent_page' => false,
				'dbw_wpat_recent_comment' => false,
				'dbw_wpat_memory' => false,
				'wt_pages' => false,
				'wt_calendar' => false,
				'wt_archives' => false,
				'wt_meta' => false,
				'wt_search' => false,
				'wt_text' => false,
				'wt_categories' => false,
				'wt_recent_posts' => false,
				'wt_recent_comments' => false,
				'wt_rss' => false,
				'wt_tag_cloud' => false,
				'wt_nav' => false,
				'wt_image' => false,
				'wt_audio' => false,
				'wt_video' => false,
				'wt_gallery' => false,
				'wt_html' => false,
				'wp_header_code' => esc_html( $wp_header_code ),
				'wp_footer_code' => esc_html( $wp_footer_code ),
				'meta_referrer_policy' => 'none',
			);

			// Get registered options
			$this->options = get_option( 'wp_admin_theme_settings_options' );		
			if( is_multisite() ) {
				$this->options = get_blog_option( get_current_blog_id(), 'wp_admin_theme_settings_options', array() );
			}		
			
			// Check if options not exist
			if( ! $this->options ) {				
				// Set initial options to the index
				if( is_multisite() ) {
					update_blog_option( get_current_blog_id(), 'wp_admin_theme_settings_options', $this->pre_options);
				} else {
					update_option( 'wp_admin_theme_settings_options', $this->pre_options, 'yes' );
				}
			}

		}
		

		/*****************************************************************/
		/* ADD PLUGIN OPTIONS PAGE */
		/*****************************************************************/

		public function wp_admin_theme_cd_add_page() {

			// $page_title, $menu_title, $capability, $menu_slug, $callback_function
			add_submenu_page( 
				'tools.php', esc_html__( 'WP Admin Theme', 'wp-admin-theme-cd' ), esc_html__( 'WP Admin Theme', 'wp-admin-theme-cd' ), 'manage_options', 'wp-admin-theme-cd', array( $this, 'wp_admin_theme_cd_display_page' ) 
			);

		}


		/*****************************************************************/
		/* REGISTER PLUGIN SETTINGS/OPTIONS */
		/*****************************************************************/

		public function wp_admin_theme_cd_register_settings() {

			// option group, option name, sanitize
			register_setting( 
				'wp_admin_theme_settings_options', 'wp_admin_theme_settings_options', array( $this, 'wp_admin_theme_cd_validate_options' ) 
			);

		}


		/*****************************************************************/
		/* DISPLAY PLUGIN OPTIONS PAGE */
		/*****************************************************************/

		public function wp_admin_theme_cd_display_page() { ?>

			<div class="wrap wpat">

				<h1>
					<?php echo wp_admin_theme_cd_title(); ?>
				</h1> 
				
				<?php if( wp_admin_theme_cd_activation_status() ) {
				
					// Output the tab menu
					$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display-options'; 
					echo wp_admin_theme_cd_tab_menu( $active_tab ); ?>

					<p>
						<?php esc_html_e( 'Speed up and modify your WordPress backend like a charm. This plugin is the central place to take WordPress design to the next level.', 'wp-admin-theme-cd' ); ?>
					</p>

					<div class="wpat-page-menu">
						<ul<?php if( is_rtl() ) { echo ' dir="rtl"'; } ?>>
							<li><a href="#index_theme"><?php echo $this->option_heads['head_theme']; ?></a></li>
							<li><a href="#index_toolbar"><?php echo $this->option_heads['head_toolbar']; ?></a></li>
							<li><a href="#index_footer"><?php echo $this->option_heads['head_footer']; ?></a></li>
							<li><a href="#index_login"><?php echo $this->option_heads['head_login']; ?></a></li>
							<li><a href="#index_media"><?php echo $this->option_heads['head_media']; ?></a></li>
							<li><a href="#index_page"><?php echo $this->option_heads['head_pages']; ?></a></li>
							<li><a href="#index_metabox"><?php echo $this->option_heads['head_metabox']; ?></a></li>
							<li><a href="#index_dashboard"><?php echo $this->option_heads['head_dashboard']; ?></a></li>
							<li><a href="#index_widget"><?php echo $this->option_heads['head_widget']; ?></a></li>
							<li><a href="#index_frontend"><?php echo $this->option_heads['head_frontend']; ?></a></li>
							<li><a href="#index_optimize"><?php echo $this->option_heads['head_optimize']; ?></a></li>
							<li><a href="#index_ms"><?php echo $this->option_heads['head_ms']; ?></a></li>
						</ul>
					</div>

					<form action="options.php" method="post" enctype="multipart/form-data">

						<?php $options = get_option( 'wp_admin_theme_settings_options' );
						if( is_multisite() ) {
							$main_blog_id = 1;
							$options = get_blog_option( $main_blog_id, 'wp_admin_theme_settings_options', array() );
						}

						/*
						// print options check
						echo '<pre>';
							print_r($this->options);
						echo '</pre>';
						*/		
			
						// Error message output			
						settings_errors('wp_admin_theme_settings_options');

						// Fields output				
						settings_fields('wp_admin_theme_settings_options');
						do_settings_sections('wp_admin_theme_settings_options'); ?>

						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"></th>
									<td>
										<p class="description">

											<?php $disable_theme_options = isset( $options['disable_theme_options'] ) ? $options['disable_theme_options'] : $this->pre_options['disable_theme_options'];
			
											$access = true;
											if( $disable_theme_options ) { // <-- Only the option of the blog ID 1 is essential here
												$access = ( get_current_blog_id() == 1 );
											}
			
											// Manage save button visibility								
											if( $access ) {
												submit_button( esc_html__( 'Save Changes', 'wp-admin-theme-cd' ), 'button button-primary', 'save', false );
											} else { ?>
												<button class="button" disabled>
													<?php echo esc_html__( 'You have no permissions to change this options!', 'wp-admin-theme-cd' ); ?>
												</button>
											<?php }

											// Manage restore button visibility
											if( $access ) {
												submit_button( esc_html__( 'Restore all', 'wp-admin-theme-cd' ), 'button restore', 'reset', false ); 
											} ?>

										</p>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				
				<?php } else {
					echo wp_admin_theme_cd_plugin_activation_message();
				} ?>

			</div>

		<?php }


		/*****************************************************************/
		/* REGISTER PLUGIN ADMIN PAGE OPTIONS */
		/*****************************************************************/

		public function wp_admin_theme_cd_register_page_options() {

			/**********************************/
			// Add Section for option fields
			/**********************************/
			
			add_settings_section( 
				'admin_theme_section', '<span id="index_theme" class="wpat-page-index"></span>' . $this->option_heads['head_theme'], array( $this, 'wp_admin_theme_cd_display_section' ), 'wp_admin_theme_settings_options' 
			);

				add_settings_field( 'admin_theme_color',                    $this->option_fields['theme_color'],                    array( $this, 'admin_theme_color_settings' ),                   'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add custom Theme Color Field            
				add_settings_field( 'admin_theme_background',               esc_html__( 'Background Gradient Color', 'wp-admin-theme-cd' ), array( $this, 'admin_theme_background_settings' ),      'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add custom Theme Background Gradient Color Field
				add_settings_field( 'admin_theme_spacing',                  $this->option_fields['spacing'],                        array( $this, 'admin_theme_spacing_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add Spacing Option
				add_settings_field( 'admin_theme_user_box',                 $this->option_fields['user_box'],                       array( $this, 'admin_theme_user_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add User Box Option
				add_settings_field( 'admin_theme_company_box',              $this->option_fields['company_box'],                    array( $this, 'admin_theme_company_box_settings' ),             'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add Company Box Option
				add_settings_field( 'admin_theme_left_menu_width',       	$this->option_fields['left_menu_width'],             	array( $this, 'admin_theme_left_menu_width_settings' ),      	'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add Left Menu Width Option
				add_settings_field( 'admin_theme_left_menu_expand',       	$this->option_fields['left_menu_expand'],             	array( $this, 'admin_theme_left_menu_expand_settings' ),      	'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add Left expandable Menu Option
				add_settings_field( 'admin_theme_google_webfont',           $this->option_fields['google_webfont'],                 array( $this, 'admin_theme_google_webfont_settings' ),          'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add Google Webfont Option
				add_settings_field( 'admin_theme_thumbnail',                $this->option_fields['thumbnail'],                      array( $this, 'admin_theme_thumbnail_settings' ),               'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add Thumbnail Option
				add_settings_field( 'admin_theme_post_page_id',             $this->option_fields['post_page_id'],                   array( $this, 'admin_theme_post_page_id_settings' ),            'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add Post/Page ID Option
				add_settings_field( 'admin_theme_hide_help',                $this->option_fields['hide_help'],                      array( $this, 'admin_theme_hide_help_settings' ),               'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add Hide the Contextual Help Option
				add_settings_field( 'admin_theme_hide_screen_option',       $this->option_fields['hide_screen_option'],             array( $this, 'admin_theme_hide_screen_option_settings' ),      'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add Hide the Screen Options
				add_settings_field( 'admin_theme_css_admin',                $this->option_fields['css_admin'],                      array( $this, 'admin_theme_css_admin_settings' ),               'wp_admin_theme_settings_options', 'admin_theme_section' ); // Add Custom CSS for WP Admin Theme

			/**********************************/
			// Add Section for Toolbar
			/**********************************/
			
			add_settings_section( 
				'admin_theme_section_toolbar', '<span id="index_toolbar" class="wpat-page-index"></span>' . $this->option_heads['head_toolbar'], array( $this, 'wp_admin_theme_cd_display_section_toolbar' ), 'wp_admin_theme_settings_options' 
			);		

				add_settings_field( 'admin_theme_toolbar',                  $this->option_fields['toolbar'],                        array( $this, 'admin_theme_toolbar_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_toolbar' ); // Add Hide Toolbar Option    
				add_settings_field( 'admin_toolbar_color',                  $this->option_fields['toolbar_color'],                  array( $this, 'admin_toolbar_color_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_toolbar' ); // Add custom Toolbar Color Field   
				add_settings_field( 'admin_theme_hide_adminbar_comments',   $this->option_fields['hide_adminbar_comments'],         array( $this, 'admin_theme_hide_adminbar_comments_settings' ),  'wp_admin_theme_settings_options', 'admin_theme_section_toolbar' ); // Add Hide Toolbar Comments Menu            
				add_settings_field( 'admin_theme_hide_adminbar_new',        $this->option_fields['hide_adminbar_new'],              array( $this, 'admin_theme_hide_adminbar_new_settings' ),       'wp_admin_theme_settings_options', 'admin_theme_section_toolbar' ); // Add Hide Toolbar New Content Menu         
				add_settings_field( 'admin_theme_hide_adminbar_customize',  $this->option_fields['hide_adminbar_customize'],        array( $this, 'admin_theme_hide_adminbar_customize_settings' ), 'wp_admin_theme_settings_options', 'admin_theme_section_toolbar' ); // Add Hide Toolbar Customize Link          
				add_settings_field( 'admin_theme_hide_adminbar_search',     $this->option_fields['hide_adminbar_search'],           array( $this, 'admin_theme_hide_adminbar_search_settings' ),    'wp_admin_theme_settings_options', 'admin_theme_section_toolbar' ); // Add Hide Toolbar Search   
				add_settings_field( 'admin_theme_toolbar_wp_icon',          $this->option_fields['toolbar_wp_icon'],                array( $this, 'admin_theme_toolbar_wp_icon_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_toolbar' ); // Add Hide Toolbar WP Icon          
				add_settings_field( 'admin_theme_toolbar_icon',             $this->option_fields['toolbar_icon'],                   array( $this, 'admin_theme_toolbar_icon_settings' ),            'wp_admin_theme_settings_options', 'admin_theme_section_toolbar' ); // Add custom Toolbar Icon 

			/**********************************/
			// Add Section for Footer Information Option
			/**********************************/
			
			add_settings_section( 
				'admin_theme_section_footer', '<span id="index_footer" class="wpat-page-index"></span>' . $this->option_heads['head_footer'], array( $this, 'wp_admin_theme_cd_display_section_footer' ), 'wp_admin_theme_settings_options' 
			);		

				add_settings_field( 'admin_theme_credits',                  $this->option_fields['credits'],                        array( $this, 'admin_theme_credits_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_footer' ); // Add Credits Option
				add_settings_field( 'admin_theme_memory_usage',             $this->option_fields['memory_usage'],                   array( $this, 'admin_theme_memory_usage_settings' ),            'wp_admin_theme_settings_options', 'admin_theme_section_footer' ); // Add Memory Usage Option            
				add_settings_field( 'admin_theme_memory_limit',             $this->option_fields['memory_limit'],                   array( $this, 'admin_theme_memory_limit_settings' ),            'wp_admin_theme_settings_options', 'admin_theme_section_footer' ); // Add WP Memory Limit Option         
				add_settings_field( 'admin_theme_memory_available',         $this->option_fields['memory_available'],               array( $this, 'admin_theme_memory_available_settings' ),        'wp_admin_theme_settings_options', 'admin_theme_section_footer' ); // Add Memory Available Option             
				add_settings_field( 'admin_theme_php_version',              $this->option_fields['php_version'],                    array( $this, 'admin_theme_php_version_settings' ),             'wp_admin_theme_settings_options', 'admin_theme_section_footer' ); // Add PHP Version Option            
				add_settings_field( 'admin_theme_ip_address',               $this->option_fields['ip_address'],                     array( $this, 'admin_theme_ip_address_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_footer' ); // Add IP Address Option            
				add_settings_field( 'admin_theme_wp_version',               $this->option_fields['wp_version'],                     array( $this, 'admin_theme_wp_version_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_footer' ); // Add WP Version Option
			
			/**********************************/
			// Add Section for Login Option
			/**********************************/
			
			add_settings_section( 
				'admin_theme_section_login', '<span id="index_login" class="wpat-page-index"></span>' . $this->option_heads['head_login'], array( $this, 'wp_admin_theme_cd_display_section_login' ), 'wp_admin_theme_settings_options' 
			);

				add_settings_field( 'admin_theme_login_disable',            $this->option_fields['login_disable'],                  array( $this, 'admin_theme_login_disable_settings' ),           'wp_admin_theme_settings_options', 'admin_theme_section_login' ); // Add Login Disable Option            
				add_settings_field( 'admin_theme_login_title',              $this->option_fields['login_title'],                    array( $this, 'admin_theme_login_title_settings' ),             'wp_admin_theme_settings_options', 'admin_theme_section_login' ); // Add Title Field            
				add_settings_field( 'admin_theme_logo_upload',              $this->option_fields['logo_upload'],                    array( $this, 'admin_theme_logo_upload_settings' ),             'wp_admin_theme_settings_options', 'admin_theme_section_login' ); // Add Logo Option            
				add_settings_field( 'admin_theme_login_bg',                 $this->option_fields['login_bg'],                       array( $this, 'admin_theme_login_bg_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_login' ); // Add Login BG Image Option         
				add_settings_field( 'admin_theme_css_login',                $this->option_fields['css_login'],                      array( $this, 'admin_theme_css_login_settings' ),               'wp_admin_theme_settings_options', 'admin_theme_section_login' ); // Add Custom CSS for WP Login

			/**********************************/
			// Add Section for Media Support
			/**********************************/
			
			add_settings_section( 
				'admin_theme_section_media', '<span id="index_media" class="wpat-page-index"></span>' . $this->option_heads['head_media'], array( $this, 'wp_admin_theme_cd_display_section_media' ), 'wp_admin_theme_settings_options' 
			);		

				add_settings_field( 'admin_theme_wp_svg',                   $this->option_fields['wp_svg'],                         array( $this, 'admin_theme_wp_svg_settings' ),                  'wp_admin_theme_settings_options', 'admin_theme_section_media' ); // Add SVG Support            
				add_settings_field( 'admin_theme_wp_ico',                   $this->option_fields['wp_ico'],                         array( $this, 'admin_theme_wp_ico_settings' ),                  'wp_admin_theme_settings_options', 'admin_theme_section_media' ); // Add ICO Support

			/**********************************/
			// Add Section for Plugin Pages
			/**********************************/
			
			add_settings_section( 
				'admin_theme_section_plugin_pages', '<span id="index_page" class="wpat-page-index"></span>' . $this->option_heads['head_pages'], array( $this, 'wp_admin_theme_cd_display_section_plugin_pages' ), 'wp_admin_theme_settings_options' 
			);		

				add_settings_field( 'admin_theme_disable_page_system',      $this->option_fields['disable_page_system'],            array( $this, 'admin_theme_disable_plugin_pages_settings' ),    'wp_admin_theme_settings_options', 'admin_theme_section_plugin_pages' ); // Add Disable Plugin System Page
				add_settings_field( 'admin_theme_disable_page_export',      $this->option_fields['disable_page_export'],            array( $this, 'admin_theme_disable_plugin_pages_settings' ),    'wp_admin_theme_settings_options', 'admin_theme_section_plugin_pages' ); // Add Disable Plugin Im-/Export Page
				add_settings_field( 'admin_theme_disable_page_ms',          $this->option_fields['disable_page_ms'],                array( $this, 'admin_theme_disable_plugin_pages_settings' ),    'wp_admin_theme_settings_options', 'admin_theme_section_plugin_pages' ); // Add Disable Plugin Multisite Sync Page

			/**********************************/
			// Add Section for Meta Boxes
			/**********************************/
			
			add_settings_section( 
				'admin_theme_section_meta_boxes', '<span id="index_metabox" class="wpat-page-index"></span>' . $this->option_heads['head_metabox'], array( $this, 'wp_admin_theme_cd_display_section_meta_boxes' ), 'wp_admin_theme_settings_options' 
			);		

				add_settings_field( 'admin_theme_mb_custom_fields',         $this->option_fields['mb_custom_fields'],               array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Custom Field Meta Box
				add_settings_field( 'admin_theme_mb_commentstatus',         $this->option_fields['mb_commentstatus'],               array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Comments Status Meta Box
				add_settings_field( 'admin_theme_mb_comments',              $this->option_fields['mb_comments'],                    array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Comments Meta Box
				add_settings_field( 'admin_theme_mb_author',                $this->option_fields['mb_author'],                      array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Author Meta Box
				add_settings_field( 'admin_theme_mb_category',              $this->option_fields['mb_category'],                    array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Category Meta Box
				add_settings_field( 'admin_theme_mb_format',                $this->option_fields['mb_format'],                      array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Post Format Meta Box
				add_settings_field( 'admin_theme_mb_pageparent',            $this->option_fields['mb_pageparent'],                  array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Page Parent Meta Box
				add_settings_field( 'admin_theme_mb_postexcerpt',           $this->option_fields['mb_postexcerpt'],                 array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Post Excerpt Meta Box
				add_settings_field( 'admin_theme_mb_postimage',             $this->option_fields['mb_postimage'],                   array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Post Image Meta Box
				add_settings_field( 'admin_theme_mb_revisions',             $this->option_fields['mb_revisions'],                   array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Revisions Meta Box
				add_settings_field( 'admin_theme_mb_slug',                  $this->option_fields['mb_slug'],                        array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Slug Meta Box
				add_settings_field( 'admin_theme_mb_tags',                  $this->option_fields['mb_tags'],                        array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Tags Meta Box
				add_settings_field( 'admin_theme_mb_trackbacks',            $this->option_fields['mb_trackbacks'],                  array( $this, 'admin_theme_meta_box_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_meta_boxes' ); // Add Remove Trackbacks Meta Box

			/**********************************/
			// Add Section for Dashboard Widgets
			/**********************************/
			
			add_settings_section( 
				'admin_theme_section_db_widgets', '<span id="index_dashboard" class="wpat-page-index"></span>' . $this->option_heads['head_dashboard'], array( $this, 'wp_admin_theme_cd_display_section_db_widgets' ), 'wp_admin_theme_settings_options' 
			);	

				add_settings_field( 'admin_theme_dbw_quick_press',          $this->option_fields['dbw_quick_press'],                array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove Qick Draft Widget
				add_settings_field( 'admin_theme_dbw_right_now',            $this->option_fields['dbw_right_now'],                  array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove At the Glance Widget
				add_settings_field( 'admin_theme_dbw_activity',             $this->option_fields['dbw_activity'],                   array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove Activity Widget
				add_settings_field( 'admin_theme_dbw_primary',              $this->option_fields['dbw_primary'],                    array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove WP Events & News Widget
				add_settings_field( 'admin_theme_dbw_welcome',              $this->option_fields['dbw_welcome'],                    array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove Welcome Widget
				add_settings_field( 'admin_theme_dbw_wpat_user_log',        $this->option_fields['dbw_wpat_user_log'],              array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove WPAT User Activities Widget
				add_settings_field( 'admin_theme_dbw_wpat_sys_info',        $this->option_fields['dbw_wpat_sys_info'],              array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove WPAT System info Widget
				add_settings_field( 'admin_theme_dbw_wpat_count_post',      $this->option_fields['dbw_wpat_count_post'],            array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove WPAT Posts Count Widget
				add_settings_field( 'admin_theme_dbw_wpat_count_page',      $this->option_fields['dbw_wpat_count_page'],            array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove WPAT Pages Count Widget
				add_settings_field( 'admin_theme_dbw_wpat_count_comment',   $this->option_fields['dbw_wpat_count_comment'],         array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove WPAT Comments Count Widget
				add_settings_field( 'admin_theme_dbw_wpat_recent_post',     $this->option_fields['dbw_wpat_recent_post'],           array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove WPAT Recent Posts Widget
				add_settings_field( 'admin_theme_dbw_wpat_recent_page',     $this->option_fields['dbw_wpat_recent_page'],           array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove WPAT Recent Pages Widget
				add_settings_field( 'admin_theme_dbw_wpat_recent_comment',  $this->option_fields['dbw_wpat_recent_comment'],        array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove WPAT Recent Comments Widget
				add_settings_field( 'admin_theme_dbw_wpat_memory',          $this->option_fields['dbw_wpat_memory'],                array( $this, 'admin_theme_db_widgets_settings' ),              'wp_admin_theme_settings_options', 'admin_theme_section_db_widgets' ); // Add Remove WPAT Memory Usage Widget

			/**********************************/
			// Add Section for Widgets
			/**********************************/
			
			add_settings_section( 
				'admin_theme_section_widgets', '<span id="index_widget" class="wpat-page-index"></span>' . $this->option_heads['head_widget'], array( $this, 'wp_admin_theme_cd_display_section_widgets' ), 'wp_admin_theme_settings_options' 
			);	

				add_settings_field( 'admin_theme_wt_pages',                 $this->option_fields['wt_pages'],                       array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Pages Widget
				add_settings_field( 'admin_theme_wt_archives',              $this->option_fields['wt_archives'],                    array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Calendar Widget
				add_settings_field( 'admin_theme_wt_calendar',              $this->option_fields['wt_calendar'],                    array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Archives Widget
				add_settings_field( 'admin_theme_wt_meta',                  $this->option_fields['wt_meta'],                        array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Meta Widget
				add_settings_field( 'admin_theme_wt_search',                $this->option_fields['wt_search'],                      array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Search Widget
				add_settings_field( 'admin_theme_wt_text',                  $this->option_fields['wt_text'],                        array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Text Widget
				add_settings_field( 'admin_theme_wt_categories',            $this->option_fields['wt_categories'],                  array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Categories Widget
				add_settings_field( 'admin_theme_wt_recent_posts',          $this->option_fields['wt_recent_posts'],                array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Recent Posts Widget
				add_settings_field( 'admin_theme_wt_recent_comments',       $this->option_fields['wt_recent_comments'],             array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Recent Comments Widget
				add_settings_field( 'admin_theme_wt_rss',                   $this->option_fields['wt_rss'],                         array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove RSS Widget
				add_settings_field( 'admin_theme_wt_tag_cloud',             $this->option_fields['wt_tag_cloud'],                   array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Tag Cloud Widget
				add_settings_field( 'admin_theme_wt_nav',                   $this->option_fields['wt_nav'],                         array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Navigation Menu Widget
				add_settings_field( 'admin_theme_wt_image',                 $this->option_fields['wt_image'],                       array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Image Widget
				add_settings_field( 'admin_theme_wt_audio',                 $this->option_fields['wt_audio'],                       array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Audio Widget
				add_settings_field( 'admin_theme_wt_video',                 $this->option_fields['wt_video'],                       array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Video Widget
				add_settings_field( 'admin_theme_wt_gallery',               $this->option_fields['wt_gallery'],                     array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Gallery Widget
				add_settings_field( 'admin_theme_wt_html',                  $this->option_fields['wt_html'],                        array( $this, 'admin_theme_widgets_settings' ),                 'wp_admin_theme_settings_options', 'admin_theme_section_widgets' ); // Add Remove Custom HTML Widget
			
			/**********************************/
			// Add Section for Frontend
			/**********************************/
			
			add_settings_section( 
				'admin_theme_section_frontend', '<span id="index_frontend" class="wpat-page-index"></span>' . $this->option_heads['head_frontend'], array( $this, 'wp_admin_theme_cd_display_section_frontend' ), 'wp_admin_theme_settings_options' 
			);	

				add_settings_field( 'admin_theme_wp_header_code',           $this->option_fields['wp_header_code'],                 array( $this, 'admin_theme_frontend_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_frontend' ); // Add Header Code
				add_settings_field( 'admin_theme_wp_footer_code',           $this->option_fields['wp_footer_code'],                 array( $this, 'admin_theme_frontend_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_frontend' ); // Add Footer Code
				add_settings_field( 'admin_theme_meta_referrer_policy',     $this->option_fields['meta_referrer_policy'],           array( $this, 'admin_theme_frontend_settings' ),                'wp_admin_theme_settings_options', 'admin_theme_section_frontend' ); // Add Meta Policy

			/**********************************/
			// Add Section for Optimization
			/**********************************/
			
			add_settings_section( 
				'admin_theme_section_optimization', '<span id="index_optimize" class="wpat-page-index"></span>' . $this->option_heads['head_optimize'], array( $this, 'wp_admin_theme_cd_display_section_optimization' ), 'wp_admin_theme_settings_options' 
			);		

				add_settings_field( 'admin_theme_wp_version_tag',           $this->option_fields['wp_version_tag'],                 array( $this, 'admin_theme_wp_optimization_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_optimization' ); // Add Remove WP Version Tag            
				add_settings_field( 'admin_theme_wp_emoji',                 $this->option_fields['wp_emoji'],                       array( $this, 'admin_theme_wp_optimization_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_optimization' ); // Add Remove WP Emoticons            
				add_settings_field( 'admin_theme_wp_feed_links',            $this->option_fields['wp_feed_links'],                  array( $this, 'admin_theme_wp_optimization_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_optimization' ); // Add Remove WP Feed Links            
				add_settings_field( 'admin_theme_wp_rsd_link',              $this->option_fields['wp_rsd_link'],                    array( $this, 'admin_theme_wp_optimization_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_optimization' ); // Add Remove WP RSD Link            
				add_settings_field( 'admin_theme_wp_wlwmanifest',           $this->option_fields['wp_wlwmanifest'],                 array( $this, 'admin_theme_wp_optimization_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_optimization' ); // Add Remove WP Wlwmanifest            
				add_settings_field( 'admin_theme_wp_shortlink',             $this->option_fields['wp_shortlink'],                   array( $this, 'admin_theme_wp_optimization_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_optimization' ); // Add Remove WP Shortlink            
				add_settings_field( 'admin_theme_wp_rest_api',              $this->option_fields['wp_rest_api'],                    array( $this, 'admin_theme_wp_optimization_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_optimization' ); // Add Remove WP Rest API            
				add_settings_field( 'admin_theme_wp_oembed',                $this->option_fields['wp_oembed'],                      array( $this, 'admin_theme_wp_optimization_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_optimization' ); // Add Remove WP oEmbed            
				add_settings_field( 'admin_theme_wp_xml_rpc',               $this->option_fields['wp_xml_rpc'],                     array( $this, 'admin_theme_wp_optimization_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_optimization' ); // Add Remove WP XML RPC            
				add_settings_field( 'admin_theme_wp_heartbeat',             $this->option_fields['wp_heartbeat'],                   array( $this, 'admin_theme_wp_optimization_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_optimization' ); // Add Remove WP Heartbeat            
				add_settings_field( 'admin_theme_wp_rel_link',              $this->option_fields['wp_rel_link'],                    array( $this, 'admin_theme_wp_optimization_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_optimization' ); // Add Remove WP Rel Link          
				add_settings_field( 'admin_theme_wp_self_pingback',         $this->option_fields['wp_self_pingback'],               array( $this, 'admin_theme_wp_optimization_settings' ),         'wp_admin_theme_settings_options', 'admin_theme_section_optimization' ); // Add Disable Self Pingbacks Link

			/**********************************/
			// Add Section for Multisite Support
			/**********************************/
			
			add_settings_section( 
				'admin_theme_section_multisite', '<span id="index_ms" class="wpat-page-index"></span>' . $this->option_heads['head_ms'], array( $this, 'wp_admin_theme_cd_display_section_multisite' ), 'wp_admin_theme_settings_options' 
			);		

				add_settings_field( 'admin_theme_disable_theme_options',    $this->option_fields['disable_theme_options'],          array( $this, 'admin_theme_disable_theme_options_settings' ),   'wp_admin_theme_settings_options', 'admin_theme_section_multisite' ); // Add Disable Theme Options

		}


		/*****************************************************************/
		/* ADD JS ONLY FOR PLUGIN OPTIONS PAGE */
		/*****************************************************************/

		function wp_admin_theme_cd_load_plugin_page_specific_scripts( $hook ) {

			// Method to get the page hook
			// wp_die($hook);

			// Load only on admin_toplevel_page?page=mypluginname
			if( $hook != 'tools_page_wp-admin-theme-cd' ) {
				return;
			}

			// Add color picker css
			wp_enqueue_style( 'wp-color-picker' );

			// Add media upload js
			wp_enqueue_media();

			// Add plugin js		
			wp_enqueue_script( 
				'wp_admin_script_plugin', wp_admin_theme_cd_path( 'js/jquery.plugin.js' ), array( 'jquery', 'wp-color-picker' ), null, true 
			);

		}


		/*****************************************************************/
		/* ADD GLOBAL JS / CSS */
		/*****************************************************************/

		public function wp_admin_theme_cd_enqueue_admin_js() {

			// Add admin main style
			wp_enqueue_style( 
				'wp_admin_style_custom', wp_admin_theme_cd_path( 'style.css' ), array(), filemtime( wp_admin_theme_cd_dir( 'style.css' ) ), 'all' 
			);

			// Add admin style less
			wp_enqueue_style( 
				'wpat-style-less',  wp_admin_theme_cd_path( 'css/less/style.less' ), array(), null, 'all'
			);

			// Add admin plugin style less
			wp_enqueue_style( 
				'wpat-plugins-less',  wp_admin_theme_cd_path( 'css/less/plugins.less' ), array(), null, 'all'
			);

			// Add admin block editor style less
			wp_enqueue_style( 
				'wpat-block-editor-less',  wp_admin_theme_cd_path( 'css/less/block-editor.less' ), array(), null, 'all'
			);

			// Add admin rtl style less
			if( is_rtl() ) {			
				wp_enqueue_style( 
					'wpat-rtl-style-less', wp_admin_theme_cd_path( 'css/less/rtl-style.less' ), array(), null, 'all' 
				);		
			}

			// Add custom user css for wp admin
			if( wpat_option('css_admin') ) {
				wp_enqueue_style( 
					'wpat-admin-style', wp_admin_theme_cd_path( 'css/admin.css' ), array(), filemtime( wp_admin_theme_cd_dir( 'css/admin.css' ) ), 'all' 
				);
			}

			// Add admin js		
			wp_enqueue_script( 
				'wp_admin_script_custom', wp_admin_theme_cd_path( 'js/jquery.custom.js' ), array( 'jquery' ), null, true 
			);

			// Avoiding flickering to reorder the first menu item (User Box) for left toolbar
			$custom_css = "#adminmenu li:first-child { display:none }";
			wp_add_inline_style( 'wp_admin_style_custom', $custom_css );
			
			// Add HTML tag style for toolbar hide view
			if( wpat_option('toolbar') && ! wpat_option('spacing') || wpat_option('toolbar') && wpat_option('spacing') ) {
				$toolbar_hide_css = "@media (min-width: 960px) { html.wp-toolbar { padding:0px } }";
				wp_add_inline_style( 'wp_admin_style_custom', $toolbar_hide_css );
			}

		}


		/*****************************************************************/
		/* GENERATE CUSTOM ADMIN CSS */
		/*****************************************************************/

		public function wp_admin_theme_cd_generate_custom_admin_css() {

			if( empty( wpat_option('css_admin') ) ) {
				// Stop if no custom admin css is entered
				return;
			}
			
			global $wp_filesystem;
			WP_Filesystem(); // Initial WP file system

			// Add custom user css for wp admin
			ob_start();
			require_once( wp_admin_theme_cd_dir('css/admin.php') );
			$css = ob_get_clean();
			$wp_filesystem->put_contents( wp_admin_theme_cd_dir('css/admin.css'), $css, 0644 );

		}
		
		
		/*****************************************************************/
		/* GENERATE CUSTOM LOGIN CSS */
		/*****************************************************************/

		public function wp_admin_theme_cd_generate_custom_login_css() {

			if( empty( wpat_option('css_login') ) ) {
				// Stop if no custom login css is entered
				return;
			}
			
			global $wp_filesystem;
			WP_Filesystem(); // Initial WP file system

			// Add custom user css for wp login
			ob_start();
			require_once( wp_admin_theme_cd_dir('css/login.php') );
			$css = ob_get_clean();
			$wp_filesystem->put_contents( wp_admin_theme_cd_dir('css/login.css'), $css, 0644 );

		}


		/*****************************************************************/
		/* VALIDATE ALL OPTION FIELDS */
		/*****************************************************************/

		public function wp_admin_theme_cd_validate_options( $fields ) {

			$validation = array();
			
			foreach( $fields as $key => $value ) {

				$option = isset( $this->options[$key] ) ? $this->options[$key] : $this->pre_options[$key];
				
				// Extra check for color fields
				if( $key == 'theme_color' || $key == 'theme_background' || $key == 'theme_background_end' ) {

					// Check color is empty (or cleared by user)
					if( empty( $value ) ) {
						// Empty value
						$validation[ $key ] = '';

					// Check if is a valid hex color    
					} elseif( false == $this->wp_admin_theme_cd_check_color( $value ) ) {                    

						// Color code is not valid, so get the pre saved color values
						$validation[ $key ] = isset( $this->options[$key] ) ? $this->options[$key] : $this->pre_options[$key];  
						
						// Invalid color notice
						if( ! empty( $key ) ) {
							add_settings_error(
								'wp_admin_theme_settings_options', 'save_updated', esc_html__( 'Invalid Color for', 'wp-admin-theme-cd' ) . ' ' . $this->option_fields[ $key ] . ' ' . esc_html__( 'with the value' )  . ' "' . $value . '"! ' . esc_html__( 'The old value has been restored.', 'wp-admin-theme-cd' ), 'error' 
							);
						} 

					// Get validated new hex code
					} else {
						$validation[ $key ] = $value;
					}

				} else {

					// Validate all other fields
					if( $key == 'wp_header_code' || $key == 'wp_footer_code' ) {
						$validation[ $key ] = $value;
					} else {
						// Validate all the other fields
						$validation[ $key ] = strip_tags( stripslashes( $value ) );
					}
				}
				
				
				// Get specific update notice
				if( $validation[ $key ] == $key && $value == $option ) {
					// Specific field has been not updated (new value == old value)
					//add_settings_error('wp_admin_theme_settings_options', 'save_updated', esc_html__( 'nothing has been updated', 'wp-admin-theme-cd' ), 'error' );
				} else {
					// Specific field has been updated
					if( $value != $option ) {
						add_settings_error(
							'wp_admin_theme_settings_options', 'save_updated', $this->option_fields[ $key ] . ' ' . esc_html__( 'with the value' )  . ' "' . $value . '" ' . esc_html__( 'has been updated.', 'wp-admin-theme-cd' ), 'updated' 
						);
					}
				}

			}

			// Reset all fields to default theme options
			if( isset( $_POST['reset'] ) ) {

				add_settings_error(
					'wp_admin_theme_settings_options', 'reset_error', esc_html__( 'All fields has been restored.', 'wp-admin-theme-cd' ), 'updated' 
				);

				// Restore all options to pre defined values
				return $this->pre_options;

			}

			add_settings_error(
				'wp_admin_theme_settings_options', 'save_updated', esc_html__('Settings saved.', 'wp-admin-theme-cd'), 'updated' 
			);

			// Validate all
			return apply_filters( 'wp_admin_theme_cd_validate_options', $validation, $fields);

		}


		/*****************************************************************/
		/* VALIDATE HEX CODE */
		/*****************************************************************/

		// Function that will check if value is a valid HEX color.
		public function wp_admin_theme_cd_check_color( $value ) {

			// If user insert a HEX color with #
			if( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) {
				return true;
			}

			return false;
		}


		public function wp_admin_theme_cd_display_section() {
			/* Leave blank */
		}

		public function wp_admin_theme_cd_display_section_toolbar() { 
			/* Leave blank */ 
		}

		public function wp_admin_theme_cd_display_section_colors() {
			/* Leave blank */ 
		}

		public function wp_admin_theme_cd_display_section_login() { 
			/* Leave blank */ 
		}

		public function wp_admin_theme_cd_display_section_footer() { 
			/* Leave blank */ 
		}

		public function wp_admin_theme_cd_display_section_css() { 
			/* Leave blank */ 
		}

		public function wp_admin_theme_cd_display_section_media() { 
			/* Leave blank */ 
		}

		public function wp_admin_theme_cd_display_section_plugin_pages() { 
			/* Leave blank */ 
		}

		public function wp_admin_theme_cd_display_section_multisite() { 
			/* Leave blank */ 
		}

		public function wp_admin_theme_cd_display_section_optimization() { 
			/* Leave blank */ 
		}

		public function wp_admin_theme_cd_display_section_meta_boxes() { 
			/* Leave blank */ 
		}

		public function wp_admin_theme_cd_display_section_db_widgets() { 
			/* Leave blank */ 
		}

		public function wp_admin_theme_cd_display_section_widgets() { 
			/* Leave blank */ 
		}

		public function wp_admin_theme_cd_display_section_frontend() { 
			/* Leave blank */ 
		}


		/*****************************************************************/
		/* DISPLAY THE OPTION PAGES SETTINGS FIELDS */
		/*****************************************************************/
		
		/*****************************/
		// THEME COLOR
		/*****************************/

		public function admin_theme_color_settings() {

			$theme_color = isset( $this->options['theme_color'] ) ? $this->options['theme_color'] : $this->pre_options['theme_color']; ?>

			<input type="text" name="wp_admin_theme_settings_options[theme_color]" value="<?php echo esc_html( $theme_color ); ?>" class="cpa-color-picker">

			<p class="description">
				<?php echo esc_html__( 'Select your custom wp admin theme color. Default value is #4777CD', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// THEME GRADIENT COLOR / START + END
		/*****************************/

		public function admin_theme_background_settings() {

			$theme_background = isset( $this->options['theme_background'] ) ? $this->options['theme_background'] : $this->pre_options['theme_background'];
			$theme_background_end = isset( $this->options['theme_background_end'] ) ? $this->options['theme_background_end'] : $this->pre_options['theme_background_end']; ?>

			<input type="text" name="wp_admin_theme_settings_options[theme_background]" value="<?php echo esc_html( $theme_background ); ?>" class="cpa-color-picker">

			<label for="theme_background" class="color-picker">
				<?php echo esc_html__( 'Start Color', 'wp-admin-theme-cd' ); ?>
			</label>

			<input type="text" name="wp_admin_theme_settings_options[theme_background_end]" value="<?php echo esc_html( $theme_background_end ); ?>" class="cpa-color-picker">

			<label for="theme_background_end" class="color-picker">
				<?php echo esc_html__( 'End Color', 'wp-admin-theme-cd' ); ?>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Select your custom wp admin theme color. Default value is #4777CD', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// SPACING
		/*****************************/

		public function admin_theme_spacing_settings() {

			$spacing = isset( $this->options['spacing'] ) ? $this->options['spacing'] : $this->pre_options['spacing'];
			$spacing_max_width = isset( $this->options['spacing_max_width'] ) ? $this->options['spacing_max_width'] : $this->pre_options['spacing_max_width'];
			
			global $spacing_is_enabled;
			$spacing_is_enabled = $spacing;
			
			$status = 'hidden';
			$label = esc_html__( 'Disabled', 'wp-admin-theme-cd' );
			if( $spacing ) { 
				$status = 'visible';
				$label = esc_html__( 'Enabled', 'wp-admin-theme-cd' );				
			} ?>

			<input type="checkbox" id="spacing" name="wp_admin_theme_settings_options[spacing]"<?php if( $spacing ) { ?> checked="checked"<?php } ?> />

			<label for="spacing">
				<?php echo esc_html__( 'Enable', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Add spacing around the backend block', 'wp-admin-theme-cd' ); ?>.
			</p>

			<?php if( ! $spacing_is_enabled ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

				<br>

				<label class="wpat-nextto-input" for="spacing_max_width">
					<?php echo  esc_html__( 'Max Width', 'wp-admin-theme-cd' ); ?>
				</label>
				
				<input class="wpat-range-value"  type="range" id="spacing_max_width" name="wp_admin_theme_settings_options[spacing_max_width]" value="<?php echo esc_html( $spacing_max_width ); ?>" min="1000" max="2600" />
				<span class="wpat-input-range"><span>2000</span></span>
				
				<label for="spacing_max_width">
					<?php echo esc_html__( 'Pixel', 'wp-admin-theme-cd' ); ?>
				</label>

			<?php if( ! $spacing_is_enabled ) { ?>
				</div>
			<?php } ?>

			<small class="wpat-info">
				<?php echo esc_html__( 'This option may affect the design of some WordPress plugins. If you use plugins with fixed positioned containers, this option is not recommended.', 'wp-admin-theme-cd' ); ?>
			</small>

		<?php }
		
		/*****************************/
		// USER BOX
		/*****************************/
		
		public function admin_theme_user_box_settings() {
			
			$user_box = isset( $this->options['user_box'] ) ? $this->options['user_box'] : $this->pre_options['user_box'];
			
			global $user_box_is_hidden;
			$user_box_is_hidden = $user_box;

			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $user_box ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );				
			} ?>

			<input type="checkbox" id="user_box" name="wp_admin_theme_settings_options[user_box]"<?php if( $user_box ) { ?> checked="checked"<?php } ?> />

			<label for="user_box">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the user avatar and name before the left wordpress admin menu', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }

		/*****************************/
		// COMPANY BOX
		/*****************************/

		public function admin_theme_company_box_settings() {

			$company_box = isset( $this->options['company_box'] ) ? $this->options['company_box'] : $this->pre_options['company_box'];
			$company_box_logo = ( isset( $this->options['company_box_logo'] ) ) ? $this->options['company_box_logo'] : $this->pre_options['company_box_logo'];
			$company_box_logo_size = ( isset( $this->options['company_box_logo_size'] ) ) ? $this->options['company_box_logo_size'] : $this->pre_options['company_box_logo_size'];
			
			$bg_image = wp_admin_theme_cd_path('img/no-thumb.jpg');
			if( $company_box_logo ) {
				$bg_image = $company_box_logo;
			}
			
			global $user_box_is_hidden;

			if( $user_box_is_hidden ) { ?>
				<div class="wpat-inactive-option">
			<?php }
					
				$status = 'hidden';
				$label = esc_html__( 'Disabled', 'wp-admin-theme-cd' );
				if( $company_box ) { 
					$status = 'visible';
					$label = esc_html__( 'Enabled', 'wp-admin-theme-cd' );					
				} ?>

				<input type="checkbox" id="company_box" name="wp_admin_theme_settings_options[company_box]"<?php if( $company_box ) { ?> checked="checked"<?php } ?> />

				<label for="company_box">
					<?php echo esc_html__( 'Enable', 'wp-admin-theme-cd' ) ?>
					<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
				</label>

				<p class="description">
					<?php echo esc_html__( 'Show a company box with your logo instead of the user box. The user box must be visible', 'wp-admin-theme-cd' ); ?>
				</p>
					
				<br>

				<label for="company_box_logo">
					<?php echo esc_html__( 'Company Logo', 'wp-admin-theme-cd' ); ?>
				</label>
					
				<input type="text" id="company_box_logo" name="wp_admin_theme_settings_options[company_box_logo]" value="<?php echo esc_html__( $company_box_logo ); ?>" /> 
				<input id="company_box_logo_upload_button" class="button uploader" type="button" value="<?php echo esc_html__( 'Upload Image', 'wp-admin-theme-cd' ); ?>" />

				<label class="wpat-nextto-input" for="company_box_logo_size" style="margin-left: 30px">
					<?php echo esc_html__( 'Logo Size', 'wp-admin-theme-cd' ); ?>
				</label>
					
				<input class="wpat-range-value" type="range" id="company_box_logo_size" name="wp_admin_theme_settings_options[company_box_logo_size]" value="<?php echo esc_html__( $company_box_logo_size ); ?>" min="100" max="300" />
				<span class="wpat-input-range"><span>140</span></span>
					
				<label for="company_box_logo_size">
					<?php echo esc_html__( 'Pixel', 'wp-admin-theme-cd' ); ?>
				</label>

				<div class="img-upload-container" style="background-image:url(<?php echo esc_url( $bg_image ); ?>)"></div>

			<?php if( $user_box_is_hidden ) { ?>
				</div>
			<?php }

		}

		/*****************************/
		// THUMBNAILS
		/*****************************/

		public function admin_theme_thumbnail_settings() {

			$thumbnail = isset( $this->options['thumbnail'] ) ? $this->options['thumbnail'] : $this->pre_options['thumbnail'];
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $thumbnail ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="thumbnail" name="wp_admin_theme_settings_options[thumbnail]"<?php if( $thumbnail ) { ?> checked="checked"<?php } ?> />

			<label for="thumbnail">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display a thumbnail column before the title for post and page table lists', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }

		/*****************************/
		// POST/PAGE IDS
		/*****************************/

		public function admin_theme_post_page_id_settings() {

			$post_page_id = isset( $this->options['post_page_id'] ) ? $this->options['post_page_id'] : $this->pre_options['post_page_id'];
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $post_page_id ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="post_page_id" name="wp_admin_theme_settings_options[post_page_id]"<?php if( $post_page_id ) { ?> checked="checked"<?php } ?> />

			<label for="post_page_id">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display a IDs column for post and page table lists', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }

		/*****************************/
		// HIDE CONTExTUAL HELP
		/*****************************/

		public function admin_theme_hide_help_settings() {

			$hide_help = isset( $this->options['hide_help'] ) ? $this->options['hide_help'] : $this->pre_options['hide_help'];
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $hide_help ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="hide_help" name="wp_admin_theme_settings_options[hide_help]"<?php if( $hide_help ) { ?> checked="checked"<?php } ?> />

			<label for="hide_help">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Hide the contextual help at the top right side', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }

		/*****************************/
		// HIDE SCREEN OPTIONS
		/*****************************/

		public function admin_theme_hide_screen_option_settings() {

			$hide_screen_option = isset( $this->options['hide_screen_option'] ) ? $this->options['hide_screen_option'] : $this->pre_options['hide_screen_option'];
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $hide_screen_option ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="hide_screen_option" name="wp_admin_theme_settings_options[hide_screen_option]"<?php if( $hide_screen_option ) { ?> checked="checked"<?php } ?> />

			<label for="hide_screen_option">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Hide the screen options at the top right side', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// LEFT MENU WIDTH
		/*****************************/

		public function admin_theme_left_menu_width_settings() {

			$left_menu_width = isset( $this->options['left_menu_width'] ) ? $this->options['left_menu_width'] : $this->pre_options['left_menu_width']; ?>

			<input class="wpat-range-value" type="range" id="left_menu_width" name="wp_admin_theme_settings_options[left_menu_width]" value="<?php echo esc_html( $left_menu_width ); ?>" min="160" max="400" />
			<span class="wpat-input-range"><span>160</span></span>

			<label for="left_menu_width">
				<?php echo esc_html__( 'Pixel', 'wp-admin-theme-cd' ); ?>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Increase the left admin menu width up to 400px', 'wp-admin-theme-cd' ); ?>.
			</p>

			<small class="wpat-info">
				<?php echo esc_html__( 'This option may affect the design of some WordPress plugins. If you use many plugins, this option is not recommended.', 'wp-admin-theme-cd' ); ?>
			</small>

		<?php }
		
		/*****************************/
		// LEFT MENU EXPANDABLE
		/*****************************/

		public function admin_theme_left_menu_expand_settings() {

			$left_menu_expand = isset( $this->options['left_menu_expand'] ) ? $this->options['left_menu_expand'] : $this->pre_options['left_menu_expand'];
			
			$status = 'hidden';
			$label = esc_html__( 'Disabled', 'wp-admin-theme-cd' );
			if( $left_menu_expand ) { 
				$status = 'visible';
				$label = esc_html__( 'Enabled', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="left_menu_expand" name="wp_admin_theme_settings_options[left_menu_expand]"<?php if( $left_menu_expand ) { ?> checked="checked"<?php } ?> />

			<label for="left_menu_expand">
				<?php echo esc_html__( 'Enable', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display submenus of the left admin menu only after clicking as an expandable menu', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// GOOGLE WEBFONT
		/*****************************/

		public function admin_theme_google_webfont_settings() {

			$google_webfont = isset( $this->options['google_webfont'] ) ? $this->options['google_webfont'] : $this->pre_options['google_webfont'];
			$google_webfont_weight = isset( $this->options['google_webfont_weight'] ) ? $this->options['google_webfont_weight'] : $this->pre_options['google_webfont_weight']; ?>

			<p>
				<input type="text" id="google_webfont" name="wp_admin_theme_settings_options[google_webfont]" value="<?php echo esc_html( $google_webfont ); ?>" size="60" placeholder="Open+Sans" />&nbsp;&nbsp;

				<label for="google_webfont">
					<?php echo esc_html__( 'Font-Family', 'wp-admin-theme-cd' ); ?>
				</label>
				
			</p>

			<p>
				<input type="text" id="google_webfont_weight" name="wp_admin_theme_settings_options[google_webfont_weight]" value="<?php echo esc_html( $google_webfont_weight ); ?>" size="60" placeholder="300,400,400i,700" />&nbsp;&nbsp;
			
				<label for="google_webfont_weight">
					<?php echo esc_html__( 'Font-Weight', 'wp-admin-theme-cd' ); ?>
				</label>
			</p>

			<p class="description">
				<?php echo wp_kses( __( 'Embed a custom <a target="_blank" href="https://fonts.google.com/">Google Webfont</a> to your WordPress Admin', 'wp-admin-theme-cd' ), 
					array(  
						'a' => array( 
							'href' => array(),
							'target' => array(),
						) 
					)
				); ?>.
			</p>

			<small class="wpat-info">
				<?php echo esc_html__( 'Please separate in Font-Name and Font-Weight like this example: [Font-Family = "Roboto"] and [Font-Weight = "400,400i,700"]', 'wp-admin-theme-cd' ); ?>
			</small>

		<?php }
		
		/*****************************/
		// TOOLBAR
		/*****************************/

		public function admin_theme_toolbar_settings() {

			$toolbar = isset( $this->options['toolbar'] ) ? $this->options['toolbar'] : $this->pre_options['toolbar'];
			
			global $toolbar_is_hidden;
			$toolbar_is_hidden = $toolbar;
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $toolbar ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="toolbar" name="wp_admin_theme_settings_options[toolbar]"<?php if( $toolbar ) { ?> checked="checked"<?php } ?> />

			<label for="toolbar">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the upper toolbar', 'wp-admin-theme-cd' ); ?>.
			</p>

			<small class="wpat-info">
				<?php echo esc_html__( 'This option may affect the design of some WordPress plugins. If you use plugins with fixed positioned containers, this option is not recommended.', 'wp-admin-theme-cd' ); ?>
			</small>

		<?php }
		
		/*****************************/
		// TOOLBAR COMMENTS MENU
		/*****************************/

		public function admin_theme_hide_adminbar_comments_settings() {

			$hide_adminbar_comments = isset( $this->options['hide_adminbar_comments'] ) ? $this->options['hide_adminbar_comments'] : $this->pre_options['hide_adminbar_comments'];
			
			global $toolbar_is_hidden;
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $hide_adminbar_comments ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} 

			if( $toolbar_is_hidden ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="checkbox" id="hide_adminbar_comments" name="wp_admin_theme_settings_options[hide_adminbar_comments]"<?php if( $hide_adminbar_comments ) { ?> checked="checked"<?php } ?> />

			<label for="hide_adminbar_comments">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the WordPress Comments Menu from the upper toolbar', 'wp-admin-theme-cd' ); ?>.
			</p>
					
			<?php if( $toolbar_is_hidden ) { ?>
				</div>
			<?php }

		}
		
		/*****************************/
		// TOOLBAR NEW CONTENT MENU
		/*****************************/

		public function admin_theme_hide_adminbar_new_settings() {

			$hide_adminbar_new = isset( $this->options['hide_adminbar_new'] ) ? $this->options['hide_adminbar_new'] : $this->pre_options['hide_adminbar_new'];
			
			global $toolbar_is_hidden;
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $hide_adminbar_new ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} 

			if( $toolbar_is_hidden ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="checkbox" id="hide_adminbar_new" name="wp_admin_theme_settings_options[hide_adminbar_new]"<?php if( $hide_adminbar_new ) { ?> checked="checked"<?php } ?> />

			<label for="hide_adminbar_new">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the WordPress New Content Menu from the upper toolbar', 'wp-admin-theme-cd' ); ?>.
			</p>
					
			<?php if( $toolbar_is_hidden ) { ?>
				</div>
			<?php }

		}
		
		/*****************************/
		// TOOLBAR CUSTOMIZER LINK
		/*****************************/

		public function admin_theme_hide_adminbar_customize_settings() {

			$hide_adminbar_customize = isset( $this->options['hide_adminbar_customize'] ) ? $this->options['hide_adminbar_customize'] : $this->pre_options['hide_adminbar_customize'];
			
			global $toolbar_is_hidden;
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $hide_adminbar_customize ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} 

			if( $toolbar_is_hidden ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="checkbox" id="hide_adminbar_customize" name="wp_admin_theme_settings_options[hide_adminbar_customize]"<?php if( $hide_adminbar_customize ) { ?> checked="checked"<?php } ?> />

			<label for="hide_adminbar_customize">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the WordPress Customize Link from the upper frontend toolbar', 'wp-admin-theme-cd' ); ?>.
			</p>
					
			<?php if( $toolbar_is_hidden ) { ?>
				</div>
			<?php }

		}
		
		/*****************************/
		// TOOLBAR SEARCH
		/*****************************/

		public function admin_theme_hide_adminbar_search_settings() {

			$hide_adminbar_search = isset( $this->options['hide_adminbar_search'] ) ? $this->options['hide_adminbar_search'] : $this->pre_options['hide_adminbar_search'];
			
			global $toolbar_is_hidden;
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $hide_adminbar_search ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} 

			if( $toolbar_is_hidden ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="checkbox" id="hide_adminbar_search" name="wp_admin_theme_settings_options[hide_adminbar_search]"<?php if( $hide_adminbar_search ) { ?> checked="checked"<?php } ?> />

			<label for="hide_adminbar_search">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the WordPress Search from the upper frontend toolbar', 'wp-admin-theme-cd' ); ?>.
			</p>
					
			<?php if( $toolbar_is_hidden ) { ?>
				</div>
			<?php }

		}
		
		/*****************************/
		// TOOLBAR WP ICON
		/*****************************/

		public function admin_theme_toolbar_wp_icon_settings() {

			$toolbar_wp_icon = isset( $this->options['toolbar_wp_icon'] ) ? $this->options['toolbar_wp_icon'] : $this->pre_options['toolbar_wp_icon'];
			
			global $toolbar_is_hidden, $toolbar_wp_icon_is_hidden;
			$toolbar_wp_icon_is_hidden = $toolbar_wp_icon;
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $toolbar_wp_icon ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} 

			if( $toolbar_is_hidden ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="checkbox" id="toolbar_wp_icon" name="wp_admin_theme_settings_options[toolbar_wp_icon]"<?php if( $toolbar_wp_icon ) { ?> checked="checked"<?php } ?> />

			<label for="toolbar_wp_icon">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the WordPress Menu and Icon from the upper toolbar', 'wp-admin-theme-cd' ); ?>.
			</p>
					
			<?php if( $toolbar_is_hidden ) { ?>
				</div>
			<?php }

		}
		
		/*****************************/
		// TOOLBAR CUSTOM ICON
		/*****************************/

		public function admin_theme_toolbar_icon_settings() {

			$toolbar_icon = isset( $this->options['toolbar_icon'] ) ? $this->options['toolbar_icon'] : $this->pre_options['toolbar_icon'];

			$bg_image = wp_admin_theme_cd_path('img/no-thumb.jpg');
			if( $toolbar_icon ) {
				$bg_image = $toolbar_icon;
			}
			
			global $toolbar_is_hidden, $toolbar_wp_icon_is_hidden;

			if( $toolbar_is_hidden || $toolbar_wp_icon_is_hidden ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

				<input type="text" id="toolbar_icon" name="wp_admin_theme_settings_options[toolbar_icon]" value="<?php echo esc_html( $toolbar_icon ); ?>" />
				<input id="toolbar_icon_upload_button" class="button uploader" type="button" value="<?php echo esc_html__( 'Upload Image', 'wp-admin-theme-cd' ); ?>" />

				<div class="img-upload-container" style="background-image:url(<?php echo esc_url( $bg_image ); ?>)"></div>
			
				<p class="description">
					<?php echo esc_html__( 'Upload a custom icon instead of the WordPress icon', 'wp-admin-theme-cd' ); ?>.
				</p>

				<small class="wpat-info">
					<?php echo esc_html__( 'Recommended image size is 26 x 26px.', 'wp-admin-theme-cd' ); ?>
				</small>

			<?php if( $toolbar_is_hidden || $toolbar_wp_icon_is_hidden ) { ?>
				</div>
			<?php }

		}
		
		/*****************************/
		// TOOLBAR COLOR
		/*****************************/

		public function admin_toolbar_color_settings() {

			$toolbar_color = isset( $this->options['toolbar_color'] ) ? $this->options['toolbar_color'] : $this->pre_options['toolbar_color']; ?>

			<input type="text" name="wp_admin_theme_settings_options[toolbar_color]" value="<?php echo esc_html( $toolbar_color ); ?>" class="cpa-color-picker">

			<p class="description">
				<?php echo esc_html__( 'Default value is #32373c', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// DISABLE LOGIN
		/*****************************/

		public function admin_theme_login_disable_settings() {

			$login_disable = isset( $this->options['login_disable'] ) ? $this->options['login_disable'] : $this->pre_options['login_disable'];
			
			global $login_is_disabled;
			$login_is_disabled = $login_disable;

			$status = 'visible';
			$label = esc_html__( 'Enabled', 'wp-admin-theme-cd' );
			if( $login_disable ) { 
				$status = 'hidden';
				$label = esc_html__( 'Disabled', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="login_disable" name="wp_admin_theme_settings_options[login_disable]"<?php if( $login_disable ) { ?> checked="checked"<?php } ?> />

			<label for="login_disable">
				<?php echo esc_html__( 'Disable', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'It is useful if you have an other login plugin installed. This is preventing conflicts with other plugins', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// LOGIN TITLE
		/*****************************/

		public function admin_theme_login_title_settings() {

			$login_title = isset( $this->options['login_title'] ) ? $this->options['login_title'] : $this->pre_options['login_title'];

			global $login_is_disabled; 

			if( $login_is_disabled ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="text" name="wp_admin_theme_settings_options[login_title]" value="<?php echo esc_html( $login_title ); ?>" size="60">

			<?php if( $login_is_disabled ) { ?>
				</div>
			<?php }
		
		}
		
		/*****************************/
		// LOGIN LOGO + SIZE
		/*****************************/

		public function admin_theme_logo_upload_settings() {

			$logo_upload = isset( $this->options['logo_upload'] ) ? $this->options['logo_upload'] : $this->pre_options['logo_upload'];
			$logo_size = isset( $this->options['logo_size'] ) ? $this->options['logo_size'] : $this->pre_options['logo_size'];

			$logo_image = wp_admin_theme_cd_path('img/no-thumb.jpg');
			if( $logo_upload ) {
				$logo_image = $logo_upload;
			}
			
			global $login_is_disabled;

			if( $login_is_disabled ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="text" id="logo_upload" name="wp_admin_theme_settings_options[logo_upload]" value="<?php echo esc_html( $logo_upload ); ?>" />
			<input id="logo_upload_button" class="button uploader" type="button" value="<?php echo esc_html__( 'Upload Image', 'wp-admin-theme-cd' ); ?>" />

			<label class="wpat-nextto-input" for="logo_size" style="margin-left: 30px">
				<?php echo esc_html__( 'Logo Size', 'wp-admin-theme-cd' ); ?>
			</label>
					
			<input class="wpat-range-value" type="range" id="logo_size" name="wp_admin_theme_settings_options[logo_size]" value="<?php echo esc_html( $logo_upload ); ?>" min="100" max="400" />
			<span class="wpat-input-range"><span>200</span></span>
					
			<label for="logo_size" class="logo-size">
				<?php echo esc_html__( 'Pixel', 'wp-admin-theme-cd' ); ?>
			</label>
					
			<div class="img-upload-container" style="background-image:url(<?php echo esc_url( $logo_image ); ?>)"></div>
			
			<p class="description">
				<?php echo esc_html__( 'Upload an image for your WordPress login page', 'wp-admin-theme-cd' ); ?>.
			</p>

			<?php if( $login_is_disabled ) { ?>
				</div>
			<?php }
		
		}
		
		/*****************************/
		// LOGIN BACKGROUND IMAGE
		/*****************************/

		public function admin_theme_login_bg_settings() {

			$login_bg = isset( $this->options['login_bg'] ) ? $this->options['login_bg'] : $this->pre_options['login_bg'];

			$bg_image = wp_admin_theme_cd_path('img/no-thumb.jpg');
			if( $login_bg ) {
				$bg_image = $login_bg;
			}
			
			global $login_is_disabled;

			if( $login_is_disabled ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="text" id="login_bg" name="wp_admin_theme_settings_options[login_bg]" value="<?php echo esc_html( $login_bg ); ?>" />
			<input id="login_bg_upload_button" class="button uploader" type="button" value="<?php echo esc_html__( 'Upload Image', 'wp-admin-theme-cd' ); ?>" />

			<div class="img-upload-container" style="background-image:url(<?php echo esc_url( $bg_image ); ?>)"></div>
			
			<p class="description">
				<?php echo esc_html__( 'Upload a background image for your WordPress login page', 'wp-admin-theme-cd' ); ?>.
			</p>

			<?php if( $login_is_disabled ) { ?>
				</div>
			<?php }
		
		}
		
		/*****************************/
		// CREDITS
		/*****************************/

		public function admin_theme_credits_settings() {

			$credits = isset( $this->options['credits'] ) ? $this->options['credits'] : $this->pre_options['credits'];
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $credits ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="credits" name="wp_admin_theme_settings_options[credits]"<?php if( $credits ) { ?> checked="checked"<?php } ?> />

			<label for="credits">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the credits note from the footer', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// MEMORY USAGE
		/*****************************/

		public function admin_theme_memory_usage_settings() {

			$memory_usage = isset( $this->options['memory_usage'] ) ? $this->options['memory_usage'] : $this->pre_options['memory_usage'];
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $memory_usage ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="memory_usage" name="wp_admin_theme_settings_options[memory_usage]"<?php if( $memory_usage ) { ?> checked="checked"<?php } ?> />

			<label for="memory_usage">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the currently memory usage of your WordPress installation', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// MEMORY LIMIT
		/*****************************/

		public function admin_theme_memory_limit_settings() {

			$memory_limit = isset( $this->options['memory_limit'] ) ? $this->options['memory_limit'] : $this->pre_options['memory_limit'];
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $memory_limit ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="memory_limit" name="wp_admin_theme_settings_options[memory_limit]"<?php if( $memory_limit ) { ?> checked="checked"<?php } ?> />

			<label for="memory_limit">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the memory limit of your WordPress installation', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// MEMORY AVAILABLE
		/*****************************/

		public function admin_theme_memory_available_settings() {

			$memory_available = isset( $this->options['memory_available'] ) ? $this->options['memory_available'] : $this->pre_options['memory_available'];
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $memory_available ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="memory_available" name="wp_admin_theme_settings_options[memory_available]"<?php if( $memory_available ) { ?> checked="checked"<?php } ?> />

			<label for="memory_available">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the available server memory for your WordPress installation', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// PHP VERSION
		/*****************************/

		public function admin_theme_php_version_settings() {

			$php_version = isset( $this->options['php_version'] ) ? $this->options['php_version'] : $this->pre_options['php_version'];
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $php_version ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="php_version" name="wp_admin_theme_settings_options[php_version]"<?php if( $php_version ) { ?> checked="checked"<?php } ?> />

			<label for="php_version">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the PHP version of your server', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// IP ADDRESS
		/*****************************/

		public function admin_theme_ip_address_settings() {

			$ip_address = isset( $this->options['ip_address'] ) ? $this->options['ip_address'] : $this->pre_options['ip_address'];
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $ip_address ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="ip_address" name="wp_admin_theme_settings_options[ip_address]"<?php if( $ip_address ) { ?> checked="checked"<?php } ?> />

			<label for="ip_address">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the IP address of your server', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// WP VERSION
		/*****************************/

		public function admin_theme_wp_version_settings() {

			$wp_version = isset( $this->options['wp_version'] ) ? $this->options['wp_version'] : $this->pre_options['wp_version'];
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wp-admin-theme-cd' );
			if( $wp_version ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="wp_version" name="wp_admin_theme_settings_options[wp_version]"<?php if( $wp_version ) { ?> checked="checked"<?php } ?> />

			<label for="wp_version">
				<?php echo esc_html__( 'Hide', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the installed WordPress version', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// THEME CSS
		/*****************************/

		public function admin_theme_css_admin_settings() {

			$css_admin = isset( $this->options['css_admin'] ) ? $this->options['css_admin'] : $this->pre_options['css_admin']; ?>

			<textarea class="option-textarea" type="text" name="wp_admin_theme_settings_options[css_admin]" placeholder=".your-class { color: blue }" /><?php echo esc_html( $css_admin ); ?></textarea>

			<p class="description">
				<?php echo esc_html__( 'Add custom CSS for the Wordpress admin theme. To overwrite some classes, use "!important". Like this example "border-right: 3px!important"', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// LOGIN CSS
		/*****************************/

		public function admin_theme_css_login_settings() {

			$css_login = isset( $this->options['css_login'] ) ? $this->options['css_login'] : $this->pre_options['css_login']; ?>

			<textarea class="option-textarea" type="text" name="wp_admin_theme_settings_options[css_login]" placeholder=".your-class { color: blue }" /><?php echo esc_html( $css_login ); ?></textarea>

			<p class="description">
				<?php echo esc_html__( 'Add custom CSS for the Wordpress login page. To overwrite some classes, use "!important". Like this example "border-right: 3px!important"', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// WP SVG
		/*****************************/

		public function admin_theme_wp_svg_settings() {

			$wp_svg = isset( $this->options['wp_svg'] ) ? $this->options['wp_svg'] : $this->pre_options['wp_svg'];
			
			$status = 'hidden';
			$label = esc_html__( 'Deactivated', 'wp-admin-theme-cd' );
			if( $wp_svg ) { 
				$status = 'visible';
				$label = esc_html__( 'Activated', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="wp_svg" name="wp_admin_theme_settings_options[wp_svg]"<?php if( $wp_svg ) { ?> checked="checked"<?php } ?> />

			<label for="wp_svg">
				<?php echo esc_html__( 'Enable', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Allow the upload of SVG files', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// WP ICO
		/*****************************/

		public function admin_theme_wp_ico_settings() {

			$wp_ico = isset( $this->options['wp_ico'] ) ? $this->options['wp_ico'] : $this->pre_options['wp_ico'];
			
			$status = 'hidden';
			$label = esc_html__( 'Deactivated', 'wp-admin-theme-cd' );
			if( $wp_ico ) { 
				$status = 'visible';
				$label = esc_html__( 'Activated', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="wp_ico" name="wp_admin_theme_settings_options[wp_ico]"<?php if( $wp_ico ) { ?> checked="checked"<?php } ?> />

			<label for="wp_ico">
				<?php echo esc_html__( 'Enable', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Allow the upload of ICO files', 'wp-admin-theme-cd' ); ?>.
			</p>

		<?php }
		
		/*****************************/
		// REMOVE PLUGIN PAGES (REPEATER)
		/*****************************/

		function admin_theme_disable_plugin_pages_settings() {

			// Get all meta box settings fields
			$field = array_shift( $this->plugin_pages_option_fields );
			
			$option = isset( $this->options[$field] ) ? $this->options[$field] : $this->pre_options[$field];
			
			$plugin_system_page = ( $field == 'disable_page_ms' && ! is_multisite() );
			
			$status = 'visible';
			$label = esc_html__( 'Activated', 'wp-admin-theme-cd' );
			if( $option || $plugin_system_page ) { 
				$status = 'hidden';
				$label = esc_html__( 'Removed', 'wp-admin-theme-cd' );					
			}
			
			// Multisite sync page can not be visible, because WordPress multisite is not activated
			if( $plugin_system_page ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>
					
				<input type="checkbox" id="<?php echo esc_html( $field ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field ); ?>]"<?php if( $option ) { ?> checked="checked"<?php } ?> />

				<label for="<?php echo esc_html( $field ); ?>">
					<?php echo esc_html__( 'Disable', 'wp-admin-theme-cd' ); ?>
					<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
				</label>

			<?php if( $plugin_system_page ) { ?>
				</div>            

				<small class="wpat-info">
					<?php echo esc_html__( 'Activate multisite support for WordPress to use this option', 'wp-admin-theme-cd' ); ?>.
				</small>
			<?php }


		} 
		
		/*****************************/
		// DISABLE THEME OPTIONS (MULTISITE)
		/*****************************/

		public function admin_theme_disable_theme_options_settings() {

			$disable_theme_options = isset( $this->options['disable_theme_options'] ) ? $this->options['disable_theme_options'] : $this->pre_options['disable_theme_options'];
			
			global $blog_id;
			
			$status = 'hidden';
			$label = esc_html__( 'Deactivated', 'wp-admin-theme-cd' );
			if( $disable_theme_options ) { 
				$status = 'visible';
				$label = esc_html__( 'Activated', 'wp-admin-theme-cd' );					
			}

			if( is_multisite() && $blog_id == 1 ) { ?>
				<input type="checkbox" id="disable_theme_options" name="wp_admin_theme_settings_options[disable_theme_options]"<?php if( $disable_theme_options ) { ?> checked="checked"<?php } ?> />
			<?php } else { ?>
				<input type="checkbox" id="#" name="#" disabled="disabled"<?php if( $disable_theme_options ) { ?> checked="checked"<?php } ?> />
			<?php } ?>

			<label for="disable_theme_options">
				<?php echo esc_html__( 'Disable', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Disable the permissions to change WP Admin Theme options for all other network sites', 'wp-admin-theme-cd' ); ?>.
			</p>

			<?php if ( ! is_multisite() ) { ?>
				<small class="wpat-info">
					<?php echo esc_html__( 'Activate multisite support for WordPress to use this option', 'wp-admin-theme-cd' ); ?>.
				</small>
			<?php } 

		}
		
		/*****************************/
		// WP OPTIMIZATION (REPEATER)
		/*****************************/

		public function admin_theme_wp_optimization_settings() {

			// Get all optimization settings fields
			$field = array_shift( $this->optimization_option_fields );

			$option = isset( $this->options[$field[0]] ) ? $this->options[$field[0]] : $this->pre_options[$field[0]];
			
			$status = 'visible';
			$label = esc_html__( 'Activated', 'wp-admin-theme-cd' );
			if( $option ) { 
				$status = 'hidden';
				$label = esc_html__( 'Removed', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="<?php echo esc_html( $field[0] ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field[0] ); ?>]"<?php if( $option ) { ?> checked="checked"<?php } ?> />

			<label for="<?php echo esc_html( $field[0] ); ?>">
				<?php echo esc_html__( 'Disable', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html( $field[1] ); ?>
			</p>

			<small class="wpat-info">
				<?php echo esc_html( $field[2] ); ?>
			</small>

		<?php }
		
		/*****************************/
		// REMOVE META BOXES (REPEATER)
		/*****************************/

		public function admin_theme_meta_box_settings() {

			// Get all meta box settings fields
			$field = array_shift( $this->meta_box_option_fields );
			
			$option = isset( $this->options[$field[0]] ) ? $this->options[$field[0]] : $this->pre_options[$field[0]];
			
			$status = 'visible';
			$label = esc_html__( 'Activated', 'wp-admin-theme-cd' );
			if( $option ) { 
				$status = 'hidden';
				$label = esc_html__( 'Removed', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="<?php echo esc_html( $field[0] ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field[0] ); ?>]"<?php if( $option ) { ?> checked="checked"<?php } ?> />

			<label for="<?php echo esc_html( $field[0] ); ?>">
				<?php echo esc_html__( 'Disable', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html( $field[1] ); ?>.
			</p>

			<?php if( $field[2] ) { ?>
				<small class="wpat-info">
					<?php echo esc_html( $field[2] ); ?>
				</small>
			<?php }

		}
		
		/*****************************/
		// REMOVE WP DASHBOARD WIDGETS (REPEATER)
		/*****************************/

		public function admin_theme_db_widgets_settings() {

			// Get all meta box settings fields
			$field = array_shift( $this->db_widget_option_fields );
			
			// System info dashboad widget can not be activated, if the plugin system info page is deactivated
			$plugin_system_page_is_disabled = isset( $this->options['disable_page_system'] ) ? $this->options['disable_page_system'] : $this->pre_options['disable_page_system'];
			$plugin_system_page = ( $field == 'dbw_wpat_sys_info' && $plugin_system_page_is_disabled );
			
			$option = isset( $this->options[$field] ) ? $this->options[$field] : $this->pre_options[$field];
			
			$status = 'visible';
			$label = esc_html__( 'Activated', 'wp-admin-theme-cd' );
			if( $option || $plugin_system_page ) { 
				$status = 'hidden';
				$label = esc_html__( 'Removed', 'wp-admin-theme-cd' );					
			}

			if( $plugin_system_page ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="checkbox" id="<?php echo esc_html( $field ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field ); ?>]"<?php if( $option || $plugin_system_page ) { ?> checked="checked"<?php } ?> />

			<label for="<?php echo esc_html( $field ); ?>">
				<?php echo esc_html__( 'Disable', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>
					
			<?php if( $plugin_system_page ) { ?>
				</div>

				<small class="wpat-info">
					<?php echo esc_html__( 'System info dashboad widget can not be activated, if the plugin system info page is deactivated', 'wp-admin-theme-cd' ); ?>.
				</small>
			<?php }

		}
		
		/*****************************/
		// REMOVE WP WIDGETS (REPEATER)
		/*****************************/

		public function admin_theme_widgets_settings() {

			// Get all meta box settings fields
			$field = array_shift( $this->widget_option_fields );
			
			$option = isset( $this->options[$field] ) ? $this->options[$field] : $this->pre_options[$field];
			
			$status = 'visible';
			$label = esc_html__( 'Activated', 'wp-admin-theme-cd' );
			if( $option ) { 
				$status = 'hidden';
				$label = esc_html__( 'Removed', 'wp-admin-theme-cd' );					
			} ?>

			<input type="checkbox" id="<?php echo esc_html( $field ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field ); ?>]"<?php if( $option ) { ?> checked="checked"<?php } ?> />

			<label for="<?php echo esc_html( $field ); ?>">
				<?php echo esc_html__( 'Disable', 'wp-admin-theme-cd' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

		<?php }
		
		/*****************************/
		// FRONTEND (REPEATER)
		/*****************************/

		public function admin_theme_frontend_settings() {

			// Get all frontend settings fields
			$field = array_shift( $this->frontend_option_fields );

			$option = isset( $this->options[$field[0]] ) ? $this->options[$field[0]] : $this->pre_options[$field[0]];
			
			// Meta Referrer Policy Field
			if( $field[0] == 'meta_referrer_policy' ) {

				$items = array(
					'none' => esc_html__( 'Disabled', 'wp-admin-theme-cd' ),
					'no-referrer' => 'No Referrer',
					'no-referrer-when-downgrade' => 'No Referrer When Downgrade',
					'same-origin' => 'Same Origin',
					'origin' => 'Origin',
					'strict-origin' => 'Strict Origin',
					'origin-when-crossorigin' => 'Origin When Crossorigin',
					'strict-origin-when-crossorigin' => 'Strict Origin When Crossorigin',
					'unsafe-url' => 'Unsafe URL',
				); ?>

				<select id="<?php echo esc_html( $field[0] ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field[0] ); ?>]">

					<?php foreach( $items as $key => $item ) { ?>
						<option value="<?php echo esc_html( $key ); ?>"<?php if( $option == $key ) { ?> selected="selected"<?php } ?>>
							<?php echo esc_html( $item ); ?>
						</option>
					<?php } ?>

				</select>

			<?php // Header + Footer Code
			} elseif( $field[0] == 'wp_header_code' || $field[0] == 'wp_footer_code' ) { ?>

				<textarea class="option-textarea" type="text" name="wp_admin_theme_settings_options[<?php echo esc_html( $field[0] ); ?>]" placeholder="<script>alert(\'My custom script\');</script> or <style>.my-class {color: red}</style>" /><?php echo $option; ?></textarea>

			<?php // Other Fields
			} else { 

				$status = 'visible';
				$label = esc_html__( 'Activated', 'wp-admin-theme-cd' );
				if( $option ) { 
					$status = 'hidden';
					$label = esc_html__( 'Removed', 'wp-admin-theme-cd' );					
				} ?>   

				<input type="checkbox" id="<?php echo esc_html( $field[0] ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field[0] ); ?>]"<?php if( $option ) { ?> checked="checked"<?php } ?> />

				<label for="<?php echo esc_html( $field[0] ); ?>">
					<?php echo esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status; ?>
				</label>

			<?php } ?>

			<p class="description">
				<?php echo esc_html( $field[1] ); ?>
			</p>

			<small class="wpat-info">
				<?php echo esc_html( $field[2] ); ?>
			</small>

		<?php }

	} // end class

	WP_Admin_Theme_CD_Options::get_instance();


	/*****************************************************************/
	/* CREATE OPTION WRAPPER FUNCTION */
	/*****************************************************************/

	if ( ! function_exists( 'wpat_option' ) ) :

		function wpat_option( $option ) {

			// Get currently indexed option fields
			$options = get_option( 'wp_admin_theme_settings_options' );
			if( is_multisite() ) {
				$options = get_blog_option( get_current_blog_id(), 'wp_admin_theme_settings_options', array() );
			}
			
			$get_option = isset( $options[$option] ) ? $options[$option] : false;
			
			return $get_option;

		}

	endif;


	/*****************************************************************/
	/* SWITCH HEX COLOR CODE TO RGBA COLOR CODE */
	/*****************************************************************/

	if ( ! function_exists( 'wp_admin_theme_cd_hex2rgba' ) ) :

		function wp_admin_theme_cd_hex2rgba( $color, $opacity = false ) {

			$default = 'rgb(0,0,0)';

			// Return default if no color provided
			if( empty( $color ) ) {
				return $default;
			}

			// Sanitize $color if "#" is provided 
			if( $color[0] == '#' ) {
				$color = substr( $color, 1 );
			}

			// Check if color has 6 or 3 characters and get values
			if( strlen( $color ) == 6 ) {
				$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
			} elseif ( strlen( $color ) == 3 ) {
				$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
			} else {
				return $default;
			}

			// Convert hexadec to rgb
			$rgb = array_map( 'hexdec', $hex );

			// Check if opacity is set(rgba or rgb)
			if( $opacity ) {
				if( abs( $opacity ) > 1 ) $opacity = 1.0;
				$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
			} else {
				$output = 'rgb(' . implode( ",", $rgb ) . ')';
			}

			// Return rgb(a) color string
			return $output;

			/* Usage example:

			$color = '#ffa226';
			$rgb = hex2rgba($color);
			$rgba = hex2rgba($color, 0.7);

			*/

		}

	endif;


	/*****************************************************************/
	/* GET INSTALLATION URL */
	/*****************************************************************/

	if ( ! function_exists( ' wp_admin_theme_cd_root_url' ) ) : 

		// Return the root url
		function wp_admin_theme_cd_root_url() {
			
			// Get the full WordPress installation path (included subfolders)
			if( is_multisite() ) {
				return get_site_url( get_current_blog_id() );
			}
			return get_site_url();

			// Notice: The following snippet is not working only for the root domain
			// --> For WordPress installation use this snippet to get the root url
			//$url_parts = parse_url( home_url() );
			//$domain = $url_parts['host'];
			//return set_url_scheme( 'http' . ( empty( $_SERVER['HTTPS'] ) ? '' : 's' ) . '://' . $domain );

			// Notice: The following snippet is not working in all cases
			// --> return set_url_scheme( 'http' . ( empty( $_SERVER['HTTPS'] ) ? '' : 's' ) . '://' . $_SERVER['HTTP_HOST'] );

		}

	endif;


	/*****************************************************************/
	/* MYSQLI CONNECTION */
	/*****************************************************************/

	if ( ! function_exists( ' wp_admin_theme_cd_mysqli_connect' ) ) : 

		// Connect to the WordPress MySQL database
		function wp_admin_theme_cd_mysqli_connect() {
			
			$mysqli = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

			// Check for MySQL connection error
			if( ! $mysqli || $mysqli->connect_error ) {			
				// Stop connection
				return false;
			}

			// Connection successful
			return $mysqli;

		}

	endif;


	/*****************************************************************/
	/* PLUGIN USAGE */
	/*****************************************************************/

	function wp_admin_theme_cd_plugin() {	
		$permission = 'denied';	
		if( has_filter('wp_admin_theme_cd_accepted') ) {
			$apply = apply_filters('wp_admin_theme_cd_accepted', $permission);
			if( $apply === 'accepted_by_theme' || $apply === 'accepted' ) {
				return true;
			}
		}	
		return false;
	}

	add_action( 'admin_init', 'wp_admin_theme_cd_plugin' );


	/*****************************************************************/
	/* PLUGIN ENVATO RATING */
	/*****************************************************************/

	if ( ! function_exists('wp_admin_theme_cd_activation_date') ) :	

		function wp_admin_theme_cd_activation_date() {
			
			// Set plugin activation date
			add_option('wp_admin_theme_cd_activation_date', current_time( 'mysql' ), '', 'yes');
			define( 'WP_ADMIN_THEME_CD_ACTIVATION_DATE', get_option('wp_admin_theme_cd_activation_date') );
			
		}

	endif;

	add_action('plugins_loaded', 'wp_admin_theme_cd_activation_date');


	if ( ! function_exists('wp_admin_theme_cd_envato_rating') ) :	

		function wp_admin_theme_cd_envato_rating() {
			
			$start_date = WP_ADMIN_THEME_CD_ACTIVATION_DATE;  
			$date = strtotime( $start_date );
			$date = strtotime( '+182 day', $date ); // after a half year of plugin activation
			$reached_time = date( 'Y-m-d H:i:s', $date );		

			// Check if plugin activation date is older than a half year
			if( $reached_time < current_time( 'mysql' ) ) {

				$apply = apply_filters('wp_admin_theme_cd_accepted', $permission);
				if( $apply === 'accepted_by_theme' ) {
					// Do not show this admin notice, if the plugin is free in combination with "CreativeDive" themes
					return false;
				}

				if ( ! function_exists( 'wp_admin_theme_cd_envato_rating_notice' ) ) :

					// Show a admin notice to rate for the theme		
					function  wp_admin_theme_cd_envato_rating_notice() { 

						$star = '<span style="font-size:17px;margin:0px -2px;color:rgba(208,174,71,0.57)" class="dashicons dashicons-star-filled"></span>';
						$author = '<img style="float:left;margin:-6px 10px 0px -6px;border-radius:50%" class="theme-author-img" src="' . wp_admin_theme_cd_path( "/img/avatar-author.jpg" ) . '" width="32" alt="Theme Author">'; ?>

						<div class="notice notice-success is-dismissible">
							<p><?php printf( wp_kses_post( __( '%3$s <strong style="color:#4d820c">Hey you! I\'m Martin, the plugin author of WP Admin Theme CD.</strong> Do you like this plugin? Please show your appreciation and rate the plugin. Help me to develop a powerful plugin that will benefit you for a long time. %2$s %2$s %2$s %2$s %2$s <a href="%1$s" target="_blank">Rate now!</a>', 'hannah-cd' ) ), WP_ADMIN_THEME_CD_ENVATO_THEME_REVIEW_URL, $star, $author ); ?></p>
						</div>

					<?php }

				endif;

				add_action('admin_notices', 'wp_admin_theme_cd_envato_rating_notice');

			}

		}

	endif;

	add_action('admin_init', 'wp_admin_theme_cd_envato_rating');


	/*****************************************************************/
	/* PLUGIN ACTIVATION MESSAGE */
	/*****************************************************************/

	if ( ! function_exists( 'wp_admin_theme_cd_plugin_activation_message' ) ) :

		function wp_admin_theme_cd_plugin_activation_message() {			
			return esc_html__( 'This plugin is not activated. Please enter your Envato purchase code to enable the plugin settings.', 'wp-admin-theme-cd' );
		}

	endif;


	/*****************************************************************/
	/* INCLUDE PLUGIN PARTS */
	/*****************************************************************/

	// Plugin Setup Page
	include_once( wp_admin_theme_cd_dir( 'inc/page-setup.php' ) );
	include_once( wp_admin_theme_cd_dir( 'inc/option-setup.php' ) );

	// Plugin System Info Page
	if( wpat_option('disable_page_system') != true ) { 
		include_once( wp_admin_theme_cd_dir( 'inc/page-system-info.php' ) );
	}

	// Plugin Im- / Export Page
	if( wpat_option('disable_page_export') != true ) { 
		include_once( wp_admin_theme_cd_dir( 'inc/page-ex-import.php' ) );
	}

	// Plugin Multisite Sync Page
	if( wpat_option('disable_page_ms') != true ) { 
		include_once( wp_admin_theme_cd_dir( 'inc/page-multisite-sync.php' ) );
	}

	// (BETA) Plugin Optimization Tipps Page
	//include_once( wp_admin_theme_cd_dir( 'inc/page-optimization.php' ) );
	
	include_once( wp_admin_theme_cd_dir( 'inc/option-db-widgets.php' ) );
	include_once( wp_admin_theme_cd_dir( 'inc/option-layout.php' ) );	
	include_once( wp_admin_theme_cd_dir( 'inc/option-login.php' ) );
	include_once( wp_admin_theme_cd_dir( 'inc/option-footer.php' ) );
	include_once( wp_admin_theme_cd_dir( 'inc/option-wp.php' ) );


	/*****************************************************************/
	/* LOAD FRONTEND PART */
	/*****************************************************************/

	if ( ! function_exists( 'wp_admin_theme_cd_load_frontend_part' ) ) :

		function wp_admin_theme_cd_load_frontend_part() {	
			if( is_user_logged_in() ) {
				include_once( wp_admin_theme_cd_dir( 'inc/option-frontend.php' ) );
			}
		}

	endif;

	add_action('init', 'wp_admin_theme_cd_load_frontend_part');

	
	/*****************************************************************/
	/* PLUGIN MAIN PAGE REDIRECT TO PLUGIN ACTIVATION PAGE */
	/*****************************************************************/	
		
	function wp_admin_theme_cd_activation_redirect() {

		if( wp_admin_theme_cd_activation_status() ) {
			return false;
		}
		
		// Check current page is "wp-admin-theme-cd"
		if( isset( $_GET['page'] ) && $_GET['page'] == 'wp-admin-theme-cd' ) {
			// Redirect to plugin "wp-admin-theme-cd-purchase-code&tab=activation" page to verify the plugin
			wp_redirect( admin_url('tools.php?page=wp-admin-theme-cd-purchase-code&tab=activation') );
			exit();
		}
		
	}

	add_action('admin_init', 'wp_admin_theme_cd_activation_redirect', 1);


	/*****************************************************************/
	/* SET WP LESS OPTION VARIABLES */
	/*****************************************************************/

	// WP Less
	if( ! class_exists( 'wp_less' ) ) {
		// Only included if wpless is not already loaded by another plugin or theme to prevent conflicts
		include_once( wp_admin_theme_cd_dir('inc/wp-less/wp-less.php') );
	}	

	if ( ! function_exists( 'wp_admin_theme_cd_wp_less_vars' ) ) :

		function wp_admin_theme_cd_wp_less_vars( $vars, $handle ) {

			$wpat = new WP_Admin_Theme_CD_Options();
			
			$vars[ 'wpatThemeColor' ] = $wpat->pre_options['theme_color'];
			if( wpat_option('theme_color') ) {
				$vars[ 'wpatThemeColor' ] = wpat_option('theme_color');
			}
			
			$vars[ 'wpatGradientStartColor' ] = $wpat->pre_options['theme_background'];
			if( wpat_option('theme_background') ) {
				$vars[ 'wpatGradientStartColor' ] = wp_admin_theme_cd_hex2rgba( wpat_option('theme_background') );
			}
			
			$vars[ 'wpatGradientEndColor' ] = $wpat->pre_options['theme_background_end'];
			if( wpat_option('theme_background_end') ) {
				$vars[ 'wpatGradientEndColor' ] = wp_admin_theme_cd_hex2rgba( wpat_option('theme_background_end') );
			}
			
			$vars[ 'wpatToolbarColor' ] = $wpat->pre_options['toolbar_color'];
			if( wpat_option('toolbar_color') ) {
				$vars[ 'wpatToolbarColor' ] = wpat_option('toolbar_color');
			}
			
			$vars[ 'wpatSpacingMaxWidth' ] = $wpat->pre_options['spacing_max_width'] . 'px';
			if( wpat_option('spacing_max_width') ) {
				$vars[ 'wpatSpacingMaxWidth' ] = wpat_option('spacing_max_width') . 'px';
			}
			
			$vars[ 'wpatMenuLeftWidth' ] = $wpat->pre_options['left_menu_width'];
			if( wpat_option('left_menu_width') ) {
				$vars[ 'wpatMenuLeftWidth' ] = wpat_option('left_menu_width') . 'px';
			}
			
			$vars[ 'wpatMenuLeftWidthDiff' ] = $wpat->pre_options['left_menu_width'] - 40 . 'px';
			if( wpat_option('left_menu_width') ) {
				$vars[ 'wpatMenuLeftWidthDiff' ] = wpat_option('left_menu_width') - 40 . 'px';
			}
			
			$vars[ 'wpatLoginLogoSize' ] = $wpat->pre_options['logo_size'] . 'px';
			if( wpat_option('logo_size') ) {
				$vars[ 'wpatLoginLogoSize' ] = wpat_option('logo_size') . 'px';
			}
			
			$vars[ 'wpatToolbarIcon' ] = 'none';
			if( wpat_option('toolbar_icon') != '' ) {
				$vars[ 'wpatToolbarIcon' ] = 'url(' . wpat_option('toolbar_icon') . ')';
			}

			$vars[ 'wpatWebFont' ] = 'none';
			if( wpat_option('google_webfont') != '' ) {
				$web_font = str_replace( '+', ' ', esc_html( wpat_option('google_webfont') ) );
				$vars[ 'wpatWebFont' ] = $web_font;
			}

			$vars[ 'wpatLoginBg' ] = 'none';
			if( wpat_option('login_bg') != '' ) {
				$vars[ 'wpatLoginBg' ] = 'url(' . wpat_option('login_bg') . ')';
			}

			$vars[ 'wpatLoginLogo' ] = 'none';
			if( wpat_option('logo_upload') != '' ) {
				$vars[ 'wpatLoginLogo' ] = 'url(' . wpat_option('logo_upload') . ')';
			}

			return $vars;

		}

	endif;


	/*****************************************************************/
	/* PARSE WP LESS ONLY IN SPECIFIC CASES */
	/*****************************************************************/

	if ( ! function_exists( 'wp_admin_theme_cd_load_wpless_filter' ) ) :

		function wp_admin_theme_cd_load_wpless_filter( $hook ) {

			// Method to get the admin page hook
			// wp_die($hook);

			// Function to check if is wp login or wp register page
			$is_login = wp_admin_theme_cd_is_login_page();

			// Load only on admin_toplevel_page?page=mypluginname
			$is_wpat_option_page = ( $hook != 'tools_page_wp-admin-theme-cd' );

			// Load filter only in above cases
			if( $is_login || is_admin() && $is_wpat_option_page ) {
				add_filter( 'less_vars', 'wp_admin_theme_cd_wp_less_vars', 999, 2 );
			}

		}

	endif;

	add_action( 'init', 'wp_admin_theme_cd_load_wpless_filter' );
    




endif; // END of class_exists check

?>