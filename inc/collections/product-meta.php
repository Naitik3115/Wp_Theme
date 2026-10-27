<?php
/**
 * Product fields (native meta boxes + Media Library): reference, season/collection label, fabric, colours,
 * featured flag, hover image, gallery. Main image = featured image; short description = excerpt;
 * custom order = "Order" in the Attributes box (menu_order).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_product_add_meta_boxes() {
	add_meta_box( 'atelier_product_details', __( 'Product details', 'atelier-b2b' ), 'atelier_product_details_box', 'atelier_product', 'normal', 'high' );
	add_meta_box( 'atelier_product_media', __( 'Hover image & gallery', 'atelier-b2b' ), 'atelier_product_media_box', 'atelier_product', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'atelier_product_add_meta_boxes' );

function atelier_product_admin_assets( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || 'atelier_product' !== $screen->post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_style( 'atelier-admin-product', get_template_directory_uri() . '/assets/css/admin-product.css', array(), ATELIER_VERSION );
	wp_enqueue_script( 'atelier-admin-product', get_template_directory_uri() . '/assets/js/admin-product.js', array( 'jquery', 'jquery-ui-sortable' ), ATELIER_VERSION, true );
	wp_localize_script( 'atelier-admin-product', 'atelierProductAdmin', array(
		'title'  => __( 'Choose images', 'atelier-b2b' ),
		'button' => __( 'Use selected', 'atelier-b2b' ),
	) );
}
add_action( 'admin_enqueue_scripts', 'atelier_product_admin_assets' );

function atelier_product_details_box( $post ) {
	wp_nonce_field( 'atelier_product_meta', 'atelier_product_meta_nonce' );
	$v = function ( $k ) use ( $post ) {
		return get_post_meta( $post->ID, $k, true );
	};
	?>
	<table class="form-table atelier-fields" role="presentation">
		<tr><th><label for="atelier_ref"><?php esc_html_e( 'Product reference / SKU', 'atelier-b2b' ); ?></label></th>
			<td><input type="text" class="regular-text" id="atelier_ref" name="atelier_ref" value="<?php echo esc_attr( $v( '_atelier_ref' ) ); ?>"><p class="description"><?php esc_html_e( 'Shown on the card and sent with the inquiry link.', 'atelier-b2b' ); ?></p></td></tr>
		<tr><th><label for="atelier_season"><?php esc_html_e( 'Collection / season label', 'atelier-b2b' ); ?></label></th>
			<td><input type="text" class="regular-text" id="atelier_season" name="atelier_season" value="<?php echo esc_attr( $v( '_atelier_season' ) ); ?>"></td></tr>
		<tr><th><label for="atelier_fabric"><?php esc_html_e( 'Fabric / material (optional)', 'atelier-b2b' ); ?></label></th>
			<td><input type="text" class="large-text" id="atelier_fabric" name="atelier_fabric" value="<?php echo esc_attr( $v( '_atelier_fabric' ) ); ?>"></td></tr>
		<tr><th><label for="atelier_colors"><?php esc_html_e( 'Colours (optional)', 'atelier-b2b' ); ?></label></th>
			<td><input type="text" class="large-text" id="atelier_colors" name="atelier_colors" value="<?php echo esc_attr( $v( '_atelier_colors' ) ); ?>"><p class="description"><?php esc_html_e( 'Comma separated, e.g. "Sand, Chestnut".', 'atelier-b2b' ); ?></p></td></tr>
		<tr><th><?php esc_html_e( 'Featured', 'atelier-b2b' ); ?></th>
			<td><label><input type="checkbox" name="atelier_featured" value="1" <?php checked( $v( '_atelier_featured' ), '1' ); ?>> <?php esc_html_e( 'Show as a large featured card in the grid', 'atelier-b2b' ); ?></label></td></tr>
	</table>
	<p class="description"><?php esc_html_e( 'Main image = "Main image" box on the right. Short description = Excerpt. Custom order = "Order" in Attributes (lower first).', 'atelier-b2b' ); ?></p>
	<?php
}

/** Render a media-picker field (hidden input holds comma-separated attachment IDs). */
function atelier_product_media_field( $name, $ids, $multiple ) {
	$ids = array_filter( array_map( 'absint', (array) $ids ) );
	echo '<div class="atelier-media-field" data-multiple="' . ( $multiple ? '1' : '0' ) . '">';
	echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( implode( ',', $ids ) ) . '">';
	echo '<ul class="atelier-media-list">';
	foreach ( $ids as $id ) {
		echo '<li data-id="' . (int) $id . '">' . wp_get_attachment_image( $id, 'thumbnail' ) . '<button type="button" class="atelier-media-remove" aria-label="' . esc_attr__( 'Remove', 'atelier-b2b' ) . '">&times;</button></li>';
	}
	echo '</ul>';
	echo '<button type="button" class="button atelier-media-select">' . ( $multiple ? esc_html__( 'Add gallery images', 'atelier-b2b' ) : esc_html__( 'Choose hover image', 'atelier-b2b' ) ) . '</button>';
	echo '</div>';
}

function atelier_product_media_box( $post ) {
	$hover   = get_post_meta( $post->ID, '_atelier_hover_image', true );
	$gallery = get_post_meta( $post->ID, '_atelier_gallery', true );
	echo '<p><strong>' . esc_html__( 'Secondary (hover) image', 'atelier-b2b' ) . '</strong><br><span class="description">' . esc_html__( 'Crossfades in when visitors hover the card. Same aspect ratio as the main image works best.', 'atelier-b2b' ) . '</span></p>';
	atelier_product_media_field( 'atelier_hover_image', $hover ? array( $hover ) : array(), false );
	echo '<hr><p><strong>' . esc_html__( 'Additional gallery images (Quick View)', 'atelier-b2b' ) . '</strong><br><span class="description">' . esc_html__( 'Drag to reorder. The main image is always the first gallery image.', 'atelier-b2b' ) . '</span></p>';
	atelier_product_media_field( 'atelier_gallery', $gallery ? explode( ',', $gallery ) : array(), true );
}

/** Keep only real attachment IDs. */
function atelier_sanitize_attachment_ids( $raw ) {
	$out = array();
	foreach ( array_filter( array_map( 'absint', explode( ',', (string) $raw ) ) ) as $id ) {
		if ( 'attachment' === get_post_type( $id ) ) {
			$out[] = $id;
		}
	}
	return array_values( array_unique( $out ) );
}

function atelier_product_save_meta( $post_id ) {
	if ( ! isset( $_POST['atelier_product_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['atelier_product_meta_nonce'] ) ), 'atelier_product_meta' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text = array(
		'atelier_ref'    => '_atelier_ref',
		'atelier_season' => '_atelier_season',
		'atelier_fabric' => '_atelier_fabric',
		'atelier_colors' => '_atelier_colors',
	);
	foreach ( $text as $field => $key ) {
		$val = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
		if ( '' === $val ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $val );
		}
	}

	if ( ! empty( $_POST['atelier_featured'] ) ) {
		update_post_meta( $post_id, '_atelier_featured', '1' );
	} else {
		delete_post_meta( $post_id, '_atelier_featured' ); // "EXISTS" = featured (used for ordering).
	}

	$hover = isset( $_POST['atelier_hover_image'] ) ? atelier_sanitize_attachment_ids( sanitize_text_field( wp_unslash( $_POST['atelier_hover_image'] ) ) ) : array();
	if ( $hover ) {
		update_post_meta( $post_id, '_atelier_hover_image', $hover[0] );
	} else {
		delete_post_meta( $post_id, '_atelier_hover_image' );
	}

	$gallery = isset( $_POST['atelier_gallery'] ) ? atelier_sanitize_attachment_ids( sanitize_text_field( wp_unslash( $_POST['atelier_gallery'] ) ) ) : array();
	if ( $gallery ) {
		update_post_meta( $post_id, '_atelier_gallery', implode( ',', $gallery ) );
	} else {
		delete_post_meta( $post_id, '_atelier_gallery' );
	}
}
add_action( 'save_post_atelier_product', 'atelier_product_save_meta' );

/* ---- List table columns ---- */
add_filter( 'manage_atelier_product_posts_columns', function ( $cols ) {
	$new = array();
	foreach ( $cols as $k => $label ) {
		if ( 'title' === $k ) {
			$new['atelier_thumb'] = __( 'Image', 'atelier-b2b' );
		}
		$new[ $k ] = $label;
	}
	$new['atelier_ref']      = __( 'Reference', 'atelier-b2b' );
	$new['atelier_featured'] = __( 'Featured', 'atelier-b2b' );
	$new['atelier_order']    = __( 'Order', 'atelier-b2b' );
	return $new;
} );
add_action( 'manage_atelier_product_posts_custom_column', function ( $col, $id ) {
	if ( 'atelier_thumb' === $col ) {
		echo get_the_post_thumbnail( $id, array( 56, 56 ) ) ?: '&mdash;'; // phpcs:ignore
	} elseif ( 'atelier_ref' === $col ) {
		echo esc_html( get_post_meta( $id, '_atelier_ref', true ) );
	} elseif ( 'atelier_featured' === $col ) {
		echo '1' === get_post_meta( $id, '_atelier_featured', true ) ? '&#9733;' : '&mdash;';
	} elseif ( 'atelier_order' === $col ) {
		echo (int) get_post_field( 'menu_order', $id );
	}
}, 10, 2 );
