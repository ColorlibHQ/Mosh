<?php 
/**
 * @Packge 	   : Colorlib
 * @Version    : 1.0
 * @Author 	   : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 */
 
	// Block direct access
	if( !defined( 'ABSPATH' ) ){
		exit( 'Direct script access denied.' );
	}

	/**
	 *
	 * Before Wrapper
	 *
	 * @Preloader
	 *
	 */
	add_action( 'mosh_preloader', 'mosh_site_preloader', 10 );

	/**
	 * Header
	 *
	 * @Header Menu
	 * @Header Bottom
	 * 
	 */

	add_action( 'mosh_header', 'mosh_header_cb', 10 );

	/**
	 * Hook for footer
	 *
	 */
	add_action( 'mosh_footer', 'mosh_footer_area', 10 );

	/**
	 * Hook for Blog, single, page, search, archive pages wrapper.
	 */
	add_action( 'mosh_wrp_start', 'mosh_wrp_start_cb', 10 );
	add_action( 'mosh_wrp_end', 'mosh_wrp_end_cb', 10 );
	
	/**
	 * Hook for Blog, single, search, archive pages column.
	 */
	add_action( 'mosh_blog_col_start', 'mosh_blog_col_start_cb', 10 );
	add_action( 'mosh_blog_col_end', 'mosh_blog_col_end_cb', 10 );
	
	/**
	 * Hook for blog posts thumbnail.
	 */
	add_action( 'mosh_blog_posts_thumb', 'mosh_blog_posts_thumb_cb', 10 );

	/**
	 * Hook for blog posts title.
	 */
	add_action( 'mosh_blog_posts_title', 'mosh_blog_posts_title_cb', 10 );

	/**
	 * Hook for blog posts meta.
	 */
	add_action( 'mosh_blog_posts_meta', 'mosh_blog_posts_meta_cb', 10 );

	/**
	 * Hook for blog posts excerpt.
	 */
	add_action( 'mosh_blog_posts_excerpt', 'mosh_blog_posts_excerpt_cb', 10 );

	/**
	 * Hook for blog posts content.
	 */
	add_action( 'mosh_blog_posts_content', 'mosh_blog_posts_content_cb', 10 );

	/**
	 * Hook for blog sidebar.
	 */
	add_action( 'mosh_blog_sidebar', 'mosh_blog_sidebar_cb', 10 );
	
	/**
	 * Hook for blog single post social share option.
	 */
	add_action( 'mosh_blog_posts_share', 'mosh_blog_posts_share_cb', 10 );
	
	/**
	 * Hook for blog single post meta category, tag, next - previous link and comments form.
	 */
	add_action( 'mosh_blog_single_meta', 'mosh_blog_single_meta_cb', 10 );
	
	/**
	 * Hook for page content.
	 */
	add_action( 'mosh_page_content', 'mosh_page_content_cb', 10 );
	
	
	/**
	 * Hook for 404 page.
	 */
	add_action( 'mosh_fof', 'mosh_fof_cb', 10 );

	

?>