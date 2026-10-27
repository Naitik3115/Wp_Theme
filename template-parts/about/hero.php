<?php
/**
 * About 01 – Cinematic story hero (PHP fallback).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$c = atelier_content( 'about' );
$c = isset( $c['hero'] ) ? $c['hero'] : array();

atelier_about_hero_section(
	array(
		'eyebrow'   => $c['eyebrow'],
		'title'     => $c['title'],
		'sub'       => $c['sub'],
		'scroll'    => $c['scroll'],
		'cta_1'     => $c['cta_1'],
		'cta_2'     => $c['cta_2'],
	)
);
