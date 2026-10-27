<?php
/**
 * Taxonomy: Collection Categories (unlimited, hierarchical, products can have several) + optional term order.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_register_collection_taxonomy() {
	register_taxonomy(
		'atelier_collection',
		array( 'atelier_product' ),
		array(
			'labels'            => array(
				'name'          => __( 'Collection Categories', 'atelier-b2b' ),
				'singular_name' => __( 'Collection Category', 'atelier-b2b' ),
				'menu_name'     => __( 'Collection Categories', 'atelier-b2b' ),
				'all_items'     => __( 'All Categories', 'atelier-b2b' ),
				'add_new_item'  => __( 'Add New Category', 'atelier-b2b' ),
				'edit_item'     => __( 'Edit Category', 'atelier-b2b' ),
				'search_items'  => __( 'Search Categories', 'atelier-b2b' ),
			),
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_in_rest'      => false,
			'rewrite'           => false,
			'query_var'         => false,
		)
	);
}
add_action( 'init', 'atelier_register_collection_taxonomy' );

/* ---- Display order for the filter bar (lower first, blank = after ordered terms, then A–Z) ---- */
function atelier_term_order_field_add() {
	wp_nonce_field( 'atelier_term_order', 'atelier_term_order_nonce' );
	echo '<div class="form-field"><label for="atelier_term_order">' . esc_html__( 'Display order', 'atelier-b2b' ) . '</label><input type="number" name="atelier_term_order" id="atelier_term_order" value="" class="small-text"><p>' . esc_html__( 'Position in the filter bar (1 = first). Optional.', 'atelier-b2b' ) . '</p></div>';
}
add_action( 'atelier_collection_add_form_fields', 'atelier_term_order_field_add' );

function atelier_term_order_field_edit( $term ) {
	wp_nonce_field( 'atelier_term_order', 'atelier_term_order_nonce' );
	$val = get_term_meta( $term->term_id, '_atelier_term_order', true );
	echo '<tr class="form-field"><th scope="row"><label for="atelier_term_order">' . esc_html__( 'Display order', 'atelier-b2b' ) . '</label></th><td><input type="number" name="atelier_term_order" id="atelier_term_order" value="' . esc_attr( $val ) . '" class="small-text"><p class="description">' . esc_html__( 'Position in the filter bar (1 = first). Optional.', 'atelier-b2b' ) . '</p></td></tr>';
}
add_action( 'atelier_collection_edit_form_fields', 'atelier_term_order_field_edit' );

function atelier_term_order_save( $term_id ) {
	if ( ! isset( $_POST['atelier_term_order_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['atelier_term_order_nonce'] ) ), 'atelier_term_order' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	$val = isset( $_POST['atelier_term_order'] ) ? sanitize_text_field( wp_unslash( $_POST['atelier_term_order'] ) ) : '';
	if ( '' === $val ) {
		delete_term_meta( $term_id, '_atelier_term_order' );
	} else {
		update_term_meta( $term_id, '_atelier_term_order', (int) $val );
	}
}
add_action( 'created_atelier_collection', 'atelier_term_order_save' );
add_action( 'edited_atelier_collection', 'atelier_term_order_save' );
