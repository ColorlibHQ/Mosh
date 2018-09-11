<?php
// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit( 'Direct script access denied.' );
}

/**
 * @Packge     : Mosh
 * @Version    : 1.0
 * @Author     : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 */
 
    
function mosh_widgets_init() {
    // sidebar widgets register
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'mosh' ),
        'id'            => 'mosh-post-sidebar',
        'before_widget' => '<div id="%1$s" class="sidebar-widget widget blog-post-archives mb-100 %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5>',
        'after_title'   => '</h5>',
    ) );
    // page sidebar widgets register
    register_sidebar( array(
        'name'          => esc_html__( 'Page Sidebar', 'mosh' ),
        'id'            => 'mosh-page-sidebar',
        'before_widget' => '<div id="%1$s" class="sidebar-widget widget blog-post-archives mb-100 %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5>',
        'after_title'   => '</h5>',
    ) );
    
    // footer widgets register
    register_sidebar( array(
        'name'          => esc_html__( 'Footer One', 'mosh' ),
        'id'            => 'footer-1',
        'before_widget' => '<div id="%1$s" class="single-footer-widget widget mb-100 %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5>',
        'after_title'   => '</h5>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Two', 'mosh' ),
        'id'            => 'footer-2',
        'before_widget' => '<div id="%1$s" class="single-footer-widget widget mb-100 %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5>',
        'after_title'   => '</h5>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Three', 'mosh' ),
        'id'            => 'footer-3',
        'before_widget' => '<div id="%1$s" class="single-footer-widget widget mb-100 %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5>',
        'after_title'   => '</h5>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Four', 'mosh' ),
        'id'            => 'footer-4',
        'before_widget' => '<div id="%1$s" class="single-footer-widget widget mb-100 %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5>',
        'after_title'   => '</h5>',
    ) );

    
    
}
add_action( 'widgets_init', 'mosh_widgets_init' );
