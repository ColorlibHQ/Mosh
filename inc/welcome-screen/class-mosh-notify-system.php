<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Notify_System
 */
class Mosh_Notify_System extends Epsilon_Notify_System {

	/**
	 * @return bool
	 */
	public static function has_widgets() {
		if ( ! is_active_sidebar( 'homepage-slider' ) && ! is_active_sidebar( 'content-area' ) ) {
			return false;
		}

		return true;
	}


	/**
	 * @return bool
	 */
	public static function check_mosh_importers() {

		$importeractive      = self::check_plugin_is_active( 'one-click-demo-import' );
		$moshcompanionactive = self::check_plugin_is_active( 'mosh-companion' );

		if ( $importeractive && $moshcompanionactive ) {
			return false;
		}


		return true;
	}

	/**
	 * @return array
	 */
	public static function _get_plugins() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return get_plugins();
	}

	/**
	 * @param $slug
	 *
	 * @return mixed
	 */
	public static function _get_plugin_basename_from_slug( $slug ) {
		if ( empty( self::$plugins ) ) {
			self::$plugins = array_keys( self::_get_plugins() );
		}
		$keys = self::$plugins;
		foreach ( $keys as $key ) {
			if ( preg_match( '|^' . $slug . '/|', $key ) ) {
				return $key;
			}
		}

		return $slug;
	}

	/**
	 * @param $slug
	 *
	 * @return bool
	 */
	public static function check_plugin_is_installed( $slug ) {
		$plugin_path = self::_get_plugin_basename_from_slug( $slug );
		if ( file_exists( trailingslashit( WP_PLUGIN_DIR ) . $plugin_path ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param $slug
	 *
	 * @return bool
	 */
	public static function check_plugin_is_active( $slug ) {
		$plugin_path = self::_get_plugin_basename_from_slug( $slug );
		if ( file_exists( trailingslashit( WP_PLUGIN_DIR ) . $plugin_path ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			return is_plugin_active( $plugin_path );
		}
	}

	/**
	 * @return String
	 */
	public static function create_plugin_title( $plugin_title, $plugin_slug ) {
		$installed = self::check_plugin_is_installed( $plugin_slug );
		if ( ! $installed ) {
			return __( 'Install : ', 'mosh' ) . $plugin_title;
		} elseif ( ! self::check_plugin_is_active( $plugin_slug ) && $installed ) {
			return __( 'Activate : ', 'mosh' ) . $plugin_title;
		} else {
			return __( 'Update : ', 'mosh' ) . $plugin_title;
		}
	}

	public static function check_cf7_active( $slug, $filename ) {
		if ( file_exists( ABSPATH . 'wp-content/plugins/' . $slug . '/' . $filename . '.php' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			return is_plugin_active( $slug . '/' . $filename . '.php' );
		}
	}

	/**
	 * @return bool
	 */
	public static function is_not_static_page() {
		return 'page' == get_option( 'show_on_front' ) ? true : false;
	}
}
