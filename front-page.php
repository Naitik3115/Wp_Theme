<?php
/**
 * Homepage.
 *
 * Hero + Trust marquee: Elementor section if one is assigned, otherwise the original PHP template part.
 * All other sections are unchanged static template parts.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
get_header();
get_template_part( 'template-parts/homepage-content' );
get_footer();
