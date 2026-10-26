<?php
/**
 * About 03 – Our Philosophy (PHP fallback).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$c       = atelier_content( 'about' );
$c       = isset( $c['philosophy'] ) ? $c['philosophy'] : array();
$pillars = array();

foreach ( isset( $c['pillars'] ) ? $c['pillars'] : array() as $p ) {
	$pillars[] = array(
		'index'     => $p['index'],
		'title'     => $p['title'],
		'text'      => $p['text'],
		'image_key' => $p['image'],
	);
}

atelier_about_philosophy_section(
	array(
		'label'   => $c['label'],
		'title'   => $c['title'],
		'text'    => $c['text'],
		'pillars' => $pillars,
	)
);
