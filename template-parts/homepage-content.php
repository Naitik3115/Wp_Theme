<?php
/**
 * Shared homepage section stack (front-page.php + Atelier Homepage page template).
 *
 * Order: Hero → Trust → Categories → Editorial Collection → Selected Work → Partnership.
 * Each slot: Elementor Home Section if assigned, otherwise static PHP template part.
 * Header/footer are rendered by get_header()/get_footer() — not duplicated here.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

atelier_render_home_section( 'hero' );
atelier_render_home_section( 'trust' );
atelier_render_home_section( 'categories' );
atelier_render_home_section( 'feature' );
atelier_render_home_section( 'showcase' );
atelier_render_home_section( 'partnership' );
