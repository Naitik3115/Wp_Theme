<?php
/**
 * Elementor Free integration: global header (Phase 2) + Hero / Trust marquee sections (Phase 3).
 * Everything is inert if Elementor is missing, so the static PHP templates keep working.
 *
 * Kill-switches (wp-config.php):
 *   define( 'ATELIER_DISABLE_ELEMENTOR_HEADER', true );
 *   define( 'ATELIER_DISABLE_ELEMENTOR_SECTIONS', true );
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/header-cpt.php';
require_once __DIR__ . '/header-render.php';
require_once __DIR__ . '/header-settings.php';
require_once __DIR__ . '/sections-cpt.php';
require_once __DIR__ . '/section-render.php';
require_once __DIR__ . '/section-settings.php';

function atelier_el_register_category( $elements_manager ) {
	$elements_manager->add_category(
		'atelier',
		array(
			'title' => __( 'Atelier', 'atelier-b2b' ),
			'icon'  => 'eicon-header',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'atelier_el_register_category' );

function atelier_el_register_widgets( $widgets_manager ) {
	require_once __DIR__ . '/widgets/class-atelier-navigation.php';
	require_once __DIR__ . '/widgets/class-atelier-marquee.php';
	require_once __DIR__ . '/widgets/class-atelier-hero.php';
	require_once __DIR__ . '/widgets/class-atelier-trust-marquee.php';
	require_once __DIR__ . '/widgets/class-atelier-categories.php';
	require_once __DIR__ . '/widgets/class-atelier-featured-collection.php';
	require_once __DIR__ . '/widgets/class-atelier-selected-work.php';
	require_once __DIR__ . '/widgets/class-atelier-partnership-cta.php';
	require_once __DIR__ . '/widgets/class-atelier-contact-form.php';
	require_once __DIR__ . '/widgets/class-atelier-offices.php';
	require_once __DIR__ . '/widgets/class-atelier-about-hero.php';
	require_once __DIR__ . '/widgets/class-atelier-about-who.php';
	require_once __DIR__ . '/widgets/class-atelier-about-philosophy.php';
	require_once __DIR__ . '/widgets/class-atelier-about-commitment.php';
	require_once __DIR__ . '/widgets/class-atelier-about-cta.php';
	require_once __DIR__ . '/widgets/class-atelier-product-catalog.php';
	require_once __DIR__ . '/widgets/class-atelier-footer.php';

	$widgets = array(
		new Atelier_Navigation_Widget(),
		new Atelier_Marquee_Widget(),
		new Atelier_Hero_Widget(),
		new Atelier_Trust_Marquee_Widget(),
		new Atelier_Categories_Widget(),
		new Atelier_Featured_Collection_Widget(),
		new Atelier_Selected_Work_Widget(),
		new Atelier_Partnership_CTA_Widget(),
		new Atelier_Contact_Form_Widget(),
		new Atelier_Offices_Widget(),
		new Atelier_About_Hero_Widget(),
		new Atelier_About_Who_Widget(),
		new Atelier_About_Philosophy_Widget(),
		new Atelier_About_Commitment_Widget(),
		new Atelier_About_CTA_Widget(),
		new Atelier_Product_Catalog_Widget(),
		new Atelier_Footer_Widget(),
	);
	foreach ( $widgets as $widget ) {
		if ( method_exists( $widgets_manager, 'register' ) ) {
			$widgets_manager->register( $widget );
		} else { // Elementor < 3.5.
			$widgets_manager->register_widget_type( $widget );
		}
	}
}
add_action( 'elementor/widgets/register', 'atelier_el_register_widgets' );
add_action( 'elementor/widgets/widgets_registered', function ( $widgets_manager ) {
	if ( ! did_action( 'elementor/widgets/register' ) ) {
		atelier_el_register_widgets( $widgets_manager );
	}
} );
