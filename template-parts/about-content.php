<?php
/**
 * About Us page section stack.
 * Elementor Home Section when assigned; otherwise static PHP fallback.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

atelier_render_home_section( 'about_hero' );
atelier_render_home_section( 'about_who' );
atelier_render_home_section( 'about_philosophy' );
atelier_render_home_section( 'about_commitment' );
atelier_render_home_section( 'about_cta' );
