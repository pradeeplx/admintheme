<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    // This is your option name where all the Redux data is stored.
    $opt_name = "phadkelabs";

    // This line is only for altering the demo. Can be easily removed.
    $opt_name = apply_filters( 'redux_demo/opt_name', $opt_name );

    /*
     *
     * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
     *
     */

    $sampleHTML = '';
    if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
        Redux_Functions::initWpFilesystem();

        global $wp_filesystem;

        $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
    }

    // Background Patterns Reader
    $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
    $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
    $sample_patterns      = array();
    
    if ( is_dir( $sample_patterns_path ) ) {

        if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
            $sample_patterns = array();

            while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                    $name              = explode( '.', $sample_patterns_file );
                    $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                    $sample_patterns[] = array(
                        'alt' => $name,
                        'img' => $sample_patterns_url . $sample_patterns_file
                    );
                }
            }
        }
    }

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => __( 'Phadkelabs Option', 'redux-framework-demo' ),
        'page_title'           => __( 'Theme Options', 'redux-framework-demo' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => false,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => true,
        // Show the time the page took to load, etc
        'update_notice'        => true,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $args['admin_bar_links'][] = array(
        'id'    => 'redux-docs',
        'href'  => 'http://docs.reduxframework.com/',
        'title' => __( 'Documentation', 'redux-framework-demo' ),
    );

    $args['admin_bar_links'][] = array(
        //'id'    => 'redux-support',
        'href'  => 'https://github.com/ReduxFramework/redux-framework/issues',
        'title' => __( 'Support', 'redux-framework-demo' ),
    );

    $args['admin_bar_links'][] = array(
        'id'    => 'redux-extensions',
        'href'  => 'reduxframework.com/extensions',
        'title' => __( 'Extensions', 'redux-framework-demo' ),
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'][] = array(
        'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
        'title' => 'Visit us on GitHub',
        'icon'  => 'el el-github'
        //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://twitter.com/reduxframework',
        'title' => 'Follow us on Twitter',
        'icon'  => 'el el-twitter'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://www.linkedin.com/company/redux-framework',
        'title' => 'Find us on LinkedIn',
        'icon'  => 'el el-linkedin'
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'redux-framework-demo' ), $v );
    } else {
        $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo' );
    }

    // Add content after the form.
    $args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework-demo' );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1', 'redux-framework-demo' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'redux-framework-demo' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */
	 
	  // -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Header Settings', 'redux-framework-demo' ),
        'id'               => 'header-basic',
        //'desc'             => __( 'These are really basic fields!', 'redux-framework-demo' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-home'
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => __( 'Header Logo', 'redux-framework-demo' ),
        'id'               => 'basic-header',
        'subsection'       => true,
        'customizer_width' => '450px',
        //'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/checkbox/" target="_blank">docs.reduxframework.com/core/fields/checkbox/</a>',
        'fields'           => array(
			array(
                'id'       => 'header_logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Upload  header logo', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can upload a header logo. (width: 261px, height:46px)', 'redux-framework-demo' ),
                'subtitle' => __( 'Upload any image to use as header logo.', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/header/Rectangle 1336.png' ),
               
            ),	
			
           
        )
    ) );

	Redux::setSection( $opt_name, array(
        'title'            => __( 'Header Top', 'redux-framework-demo' ),
        'id'               => 'basic-header-topbar',
        'subsection'       => true,
        'customizer_width' => '450px',
        //'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/checkbox/" target="_blank">docs.reduxframework.com/core/fields/checkbox/</a>',
        'fields'           => array(
			array(
                'id'       => 'header_top_bar_title',
                'type'     => 'textarea',
                'title'    => __( 'Topbar Title', 'redux-framework-demo' ),
                'desc'     => __( 'You can change topbar title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change top bar title.', 'redux-framework-demo' ),
                'default'  => __( 'Blood collection at home facilities across Mumbai, Navi Mumbai & Thane
								disctrict', 'redux-framework-demo' )               
            ),
			array(
                'id'       => 'topbar_email',
                'type'     => 'text',
                'title'    => __( 'Topbar Email', 'redux-framework-demo' ),
                'desc'     => __( 'You can change topbar email', 'redux-framework-demo' ),
                'subtitle' => __( 'Change top bar email.', 'redux-framework-demo' ),
                'default'  => __( 'contact@phadkelabs.com', 'redux-framework-demo' )               
            ),	
			array(
                'id'       => 'topbar_phone',
                'type'     => 'text',
                'title'    => __( 'Topbar Phone', 'redux-framework-demo' ),
                'desc'     => __( 'You can change topbar phone', 'redux-framework-demo' ),
                'subtitle' => __( 'Change top bar phone.', 'redux-framework-demo' ),
                'default'  => __( '02248900114', 'redux-framework-demo' )               
            ),
			array(
                'id'       => 'topbar_whatsapp',
                'type'     => 'text',
                'title'    => __( 'Topbar Whatsapp', 'redux-framework-demo' ),
                'desc'     => __( 'You can change topbar whatsapp number', 'redux-framework-demo' ),
                'subtitle' => __( 'Change top bar whatsapp number.', 'redux-framework-demo' ),
                'default'  => __( '919819938916', 'redux-framework-demo' )               
            ),	
			
           
        )
    ) );
	
	  // -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Homepage Body', 'redux-framework-demo' ),
        'id'               => 'homepage-body-section',
        //'desc'             => __( 'These are really basic fields!', 'redux-framework-demo' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-screen'
    ) );
	
	
	Redux::setSection( $opt_name, array(
        'title'            => __( 'Banner Intro', 'redux-framework-demo' ),
        'id'               => 'basic-homepage-body-intro',
        'subsection'       => true,
        'customizer_width' => '450px',
        //'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/checkbox/" target="_blank">docs.reduxframework.com/core/fields/checkbox/</a>',
        'fields'           => array(
			array(
                'id'       => 'banner_intro_caption',
                'type'     => 'textarea',
                'title'    => __( 'Intro caption', 'redux-framework-demo' ),
                'desc'     => __( 'You can change intro caption', 'redux-framework-demo' ),
                'subtitle' => __( 'Change intro caption.', 'redux-framework-demo' ),
                'default'  => __( 'You Cannot Separate Passion From Pathology Any More.', 'redux-framework-demo' )               
            ),
			array(
                'id'       => 'banner_intro_details',
                'type'     => 'textarea',
                'title'    => __( 'Intro caption details', 'redux-framework-demo' ),
                'desc'     => __( 'You can change intro details', 'redux-framework-demo' ),
                'subtitle' => __( 'Change intro details.', 'redux-framework-demo' ),
                'default'  => __( 'At Phadke Labs, pathology is not merely a science, it is fine-tuned blend of art, passion, empathy and stringent discipline that adds credibility, authenticity and respect to our tests.', 'redux-framework-demo' )              
            ),
			array(
                'id'       => 'banner_intro_banner',
                'type'     => 'media',
				'url'	   => true,
				'compiler' => 'true',
                'title'    => __( 'Intro banner', 'redux-framework-demo' ),
                'desc'     => __( 'You can change intro banner', 'redux-framework-demo' ),
                'subtitle' => __( 'Upload intro banner.', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/banner/bg.jpg' )            
            ),
			
           
        )
    ) );
	
	// -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title'            => __( 'About Us', 'redux-framework-demo' ),
        'id'               => 'about-us-body-section',
        //'desc'             => __( 'These are really basic fields!', 'redux-framework-demo' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-screen'
    ) );


	Redux::setSection( $opt_name, array(
        'title'            => __( 'Our Legacy', 'redux-framework-demo' ),
        'id'               => 'basic-our-legacy-section',
        'subsection'       => true,
        'customizer_width' => '450px',
        //'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/checkbox/" target="_blank">docs.reduxframework.com/core/fields/checkbox/</a>',
        'fields'           => array(
			array(
                'id'       => 'our_legacy_text_title',
                'type'     => 'text',
                'title'    => __( 'Intro title', 'redux-framework-demo' ),
                'desc'     => __( 'You can change title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the title.', 'redux-framework-demo' ),
                'default'  => __( 'Our Legacy', 'redux-framework-demo' )               
            ),
			array(
                'id'       => 'our_legacy_text',
                'type'     => 'editor',
                'title'    => __( 'Legacy Text', 'redux-framework-demo' ),
                'desc'     => __( 'You can change legacy text', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the legacy text.', 'redux-framework-demo' ),
                'default'  => __( '<p>In 1963, when Dr. Achyut M. Phadke started the Andrology centre in Shivaji Park, Mumbai, It
							was the first testing facility in Maharashtra for male fertility. Half a century later, the
							Centre is still considered to be the pioneers in Male Infertility Testing.</p>
						<span>The Original Mentor of India’s Andrology testing was joined by Dr. Avinash Phadke and Dr. Vandana Phadke who brought in further pathology services with even more dedication and sophisticated processes to help the lab achieve great heights. Dr. Avinash Phadke Pathology Labs, is one of the oldest ISO certified diagnostic laboratory. The lab was the first standalone lab to be accredited by the National Accreditation Board for Testing and Calibration Laboratories (NABL).</span>', 'redux-framework-demo' )               
            ),
			 array(
                'id'       => 'our_legacy_right_photo',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Legacy Right Photo', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can change the photo', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the photo.', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/banner/doctors1.png' ),
               
            ),	
           
        )
    ) );
	
	Redux::setSection( $opt_name, array(
        'title'            => __( 'Pedigree', 'redux-framework-demo' ),
        'id'               => 'basic-pedigree-section',
        'subsection'       => true,
        'customizer_width' => '450px',
        //'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/checkbox/" target="_blank">docs.reduxframework.com/core/fields/checkbox/</a>',
        'fields'           => array(
			array(
                'id'       => 'pedigree_title',
                'type'     => 'text',
                'title'    => __( 'Intro title', 'redux-framework-demo' ),
                'desc'     => __( 'You can change title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the title.', 'redux-framework-demo' ),
                'default'  => __( 'Our Legacy', 'redux-framework-demo' )               
            ),
			array(
                'id'       => 'pedigree_details',
                'type'     => 'editor',
                'title'    => __( 'Pedigree details', 'redux-framework-demo' ),
                'desc'     => __( 'You can change pedigree details', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the pedigree details.', 'redux-framework-demo' ),
                'default'  => __( '<p>A true blue pathology pedigree spanning across three generations of highly qualified doctors.
						</p>
						<span>The legacy has helped us transform into perhaps the most reliable, the most authentic and
							definitely the most respected pathological lab in the country. We have perhaps the highest
							count of qualified doctors, PhD scholars and certified biochemists on our rolls. Dr. Avinash
							Phadke Pathology Labs is not just a testing centre, it is a compelling and most crucial
							haven for thousands of doctors in and around Mumbai who vouch for our test outcomes and for
							their patients’ treatment.</span>
							<br>
							<span>Friedrich Nietzsche once said, “Objection, evasion, joyous distrust, and love of irony are signs of health; everything absolute belongs to pathology.”</span>', 'redux-framework-demo' )               
            ),
			 array(
                'id'       => 'pedigree_left_photo',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Pedigree Left Photo', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can change the photo', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the photo.', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/banner/doctors2.png' ),               
            ),	
           
        )
    ) );

	
	Redux::setSection( $opt_name, array(
        'title'            => __( 'Our Work History', 'redux-framework-demo' ),
        'id'               => 'basic-work-history-section',
        'subsection'       => true,
        'customizer_width' => '450px',
        //'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/checkbox/" target="_blank">docs.reduxframework.com/core/fields/checkbox/</a>',
        'fields'           => array(
			
			
			array(
                'id'       => 'our_history_title_1',
                'type'     => 'textarea',
                'title'    => __( 'History title 1', 'redux-framework-demo' ),
                'desc'     => __( 'You can change title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the title.', 'redux-framework-demo' ),
                'default'  => __( '18 years of NABL <br> accreditation', 'redux-framework-demo' )               
            ),
			 array(
                'id'       => 'our_history_logo_1',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'History logo 1', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can history logo', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/about-srl/year.png' ),               
                'subtitle' => __( 'Change the logo.', 'redux-framework-demo' ),
            ),


			array(
                'id'       => 'our_history_title_2',
                'type'     => 'textarea',
                'title'    => __( 'History title 2', 'redux-framework-demo' ),
                'desc'     => __( 'You can change title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the title.', 'redux-framework-demo' ),
                'default'  => __( 'Completed 56 years<br> this April', 'redux-framework-demo' )               
            ),
			 array(
                'id'       => 'our_history_logo_2',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'History logo 2', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can history logo', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/about-srl/year.png' ),               
                'subtitle' => __( 'Change the logo.', 'redux-framework-demo' ),
            ),


			array(
                'id'       => 'our_history_title_3',
                'type'     => 'textarea',
                'title'    => __( 'History title 3', 'redux-framework-demo' ),
                'desc'     => __( 'You can change title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the title.', 'redux-framework-demo' ),
                'default'  => __( 'Completed 56 years<br> this April', 'redux-framework-demo' )               
            ),
			 array(
                'id'       => 'our_history_logo_3',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'History logo 3', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can history logo', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/about-srl/year.png' ),               
                'subtitle' => __( 'Change the logo.', 'redux-framework-demo' ),
            ),



			array(
                'id'       => 'our_history_title_4',
                'type'     => 'textarea',
                'title'    => __( 'History title 4', 'redux-framework-demo' ),
                'desc'     => __( 'You can change title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the title.', 'redux-framework-demo' ),
                'default'  => __( 'Completed 56 years<br> this April', 'redux-framework-demo' )               
            ),
			 array(
                'id'       => 'our_history_logo_4',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'History logo 4', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can history logo', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/about-srl/year.png' ),               
                'subtitle' => __( 'Change the logo.', 'redux-framework-demo' ),
            ),


			
			array(
                'id'       => 'our_history_title_5',
                'type'     => 'textarea',
                'title'    => __( 'History title 5', 'redux-framework-demo' ),
                'desc'     => __( 'You can change title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the title.', 'redux-framework-demo' ),
                'default'  => __( 'Completed 56 years<br> this April', 'redux-framework-demo' )               
            ),
			 array(
                'id'       => 'our_history_logo_5',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'History logo 5', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can history logo', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/about-srl/year.png' ),               
                'subtitle' => __( 'Change the logo.', 'redux-framework-demo' ),
            ),


			array(
                'id'       => 'our_history_title_6',
                'type'     => 'textarea',
                'title'    => __( 'History title 6', 'redux-framework-demo' ),
                'desc'     => __( 'You can change title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the title.', 'redux-framework-demo' ),
                'default'  => __( 'Completed 56 years<br> this April', 'redux-framework-demo' )               
            ),
			 array(
                'id'       => 'our_history_logo_6',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'History logo 6', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can history logo', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/about-srl/year.png' ),               
                'subtitle' => __( 'Change the logo.', 'redux-framework-demo' ),
            ),


			array(
                'id'       => 'our_history_title_7',
                'type'     => 'textarea',
                'title'    => __( 'History title 7', 'redux-framework-demo' ),
                'desc'     => __( 'You can change title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the title.', 'redux-framework-demo' ),
                'default'  => __( 'Completed 56 years<br> this April', 'redux-framework-demo' )               
            ),
			 array(
                'id'       => 'our_history_logo_7',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'History logo 7', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can history logo', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/about-srl/year.png' ),               
                'subtitle' => __( 'Change the logo.', 'redux-framework-demo' ),
            ),

			array(
                'id'       => 'our_history_title_8',
                'type'     => 'textarea',
                'title'    => __( 'History title 8', 'redux-framework-demo' ),
                'desc'     => __( 'You can change title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the title.', 'redux-framework-demo' ),
                'default'  => __( 'Completed 56 years<br> this April', 'redux-framework-demo' )               
            ),
			 array(
                'id'       => 'our_history_logo_8',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'History logo 8', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can history logo', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/about-srl/year.png' ),               
                'subtitle' => __( 'Change the logo.', 'redux-framework-demo' ),
            ),


			
           
        )
    ) );
	
	
		Redux::setSection( $opt_name, array(
        'title'            => __( 'Lab facilty', 'redux-framework-demo' ),
        'id'               => 'basic-lab-facility-section2',
        'subsection'       => true,
        'customizer_width' => '450px',
        //'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/checkbox/" target="_blank">docs.reduxframework.com/core/fields/checkbox/</a>',
        'fields'           => array(
			array(
                'id'       => 'lab_facility_title',
                'type'     => 'text',
                'title'    => __( 'Lab facility title', 'redux-framework-demo' ),
                'desc'     => __( 'You can change title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the title.', 'redux-framework-demo' ),
                'default'  => __( 'A chain of standalone NABL accredited labs.', 'redux-framework-demo' )               
            ),
			array(
                'id'       => 'lab_facility_subtitle',
                'type'     => 'textarea',
                'title'    => __( 'Lab facility subtitle', 'redux-framework-demo' ),
                'desc'     => __( 'You can change subtitle', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the subtitle.', 'redux-framework-demo' ),
                'default'  => __( 'Our home visit facilities are present throughout Mumbai, Navi Mumbai and Thane District.', 'redux-framework-demo' )               
            ),
			array(
                'id'       => 'lab_facility_details',
                'type'     => 'editor',
                'title'    => __( 'Lab facility details', 'redux-framework-demo' ),
                'desc'     => __( 'You can change lab_facility details', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the lab_facility details.', 'redux-framework-demo' ),
                'default'  => __( 'We have shifted our Central Processing to a State of Art Lab in Mahim which has all tests under one roof. We have started a full-fledged molecular lab in house. It has 5 separate rooms for various Sample processing steps. The molecular test menu is wide and includes infectious and non-infectious diseases', 'redux-framework-demo' )               
            ),
			 array(
                'id'       => 'lab_facility_left_photo',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Lab facility Left photo', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can change the photo', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the photo.', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/our-journey/lab.jpg' ),               
            ),	
           
        )
    ) );
	
	
		Redux::setSection( $opt_name, array(
        'title'            => __( 'Our Nerve Centre', 'redux-framework-demo' ),
        'id'               => 'basic-our-nerve-centre-section',
        'subsection'       => true,
        'customizer_width' => '450px',
        //'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/checkbox/" target="_blank">docs.reduxframework.com/core/fields/checkbox/</a>',
        'fields'           => array(
			array(
                'id'       => 'nerve_center_title',
                'type'     => 'text',
                'title'    => __( 'Our Nerve Centre title', 'redux-framework-demo' ),
                'desc'     => __( 'You can change title', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the title.', 'redux-framework-demo' ),
                'default'  => __( 'Our Nerve Centre', 'redux-framework-demo' )               
            ),
			array(
                'id'       => 'nerve_center_subtitle',
                'type'     => 'textarea',
                'title'    => __( 'Our Nerve Centre subtitle', 'redux-framework-demo' ),
                'desc'     => __( 'You can change subtitle', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the subtitle.', 'redux-framework-demo' ),
                'default'  => __( 'Dr. Avinash Phadke Labs is a chain of standalone NABL accredited labs. Our labs completed 56 years this April, and 18 years of NABL accreditation. Our home visit facilities are present throughout Mumbai, Navi Mumbai and Thane District. ', 'redux-framework-demo' )               
            ),
			array(
                'id'       => 'nerve_center_details',
                'type'     => 'editor',
                'title'    => __( 'Nerve center details', 'redux-framework-demo' ),
                'desc'     => __( 'You can change lab_facility details', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the lab_facility details.', 'redux-framework-demo' ),
                'default'  => __( 'Our Central Processing is located at our state-of-the-art Lab in Mahim, Mumbai which has over 3,000 tests under one roof. Our in-house full-fledged molecular lab has 5 separate rooms for various processing steps that include molecular tests for infectious and non-infectious diseases. We also offer comprehensive panels such as Allergy Testing, Molecular tests for Monsoon fevers and Pre Natal Screening.', 'redux-framework-demo' )               
            ),
			 array(
                'id'       => 'nerve_center_left_photo_1',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Nerve center left photo 1', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can change the photo', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the photo.', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/our-journey/photo1.jpg' ),               
            ),	
           array(
                'id'       => 'nerve_center_left_photo_2',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Nerve center left photo 2', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can change the photo', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the photo.', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/our-journey/photo2.jpg' ),               
            ),
			array(
                'id'       => 'nerve_center_left_photo_3',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Nerve center left photo 3', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can change the photo', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the photo.', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/our-journey/photo3.jpg' ),               
            ),
			
           
        )
    ) );

	Redux::setSection( $opt_name, array(
        'title'            => __( 'Range of services', 'redux-framework-demo' ),
        'id'               => 'basic-range-of-services-section',
        'subsection'       => true,
        'customizer_width' => '450px',
        //'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/checkbox/" target="_blank">docs.reduxframework.com/core/fields/checkbox/</a>',
        'fields'           => array(			
			array(
                'id'       => 'range_off_services_details',
                'type'     => 'editor',
                'title'    => __( 'Services details', 'redux-framework-demo' ),
                'desc'     => __( 'You can change services details', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the services details.', 'redux-framework-demo' ),
                'default'  => __( '<div class="lab-service-intro">
						<h3>Our Range of services</h3>
						<p>we use fully automated modules integrated with the latest technologies.</p>
						<ul>
							<li>In order to achieve faster and more accurate results, we use fully automated modules integrated with the latest technologies.
							</li>
							<li>Cobas c 702, with a throughput of up to 2000 tests/hour for faster, accurate results.
							</li>
							<li>VITEK® MS, utilises Matrix Assisted Laser Desorption Ionization
								Time-of-Flight (MALDI-TOF) technology. This allows us to identify Bacteria, Fungi, TB
								and Virii within minutes of a pure colony growth.</li>
							<li>The dual-target COBAS® AmpliPrep/COBAS® TaqMan® HIV-1 Test, v2.0 enhances the
							reliability of HIV-1 viral load results and provides greater confidence while assessing
							disease and patient management.</li>
							<li>We have a completely automated system of HIV Nucleic acid extraction along with PCR.
							</li>
						</ul>
					</div>' )               
            ),
			 array(
                'id'       => 'nerve_center_left_photo_22',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Nerve center left photo 1', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'You can change the photo', 'redux-framework-demo' ),
                'subtitle' => __( 'Change the photo.', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/about/our-journey/photo1.jpg' ),               
            ),	
        
		
			
           
        )
    ) );



    // -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Basic Fields', 'redux-framework-demo' ),
        'id'               => 'basic-section',
        //'desc'             => __( 'These are really basic fields!', 'redux-framework-demo' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-home'
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => __( 'Home visiting', 'redux-framework-demo' ),
        'id'               => 'basic-checkbox',
        'subsection'       => true,
        'customizer_width' => '450px',
        //'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/checkbox/" target="_blank">docs.reduxframework.com/core/fields/checkbox/</a>',
        'fields'           => array(
           	array(
                'id'       => 'home_visiting_section_caption',
                'type'     => 'textarea',
                'title'    => __( 'Section caption', 'redux-framework-demo' ),
                'desc'     => __( 'You can change section caption', 'redux-framework-demo' ),
                'subtitle' => __( 'Change section caption.', 'redux-framework-demo' ),
                'default'  => __( 'All home visits above the billing of Rs. 500
					will be free.', 'redux-framework-demo' )               
            ), 	
			array(
                'id'       => 'home_visiting_section_subtitle',
                'type'     => 'textarea',
                'title'    => __( 'Section subtitle', 'redux-framework-demo' ),
                'desc'     => __( 'You can change section subtitle', 'redux-framework-demo' ),
                'subtitle' => __( 'Change section subtitle.', 'redux-framework-demo' ),
                'default'  => __( '<p>Home visits charges below the billing of Rs. 500 will be as follows</p>' )               
            ),
			array(
                'id'       => 'home_visiting_section_details',
                'type'     => 'editor',
                'title'    => __( 'Section details', 'redux-framework-demo' ),
                'desc'     => __( 'You can change section details', 'redux-framework-demo' ),
                'subtitle' => __( 'Change section details.', 'redux-framework-demo' ),
                'default'  => __( '
				<ul>
					<li>Rs. 100 per visit (in-case of a test that requires 2 visits, each visit will be charged Rs.100)
					</li>
					<li>Per visit charges will be Rs. 200 on Sundays. Sunday visits will be taken subject to
						availability of technician.</li>
					<li>These rates are applicable for Mumbai, Navi Mumbai & Thane district.</li>
				</ul>', 'redux-framework-demo' )               
            ),
			
			array(
                'id'       => 'banner_intro_banner222',
                'type'     => 'media',
				'url'	   => true,
				'compiler' => 'true',
                'title'    => __( 'Intro banner', 'redux-framework-demo' ),
                'desc'     => __( 'You can change intro banner', 'redux-framework-demo' ),
                'subtitle' => __( 'Upload intro banner.', 'redux-framework-demo' ),
                'default'  => array( 'url' => get_template_directory_uri().'/images/banner/bg.jpg' )            
            ),
       
          
        )
    ) );
    



    Redux::setSection( $opt_name, array(
        'icon'            => 'el el-list-alt',
        'title'           => __( 'Customizer Only', 'redux-framework-demo' ),
        'desc'            => __( '<p class="description">This Section should be visible only in Customizer</p>', 'redux-framework-demo' ),
        'customizer_only' => true,
        'fields'          => array(
            array(
                'id'              => 'opt-customizer-only',
                'type'            => 'select',
                'title'           => __( 'Customizer Only Option', 'redux-framework-demo' ),
                'subtitle'        => __( 'The subtitle is NOT visible in customizer', 'redux-framework-demo' ),
                'desc'            => __( 'The field desc is NOT visible in customizer.', 'redux-framework-demo' ),
                'customizer_only' => true,
                //Must provide key => value pairs for select options
                'options'         => array(
                    '1' => 'Opt 1',
                    '2' => 'Opt 2',
                    '3' => 'Opt 3'
                ),
                'default'         => '2'
            ),
        )
    ) );

    if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
        $section = array(
            'icon'   => 'el el-list-alt',
            'title'  => __( 'Documentation', 'redux-framework-demo' ),
            'fields' => array(
                array(
                    'id'       => '17',
                    'type'     => 'raw',
                    'markdown' => true,
                    'content_path' => dirname( __FILE__ ) . '/../README.md', // FULL PATH, not relative please
                    //'content' => 'Raw content here',
                ),
            ),
        );
        Redux::setSection( $opt_name, $section );
    }
    /*
     * <--- END SECTIONS
     */


    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */

    /*
    *
    * --> Action hook examples
    *
    */

    // If Redux is running as a plugin, this will remove the demo notice and links
    //add_action( 'redux/loaded', 'remove_demo' );

    // Function to test the compiler hook and demo CSS output.
    // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
    //add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

    // Change the arguments after they've been declared, but before the panel is created
    //add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

    // Change the default value of a field after it's been set, but before it's been useds
    //add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

    // Dynamically add a section. Can be also used to modify sections/fields
    //add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');

    /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values ); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
        }
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $field['msg']    = 'your custom error message';
                $return['error'] = $field;
            }

            if ( $warning == true ) {
                $field['msg']      = 'your custom warning message';
                $return['warning'] = $field;
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => __( 'Section via hook', 'redux-framework-demo' ),
                'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo' ),
                'icon'   => 'el el-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }

    /**
     * Removes the demo link and the notice of integrated demo from the redux-framework plugin
     */
    if ( ! function_exists( 'remove_demo' ) ) {
        function remove_demo() {
            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                remove_filter( 'plugin_row_meta', array(
                    ReduxFrameworkPlugin::instance(),
                    'plugin_metalinks'
                ), null, 2 );

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
            }
        }
    }

