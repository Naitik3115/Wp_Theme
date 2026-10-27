<?php
/**
 * Collections helpers: asset registration, product data, markup assembly, page-template routing.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

const ATELIER_COLLECTIONS_TEMPLATE = 'page-templates/template-atelier-collections.php';

function atelier_is_collections_page() {
	return is_page_template( ATELIER_COLLECTIONS_TEMPLATE );
}

add_filter( 'body_class', function ( $classes ) {
	if ( atelier_is_collections_page() ) {
		$classes[] = 'atelier-collections-page';
	}
	return $classes;
} );

/* ---------------- Assets: registered always, enqueued only where needed ---------------- */
function atelier_collections_register_assets() {
	$uri = get_template_directory_uri();

	wp_register_style( 'atelier-collections', $uri . '/assets/css/collections.css', array( 'atelier-responsive' ), ATELIER_VERSION );
	wp_register_script(
		'atelier-collections',
		$uri . '/assets/js/collections.js',
		array( 'atelier-main' ),
		ATELIER_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

	$default = home_url( '/contact/' );
	wp_localize_script( 'atelier-collections', 'atelierCollections', array(
		'rest'    => esc_url_raw( rest_url( 'atelier/v1/' ) ),
		'inquiry' => esc_url_raw( apply_filters( 'atelier_inquiry_url', $default ) ),
		'i18n'    => array(
			'loading'  => __( 'Loading…', 'atelier-b2b' ),
			'error'    => __( 'We could not load this item. Please try again.', 'atelier-b2b' ),
			'retry'    => __( 'Try again', 'atelier-b2b' ),
			'showing'  => __( 'Showing %1$s of %2$s', 'atelier-b2b' ),
			'of'       => __( 'Image %1$s of %2$s', 'atelier-b2b' ),
		),
	) );
}
add_action( 'wp_enqueue_scripts', 'atelier_collections_register_assets', 5 );

/** The collections page template loads everything (incl. existing GSAP reveals); the widget declares its own deps. */
function atelier_collections_enqueue_page_assets() {
	if ( ! atelier_is_collections_page() ) {
		return;
	}
	wp_enqueue_style( 'atelier-collections' );
	wp_enqueue_script( 'atelier-collections' );

	// Reuse the theme's existing GSAP reveals ([data-lines], [data-fade], [data-clip], [data-parallax]).
	$uri = get_template_directory_uri();
	wp_enqueue_script( 'atelier-animations', $uri . '/assets/js/animations.js', array( 'atelier-scrolltrigger', 'atelier-main' ), ATELIER_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
}
add_action( 'wp_enqueue_scripts', 'atelier_collections_enqueue_page_assets', 20 );

/* ---------------- Product data ---------------- */
function atelier_product_terms_names( $post_id ) {
	$terms = get_the_terms( $post_id, 'atelier_collection' );
	return ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'name' ) : array();
}

/** Short description: excerpt, else trimmed content. */
function atelier_product_short_description( $post ) {
	$text = has_excerpt( $post ) ? $post->post_excerpt : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 45 );
	return trim( (string) $text );
}

/** Data for one grid card. */
function atelier_product_card_data( $post ) {
	$id       = $post->ID;
	$thumb    = get_post_thumbnail_id( $id );
	$hover_id = (int) get_post_meta( $id, '_atelier_hover_image', true );
	return array(
		'id'         => $id,
		'title'      => get_the_title( $post ),
		'ref'        => get_post_meta( $id, '_atelier_ref', true ),
		'season'     => get_post_meta( $id, '_atelier_season', true ),
		'categories' => atelier_product_terms_names( $id ),
		'featured'   => '1' === get_post_meta( $id, '_atelier_featured', true ),
		'thumb_id'   => $thumb ? (int) $thumb : 0,
		'hover_id'   => $hover_id && 'attachment' === get_post_type( $hover_id ) ? $hover_id : 0,
	);
}

/** Data for the Quick View drawer (lazy REST payload). */
function atelier_product_quickview_data( $post ) {
	$id      = $post->ID;
	$ids     = array();
	$thumb   = get_post_thumbnail_id( $id );
	if ( $thumb ) {
		$ids[] = (int) $thumb;
	}
	$hover = (int) get_post_meta( $id, '_atelier_hover_image', true );
	$extra = array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $id, '_atelier_gallery', true ) ) ) );
	foreach ( $extra as $x ) {
		$ids[] = $x;
	}
	if ( $hover && ! in_array( $hover, $ids, true ) ) {
		$ids[] = $hover;
	}
	$ids = array_values( array_unique( $ids ) );

	$gallery = array();
	foreach ( $ids as $att ) {
		$full = wp_get_attachment_image_url( $att, 'large' );
		if ( ! $full ) {
			continue;
		}
		$alt       = get_post_meta( $att, '_wp_attachment_image_alt', true );
		$gallery[] = array(
			'src'    => $full,
			'srcset' => (string) wp_get_attachment_image_srcset( $att, 'large' ),
			'thumb'  => wp_get_attachment_image_url( $att, 'thumbnail' ),
			'alt'    => $alt ? $alt : get_the_title( $post ),
		);
	}

	$colors = array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $id, '_atelier_colors', true ) ) ) );

	return array(
		'id'          => $id,
		'title'       => get_the_title( $post ),
		'ref'         => (string) get_post_meta( $id, '_atelier_ref', true ),
		'season'      => (string) get_post_meta( $id, '_atelier_season', true ),
		'categories'  => atelier_product_terms_names( $id ),
		'description' => atelier_product_short_description( $post ),
		'fabric'      => (string) get_post_meta( $id, '_atelier_fabric', true ),
		'colors'      => array_values( $colors ),
		'gallery'     => $gallery,
	);
}

/** Render the product cards of a query (shared by the page render and REST). */
function atelier_collection_cards_html( $query, $a ) {
	ob_start();
	$i = ( $a['page'] - 1 ) * $a['per_page'];
	foreach ( $query->posts as $post ) {
		get_template_part( 'template-parts/collections/product-card', null, array(
			'product' => atelier_product_card_data( $post ),
			'index'   => $i++,
			'opts'    => $a,
		) );
	}
	return ob_get_clean();
}

/** Remember that the Quick View drawer must be printed once in the footer. */
function atelier_collections_need_quickview() {
	static $needed = false;
	if ( ! $needed ) {
		$needed = true;
		add_action( 'wp_footer', function () {
			get_template_part( 'template-parts/collections/quick-view' );
		}, 5 );
	}
}

/**
 * Render the filter bar + grid. Used by the Elementor widget and by the default page template.
 *
 * @param array $s Settings: heading{label,title,text}, terms[], orderby, per_page, columns{d,t,m}, featured, filters,
 *                 sticky, count, quick, load_more, ref, season, hide_empty, inquiry, empty_title, empty_text, all_label.
 */
function atelier_collection_render( $s = array() ) {
	$s = wp_parse_args(
		$s,
		array(
			'label'      => '',
			'title'      => '',
			'text'       => '',
			'terms'      => array(),
			'orderby'    => 'menu',
			'per_page'   => 9,
			'cols_d'     => 3,
			'featured'   => 'wide',
			'filters'    => true,
			'sticky'     => true,
			'count'      => true,
			'quick'      => true,
			'load_more'  => true,
			'ref'        => true,
			'season'     => true,
			'hide_empty' => false,
			'inquiry'    => '',
			'all_label'  => __( 'All Collections', 'atelier-b2b' ),
			'more_label' => __( 'Load more', 'atelier-b2b' ),
			'empty_title' => __( 'Nothing here yet.', 'atelier-b2b' ),
			'empty_text'  => __( 'There are no products in this category at the moment. Please explore another collection.', 'atelier-b2b' ),
		)
	);

	$initial = isset( $_GET['collection'] ) ? sanitize_title( wp_unslash( $_GET['collection'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$a       = atelier_collection_args( array(
		'terms'    => $s['terms'],
		'term'     => $initial,
		'orderby'  => $s['orderby'],
		'per_page' => $s['per_page'],
		'featured' => $s['featured'],
		'quick'    => $s['quick'],
		'ref'      => $s['ref'],
		'season'   => $s['season'],
	) );

	$filter_terms = atelier_collection_filter_terms( $a['terms'], $s['hide_empty'] );
	$valid_slugs  = wp_list_pluck( $filter_terms, 'slug' );
	if ( $a['term'] && ! in_array( $a['term'], $valid_slugs, true ) ) {
		$a['term'] = '';
	}

	$query = atelier_collection_query( $a );

	if ( $a['quick'] ) {
		atelier_collections_need_quickview();
	}

	get_template_part( 'template-parts/collections/product-grid', null, array(
		's'            => $s,
		'a'            => $a,
		'query'        => $query,
		'filter_terms' => $filter_terms,
		'total'        => atelier_collection_total( $a['terms'] ),
		'cards_html'   => atelier_collection_cards_html( $query, $a ),
	) );
}

/** Contact-page URL used as inquiry destination. */
function atelier_collections_inquiry_url( $custom = '' ) {
	return $custom ? $custom : apply_filters( 'atelier_inquiry_url', home_url( '/contact/' ) );
}

/** <img> from an attachment ID with the theme's sizes/lazy defaults. */
function atelier_col_img( $id, $class, $sizes, $lazy = true, $size = 'large' ) {
	if ( ! $id ) {
		return '';
	}
	return wp_get_attachment_image( $id, $size, false, array(
		'class'    => $class,
		'sizes'    => $sizes,
		'loading'  => $lazy ? 'lazy' : 'eager',
		'decoding' => 'async',
	) );
}

/** Does this Collections page use Elementor content? (then the template prints the_content instead of the defaults) */
function atelier_collections_page_uses_elementor() {
	if ( ! function_exists( 'atelier_elementor_active' ) || ! atelier_elementor_active() ) {
		return false;
	}
	$id = get_queried_object_id();
	return $id && 'builder' === get_post_meta( $id, '_elementor_edit_mode', true );
}

/**
 * <img> for an Elementor MEDIA control value (or '' if empty); uses configured dimensions/alt when the URL is a
 * theme asset. Guarded so it can also live in sections-markup.php without clashing.
 */
if ( ! function_exists( 'atelier_media_img' ) ) {
	function atelier_media_img( $media, $args = array(), $fallback_key = '' ) {
		$args = wp_parse_args( $args, array( 'class' => '', 'loading' => 'lazy', 'sizes' => '100vw', 'alt' => '' ) );

		if ( ! empty( $media['id'] ) ) {
			$attr = array( 'class' => $args['class'], 'loading' => $args['loading'], 'sizes' => $args['sizes'], 'decoding' => 'async' );
			if ( '' !== $args['alt'] ) {
				$attr['alt'] = $args['alt'];
			}
			$html = wp_get_attachment_image( (int) $media['id'], 'full', false, $attr );
			if ( $html ) {
				return $html;
			}
		}
		if ( empty( $media['url'] ) ) {
			return '';
		}

		$dims = '';
		$alt  = $args['alt'];
		if ( $fallback_key ) {
			$assets = atelier_assets();
			if ( isset( $assets[ $fallback_key ] ) && atelier_asset_url( $fallback_key ) === $media['url'] ) {
				$dims = ' width="' . (int) $assets[ $fallback_key ]['w'] . '" height="' . (int) $assets[ $fallback_key ]['h'] . '"';
				if ( '' === $alt && isset( $assets[ $fallback_key ]['alt'] ) ) {
					$alt = $assets[ $fallback_key ]['alt'];
				}
			}
		}
		return sprintf(
			'<img src="%1$s"%2$s alt="%3$s" class="%4$s" loading="%5$s" decoding="async" sizes="%6$s">',
			esc_url( $media['url'] ),
			$dims,
			esc_attr( $alt ),
			esc_attr( $args['class'] ),
			esc_attr( $args['loading'] ),
			esc_attr( $args['sizes'] )
		);
	}
}
