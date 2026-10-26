<?php
/**
 * Contact page main section (PHP fallback).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$c      = atelier_content( 'contact' );
$footer = atelier_content( 'footer' );

atelier_contact_form_section(
	array(
		'title'       => $c['title'],
		'intro_title' => $c['intro_title'],
		'intro_text'  => $c['intro_text'],
		'email'       => isset( $footer['email'] ) ? $footer['email'] : '',
		'phone'       => isset( $footer['phone'] ) ? $footer['phone'] : '',
		'location'    => isset( $footer['location'] ) ? $footer['location'] : '',
		'social'      => isset( $footer['social'] ) ? $footer['social'] : array(),
		'button'      => $c['button'],
		'fields'      => $c['fields'],
		'notice_html' => atelier_contact_notice(),
		'uid'         => 'contact',
	)
);
