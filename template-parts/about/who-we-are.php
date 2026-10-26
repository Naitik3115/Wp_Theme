<?php
/**
 * About 02 – Who We Are (PHP fallback).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$c = atelier_content( 'about' );
$c = isset( $c['who'] ) ? $c['who'] : array();

atelier_about_who_section(
	array(
		'label'       => $c['label'],
		'title'       => $c['title'],
		'lede'        => $c['lede'],
		'text'        => $c['text'],
		'aside'       => $c['aside'],
		'image_1_key' => $c['image_1'],
		'image_2_key' => $c['image_2'],
	)
);
