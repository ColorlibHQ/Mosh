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
 

// enqueue css
function mosh_common_custom_css(){
    
    wp_enqueue_style( 'mosh-common', get_template_directory_uri().'/assets/css/common.css' );
		
		$navbarBg          		  = mosh_opt( 'mosh_header_navbar_bgColor' );
		$navbarstickyBg           = mosh_opt( 'mosh_header_navbarsticky_bgColor' );
		$navColor       	  	  = mosh_opt( 'mosh_header_navbar_menuColor' );
		$navhoverColor       	  = mosh_opt( 'mosh_header_navbar_menuHovColor' );
		$stickynavColor  		  = mosh_opt( 'mosh_header_sticky_navbar_menuColor' );
		$stickynavhoverColor 	  = mosh_opt( 'mosh_header_sticky_navbar_menuHovColor' );
		$footerbtmbgColor         = mosh_opt( 'mosh_footerbtm_bgColor_settings' );
		$copyrighttextcolor       = mosh_opt( 'mosh_footer_copyrighttextcolor_settings' );
		$fofbgcolor       		  = mosh_opt( 'mosh_fof_bgcolor_settings' );
		$textonecolor       	  = mosh_opt( 'mosh_fof_textonecolor_settings' );
		$texttwocolor       	  = mosh_opt( 'mosh_fof_texttwocolor_settings' );
		$headerTextColor   		  = get_header_textcolor();
		$headerbgcolor     	  	  = mosh_opt('mosh_headerbgcolor');
		$footerwbgColor     	  = mosh_opt('mosh_footer_bgColor_settings');
		$footerwTextColor   	  = mosh_opt('mosh_footer_wtcolor_settings');
		$footerwanchorcolor 	  = mosh_opt('mosh_footer_wanchorcolor_settings');
		$footerwanchorhovcolor    = mosh_opt('mosh_footer_wanchorhovcolor_settings');
		$widgettitlecolor  		  = mosh_opt('mosh_footer_widgettitlecolor_settings');
		$themecolor  	   		  = mosh_opt('mosh_themecolor');
		$themesecondcolor  	      = mosh_opt('mosh_themesecondcolor');
		$preloaderbgcolor  	      = mosh_opt('mosh_preloaderbgcolor');
		$loaderbordcolor  	      = mosh_opt('mosh_loaderbordcolor');
		$loaderbordactivecolor    = mosh_opt('mosh_loaderbordactivecolor');
		$socialiconcolor    	  = mosh_opt('mosh_footer_socialiconcolor_settings');
		$socialiconhovercolor     = mosh_opt('mosh_footer_socialiconhovercolor_settings');

        $customcss ="
        	#scrollUp,
        	.page-item.active .page-link,
        	.mosh-btn,
        	.mosh-blog-posts .sticky .single-blog .post-meta .featured {
				background-color: {$themecolor};
			}
			a,
			.single-blog > a,
			.latest-blog-post-content h6 > a:hover, 
			.blog-post-archives ul > li > a:hover, 
			.blog-post-categories ul > li > a:hover,
			.mosh-pagination-area .page-link {
				color: {$themecolor};
			}
			.page-item.active .page-link {
				border-color: {$themecolor};
			}
			a:hover {
				color: {$themesecondcolor}
			}
			.mosh-btn:hover{
				background-color: {$themesecondcolor}
			}
			.header_area{
				background-color: {$navbarBg}
			}
			.header_area.sticky {
				background-color: {$navbarstickyBg}
			}
			.login-register-btn span,
			.login-register-btn a,
			.menu_area #nav .nav-link {
				color: {$navColor}
			}
			.menu_area #nav .nav-link:hover, 
			.menu_area #nav .nav-item.active .nav-link, 
			.menu_area .dropdown-item:hover,
			.login-register-btn a:hover {
				color: {$navhoverColor}
			}
			.header_area.sticky .login-register-btn span,
			.header_area.sticky .login-register-btn a,
			.header_area.sticky .menu_area #nav .nav-link {
				color: {$stickynavColor}
			}
			.header_area.sticky .menu_area #nav .nav-link:hover, 
			.header_area.sticky .menu_area #nav .nav-item.active .nav-link, 
			.header_area.sticky .menu_area .dropdown-item:hover,
			.header_area.sticky .login-register-btn a:hover {
				color: {$stickynavhoverColor}
			}
			.footer-bottom-area {
				background-color: {$footerbtmbgColor}
			}
			.footer-area .copyright-text,
			.footer-area .copyright-text p{
				color: {$copyrighttextcolor}
			}
			.bradcumbContent .breadcrumb-item a, 
			.bradcumbContent .breadcrumb-item,
			.bradcumbContent h2 {
				color: {$headerTextColor};
			}
			.mosh-breadcumb-area{
				background-color: {$headerbgcolor}
			}
			.footer-area {
				background-color: {$footerwbgColor};
			}
			.footer-area,
			.footer-area p,
			.footer-area caption {
				color: {$footerwTextColor};
			}
			.single-footer-widget ul > li > a,
			.single-footer-widget a{
				color: {$footerwanchorcolor};
			}
			.single-footer-widget ul > li > a:hover,
			.single-footer-widget a:hover{
				color: {$footerwanchorhovcolor};
			}
			.single-footer-widget h5{
				color: {$widgettitlecolor};
			}
			#f0f {
				background-color: {$fofbgcolor};
			}
			.fof-content-before h1{
				color: {$textonecolor};
			}
			.fof-content-before p{
				color: {$texttwocolor};
			}
			.footer-social-info > li > a {
				color: {$socialiconcolor}
			}
			.footer-social-info > li > a:hover {
				color: {$socialiconhovercolor}
			}
			#preloader {
				background-color: {$preloaderbgcolor};
			}
			.mosh-preloader {
				border-color: {$loaderbordcolor} {$loaderbordcolor} {$loaderbordactivecolor};
			}


        ";
       
    wp_add_inline_style( 'mosh-common', $customcss );
    
}
add_action( 'wp_enqueue_scripts', 'mosh_common_custom_css', 50 );