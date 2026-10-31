<?php
/**
 * Centralised media configuration.
 *
 * To replace any image: overwrite the file in /assets/images (keep the file name),
 * or change the 'file' value below. Update 'w' / 'h' if the new image has a
 * different pixel size so layout shift stays at zero.
 *
 * The files shipped with the theme are PLACEHOLDERS, not final photography.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_assets() {
	static $assets = null;

	if ( null === $assets ) {
		$assets = array(
			'logo'               => array( 'file' => 'images/logo.svg', 'w' => 200, 'h' => 44, 'alt' => '' ),

			// Hero: landscape for tablet/desktop; optional portrait for mobile (hero-poster-mobile.jpg).
			'hero_poster'        => array( 'file' => 'images/hero-poster.jpg', 'w' => 1920, 'h' => 1080, 'alt' => 'Premium apparel campaign imagery' ),
			'hero_poster_mobile' => array( 'file' => 'images/hero-poster-mobile.jpg', 'w' => 1080, 'h' => 1620, 'alt' => 'Premium apparel campaign imagery' ),
			'hero_video'         => array( 'file' => 'video/hero-video.mp4' ),

			'category_men'       => array( 'file' => 'images/category-men.jpg', 'w' => 1200, 'h' => 1600, 'alt' => 'Menswear collection' ),
			'category_women'     => array( 'file' => 'images/category-women.jpg', 'w' => 1200, 'h' => 1600, 'alt' => 'Womenswear collection' ),
			'category_street'    => array( 'file' => 'images/category-streetwear.jpg', 'w' => 1200, 'h' => 1600, 'alt' => 'Streetwear collection' ),
			'category_essential' => array( 'file' => 'images/category-essentials.jpg', 'w' => 1200, 'h' => 1600, 'alt' => 'Essentials collection' ),
			'category_active'    => array( 'file' => 'images/category-activewear.jpg', 'w' => 1200, 'h' => 1600, 'alt' => 'Activewear collection' ),

			'editorial'          => array( 'file' => 'images/editorial-collection.webp', 'w' => 1400, 'h' => 1800, 'alt' => 'Editorial fashion image from the featured collection' ),

			'showcase_01'        => array( 'file' => 'images/category-men.jpg', 'w' => 1200, 'h' => 1600, 'alt' => 'Relaxed essentials apparel' ),
			'showcase_02'        => array( 'file' => 'images/category-women.jpg', 'w' => 1200, 'h' => 1500, 'alt' => 'Modern tailoring apparel' ),
			'showcase_03'        => array( 'file' => 'images/category-streetwear-left.jpg', 'w' => 1800, 'h' => 1125, 'alt' => 'Elevated streetwear apparel' ),
			'showcase_04'        => array( 'file' => 'images/category-activewear.jpg', 'w' => 1200, 'h' => 1600, 'alt' => 'Performance wear apparel' ),
		);
		$assets = apply_filters( 'atelier_assets', $assets );
	}

	return $assets;
}

function atelier_asset_exists( $key ) {
	$assets = atelier_assets();
	if ( empty( $assets[ $key ]['file'] ) ) {
		return false;
	}
	return file_exists( get_template_directory() . '/assets/' . $assets[ $key ]['file'] );
}

function atelier_asset_url( $key ) {
	$assets = atelier_assets();
	if ( empty( $assets[ $key ]['file'] ) || ! atelier_asset_exists( $key ) ) {
		return '';
	}
	return get_template_directory_uri() . '/assets/' . $assets[ $key ]['file'];
}

/**
 * Echo an <img> for a configured asset.
 *
 * @param string $key  Key from atelier_assets().
 * @param array  $args class, loading (lazy|eager), fetchpriority, sizes, alt.
 */
function atelier_image( $key, $args = array() ) {
	$assets = atelier_assets();
	$url    = atelier_asset_url( $key );
	if ( ! $url ) {
		return;
	}
	$a    = $assets[ $key ];
	$args = wp_parse_args(
		$args,
		array(
			'class'         => '',
			'loading'       => 'lazy',
			'fetchpriority' => '',
			'sizes'         => '100vw',
			'alt'           => isset( $a['alt'] ) ? $a['alt'] : '',
		)
	);

	printf(
		'<img src="%1$s" width="%2$d" height="%3$d" alt="%4$s" class="%5$s" loading="%6$s" decoding="async" sizes="%7$s"%8$s>',
		esc_url( $url ),
		(int) $a['w'],
		(int) $a['h'],
		esc_attr( $args['alt'] ),
		esc_attr( $args['class'] ),
		esc_attr( $args['loading'] ),
		esc_attr( $args['sizes'] ),
		$args['fetchpriority'] ? ' fetchpriority="' . esc_attr( $args['fetchpriority'] ) . '"' : ''
	);
}
