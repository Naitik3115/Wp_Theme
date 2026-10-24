<?php
/**
 * Theme supports, menus and body classes.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_setup() {
	load_theme_textdomain( 'atelier-b2b', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 220,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'atelier-b2b' ),
		)
	);
}
add_action( 'after_setup_theme', 'atelier_setup' );

/**
 * Flag the front page as the "hero" page (transparent header overlay).
 */
function atelier_body_classes( $classes ) {
	if ( atelier_has_hero() ) {
		$classes[] = 'has-hero';
	}
	return $classes;
}
add_filter( 'body_class', 'atelier_body_classes' );

function atelier_has_hero() {
	return (bool) apply_filters( 'atelier_has_hero', atelier_is_homepage_layout() );
}

function atelier_is_homepage_layout() {
	return is_front_page() || is_page_template( 'page-templates/template-atelier-home.php' );
}
