<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Welcome Screen Class
 */
class Mosh_Welcome_Screen {

	/**
	 * Constructor for the welcome screen
	 */
	public function __construct() {
		/* create dashbord page */
		add_action( 'admin_menu', array( $this, 'mosh_welcome_register_menu' ) );

		/* activation notice */
		add_action( 'load-themes.php', array( $this, 'mosh_activation_admin_notice' ) );

		/* enqueue script and style for welcome screen */
		add_action( 'admin_enqueue_scripts', array( $this, 'mosh_welcome_style_and_scripts' ) );

		/* ajax callback for dismissable required actions */
		add_action( 'wp_ajax_mosh_dismiss_required_action', array(
			$this,
			'mosh_dismiss_required_action_callback'
		) );
		add_action( 'wp_ajax_nopriv_mosh_dismiss_required_action', array(
			$this,
			'mosh_dismiss_required_action_callback'
		) );

		/**
		 * Set the blog / static page automatically
		 */
		add_action( 'admin_init', array( $this, 'mosh_set_pages' ) );
	}

	/**
	 * Set the latest blog / static page automatically
	 */
	public function mosh_set_pages() {
		if ( ! empty( $_GET ) ) {
			/**
			 * Check action
			 */
			if ( ! empty( $_GET['action'] ) && $_GET['action'] === 'set_page_automatic' ) {
				$active_tab = $_GET['tab'];
				$about      = get_page_by_title( 'Home' );
				update_option( 'page_on_front', $about->ID );
				update_option( 'show_on_front', 'page' );

				// Set the blog page
				$blog = get_page_by_title( 'Blog' );
				update_option( 'page_for_posts', $blog->ID );

				wp_redirect( self_admin_url( 'themes.php?page=mosh-welcome&tab=' . $active_tab ) );
			}
		}
	}

	/**
	 * Creates the dashboard page
	 *
	 * @see   add_theme_page()
	 * @since 1.8.2.4
	 */
	public function mosh_welcome_register_menu() {
		$action_count = $this->count_actions();
		$title        = $action_count > 0 ? __( 'About Mosh', 'mosh' ) . '<span class="badge-action-count">' . esc_html( $action_count ) . '</span>' : __( 'About Mosh', 'mosh' );

		add_theme_page( __( 'About Mosh', 'mosh' ), $title, 'edit_theme_options', 'mosh-welcome', array(
			$this,
			'mosh_welcome_screen'
		) );
	}

	/**
	 * Adds an admin notice upon successful activation.
	 *
	 */
	public function mosh_activation_admin_notice() {
		global $pagenow;

		if ( is_admin() && ( 'themes.php' == $pagenow ) && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', array( $this, 'mosh_welcome_admin_notice' ), 99 );
		}
	}

	/**
	 * Display an admin notice linking to the welcome screen
	 *
	 */
	public function mosh_welcome_admin_notice() {
		?>
		<div class="updated notice is-dismissible">
			<p><?php echo sprintf( esc_html__( 'Welcome! Thank you for choosing Mosh! To fully take advantage of the best our theme can offer please make sure you visit our %swelcome page%s.', 'mosh' ), '<a href="' . esc_url( admin_url( 'themes.php?page=mosh-welcome' ) ) . '">', '</a>' ); ?></p>
			<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=mosh-welcome' ) ); ?>" class="button"
			      style="text-decoration: none;"><?php _e( 'Get started with Mosh', 'mosh' ); ?></a></p>
		</div>
		<?php
	}

	/**
	 * Load welcome screen css and javascript
	 *
	 */
	public function mosh_welcome_style_and_scripts( $hook_suffix ) {

		wp_enqueue_style( 'mosh-welcome-screen', get_template_directory_uri() . '/inc/welcome-screen/assets/css/welcome.css' );
		wp_enqueue_script( 'mosh-welcome-screen', get_template_directory_uri() . '/inc/welcome-screen/assets/js/welcome.js', array( 'jquery' ), '12123' );

		wp_localize_script( 'mosh-welcome-screen', 'moshWelcomeScreenObject', array(
			'nr_actions_required'      => absint( $this->count_actions() ),
			'ajaxurl'                  => esc_url( admin_url( 'admin-ajax.php' ) ),
			'template_directory'       => esc_url( get_template_directory_uri() ),
			'no_required_actions_text' => __( 'Hooray! There are no required actions for you right now.', 'mosh' )
		) );

	}

	/**
	 * Load scripts for customizer page
	 *
	 */
	public function mosh_welcome_scripts_for_customizer() {

		wp_enqueue_style( 'mosh-welcome-screen-customizer', get_template_directory_uri() . '/inc/welcome-screen/assets/css/welcome_customizer.css' );
		wp_enqueue_script( 'mosh-welcome-screen-customizer', get_template_directory_uri() . '/inc/welcome-screen/assets/js/welcome_customizer.js', array( 'jquery' ), '20120206', true );

		wp_localize_script( 'mosh-welcome-screen-customizer', 'moshWelcomeScreenCustomizerObject', array(
			'nr_actions_required' => absint( $this->count_actions() ),
			'aboutpage'           => esc_url( admin_url( 'themes.php?page=mosh-welcome&tab=recommended_actions' ) ),
			'customizerpage'      => esc_url( admin_url( 'customize.php#recommended_actions' ) ),
			'themeinfo'           => __( 'View Theme Info', 'mosh' ),
		) );
	}

	/**
	 * Dismiss required actions
	 *
	 * @since 1.8.2.4
	 */
	public function mosh_dismiss_required_action_callback() {

		global $mosh_required_actions;

		$action_id = ( isset( $_GET['id'] ) ) ? $_GET['id'] : 0;

		echo esc_html( $action_id ); /* this is needed and it's the id of the dismissable required action */

		if ( ! empty( $action_id ) ):

			/* if the option exists, update the record for the specified id */
			if ( get_option( 'mosh_show_required_actions' ) ):

				$mosh_show_required_actions = get_option( 'mosh_show_required_actions' );

				switch ( $_GET['todo'] ) {
					case 'add';
						$mosh_show_required_actions[ $action_id ] = true;
						break;
					case 'dismiss';
						$mosh_show_required_actions[ $action_id ] = false;
						break;
				}

				update_option( 'mosh_show_required_actions', $mosh_show_required_actions );

			/* create the new option,with false for the specified id */
			else:

				$mosh_show_required_actions_new = array();

				if ( ! empty( $mosh_required_actions ) ):

					foreach ( $mosh_required_actions as $mosh_required_action ):

						if ( $mosh_required_action['id'] == $action_id ):
							$mosh_show_required_actions_new[ $mosh_required_action['id'] ] = false;
						else:
							$mosh_show_required_actions_new[ $mosh_required_action['id'] ] = true;
						endif;

					endforeach;

					update_option( 'mosh_show_required_actions', $mosh_show_required_actions_new );

				endif;

			endif;

		endif;

		die(); // this is required to return a proper result
	}


	/**
	 *
	 */
	public function count_actions() {
		global $mosh_required_actions;

		$mosh_show_required_actions = get_option( 'mosh_show_required_actions' );
		if ( ! $mosh_show_required_actions ) {
			$mosh_show_required_actions = array();
		}

		$i = 0;
		foreach ( $mosh_required_actions as $action ) {
			$true      = false;
			$dismissed = false;

			if ( ! $action['check'] ) {
				$true = true;
			}

			if ( ! empty( $mosh_show_required_actions ) && isset( $mosh_show_required_actions[ $action['id'] ] ) && ! $mosh_show_required_actions[ $action['id'] ] ) {
				$true = false;
			}

			if ( $true ) {
				$i ++;
			}
		}


		return $i;
	}


	/**
	 * @param $slug
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function call_plugin_api( $slug ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

		if ( false === ( $call_api = get_transient( 'mosh_plugin_information_transient_' . $slug ) ) ) {
			$call_api = plugins_api( 'plugin_information', array(
				'slug'   => $slug,
				'fields' => array(
					'downloaded'        => false,
					'rating'            => false,
					'description'       => false,
					'short_description' => true,
					'donate_link'       => false,
					'tags'              => false,
					'sections'          => true,
					'homepage'          => true,
					'added'             => false,
					'last_updated'      => false,
					'compatibility'     => false,
					'tested'            => false,
					'requires'          => false,
					'downloadlink'      => false,
					'icons'             => true
				)
			) );
			set_transient( 'mosh_plugin_information_transient_' . $slug, $call_api, 30 * MINUTE_IN_SECONDS );
		}

		return $call_api;
	}

	/**
	 * @param $slug
	 *
	 * @return array
	 */
	public function check_active( $slug, $filename ) {

		if ( file_exists( ABSPATH . 'wp-content/plugins/' . $slug . '/' . $filename . '.php' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			$needs = is_plugin_active( $slug . '/' . $filename . '.php' ) ? 'deactivate' : 'activate';

			return array( 'status' => is_plugin_active( $slug . '/' . $filename . '.php' ), 'needs' => $needs );
		}

		return array( 'status' => false, 'needs' => 'install' );
	}

	/**
	 * @param $arr
	 *
	 * @return mixed
	 */
	public function check_for_icon( $arr ) {
		if ( ! empty( $arr['svg'] ) ) {
			$plugin_icon_url = $arr['svg'];
		} elseif ( ! empty( $arr['2x'] ) ) {
			$plugin_icon_url = $arr['2x'];
		} elseif ( ! empty( $arr['1x'] ) ) {
			$plugin_icon_url = $arr['1x'];
		} else {
			$plugin_icon_url = $arr['default'];
		}

		return $plugin_icon_url;
	}

	/**
	 * @param $state
	 * @param $slug
	 *
	 * @return string
	 */
	public function create_action_link( $state, $slug, $filename ) {
		switch ( $state ) {
			case 'install':
				return wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'install-plugin',
							'plugin' => $slug
						),
						network_admin_url( 'update.php' )
					),
					'install-plugin_' . $slug
				);
				break;
			case 'deactivate':
				return add_query_arg( array(
					                      'action'        => 'deactivate',
					                      'plugin'        => rawurlencode( $slug . '/' . $filename . '.php' ),
					                      'plugin_status' => 'all',
					                      'paged'         => '1',
					                      '_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $slug . '/' . $filename . '.php' ),
				                      ), admin_url( 'plugins.php' ) );
				break;
			case 'activate':
				return add_query_arg( array(
					                      'action'        => 'activate',
					                      'plugin'        => rawurlencode( $slug . '/' . $filename . '.php' ),
					                      'plugin_status' => 'all',
					                      'paged'         => '1',
					                      '_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $slug . '/' . $filename . '.php' ),
				                      ), admin_url( 'plugins.php' ) );
				break;
		}
	}

	/**
	 * Welcome screen content
	 *
	 * @since 1.8.2.4
	 */
	public function mosh_welcome_screen() {
		require_once( ABSPATH . 'wp-load.php' );
		require_once( ABSPATH . 'wp-admin/admin.php' );
		require_once( ABSPATH . 'wp-admin/admin-header.php' );

		$mosh      = wp_get_theme();
		$active_tab   = isset( $_GET['tab'] ) ? $_GET['tab'] : 'getting_started';
		$action_count = $this->count_actions();

		?>

		<div class="wrap about-wrap epsilon-wrap">

			<h1><?php echo __( 'Welcome to Mosh! - Version ', 'mosh' ) . esc_html( $mosh['Version'] ); ?></h1>

			<div
				class="about-text"><?php echo esc_html__( 'Mosh is now installed and ready to use! Get ready to build something beautiful. We hope you enjoy it! We want to make sure you have the best experience using Mosh and that is why we gathered here all the necessary information for you. We hope you will enjoy using Mosh, as much as we enjoy creating great products.', 'mosh' ); ?></div>

			<div class="wp-badge epsilon-welcome-logo"></div>


			<h2 class="nav-tab-wrapper wp-clearfix">
				<a href="<?php echo esc_url( admin_url( 'themes.php?page=mosh-welcome&tab=getting_started' ) ); ?>"
				   class="nav-tab <?php echo esc_attr( $active_tab == 'getting_started' ? 'nav-tab-active' : '' ); ?>"><?php echo esc_html__( 'Getting Started', 'mosh' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'themes.php?page=mosh-welcome&tab=recommended_actions' ) ); ?>"
				   class="nav-tab <?php echo esc_attr( $active_tab == 'recommended_actions' ? 'nav-tab-active' : '' ); ?> "><?php echo esc_html__( 'Recommended Actions', 'mosh' ); ?>
                    <?php echo wp_kses_post( $action_count > 0 ? '<span class="badge-action-count">' . esc_html( $action_count ) . '</span>' : '' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'themes.php?page=mosh-welcome&tab=recommended_plugins' ) ); ?>"
				   class="nav-tab <?php echo esc_attr( $active_tab == 'recommended_plugins' ? 'nav-tab-active' : '' ); ?> "><?php echo esc_html__( 'Recommended Plugins', 'mosh' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'themes.php?page=mosh-welcome&tab=support' ) ); ?>"
				   class="nav-tab <?php echo esc_attr( $active_tab == 'support' ? 'nav-tab-active' : '' ); ?> "><?php echo esc_html__( 'Support', 'mosh' ); ?></a>
			</h2>

			<?php
			switch ( $active_tab ) {
				case 'getting_started':
					require_once get_template_directory() . '/inc/welcome-screen/sections/getting-started.php';
					break;
				case 'recommended_actions':
					require_once get_template_directory() . '/inc/welcome-screen/sections/actions-required.php';
					break;
				case 'recommended_plugins':
					require_once get_template_directory() . '/inc/welcome-screen/sections/recommended-plugins.php';
					break;
				case 'support':
					require_once get_template_directory() . '/inc/welcome-screen/sections/support.php';
					break;
				default:
					require_once get_template_directory() . '/inc/admin/welcome-screen/sections/getting-started.php';
					break;
			}
			?>


		</div><!--/.wrap.about-wrap-->

		<?php
	}
}
