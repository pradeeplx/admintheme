<?php

/*****************************************************************/
/* ADD BODY CLASSES */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_body_class' ) ) :

	function wp_admin_theme_cd_body_class( $classes ) {
        
		$new_classes = array();
		
		$new_classes[] = 'wpat';
		
		// spacing enabled
		if( wpat_option('spacing') ) { 
			$new_classes[] = 'wpat-spacing-on';
		}
		
		// spacing disabled
		if( ! wpat_option('spacing') ) { 
			$new_classes[] = 'wpat-spacing-off';
		}
		
		// toolbar enabled
        if( ! wpat_option('toolbar') ) { 
			$new_classes[] = 'wpat-toolbar-on';
		}
        
		// toolbar disabled
        if( wpat_option('toolbar') ) { 
			$new_classes[] = 'wpat-toolbar-off';
		}
		
		// Left menu is expandable
		if( wpat_option('left_menu_expand') ) { 
			$new_classes[] = 'wpat-menu-left-expand';
		}
		
		// Custom toolbar icon
		if( wpat_option('toolbar_icon') ) {
			$new_classes[] = 'wpat-toolbar-icon';
		}
		
		// Custom web font
		if( wpat_option('google_webfont') ) {
			$new_classes[] = 'wpat-web-font';
		}
		
		// Custom left menu width
		if( ( wpat_option('left_menu_width') >= 190 ) ) {
			$new_classes[] = 'wpat-left-menu-width';
		}		
		
		return $classes . ' ' . implode( ' ', $new_classes );

	}

endif;

add_filter( 'admin_body_class', 'wp_admin_theme_cd_body_class' );


/*****************************************************************/
/* LOADING GOOGLE WEB FONTS */
/*****************************************************************/

if( wpat_option('google_webfont') ) {

	if ( ! function_exists( 'wp_admin_theme_cd_webfonts_url' ) ) :

		function wp_admin_theme_cd_webfonts_url( $font_style = '' ) {

			$selected_fonts = '';
			
			// get custom font name
			$selected_fonts .= wpat_option('google_webfont');
			
			// check if custom font weight exist
			if( ! empty( wpat_option('google_webfont_weight') ) ) {													
				$selected_fonts .= ':' . wpat_option('google_webfont_weight');
			}
			
			$font_style = add_query_arg( 'family', esc_html( $selected_fonts ), "//fonts.googleapis.com/css" );

			return $font_style;
		}

	endif;


	if ( ! function_exists( 'wp_admin_theme_cd_webfonts_output' ) ) :

		function wp_admin_theme_cd_webfonts_output() {

			wp_enqueue_style( 'wp_admin_theme_cd_webfonts', wp_admin_theme_cd_webfonts_url(), array(), null, 'all' );

		}

	endif;

	add_action( 'admin_enqueue_scripts', 'wp_admin_theme_cd_webfonts_output', 30 );

}


/*****************************************************************/
/* WRAP THE WP ADMIN CONTENT */
/*****************************************************************/

if( wpat_option('spacing') ) {

	if ( ! function_exists( 'wp_admin_theme_cd_wrap_content' ) ) :

		function wp_admin_theme_cd_wrap_content() {
			ob_start( 'wp_admin_theme_cd_replace_content' );
		}

	endif;

	if ( ! function_exists( 'wp_admin_theme_cd_replace_content' ) ) :

        function wp_admin_theme_cd_replace_content( $output ) {

            $find = array('/<div id="wpwrap">/', '#</body>#');
            $replace = array('<div class="body-spacer"><div id="wpwrap">', '</div></body>');
            $result = preg_replace( $find, $replace, $output );

            return $result;
        }

	endif;

	add_action( 'init', 'wp_admin_theme_cd_wrap_content', 0, 0 );

}


/*****************************************************************/
/* CREATE LOGOUT BUTTON */
/*****************************************************************/

if( wpat_option('toolbar') ) {

	if ( ! function_exists( 'wp_admin_theme_cd_logout' ) ) :

		function wp_admin_theme_cd_logout() {
			echo '<div class="wpat-logout"><div class="wpat-logout-button"></div><div class="wpat-logout-content"><a target="_blank" class="btn home-btn" href="' . home_url() . '">' . esc_html__( 'Home', 'wp-admin-theme-cd' ) . '</a>';
            if( is_multisite() ) {
                echo '<a class="btn multisite-btn" href="' . network_admin_url() . '">' . esc_html__( 'My Sites', 'wp-admin-theme-cd' ) . '</a>';
            }
            echo '<a class="btn logout-btn" href="' . wp_logout_url() . '">' . esc_html__( 'Logout', 'wp-admin-theme-cd' ) . '</a></div></div>';
		}

	endif;

    add_action('admin_head', 'wp_admin_theme_cd_logout');
	
}


/*****************************************************************/
/* ADD USER BOX TO LEFT ADMIN MENU */
/*****************************************************************/
	
if( ! wpat_option('user_box') && ! wpat_option('company_box') ) {

	if ( ! function_exists( 'wp_admin_theme_cd_userbox' ) ) :

		function wp_admin_theme_cd_userbox() {

			global $menu, $user_id, $scheme;

			// get user name and avatar
			$current_user = wp_get_current_user();
			$user_name = $current_user->display_name ;
			$user_avatar = get_avatar( $current_user->user_email, 74 );

			// get user profile link
			if ( is_user_admin() ) {
				$url = user_admin_url( 'profile.php', $scheme );
			} elseif ( is_network_admin() ) {
				$url = network_admin_url( 'profile.php', $scheme );
			} else {
				$url = get_dashboard_url( $user_id, 'profile.php', $scheme );
			}    

			if( is_rtl() ) {
				$html = '<div class="adminmenu-avatar">' . $user_avatar . '<div class="adminmenu-user-edit">' . esc_html__( 'Edit', 'wp-admin-theme-cd' ) . '</div></div><div class="adminmenu-user-name"><span>' . esc_html__( $user_name ) . ', ' . esc_html__('Howdy', 'wp-admin-theme-cd') . '</span></div>';
			} else {
				$html = '<div class="adminmenu-avatar">' . $user_avatar . '<div class="adminmenu-user-edit">' . esc_html__( 'Edit', 'wp-admin-theme-cd' ) . '</div></div><div class="adminmenu-user-name"><span>' . esc_html__('Howdy', 'wp-admin-theme-cd') . ', ' . esc_html__( $user_name ) . '</span></div>';
			}

			$menu[0] = array( $html, 'read', $url, 'user-box', 'adminmenu-container');

		}

	endif;

	add_action('admin_menu', 'wp_admin_theme_cd_userbox');
	
}
	

/*****************************************************************/
/* ADD COMPANY BOX TO LEFT ADMIN MENU */
/*****************************************************************/

if( ! wpat_option('user_box') && wpat_option('company_box') ) {
	
	if ( ! function_exists( 'wp_admin_theme_cd_companybox' ) ) :

		function wp_admin_theme_cd_companybox() {

			global $menu, $user_id, $scheme;

			$blog_name = get_bloginfo( 'name' );
			$site_url = get_bloginfo( 'wpurl' ) . '/';

			if( ! empty( wpat_option('company_box_logo') ) ){
				$company_logo_output = '<img style="width:' . esc_html( wpat_option('company_box_logo_size') ) . 'px" class="company-box-logo" src="' . esc_url( wpat_option('company_box_logo') ) . '" alt="' . esc_attr( $blog_name ) . '">';
			} else {
				$company_logo_output = esc_html__( 'No image selected.', 'wp-admin-theme-cd' );
			}

			$html = '<div class="adminmenu-avatar">' . $company_logo_output . '<div class="adminmenu-user-edit">' . esc_html__( 'Home', 'wp-admin-theme-cd' ) . '</div></div><div class="adminmenu-user-name"><span>' . esc_html( $blog_name ) . '</span></div>';

			$menu[0] = array( $html, 'read', $site_url, 'user-box', 'adminmenu-container');

		}

	endif;

	add_action('admin_menu', 'wp_admin_theme_cd_companybox');
	
}


/*****************************************************************/
/* ADD FEATURED IMAGE COLUMN TO WP ADMIN */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_featured_image_column' ) ) :

	function wp_admin_theme_cd_featured_image_column() {	

		if( wpat_option('thumbnail') ) {
			return false;	
		}		

		if ( ! function_exists( 'wp_admin_theme_cd_featured_image' ) ) :

			// Get the image
			function wp_admin_theme_cd_featured_image( $post_id ) {
				$post_thumbnail_id = get_post_thumbnail_id( $post_id );
				if( $post_thumbnail_id ) {
					$image = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );
					return $image[0];
				}
			}

		endif;


		if ( ! function_exists( 'wp_admin_theme_cd_columns_head' ) ) :

			// Add new column
			function wp_admin_theme_cd_columns_head( $column ) {
				$column['featured_image'] = esc_html__( 'Image', 'wp-admin-theme-cd' );
				return $column;
			}

		endif;


		if ( ! function_exists( 'wp_admin_theme_cd_columns_content' ) ) :

			// Output the image
			function wp_admin_theme_cd_columns_content( $column_name, $post_id ) {
				if( $column_name === 'featured_image' ) {
					$post_featured_image = wp_admin_theme_cd_featured_image( $post_id );
					if( $post_featured_image ) {
						echo '<img src="' . esc_url( $post_featured_image ) . '" />';
					} else {
						echo '<img style="width:55px;height:55px" src="' . wp_admin_theme_cd_path( 'img/no-thumb.jpg' ) . '" alt="' . esc_attr__( 'No Thumbnail', 'wp-admin-theme-cd' ) . '"/>';
					}
				}
			}

		endif;
		

		if ( ! function_exists( 'wp_admin_theme_cd_thumbnail_column' ) ) :

			// Move image column before title column
			function wp_admin_theme_cd_thumbnail_column( $columns ) {
				$new = array();
				foreach( $columns as $key => $title ) {
					if( $key === 'title' ) {
						$new['featured_image'] = esc_html__( 'Image', 'wp-admin-theme-cd' );
					}								
					$new[$key] = $title;
				}
				return $new;
			}

		endif;


		// Restrict the custom column to specific post_types
		$post_types = array( 'post', 'page', 'recipe' );
		if( empty( $post_types ) ) {
			return;
		}

		// Add custom column filter and action
		foreach( $post_types as $post_type ) {
			add_filter( "manage_{$post_type}_posts_columns", 'wp_admin_theme_cd_columns_head' );
			add_filter( "manage_{$post_type}_posts_columns", 'wp_admin_theme_cd_thumbnail_column' );
			add_action( "manage_{$post_type}_posts_custom_column", 'wp_admin_theme_cd_columns_content', 10, 2 );
		}
		
	}

endif;

add_action('plugins_loaded', 'wp_admin_theme_cd_featured_image_column');


/*****************************************************************/
/* ADD ID COL TO WP ADMIN PAGES AND POSTS */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_id_column' ) ) :

	function wp_admin_theme_cd_id_column() {	

		if( wpat_option('post_page_id') ) {
			return false;	
		}	

		if( ! function_exists('wp_admin_theme_cd_posts_columns_id') ) :

			function wp_admin_theme_cd_posts_columns_id( $defaults ) {
				$defaults['wps_post_id'] = esc_html__('ID', 'wp-admin-theme-cd');
				return $defaults;
			}

		endif;
		

		if( ! function_exists('wp_admin_theme_cd_posts_custom_id_columns') ) :

			function wp_admin_theme_cd_posts_custom_id_columns( $column_name, $id ) {
				if( $column_name === 'wps_post_id' ) {
					echo esc_html( $id );
				}
			}

		endif;


		// Restrict the custom column to specific post_types
		$post_types = array( 'post', 'page', 'recipe', 'product' );
		if( empty( $post_types ) ) {
			return;
		}

		// Add custom column filter and action
		foreach( $post_types as $post_type ) {
			add_filter( "manage_{$post_type}_posts_columns", 'wp_admin_theme_cd_posts_columns_id' );
			add_action( "manage_{$post_type}_posts_custom_column", 'wp_admin_theme_cd_posts_custom_id_columns', 10, 2 );
		}
		
	}

endif;

add_action('plugins_loaded', 'wp_admin_theme_cd_id_column');


/*****************************************************************/
/* ADD TAXONOMY COL TO WP ADMIN TAXONOMY PAGES */
/*****************************************************************/

if ( ! function_exists( 'hannah_cd_tax_id_column' ) ) :

	function hannah_cd_tax_id_column() {

		if( wpat_option('post_page_id') ) {
			return false;	
		}	
		
		if( ! function_exists('wp_admin_theme_cd_tax_column_id_head') ) :

			// Add new column
			function wp_admin_theme_cd_tax_column_id_head( $column ) {
				$column['tax_id'] = esc_html__( 'ID', 'wp-admin-theme-cd' );
				return $column;
			}

		endif;


		if( ! function_exists('wp_admin_theme_cd_tax_column_id_content') ) :

			// Output the id
			function wp_admin_theme_cd_tax_column_id_content( $value, $name, $id ) {  
				return 'tax_id' === $name ? $id : $value;
			}

		endif;

		// Restrict the custom column to specific tax_types
		$tax_types = array( 
			'category', 
			'post_tag', 
			'product_cat', 
			'product_tag', 
			'page_category', 
			'page_tag', 
			'recipe_category', 
			'recipe_tag', 
			'recipe_ingredient', 
			'recipe_feature', 
			'recipe_cuisine' 
		);

		if( empty( $tax_types ) ) {
			return;
		}

		foreach( $tax_types as $taxonomy ) {
			add_action( "manage_edit-{$taxonomy}_columns", 'wp_admin_theme_cd_tax_column_id_head' );
			add_filter( "manage_edit-{$taxonomy}_sortable_columns", 'wp_admin_theme_cd_tax_column_id_head' );
			add_filter( "manage_{$taxonomy}_custom_column", 'wp_admin_theme_cd_tax_column_id_content', 11, 3 );
		}
		
	}

endif;

add_action('plugins_loaded', 'hannah_cd_tax_id_column');


/*****************************************************************/
/* OPTION PAGES TITLE */
/*****************************************************************/

if( ! function_exists('wp_admin_theme_cd_title') ) :

	function wp_admin_theme_cd_title() {
		
		echo esc_html__( 'WP Admin Theme CD', 'wp-admin-theme-cd' );
		if ( is_multisite() ) { ?>
			<span style="color:#8b959e;"<?php if( is_rtl() ) { echo ' dir="rtl"'; } ?>> <?php echo ' | ' . esc_html__( 'Current Blog ID', 'wp-admin-theme-cd' ) . ': '. get_current_blog_id(); ?></span>
		<?php }
	
	}

endif;


/*****************************************************************/
/* OPTION PAGES TAB MENU */
/*****************************************************************/

if( ! function_exists('wp_admin_theme_cd_tab_menu') ) :

	function wp_admin_theme_cd_tab_menu( $active_tab ) { ?>
		
		<h2 class="nav-tab-wrapper">
			<?php if( wp_admin_theme_cd_activation_status() ) { ?>
				<a href="<?php echo admin_url( 'tools.php?page=wp-admin-theme-cd&tab=display-options' ); ?>" class="nav-tab <?php echo $active_tab == 'display-options' ? 'nav-tab-active' : ''; ?>">
					<?php echo esc_html__( 'Options', 'wp-admin-theme-cd' ); ?>
				</a>
				<?php if( wpat_option('disable_page_system') != true ) { ?>
					<a href="<?php echo admin_url( 'tools.php?page=wp-admin-theme-cd-sys_info&tab=sys-info' ); ?>" class="nav-tab <?php echo $active_tab == 'sys-info' ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_html__( 'System Info', 'wp-admin-theme-cd' ); ?>
					</a>
				<?php }
				
				if( wpat_option('disable_page_export') != true ) { ?>
					<a href="<?php echo admin_url( 'tools.php?page=wp-admin-theme-cd-export&tab=im-export' ); ?>" class="nav-tab <?php echo $active_tab == 'im-export' ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_html__( 'Im-/Export', 'wp-admin-theme-cd' ); ?>
					</a>
				<?php }										   
															   
				if( is_multisite() && ( wpat_option('disable_page_ms') != true ) && get_current_blog_id() == 1 ) {?>
					<a href="<?php echo admin_url( 'tools.php?page=wp-admin-theme-cd-update-network&tab=multisite' ); ?>" class="nav-tab <?php echo $active_tab == 'multisite' ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_html__( 'Multisite Sync', 'wp-admin-theme-cd' ); ?>
					</a>
				<?php }
			} ?>
			<a href="<?php echo admin_url( 'tools.php?page=wp-admin-theme-cd-purchase-code&tab=activation' ); ?>" class="nav-tab <?php echo $active_tab == 'activation' ? 'nav-tab-active' : ''; ?>">
				<?php if( ! wp_admin_theme_cd_activation_status() ) { ?>
					<span style="color:#d63316" class="dashicons dashicons-no-alt"></span>
				<?php } else { ?>
					<span style="color:#8db51e" class="dashicons dashicons-yes"></span>
				<?php }
				echo esc_html__( 'Activation', 'wp-admin-theme-cd' ); ?>
			</a>
		</h2>

	<?php }

endif;
