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
	public static function newmsag_has_posts() {
		$args  = array( "s" => 'Gary Johns: \'What is Aleppo\'' );
		$query = get_posts( $args );

		if ( ! empty( $query ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public static function has_content() {
		$check = array(
			'widgets' => self::has_widgets(),
			'posts'   => self::newmsag_has_posts(),
		);

		if ( $check['widgets'] && $check['posts'] ) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public static function check_wordpress_importer() {

		if (	file_exists( ABSPATH . 'wp-content/plugins/one-click-demo-import/one-click-demo-import.php' ) && 
				file_exists( ABSPATH . 'wp-content/plugins/mosh-companion/mosh-companion.php' )
			) {

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			$importeractive 	 = is_plugin_active( 'one-click-demo-import/one-click-demo-import.php' );
			$moshcompanionactive = is_plugin_active( 'mosh-companion/mosh-companion.php' );

			if( $importeractive && $moshcompanionactive ){
				return false;
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public static function check_plugin_is_installed( $slug ) {
		if ( file_exists( ABSPATH . 'wp-content/plugins/' . $slug . '/' . $slug . '.php' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public static function check_plugin_is_active( $slug ) {
		if ( file_exists( ABSPATH . 'wp-content/plugins/' . $slug . '/' . $slug . '.php' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			return is_plugin_active( $slug . '/' . $slug . '.php' );
		}
	}

	public static function has_import_plugin( $slug = NULL ) {
		$return = self::has_content();

		if ( $return ) {
			return true;
		}
		$check = array(
			'installed' => self::check_plugin_is_installed( $slug ),
			'active'    => self::check_plugin_is_active( $slug )
		);

		if ( ! $check['installed'] || ! $check['active'] ) {
			return false;
		}

		return true;
	}

	public static function has_import_plugins() {
		$check = array(
			'wordpress-importer'       => array( 'installed' => false, 'active' => false ),
			'widget-importer-exporter' => array( 'installed' => false, 'active' => false )
		);

		$content = self::has_content();
		$return  = false;
		if ( $content ) {
			return true;
		}

		$stop = false;
		foreach ( $check as $plugin => $val ) {
			if ( $stop ) {
				continue;
			}

			$check[ $plugin ]['installed'] = self::check_plugin_is_installed( $plugin );
			$check[ $plugin ]['active']    = self::check_plugin_is_active( $plugin );

			if ( ! $check[ $plugin ]['installed'] || ! $check[ $plugin ]['active'] ) {
				$return = true;
				$stop   = true;
			}

		}

		return $return;
	}
	/**
	 * @return Strign
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
	/**
	 * @return bool
	 */
	public static function check_plugin_update( $slug ) {

		$check = array(
			'installed' => self::check_plugin_is_installed( $slug ),
			'active'    => self::check_plugin_is_active( $slug ),
			'update'    => self::check_plugin_need_update( $slug ),
		);

		if ( ! $check['installed'] || ! $check['active'] || ! $check['update'] ) {
			return false;
		}

		return true;

	}
	/**
	 * @return bool
	 */
	public static function check_plugin_need_update( $slug ) {

		$update_plugin_transient = get_site_transient( 'update_plugins' );

		if ( isset( $update_plugin_transient->response ) ) {
			$plugins = $update_plugin_transient->response;

			foreach ( $plugins as $key => $plugin ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					return false;
				}
			}
		}

		return true;

	}
	/**
	 * @return bool
	 */
	public static function is_not_template_front_page() {
		$page_id = get_option( 'page_on_front' );

		return get_page_template_slug( $page_id ) == 'page-templates/frontpage-template.php' ? true : false;
	}
}
