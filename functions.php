<?php
/**
 * Atelier B2B – theme bootstrap.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

define( 'ATELIER_VERSION', '1.2.0' );

require_once get_template_directory() . '/inc/theme-setup.php';
require_once get_template_directory() . '/inc/asset-config.php';
require_once get_template_directory() . '/inc/content-config.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/sections-markup.php'; // Phase 3: shared Hero / Trust markup (static + Elementor).
require_once get_template_directory() . '/inc/enqueue-assets.php';

// Phase 2 + 3: optional Elementor Free integration (falls back to static PHP automatically).
require_once get_template_directory() . '/inc/elementor/elementor-integration.php';
