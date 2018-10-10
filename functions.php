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

require_once( MOSH_DIR_PATH_INC . 'class-epsilon-dashboard-autoloader.php' );
require_once( MOSH_DIR_PATH_INC . 'class-epsilon-init-dashboard.php' );



/**
 * Instantiate Mosh object
 *
 * Inside this object:
 * Enqueue scripts, Google font, Theme support features, Epsilon Dashboard .
 *
 */

$Mosh = new Mosh();