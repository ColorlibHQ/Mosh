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

?>
<!-- Top Fotter Area -->
<div class="top-footer-area section_padding_100_0">
    <div class="container">
        <div class="row">
			<?php 
			// Footer widget 1
			if( is_active_sidebar( 'footer-1' ) ){
				echo '<div class="col-12 col-sm-6 col-lg-3">';
					dynamic_sidebar( 'footer-1' );
				echo '</div>';
			}

			// Footer widget 2
			if( is_active_sidebar( 'footer-2' ) ){
				echo '<div class="col-12 col-sm-6 col-lg-3">';
					dynamic_sidebar( 'footer-2' );
				echo '</div>';
			}

			// Footer widget 3
			if( is_active_sidebar( 'footer-3' ) ){
				echo '<div class="col-12 col-sm-6 col-lg-3">';
					dynamic_sidebar( 'footer-3' );
				echo '</div>';
			}
			
			// Footer widget 4
			if( is_active_sidebar( 'footer-4' ) ){
				echo '<div class="col-12 col-sm-6 col-lg-3">';
					dynamic_sidebar( 'footer-4' );
				echo '</div>';
			}
			?>
        </div>
    </div>
</div>

