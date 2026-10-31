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
		'eyebrow'          => $c['eyebrow'],
		'title'            => $c['title'],
		'sub'              => $c['sub'],
		'scroll'           => $c['scroll'],
		'cta_1'            => isset( $c['cta_1'] ) ? $c['cta_1'] : array(),
		'cta_2'            => isset( $c['cta_2'] ) ? $c['cta_2'] : array(),
		'image_key'        => ! empty( $c['image'] ) ? $c['image'] : 'hero_poster',
		'image_mobile_key' => ! empty( $c['image_mobile'] ) ? $c['image_mobile'] : ( atelier_asset_exists( 'hero_poster_mobile' ) ? 'hero_poster_mobile' : 'category_women' ),
	)
);
