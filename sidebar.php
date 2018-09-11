<?php 
// Block direct access
if( !defined( 'ABSPATH' ) ){
	exit( 'Direct script access denied.' );
}
/**
 * @Packge 	   : Colorlib
 * @Version    : 1.0
 * @Author 	   : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 */

// Sidebar
if( is_active_sidebar( 'mosh-post-sidebar' ) ){
	
	echo '<div class="col-12 col-md-4"><div class="mosh-blog-sidebar">';
		dynamic_sidebar( 'mosh-post-sidebar' );
	echo '</div></div>';
}
 

?>