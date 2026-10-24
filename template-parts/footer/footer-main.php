<?php
/**
 * Section 07 – Global footer.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
$c       = atelier_content( 'footer' );
$privacy = function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ? get_privacy_policy_url() : atelier_url( 'privacy-policy' );
$c['col_2'][2]['url'] = $privacy;
?>
<footer class="footer" id="colophon">
	<div class="container footer__top">
		<p class="footer__statement display" aria-label="<?php echo esc_attr( atelier_plain( $c['statement'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $c['statement'] ); // phpcs:ignore ?></span></p>
		<a class="btn btn--light btn--lg" href="<?php echo esc_url( $c['cta']['url'] ); ?>"><span><?php echo esc_html( $c['cta']['label'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore ?></a>
	</div>

	<div class="container footer__mid">
		<div class="footer__col footer__col--brand">
			<a class="brand brand--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><?php atelier_logo(); ?></a>
			<p class="footer__intro"><?php echo esc_html( $c['intro'] ); ?></p>
		</div>

		<nav class="footer__col" aria-label="<?php esc_attr_e( 'Footer', 'atelier-b2b' ); ?>">
			<ul class="footer__list">
				<?php foreach ( $c['col_1'] as $l ) : ?>
					<li><a href="<?php echo esc_url( $l['url'] ); ?>"><?php echo esc_html( $l['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</nav>

		<nav class="footer__col" aria-label="<?php esc_attr_e( 'Footer secondary', 'atelier-b2b' ); ?>">
			<ul class="footer__list">
				<?php foreach ( $c['col_2'] as $l ) : ?>
					<li><a href="<?php echo esc_url( $l['url'] ); ?>"><?php echo esc_html( $l['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</nav>

		<div class="footer__col footer__col--contact">
			<ul class="footer__list">
				<li><a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo esc_html( $c['email'] ); ?></a></li>
				<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $c['phone'] ) ); ?>"><?php echo esc_html( $c['phone'] ); ?></a></li>
				<li><span><?php echo esc_html( $c['location'] ); ?></span></li>
			</ul>
			<ul class="footer__social" aria-label="<?php esc_attr_e( 'Social media', 'atelier-b2b' ); ?>">
				<?php foreach ( $c['social'] as $s ) : ?>
					<li><a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $s['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

	<div class="container footer__bottom">
		<p class="footer__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'atelier-b2b' ); ?></p>
		<ul class="footer__legal">
			<li><a href="<?php echo esc_url( $privacy ); ?>"><?php esc_html_e( 'Privacy Policy', 'atelier-b2b' ); ?></a></li>
			<li><a href="<?php echo esc_url( $c['terms_url'] ); ?>"><?php esc_html_e( 'Terms & Conditions', 'atelier-b2b' ); ?></a></li>
		</ul>
		<a class="footer__top-link" href="#top" data-back-to-top><span><?php esc_html_e( 'Back to top', 'atelier-b2b' ); ?></span><svg viewBox="0 0 16 16" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M8 14V2M3 7l5-5 5 5"/></svg></a>
	</div>
</footer>
