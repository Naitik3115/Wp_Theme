<?php
/**
 * Section 02 – Trust marquee (static fallback).
 * Markup lives in atelier_trust_section() so the Elementor widget renders the identical section.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$c = atelier_content( 'trust' );

atelier_trust_section(
	array(
		'label'  => $c['label'],
		'items'  => $c['items'],
		'speed'  => $c['speed'],
		'repeat' => 2,
	)
);
