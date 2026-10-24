<?php
/**
 * Section 05 – Product showcase (editorial, not eCommerce).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
$c = atelier_content( 'showcase' );
?>
<section class="showcase section" id="showcase" aria-labelledby="showcase-title">
	<div class="container section-head">
		<p class="label section-head__label"><?php echo esc_html( $c['label'] ); ?></p>
		<h2 class="display section-title section-head__title" id="showcase-title" data-lines aria-label="<?php echo esc_attr( atelier_plain( $c['title'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $c['title'] ); // phpcs:ignore ?></span></h2>
		<div class="section-head__aside">
			<p class="lede"><?php echo esc_html( $c['text'] ); ?></p>
		</div>
	</div>

	<div class="container">
		<div class="showcase__grid">
			<?php foreach ( $c['items'] as $i => $item ) : ?>
				<article class="work work--<?php echo (int) ( $i + 1 ); ?>">
					<a class="work__link" href="<?php echo esc_url( $item['url'] ); ?>">
						<span class="work__fig" data-clip>
							<?php atelier_image( $item['image'], array( 'class' => 'work__img', 'sizes' => '(min-width:1024px) 45vw, 90vw' ) ); ?>
							<span class="work__tint" aria-hidden="true"></span>
						</span>
						<span class="work__cap">
							<span class="work__index" aria-hidden="true"><?php echo esc_html( $item['index'] ); ?></span>
							<span class="work__meta">
								<span class="work__title"><?php echo esc_html( $item['title'] ); ?></span>
								<span class="work__cat"><?php echo esc_html( $item['category'] ); ?></span>
							</span>
							<?php echo atelier_arrow( 'work__arrow' ); // phpcs:ignore ?>
						</span>
					</a>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="showcase__cta">
			<a class="btn btn--outline-dark" href="<?php echo esc_url( $c['cta']['url'] ); ?>"><span><?php echo esc_html( $c['cta']['label'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore ?></a>
		</div>
	</div>
</section>
