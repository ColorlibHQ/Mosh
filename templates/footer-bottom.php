<!-- Fotter Bottom Area -->
<div class="footer-bottom-area">
    <div class="container h-100">
        <div class="row h-100">
            <div class="col-12 h-100">
                <div class="footer-bottom-content h-100 d-md-flex justify-content-between align-items-center">
                	<?php 
					// Copy right text
					$copyText = sprintf( __( 'Copyright &copy; %s All rights reserved. | This template is made with %s by <a href="%s" target="_blank">Colorlib</a>', 'mosh' ), date('Y') ,'<i class="fa fa-heart-o" aria-hidden="true"></i>', 'https://colorlib.com' );
												
					$setCopyright = mosh_opt('mosh-copyright-text-settings');
					
					if( $setCopyright ){
						$copyText = $setCopyright;
					}
						
                	?>
                    <div class="copyright-text">
                        <p><?php echo wp_kses_post( $copyText ); ?></p>
                    </div>
                    
                	<?php 
                	// Social Menu
			            if( mosh_opt('mosh-footersocial-toggle-settings') ){
			                if( has_nav_menu('social-menu') ){  
			                
			                    $args = array(
			                        'theme_location' => 'social-menu',
			                        'container' => '',
			                        'depth'          => 1,
			                        'menu_class'     => 'topbar-social-mobile footer-social-info',
			                        'walker'         => new mosh_social_navwalker(),
			                    );  
			                    echo '<div class="footer-social-info">';
			                    wp_nav_menu( $args );
			                    echo '</div>';
			                }
			            }
                	?>
                </div>
            </div>
        </div>
    </div>
</div>