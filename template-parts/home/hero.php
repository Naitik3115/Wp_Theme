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
$mobile = atelier_asset_exists( 'hero_poster_mobile' ) ? 'hero_poster_mobile' : 'category_women';

atelier_hero_section(
	array(
		'eyebrow'      => $c['eyebrow'],
		'title'        => $c['title'],
		'sub'          => $c['sub'],
		'cta_1'        => $c['cta_1'],
		'cta_2'        => $c['cta_2'],
		'bg_html'      => atelier_hero_responsive_bg_html( 'hero_poster', $mobile ),
		'video_url'    => $video,
		'poster_url'   => $poster,
		'scroll_label' => $c['scroll'],
	)
);
