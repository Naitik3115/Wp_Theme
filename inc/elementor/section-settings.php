<?php
/**
 * "Home Sections → Active Sections": choose which Elementor section replaces each homepage slot,
 * create starter sections, and a "Set as active" row action.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function () {
	add_submenu_page(
		'edit.php?post_type=atelier_section',
		__( 'Active Sections', 'atelier-b2b' ),
		__( 'Active Sections', 'atelier-b2b' ),
		ATELIER_HEADER_CAP,
		'atelier-section-settings',
		'atelier_section_settings_page'
	);
} );

add_action( 'admin_init', function () {
	register_setting(
		'atelier_sections_group',
		'atelier_active_sections',
		array(
			'type'              => 'array',
			'default'           => array(),
			'sanitize_callback' => 'atelier_sanitize_active_sections',
		)
	);
} );

add_filter( 'option_page_capability_atelier_sections_group', function () {
	return ATELIER_HEADER_CAP;
} );

function atelier_section_is_valid_for_slot( $id, $slot ) {
	$post = $id ? get_post( $id ) : null;
	return $post
		&& 'atelier_section' === $post->post_type
		&& 'publish' === $post->post_status
		&& get_post_meta( $id, '_atelier_section_slot', true ) === $slot;
}

function atelier_sanitize_active_sections( $value ) {
	$old = get_option( 'atelier_active_sections', array() );
	$old = is_array( $old ) ? $old : array();
	$out = array();
	foreach ( array_keys( atelier_section_slots() ) as $slot ) {
		$id = isset( $value[ $slot ] ) ? absint( $value[ $slot ] ) : 0;
		if ( 0 === $id ) {
			$out[ $slot ] = 0;
		} elseif ( atelier_section_is_valid_for_slot( $id, $slot ) ) {
			$out[ $slot ] = $id;
		} else {
			$out[ $slot ] = isset( $old[ $slot ] ) ? absint( $old[ $slot ] ) : 0;
			add_settings_error( 'atelier_active_sections', 'invalid_' . $slot, sprintf( /* translators: %s slot */ __( 'Invalid selection for %s – previous value kept.', 'atelier-b2b' ), $slot ) );
		}
	}
	return $out;
}

function atelier_get_section_choices( $slot ) {
	$posts = get_posts(
		array(
			'post_type'      => 'atelier_section',
			'post_status'    => 'publish',
			'posts_per_page' => 50,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
			'meta_key'       => '_atelier_section_slot', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => $slot, // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	$out = array();
	foreach ( $posts as $p ) {
		$out[ $p->ID ] = $p->post_title ? $p->post_title : sprintf( '#%d', $p->ID );
	}
	return $out;
}

function atelier_section_settings_page() {
	if ( ! current_user_can( ATELIER_HEADER_CAP ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'atelier-b2b' ) );
	}
	$notice = isset( $_GET['atelier_notice'] ) ? sanitize_key( wp_unslash( $_GET['atelier_notice'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$msgs   = array(
		'created'      => array( 'success', __( 'Starter section created and activated.', 'atelier-b2b' ) ),
		'activated'    => array( 'success', __( 'Section set as active.', 'atelier-b2b' ) ),
		'no_elementor' => array( 'error', __( 'Elementor must be installed and active to create starter sections.', 'atelier-b2b' ) ),
		'failed'       => array( 'error', __( 'The section could not be created or activated.', 'atelier-b2b' ) ),
	);
	$opt = get_option( 'atelier_active_sections', array() );
	$opt = is_array( $opt ) ? $opt : array();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Home Sections – Active Sections', 'atelier-b2b' ); ?></h1>
		<?php if ( isset( $msgs[ $notice ] ) ) : ?>
			<div class="notice notice-<?php echo esc_attr( $msgs[ $notice ][0] ); ?> is-dismissible"><p><?php echo esc_html( $msgs[ $notice ][1] ); ?></p></div>
		<?php endif; ?>
		<?php settings_errors( 'atelier_active_sections' ); ?>
		<p><?php echo atelier_elementor_active() ? esc_html__( 'Elementor is active.', 'atelier-b2b' ) : '<strong>' . esc_html__( 'Elementor is not active – the original PHP sections are being used.', 'atelier-b2b' ) . '</strong>'; ?></p>

		<form method="post" action="options.php">
			<?php settings_fields( 'atelier_sections_group' ); ?>
			<table class="form-table" role="presentation">
				<?php foreach ( atelier_section_slots() as $slot => $info ) : ?>
					<?php
					$current = isset( $opt[ $slot ] ) ? absint( $opt[ $slot ] ) : 0;
					$valid   = atelier_get_active_section_id( $slot );
					?>
					<tr>
						<th scope="row"><label for="atelier_slot_<?php echo esc_attr( $slot ); ?>"><?php echo esc_html( $info['label'] ); ?></label></th>
						<td>
							<select id="atelier_slot_<?php echo esc_attr( $slot ); ?>" name="atelier_active_sections[<?php echo esc_attr( $slot ); ?>]">
								<option value="0" <?php selected( $current, 0 ); ?>><?php esc_html_e( 'Original PHP section (fallback)', 'atelier-b2b' ); ?></option>
								<?php foreach ( atelier_get_section_choices( $slot ) as $id => $title ) : ?>
									<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $current, $id ); ?>><?php echo esc_html( $title ); ?></option>
								<?php endforeach; ?>
							</select>
							<?php if ( $valid && atelier_elementor_active() ) : ?>
								<a href="<?php echo esc_url( atelier_header_edit_url( $valid ) ); ?>"><?php esc_html_e( 'Edit with Elementor', 'atelier-b2b' ); ?></a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button( __( 'Save active sections', 'atelier-b2b' ) ); ?>
		</form>

		<hr>
		<h2><?php esc_html_e( 'Starter sections', 'atelier-b2b' ); ?></h2>
		<p><?php esc_html_e( 'Creates an Elementor section that recreates the current design exactly, then activates it.', 'atelier-b2b' ); ?></p>
		<?php foreach ( atelier_section_slots() as $slot => $info ) : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:1em">
				<input type="hidden" name="action" value="atelier_create_starter_section">
				<input type="hidden" name="slot" value="<?php echo esc_attr( $slot ); ?>">
				<?php wp_nonce_field( 'atelier_create_starter_section_' . $slot ); ?>
				<?php /* translators: %s: section name */ submit_button( sprintf( __( 'Create starter %s', 'atelier-b2b' ), $info['label'] ), 'secondary', 'submit', false ); ?>
			</form>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Build a starter section post from its JSON template.
 *
 * @return int Post ID or 0.
 */
function atelier_create_starter_section( $slot ) {
	$slots = atelier_section_slots();
	if ( ! isset( $slots[ $slot ] ) ) {
		return 0;
	}
	$file = __DIR__ . '/templates/' . $slots[ $slot ]['json'];
	if ( ! is_readable( $file ) ) {
		return 0;
	}
	$data = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! is_array( $data ) || empty( $data['content'] ) ) {
		return 0;
	}

	$tokens = array(
		'__HERO_POSTER_URL__'   => atelier_asset_url( 'hero_poster' ),
		'__HERO_VIDEO_URL__'    => atelier_asset_url( 'hero_video' ), // '' when the file does not exist.
		'__EXPERTISE_URL__'     => atelier_url( 'our-expertise' ),
		'__COLLECTIONS_URL__'   => atelier_url( 'collections' ),
		'__CONTACT_IMAGE_URL__' => atelier_asset_url( 'editorial' ),
	);
	$content = $data['content'];
	array_walk_recursive(
		$content,
		function ( &$value ) use ( $tokens ) {
			if ( is_string( $value ) ) {
				$value = strtr( $value, $tokens );
			}
		}
	);

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'atelier_section',
			'post_status' => 'publish',
			'post_title'  => $slots[ $slot ]['title'],
		),
		true
	);
	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}
	update_post_meta( $post_id, '_atelier_section_slot', $slot );
	update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $content ) ) );
	update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $post_id, '_elementor_template_type', 'wp-post' );
	if ( defined( 'ELEMENTOR_VERSION' ) ) {
		update_post_meta( $post_id, '_elementor_version', ELEMENTOR_VERSION );
	}
	return (int) $post_id;
}

function atelier_set_section_active( $slot, $id ) {
	$opt          = get_option( 'atelier_active_sections', array() );
	$opt          = is_array( $opt ) ? $opt : array();
	$opt[ $slot ] = (int) $id;
	update_option( 'atelier_active_sections', $opt );
}

add_action( 'admin_post_atelier_create_starter_section', function () {
	if ( ! current_user_can( ATELIER_HEADER_CAP ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'atelier-b2b' ), 403 );
	}
	$slot = isset( $_POST['slot'] ) ? sanitize_key( wp_unslash( $_POST['slot'] ) ) : '';
	check_admin_referer( 'atelier_create_starter_section_' . $slot );

	$back = admin_url( 'edit.php?post_type=atelier_section&page=atelier-section-settings' );
	if ( ! atelier_elementor_active() ) {
		wp_safe_redirect( add_query_arg( 'atelier_notice', 'no_elementor', $back ) );
		exit;
	}
	$id = atelier_create_starter_section( $slot );
	if ( $id ) {
		atelier_set_section_active( $slot, $id );
		wp_safe_redirect( add_query_arg( 'atelier_notice', 'created', $back ) );
	} else {
		wp_safe_redirect( add_query_arg( 'atelier_notice', 'failed', $back ) );
	}
	exit;
} );

add_action( 'admin_post_atelier_set_active_section', function () {
	$id = isset( $_GET['section_id'] ) ? absint( $_GET['section_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification -- verified below.
	if ( ! current_user_can( ATELIER_HEADER_CAP ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'atelier-b2b' ), 403 );
	}
	check_admin_referer( 'atelier_set_section_' . $id );

	$back = admin_url( 'edit.php?post_type=atelier_section&page=atelier-section-settings' );
	$slot = $id ? get_post_meta( $id, '_atelier_section_slot', true ) : '';
	if ( $slot && atelier_section_is_valid_for_slot( $id, $slot ) ) {
		atelier_set_section_active( $slot, $id );
		wp_safe_redirect( add_query_arg( 'atelier_notice', 'activated', $back ) );
	} else {
		wp_safe_redirect( add_query_arg( 'atelier_notice', 'failed', $back ) );
	}
	exit;
} );

add_filter( 'post_row_actions', function ( $actions, $post ) {
	if ( 'atelier_section' !== $post->post_type || 'publish' !== $post->post_status || ! current_user_can( ATELIER_HEADER_CAP ) ) {
		return $actions;
	}
	$slot = get_post_meta( $post->ID, '_atelier_section_slot', true );
	if ( ! $slot ) {
		return $actions;
	}
	if ( atelier_get_active_section_id_raw( $slot ) === (int) $post->ID ) {
		$actions['atelier_active'] = '<strong>' . esc_html__( 'Active section', 'atelier-b2b' ) . '</strong>';
	} else {
		$url = wp_nonce_url( admin_url( 'admin-post.php?action=atelier_set_active_section&section_id=' . (int) $post->ID ), 'atelier_set_section_' . (int) $post->ID );
		$actions['atelier_activate'] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Set as active section', 'atelier-b2b' ) . '</a>';
	}
	return $actions;
}, 10, 2 );
