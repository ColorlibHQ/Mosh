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

?>
<!-- Post Item Start -->
<div id="<?php the_ID(); ?>" <?php post_class('col-12 blog-post-details'); ?>>
	<div class="single-blog">
		<?php 
		/**
		 * Blog Post thumbnail
		 * @Hook  mosh_blog_posts_thumb
		 *
		 * @Hooked mosh_blog_posts_thumb_cb
		 * 
		 *
		 */
		do_action( 'mosh_blog_posts_thumb' );
		
		/**
		 * Blog Post Meta
		 * @Hook  mosh_blog_posts_meta
		 *
		 * @Hooked mosh_blog_posts_meta_cb
		 * 
		 *
		 */
		do_action( 'mosh_blog_posts_meta' );

		/**
		 * Blog Post title
		 * @Hook  mosh_blog_posts_title
		 *
		 * @Hooked mosh_blog_posts_title_cb
		 * 
		 *
		 */
		do_action( 'mosh_blog_posts_title' );		
		
		/**
		 * Blog single page content 
		 * Post social share
		 * @Hook  mosh_blog_posts_content
		 *
		 * @Hooked mosh_blog_posts_content_cb
		 * 
		 *
		 */
		do_action( 'mosh_blog_posts_content' );

		?>   
	</div>
	<?php 
	do_action('mosh_blog_single_meta');
	// comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
	
	?>  
</div>               