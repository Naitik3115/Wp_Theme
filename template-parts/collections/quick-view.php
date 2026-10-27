<?php
/**
 * Quick View side drawer (printed once in the footer; filled lazily from the REST endpoint).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="qv" id="atelier-qv" role="dialog" aria-modal="true" aria-labelledby="qv-title" aria-hidden="true" hidden data-qv>
	<div class="qv__backdrop" data-qv-close></div>
	<div class="qv__panel" tabindex="-1">
		<button class="qv__close" type="button" data-qv-close aria-label="<?php esc_attr_e( 'Close quick view', 'atelier-b2b' ); ?>"><span aria-hidden="true"></span></button>

		<div class="qv__state" data-qv-state role="status" aria-live="polite"></div>

		<div class="qv__content" data-qv-content hidden>
			<div class="qv__gallery">
				<div class="qv__stage" data-qv-stage>
					<img class="qv__img is-on" alt="" data-qv-img="0">
					<img class="qv__img" alt="" aria-hidden="true" data-qv-img="1">
					<button class="qv__nav qv__nav--prev" type="button" data-qv-prev aria-label="<?php esc_attr_e( 'Previous image', 'atelier-b2b' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 12H5M11 6l-6 6 6 6"/></svg></button>
					<button class="qv__nav qv__nav--next" type="button" data-qv-next aria-label="<?php esc_attr_e( 'Next image', 'atelier-b2b' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
					<span class="qv__counter label" data-qv-counter aria-live="polite"></span>
				</div>
				<ul class="qv__thumbs" data-qv-thumbs></ul>
			</div>

			<div class="qv__info">
				<p class="label qv__cat" data-qv-cat></p>
				<h2 class="display qv__title" id="qv-title" data-qv-title></h2>
				<dl class="qv__facts" data-qv-facts></dl>
				<p class="qv__desc" data-qv-desc></p>
				<a class="btn btn--solid" href="#" data-qv-cta><span><?php esc_html_e( 'Inquire about this product', 'atelier-b2b' ); ?></span><?php echo atelier_arrow(); // phpcs:ignore ?></a>
			</div>
		</div>
	</div>
</div>
