<?php 

/*****************************************************************/
/* IMPORT / EXPORT ADMIN PAGE */
/*****************************************************************/

/*****************************************************************/
/* CREATE + ADD OPTION PAGE TO ADMIN MENU */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_export_admin_menu' ) ) :

	function wp_admin_theme_cd_export_admin_menu() {
			
		add_submenu_page(
			NULL, // <-- Disable the menu item
			esc_html__( 'WP Admin Theme CD - Import & Export', 'wp-admin-theme-cd' ),
			esc_html__( 'WPAT Im-/Export', 'wp-admin-theme-cd' ),
			'manage_options',
			'wp-admin-theme-cd-export',
			'wp_admin_theme_cd_export_page'
		);
		
	}

endif;

add_action( 'admin_menu', 'wp_admin_theme_cd_export_admin_menu' );


/*****************************************************************/
/* EXPORT OPTIONS SETTINGS */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_export_settings' ) ) :

	function wp_admin_theme_cd_export_settings() {
		
		if( ! isset( $_POST['wpat_start_export'] ) || 'export_settings' != $_POST['wpat_start_export'] ) {
			return;
		}
			
		if( ! wp_verify_nonce( $_POST['wpat-export'], 'wpat-export' ) ) {
			return;
		}
		
		if( ! current_user_can( 'manage_options' ) ) {
			return;
		}
			
		// Get all WP options data
		//$options = get_alloptions(); 

		// Get specific options data
		//$options = array( 'test' => get_option('test'), 'test2' => get_option('test2') );

		// Get all options data from WP Admin Theme plugin
		$options = array( 'wp_admin_theme_settings_options' => get_option('wp_admin_theme_settings_options') );
		if( is_multisite() ) {
			$options = array( 'wp_admin_theme_settings_options' => get_blog_option( get_current_blog_id(), 'wp_admin_theme_settings_options', array() ) );
		}		

		$save_options = array();

		foreach( $options as $key => $value ) {
			$value = maybe_unserialize( $value );
			$save_options[$key] = $value;
		}

		// Encode data into json data
		$json_file = json_encode( $save_options );
		
		// Get the blog name
		$blogname = str_replace(' ', '', get_option('blogname'));

		// Get the blog charset
		$charset = get_option('blog_charset');
		
		// Get the current data
		$date = date('m-d-Y');

		// Namming the filename will be generated
		$json_name = $blogname . '-' . $date;		
		
		header( "Content-Type: application/json; charset=$charset" );
		header( "Content-Disposition: attachment; filename=$json_name.json" );
		header( "Pragma: no-cache" );
    	header( "Expires: 0" );
		echo $json_file;
		
		exit();
		
	}

endif;
	
add_action( 'admin_init', 'wp_admin_theme_cd_export_settings' );


/*****************************************************************/
/* IMPORT OPTIONS SETTINGS */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_import_settings' ) ) :

	function wp_admin_theme_cd_import_settings() {

		if( ! isset( $_POST['wpat_start_import'] ) || $_POST['wpat_start_import'] != 'import_settings' ) {
			return;
		}

		if( ! wp_verify_nonce( $_POST['wpat-import'], 'wpat-import' ) ) {
			return;
		}

		if( ! current_user_can( 'manage_options' ) ) {
			return;
		}		
		
		// Get the name of file
		$file = $_FILES['import']['name'];
		
		// Check for uploaded file
		if( empty( $file ) ) {
			return esc_html__( 'Please upload a file to import.', 'wp-admin-theme-cd' );
		}		

		// Get extension of file
		$tmp = explode('.', $file);
		$file_extension = strtolower( end( $tmp ) );

		// Check for the correct file extension
		if( $file_extension != 'json' ) {
			return esc_html__( 'Please upload a valid .json file.', 'wp-admin-theme-cd' );	
		}
		
		// Get size of file
		$file_size = $_FILES['import']['size'];

		// Check for the file size is not to large
		if( $file_size > 500000 ) { // <-- 500000 bytes
			return esc_html__( 'Your uploaded file is too large.', 'wp-admin-theme-cd' );	
		}

		// Ensure uploaded file is JSON file type and the size not over 500000 bytes
		if( ! empty( $file ) && ( $file_extension == 'json' ) && ( $file_size < 500000 ) ) {
			
			$encode_options = file_get_contents( $_FILES['import']['tmp_name'] );
			$options = json_decode( $encode_options, true );
			
			foreach( $options as $key => $value ) {
				if( is_multisite() ) {
					update_blog_option( get_current_blog_id(), $key, $value);
				} else {
					update_option( $key, $value );
				}									
			}
			
			return esc_html__( 'All options are restored successfully.', 'wp-admin-theme-cd' );
			
		}
			
		// Redirect to option main page
		//wp_safe_redirect( admin_url( 'tools.php?page=wp-admin-theme-cd&tab=display-options' ) );
		//exit();
		
	}

endif;

add_action( 'admin_init', 'wp_admin_theme_cd_import_settings' );


/*****************************************************************/
/* CREATE OPTION PAGE OUTPUT */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_export_page' ) ) :

	function wp_admin_theme_cd_export_page() { ?>

		<div class="wrap">

			<h1>
				<?php echo wp_admin_theme_cd_title(); ?>
			</h1> 

			<?php if( wp_admin_theme_cd_activation_status() ) {

				// Output the tab menu
				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'im-export'; 
				echo wp_admin_theme_cd_tab_menu( $active_tab ); ?>

				<?php if( wp_admin_theme_cd_import_settings() ) { ?>
					<div class="updated">
						<p>
							<strong><?php echo esc_html( wp_admin_theme_cd_import_settings() ); ?></strong>
						</p>
					</div>
				<?php } ?>

				<h2>
					<?php esc_html_e( 'Export', 'wp-admin-theme-cd' ); ?> <?php esc_html_e( 'Options', 'wp-admin-theme-cd' ); ?>
				</h2>

				<p>
					<?php esc_html_e( 'When you click the Export button, the system will generate a JSON file for you to save on your computer.', 'wp-admin-theme-cd' ); ?>
					<?php esc_html_e( 'This backup file contains all WP Admin Theme configution and setting options from this WordPress installation.', 'wp-admin-theme-cd' ); ?>
				</p>
			
				<p>
					<?php esc_html_e( 'After exporting, you can either use the JSON file to restore your settings on this site again or another WordPress site.', 'wp-admin-theme-cd' ); ?>
				</p>

				<form method="post">
					<p class="submit">
						<?php wp_nonce_field( 'wpat-export', 'wpat-export' ); ?>
						<input type="hidden" name="wpat_start_export" value="export_settings" />
						<?php submit_button( __( 'Export WP Admin Theme options', 'wp-admin-theme-cd' ), 'secondary', 'submit', false ); ?>
					</p>
				</form>

				<h2>
					<?php esc_html_e( 'Import', 'wp-admin-theme-cd' ); ?> <?php esc_html_e( 'Options', 'wp-admin-theme-cd' ); ?>
				</h2>

				<p>
					<?php esc_html_e( 'Click the Browse button and choose a JSON file.', 'wp-admin-theme-cd' ); ?>
				</p>
			
				<p>
					<?php esc_html_e( 'Press the Import button restore all options.', 'wp-admin-theme-cd' ); ?>
				</p>

				<form method="post" enctype="multipart/form-data">
					<p>						
						<input type="file" name="import" />		
					</p>
					
					<p class="submit">
						<?php wp_nonce_field('wpat-import', 'wpat-import'); ?>
						<input type="hidden" name="wpat_start_import" value="import_settings" />
						<?php submit_button( __( 'Import options', 'wp-admin-theme-cd' ), 'primary', 'submit', false ); ?>
					</p>
				</form>

			<?php } else {
		
				echo wp_admin_theme_cd_plugin_activation_message();
		
			} ?>

		</div>

	<?php }

endif;
