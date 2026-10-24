<?php
/**
 * Static homepage content (Phase 1).
 *
 * Every section reads from atelier_content( 'section' ). Later, swap the arrays
 * below for ACF / options / custom post types without touching the templates.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_url( $slug = '' ) {
	return home_url( '/' . ltrim( $slug, '/' ) . ( $slug ? '/' : '' ) );
}

function atelier_content( $section ) {
	static $c = null;

	if ( null === $c ) {
		$c = array(
			'announcement' => array(
				'speed' => 45, // seconds per loop. Lower = faster.
				'items' => array(
					'Premium apparel manufacturing',
					'Designed for global brands',
					'Quality without compromise',
					'Your vision, our craft',
				),
			),

			'nav' => array(
				array( 'label' => 'Home', 'url' => atelier_url() ),
				array( 'label' => 'About Us', 'url' => atelier_url( 'about-us' ) ),
				array( 'label' => 'Our Expertise', 'url' => atelier_url( 'our-expertise' ) ),
				array( 'label' => 'Collections', 'url' => atelier_url( 'collections' ) ),
				array( 'label' => 'Manufacturing', 'url' => atelier_url( 'manufacturing' ) ),
				array( 'label' => 'Contact', 'url' => atelier_url( 'contact' ) ),
			),

			'nav_cta' => array( 'label' => 'Partner with us', 'url' => atelier_url( 'contact' ) ),

			'hero' => array(
				'eyebrow'   => 'Crafted for the world',
				'title'     => "WE CREATE\nWHAT BRANDS\nBECOME.",
				'sub'       => 'Premium apparel manufacturing, thoughtfully crafted for ambitious brands across the globe.',
				'cta_1'     => array( 'label' => 'Explore our expertise', 'url' => atelier_url( 'our-expertise' ) ),
				'cta_2'     => array( 'label' => 'Discover collections', 'url' => atelier_url( 'collections' ) ),
				'scroll'    => 'Scroll',
			),

			'trust' => array(
				'label' => 'Your trusted apparel manufacturing partner',
				'speed' => 60,
				'items' => array(
					'Premium Quality',
					'Global Sourcing',
					'Precision Manufacturing',
					'Responsible Production',
					'Made for your brand',
				),
			),

			'categories' => array(
				'label'    => '01 / What we create',
				'title'    => "CATEGORIES\nOF EXPRESSION.",
				'text'     => 'From everyday essentials to statement collections, explore apparel crafted around your brand’s vision.',
				'items'    => array(
					array( 'title' => 'Menswear', 'image' => 'category_men', 'url' => atelier_url( 'collections/menswear' ) ),
					array( 'title' => 'Womenswear', 'image' => 'category_women', 'url' => atelier_url( 'collections/womenswear' ) ),
					array( 'title' => 'Streetwear', 'image' => 'category_street', 'url' => atelier_url( 'collections/streetwear' ) ),
					array( 'title' => 'Essentials', 'image' => 'category_essential', 'url' => atelier_url( 'collections/essentials' ) ),
					array( 'title' => 'Activewear', 'image' => 'category_active', 'url' => atelier_url( 'collections/activewear' ) ),
				),
			),

			'feature' => array(
				'eyebrow' => 'Beyond the ordinary / 2026',
				'title'   => "DESIGNED\nTO MAKE\nA STATEMENT.",
				'text'    => 'Every collection begins with an idea. Through thoughtful materials, refined craftsmanship, and attention to detail, we transform creative visions into apparel that speaks for itself.',
				'cta'     => array( 'label' => 'Explore the collection', 'url' => atelier_url( 'collections' ) ),
				'image'   => 'editorial',
				'index'   => '01',
				'tag'     => 'Featured collection',
			),

			'showcase' => array(
				'label' => '02 / Selected work',
				'title' => "THE ART OF\nEVERYDAY WEAR.",
				'text'  => 'Thoughtfully developed apparel, made to represent the identity of every brand we partner with.',
				'cta'   => array( 'label' => 'View all collections', 'url' => atelier_url( 'collections' ) ),
				'items' => array(
					array( 'index' => '01', 'title' => 'Relaxed Essentials', 'category' => 'Everyday Collection', 'image' => 'showcase_01', 'url' => atelier_url( 'collections' ) ),
					array( 'index' => '02', 'title' => 'Modern Tailoring', 'category' => 'Contemporary Apparel', 'image' => 'showcase_02', 'url' => atelier_url( 'collections' ) ),
					array( 'index' => '03', 'title' => 'Elevated Streetwear', 'category' => 'Urban Collection', 'image' => 'showcase_03', 'url' => atelier_url( 'collections' ) ),
					array( 'index' => '04', 'title' => 'Performance Wear', 'category' => 'Active Collection', 'image' => 'showcase_04', 'url' => atelier_url( 'collections' ) ),
				),
			),

			'cta' => array(
				'label' => 'Let’s build something together',
				'title' => "YOUR VISION.\nOUR CRAFT.",
				'text'  => 'From concept to creation, we partner with brands to bring exceptional apparel to life.',
				'cta'   => array( 'label' => 'Start a conversation', 'url' => atelier_url( 'contact' ) ),
			),

			'footer' => array(
				'statement' => "LET’S CREATE\nWHAT’S NEXT.",
				'cta'       => array( 'label' => 'Get in touch', 'url' => atelier_url( 'contact' ) ),
				'intro'     => 'Premium apparel manufacturing for ambitious brands around the world.',
				'col_1'     => array(
					array( 'label' => 'Home', 'url' => atelier_url() ),
					array( 'label' => 'About Us', 'url' => atelier_url( 'about-us' ) ),
					array( 'label' => 'Expertise', 'url' => atelier_url( 'our-expertise' ) ),
					array( 'label' => 'Collections', 'url' => atelier_url( 'collections' ) ),
				),
				'col_2'     => array(
					array( 'label' => 'Manufacturing', 'url' => atelier_url( 'manufacturing' ) ),
					array( 'label' => 'Contact', 'url' => atelier_url( 'contact' ) ),
					array( 'label' => 'Privacy Policy', 'url' => 'privacy' ),
				),
				// PLACEHOLDERS – replace with real details.
				'email'     => 'hello@example.com',
				'phone'     => '+00 000 000 0000',
				'location'  => 'City, Country',
				'social'    => array(
					array( 'label' => 'Instagram', 'url' => '#' ),
					array( 'label' => 'LinkedIn', 'url' => '#' ),
					array( 'label' => 'Pinterest', 'url' => '#' ),
				),
				'terms_url' => '#',
			),
		);
	}

	return isset( $c[ $section ] ) ? $c[ $section ] : array();
}
