<?php 
/**
 * @Packge     : Mosh
 * @Version    : 1.0
 * @Author     : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 * Customizer section fields
 *
 */

/***********************************
 * General Section Fields
 ***********************************/

// Preloader toggle field
Epsilon_Customizer::add_field(
    'mosh-preloader-toggle-settings',
    array(
        'type'        => 'epsilon-toggle',
        'label'       => esc_html__( 'Preloader On/Off', 'mosh' ),
        'description' => esc_html__( 'Toggle to display preloader.', 'mosh' ),
        'section'     => 'mosh_general_options_section',
        'default'     => true,
    )
);
// Preloader background color field
Epsilon_Customizer::add_field(
    'mosh_preloaderbgcolor',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Preloader Background Color', 'mosh' ),
        'description' => esc_html__( 'Select the preloader background color.', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_general_options_section',
        'default'     => '#211b31',
    )
);
// Preloader color field
Epsilon_Customizer::add_field(
    'mosh_loaderbordcolor',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Preloader Border Color', 'mosh' ),
        'description' => esc_html__( 'Select the preloader border color.', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_general_options_section',
        'default'     => '#f1f2f3',
    )
);
// Preloader Active Border Color Picker
Epsilon_Customizer::add_field(
    'mosh_loaderbordactivecolor',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Preloader Border Active Color', 'mosh' ),
        'description' => esc_html__( 'Select the preloader border active color.', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_general_options_section',
        'default'     => '#4a7aec',
    )
);

// Theme Main Color Picker
Epsilon_Customizer::add_field(
    'mosh_themecolor',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Theme Main Color.', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_general_options_section',
        'default'     => '#4a7aec',
    )
);

// Theme Secondary Color Picker
Epsilon_Customizer::add_field(
    'mosh_themesecondcolor',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Theme Secondary Color.', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_general_options_section',
        'default'     => '#1a3c8d',
    )
);
// Instagram Access Token field
Epsilon_Customizer::add_field(
    'mosh_igaccess_token',
    array(
        'type'              => 'text',
        'label'             => esc_html__( 'Instagram Access Token', 'mosh' ),
        'description'       => esc_html__( 'Set instagram access token.', 'mosh' ),
        'section'           => 'mosh_general_options_section',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
        
    )
);
// Google map api key field
$url = 'https://developers.google.com/maps/documentation/geocoding/get-api-key';

Epsilon_Customizer::add_field(
    'mosh_map_apikey',
    array(
        'type'              => 'text',
        'label'             => esc_html__( 'Google map api key', 'mosh' ),
        'description'       => sprintf( __( 'Set google map api key. To get api key %s click here %s.', 'mosh' ), '<a target="_blank" href="'.esc_url( $url  ).'">', '</a>' ),
        'section'           => 'mosh_general_options_section',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
        
    )
);
/***********************************
 * Header Section Fields
 ***********************************/

// Header button #1 text settings
Epsilon_Customizer::add_field(
    'mosh_btnone_text',
    array(
        'type'        => 'text',
        'label'       => esc_html__( 'Button #1 Text', 'mosh' ),
        'section'     => 'mosh_headertop_options_section',
        'sanitize_callback' => 'sanitize_text_field'
    )
);

// Header button #1 url
Epsilon_Customizer::add_field(
    'mosh_btnone_url',
    array(
        'type'        => 'text',
        'label'       => esc_html__( 'Button #1 Url', 'mosh' ),
        'section'     => 'mosh_headertop_options_section',
        'sanitize_callback' => 'sanitize_text_field'
    )
);
// Header button #2 url
Epsilon_Customizer::add_field(
    'mosh_btntwo_text',
    array(
        'type'        => 'text',
        'label'       => esc_html__( 'Button #2 Text', 'mosh' ),
        'section'     => 'mosh_headertop_options_section',
        'sanitize_callback' => 'sanitize_text_field'
    )
);
// Header button #2 url
Epsilon_Customizer::add_field(
    'mosh_btntwo_url',
    array(
        'type'        => 'text',
        'label'       => esc_html__( 'Button #2 Url', 'mosh' ),
        'section'     => 'mosh_headertop_options_section',
        'sanitize_callback' => 'sanitize_text_field'
    )
);

// Header top search form show / Hide opt
Epsilon_Customizer::add_field(
    'mosh-searchopt-toggle-settings',
    array(
        'type'        => 'epsilon-toggle',
        'label'       => esc_html__( 'Show header search form', 'mosh' ),
        'description' => esc_html__( 'Toggle to show header search form.', 'mosh' ),
        'section'     => 'mosh_headertop_options_section',
        'default'     => true,
    )
);

// Header Nav Bar Background Color Picker
Epsilon_Customizer::add_field(
    'mosh_header_navbar_bgColor',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Header Nav Bar Background Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_headertop_options_section',
        'default'     => '',
    )
);
// Header Sticky  Nav Bar Background Color Picker
Epsilon_Customizer::add_field(
    'mosh_header_navbarsticky_bgColor',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Header Sticky Nav Bar Background Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_headertop_options_section',
        'default'     => '',
    )
);
// Header Nav Bar Menu Color Picker
Epsilon_Customizer::add_field(
    'mosh_header_navbar_menuColor',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Header Nav Bar Menu Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_headertop_options_section',
        'default'     => '#fff',
    )
);

// Header Nav Bar Menu Hover Color Picker
Epsilon_Customizer::add_field(
    'mosh_header_navbar_menuHovColor',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Header Nav Bar Menu Hover Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_headertop_options_section',
        'default'     => '#4a7aec',
    )
);
// Header Nav Bar Menu Color Picker
Epsilon_Customizer::add_field(
    'mosh_header_sticky_navbar_menuColor',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Sticky Header Nav Bar Menu Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_headertop_options_section',
        'default'     => '#fff',
    )
);
// Header Nav Bar Menu Hover Color Picker
Epsilon_Customizer::add_field(
    'mosh_header_sticky_navbar_menuHovColor',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Sticky Header Nav Bar Menu Hover Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_headertop_options_section',
        'default'     => '#4a7aec',
    )
);
// Page Header Background Color Picker
Epsilon_Customizer::add_field(
    'mosh_headerbgcolor',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Header Background Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'colors',
        'default'     => '#211b31',
    )
);

/***********************************
 * Blog Section Fields
 ***********************************/


// Post excerpt length field
Epsilon_Customizer::add_field(
    'mosh_post_excerpt',
    array(
        'type'        => 'text',
        'label'       => esc_html__( 'Post Excerpt', 'mosh' ),
        'description' => esc_html__( 'Set post excerpt length.', 'mosh' ),
        'section'     => 'mosh_blog_options_section',
        'sanitize_callback' => 'sanitize_text_field',
        'default'     => '30',
    )
);
// Blog sidebar layout field
Epsilon_Customizer::add_field(
    'mosh-blog-sidebar-settings',
    array(
        'type'     => 'epsilon-layouts',
        'label'    => esc_html__( 'Blog Layout', 'mosh' ),
        'section'  => 'mosh_blog_options_section',
        'description' => esc_html__( 'Select the option to set blog page layout.', 'mosh' ),
        'layouts'  => array(
            '1' => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/one-column.png',
            '2' => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/epsilon-section-titleright.jpg',
            '3' => get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/img/epsilon-section-titleleft.jpg',
        ),
        'default'  => array(
            'columnsCount' => 1,
            'columns'      => array(
                1 => array(
                    'index' => 1,
                ),
                2 => array(
                    'index' => 2,
                ),
                3 => array(
                    'index' => 3,
                ),
            ),
        ),
        'min_span' => 4,
        'fixed'    => true
    )
);


/***********************************
 * 404 Page Section Fields
 ***********************************/

// 404 text #1 field
Epsilon_Customizer::add_field(
    'mosh_fof_text_one',
    array(
        'type'              => 'text',
        'label'             => esc_html__( '404 Text #1', 'mosh' ),
        'section'           => 'mosh_fof_options_section',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'Ooops 404 Error !'
    )
);
// 404 text #2 field
Epsilon_Customizer::add_field(
    'mosh_fof_text_two',
    array(
        'type'              => 'text',
        'label'             => esc_html__( '404 Text #2', 'mosh' ),
        'section'           => 'mosh_fof_options_section',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'Either something went wrong or the page dosen\'t exist anymore.'
    )
);
// 404 text #1 color field
Epsilon_Customizer::add_field(
    'mosh_fof_textonecolor_settings',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( '404 Text #1 Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_fof_options_section',
        'default'     => '#404551', 
    )
);
// 404 text #2 color field
Epsilon_Customizer::add_field(
    'mosh_fof_texttwocolor_settings',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( '404 Text #2 Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_fof_options_section',
        'default'     => '#abadbe',
    )
);
// 404 background color field
Epsilon_Customizer::add_field(
    'mosh_fof_bgcolor_settings',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( '404 Page Background Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_fof_options_section',
        'default'     => '#fff',
    )
);

/***********************************
 * Footer Section Fields
 ***********************************/

// Footer widget toggle field
Epsilon_Customizer::add_field(
    'mosh-widget-toggle-settings',
    array(
        'type'        => 'epsilon-toggle',
        'label'       => esc_html__( 'Footer widget show/hide', 'mosh' ),
        'description' => esc_html__( 'Toggle to display footer widgets.', 'mosh' ),
        'section'     => 'mosh_footer_options_section',
        'default'     => true,
    )
);
// Footer widget toggle field
Epsilon_Customizer::add_field(
    'mosh-footersocial-toggle-settings',
    array(
        'type'        => 'epsilon-toggle',
        'label'       => esc_html__( 'Footer bottom social media show/hide', 'mosh' ),
        'description' => esc_html__( 'Toggle to display footer bottom social media.', 'mosh' ),
        'section'     => 'mosh_footer_options_section',
        'default'     => '',
    )
);
// Footer copy right text add settings
Epsilon_Customizer::add_field(
    'mosh-copyright-text-settings',
    array(
        'type'        => 'epsilon-text-editor',
        'label'       => esc_html__( 'Footer copyright text', 'mosh' ),
        'section'     => 'mosh_footer_options_section',
        'default'     => '',
    )
);
// Footer widget background color field
Epsilon_Customizer::add_field(
    'mosh_footer_bgColor_settings',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Widget Background Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_footer_options_section',
        'default'     => '#211b31',
    )
);
// Footer widget text color field
Epsilon_Customizer::add_field(
    'mosh_footer_wtcolor_settings',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Widget Text Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_footer_options_section',
        'default'     => '#888888',
    )
);
// Footer widget title color field
Epsilon_Customizer::add_field(
    'mosh_footer_widgettitlecolor_settings',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Widget Title Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_footer_options_section',
        'default'     => '#222',
    )
);
// Footer widget anchor color field
Epsilon_Customizer::add_field(
    'mosh_footer_wanchorcolor_settings',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Widget Anchor Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_footer_options_section',
        'default'     => '#888888',
    )
);
// Footer widget anchor hover Color 
Epsilon_Customizer::add_field(
    'mosh_footer_wanchorhovcolor_settings',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Widget Anchor Hover Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_footer_options_section',
        'default'     => '#888888',
    )
);
// Footer bottom background color 
Epsilon_Customizer::add_field(
    'mosh_footerbtm_bgColor_settings',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Copyright Area Background Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_footer_options_section',
        'default'     => '#191426',
    )
);
// Footer Copyright Text Color
Epsilon_Customizer::add_field(
    'mosh_footer_copyrighttextcolor_settings',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Copyright Text Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_footer_options_section',
        'default'     => '#abadbe',
    )
);
// Footer Social Icon Color
Epsilon_Customizer::add_field(
    'mosh_footer_socialiconcolor_settings',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Social Icon Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_footer_options_section',
        'default'     => '#abadbe',
    )
);
// Footer Social Icon Hover Color
Epsilon_Customizer::add_field(
    'mosh_footer_socialiconhovercolor_settings',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Social Icon Hover Color', 'mosh' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'mosh_footer_options_section',
        'default'     => '#fff',
    )
);

?>