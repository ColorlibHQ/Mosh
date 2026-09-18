<?php
/**
 * @Packge       : Colorlib
 * @Version      : 1.0
 * @Author       : Colorlib
 * @Author       URI : http://colorlib.com/wp/
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


/**
 *
 * Define constant
 *
 */

// Base URI
if ( ! defined( 'MOSH_DIR_URI' ) ) {
	define( 'MOSH_DIR_URI', get_template_directory_uri() . '/' );
}

// assets URI
if ( ! defined( 'MOSH_DIR_ASSETS_URI' ) ) {
	define( 'MOSH_DIR_ASSETS_URI', MOSH_DIR_URI . 'assets/' );
}

// Css File URI
if ( ! defined( 'MOSH_DIR_CSS_URI' ) ) {
	define( 'MOSH_DIR_CSS_URI', MOSH_DIR_ASSETS_URI . 'css/' );
}

// Js File URI
if ( ! defined( 'MOSH_DIR_JS_URI' ) ) {
	define( 'MOSH_DIR_JS_URI', MOSH_DIR_ASSETS_URI . 'js/' );
}

// Icon Images
if ( ! defined( 'MOSH_DIR_ICON_IMG_URI' ) ) {
	define( 'MOSH_DIR_ICON_IMG_URI', MOSH_DIR_URI . 'img/core-img/' );
}

// Base Directory
if ( ! defined( 'MOSH_DIR_PATH' ) ) {
	define( 'MOSH_DIR_PATH', get_parent_theme_file_path() . '/' );
}

//Inc Folder Directory
if ( ! defined( 'MOSH_DIR_PATH_INC' ) ) {
	define( 'MOSH_DIR_PATH_INC', MOSH_DIR_PATH . 'inc/' );
}

//Mosh Libraries Folder Directory
if ( ! defined( 'MOSH_DIR_PATH_LIBS' ) ) {
	define( 'MOSH_DIR_PATH_LIBS', MOSH_DIR_PATH_INC . 'libraries/' );
}

//Classes Folder Directory
if ( ! defined( 'MOSH_DIR_PATH_CLASSES' ) ) {
	define( 'MOSH_DIR_PATH_CLASSES', MOSH_DIR_PATH_INC . 'classes/' );
}

//Hooks Folder Directory
if ( ! defined( 'MOSH_DIR_PATH_HOOKS' ) ) {
	define( 'MOSH_DIR_PATH_HOOKS', MOSH_DIR_PATH_INC . 'hooks/' );
}

// Admin Enqueue script
function mosh_admin_script(){
    wp_enqueue_style( 'mosh-admin', get_template_directory_uri().'/assets/css/mosh_admin.css', false, '1.0.0' );
    wp_enqueue_script( 'mosh_admin', get_template_directory_uri().'/assets/js/mosh_admin.js', false, '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'mosh_admin_script' );



/**
 * Include File
 *
 */
require_once( MOSH_DIR_PATH_INC . 'mosh-breadcrumbs.php' );
require_once( MOSH_DIR_PATH_INC . 'mosh-widgets-reg.php' );
require_once( MOSH_DIR_PATH_INC . 'wp_bootstrap_navwalker.php' );
require_once( MOSH_DIR_PATH_INC . 'mosh-functions.php' );
require_once( MOSH_DIR_PATH_INC . 'mosh-commoncss.php' );
require_once( MOSH_DIR_PATH_INC . 'support-functions.php' );
require_once( MOSH_DIR_PATH_INC . 'wp-html-helper.php' );
require_once( MOSH_DIR_PATH_INC . 'wp_bootstrap_pagination.php' );
require_once( MOSH_DIR_PATH_INC . 'customizer/customizer.php' );
require_once( MOSH_DIR_PATH_CLASSES . 'Class-Enqueue.php' );
require_once( MOSH_DIR_PATH_CLASSES . 'Class-Config.php' );
require_once( MOSH_DIR_PATH_HOOKS . 'hooks.php' );
require_once( MOSH_DIR_PATH_HOOKS . 'hooks-functions.php' );



/**
 * Instantiate Mosh object
 *
 * Inside this object:
 * Enqueue scripts, Google font, Theme support features, Epsilon Dashboard .
 *
 */

$Mosh = new Mosh();

/**
 * Editor and markup support this theme predates.
 */
if ( ! function_exists( 'mosh_modern_supports' ) ) {
	function mosh_modern_supports() {
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
	}
	add_action( 'after_setup_theme', 'mosh_modern_supports', 20 );
}

/**
 * The theme's Customizer controls.
 *
 * Replaces the Epsilon framework: same fields and stored values,
 * built on core's Customizer API.
 */
require_once get_template_directory() . '/inc/customizer/colorlib-customizer/colorlib-customizer.php';
