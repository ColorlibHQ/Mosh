<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Actions required
 */

wp_enqueue_style( 'plugin-install' );
wp_enqueue_script( 'plugin-install' );
wp_enqueue_script( 'updates' );
?>

<div class="feature-section action-required demo-import-boxed" id="plugin-filter">

	<?php
	global $mosh_required_actions;

	if ( ! empty( $mosh_required_actions ) ):

		/* mosh_show_required_actions is an array of true/false for each required action that was dismissed */
		$mosh_show_required_actions = get_option( "mosh_show_required_actions" );
		$hooray = true;

		foreach ( $mosh_required_actions as $mosh_required_action_key => $mosh_required_action_value ):
			$hidden = false;
			if ( @$mosh_show_required_actions[ $mosh_required_action_value['id'] ] === false ) {
				$hidden = true;
			}
			if ( @$mosh_required_action_value['check'] ) {
				continue;
			}
			?>
			<div class="mosh-action-required-box">
				<?php if ( ! $hidden ): ?>
					<span data-action="dismiss" class="dashicons dashicons-visibility mosh-required-action-button"
					      id="<?php echo esc_attr( $mosh_required_action_value['id'] ); ?>"></span>
				<?php else: ?>
					<span data-action="add" class="dashicons dashicons-hidden mosh-required-action-button"
					      id="<?php echo esc_attr( $mosh_required_action_value['id'] ); ?>"></span>
				<?php endif; ?>
				<h3><?php if ( ! empty( $mosh_required_action_value['title'] ) ): echo esc_html( $mosh_required_action_value['title'] ); endif; ?></h3>
				<p>
					<?php if ( ! empty( $mosh_required_action_value['description'] ) ): echo esc_html( $mosh_required_action_value['description'] ); endif; ?>
					<?php if ( ! empty( $mosh_required_action_value['help'] ) ): echo '<br/>' . wp_kses_post( $mosh_required_action_value['help'] ); endif; ?>
				</p>
				<?php

				if ( ! empty( $mosh_required_action_value['plugin_slug'] ) ) {
					$active = $this->check_active( $mosh_required_action_value['plugin_slug'], $mosh_required_action_value['plugin_filrname'] );
					$url    = $this->create_action_link( $active['needs'], $mosh_required_action_value['plugin_slug'], $mosh_required_action_value['plugin_filrname'] );
										
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

					?>
					<p class="plugin-card-<?php echo esc_attr( $mosh_required_action_value['plugin_slug'] ) ?> action_button <?php echo esc_attr( ( $active['needs'] !== 'install' && $active['status'] ) ? 'active' : '' ); ?>">
						<a data-slug="<?php echo esc_attr( $mosh_required_action_value['plugin_slug'] ) ?>"
						   class="<?php echo esc_attr( $class ); ?>"
						   href="<?php echo esc_url( $url ) ?>"> <?php echo esc_html( $label ) ?> </a>
					</p>
					<?php
				};
				?>
			</div>
			<?php
			$hooray = false;
		endforeach;
	endif;

	if ( $hooray ):
		echo '<span class="hooray">' . __( 'Hooray! There are no required actions for you right now.', 'mosh' ) . '</span>';
	endif;
	?>

</div>
