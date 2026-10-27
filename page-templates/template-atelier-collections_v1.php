<?php
/**
 * Template Name: Atelier Collections V1
 * Template Post Type: page
 *
 * Product catalog with category filters. Supports Edit with Elementor:
 * when the page is built in Elementor, the_content() renders (add the
 * Atelier Product Catalog widget). Otherwise the static PHP fallback loads.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( atelier_page_uses_elementor() ) {
	while ( have_posts() ) {
		the_post();
		echo '<div class="atelier-page atelier-page--elementor atelier-page--collections">';
		the_content();
		echo '</div>';
	}
} else {
	get_template_part( 'template-parts/collections-content' );
}

get_footer();
