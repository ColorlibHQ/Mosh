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
 


if ( post_password_required() ) 
{
    return;
}
?>

    <?php if ( have_comments() ) : ?>
		<div id="comments" class="comment--items"> <!-- Comment Item Start-->
        <h4 class="comment-count-title"><?php printf( _nx( '1 Comment', '%1$s Comments', get_comments_number(), 'comments title', 'mosh' ), number_format_i18n( get_comments_number() ) ); ?></h4>
		
        <?php the_comments_navigation(); ?>
            <ul class="commentlist">
                <?php
                    wp_list_comments( array(
                        'style'       => 'ul',
                        'short_ping'  => true,
                        'avatar_size' => 70,
                        'callback'    => 'mosh_comment_callback'
                    ) );
                ?>
            </ul><!-- .comment-list -->
        <?php the_comments_navigation(); ?>
		</div><!-- Comment Item End-->
    <?php endif; // Check for have_comments(). ?>

    <?php
        // If comments are closed and there are comments, let's leave a little note, shall we?
        if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
    ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'mosh' ); ?></p>
    <?php endif; ?>
    
<?php
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? "required='required'" : '' );
	$fields =  array(
	  'author' =>'<div class="row"><div class="col-12 col-md-6"><input class="form-control" placeholder="'.esc_attr__( 'Your Name', 'mosh' ).'" type="text" name="author" value="'. esc_attr( $commenter['comment_author'] ).'" id="cName" '.$aria_req.'></div>',
	  'email' =>'<div class="col-12 col-md-6"><input class="form-control" placeholder="'.esc_attr__( 'Your Email', 'mosh' ).'" type="text" name="email"  value="' . esc_attr(  $commenter['comment_author_email'] ) .'" id="cEmail" '.$aria_req.'></div>',
	  'url' =>'<div class="col-12"><input class="form-control" placeholder="'.esc_attr__( 'Website', 'mosh' ).'" type="text" name="url" value="'. esc_attr( $commenter['comment_author_url'] ) .'" id="cWebsite"></div>',
      'cookies_consent' =>'<div class="col-12"><p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" value="yes" type="checkbox"><label for="wp-comment-cookies-consent">'.esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'mosh' ).'</label></p></div></div>',
	);
	

	$args=array(
	'comment_field'         =>'<div class="row"><div class="col-12"><textarea id="cMessage" class="form-control" rows="10" name="comment" placeholder="'.esc_attr__( 'Comment...', 'mosh' ).'"></textarea></div></div>',
	'id_form'               =>'contactForm',
    'class_form'            =>'',
	'title_reply'           =>esc_html__( 'LEAVE A COMMENT', 'mosh' ),
	'title_reply_before'    =>'<h4>',
	'title_reply_after'     =>'</h4>',
    'label_submit'          => esc_html__( 'Post Comment', 'mosh' ),
    'class_submit'          => 'btn mosh-btn mt-50',
	'submit_button'         => '<div class="w-size24"><button type="submit" name="%1$s" id="%2$s" class="%3$s">%4$s</button></div>',
	'fields'                =>$fields,
	
	);
    echo '<div class="contact-form-area">';
	comment_form( $args );
    echo '</div>';

?>
<!-- .comments-area -->
