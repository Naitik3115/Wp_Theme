<?php
/**
 * Shared Collections / product-catalog markup (static + Elementor).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print a product image from Elementor MEDIA array or theme asset key.
 *
 * @param array|string $source Media array or asset key.
 * @param string       $class  Img class list.
 * @param string       $alt    Alt text.
 */
function atelier_collections_image( $source, $class, $alt = '' ) {
	if ( is_array( $source ) ) {
		if ( ! empty( $source['id'] ) ) {
			echo wp_get_attachment_image(
				absint( $source['id'] ),
				'large',
				false,
				array(
					'class'    => $class,
					'alt'      => $alt,
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
			return;
		}
		if ( ! empty( $source['url'] ) ) {
			printf(
				'<img class="%1$s" src="%2$s" alt="%3$s" loading="lazy" decoding="async">',
				esc_attr( $class ),
				esc_url( $source['url'] ),
				esc_attr( $alt )
			);
			return;
		}
		return;
	}

	$key = is_string( $source ) ? $source : '';
	if ( $key && atelier_asset_url( $key ) ) {
		atelier_image(
			$key,
			array(
				'class' => $class,
				'alt'   => $alt,
				'sizes' => '(min-width:1100px) 30vw, (min-width:700px) 45vw, 90vw',
			)
		);
	}
}

/**
 * Render one non-linking product card with dual-image hover swap.
 *
 * @param array $item Keys: title, category, category_label, image, image_hover, index.
 * @param bool  $hidden Whether the card starts filtered out.
 */
function atelier_collections_product_card( $item, $hidden = false ) {
	$slug  = isset( $item['category'] ) ? sanitize_title( $item['category'] ) : '';
	$index = isset( $item['index'] ) ? $item['index'] : '01';
	$title = isset( $item['title'] ) ? $item['title'] : '';
	$label = isset( $item['category_label'] ) ? $item['category_label'] : '';
	$img_a = isset( $item['image'] ) ? $item['image'] : '';
	$img_b = ! empty( $item['image_hover'] ) ? $item['image_hover'] : $img_a;
	?>
	<article
		class="collection-card<?php echo $hidden ? ' is-hidden' : ''; ?>"
		data-category="<?php echo esc_attr( $slug ); ?>"
		<?php echo $hidden ? 'hidden' : ''; ?>
	>
		<div class="collection-card__media">
			<div class="collection-card__fig">
				<span class="collection-card__stack" aria-hidden="true">
					<?php atelier_collections_image( $img_a, 'collection-card__img collection-card__img--a', $title ); ?>
					<?php atelier_collections_image( $img_b, 'collection-card__img collection-card__img--b', $title ); ?>
				</span>
			</div>
			<div class="collection-card__cap">
				<span class="collection-card__index"><?php echo esc_html( $index ); ?></span>
				<span class="collection-card__meta">
					<span class="collection-card__title"><?php echo esc_html( $title ); ?></span>
					<?php if ( $label ) : ?>
						<span class="collection-card__cat"><?php echo esc_html( $label ); ?></span>
					<?php endif; ?>
				</span>
			</div>
		</div>
	</article>
	<?php
}
