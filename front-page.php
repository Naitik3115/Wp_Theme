<?php
/**
 * Site front page (Reading Settings → Your homepage displays → A static page).
 *
 * Renders the shared homepage assembly. WordPress prefers this file over a page
 * template when the page is set as the front page — both paths share the same
 * template part so Elementor-assigned sections and PHP fallbacks stay in sync.
 *
 * To reuse the full homepage on any other page: Template → Atelier Homepage.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
get_header();
get_template_part( 'template-parts/homepage-content' );
get_footer();
