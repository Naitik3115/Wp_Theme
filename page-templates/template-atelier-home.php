<?php
/**
 * Template Name: Atelier Homepage
 * Template Post Type: page
 *
 * Assignable homepage layout for any WordPress page (not only the front page).
 * Uses the same section stack as front-page.php via homepage-content.php.
 * Elementor Home Sections replace slots when active; otherwise PHP fallbacks.
 *
 * Note: If this page is also set as the site front page, WordPress loads
 * front-page.php instead — both call the same template part, so output matches.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
get_header();
get_template_part( 'template-parts/homepage-content' );
get_footer();
