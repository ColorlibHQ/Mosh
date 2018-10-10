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
 
	// Before wrapper Preloader
	if( !function_exists('mosh_site_preloader') ){
		function mosh_site_preloader(){
			if( mosh_opt('mosh-preloader-toggle-settings') ):
			?>
		    <div id="preloader">
		        <div class="mosh-preloader"></div>
		    </div>
			<?php
			endif;
		}
	}

	// Header menu hook function
	if( !function_exists( 'mosh_header_cb' ) ){
		function mosh_header_cb(){
			if( !is_404() ){
						
				get_template_part( 'templates/header', 'top' );

				if( !is_page_template( 'template-builder.php' ) ){
					get_template_part( 'templates/header', 'bottom' );
				}

			}
			
		}
	}

	// Footer area hook function
	if( !function_exists( 'mosh_footer_area' ) ){
		function mosh_footer_area(){

			if( !is_404() ){
				echo '<footer class="footer-area clearfix">';
				// Footer widget
				
				if( mosh_opt( 'mosh-widget-toggle-settings' ) ){
					get_template_part( 'templates/footer', 'widgets' );
				}
				
				// Footer bottom
				get_template_part( 'templates/footer', 'bottom' );	
				echo '</footer>';

				
			}
		}
	}


	// Blog, single, page, search, archive pages wrapper start hook function.
	if( !function_exists('mosh_wrp_start_cb') ){
		function mosh_wrp_start_cb(){

			echo '<section class="blog-area section_padding_100"><div class="container"><div class="row">';
		}
	}
	// Blog, single, page, search, archive pages wrapper end hook function.
	if( !function_exists('mosh_wrp_end_cb') ){
		function mosh_wrp_end_cb(){
			echo '</div></div></section>';
		}
	}
	// Blog, single, search, archive pages column start hook function.
	if( !function_exists('mosh_blog_col_start_cb') ){
		function mosh_blog_col_start_cb(){
			
			$sidebarOpt = mosh_sidebar_opt();
								
			//
			if( !is_page() ){
				$pullRight  = mosh_pull_right( $sidebarOpt , '3' );

				if( $sidebarOpt != '1' ){
					$col = '8'.$pullRight;
				}else{

					if( !is_single() ){
						$col = '12';
					}else{
						$col = '10 offset-lg-1';
					}

				}
			}else{
				$col = '8';
				
			}

			// single page should be p-b-80
			echo '<div class="col-12 col-md-'.esc_attr( $col ).'"><div class="mosh-blog-posts"><div class="row">';
		}
	}
	// Blog, single, search, archive pages column end hook function.
	if( !function_exists('mosh_blog_col_end_cb') ){
		function mosh_blog_col_end_cb(){
			echo '</div></div></div>';
		}
	}

	// Blog post thumbnail hook function.
	if( !function_exists('mosh_blog_posts_thumb_cb') ){
		function mosh_blog_posts_thumb_cb(){
			// Thumbnail Show
			if( has_post_thumbnail() ){
						
				if( !is_single() ){
				
					$html = '';
					$html .= '<div class="blog-post-thumb">';
					$html .= '<a href="'.esc_url( get_the_permalink() ).'" class="item-blog-img pos-relative dis-block hov-img-zoom">';
					$html .= mosh_img_tag(
						array(
							'url' => esc_url( get_the_post_thumbnail_url() )
						)
					);
					$html .= '</a>';
					$html .= '</div>';
				
				}else{
					
					$html = '';
					$html .= '<div class="blog-post-thumb">';
					$html .= mosh_img_tag(
						array(
							'url' => esc_url( get_the_post_thumbnail_url() )
						)
					);
					$html .= '</div>';

				}
				echo wp_kses_post( $html );
				

			}
			// Thumbnail check and video and audio thumb show
			if( !is_single() && !has_post_thumbnail() ){
				$html = '';
				if( has_post_format( array( 'video' ) ) ){
					
					$html .= '<div class="blog-post-thumb">';
					$html .= mosh_embedded_media( array( 'video', 'iframe' ) );
					$html .= '</div>';

				}else{

					if( has_post_format( array( 'audio' ) ) ){
						
						$html .= '<div class="blog-post-thumb">';
						$html .= mosh_embedded_media( array( 'audio', 'iframe' ) );
						$html .= '</div>';
					}
				}
				
				echo apply_filters( 'mosh_audio_embedded_media', $html );

			}
		}
	}

	// Blog post title hook function.
	if( !function_exists('mosh_blog_posts_title_cb') ){
		function mosh_blog_posts_title_cb(){
			if( get_the_title() ){

				$html = '';
				if( !is_single() ){
					$html .= '<h2><a href="'.esc_url( get_the_permalink() ).'">'.esc_html( get_the_title() ).'</a></h2>';
				}else{
					$html .= '<h2>'.esc_html( get_the_title() ).'</h2>';
				}
				
				echo wp_kses_post( $html );

			}
		}
	}

	// Blog posts meta hook function.
	if( !function_exists('mosh_blog_posts_meta_cb') ){
		function mosh_blog_posts_meta_cb(){

			echo '<div class="post-meta"><h6>';
			// Author
			if( get_the_author() ){
				echo esc_html__( 'By ', 'mosh' ).'<a href="'.esc_url( get_author_posts_url( get_the_author_meta('ID') ) ).'">'.esc_html( get_the_author() ).',</a>';
			}
			// Date
			if( get_the_date() ){
				$postData = '<a href="'.esc_url( mosh_blog_date_permalink() ).'">'.esc_html( get_the_date() ).',</a>';
				echo wp_kses_post( $postData );
			}
			
			// Post category
			$cats = get_the_category();
			$categories = '';
			if( is_array( $cats ) && count( $cats ) > 0 ){
				
				foreach( $cats as $cat ){
				   $categories .= '<a href="'.esc_url( get_category_link( $cat->term_id ) ).'" class="category-link">'.esc_html( $cat->name ).',</a>';
				}
			}
							
			echo wp_kses_post( $categories );

			comments_popup_link( esc_html__( '0 Comment', 'mosh' ), esc_html__( '1 Comment','mosh' ), esc_html__('% Comments','mosh'));
			
			$featured = '';
			if( is_sticky() ){
				$featured = '<span class="featured">'.esc_html__( 'Featured', 'mosh' ).'</span>';
			}

			echo '</h6>'.wp_kses_post( $featured ).'</div>';

		
			
		}
	}

	// Blog posts excerpt hook function.
	if( !function_exists('mosh_blog_posts_excerpt_cb') ){
		function mosh_blog_posts_excerpt_cb(){
			?>
			<div class="blog-postexcerpt">
				<?php 
				// Post excerpt
				echo mosh_excerpt_length( esc_html( mosh_opt('mosh_post_excerpt') ) );

				// Link Pages
				mosh_link_pages();
				?>
			</div>	
			<a href="<?php the_permalink(); ?>">
				<?php esc_html_e( 'Read More', 'mosh' ); ?>
			</a>			
			<?php

		}
	}

	// Blog posts content hook function.
	if( !function_exists('mosh_blog_posts_content_cb') ){
		function mosh_blog_posts_content_cb(){

				the_content();
				// Link Pages
				mosh_link_pages();


		}
	}

	// Page content hook function.
	if( !function_exists('mosh_page_content_cb') ){
		function mosh_page_content_cb(){
			?>
			<div class="page--content single-blog">
				<?php 
				the_content();

				// Link Pages
				mosh_link_pages();
				?>

			</div>
			<?php
			// comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		}
	}

	// Blog page sidebar hook function.
	if( !function_exists('mosh_blog_sidebar_cb') ){
		function mosh_blog_sidebar_cb(){
			
			$sidebarOpt = mosh_sidebar_opt();
					
			if( $sidebarOpt != '1'  || is_page()  ){
				get_sidebar();
			}
			
				
		}
	}


	// Blog single post  social share hook function.
	if( !function_exists('mosh_blog_posts_share_cb') ){
		function mosh_blog_posts_share_cb(){
						
			if( function_exists('mosh_social_sharing_buttons') ){
			
				mosh_social_sharing_buttons();
				
			}			
		}
	}

	/**
	 * Blog single post meta category, tag, next-previous link, comments form and biography hook function.
	 */
	if( !function_exists('mosh_blog_single_meta_cb') ){
		function mosh_blog_single_meta_cb(){
						
			$tags = mosh_post_tags();
	
			if( $tags ){
				echo '<div class="wrap-tags">';
					echo '<span class="tag-title">'.esc_html__( 'Post Tags:', 'mosh' ).'</span>';
					echo '<div class="tags-items">';
					// single post tag
					echo wp_kses_post( $tags );
					
					echo '</div>';
				echo '</div>';
			}

			// Author biography
			if( '' !== get_the_author_meta('description') ){
				get_template_part( 'templates/biography' );
			}
	
		}
	}

	// Blog 404 page hook function.
	if( !function_exists('mosh_fof_cb') ){
		function mosh_fof_cb(){
			get_template_part( 'templates/404' );			
		}
	}



?>