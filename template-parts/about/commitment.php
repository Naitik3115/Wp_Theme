<?php
/**
 * About 04 – Our Commitment (PHP fallback).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$c = atelier_content( 'about' );
$c = isset( $c['commitment'] ) ? $c['commitment'] : array();

atelier_about_commitment_section(
	array(
		'label'     => $c['label'],
		'title'     => $c['title'],
		'text'      => $c['text'],
		'points'    => isset( $c['points'] ) ? $c['points'] : array(),
		'image_key' => $c['image'],
	)
);
