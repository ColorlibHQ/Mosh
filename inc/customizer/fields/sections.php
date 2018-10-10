<?php 
/**
 * @Packge     : Mosh
 * @Version    : 1.0
 * @Author     : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 * Customizer panels and sections
 *
 */

/***********************************
 * Register customizer panels
 ***********************************/

$panels = array(
    /**
     * Theme Options Panel
     */
    array(
        'id'   => 'mosh_options_panel',
        'args' => array(
            'priority'       => 0,
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => esc_html__( 'Theme Options', 'mosh' ),
        ),
    )
);


/***********************************
 * Register customizer sections
 ***********************************/


$sections = array(
    /**
     * General Section
     */
    array(
        'id'   => 'mosh_general_options_section',
        'args' => array(
            'title'    => esc_html__( 'General', 'mosh' ),
            'panel'    => 'mosh_options_panel',
            'priority' => 1,
        ),
    ),
    /**
     * Header Section
     */
    array(
        'id'   => 'mosh_headertop_options_section',
        'args' => array(
            'title'    => esc_html__( 'Header Settings', 'mosh' ),
            'panel'    => 'mosh_options_panel',
            'priority' => 2,
        ),
    ),
    /**
     * Blog Section
     */
    array(
        'id'   => 'mosh_blog_options_section',
        'args' => array(
            'title'    => esc_html__( 'Blog', 'mosh' ),
            'panel'    => 'mosh_options_panel',
            'priority' => 3,
        ),
    ),

    /**
     * 404 Page Section
     */
    array(
        'id'   => 'mosh_fof_options_section',
        'args' => array(
            'title'    => esc_html__( '404 Page', 'mosh' ),
            'panel'    => 'mosh_options_panel',
            'priority' => 6,
        ),
    ),
    /**
     * Footer Section
     */
    array(
        'id'   => 'mosh_footer_options_section',
        'args' => array(
            'title'    => esc_html__( 'Footer', 'mosh' ),
            'panel'    => 'mosh_options_panel',
            'priority' => 7,
        ),
    ),

);


/***********************************
 * Add customizer elements
 ***********************************/
$collection = array(
    'panel'   => $panels,
    'section' => $sections,
);

Epsilon_Customizer::add_multiple( $collection );

?>