<?php
/**
 * Resolve the active header and render it with Elementor's frontend API.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

/** Is Elementor loaded? */
function atelier_elementor_active() {
	return did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance );
}

/**
 * Is the current request the Elementor editor / preview iframe?
 */
function atelier_is_elementor_editor() {
	if ( ! atelier_elementor_active() ) {
		return false;
	}
	$plugin = \Elementor\Plugin::$instance;
	if ( isset( $plugin->editor ) && $plugin->editor->is_edit_mode() ) {
		return true;
	}
	if ( isset( $plugin->preview ) && $plugin->preview->is_preview_mode() ) {
		return true;
	}
	return false;
}

/**
 * Saved + validated active header ID (0 = none / invalid).
 */
function atelier_get_active_header_id() {
	$id = absint( get_option( 'atelier_active_header_id', 0 ) );
	if ( ! $id ) {
		return 0;
	}
	$post = get_post( $id );
	if ( ! $post || 'atelier_header' !== $post->post_type || 'publish' !== $post->post_status ) {
		return 0;
	}
	if ( 'builder' !== get_post_meta( $id, '_elementor_edit_mode', true ) ) {
		return 0; // Not built with Elementor yet.
	}
	return $id;
}

/**
 * Should the Elementor header be used on this request?
 */
function atelier_use_elementor_header() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$use = true;
	if ( defined( 'ATELIER_DISABLE_ELEMENTOR_HEADER' ) && ATELIER_DISABLE_ELEMENTOR_HEADER ) {
		$use = false;
	} elseif ( ! atelier_elementor_active() ) {
		$use = false;
	} elseif ( is_singular( 'atelier_header' ) ) {
		$use = false; // Prevent recursion: the header CPT has its own canvas template.
	} elseif ( ! atelier_get_active_header_id() ) {
		$use = false;
	}

	$cache = (bool) apply_filters( 'atelier_use_elementor_header', $use );
	return $cache;
}

/**
 * Render the Elementor header markup. Returns '' if anything is unavailable so the static header is used.
 *
 * @return string
 */
function atelier_render_elementor_header() {
	static $html = null;
	if ( null !== $html ) {
		return $html;
	}
	$html = '';

	if ( ! atelier_use_elementor_header() ) {
		return $html;
	}

	try {
		$out = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( atelier_get_active_header_id() );
	} catch ( \Throwable $e ) {
		$out = '';
	}

	// Treat an emptied header as "unavailable" so the site never shows a blank header.
	if ( is_string( $out ) && false !== strpos( $out, 'elementor-element' ) ) {
		$html = $out;
	}
	return $html;
}

/**
 * Shared header context: Elementor widgets publish menu / CTA / logo here so the mobile menu
 * (rendered outside the transformed header) uses the same data source.
 *
 * @param array|null $data Pass an array to merge values in; omit to read.
 * @return array
 */
function atelier_header_context( $data = null ) {
	static $ctx = array();
	if ( is_array( $data ) ) {
		$ctx = array_merge( $ctx, $data );
	}
	return $ctx;
}

/**
 * Resolve a menu setting into top-level items.
 *
 * @param string|int $setting 'location:primary' | menu term ID | '' (default: primary location, then static defaults).
 * @return array[] label, url, current, target
 */
function atelier_resolve_menu_items( $setting = '' ) {
	$menu_id = 0;

	if ( is_string( $setting ) && 0 === strpos( $setting, 'location:' ) ) {
		$locations = get_nav_menu_locations();
		$loc       = substr( $setting, 9 );
		$menu_id   = ! empty( $locations[ $loc ] ) ? (int) $locations[ $loc ] : 0;
	} elseif ( (int) $setting > 0 ) {
		$menu_id = (int) $setting;
	}

	$items = array();

	if ( $menu_id ) {
		$raw = wp_get_nav_menu_items( $menu_id );
		if ( $raw ) {
			global $wp;
			$request = isset( $wp->request ) ? $wp->request : '';
			$current = is_front_page() ? trailingslashit( home_url( '/' ) ) : trailingslashit( home_url( '/' . $request ) );
			$qid     = (int) get_queried_object_id();

			foreach ( $raw as $m ) {
				if ( (int) $m->menu_item_parent ) {
					continue; // Top level only (matches the original design).
				}
				$is_current = false;
				if ( 'post_type' === $m->type && $qid && (int) $m->object_id === $qid ) {
					$is_current = true;
				} elseif ( trailingslashit( $m->url ) === $current ) {
					$is_current = true;
				}
				$items[] = array(
					'label'   => $m->title,
					'url'     => $m->url,
					'current' => $is_current,
					'target'  => $m->target,
				);
			}
		}
	}

	if ( ! $items ) {
		$items = atelier_nav_items(); // Existing helper: primary location or static defaults.
	}

	return $items;
}

/**
 * Enqueue Elementor CSS for the header in <head> (avoids a flash of unstyled header) + theme glue CSS.
 */
function atelier_enqueue_elementor_header_assets() {
	$preview = is_singular( 'atelier_header' );

	if ( ! atelier_use_elementor_header() && ! $preview ) {
		return;
	}

	wp_enqueue_style(
		'atelier-elementor-header',
		get_template_directory_uri() . '/assets/css/elementor-header.css',
		array( 'atelier-responsive' ),
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

	if ( atelier_use_elementor_header() && class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
		$css = new \Elementor\Core\Files\CSS\Post( atelier_get_active_header_id() );
		$css->enqueue();
	}
}
add_action( 'wp_enqueue_scripts', 'atelier_enqueue_elementor_header_assets', 20 );
