<?php
/**
 * Collections page hero (editorial, asymmetric). $args: eyebrow, title, text, scroll, image_a_html, image_b_html, anchor.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$d = wp_parse_args( $args, array(
	'eyebrow'      => '',
	'title'        => '',
	'text'         => '',
	'scroll'       => '',
	'anchor'       => '',
	'image_a_html' => '',
	'image_b_html' => '',
) );
?>
<section class="chero<?php echo ( $d['image_a_html'] || $d['image_b_html'] ) ? '' : ' chero--no-media'; ?>" aria-labelledby="chero-title">
	<div class="container chero__grid">
		<div class="chero__text">
			<?php if ( $d['eyebrow'] ) : ?><p class="label chero__eyebrow"><?php echo esc_html( $d['eyebrow'] ); ?></p><?php endif; ?>
			<h1 class="display chero__title" id="chero-title" data-lines aria-label="<?php echo esc_attr( atelier_plain( $d['title'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $d['title'] ); // phpcs:ignore ?></span></h1>
		</div>

		<?php if ( $d['image_a_html'] ) : ?>
		<figure class="chero__fig chero__fig--a" data-clip>
			<div class="parallax-wrap"><div class="parallax-img" data-parallax><?php echo $d['image_a_html']; // phpcs:ignore ?></div></div>
		</figure>
		<?php endif; ?>

		<div class="chero__foot">
			<?php if ( $d['text'] ) : ?><p class="lede chero__desc" data-fade><?php echo esc_html( $d['text'] ); ?></p><?php endif; ?>
			<?php if ( $d['scroll'] ) : ?>
				<a class="chero__scroll" href="<?php echo esc_attr( $d['anchor'] ); ?>"><span class="chero__scroll-line" aria-hidden="true"></span><span class="label"><?php echo esc_html( $d['scroll'] ); ?></span></a>
			<?php endif; ?>
		</div>

		<?php if ( $d['image_b_html'] ) : ?>
		<figure class="chero__fig chero__fig--b" data-clip>
			<div class="parallax-wrap"><div class="parallax-img" data-parallax><?php echo $d['image_b_html']; // phpcs:ignore ?></div></div>
		</figure>
		<?php endif; ?>
	</div>
</section>
