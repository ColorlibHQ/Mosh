<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Recommended Plugins
 */
global $mosh_required_actions, $mosh_recommended_plugins;

wp_enqueue_style( 'plugin-install' );
wp_enqueue_script( 'plugin-install' );
wp_enqueue_script( 'updates' );
add_thickbox();
?>

<div class="feature-section recommended-plugins three-col demo-import-boxed" id="plugin-filter">
	<?php foreach ( $mosh_recommended_plugins as $plugin => $prop ) { ?>
		<?php

		$info   = $this->call_plugin_api( $plugin );
		$icon   = $this->check_for_icon( $info->icons );
		$active = $this->check_active( $plugin ,$prop['plugin_filrname'] );

		$url    = $this->create_action_link( $active['needs'], $plugin, $prop['plugin_filrname'] );


		$label  = '';



		switch ( $active['needs'] ) {
			case 'install':
				$class = 'install-now button';
				$label = __( 'Install', 'mosh' );
				break;
			case 'activate':
				$class = 'activate-now button button-primary';
				$label = __( 'Activate', 'mosh' );
				break;
			case 'deactivate':
				$class = 'deactivate-now button';
				$label = __( 'Deactivate', 'mosh' );
				break;
		}

		if ( ! empty( $prop['tracking_url'] ) ) {
			$url   = $prop['tracking_url'];
			$class = 'button';
			$label = __( 'Install', 'mosh' );
		}

		?>
		<div class="col plugin_box">
			<?php if ( $prop['recommended'] ): ?>
				<span class="recommended"><?php _e( 'Recommended', 'mosh' ); ?></span>
			<?php endif; ?>
			<img src="<?php echo esc_attr( $icon ) ?>" alt="plugin box image">
			<span
				class="version"><?php echo esc_html__( 'Version:', 'mosh' ); ?><?php echo esc_html( $info->version ) ?></span>
			<span
				class="separator"><?php esc_html_e( '|', 'mosh' ); ?></span> <?php echo wp_kses_post( $info->author ) ?>
			<div
				class="action_bar <?php echo esc_attr( ( $active['needs'] !== 'install' && $active['status'] ) ? 'active' : '' ); ?>">
				<span
					class="plugin_name"><?php echo esc_attr( ( $active['needs'] !== 'install' && $active['status'] ) ? 'Active: ' : '' ); ?><?php echo esc_html( $info->name ); ?></span>
			</div>
			<span
				class="plugin-card-<?php echo esc_attr( $plugin ) ?> action_button <?php echo esc_attr( ( $active['needs'] !== 'install' && $active['status'] ) ? 'active' : '' ); ?>">
				<a data-slug="<?php echo esc_attr( $plugin ) ?>" <?php echo ( ! empty( $prop['tracking_url'] ) ) ? ' target="_blank" ' : '' ?>
				   class="<?php echo esc_attr( $class ); ?>"
				   href="<?php echo esc_url( $url ) ?>"> <?php echo esc_attr( $label ) ?> </a>
			</span>
		</div>
	<?php } ?>
</div>