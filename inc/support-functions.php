<?php
/**
 * @Packge     : Mosh
 * @Version    : 1.0
 * @Author     : Colorlib
 * @Author     URI : http://colorlib.com/wp/
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


// Post Category
function mosh_post_cats() {

	$cats       = get_the_category();
	$categories = '';
	if ( $cats ) {

		$categories .= '<div class="posts--cat m--30-0-0">';
		$categories .= '<ul class="nav"><li><span><i class="fa fm fa-th-list"></i>' . esc_html( 'Catagory :', 'mosh' ) . '</span></li>';

		foreach ( $cats as $cat ) {
			$categories .= '<li><a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="category-link">' . esc_html( $cat->name ) . '</a></li>';
		}

		$categories .= '</ul>';
		$categories .= '</div>';
	}

	return $categories;

}

// Post Tags
function mosh_post_tags() {

	$tags = get_the_tags();

	$getTags = '';

	if ( $tags ) {

		foreach ( $tags as $tag ) {
			$getTags .= '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="tag-item">' . esc_html( $tag->name ) . '</a>';
		}

	}

	return $getTags;

}

// mosh comment template callback
function mosh_comment_callback( $comment, $args, $depth ) {

	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}
	?>
	<<?php echo esc_attr( $tag ); ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php absint( comment_ID() ); ?>" class="comment--item">
	<?php endif; ?>
	<div class="comment--meta-info">
		<div class="comment--meta-img comment__avatar">
			<?php if ( $args['avatar_size'] != 0 ) {
				echo get_avatar( $comment, $args['avatar_size'] );
			} ?>
		</div>
		<div class="comment__info">
			<cite><?php printf( __( '<span class="comment-author-name">%s</span> ', 'mosh' ), get_comment_author_link() ); ?></cite>
			<div class="comment__meta">
				<time class="comment__time"> <?php printf( __( '%1$s at %2$s', 'mosh' ), get_comment_date(), get_comment_time() ); ?><?php edit_comment_link( esc_html__( '(Edit)', 'mosh' ), '  ', '' ); ?></time>
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'mosh' ); ?></em>
				<?php endif; ?>

				<?php comment_reply_link( array_merge( $args, array(
					'add_below'  => $add_below,
					'depth'      => 1,
					'max_depth'  => 5,
					'reply_text' => 'Reply',
				) ) ); ?>
			</div>
		</div>
	</div>
	<div class="comment__content">

		<div class="comment__text">
			<?php wp_kses_post( comment_text() ); ?>

		</div>
	</div>

	<?php if ( 'div' != $args['style'] ) : ?>
		</div>
	<?php endif; ?>
	<?php
}

// add class comment reply link
add_filter( 'comment_reply_link', 'mosh_replace_reply_link_class' );
function mosh_replace_reply_link_class( $class ) {
	$class = str_replace( "class='comment-reply-link", "class='reply", $class );

	return $class;
}

// social media
if ( ! function_exists( 'mosh_social' ) ) {
	function mosh_social( $args = array() ) {

		$default = array(
			'wrapper_start' => '',
			'wrapper_end'   => '',
			'class'         => 'topbar-social',
		);

		$args = wp_parse_args( $args, $default );


		$url = mosh_opt( 'mosh_social_url' );
		if ( is_array( $url ) && count( $url ) > 0 ):

			echo wp_kses_post( $args['wrapper_start'] );

			echo '<div class="' . esc_attr( $args['class'] ) . '">';

			// Facebook
			if ( ! empty( $url['facebook_url'] ) ) {
				echo '<a href="' . esc_url( $url['facebook_url'] ) . '" class="topbar-social-item fa fa-facebook"></a>';
			}
			// Twitter
			if ( ! empty( $url['twitter_url'] ) ) {
				echo '<a href="' . esc_url( $url['twitter_url'] ) . '" class="topbar-social-item fa fa-twitter"></a>';
			}
			// Google
			if ( ! empty( $url['google_url'] ) ) {
				echo '<a href="' . esc_url( $url['google_url'] ) . '" class="topbar-social-item fa fa-google-plus"></a>';
			}
			// Instagram
			if ( ! empty( $url['instagram_url'] ) ) {
				echo '<a href="' . esc_url( $url['instagram_url'] ) . '" class="topbar-social-item fa fa-instagram"></a>';
			}
			// Pinterest
			if ( ! empty( $url['pinterest_url'] ) ) {
				echo '<a href="' . esc_url( $url['pinterest_url'] ) . '" class="topbar-social-item fa fa-pinterest-p"></a>';
			}
			// Snapchat
			if ( ! empty( $url['snapchat_url'] ) ) {
				echo '<a href="' . esc_url( $url['snapchat_url'] ) . '" class="topbar-social-item fa fa-snapchat-ghost"></a>';
			}
			// Youtube
			if ( ! empty( $url['youtube_url'] ) ) {
				echo '<a href="' . esc_url( $url['youtube_url'] ) . '" class="topbar-social-item fa fa-youtube-play"></a>';
			}


			echo '</div>';
			echo wp_kses_post( $args['wrapper_end'] );

		endif;
	}
}
// header cart count
function mosh_cart_count( $class = '' ) {

	?>
	<div class="header-wrapicon2 <?php echo esc_attr( $class ); ?>">
		<img src="<?php echo esc_url( MOSH_DIR_ASSETS_URI ); ?>img/icons/icon-header-02.png" class="header-icon1 js-show-header-dropdown" alt="<?php esc_html_e( 'ICON', 'mosh' ); ?>">
		<span class="header-icons-noti"><?php echo sprintf( '%d', WC()->cart->cart_contents_count ); ?></span>
		<div class="header-cart header-dropdown">
			<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
		</div>
	</div>
	<?php
}

// Set contact form 7 default form template
function mosh_contact7_form_content( $template, $prop ) {
	if ( 'form' == $prop ) {

		$template = '<div class="row">
            <div class="col-12 col-md-6">
                [text* name-228 id:name class:form-control placeholder "Name"]
            </div>
            <div class="col-12 col-md-6">
                [email* email-802 id:email class:form-control placeholder "E-mail"]
            </div>
            <div class="col-12">
                [text* subject-228 id:name class:form-control placeholder "Subject"]
            </div>
            <div class="col-12">
                [textarea message-360 id:message class:form-control placeholder "Message"]
            </div>
            [submit class:btn class:mosh-btn class:mt-50 "Send Message"]
            </div>';

		return $template;

	} else {
		return $template;
	}
}

add_filter( 'wpcf7_default_template', 'mosh_contact7_form_content', 10, 2 );
