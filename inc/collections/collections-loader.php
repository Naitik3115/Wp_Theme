<?php
/**
 * Phase 5 loader: Atelier Products (CPT), Collection Categories (taxonomy), REST data, assets,
 * Collections page template routing, optional demo content and Elementor widgets.
 *
 * Kill-switch for the Elementor widgets only: nothing to disable – they simply do not register
 * when Elementor is inactive.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$atelier_col_dir = __DIR__;

require_once $atelier_col_dir . '/register-post-type.php';
require_once $atelier_col_dir . '/register-taxonomy.php';
require_once $atelier_col_dir . '/product-meta.php';
require_once $atelier_col_dir . '/product-query.php';
require_once $atelier_col_dir . '/collection-helpers.php';
require_once $atelier_col_dir . '/collection-demo.php';

unset( $atelier_col_dir );

/** Register the three Collections widgets (extends the "Atelier" category created in Phase 2). */
function atelier_col_register_widgets( $widgets_manager ) {
	$base = get_template_directory() . '/inc/elementor/widgets/';
	require_once $base . 'class-atelier-collection-grid.php';
	require_once $base . 'class-atelier-collections-hero.php';
	require_once $base . 'class-atelier-collections-cta.php';

	$widgets = array(
		new Atelier_Collection_Grid_Widget(),
		new Atelier_Collections_Hero_Widget(),
		new Atelier_Collections_Cta_Widget(),
	);
	foreach ( $widgets as $widget ) {
		if ( method_exists( $widgets_manager, 'register' ) ) {
			$widgets_manager->register( $widget );
		} else {
			$widgets_manager->register_widget_type( $widget );
		}
	}
}
add_action( 'elementor/widgets/register', 'atelier_col_register_widgets' );
