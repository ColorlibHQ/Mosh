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

	<div id="f0f">
		<div class="container">
			<div class="row">
				<div class="f0f-content text-center col-md-12">
					<div class="fof-content-before">
						<?php 
						$errorText = esc_html__( 'Ooops 404 Error !', 'mosh' );
						if( mosh_opt( 'mosh_fof_text_one' ) ){
							$errorText = mosh_opt( 'mosh_fof_text_one' );
						}
						//
						echo '<h1 class="h1">'.esc_html( $errorText ).'</h1>';
						//

						// Wrong text block

						$wrongText = wp_kses_post( __( 'Either something went wrong or the page dosen&rsquo;t exist anymore.', 'mosh' ) );

						if( mosh_opt('mosh_fof_text_two') ){
							$wrongText = mosh_opt('mosh_fof_text_two');
						}

						$anchor = mosh_anchor_tag(
							array(
								'url' 	 => esc_url( site_url( '/' ) ),
								'text' 	 => esc_html__( 'Go To Home page', 'mosh' ),
							)
						);

						echo mosh_paragraph_tag(
							array(
								'text' 	 => esc_html( $wrongText ).' '.wp_kses_post( $anchor ),
							)
						);
						?>
						<div class="row">
							<div class="col-sm-4 offset-sm-4">
								<?php 
								// Search Form
								get_search_form();
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>