<?php
/**
 * Custom post type that stores the Elementor-built global header.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_register_header_cpt() {
	register_post_type(
		'atelier_header',
		array(
			'labels'              => array(
				'name'               => __( 'Atelier Header', 'atelier-b2b' ),
				'singular_name'      => __( 'Atelier Header', 'atelier-b2b' ),
				'menu_name'          => __( 'Atelier Header', 'atelier-b2b' ),
				'all_items'          => __( 'All Headers', 'atelier-b2b' ),
				'add_new'            => __( 'Add New', 'atelier-b2b' ),
				'add_new_item'       => __( 'Add New Header', 'atelier-b2b' ),
				'edit_item'          => __( 'Edit Header', 'atelier-b2b' ),
				'new_item'           => __( 'New Header', 'atelier-b2b' ),
				'view_item'          => __( 'Preview Header', 'atelier-b2b' ),
				'search_items'       => __( 'Search Headers', 'atelier-b2b' ),
				'not_found'          => __( 'No headers yet. Open "Active Header" and click "Create starter header".', 'atelier-b2b' ),
				'not_found_in_trash' => __( 'No headers in Trash.', 'atelier-b2b' ),
			),
			// Not public (no archives, search, sitemap) but queryable so Elementor can load its preview.
			'public'              => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'show_in_rest'        => false, // Classic editor screen exposes "Edit with Elementor".
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false, // Plain ?atelier_header=slug URLs: no permalink flush needed.
			'query_var'           => true,
			'menu_position'       => 58,
			'menu_icon'           => 'dashicons-align-wide',
			'capability_type'     => 'page',
			'map_meta_cap'        => true,
			'supports'            => array( 'title', 'editor', 'elementor', 'revisions' ),
		)
	);
}
add_action( 'init', 'atelier_register_header_cpt' );

/**
 * Make sure Elementor's "post types" setting includes the header CPT.
 */
function atelier_enable_elementor_for_header_cpt() {
	$supported = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
	if ( ! is_array( $supported ) ) {
		$supported = array( 'page', 'post' );
	}
	if ( ! in_array( 'atelier_header', $supported, true ) ) {
		$supported[] = 'atelier_header';
		update_option( 'elementor_cpt_support', $supported );
	}
}
add_action( 'admin_init', 'atelier_enable_elementor_for_header_cpt' );
add_action( 'after_switch_theme', 'atelier_enable_elementor_for_header_cpt' );

/**
 * Preview body class.
 */
function atelier_header_preview_body_class( $classes ) {
	if ( is_singular( 'atelier_header' ) ) {
		$classes[] = 'atelier-header-preview';
	}
	return $classes;
}
add_filter( 'body_class', 'atelier_header_preview_body_class' );

/**
 * Keep the header CPT away from visitors and search engines (editors can still preview it).
 */
function atelier_header_cpt_guard() {
	if ( is_singular( 'atelier_header' ) && ! current_user_can( 'edit_pages' ) ) {
		wp_safe_redirect( home_url( '/' ), 302 );
		exit;
	}
}
add_action( 'template_redirect', 'atelier_header_cpt_guard' );

function atelier_header_cpt_noindex( $robots ) {
	if ( is_singular( 'atelier_header' ) ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'atelier_header_cpt_noindex' );
