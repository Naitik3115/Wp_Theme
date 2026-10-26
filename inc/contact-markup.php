<?php
/**
 * Shared markup for Contact page sections (static template parts + Elementor widgets).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

/** Small line icons for contact details. */
function atelier_contact_icon( $type ) {
	$icons = array(
		'email'    => '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="1"/><path d="M3 7l9 7 9-7"/></svg>',
		'phone'    => '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M6.5 3.5l3 1.2a1 1 0 01.6.9v2.6a1 1 0 01-.3.7l-1.5 1.5a13 13 0 006.2 6.2l1.5-1.5a1 1 0 01.7-.3h2.6a1 1 0 01.9.6l1.2 3a1 1 0 01-.5 1.2A16 16 0 015.3 4a1 1 0 011.2-.5z"/></svg>',
		'location' => '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 21s7-5.2 7-11a7 7 0 10-14 0c0 5.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>',
	);
	return isset( $icons[ $type ] ) ? $icons[ $type ] : '';
}

/**
 * Contact page hero + Get In Touch + form.
 *
 * @param array $d title, intro_title, intro_text, email, phone, location, social[], button, fields[], form_source, form_shortcode, notice_html, uid.
 */
function atelier_contact_form_section( $d ) {
	$d = wp_parse_args(
		$d,
		array(
			'title'          => '',
			'intro_title'    => '',
			'intro_text'     => '',
			'email'          => '',
			'phone'          => '',
			'location'       => '',
			'social'         => array(),
			'button'         => __( 'Send message', 'atelier-b2b' ),
			'fields'         => array(),
			'form_source'    => 'built_in',
			'form_shortcode' => '',
			'notice_html'    => '',
			'uid'            => 'contact',
		)
	);

	$title_id = 'contact-page-title';
	$uid      = sanitize_html_class( $d['uid'] );
	?>
	<section class="contact-page" id="contact-form"<?php echo $d['title'] ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ' aria-label="' . esc_attr__( 'Contact', 'atelier-b2b' ) . '"'; ?>>
		<div class="container contact-page__inner">
			<header class="contact-page__hero" data-fade>
				<?php if ( $d['title'] ) : ?>
					<h1 class="display contact-page__title" id="<?php echo esc_attr( $title_id ); ?>" data-lines aria-label="<?php echo esc_attr( atelier_plain( $d['title'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $d['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></h1>
				<?php endif; ?>

				<?php if ( ! empty( $d['social'] ) ) : ?>
					<ul class="contact-page__social">
						<?php foreach ( $d['social'] as $item ) :
							$item = wp_parse_args( $item, array( 'label' => '', 'url' => '#' ) );
							if ( ! $item['label'] ) {
								continue;
							}
							?>
							<li><a class="contact-page__social-link" href="<?php echo esc_url( $item['url'] ); ?>"<?php echo ( 0 === strpos( $item['url'], 'http' ) ) ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $item['label'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</header>

			<div class="contact-page__divider" aria-hidden="true"></div>

			<div class="contact-page__grid">
				<div class="contact-page__intro" data-fade>
					<?php if ( $d['intro_title'] ) : ?>
						<h2 class="contact-page__heading"><?php echo esc_html( $d['intro_title'] ); ?></h2>
					<?php endif; ?>
					<?php if ( $d['intro_text'] ) : ?>
						<p class="contact-page__lede"><?php echo esc_html( $d['intro_text'] ); ?></p>
					<?php endif; ?>

					<ul class="contact-page__details">
						<?php if ( $d['email'] ) : ?>
							<li>
								<span class="contact-page__icon"><?php echo atelier_contact_icon( 'email' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<a href="mailto:<?php echo esc_attr( $d['email'] ); ?>"><?php echo esc_html( $d['email'] ); ?></a>
							</li>
						<?php endif; ?>
						<?php if ( $d['phone'] ) : ?>
							<li>
								<span class="contact-page__icon"><?php echo atelier_contact_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $d['phone'] ) ); ?>"><?php echo esc_html( $d['phone'] ); ?></a>
							</li>
						<?php endif; ?>
						<?php if ( $d['location'] ) : ?>
							<li>
								<span class="contact-page__icon"><?php echo atelier_contact_icon( 'location' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span><?php echo esc_html( $d['location'] ); ?></span>
							</li>
						<?php endif; ?>
					</ul>
				</div>

				<div class="contact-page__panel" data-fade data-delay="0.1">
					<h2 class="contact-page__heading"><?php esc_html_e( 'Send us a message', 'atelier-b2b' ); ?></h2>
					<?php echo $d['notice_html']; // phpcs:ignore WordPress.Security.EscapeOutput -- atelier_contact_notice(). ?>

					<?php if ( 'shortcode' === $d['form_source'] && $d['form_shortcode'] ) : ?>
						<div class="contact-page__plugin">
							<?php echo do_shortcode( $d['form_shortcode'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</div>
					<?php else : ?>
						<form class="contact-page__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
							<input type="hidden" name="action" value="atelier_contact">
							<?php wp_nonce_field( 'atelier_contact', 'atelier_contact_nonce' ); ?>
							<p class="contact-page__hp" aria-hidden="true">
								<label for="<?php echo esc_attr( $uid ); ?>_website"><?php esc_html_e( 'Website', 'atelier-b2b' ); ?></label>
								<input type="text" name="atelier_website" id="<?php echo esc_attr( $uid ); ?>_website" value="" tabindex="-1" autocomplete="off">
							</p>

							<div class="contact-page__fields">
								<?php foreach ( $d['fields'] as $field ) :
									$field = wp_parse_args(
										$field,
										array(
											'name'        => '',
											'label'       => '',
											'type'        => 'text',
											'placeholder' => '',
											'required'    => false,
										)
									);
									if ( ! $field['name'] ) {
										continue;
									}
									$id = $uid . '-' . sanitize_html_class( $field['name'] );
									?>
									<div class="contact-page__field">
										<label class="label contact-page__label" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
										<?php if ( 'textarea' === $field['type'] ) : ?>
											<textarea class="contact-page__input contact-page__input--area" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" rows="5" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"<?php echo ! empty( $field['required'] ) ? ' required' : ''; ?>></textarea>
										<?php else : ?>
											<input class="contact-page__input" type="<?php echo esc_attr( $field['type'] ); ?>" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"<?php echo ! empty( $field['required'] ) ? ' required' : ''; ?>>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>

							<button type="submit" class="btn btn--solid btn--lg contact-page__submit">
								<span><?php echo esc_html( $d['button'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</button>
						</form>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Simple Our Offices section: address + map.
 *
 * @param array $d title, address_label, address, map_embed (iframe src or full iframe HTML), map_query.
 */
function atelier_offices_section( $d ) {
	$d = wp_parse_args(
		$d,
		array(
			'title'         => '',
			'address_label' => '',
			'address'       => '',
			'map_embed'     => '',
			'map_query'     => '',
		)
	);

	$title_id = 'offices-title';
	$map_src  = '';

	if ( $d['map_embed'] ) {
		if ( preg_match( '/src=["\']([^"\']+)["\']/', $d['map_embed'], $m ) ) {
			$map_src = $m[1];
		} elseif ( 0 === strpos( $d['map_embed'], 'http' ) ) {
			$map_src = $d['map_embed'];
		}
	}
	if ( ! $map_src && $d['map_query'] ) {
		$map_src = 'https://maps.google.com/maps?q=' . rawurlencode( $d['map_query'] ) . '&z=14&output=embed';
	}
	?>
	<section class="offices" id="offices"<?php echo $d['title'] ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ' aria-label="' . esc_attr__( 'Our offices', 'atelier-b2b' ) . '"'; ?>>
		<div class="container offices__inner">
			<div class="contact-page__divider" aria-hidden="true"></div>

			<div class="offices__grid">
				<div class="offices__info" data-fade>
					<?php if ( $d['title'] ) : ?>
						<h2 class="contact-page__heading" id="<?php echo esc_attr( $title_id ); ?>"><?php echo esc_html( $d['title'] ); ?></h2>
					<?php endif; ?>
					<?php if ( $d['address_label'] || $d['address'] ) : ?>
						<div class="offices__address">
							<?php if ( $d['address_label'] ) : ?>
								<p class="label offices__label"><?php echo esc_html( $d['address_label'] ); ?></p>
							<?php endif; ?>
							<?php if ( $d['address'] ) : ?>
								<p class="offices__text"><?php echo nl2br( esc_html( $d['address'] ) ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( $map_src ) : ?>
					<div class="offices__map" data-fade data-delay="0.1">
						<iframe
							title="<?php echo esc_attr__( 'Office location map', 'atelier-b2b' ); ?>"
							src="<?php echo esc_url( $map_src ); ?>"
							loading="lazy"
							referrerpolicy="no-referrer-when-downgrade"
							allowfullscreen
						></iframe>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}
