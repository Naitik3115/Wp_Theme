<?php
/**
 * Custom post type: Atelier Products (showcase items – no prices, cart or checkout).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_register_product_cpt() {
	register_post_type(
		'atelier_product',
		array(
			'labels'              => array(
				'name'               => __( 'Atelier Products', 'atelier-b2b' ),
				'singular_name'      => __( 'Atelier Product', 'atelier-b2b' ),
				'menu_name'          => __( 'Atelier Products', 'atelier-b2b' ),
				'all_items'          => __( 'All Products', 'atelier-b2b' ),
				'add_new'            => __( 'Add New', 'atelier-b2b' ),
				'add_new_item'       => __( 'Add New Product', 'atelier-b2b' ),
				'edit_item'          => __( 'Edit Product', 'atelier-b2b' ),
				'new_item'           => __( 'New Product', 'atelier-b2b' ),
				'search_items'       => __( 'Search Products', 'atelier-b2b' ),
				'not_found'          => __( 'No products found.', 'atelier-b2b' ),
				'not_found_in_trash' => __( 'No products in Trash.', 'atelier-b2b' ),
				'featured_image'     => __( 'Main image', 'atelier-b2b' ),
				'set_featured_image' => __( 'Set main image', 'atelier-b2b' ),
				'remove_featured_image' => __( 'Remove main image', 'atelier-b2b' ),
				'use_featured_image' => __( 'Use as main image', 'atelier-b2b' ),
			),
			// Products are shown through the grid + Quick View only: no single pages / archives / search.
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'menu_position'       => 57,
			'menu_icon'           => 'dashicons-tag',
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
			'taxonomies'          => array( 'atelier_collection' ),
		)
	);
}
add_action( 'init', 'atelier_register_product_cpt' );
