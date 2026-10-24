<?php
/**
 * "Home Sections" custom post type (Hero, Trust Marquee …) edited with Elementor Free.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

/** Slots that can be replaced by an Elementor section. */
function atelier_section_slots() {
	return array(
		'hero'  => array(
			'label' => __( 'Hero', 'atelier-b2b' ),
			'part'  => 'template-parts/home/hero',
			'json'  => 'atelier-hero.json',
			'title' => __( 'Atelier Hero', 'atelier-b2b' ),
		),
		'trust' => array(
			'label' => __( 'Trust Marquee', 'atelier-b2b' ),
			'part'  => 'template-parts/home/trust-marquee',
			'json'  => 'atelier-trust-marquee.json',
			'title' => __( 'Atelier Trust Marquee', 'atelier-b2b' ),
		),
		'categories' => array(
			'label' => __( 'Featured Categories', 'atelier-b2b' ),
			'part'  => 'template-parts/home/categories',
			'json'  => 'atelier-categories.json',
			'title' => __( 'Atelier Categories', 'atelier-b2b' ),
		),
		'feature' => array(
			'label' => __( 'Editorial Collection', 'atelier-b2b' ),
			'part'  => 'template-parts/home/featured-collection',
			'json'  => 'atelier-featured-collection.json',
			'title' => __( 'Atelier Editorial Collection', 'atelier-b2b' ),
		),
		'showcase' => array(
			'label' => __( 'Selected Work', 'atelier-b2b' ),
			'part'  => 'template-parts/home/product-showcase',
			'json'  => 'atelier-selected-work.json',
			'title' => __( 'Atelier Selected Work', 'atelier-b2b' ),
		),
		'partnership' => array(
			'label' => __( 'Partnership CTA', 'atelier-b2b' ),
			'part'  => 'template-parts/home/partnership-cta',
			'json'  => 'atelier-partnership-cta.json',
			'title' => __( 'Atelier Partnership CTA', 'atelier-b2b' ),
		),
		'footer' => array(
			'label' => __( 'Global Footer', 'atelier-b2b' ),
			'part'  => 'template-parts/footer/footer-main',
			'json'  => 'atelier-footer.json',
			'title' => __( 'Atelier Global Footer', 'atelier-b2b' ),
		),
	);
}

function atelier_register_section_cpt() {
	register_post_type(
		'atelier_section',
		array(
			'labels'              => array(
				'name'               => __( 'Home Sections', 'atelier-b2b' ),
				'singular_name'      => __( 'Home Section', 'atelier-b2b' ),
				'menu_name'          => __( 'Home Sections', 'atelier-b2b' ),
				'all_items'          => __( 'All Sections', 'atelier-b2b' ),
				'add_new'            => __( 'Add New', 'atelier-b2b' ),
				'add_new_item'       => __( 'Add New Section', 'atelier-b2b' ),
				'edit_item'          => __( 'Edit Section', 'atelier-b2b' ),
				'view_item'          => __( 'Preview Section', 'atelier-b2b' ),
				'search_items'       => __( 'Search Sections', 'atelier-b2b' ),
				'not_found'          => __( 'No sections yet. Open "Active Sections" to create a starter homepage section.', 'atelier-b2b' ),
				'not_found_in_trash' => __( 'No sections in Trash.', 'atelier-b2b' ),
			),
			'public'              => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => true,
			'menu_position'       => 59,
			'menu_icon'           => 'dashicons-layout',
			'capability_type'     => 'page',
			'map_meta_cap'        => true,
			'supports'            => array( 'title', 'editor', 'elementor', 'revisions' ),
		)
	);
}
add_action( 'init', 'atelier_register_section_cpt' );

function atelier_enable_elementor_for_section_cpt() {
	$supported = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
	if ( ! is_array( $supported ) ) {
		$supported = array( 'page', 'post' );
	}
	if ( ! in_array( 'atelier_section', $supported, true ) ) {
		$supported[] = 'atelier_section';
		update_option( 'elementor_cpt_support', $supported );
	}
}
add_action( 'admin_init', 'atelier_enable_elementor_for_section_cpt' );
add_action( 'after_switch_theme', 'atelier_enable_elementor_for_section_cpt' );

function atelier_section_preview_body_class( $classes ) {
	if ( is_singular( 'atelier_section' ) ) {
		$classes[] = 'atelier-section-preview';
		$slot      = get_post_meta( get_queried_object_id(), '_atelier_section_slot', true );
		if ( $slot ) {
			$classes[] = 'atelier-section-preview--' . sanitize_html_class( $slot );
		}
	}
	return $classes;
}
add_filter( 'body_class', 'atelier_section_preview_body_class' );

function atelier_section_cpt_guard() {
	if ( is_singular( 'atelier_section' ) && ! current_user_can( 'edit_pages' ) ) {
		wp_safe_redirect( home_url( '/' ), 302 );
		exit;
	}
}
add_action( 'template_redirect', 'atelier_section_cpt_guard' );

add_filter( 'wp_robots', function ( $robots ) {
	if ( is_singular( 'atelier_section' ) ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
} );

/* ---- "Section type" meta box (decides which homepage slot a section can be assigned to) ---- */
function atelier_section_add_meta_box() {
	add_meta_box( 'atelier_section_type', __( 'Section type', 'atelier-b2b' ), 'atelier_section_type_box', 'atelier_section', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'atelier_section_add_meta_box' );

function atelier_section_type_box( $post ) {
	wp_nonce_field( 'atelier_section_type', 'atelier_section_type_nonce' );
	$current = get_post_meta( $post->ID, '_atelier_section_slot', true );
	echo '<select name="atelier_section_slot" style="width:100%">';
	echo '<option value="">' . esc_html__( 'Other (not assignable)', 'atelier-b2b' ) . '</option>';
	foreach ( atelier_section_slots() as $slot => $info ) {
		printf( '<option value="%s"%s>%s</option>', esc_attr( $slot ), selected( $current, $slot, false ), esc_html( $info['label'] ) );
	}
	echo '</select><p class="description">' . esc_html__( 'Hero sections can only be assigned to the Hero slot, etc.', 'atelier-b2b' ) . '</p>';
}

function atelier_section_save_type( $post_id ) {
	if ( ! isset( $_POST['atelier_section_type_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['atelier_section_type_nonce'] ) ), 'atelier_section_type' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$slot = isset( $_POST['atelier_section_slot'] ) ? sanitize_key( wp_unslash( $_POST['atelier_section_slot'] ) ) : '';
	if ( $slot && ! isset( atelier_section_slots()[ $slot ] ) ) {
		$slot = '';
	}
	update_post_meta( $post_id, '_atelier_section_slot', $slot );
}
add_action( 'save_post_atelier_section', 'atelier_section_save_type' );

add_filter( 'manage_atelier_section_posts_columns', function ( $cols ) {
	$cols['atelier_type']   = __( 'Section type', 'atelier-b2b' );
	$cols['atelier_active'] = __( 'Status on site', 'atelier-b2b' );
	return $cols;
} );
add_action( 'manage_atelier_section_posts_custom_column', function ( $col, $post_id ) {
	if ( 'atelier_type' === $col ) {
		$slot  = get_post_meta( $post_id, '_atelier_section_slot', true );
		$slots = atelier_section_slots();
		echo esc_html( isset( $slots[ $slot ] ) ? $slots[ $slot ]['label'] : '—' );
	} elseif ( 'atelier_active' === $col ) {
		$slot = get_post_meta( $post_id, '_atelier_section_slot', true );
		echo $slot && atelier_get_active_section_id_raw( $slot ) === (int) $post_id ? '<strong>' . esc_html__( 'Active', 'atelier-b2b' ) . '</strong>' : '&mdash;';
	}
}, 10, 2 );
