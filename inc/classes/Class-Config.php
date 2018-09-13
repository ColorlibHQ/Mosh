<?php
/**
 * @Packge       : Mosh
 * @Version      : 1.0
 * @Author       : Colorlib
 * @Author       URI : http://colorlib.com/wp/
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

// Final Class
final class Mosh {

	// Theme Version
	private $mosh_version = '1.0';

	// Minimum WordPress Version required
	private $min_wp = '4.0';

	// Minimum PHP version required
	private $min_php = '5.6.25';

	// Theme init
	public function init() {

		$this->setup();
	}

	// Theme setup
	private function setup() {

		// Create enqueue class instance
		$enqueu          = new mosh_Enqueue();
		$enqueu->scripts = $this->enqueue();
		$enqueu->mosh_scripts_enqueue_init();

		// Welcome screen
		$this->init_welcome_screen();

	}

	// Theme Support
	public function support() {
		// content width
		$GLOBALS['content_width'] = apply_filters( 'mosh_content_width', 751 );


		// text domain for translation.
		load_theme_textdomain( 'mosh', MOSH_DIR_PATH . '/languages' );

		// support title tage
		add_theme_support( 'title-tag' );

		// support logo
		add_theme_support( 'custom-logo' );

		//  support post format
		add_theme_support( 'post-formats', array( 'video', 'audio' ) );

		// support post-thumbnails
		add_theme_support( 'post-thumbnails', array( 'post', 'mosh-portfolio' ) );

		// Latest post thumbnail Widget thumbnail size
		add_image_size( 'mosh_widget_post_thumb', 70, 70, true );

		// support custom background
		add_theme_support( 'custom-background', array(
			'default-color' => '#fff',
		) );

		// support custom header
		add_theme_support( 'custom-header' );

		// support automatic feed links
		add_theme_support( 'automatic-feed-links' );

		// support html5
		add_theme_support( 'html5' );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// register nav menu
		register_nav_menus( array(
			'primary-menu' => esc_html__( 'Primary Menu', 'mosh' ),
			'social-menu'  => esc_html__( 'Social Menu', 'mosh' ),
		) );

		// editor style
		add_editor_style( 'assets/css/editor-style.css' );

	} // end support method

	// enqueue theme style and script
	private function enqueue() {

		$cssPath = MOSH_DIR_CSS_URI;
		$jsPath  = MOSH_DIR_JS_URI;
		$apiKey  = mosh_opt( 'mosh_map_apikey' );

		$scripts = array(
			'style'   => array(
				array(
					'handler' => 'google-font',
					'file'    => $this->google_font(),
				),
				array(
					'handler'    => 'bootstrap',
					'file'       => $cssPath . 'bootstrap.min.css',
					'dependency' => array(),
					'version'    => '4.0.0',
				),
				array(
					'handler'    => 'font-awesome',
					'file'       => $cssPath . 'font-awesome.min.css',
					'dependency' => array(),
					'version'    => '4.7.0',
				),
				array(
					'handler'    => 'animate',
					'file'       => $cssPath . 'animate.css',
					'dependency' => array(),
					'version'    => '3.5.2',
				),
				array(
					'handler'    => 'mosh',
					'file'       => $cssPath . 'main.css',
					'dependency' => array(),
					'version'    => $this->mosh_version,
				),
				array(
					'handler'    => 'mosh-responsive',
					'file'       => $cssPath . 'responsive.css',
					'dependency' => array(),
					'version'    => $this->mosh_version,
				),
				array(
					'handler' => 'mosh-style',
					'file'    => get_stylesheet_uri(),
				),
			),
			'scripts' => array(
				array(
					'handler'    => 'popper',
					'file'       => $jsPath . 'popper.min.js',
					'dependency' => array( 'jquery' ),
					'version'    => '1.0',
					'in_footer'  => true,
				),
				array(
					'handler'    => 'bootstrap',
					'file'       => $jsPath . 'bootstrap.min.js',
					'dependency' => array( 'jquery' ),
					'version'    => '4.0.0',
					'in_footer'  => true,
				),
				array(
					'handler'    => 'wow',
					'file'       => $jsPath . 'wow.js',
					'dependency' => array( 'jquery' ),
					'version'    => '1.1.3',
					'in_footer'  => true,
				),
				array(
					'handler'    => 'scrollup',
					'file'       => $jsPath . 'scrollup.js',
					'dependency' => array( 'jquery' ),
					'version'    => '2.4.1',
					'in_footer'  => true,
				),
				array(
					'handler'    => 'mosh-active',
					'file'       => $jsPath . 'active.js',
					'dependency' => array( 'jquery' ),
					'version'    => $this->mosh_version,
					'in_footer'  => true,
				),

			),
		);

		return $scripts;

	} // end enqueu method

	/**
	 * Initiate the welcome screen
	 */

	function init_welcome_screen() {
		// Welcome screen
		if ( is_admin() ) {
			global $mosh_required_actions, $mosh_recommended_plugins;

			$mosh_recommended_plugins = array(
				'elementor'                 => array( 'recommended' => true, 'plugin_filrname' => 'elementor' ),
				'contact-form-7'            => array( 'recommended' => true, 'plugin_filrname' => 'wp-contact-form-7' ),
				'colorlib-login-customizer' => array(
					'recommended'     => true,
					'plugin_filrname' => 'colorlib-login-customizer',
				),
				'simple-custom-post-order'  => array(
					'recommended'     => true,
					'plugin_filrname' => 'simple-custom-post-order',
				),

			);

			/*
			 * id - unique id; required
			 * title
			 * description
			 * check - check for plugins (if installed)
			 * plugin_slug - the plugin's slug (used for installing the plugin)
			 *
			 */
			$mosh_required_actions = array(

				array(
					"id"          => 'mosh-companion-plugin',
					"title"       => Mosh_Notify_System::create_plugin_title( 'Mosh Companion', 'mosh' ),
					"description" => __( 'It is highly recommended that you install the mosh companion.', 'mosh' ),
					'plugin_slug' => 'mosh-companion',
					'plugin_filrname' => 'mosh-companion',
					"check"       => Mosh_Notify_System::check_plugin_is_active( 'mosh-companion' ),
				),
				array(
					"id"          => 'mosh-elementor',
					"title"       => Mosh_Notify_System::create_plugin_title( 'Elementor', 'elementor' ),
					'description' => __( 'It is highly recommended that you install the elementor.', 'mosh' ),
					'plugin_slug' => 'elementor',
					'plugin_filrname' => 'elementor',
					"check"       => Mosh_Notify_System::check_plugin_active( 'elementor', 'elementor' ),
				),

				array(
					"id"          => 'mosh-contact-form',
					"title"       => Mosh_Notify_System::create_plugin_title( 'WP Contact Form 7', 'contact-form-7' ),
					'description' => __( 'It is highly recommended that you install the WP Contact Form 7.', 'mosh' ),
					'plugin_slug' => 'contact-form-7',
					'plugin_filrname' => 'wp-contact-form-7',
					"check"       => Mosh_Notify_System::check_plugin_active( 'contact-form-7', 'wp-contact-form-7' ),
				),
				array(
					"id"          => 'mosh-oneclick-demo-importer',
					"title"       => Mosh_Notify_System::create_plugin_title( 'One Click Demo Import', 'one-click-demo-import' ),
					'description' => __( 'It is highly recommended that you install the One Click Demo Import to demo import.', 'mosh' ),
					'plugin_slug' => 'one-click-demo-import',
					'plugin_filrname' => 'one-click-demo-import',
					"check"       => Mosh_Notify_System::check_plugin_active( 'one-click-demo-import', 'one-click-demo-import' ),
				),
				array(
					"id"          => 'mosh-req-ac-install-data',
					"title"       => esc_html__( 'Import Demo Data', 'mosh' ),
					"description" => esc_html__( 'Before demo install make sure mosh companion and one click demo importer are installed.', 'mosh' ),
					"help"        => '<a class="button button-primary" target="_blank"  href="' . self_admin_url( 'themes.php?page=mosh-demo-import' ) . '">' . __( 'Jump To Import', 'mosh' ) . '</a>',
					"check"       => Mosh_Notify_System::check_mosh_importers(),
				),
				array(
					"id"          => 'mosh-req-ac-static-latest-news',
					"title"       => esc_html__( 'Set front page to static', 'mosh' ),
					"description" => esc_html__( 'If you just installed Mosh, and are not able to see the front-page demo, you need to go to Settings -> Reading , Front page displays and select "Static Page".', 'mosh' ),
					"help"        => 'If you need more help understanding how this works, check out the following <a target="_blank"  href="https://codex.wordpress.org/Creating_a_Static_Front_Page#WordPress_Static_Front_Page_Process">link</a>. <br/><br/> <a class="button button-secondary" target="_blank"  href="' . self_admin_url( 'options-reading.php' ) . '">' . __( 'Set manually', 'mosh' ) . '</a> <a class="button button-primary"  href="' . wp_nonce_url( self_admin_url( 'themes.php?page=mosh-welcome&tab=recommended_actions&action=set_page_automatic' ), 'set_page_automatic' ) . '">' . __( 'Set automatically', 'mosh' ) . '</a>',
					"check"       => Mosh_Notify_System::is_not_static_page(),
				),
			);

			new Mosh_Welcome_Screen();
		}
	}


	// Google Font
	private function google_font() {

		$fontUrl = '';

		if ( 'off' !== _x( 'on', 'Google font: on or off', 'mosh' ) ) {

			$font_families = array(
				'Roboto:400,500,700,900',
			);

			$familyArgs = array(
				'family' => htmlentities( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin, latin-text' ),
			);

			$fontUrl = add_query_arg( $familyArgs, '//fonts.googleapis.com/css' );
		}

		return esc_url_raw( $fontUrl );

	} //End google_font method

} // End Mosh Class


?>