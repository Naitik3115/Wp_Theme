<?php
/**
 * Section 06 – B2B partnership CTA.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
$c = atelier_content( 'cta' );
?>
<section class="partner" id="partner" aria-labelledby="partner-title">
	<div class="container partner__inner">
		<p class="label partner__label"><?php echo esc_html( $c['label'] ); ?></p>
		<h2 class="display partner__title" id="partner-title" data-lines aria-label="<?php echo esc_attr( atelier_plain( $c['title'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $c['title'] ); // phpcs:ignore ?></span></h2>
		<div class="partner__foot">
			<p class="lede partner__text" data-fade><?php echo esc_html( $c['text'] ); ?></p>
			<div data-fade data-delay="0.12">
				<a class="btn btn--solid btn--lg" href="<?php echo esc_url( $c['cta']['url'] ); ?>"><span><?php echo esc_html( $c['cta']['label'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore ?></a>
			</div>
		</div>
	</div>
</section>
