<?php
/**
 * Contact form submission handler (theme built-in form).
 *
 * Prefer Elementor Form / CF7 / WPForms via the widget shortcode control when available;
 * this handler powers the styled fallback markup only.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_contact_redirect( $status ) {
	$ref = wp_get_referer();
	$url = $ref ? $ref : home_url( '/contact/' );
	$url = remove_query_arg( array( 'contact', 'contact_err' ), $url );
	$url = add_query_arg( 'contact', sanitize_key( $status ), $url );
	wp_safe_redirect( $url . '#contact-form' );
	exit;
}

function atelier_handle_contact_submit() {
	if ( ! isset( $_POST['atelier_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['atelier_contact_nonce'] ) ), 'atelier_contact' ) ) {
		atelier_contact_redirect( 'error' );
	}

	// Honeypot.
	if ( ! empty( $_POST['atelier_website'] ) ) {
		atelier_contact_redirect( 'sent' );
	}

	$name    = isset( $_POST['contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) : '';
	$email   = isset( $_POST['contact_email'] ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
	$subject = isset( $_POST['contact_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_subject'] ) ) : '';
	$message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';

	if ( '' === $name || ! is_email( $email ) || '' === $message ) {
		atelier_contact_redirect( 'invalid' );
	}

	$to = apply_filters( 'atelier_contact_recipient', get_option( 'admin_email' ) );
	/* translators: %s: sender name or custom subject */
	$mail_subject = $subject
		? sprintf( __( '[Atelier] %s', 'atelier-b2b' ), $subject )
		: sprintf( __( '[Atelier] Contact from %s', 'atelier-b2b' ), $name );

	$body  = __( 'Name:', 'atelier-b2b' ) . ' ' . $name . "\n";
	$body .= __( 'Email:', 'atelier-b2b' ) . ' ' . $email . "\n";
	if ( $subject ) {
		$body .= __( 'Subject:', 'atelier-b2b' ) . ' ' . $subject . "\n";
	}
	$body .= "\n" . __( 'Message:', 'atelier-b2b' ) . "\n" . $message . "\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	$sent = wp_mail( $to, $mail_subject, $body, $headers );
	atelier_contact_redirect( $sent ? 'sent' : 'error' );
}
add_action( 'admin_post_atelier_contact', 'atelier_handle_contact_submit' );
add_action( 'admin_post_nopriv_atelier_contact', 'atelier_handle_contact_submit' );

/** Flash notice after redirect (?contact=sent|invalid|error). */
function atelier_contact_notice() {
	if ( empty( $_GET['contact'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return '';
	}
	$status = sanitize_key( wp_unslash( $_GET['contact'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
	$map    = array(
		'sent'    => array( 'ok', __( 'Thank you - your message has been sent.', 'atelier-b2b' ) ),
		'invalid' => array( 'err', __( 'Please fill in your name, a valid email, and a message.', 'atelier-b2b' ) ),
		'error'   => array( 'err', __( 'Something went wrong. Please try again or email us directly.', 'atelier-b2b' ) ),
	);
	if ( ! isset( $map[ $status ] ) ) {
		return '';
	}
	return sprintf(
		'<div class="contact-page__notice contact-page__notice--%1$s" role="status">%2$s</div>',
		esc_attr( $map[ $status ][0] ),
		esc_html( $map[ $status ][1] )
	);
}
