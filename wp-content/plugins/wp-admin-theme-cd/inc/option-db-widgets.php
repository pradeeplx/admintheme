<?php

/*****************************************************************/
/* ADD WP DASHBOARD WIDGETS */
/*****************************************************************/

// Plugin User Activities Dashboard Widget
if( wpat_option('dbw_wpat_user_log') != true ) { 
    include_once( wp_admin_theme_cd_dir( 'inc/db-widget-user.php' ) );
}

// Plugin System Info Dashboard Widget
if( wpat_option('disable_page_system') != true && wpat_option('dbw_wpat_sys_info') != true ) { 
    include_once( wp_admin_theme_cd_dir( 'inc/db-widget-system.php' ) );
}

// Plugin Recent Posts Dashboard Widget
if( wpat_option('dbw_wpat_recent_post') != true ) {
    include_once( wp_admin_theme_cd_dir( 'inc/db-widget-recent-posts.php' ) );
}

// Plugin Recent Pages Dashboard Widget
if( wpat_option('dbw_wpat_recent_page') != true ) {
    include_once( wp_admin_theme_cd_dir( 'inc/db-widget-recent-pages.php' ) );
}
    
// Plugin Recent Comments Dashboard Widget
if( wpat_option('dbw_wpat_recent_comment') != true ) {
    include_once( wp_admin_theme_cd_dir( 'inc/db-widget-recent-comments.php' ) );
}

// Plugin Post Count Dashboard Widget
if( wpat_option('dbw_wpat_count_post') != true ) {
    include_once( wp_admin_theme_cd_dir( 'inc/db-widget-count-posts.php' ) );
}
    
// Plugin Page Count Dashboard Widget
if( wpat_option('dbw_wpat_count_page') != true ) {
    include_once( wp_admin_theme_cd_dir( 'inc/db-widget-count-pages.php' ) );
}
    
// Plugin Comment Count Dashboard Widget
if( wpat_option('dbw_wpat_count_comment') != true ) {
    include_once( wp_admin_theme_cd_dir( 'inc/db-widget-count-comments.php' ) );
}
    
// Plugin Memory Usage Dashboard Widget
if( wpat_option('dbw_wpat_memory') != true ) {
    include_once( wp_admin_theme_cd_dir( 'inc/db-widget-memory.php' ) );
}


/*****************************************************************/
/* ADDITIONAL CONTENT FOR RECIPE POST TYPE */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_post_type_recipe' ) ) :

	function wp_admin_theme_cd_post_type_recipe() {

		if( post_type_exists('recipe') ) {
			// Plugin Recent Recipes Dashboard Widget
			include_once( wp_admin_theme_cd_dir( 'inc/db-widget-recent-recipes.php' ) );
			
			// Plugin Recipe Count Dashboard Widget
			include_once( wp_admin_theme_cd_dir( 'inc/db-widget-count-recipes.php' ) );
		} else {
			return false;
		}

	}

endif;

add_action( 'admin_init', 'wp_admin_theme_cd_post_type_recipe', 30 );


/*****************************************************************/
/* CHECK SEARCH ENGINE VISIBILITY */
/*****************************************************************/

$visibility = get_option( 'blog_public' );
if( is_multisite() ) {
    $visibility = get_blog_option( get_current_blog_id(), 'blog_public', array() );
}

if( 0 == $visibility ) {
    include_once( wp_admin_theme_cd_dir( 'inc/db-widget-search-engine-notice.php' ) );
}