<?php
/**
 * About 05 – Closing CTA (PHP fallback).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$c = atelier_content( 'about' );
$c = isset( $c['cta'] ) ? $c['cta'] : array();

atelier_about_cta_section(
	array(
		'label' => $c['label'],
		'title' => $c['title'],
		'text'  => $c['text'],
		'cta_1' => $c['cta_1'],
		'cta_2' => $c['cta_2'],
	)
);
