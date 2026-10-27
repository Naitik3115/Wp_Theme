<?php
/**
 * Product catalog: category filters + non-linking product cards (dual-image hover).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$c          = atelier_content( 'collections' );
$categories = atelier_content( 'categories' );
$filters    = isset( $categories['items'] ) ? $categories['items'] : array();
$items      = isset( $c['items'] ) ? $c['items'] : array();
$active     = isset( $_GET['category'] ) ? sanitize_title( wp_unslash( $_GET['category'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$valid      = array();
foreach ( $filters as $f ) {
	if ( ! empty( $f['slug'] ) ) {
		$valid[] = sanitize_title( $f['slug'] );
	}
}
if ( $active && ! in_array( $active, $valid, true ) ) {
	$active = '';
}

$visible = 0;
foreach ( $items as $item ) {
	$slug = isset( $item['category'] ) ? sanitize_title( $item['category'] ) : '';
	if ( ! $active || $slug === $active ) {
		++$visible;
	}
}
$count_fmt = ! empty( $c['count_label'] ) ? $c['count_label'] : '%d products';
?>
<section
	class="collections-catalog section"
	id="collections-catalog"
	data-collections-filter
	data-active="<?php echo esc_attr( $active ); ?>"
	data-count-label="<?php echo esc_attr( $count_fmt ); ?>"
	aria-labelledby="collections-filters-label"
>
	<div class="container">
		<div class="collections-toolbar">
			<p class="label collections-toolbar__label" id="collections-filters-label"><?php echo esc_html( $c['filters_label'] ); ?></p>
			<div class="collections-filters" role="toolbar" aria-label="<?php echo esc_attr( $c['filters_label'] ); ?>">
				<button
					type="button"
					class="collections-filter<?php echo '' === $active ? ' is-active' : ''; ?>"
					data-filter=""
					aria-pressed="<?php echo '' === $active ? 'true' : 'false'; ?>"
				><?php echo esc_html( $c['all_label'] ); ?></button>
				<?php foreach ( $filters as $filter ) :
					$slug = isset( $filter['slug'] ) ? sanitize_title( $filter['slug'] ) : sanitize_title( $filter['title'] );
					$is   = ( $active === $slug );
					?>
					<button
						type="button"
						class="collections-filter<?php echo $is ? ' is-active' : ''; ?>"
						data-filter="<?php echo esc_attr( $slug ); ?>"
						aria-pressed="<?php echo $is ? 'true' : 'false'; ?>"
					><?php echo esc_html( $filter['title'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<p class="collections-toolbar__count" data-collections-count aria-live="polite">
				<?php echo esc_html( sprintf( $count_fmt, $visible ) ); ?>
			</p>
		</div>

		<div class="collections-grid" data-collections-grid>
			<?php
			foreach ( $items as $i => $item ) {
				$slug = isset( $item['category'] ) ? sanitize_title( $item['category'] ) : '';
				$item['index'] = str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT );
				atelier_collections_product_card( $item, (bool) ( $active && $slug !== $active ) );
			}
			?>
		</div>

		<p class="collections-empty<?php echo ( 0 === $visible ) ? '' : ' is-hidden'; ?>" data-collections-empty <?php echo ( 0 === $visible ) ? '' : 'hidden'; ?>>
			<?php echo esc_html( $c['empty'] ); ?>
		</p>
	</div>
</section>
