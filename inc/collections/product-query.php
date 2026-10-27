<?php
/**
 * Product queries + REST endpoints (grid pages for filtering / Load More, and lazy Quick View data).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

/** Whitelisted, sanitised grid arguments (also used for REST input). */
function atelier_collection_args( $in = array() ) {
	$in = wp_parse_args(
		(array) $in,
		array(
			'terms'    => array(),   // Allowed term slugs (empty = all).
			'term'     => '',        // Active filter slug.
			'orderby'  => 'menu',
			'per_page' => 9,
			'page'     => 1,
			'featured' => 'wide',
			'quick'    => true,
			'ref'      => true,
			'season'   => true,
		)
	);

	$terms = is_array( $in['terms'] ) ? $in['terms'] : explode( ',', (string) $in['terms'] );
	$terms = array_values( array_filter( array_map( 'sanitize_title', $terms ) ) );

	$orders = array( 'menu', 'featured', 'newest', 'oldest', 'title' );

	return array(
		'terms'    => $terms,
		'term'     => sanitize_title( (string) $in['term'] ),
		'orderby'  => in_array( $in['orderby'], $orders, true ) ? $in['orderby'] : 'menu',
		'per_page' => max( 1, min( 60, (int) $in['per_page'] ) ),
		'page'     => max( 1, (int) $in['page'] ),
		'featured' => 'none' === $in['featured'] ? 'none' : 'wide',
		'quick'    => filter_var( $in['quick'], FILTER_VALIDATE_BOOLEAN ),
		'ref'      => filter_var( $in['ref'], FILTER_VALIDATE_BOOLEAN ),
		'season'   => filter_var( $in['season'], FILTER_VALIDATE_BOOLEAN ),
	);
}

/** Tax query for the allowed set and/or active term. */
function atelier_collection_tax_query( $a ) {
	$tax = array();
	if ( $a['terms'] ) {
		$tax[] = array( 'taxonomy' => 'atelier_collection', 'field' => 'slug', 'terms' => $a['terms'] );
	}
	if ( $a['term'] ) {
		$tax[] = array( 'taxonomy' => 'atelier_collection', 'field' => 'slug', 'terms' => array( $a['term'] ) );
	}
	if ( count( $tax ) > 1 ) {
		$tax['relation'] = 'AND';
	}
	return $tax;
}

/** @return WP_Query */
function atelier_collection_query( $a ) {
	$args = array(
		'post_type'      => 'atelier_product',
		'post_status'    => 'publish',
		'posts_per_page' => $a['per_page'],
		'paged'          => $a['page'],
		'tax_query'      => atelier_collection_tax_query( $a ), // phpcs:ignore WordPress.DB.SlowDBQuery
		'no_found_rows'  => false,
	);

	switch ( $a['orderby'] ) {
		case 'featured':
			$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
				'relation' => 'OR',
				'feat'     => array( 'key' => '_atelier_featured', 'compare' => 'EXISTS' ),
				array( 'key' => '_atelier_featured', 'compare' => 'NOT EXISTS' ),
			);
			$args['orderby'] = array( 'feat' => 'DESC', 'menu_order' => 'ASC', 'date' => 'DESC' );
			break;
		case 'newest':
			$args['orderby'] = array( 'date' => 'DESC' );
			break;
		case 'oldest':
			$args['orderby'] = array( 'date' => 'ASC' );
			break;
		case 'title':
			$args['orderby'] = array( 'title' => 'ASC' );
			break;
		default:
			$args['orderby'] = array( 'menu_order' => 'ASC', 'date' => 'DESC' );
	}

	return new WP_Query( $args );
}

/**
 * Terms for the filter bar (ordered by "Display order", then name) with product counts.
 *
 * @return array[] slug, name, count
 */
function atelier_collection_filter_terms( $allowed, $hide_empty = false ) {
	$terms = get_terms( array( 'taxonomy' => 'atelier_collection', 'hide_empty' => false, 'slug' => $allowed ? $allowed : '' ) );
	if ( is_wp_error( $terms ) || ! $terms ) {
		return array();
	}
	usort( $terms, function ( $a, $b ) {
		$oa = get_term_meta( $a->term_id, '_atelier_term_order', true );
		$ob = get_term_meta( $b->term_id, '_atelier_term_order', true );
		$oa = '' === $oa ? PHP_INT_MAX : (int) $oa;
		$ob = '' === $ob ? PHP_INT_MAX : (int) $ob;
		return $oa === $ob ? strcasecmp( $a->name, $b->name ) : ( $oa < $ob ? -1 : 1 );
	} );

	$out = array();
	foreach ( $terms as $t ) {
		if ( $hide_empty && ! $t->count ) {
			continue;
		}
		$out[] = array( 'slug' => $t->slug, 'name' => $t->name, 'count' => (int) $t->count );
	}
	return $out;
}

/** Total products in the allowed set (the "All" count; avoids double-counting multi-category products). */
function atelier_collection_total( $allowed ) {
	$q = new WP_Query( array(
		'post_type'      => 'atelier_product',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'tax_query'      => atelier_collection_tax_query( array( 'terms' => $allowed, 'term' => '' ) ), // phpcs:ignore WordPress.DB.SlowDBQuery
	) );
	return (int) $q->found_posts;
}

/* ============================== REST ============================== */
function atelier_collection_register_rest() {
	register_rest_route( 'atelier/v1', '/collection', array(
		'methods'             => 'GET',
		'callback'            => 'atelier_rest_collection',
		'permission_callback' => '__return_true',
	) );
	register_rest_route( 'atelier/v1', '/product/(?P<id>\d+)', array(
		'methods'             => 'GET',
		'callback'            => 'atelier_rest_product',
		'permission_callback' => '__return_true',
		'args'                => array(
			'id' => array(
				'validate_callback' => function ( $value ) {
					return is_numeric( $value );
				},
			),
		),
	) );
}
add_action( 'rest_api_init', 'atelier_collection_register_rest' );

function atelier_rest_collection( WP_REST_Request $req ) {
	$a = atelier_collection_args( array(
		'terms'    => $req->get_param( 'terms' ),
		'term'     => $req->get_param( 'term' ),
		'orderby'  => $req->get_param( 'orderby' ),
		'per_page' => $req->get_param( 'per_page' ),
		'page'     => $req->get_param( 'page' ),
		'featured' => $req->get_param( 'featured' ),
		'quick'    => null === $req->get_param( 'quick' ) ? true : $req->get_param( 'quick' ),
		'ref'      => null === $req->get_param( 'ref' ) ? true : $req->get_param( 'ref' ),
		'season'   => null === $req->get_param( 'season' ) ? true : $req->get_param( 'season' ),
	) );

	$q    = atelier_collection_query( $a );
	$html = atelier_collection_cards_html( $q, $a );

	return rest_ensure_response( array(
		'html'  => $html,
		'found' => (int) $q->found_posts,
		'pages' => (int) $q->max_num_pages,
		'page'  => $a['page'],
		'shown' => ( $a['page'] - 1 ) * $a['per_page'] + (int) $q->post_count,
	) );
}

function atelier_rest_product( WP_REST_Request $req ) {
	$post = get_post( (int) $req['id'] );
	if ( ! $post || 'atelier_product' !== $post->post_type || 'publish' !== $post->post_status ) {
		return new WP_Error( 'atelier_not_found', __( 'Product not found.', 'atelier-b2b' ), array( 'status' => 404 ) );
	}
	return rest_ensure_response( atelier_product_quickview_data( $post ) );
}
