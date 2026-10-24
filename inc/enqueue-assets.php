<?php
/**
 * Enqueue styles and scripts.
 *
 * GSAP + ScrollTrigger are bundled locally in assets/js/vendor (no CDN).
 * Fonts load from Google Fonts with system fall-backs (see README to self-host).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_enqueue_assets() {
	$uri = get_template_directory_uri();
	$ver = ATELIER_VERSION;

	wp_enqueue_style(
		'atelier-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400;1,500&family=Inter:wght@400;500&family=Manrope:wght@400;500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'atelier-style', get_stylesheet_uri(), array(), $ver );
	wp_enqueue_style( 'atelier-main', $uri . '/assets/css/main.css', array( 'atelier-style', 'atelier-fonts' ), $ver );
	wp_enqueue_style( 'atelier-animations', $uri . '/assets/css/animations.css', array( 'atelier-main' ), $ver );
	wp_enqueue_style( 'atelier-responsive', $uri . '/assets/css/responsive.css', array( 'atelier-animations' ), $ver );

	$defer = array( 'strategy' => 'defer', 'in_footer' => true );

	wp_register_script( 'atelier-gsap', $uri . '/assets/js/vendor/gsap.min.js', array(), '3.12.5', $defer );
	wp_register_script( 'atelier-scrolltrigger', $uri . '/assets/js/vendor/ScrollTrigger.min.js', array( 'atelier-gsap' ), '3.12.5', $defer );

	wp_enqueue_script( 'atelier-main', $uri . '/assets/js/main.js', array(), $ver, $defer );
	wp_enqueue_script( 'atelier-header', $uri . '/assets/js/header.js', array( 'atelier-main' ), $ver, $defer );

	$needs_animations = atelier_has_hero()
		|| is_singular( 'atelier_section' )
		|| atelier_is_elementor_editor()
		|| ( function_exists( 'atelier_use_elementor_section' ) && atelier_use_elementor_section( 'footer' ) );

	if ( atelier_has_hero() || is_singular( 'atelier_section' ) || atelier_is_elementor_editor() ) {
		wp_enqueue_script( 'atelier-sliders', $uri . '/assets/js/sliders.js', array( 'atelier-main' ), $ver, $defer );
	}
	if ( $needs_animations ) {
		wp_enqueue_script( 'atelier-animations', $uri . '/assets/js/animations.js', array( 'atelier-scrolltrigger', 'atelier-main' ), $ver, $defer );
	}
}
add_action( 'wp_enqueue_scripts', 'atelier_enqueue_assets' );

/**
 * Preconnect to Google Fonts and preload the hero poster (LCP).
 */
function atelier_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'atelier_resource_hints', 10, 2 );

function atelier_preload_hero() {
	if ( ! atelier_has_hero() ) {
		return;
	}
	$url = atelier_asset_url( 'hero_poster' );
	if ( $url ) {
		printf( '<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n", esc_url( $url ) );
	}
}
add_action( 'wp_head', 'atelier_preload_hero', 2 );
