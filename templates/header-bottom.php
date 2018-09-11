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

// Header background image oparation

$class     = '';
$headerimg = '';
if( get_header_image() ){
    $headerimg = get_header_image();
    $class = ' header-image';
}
?>

<div class="mosh-breadcumb-area<?php echo esc_attr( $class ); ?>" <?php echo mosh_inline_bg_img( esc_url( $headerimg ) ); ?>>
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">
                <div class="bradcumbContent">
                    <?php 
                    if ( is_archive() ){
                        the_archive_title('<h2>', '</h2>');
                    }elseif ( is_home() ){
                        echo '<h2>'.esc_html__( 'Blog', 'mosh' ).'</h2>';
                    }elseif(is_search()){
                        echo '<h2>'.esc_html__( 'Search Result', 'mosh' ).'</h2>';
                    } else{
                        the_title( '<h2>', '</h2>' );
                    }
                    //
                    mosh_breadcrumbs();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>