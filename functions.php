<?php
/**
 * Atelier B2B – theme bootstrap.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

define( 'ATELIER_VERSION', '1.7.1' );

require_once get_template_directory() . '/inc/theme-setup.php';
require_once get_template_directory() . '/inc/asset-config.php';
require_once get_template_directory() . '/inc/content-config.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/sections-markup.php'; // Phase 3: shared Hero / Trust markup (static + Elementor).
require_once get_template_directory() . '/inc/contact-markup.php'; // Contact page sections (static + Elementor).
require_once get_template_directory() . '/inc/contact-form.php';
require_once get_template_directory() . '/inc/about-markup.php'; // About page sections (static + Elementor).
require_once get_template_directory() . '/inc/collections-markup.php'; // Product catalog (static + Elementor).
require_once get_template_directory() . '/inc/enqueue-assets.php';

// Phase 2 + 3: optional Elementor Free integration (falls back to static PHP automatically).
require_once get_template_directory() . '/inc/elementor/elementor-integration.php';
