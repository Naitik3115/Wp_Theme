<?php
/**
 * Resolve and render the Elementor-built Hero / Trust sections, with automatic PHP fallback.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

/** Saved ID for a slot (not validated). */
function atelier_get_active_section_id_raw( $slot ) {
	$opt = get_option( 'atelier_active_sections', array() );
	return ( is_array( $opt ) && isset( $opt[ $slot ] ) ) ? absint( $opt[ $slot ] ) : 0;
}

/** Validated active section ID for a slot (0 = use PHP fallback). */
function atelier_get_active_section_id( $slot ) {
	if ( ! isset( atelier_section_slots()[ $slot ] ) ) {
		return 0;
	}
	$id = atelier_get_active_section_id_raw( $slot );
	if ( ! $id ) {
		return 0;
	}
	$post = get_post( $id );
	if ( ! $post || 'atelier_section' !== $post->post_type || 'publish' !== $post->post_status ) {
		return 0;
	}
	if ( get_post_meta( $id, '_atelier_section_slot', true ) !== $slot ) {
		return 0;
	}
	if ( 'builder' !== get_post_meta( $id, '_elementor_edit_mode', true ) ) {
		return 0;
	}
	return $id;
}

function atelier_use_elementor_section( $slot ) {
	static $cache = array();
	if ( isset( $cache[ $slot ] ) ) {
		return $cache[ $slot ];
	}
	$use = true;
	if ( defined( 'ATELIER_DISABLE_ELEMENTOR_SECTIONS' ) && ATELIER_DISABLE_ELEMENTOR_SECTIONS ) {
		$use = false;
	} elseif ( ! atelier_elementor_active() ) {
		$use = false;
	} elseif ( is_singular( 'atelier_section' ) ) {
		$use = false; // Recursion guard: sections have their own canvas template.
	} elseif ( ! atelier_get_active_section_id( $slot ) ) {
		$use = false;
	}
	$cache[ $slot ] = (bool) apply_filters( 'atelier_use_elementor_section', $use, $slot );
	return $cache[ $slot ];
}

/** Rendered Elementor HTML for a slot, '' if unavailable. */
function atelier_render_elementor_section( $slot ) {
	static $html = array();
	if ( isset( $html[ $slot ] ) ) {
		return $html[ $slot ];
	}
	$html[ $slot ] = '';

	if ( ! atelier_use_elementor_section( $slot ) ) {
		return '';
	}
	try {
		$out = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( atelier_get_active_section_id( $slot ) );
	} catch ( \Throwable $e ) {
		$out = '';
	}
	if ( is_string( $out ) && false !== strpos( $out, 'elementor-element' ) ) {
		$html[ $slot ] = $out;
	}
	return $html[ $slot ];
}

/**
 * Output a homepage slot: Elementor section if assigned and renderable, otherwise the original template part.
 */
function atelier_render_home_section( $slot ) {
	$slots = atelier_section_slots();
	if ( ! isset( $slots[ $slot ] ) ) {
		return;
	}
	$html = atelier_render_elementor_section( $slot );
	if ( '' !== $html ) {
		echo '<div class="atelier-home-section atelier-home-section--' . esc_attr( $slot ) . '">' . $html . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- Elementor output.
		return;
	}
	get_template_part( $slots[ $slot ]['part'] );
}

/** Head assets: glue CSS + Elementor post CSS for every active section. */
function atelier_enqueue_section_assets() {
	$preview = is_singular( 'atelier_section' );
	$active  = array();

	if ( atelier_use_elementor_section( 'footer' ) ) {
		$active['footer'] = atelier_get_active_section_id( 'footer' );
	}

	if ( atelier_is_homepage_layout() ) {
		foreach ( array_diff( array_keys( atelier_section_slots() ), array( 'footer' ) ) as $slot ) {
			if ( atelier_use_elementor_section( $slot ) ) {
				$active[ $slot ] = atelier_get_active_section_id( $slot );
			}
		}
	}

	if ( ! $active && ! $preview ) {
		return;
	}

	wp_enqueue_style(
		'atelier-elementor-sections',
		get_template_directory_uri() . '/assets/css/elementor-sections.css',
		array( 'atelier-responsive', 'atelier-animations' ),
		ATELIER_VERSION
	);

	if ( ! atelier_elementor_active() ) {
		return;
	}
	$plugin = \Elementor\Plugin::$instance;
	if ( isset( $plugin->frontend ) ) {
		if ( method_exists( $plugin->frontend, 'enqueue_styles' ) ) {
			$plugin->frontend->enqueue_styles();
		}
		if ( method_exists( $plugin->frontend, 'enqueue_scripts' ) ) {
			$plugin->frontend->enqueue_scripts();
		}
	}
	if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
		foreach ( $active as $id ) {
			$css = new \Elementor\Core\Files\CSS\Post( $id );
			$css->enqueue();
		}
	}
}
add_action( 'wp_enqueue_scripts', 'atelier_enqueue_section_assets', 20 );

/** The hero poster preload from enqueue-assets.php would point at the old image once the hero is edited. */
function atelier_maybe_drop_static_hero_preload() {
	if ( atelier_is_homepage_layout() && atelier_use_elementor_section( 'hero' ) ) {
		remove_action( 'wp_head', 'atelier_preload_hero', 2 );
	}
}
add_action( 'wp', 'atelier_maybe_drop_static_hero_preload' );
