<?php
/**
 * Our Offices - address + map (PHP fallback).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$c      = atelier_content( 'offices' );
$footer = atelier_content( 'footer' );

$address = ! empty( $c['address'] ) ? $c['address'] : ( isset( $footer['location'] ) ? $footer['location'] : '' );
$query   = ! empty( $c['map_query'] ) ? $c['map_query'] : $address;

atelier_offices_section(
	array(
		'title'         => $c['title'],
		'address_label' => $c['address_label'],
		'address'       => $address,
		'map_query'     => $query,
	)
);
