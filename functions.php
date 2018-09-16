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

/*
*Elementor Partner ID
*/
if ( ! defined( 'ELEMENTOR_PARTNER_ID' ) ) {
	define( 'ELEMENTOR_PARTNER_ID', 1266 );
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

//Colorlib framework Folder Directory
if ( ! defined( 'MOSH_DIR_PATH_FRAM' ) ) {
	define( 'MOSH_DIR_PATH_FRAM', MOSH_DIR_PATH_INC . 'mosh-framework/' );
}

//Classes Folder Directory
if ( ! defined( 'MOSH_DIR_PATH_CLASSES' ) ) {
	define( 'MOSH_DIR_PATH_CLASSES', MOSH_DIR_PATH_INC . 'classes/' );
}

//Hooks Folder Directory
if ( ! defined( 'MOSH_DIR_PATH_HOOKS' ) ) {
	define( 'MOSH_DIR_PATH_HOOKS', MOSH_DIR_PATH_INC . 'hooks/' );
}

//Widgets Folder Directory
if ( ! defined( 'MOSH_DIR_PATH_WIDGET' ) ) {
	define( 'MOSH_DIR_PATH_WIDGET', MOSH_DIR_PATH_INC . 'widgets/' );
}

//Elementor Folder Directory
if ( ! defined( 'MOSH_DIR_PATH_ELEMENTOR' ) ) {
	define( 'MOSH_DIR_PATH_ELEMENTOR', MOSH_DIR_PATH_INC . 'elementor-widgets/' );
}

//Elementor Widgets Folder Directory
if ( ! defined( 'MOSH_DIR_PATH_ELEMENTOR_WIDGETS' ) ) {
	define( 'MOSH_DIR_PATH_ELEMENTOR_WIDGETS', MOSH_DIR_PATH_INC . 'elementor-widgets/widgets/' );
}


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
require_once( MOSH_DIR_PATH_FRAM . 'customizer/sanitization-callbacks.php' );
require_once( MOSH_DIR_PATH_FRAM . 'customizer/customizer.php' );
require_once( MOSH_DIR_PATH_FRAM . 'epsilon-framework/class-epsilon-framework.php' );
require MOSH_DIR_PATH_INC . 'welcome-screen/class-mosh-notify-system.php';
require MOSH_DIR_PATH_INC . 'welcome-screen/class-mosh-welcome-screen.php';
//
require_once( MOSH_DIR_PATH_CLASSES . 'Class-Enqueue.php' );
require_once( MOSH_DIR_PATH_CLASSES . 'Class-Config.php' );
require_once( MOSH_DIR_PATH_HOOKS . 'hooks.php' );
require_once( MOSH_DIR_PATH_HOOKS . 'hooks-functions.php' );


// Colorlib global variable define
global $mosh;
$mosh['moshobj'] = new Mosh();


// Colorlib theme support
add_action( 'after_setup_theme', 'mosh_themesupport' );
function mosh_themesupport() {
	global $mosh;
	$moshobj = $mosh['moshobj'];
	$moshobj->support();
}

// Colorlib theme init
add_action( 'init', 'mosh_init' );
function mosh_init() {
	global $mosh;
	$moshobj = $mosh['moshobj'];
	$moshobj->init();
}