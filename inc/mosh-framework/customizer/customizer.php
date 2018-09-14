<?php 
// Block direct access
if( !defined( 'ABSPATH' ) ){
	exit( 'Direct script access denied.' );
}
/**
 * @Packge 	   : Mosh
 * @Version    : 1.0
 * @Author 	   : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 */
	
	function mosh_customize_register( $wp_customize ) {
	
		// Add Theme option panel
		$wp_customize->add_panel( 'mosh_options_panel',
		  array(
			  'priority'       => 24,
			  'capability'     => 'edit_theme_options',
			  'theme_supports' => '',
			  'title'          => esc_html__( 'Theme options', 'mosh' )
		  )
		);
	   
	   
		/**************************
			General Section
		***************************/
		
		$wp_customize->add_section( 'mosh_general_options_section', 
			array(
				'title'       => esc_html__( 'General', 'mosh' ), 
				'priority'    => 35,
				'capability'  => 'edit_theme_options', 
				'panel'    	  => 'mosh_options_panel',
			) 
		);
         		
		// Preloader option add settings
		$wp_customize->add_setting( 'mosh-preloader-toggle-settings',
			array(
				'default'    => '', 
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback'	=> 'mosh_sanitize_checkbox' 
			) 
		); 
		// Preloader option add control
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize,
				'mosh_enable_preloader',
				array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Preloader On/Off', 'mosh' ),
					'settings'   => 'mosh-preloader-toggle-settings',
					'description' => esc_html__( 'Toggle the preloader active.', 'mosh' ),
					'section'     => 'mosh_general_options_section',
				)
			)
		);
        
       	//  = Preloader Background Color Picker
		
		$wp_customize->add_setting('mosh_preloaderbgcolor', array(
			'default'           => '#211b31',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
	 
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
			$wp_customize, 
			'mosh_preloaderbgcolor_ctrl', 
			array(
			'label'    => __('Preloader Background Color', 'mosh'),
			'section'  => 'mosh_general_options_section',
			'settings' => 'mosh_preloaderbgcolor',
		)));
		
       	//  = Preloader Border Color Picker
		
		$wp_customize->add_setting('mosh_loaderbordcolor', array(
			'default'           => '#f1f2f3',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
	 
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
			$wp_customize, 
			'mosh_loaderbordcolor_ctrl', 
			array(
			'label'    => __('Preloader Border Color', 'mosh'),
			'section'  => 'mosh_general_options_section',
			'settings' => 'mosh_loaderbordcolor',
		)));

       	//  = Preloader Active Border Color Picker
		
		$wp_customize->add_setting('mosh_loaderbordactivecolor', array(
			'default'           => '#4a7aec',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
	 
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
			$wp_customize, 
			'mosh_loaderbordactivecolor_ctrl', 
			array(
			'label'    => __('Preloader Border Active Color', 'mosh'),
			'section'  => 'mosh_general_options_section',
			'settings' => 'mosh_loaderbordactivecolor',
		)));	       		
		
		//  = Theme Main Color Picker
		
		$wp_customize->add_setting('mosh_themecolor', array(
			'default'           => '#4a7aec',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
	 
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
			$wp_customize, 
			'mosh_themecolor_ctrl', 
			array(
			'label'    => __('Theme Main Color', 'mosh'),
			'section'  => 'mosh_general_options_section',
			'settings' => 'mosh_themecolor',
		)));
       		
		
		//  = Theme Secondary Color Picker
		
		$wp_customize->add_setting('mosh_themesecondcolor', array(
			'default'           => '#1a3c8d',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
	 
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'mosh_themesecondcolor_ctrl', 
			array(
			'label'    => __('Theme Secondary Color', 'mosh'),
			'section'  => 'mosh_general_options_section',
			'settings' => 'mosh_themesecondcolor',
		)));

	   	// Instagram Access Token settings
		$wp_customize->add_setting('mosh_igaccess_token', array(
			'default'        => '',
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
			'sanitize_callback' => 'mosh_sanitize_nohtml'
	 
		));
		// Header Access Token control
		$wp_customize->add_control('mosh_igaccess_token', array(
			'label'      => __('Instagram Access Token', 'mosh'),
			'section'    => 'mosh_general_options_section',
			'settings'   => 'mosh_igaccess_token',
			'description' => esc_html__( 'Set instagram access token.', 'mosh' ),
		));
		// Map Api option add settings
		$wp_customize->add_setting('mosh_map_apikey', array(
			'default'        => '',
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
			'transport'  	 => 'refresh',
			'sanitize_callback' => 'mosh_sanitize_nohtml'
	 
		));
		
		// Map Api control
		$wp_customize->add_control('mosh_map_apikey_ctrl', array(
			'label'      => esc_html__( 'Google Map Api Key', 'mosh' ),
			'section'    => 'mosh_general_options_section',
			'settings'   => 'mosh_map_apikey',
		));		
		/**************************
			Header Menu Section
		***************************/
		
		$wp_customize->add_section( 'mosh_headertop_options_section', 
			array(
				'title'       => esc_html__( 'Header Settings', 'mosh' ), 
				'priority'    => 35,
				'capability'  => 'edit_theme_options', 
				'panel'    	  => 'mosh_options_panel',
				'description' => esc_html__('Allows you to settings header top elements.', 'mosh'), 
			) 
		);
        //  =======================================
		//  = Header Button #1 =
		//  =======================================
		// Header button #1 text settings
		$wp_customize->add_setting('mosh_btnone_text', array(
			'default'        => '',
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
			'sanitize_callback'	=> 'mosh_sanitize_nohtml'
	 
		));
		// Header button #1 text control
		$wp_customize->add_control('mosh_btnonetext_ctrl', array(
			'label'      => __('Button #1 Text', 'mosh'),
			'section'    => 'mosh_headertop_options_section',
			'settings'   => 'mosh_btnone_text',
		));	
         		
		// Header button #1 url settings
		$wp_customize->add_setting('mosh_btnone_url', array(
			'default'        => '',
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
			'sanitize_callback'	=> 'mosh_sanitize_url'
	 
		));
		// Header button #1 url control
		$wp_customize->add_control('mosh_btnoneurl_ctrl', array(
			'label'      => __('Button #1 Url', 'mosh'),
			'section'    => 'mosh_headertop_options_section',
			'settings'   => 'mosh_btnone_url',
		));	
        
        //  =======================================
		//  = Header Button #2 =
		//  =======================================

		// Header button #2 text settings
		$wp_customize->add_setting('mosh_btntwo_text', array(
			'default'        => '',
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
			'sanitize_callback'	=> 'mosh_sanitize_nohtml'
	 
		));
		// Header button #2 text control
		$wp_customize->add_control('mosh_btntwotext_ctrl', array(
			'label'      => __('Button #2 Text', 'mosh'),
			'section'    => 'mosh_headertop_options_section',
			'settings'   => 'mosh_btntwo_text',
		));	
         		
		// Header button #2 url settings
		$wp_customize->add_setting('mosh_btntwo_url', array(
			'default'        => '',
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
			'sanitize_callback'	=> 'mosh_sanitize_url'
	 
		));
		// Header button #2 url control
		$wp_customize->add_control('mosh_btntwourl_ctrl', array(
			'label'      => __('Button #2 Url', 'mosh'),
			'section'    => 'mosh_headertop_options_section',
			'settings'   => 'mosh_btntwo_url',
		));	
        
		
		//  ======+++++=================================
		//  = Header top search form show / Hide opt =
		//  =================+++++======================
		
		$wp_customize->add_setting( 'mosh-searchopt-toggle-settings',
			array(
				'default'    => false, 
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => 'mosh_sanitize_checkbox'
			) 
		); 
		
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize,
				'mosh_enable_searchopt',
				array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Header Search Option Show/Hide', 'mosh' ),
					'settings'   => 'mosh-searchopt-toggle-settings',
					'section'     => 'mosh_headertop_options_section',
				)
			)
		);
		
		//  =====================================================
		//  = Header Nav Bar Background Color Picker              =
		//  =====================================================
		$wp_customize->add_setting('mosh_header_navbar_bgColor', array(
			'default'           => '',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
	 
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'header_navbar_bgColor', 
			array(
			'label'    => __('Header Nav Bar Background Color', 'mosh'),
			'section'  => 'mosh_headertop_options_section',
			'settings' => 'mosh_header_navbar_bgColor',
		)));
		
		//  ============================================================
		//  = Header Sticky  Nav Bar Background Color Picker    =
		//  ============================================================
		$wp_customize->add_setting('mosh_header_navbarsticky_bgColor', array(
			'default'           => '',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
	 
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'header_navbarsticky_bgColor', 
			array(
			'label'    => __('Header Sticky Nav Bar Background Color', 'mosh'),
			'section'  => 'mosh_headertop_options_section',
			'settings' => 'mosh_header_navbarsticky_bgColor',
		)));
		
		//  ==============================================
		//  	= Header Nav Bar Menu Color Picker   =
		//  ==============================================
		$wp_customize->add_setting('mosh_header_navbar_menuColor', array(
			'default'           => '#fff',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
	 
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'header_navbar_menuColor', 
			array(
			'label'    => __('Header Nav Bar Menu Color', 'mosh'),
			'section'  => 'mosh_headertop_options_section',
			'settings' => 'mosh_header_navbar_menuColor',
		)));

		//  ===================================================
		//  	= Header Nav Bar Menu Hover Color Picker   =
		//  ===================================================
		$wp_customize->add_setting('mosh_header_navbar_menuHovColor', array(
			'default'           => '#4a7aec',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
	 
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'header_navbar_menuHoverColor', 
			array(
			'label'    => __('Header Nav Bar Menu Hover Color', 'mosh'),
			'section'  => 'mosh_headertop_options_section',
			'settings' => 'mosh_header_navbar_menuHovColor',
		)));
		
		//  ==============================================
		//  	= Header Nav Bar Menu Color Picker   =
		//  ==============================================
		$wp_customize->add_setting('mosh_header_sticky_navbar_menuColor', array(
			'default'           => '#fff',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
	 
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'header_sticky_navbar_menuColor', 
			array(
			'label'    => __('Sticky Header Nav Bar Menu Color', 'mosh'),
			'section'  => 'mosh_headertop_options_section',
			'settings' => 'mosh_header_sticky_navbar_menuColor',
		)));

		//  ===================================================
		//  	= Header Nav Bar Menu Hover Color Picker   =
		//  ===================================================
		$wp_customize->add_setting('mosh_header_sticky_navbar_menuHovColor', array(
			'default'           => '#4a7aec',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
	 
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'header_sticky_navbar_menuHoverColor', 
			array(
			'label'    => __(' Sticky Header Nav Bar Menu Hover Color', 'mosh'),
			'section'  => 'mosh_headertop_options_section',
			'settings' => 'mosh_header_sticky_navbar_menuHovColor',
		)));

		//  = Page Header Background Color Picker
		
		$wp_customize->add_setting('mosh_headerbgcolor', array(
			'default'           => '#211b31',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
	 
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'mosh_headerbgcolor_ctrl', 
			array(
			'label'    => __('Header Background Color', 'mosh'),
			'section'  => 'colors',
			'settings' => 'mosh_headerbgcolor',
		)));	
		/**************************
			Blog Section
		***************************/

		$wp_customize->add_section( 'mosh_blog_options_section', 
			array(
				'title'       => esc_html__( 'Blog', 'mosh' ), 
				'priority'    => 36,
				'capability'  => 'edit_theme_options', 
				'panel'    	  => 'mosh_options_panel',
				'description' => esc_html__('Allows you to settings for blog post excerpt and sidebar position.', 'mosh'), 
			) 
		);
        // Post excerpt	settings
		$wp_customize->add_setting('mosh_post_excerpt', array(
			'default'        => '',
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
			'sanitize_callback' => 'mosh_sanitize_number_absint'
	 
		));
		// Post excerpt	control
		$wp_customize->add_control('mosh_post_excerpt_ctrl', array(
			'label'      => __('Set Post Excerpt', 'mosh'),
			'section'    => 'mosh_blog_options_section',
			'settings'   => 'mosh_post_excerpt',
		));	
		// blog sidebar settings
		$wp_customize->add_setting( 'mosh-blog-sidebar-settings',
			array(
				'default'    => '', 
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => 'wp_kses_post'
			) 
		); 
		// Blog sidebar control
		$wp_customize->add_control(
			new Epsilon_Control_Layouts(
				$wp_customize,
				'mosh_blog_sidebar_position',
				array(
					'type'        => 'epsilon-layouts',
					'label'       => esc_html__( 'Blog Sidebar Layout', 'mosh' ),
					'settings'    => 'mosh-blog-sidebar-settings',
					'section'     => 'mosh_blog_options_section',
					'layouts'  => array(
						1 => MOSH_DIR_URI . 'inc/mosh-framework/epsilon-framework/assets/img/one-column.png',
						2 => MOSH_DIR_URI . 'inc/mosh-framework/epsilon-framework/assets/img/epsilon-section-titleright.jpg',
						3 => MOSH_DIR_URI . 'inc/mosh-framework/epsilon-framework/assets/img/epsilon-section-titleleft.jpg',
					),
					'default'  => array(
						'columnsCount' => 2,
					),
					'min_span' => 4,
					'fixed'    => true,
				)
			)
		);
		/**************************
			404 Page
		***************************/

		$wp_customize->add_section( 'mosh_fof_options_section', 
			array(
				'title'       => esc_html__( '404 Page Settings', 'mosh' ), 
				'priority'    => 36,
				'capability'  => 'edit_theme_options', 
				'panel'    	  => 'mosh_options_panel',
				'description' => esc_html__('Allows you to settings for 404 page element.', 'mosh'), 
			) 
		);
         		
		// 404 text one option add settings
		$wp_customize->add_setting('mosh_fof_text_one', array(
			'default'        => esc_html__( 'Ooops 404 Error !', 'mosh' ),
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
			'transport'  	 => 'refresh',
			'sanitize_callback' => 'mosh_sanitize_nohtml'
	 
		));
		
		// 404 text one control
		$wp_customize->add_control('mosh_fof_text_one_ctrl', array(
			'label'      => esc_html__( '404 Text #1', 'mosh' ),
			'section'    => 'mosh_fof_options_section',
			'settings'   => 'mosh_fof_text_one',
		));	
         		
		// 404 text one option add settings
		$wp_customize->add_setting( 'mosh_fof_text_two', array(
			'default'        => esc_html__( 'Either something went wrong or the page dosen\'t exist anymore.', 'mosh' ),
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
			'transport'  	 => 'refresh',
			'sanitize_callback' => 'mosh_sanitize_nohtml',
	 
		));
		
		// 404 text one control
		$wp_customize->add_control('mosh_fof_text_two_ctrl', array(
			'label'      => esc_html__( '404 Text #2', 'mosh' ),
			'section'    => 'mosh_fof_options_section',
			'settings'   => 'mosh_fof_text_two',
		));	
		// 404 page text 1 color setting
		$wp_customize->add_setting('mosh_fof_textonecolor_settings', array(
			'default'           => '#404551',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color',
	 
		));
		// 404 page text 1 color control
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'mosh_fof_textonecolor_ctrl', 
			array(
			'label'    => __('404 Page Text #1 Color', 'mosh'),
			'section'  => 'mosh_fof_options_section',
			'settings' => 'mosh_fof_textonecolor_settings',
		)));
		// 404 page text 2 color setting
		$wp_customize->add_setting('mosh_fof_texttwocolor_settings', array(
			'default'           => '#abadbe',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
		// 404 page text 2 color control
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'mosh_fof_texttwocolor_ctrl', 
			array(
			'label'    => __('404 Page Text #2 Color', 'mosh'),
			'section'  => 'mosh_fof_options_section',
			'settings' => 'mosh_fof_texttwocolor_settings',
		)));
		// 404 page background color setting
		$wp_customize->add_setting('mosh_fof_bgcolor_settings', array(
			'default'           => '#fff',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
		// 404 page background color control
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'mosh_fof_bgcolor_ctrl', 
			array(
			'label'    => __('404 Page Background Color', 'mosh'),
			'section'  => 'mosh_fof_options_section',
			'settings' => 'mosh_fof_bgcolor_settings',
		)));
		/**************************
			Footer Section
		***************************/

		$wp_customize->add_section( 'mosh_footer_options_section', 
			array(
				'title'       => esc_html__( 'Footer', 'mosh' ), 
				'priority'    => 36,
				'capability'  => 'edit_theme_options', 
				'panel'    	  => 'mosh_options_panel',
				'description' => esc_html__('Allows you to settings for footer widget and footer widget bottom.', 'mosh'), 
			) 
		);
         		
		// Footer Widget Show/Hide option add settings
		$wp_customize->add_setting( 'mosh-widget-toggle-settings',
			array(
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => 'mosh_sanitize_checkbox'
			) 
		); 
		// Footer widget show/hide option add control
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize,
				'mosh_widget_enable_preloader',
				array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Footer Widget show/hide', 'mosh' ),
					'settings'   => 'mosh-widget-toggle-settings',
					'section'     => 'mosh_footer_options_section',
				)
			)
		);

         		
		// Footer social media option add settings
		$wp_customize->add_setting( 'mosh-footersocial-toggle-settings',
			array(
				'default'    => '', 
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => 'mosh_sanitize_checkbox'
			) 
		); 
		// Footer social media option add control
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize,
				'mosh_footersocial_media_toggle',
				array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Footer Bottom Social Media show/Hide', 'mosh' ),
					'settings'   => 'mosh-footersocial-toggle-settings',
					'section'     => 'mosh_footer_options_section',
				)
			)
		);
		
		// Footer copy right text add settings
		$wp_customize->add_setting( 'mosh-copyright-text-settings',
			array(
				'default'    => '', 
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => 'mosh_sanitize_html' 
			) 
		); 
		// Footer copy right text add control
		$wp_customize->add_control(
			new Epsilon_Control_Text_Editor(
				$wp_customize,
				'mosh_copyright_text',
				array(
					'type'        => 'epsilon-text-editor',
					'label'       => esc_html__( 'Footer Copy Right Text', 'mosh' ),
					'settings'   => 'mosh-copyright-text-settings',
					'section'     => 'mosh_footer_options_section',
				)
			)
		);
		
		$wp_customize->selective_refresh->add_partial( 'mosh-copyright-text-settings', 
		array( 'selector' => '.copyright-text' ) );
		
		// Footer widget background color 
		$wp_customize->add_setting('mosh_footer_bgColor_settings', array(
			'default'           => '#211b31',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
		// Footer widget background color ctrl
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'header_footer_bgColor', 
			array(
			'label'    => __('Footer Widget Background Color', 'mosh'),
			'section'  => 'mosh_footer_options_section',
			'settings' => 'mosh_footer_bgColor_settings',
		)));
		
		// Footer widget text Color 
		$wp_customize->add_setting('mosh_footer_wtcolor_settings', array(
			'default'           => '#888888',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
		// Footer widget text color ctrl
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'mosh_footer_wtextColor', 
			array(
			'label'    => __('Footer Widget Text Color', 'mosh'),
			'section'  => 'mosh_footer_options_section',
			'settings' => 'mosh_footer_wtcolor_settings',
		)));
		
		// Footer widget title color setting
		$wp_customize->add_setting('mosh_footer_widgettitlecolor_settings', array(
			'default'           => '#222',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
		// Footer widget title color control
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'header_footer_widgettitlecolor', 
			array(
			'label'    => __('Footer Widget Title Color', 'mosh'),
			'section'  => 'mosh_footer_options_section',
			'settings' => 'mosh_footer_widgettitlecolor_settings',
		)));
		
		// Footer widget anchor Color 
		$wp_customize->add_setting('mosh_footer_wanchorcolor_settings', array(
			'default'           => '#888888',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
		// Footer widget anchor Color
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'header_footer_wanchorcolor', 
			array(
			'label'    => __('Footer Widget Anchor Color', 'mosh'),
			'section'  => 'mosh_footer_options_section',
			'settings' => 'mosh_footer_wanchorcolor_settings',
		)));
		
		// Footer widget anchor hover Color 
		$wp_customize->add_setting('mosh_footer_wanchorhovcolor_settings', array(
			'default'           => '#888888',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
		// Footer widget anchor hover Color
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'header_footer_anchorhovcolor', 
			array(
			'label'    => __('Footer Widget Anchor Hover Color', 'mosh'),
			'section'  => 'mosh_footer_options_section',
			'settings' => 'mosh_footer_wanchorhovcolor_settings',
		)));
		
		// Footer bottom background color 
		$wp_customize->add_setting('mosh_footerbtm_bgColor_settings', array(
			'default'           => '#191426',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
		// Footer bottom background color ctrl
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'header_footerbtm_bgColor', 
			array(
			'label'    => __('Footer Copyright Area Background Color', 'mosh'),
			'section'  => 'mosh_footer_options_section',
			'settings' => 'mosh_footerbtm_bgColor_settings',
		)));
		// Footer Copy Right Text Color 
		$wp_customize->add_setting('mosh_footer_copyrighttextcolor_settings', array(
			'default'           => '#abadbe',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
		// Footer Copy Right Text Color
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'header_footer_copyrighttextcolor', 
			array(
			'label'    => __('Footer Copy Right Text Color', 'mosh'),
			'section'  => 'mosh_footer_options_section',
			'settings' => 'mosh_footer_copyrighttextcolor_settings',
		)));

		// Footer Social Icon Color 
		$wp_customize->add_setting('mosh_footer_socialiconcolor_settings', array(
			'default'           => '#abadbe',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
		// Footer Social Icon Color Ctrl
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'footer_socialiconcolor', 
			array(
			'label'    => __('Footer Social Icon Color', 'mosh'),
			'section'  => 'mosh_footer_options_section',
			'settings' => 'mosh_footer_socialiconcolor_settings',
		)));


		// Footer Social Icon Hover Color 
		$wp_customize->add_setting('mosh_footer_socialiconhovercolor_settings', array(
			'default'           => '#fff',
			'capability'        => 'edit_theme_options',
			'type'           	=> 'theme_mod',
			'transport'  		=> 'refresh',
			'sanitize_callback' => 'mosh_sanitize_hex_color'
	 
		));
		// Footer Social Icon Hover Color Ctrl
		$wp_customize->add_control( 
			new WP_Customize_Color_Control(
			$wp_customize, 
			'footer_socialiconhovercolor', 
			array(
			'label'    => __('Footer Social Icon Hover Color', 'mosh'),
			'section'  => 'mosh_footer_options_section',
			'settings' => 'mosh_footer_socialiconhovercolor_settings',
		)));
		
		
		
	}
	add_action( 'customize_register', 'mosh_customize_register' );

function mosh_customizer_js_load() {
	wp_enqueue_script( 'mosh-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-controls' ), '1.0', true );
	$mosh_customizer             = array();
	$mosh_customizer['site_url'] = site_url();
	$mosh_customizer['blog']     = get_post_type_archive_link( 'post' );
	wp_localize_script( 'mosh-customizer', 'MoshCustomizer', $mosh_customizer );
}
add_action( 'customize_controls_enqueue_scripts', 'mosh_customizer_js_load', 99 );