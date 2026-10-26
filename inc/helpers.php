<?php
/**
 * Template helpers.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

/** Inline arrow icon (diagonal). */
function atelier_arrow( $class = 'btn__arrow' ) {
	return '<span class="' . esc_attr( $class ) . '" aria-hidden="true"><svg viewBox="0 0 16 16" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="square"><path d="M4 12L12 4M5.5 4H12v6.5"/></svg></span>';
}

/** Small star separator for marquees. */
function atelier_star() {
	return '<span class="sep" aria-hidden="true"><svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M12 2v20M3.3 7l17.4 10M3.3 17L20.7 7"/></svg></span>';
}

/** Split text on newlines into masked lines for reveal animations. */
function atelier_lines( $text ) {
	$out = '';
	foreach ( preg_split( '/\R/', $text ) as $i => $line ) {
		$out .= '<span class="line"><span class="line__inner" style="--i:' . (int) $i . '">' . esc_html( $line ) . '</span></span>';
	}
	return $out;
}

/** Plain text for aria-label / screen readers (newlines -> spaces). */
function atelier_plain( $text ) {
	return trim( preg_replace( '/\s+/', ' ', $text ) );
}

/**
 * Render a seamless marquee: two identical groups, each translated -100%.
 *
 * @param array $items Strings.
 * @param array $args  class, speed (seconds), repeat (copies of items per group),
 *                     separator (null = default star icon, '' / false = none, string = custom text).
 */
function atelier_marquee( $items, $args = array() ) {
	$args = wp_parse_args( $args, array( 'class' => '', 'speed' => 40, 'repeat' => 3, 'separator' => null ) );

	if ( null === $args['separator'] ) {
		$sep = atelier_star();
	} elseif ( '' === $args['separator'] || false === $args['separator'] ) {
		$sep = '';
	} else {
		$sep = '<span class="sep sep--text" aria-hidden="true">' . esc_html( $args['separator'] ) . '</span>';
	}

	$group = '';
	for ( $r = 0; $r < (int) $args['repeat']; $r++ ) {
		foreach ( $items as $item ) {
			$group .= '<span class="marquee__item"><span class="marquee__text">' . esc_html( $item ) . '</span>' . $sep . '</span>';
		}
	}

	printf(
		'<div class="marquee %1$s" style="--marquee-speed:%2$ds"><div class="marquee__group">%3$s</div><div class="marquee__group" aria-hidden="true">%3$s</div></div>',
		esc_attr( $args['class'] ),
		max( 1, (int) $args['speed'] ),
		$group // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts.
	);
}

/**
 * Navigation items: assigned "Primary Menu" if present, otherwise the static defaults.
 *
 * @return array[] { label, url, current, target }
 */
function atelier_nav_items() {
	$items     = array();
	$locations = get_nav_menu_locations();

	if ( ! empty( $locations['primary'] ) ) {
		$menu = wp_get_nav_menu_items( $locations['primary'] );
		if ( $menu ) {
			foreach ( $menu as $m ) {
				if ( (int) $m->menu_item_parent ) {
					continue;
				}
				$items[] = array(
					'label'   => $m->title,
					'url'     => $m->url,
					'current' => ! empty( $m->current ),
					'target'  => $m->target,
				);
			}
		}
	}

	if ( ! $items ) {
		$current_url = trailingslashit( home_url( add_query_arg( array() ) ) );
		foreach ( atelier_content( 'nav' ) as $n ) {
			$n['current'] = ( trailingslashit( $n['url'] ) === $current_url ) || ( is_front_page() && trailingslashit( home_url() ) === trailingslashit( $n['url'] ) );
			$items[]      = $n;
		}
	}

	return $items;
}

function atelier_logo( $class = 'brand__logo' ) {
	if ( has_custom_logo() ) {
		$id  = get_theme_mod( 'custom_logo' );
		echo wp_get_attachment_image( $id, 'full', false, array( 'class' => $class, 'alt' => get_bloginfo( 'name' ), 'loading' => 'eager' ) );
		return;
	}
	$a = atelier_assets();
	printf(
		'<img class="%1$s" src="%2$s" width="%3$d" height="%4$d" alt="%5$s">',
		esc_attr( $class ),
		esc_url( atelier_asset_url( 'logo' ) ),
		(int) $a['logo']['w'],
		(int) $a['logo']['h'],
		esc_attr( get_bloginfo( 'name' ) )
	);
}

/**
 * True when a page/post is built with Elementor (edit mode = builder).
 *
 * @param int $post_id Optional post ID.
 */
function atelier_page_uses_elementor( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : (int) get_the_ID();
	if ( ! $post_id ) {
		return false;
	}
	return 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true );
}
