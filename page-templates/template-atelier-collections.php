<?php
/**
 * Template Name: Atelier Collections
 * Template Post Type: page
 *
 * Without Elementor content the page shows the default composition (hero → filterable product grid → CTA).
 * Once the page is built/edited with Elementor (e.g. by inserting the "Atelier Collections Page" template)
 * the Elementor content is printed instead. Header and footer are the global ones.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
get_header();

if ( atelier_collections_page_uses_elementor() || atelier_is_elementor_editor() ) {
	while ( have_posts() ) {
		the_post();
		the_content();
	}
} else {
	get_template_part( 'template-parts/collections/collection-hero', null, array(
		'eyebrow' => __( 'The Atelier Edit', 'atelier-b2b' ),
		'title'   => "EXPLORE OUR\nCOLLECTIONS.",
		'text'    => __( 'A considered selection of apparel, crafted for brands that value design, quality, and individuality.', 'atelier-b2b' ),
		'scroll'  => __( 'Scroll', 'atelier-b2b' ),
		'anchor'  => '#collection-grid',
	) );

	atelier_collection_render();

	get_template_part( 'template-parts/collections/collection-cta', null, array(
		'label' => __( 'Let’s build something together', 'atelier-b2b' ),
		'title' => "SHARE YOUR\nCONCEPT.",
		'text'  => __( 'Tell us what you’re building, and we’ll take it from there.', 'atelier-b2b' ),
		'cta'   => array( 'label' => __( 'Start an inquiry', 'atelier-b2b' ), 'url' => atelier_url( 'contact' ) ),
	) );
}

get_footer();
