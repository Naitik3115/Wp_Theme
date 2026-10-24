<?php
/**
 * "Atelier Header → Active Header" screen: choose the global header, create a starter header,
 * and a "Set as active" row action. Uses the Settings API (nonces/caps handled by core).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

const ATELIER_HEADER_CAP = 'edit_theme_options';

function atelier_header_settings_menu() {
	add_submenu_page(
		'edit.php?post_type=atelier_header',
		__( 'Active Header', 'atelier-b2b' ),
		__( 'Active Header', 'atelier-b2b' ),
		ATELIER_HEADER_CAP,
		'atelier-header-settings',
		'atelier_header_settings_page'
	);
}
add_action( 'admin_menu', 'atelier_header_settings_menu' );

function atelier_header_register_setting() {
	register_setting(
		'atelier_header_group',
		'atelier_active_header_id',
		array(
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'atelier_sanitize_active_header',
		)
	);
}
add_action( 'admin_init', 'atelier_header_register_setting' );

add_filter( 'option_page_capability_atelier_header_group', function () {
	return ATELIER_HEADER_CAP;
} );

/**
 * Accept 0 (static fallback) or a published atelier_header ID; otherwise keep the previous value.
 */
function atelier_sanitize_active_header( $value ) {
	$id  = absint( $value );
	$old = absint( get_option( 'atelier_active_header_id', 0 ) );

	if ( 0 === $id ) {
		return 0;
	}
	$post = get_post( $id );
	if ( ! $post || 'atelier_header' !== $post->post_type || 'publish' !== $post->post_status ) {
		add_settings_error( 'atelier_active_header_id', 'invalid', __( 'Please choose a published Atelier Header.', 'atelier-b2b' ) );
		return $old;
	}
	return $id;
}

/** Published headers: id => title. */
function atelier_get_header_choices() {
	$posts = get_posts(
		array(
			'post_type'      => 'atelier_header',
			'post_status'    => 'publish',
			'posts_per_page' => 50,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);
	$out = array();
	foreach ( $posts as $p ) {
		$out[ $p->ID ] = $p->post_title ? $p->post_title : sprintf( '#%d', $p->ID );
	}
	return $out;
}

function atelier_header_edit_url( $id ) {
	if ( atelier_elementor_active() ) {
		$doc = \Elementor\Plugin::$instance->documents->get( $id );
		if ( $doc && method_exists( $doc, 'get_edit_url' ) ) {
			return $doc->get_edit_url();
		}
	}
	return get_edit_post_link( $id, 'raw' );
}

function atelier_header_settings_page() {
	if ( ! current_user_can( ATELIER_HEADER_CAP ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'atelier-b2b' ) );
	}

	$choices = atelier_get_header_choices();
	$active  = absint( get_option( 'atelier_active_header_id', 0 ) );
	$valid   = atelier_get_active_header_id();
	$notice  = isset( $_GET['atelier_notice'] ) ? sanitize_key( wp_unslash( $_GET['atelier_notice'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Atelier Header – Active Header', 'atelier-b2b' ); ?></h1>

		<?php if ( 'created' === $notice ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Starter header created and set as active.', 'atelier-b2b' ); ?></p></div>
		<?php elseif ( 'activated' === $notice ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Header set as active.', 'atelier-b2b' ); ?></p></div>
		<?php elseif ( 'no_elementor' === $notice ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'Elementor must be installed and active to create the starter header.', 'atelier-b2b' ); ?></p></div>
		<?php elseif ( 'failed' === $notice ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'The header could not be created or activated.', 'atelier-b2b' ); ?></p></div>
		<?php endif; ?>
		<?php settings_errors( 'atelier_active_header_id' ); ?>

		<table class="widefat striped" style="max-width:640px;margin:1em 0">
			<tbody>
				<tr><th><?php esc_html_e( 'Elementor', 'atelier-b2b' ); ?></th><td><?php echo atelier_elementor_active() ? esc_html__( 'Active', 'atelier-b2b' ) : '<strong>' . esc_html__( 'Not active – the static header is being used', 'atelier-b2b' ) . '</strong>'; ?></td></tr>
				<tr><th><?php esc_html_e( 'Header currently shown', 'atelier-b2b' ); ?></th><td>
					<?php
					if ( $valid && atelier_elementor_active() ) {
						echo esc_html( get_the_title( $valid ) ) . ' – <a href="' . esc_url( atelier_header_edit_url( $valid ) ) . '">' . esc_html__( 'Edit with Elementor', 'atelier-b2b' ) . '</a>';
					} else {
						esc_html_e( 'Static PHP header (fallback)', 'atelier-b2b' );
					}
					?>
				</td></tr>
			</tbody>
		</table>

		<form method="post" action="options.php">
			<?php settings_fields( 'atelier_header_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="atelier_active_header_id"><?php esc_html_e( 'Active global header', 'atelier-b2b' ); ?></label></th>
					<td>
						<select name="atelier_active_header_id" id="atelier_active_header_id">
							<option value="0" <?php selected( $active, 0 ); ?>><?php esc_html_e( 'Static PHP header (fallback)', 'atelier-b2b' ); ?></option>
							<?php foreach ( $choices as $id => $title ) : ?>
								<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $active, $id ); ?>><?php echo esc_html( $title ); ?></option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'Only published headers are listed. A header must have been saved with Elementor.', 'atelier-b2b' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button( __( 'Save active header', 'atelier-b2b' ) ); ?>
		</form>

		<hr>
		<h2><?php esc_html_e( 'Starter header', 'atelier-b2b' ); ?></h2>
		<p><?php esc_html_e( 'Creates a new header that recreates the original design (announcement marquee, logo, navigation, CTA), saves it as an Elementor layout and activates it.', 'atelier-b2b' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="atelier_create_starter_header">
			<?php wp_nonce_field( 'atelier_create_starter_header' ); ?>
			<?php submit_button( __( 'Create starter header', 'atelier-b2b' ), 'secondary', 'submit', false ); ?>
		</form>
	</div>
	<?php
}

/**
 * Build the starter header from inc/elementor/atelier-global-header.json.
 *
 * @return int Post ID or 0.
 */
function atelier_create_starter_header() {
	$file = __DIR__ . '/atelier-global-header.json';
	if ( ! is_readable( $file ) ) {
		return 0;
	}
	$data = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! is_array( $data ) || empty( $data['content'] ) ) {
		return 0;
	}

	$logo_id  = get_theme_mod( 'custom_logo' );
	$logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';
	if ( ! $logo_url ) {
		$logo_url = atelier_asset_url( 'logo' );
	}
	$tokens = array(
		'__HOME_URL__'    => home_url( '/' ),
		'__CONTACT_URL__' => home_url( '/contact/' ),
		'__LOGO_URL__'    => $logo_url,
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
			'post_type'   => 'atelier_header',
			'post_status' => 'publish',
			'post_title'  => __( 'Atelier Global Header', 'atelier-b2b' ),
		),
		true
	);
	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}

	update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $content ) ) );
	update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $post_id, '_elementor_template_type', 'wp-post' );
	if ( defined( 'ELEMENTOR_VERSION' ) ) {
		update_post_meta( $post_id, '_elementor_version', ELEMENTOR_VERSION );
	}

	return (int) $post_id;
}

function atelier_handle_create_starter_header() {
	if ( ! current_user_can( ATELIER_HEADER_CAP ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'atelier-b2b' ), 403 );
	}
	check_admin_referer( 'atelier_create_starter_header' );

	$back = admin_url( 'edit.php?post_type=atelier_header&page=atelier-header-settings' );

	if ( ! atelier_elementor_active() ) {
		wp_safe_redirect( add_query_arg( 'atelier_notice', 'no_elementor', $back ) );
		exit;
	}

	$id = atelier_create_starter_header();
	if ( $id ) {
		update_option( 'atelier_active_header_id', $id );
		wp_safe_redirect( add_query_arg( 'atelier_notice', 'created', $back ) );
	} else {
		wp_safe_redirect( add_query_arg( 'atelier_notice', 'failed', $back ) );
	}
	exit;
}
add_action( 'admin_post_atelier_create_starter_header', 'atelier_handle_create_starter_header' );

/** "Set as active header" row action. */
function atelier_handle_set_active_header() {
	$id = isset( $_GET['header_id'] ) ? absint( $_GET['header_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification -- verified below.
	if ( ! current_user_can( ATELIER_HEADER_CAP ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'atelier-b2b' ), 403 );
	}
	check_admin_referer( 'atelier_set_active_' . $id );

	$post = $id ? get_post( $id ) : null;
	$back = admin_url( 'edit.php?post_type=atelier_header' );
	if ( $post && 'atelier_header' === $post->post_type && 'publish' === $post->post_status ) {
		update_option( 'atelier_active_header_id', $id );
		wp_safe_redirect( add_query_arg( 'atelier_notice', 'activated', admin_url( 'edit.php?post_type=atelier_header&page=atelier-header-settings' ) ) );
	} else {
		wp_safe_redirect( add_query_arg( 'atelier_notice', 'failed', $back ) );
	}
	exit;
}
add_action( 'admin_post_atelier_set_active_header', 'atelier_handle_set_active_header' );

function atelier_header_row_actions( $actions, $post ) {
	if ( 'atelier_header' !== $post->post_type || 'publish' !== $post->post_status || ! current_user_can( ATELIER_HEADER_CAP ) ) {
		return $actions;
	}
	if ( (int) get_option( 'atelier_active_header_id', 0 ) === (int) $post->ID ) {
		$actions['atelier_active'] = '<strong>' . esc_html__( 'Active header', 'atelier-b2b' ) . '</strong>';
	} else {
		$url = wp_nonce_url( admin_url( 'admin-post.php?action=atelier_set_active_header&header_id=' . (int) $post->ID ), 'atelier_set_active_' . (int) $post->ID );
		$actions['atelier_activate'] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Set as active header', 'atelier-b2b' ) . '</a>';
	}
	return $actions;
}
add_filter( 'post_row_actions', 'atelier_header_row_actions', 10, 2 );

/** "Active" column in the list table. */
add_filter( 'manage_atelier_header_posts_columns', function ( $cols ) {
	$cols['atelier_active'] = __( 'Status on site', 'atelier-b2b' );
	return $cols;
} );
add_action( 'manage_atelier_header_posts_custom_column', function ( $col, $post_id ) {
	if ( 'atelier_active' === $col ) {
		echo (int) get_option( 'atelier_active_header_id', 0 ) === (int) $post_id ? '<strong>' . esc_html__( 'Active', 'atelier-b2b' ) . '</strong>' : '&mdash;';
	}
}, 10, 2 );
