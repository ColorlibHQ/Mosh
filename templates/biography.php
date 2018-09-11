<?php 
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}
/**
 *
 * @Packge      Colorlib
 * @Author      Colorlib
 * @Author URL  https//www.Colorlib.com
 * @version     1.0
 *
 */
?>
<div class="s-content__author">
	<div class="authot-avatar">
		<?php 
		// show avatar
		$avatar = get_avatar( get_the_author_meta( 'ID' ),70 );
		if( $avatar  ){
			echo wp_kses_post( $avatar );
		}
		?>
		<h5 class="s-content__author-name">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a>
		</h5>
	</div>
	<div class="authot-biography s-content__author-about">
		<p><?php esc_html( the_author_meta('description') ); ?></p>
	</div>
</div>