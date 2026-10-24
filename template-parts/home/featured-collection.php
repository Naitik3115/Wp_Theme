<?php
/**
 * Section 04 – Editorial collection feature.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
$c = atelier_content( 'feature' );
?>
<section class="feature" id="feature" aria-labelledby="feature-title">
	<div class="feature__grid">
		<figure class="feature__figure" data-clip>
			<div class="parallax-wrap">
				<div class="parallax-img" data-parallax>
					<?php atelier_image( $c['image'], array( 'class' => 'feature__img', 'sizes' => '(min-width:1024px) 57vw, 100vw' ) ); ?>
				</div>
			</div>
		</figure>

		<div class="feature__body">
			<p class="label feature__eyebrow"><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 class="display feature__title" id="feature-title" data-lines aria-label="<?php echo esc_attr( atelier_plain( $c['title'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $c['title'] ); // phpcs:ignore ?></span></h2>

			<div class="rule" aria-hidden="true">
				<span class="rule__num"><?php echo esc_html( $c['index'] ); ?></span>
				<i class="rule__line"></i>
				<span class="rule__tag"><?php echo esc_html( $c['tag'] ); ?></span>
			</div>

			<p class="feature__text" data-fade><?php echo esc_html( $c['text'] ); ?></p>
			<div data-fade data-delay="0.15">
				<a class="btn btn--outline-light" href="<?php echo esc_url( $c['cta']['url'] ); ?>"><span><?php echo esc_html( $c['cta']['label'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore ?></a>
			</div>
		</div>
	</div>
</section>
