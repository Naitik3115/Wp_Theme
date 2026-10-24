<?php
/**
 * Section 01 – Fullscreen cinematic hero (static fallback).
 * Markup lives in atelier_hero_section() so the Elementor widget renders the identical section.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$c      = atelier_content( 'hero' );
$video  = atelier_asset_url( 'hero_video' );
$poster = atelier_asset_url( 'hero_poster' );

ob_start();
atelier_image( 'hero_poster', array( 'class' => 'hero__img', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '100vw' ) );
$bg = ob_get_clean();

atelier_hero_section(
	array(
		'eyebrow'      => $c['eyebrow'],
		'title'        => $c['title'],
		'sub'          => $c['sub'],
		'cta_1'        => $c['cta_1'],
		'cta_2'        => $c['cta_2'],
		'bg_html'      => $bg,
		'video_url'    => $video,
		'poster_url'   => $poster,
		'scroll_label' => $c['scroll'],
	)
);
