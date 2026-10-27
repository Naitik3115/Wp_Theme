<?php
/**
 * Optional demo content (Atelier Products → Demo content): sample categories + products using the theme's
 * PLACEHOLDER images, so the Collections page can be previewed immediately. Clearly labelled, removable.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function () {
	add_submenu_page( 'edit.php?post_type=atelier_product', __( 'Demo content', 'atelier-b2b' ), __( 'Demo content', 'atelier-b2b' ), 'manage_options', 'atelier-demo-products', 'atelier_demo_page' );
} );

function atelier_demo_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'atelier-b2b' ) );
	}
	$done = isset( $_GET['atelier_demo'] ) ? sanitize_key( wp_unslash( $_GET['atelier_demo'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Atelier Products – Demo content', 'atelier-b2b' ); ?></h1>
		<?php if ( 'created' === $done ) : ?><div class="notice notice-success"><p><?php esc_html_e( 'Demo content created.', 'atelier-b2b' ); ?></p></div><?php endif; ?>
		<?php if ( 'removed' === $done ) : ?><div class="notice notice-success"><p><?php esc_html_e( 'Demo content removed.', 'atelier-b2b' ); ?></p></div><?php endif; ?>
		<p><?php esc_html_e( 'Creates 7 sample categories and 10 sample products that use the theme’s placeholder images (not real photography, no real product data). Replace or remove them before launch.', 'atelier-b2b' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:1em">
			<input type="hidden" name="action" value="atelier_demo_create">
			<?php wp_nonce_field( 'atelier_demo_create' ); ?>
			<?php submit_button( __( 'Create demo content', 'atelier-b2b' ), 'primary', 'submit', false ); ?>
		</form>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block">
			<input type="hidden" name="action" value="atelier_demo_remove">
			<?php wp_nonce_field( 'atelier_demo_remove' ); ?>
			<?php submit_button( __( 'Remove demo content', 'atelier-b2b' ), 'secondary', 'submit', false ); ?>
		</form>
	</div>
	<?php
}

/** Copy a theme image into the Media Library once; returns attachment ID. */
function atelier_demo_sideload( $asset_key, &$cache ) {
	if ( isset( $cache[ $asset_key ] ) ) {
		return $cache[ $asset_key ];
	}
	$assets = atelier_assets();
	if ( ! atelier_asset_exists( $asset_key ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$src = get_template_directory() . '/assets/' . $assets[ $asset_key ]['file'];
	$tmp = wp_tempnam( basename( $src ) );
	if ( ! $tmp || ! copy( $src, $tmp ) ) {
		return 0;
	}
	$id = media_handle_sideload( array( 'name' => 'demo-' . basename( $src ), 'tmp_name' => $tmp ), 0 );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp ); // phpcs:ignore
		return 0;
	}
	update_post_meta( $id, '_atelier_demo', '1' );
	update_post_meta( $id, '_wp_attachment_image_alt', 'Placeholder image' );
	$cache[ $asset_key ] = (int) $id;
	return (int) $id;
}

add_action( 'admin_post_atelier_demo_create', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'atelier-b2b' ), 403 );
	}
	check_admin_referer( 'atelier_demo_create' );
	@set_time_limit( 120 ); // phpcs:ignore

	$cats = array( 'Menswear', 'Womenswear', 'Streetwear', 'Essentials', 'Activewear', 'Custom Apparel' );
	$term = array();
	foreach ( $cats as $i => $name ) {
		$t = term_exists( $name, 'atelier_collection' );
		if ( ! $t ) {
			$t = wp_insert_term( $name, 'atelier_collection' );
		}
		if ( ! is_wp_error( $t ) ) {
			$term[ $name ] = (int) ( is_array( $t ) ? $t['term_id'] : $t );
			update_term_meta( $term[ $name ], '_atelier_term_order', $i + 1 );
			update_term_meta( $term[ $name ], '_atelier_demo', '1' );
		}
	}

	$imgs = array( 'category_men', 'category_women', 'category_street', 'category_essential', 'category_active', 'showcase_01', 'showcase_02', 'showcase_03', 'showcase_04', 'editorial' );
	$rows = array(
		array( 'Sample Product 01', 'DEMO-001', 'Menswear', 'category_men', 'showcase_01', true ),
		array( 'Sample Product 02', 'DEMO-002', 'Womenswear', 'category_women', 'showcase_02', false ),
		array( 'Sample Product 03', 'DEMO-003', 'Streetwear', 'category_street', 'showcase_04', false ),
		array( 'Sample Product 04', 'DEMO-004', 'Essentials', 'category_essential', 'category_men', false ),
		array( 'Sample Product 05', 'DEMO-005', 'Activewear', 'category_active', 'category_women', true ),
		array( 'Sample Product 06', 'DEMO-006', 'Menswear', 'showcase_04', 'category_street', false ),
		array( 'Sample Product 07', 'DEMO-007', 'Womenswear', 'editorial', 'showcase_01', false ),
		array( 'Sample Product 08', 'DEMO-008', 'Custom Apparel', 'showcase_02', 'category_essential', false ),
		array( 'Sample Product 09', 'DEMO-009', 'Streetwear', 'showcase_03', 'category_active', false ),
		array( 'Sample Product 10', 'DEMO-010', 'Essentials', 'category_women', 'showcase_03', false ),
	);

	$cache = array();
	foreach ( $rows as $n => $r ) {
		$id = wp_insert_post( array(
			'post_type'    => 'atelier_product',
			'post_status'  => 'publish',
			'post_title'   => $r[0],
			'post_excerpt' => 'Placeholder description. Replace with your own product copy.',
			'menu_order'   => $n + 1,
		) );
		if ( is_wp_error( $id ) || ! $id ) {
			continue;
		}
		update_post_meta( $id, '_atelier_demo', '1' );
		update_post_meta( $id, '_atelier_ref', $r[1] );
		update_post_meta( $id, '_atelier_season', 'Sample collection' );
		if ( $r[5] ) {
			update_post_meta( $id, '_atelier_featured', '1' );
		}
		if ( isset( $term[ $r[2] ] ) ) {
			wp_set_object_terms( $id, array( $term[ $r[2] ] ), 'atelier_collection' );
		}
		$main  = atelier_demo_sideload( $r[3], $cache );
		$hover = atelier_demo_sideload( $r[4], $cache );
		$g1    = atelier_demo_sideload( $imgs[ $n % count( $imgs ) ], $cache );
		if ( $main ) {
			set_post_thumbnail( $id, $main );
		}
		if ( $hover ) {
			update_post_meta( $id, '_atelier_hover_image', $hover );
		}
		$gallery = array_unique( array_filter( array( $hover, $g1 ) ) );
		if ( $gallery ) {
			update_post_meta( $id, '_atelier_gallery', implode( ',', $gallery ) );
		}
	}
	wp_safe_redirect( add_query_arg( 'atelier_demo', 'created', admin_url( 'edit.php?post_type=atelier_product&page=atelier-demo-products' ) ) );
	exit;
} );

add_action( 'admin_post_atelier_demo_remove', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'atelier-b2b' ), 403 );
	}
	check_admin_referer( 'atelier_demo_remove' );

	$products = get_posts( array( 'post_type' => 'atelier_product', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_atelier_demo', 'meta_value' => '1' ) ); // phpcs:ignore
	foreach ( $products as $id ) {
		wp_delete_post( $id, true );
	}
	$atts = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_atelier_demo', 'meta_value' => '1' ) ); // phpcs:ignore
	foreach ( $atts as $id ) {
		wp_delete_attachment( $id, true );
	}
	$terms = get_terms( array( 'taxonomy' => 'atelier_collection', 'hide_empty' => false, 'meta_key' => '_atelier_demo', 'meta_value' => '1' ) ); // phpcs:ignore
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $t ) {
			if ( 0 === (int) $t->count ) {
				wp_delete_term( $t->term_id, 'atelier_collection' );
			}
		}
	}
	wp_safe_redirect( add_query_arg( 'atelier_demo', 'removed', admin_url( 'edit.php?post_type=atelier_product&page=atelier-demo-products' ) ) );
	exit;
} );
