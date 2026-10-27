<?php
/**
 * Collections page closing CTA. $args: label, title, text, cta{label,url,attrs}.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$d = $args;
?>
<section class="ccta" aria-labelledby="ccta-title">
	<div class="container ccta__inner">
		<?php if ( $d['label'] ) : ?><p class="label ccta__label"><?php echo esc_html( $d['label'] ); ?></p><?php endif; ?>
		<h2 class="display ccta__title" id="ccta-title" data-lines aria-label="<?php echo esc_attr( atelier_plain( $d['title'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $d['title'] ); // phpcs:ignore ?></span></h2>
		<div class="ccta__foot">
			<?php if ( $d['text'] ) : ?><p class="lede ccta__text" data-fade><?php echo esc_html( $d['text'] ); ?></p><?php endif; ?>
			<?php if ( ! empty( $d['cta']['label'] ) && ! empty( $d['cta']['url'] ) ) : ?>
			<div data-fade data-delay="0.12"><a class="btn btn--solid btn--lg" href="<?php echo esc_url( $d['cta']['url'] ); ?>"<?php echo isset( $d['cta']['attrs'] ) ? $d['cta']['attrs'] : ''; // phpcs:ignore ?>><span><?php echo esc_html( $d['cta']['label'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore ?></a></div>
			<?php endif; ?>
		</div>
	</div>
</section>
