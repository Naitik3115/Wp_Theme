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
		'image_key' => $c['image'],
	)
);
