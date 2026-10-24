<?php
/**
 * Section 03 – Featured categories (draggable slider).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
$c     = atelier_content( 'categories' );
$total = count( $c['items'] );
?>
<section class="categories section" id="categories" aria-labelledby="categories-title">
	<div class="container section-head">
		<p class="label section-head__label"><?php echo esc_html( $c['label'] ); ?></p>
		<h2 class="display section-title section-head__title" id="categories-title" data-lines aria-label="<?php echo esc_attr( atelier_plain( $c['title'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $c['title'] ); // phpcs:ignore ?></span></h2>
		<div class="section-head__aside">
			<p class="lede"><?php echo esc_html( $c['text'] ); ?></p>
			<div class="slider-controls" data-slider-controls>
				<button class="slider-btn" type="button" data-slider-prev aria-label="<?php esc_attr_e( 'Previous category', 'atelier-b2b' ); ?>">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
				</button>
				<button class="slider-btn" type="button" data-slider-next aria-label="<?php esc_attr_e( 'Next category', 'atelier-b2b' ); ?>">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
				</button>
			</div>
		</div>
	</div>

	<div class="slider" data-slider role="region" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Product categories', 'atelier-b2b' ); ?>">
		<div class="slider__viewport" data-slider-viewport>
			<ul class="slider__track">
				<?php foreach ( $c['items'] as $i => $item ) : ?>
					<li class="slider__slide" aria-roledescription="slide" aria-label="<?php echo esc_attr( ( $i + 1 ) . ' / ' . $total ); ?>">
						<a class="cat-card" href="<?php echo esc_url( $item['url'] ); ?>" draggable="false">
							<span class="cat-card__media">
								<?php atelier_image( $item['image'], array( 'class' => 'cat-card__img', 'sizes' => '(min-width:1024px) 42vw, (min-width:768px) 60vw, 80vw' ) ); ?>
							</span>
							<span class="cat-card__shade" aria-hidden="true"></span>
							<span class="cat-card__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
							<span class="cat-card__arrow"><?php echo atelier_arrow( 'cat-card__arrow-icon' ); // phpcs:ignore ?></span>
							<span class="cat-card__title"><?php echo esc_html( $item['title'] ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
				<li class="slider__end" aria-hidden="true"></li>
			</ul>
		</div>
		<div class="container">
			<div class="slider__progress" aria-hidden="true"><span data-slider-progress></span></div>
		</div>
		<div class="drag-cursor" data-drag-cursor aria-hidden="true"><span>Drag</span></div>
	</div>
</section>
