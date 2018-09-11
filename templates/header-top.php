<!-- ***** Header Area Start ***** -->
<header class="header_area">
    <div class="container-fluid h-100">
        <div class="row h-100">
            <!-- Menu Area Start -->
            <div class="col-12 h-100">
                <div class="menu_area h-100">
                    <nav class="navbar h-100 navbar-expand-lg align-items-center">
						<?php 
						// Header Logo
						echo mosh_theme_logo('navbar-brand');
						?>
                        <!-- Menu Area -->
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mosh-navbar" aria-controls="mosh-navbar" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

                        <div class="collapse navbar-collapse justify-content-end" id="mosh-navbar">
                            <?php 
                            if( has_nav_menu( 'primary-menu' ) ){
                                $args = array(
                                    'theme_location' => 'primary-menu',
                                    'container'      => '',
                                    'depth'          => 2,
                                    'menu_id'        => 'nav',
                                    'menu_class'     => 'navbar-nav animated',
                                    'fallback_cb'    => 'mosh_bootstrap_navwalker::fallback',
                                    'walker'         => new mosh_bootstrap_navwalker(),
                                );  
                                wp_nav_menu( $args );
                            }

                            // Search Form Area Start
                            
                            if( mosh_opt('mosh-searchopt-toggle-settings') ):
                            ?>
                            <div class="search-form-area animated">
                                <form action="<?php echo esc_url( site_url('/') ); ?>" method="get">
                                    <input type="search" name="s" id="search" placeholder="<?php esc_attr_e( 'Type keywords &amp; hit enter', 'mosh' ) ?>">
                                    <button type="submit" class="d-none"><img src="<?php echo esc_url( MOSH_DIR_ICON_IMG_URI ); ?>search-icon.png" alt="<?php esc_attr_e( 'Search', 'mosh' );?>"></button>
                                </form>
                            </div>
                            <div class="search-button">
                                <a href="#" id="search-btn"><img src="<?php echo esc_url( MOSH_DIR_ICON_IMG_URI ); ?>search-icon.png" alt="<?php esc_attr_e( 'Search', 'mosh' );?>"></a>
                            </div>
                            <?php
                            endif;
                            // Login/Register btn
                            $buttonOneText = mosh_opt('mosh_btnone_text');
                            $buttonOneUrl  = mosh_opt('mosh_btnone_url'); 
                            $buttonTwoText = mosh_opt('mosh_btntwo_text'); 
                            $buttonTwoUrl  = mosh_opt('mosh_btntwo_url');

                            if( $buttonOneUrl || $buttonTwoUrl ){
                                echo '<div class="login-register-btn">';
                                    // Button 1
                                    if( $buttonOneUrl && $buttonOneText ){
                                        echo mosh_anchor_tag(
                                            array(
                                                'url'  => esc_url( $buttonOneUrl ),
                                                'text' => esc_html( $buttonOneText  ),
                                            )
                                        );
                                    }
                                    // Divider
                                    if( $buttonOneUrl && $buttonTwoUrl ){
                                        echo '<span>'.esc_html( '/', 'mosh' ).'</span>';
                                    }
                                    // Button 2
                                    if( $buttonTwoUrl && $buttonTwoText ){
                                        echo mosh_anchor_tag(
                                            array(
                                                'url'  => esc_url( $buttonTwoUrl ),
                                                'text' => esc_html( $buttonTwoText ),
                                            )
                                        );
                                    }
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>