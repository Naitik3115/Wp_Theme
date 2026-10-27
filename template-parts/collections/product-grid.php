<?php
/**
 * Collections: heading, filter bar, grid, count, Load More, empty state.
 * $args: s (settings), a (query args), query, filter_terms, total, cards_html.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$s     = $args['s'];
$a     = $args['a'];
$q     = $args['query'];
$found = (int) $q->found_posts;
$pages = (int) $q->max_num_pages;
$shown = (int) $q->post_count;

$js = array(
	'terms'    => $a['terms'],
	'orderby'  => $a['orderby'],
	'per_page' => $a['per_page'],
	'featured' => $a['featured'],
	'quick'    => $a['quick'],
	'ref'      => $a['ref'],
	'season'   => $a['season'],
	'term'     => $a['term'],
	'page'     => 1,
	'pages'    => $pages,
	'found'    => $found,
	'inquiry'  => atelier_collections_inquiry_url( $s['inquiry'] ),
);
?>
<section class="cgrid" id="collection-grid" aria-labelledby="<?php echo $s['title'] ? 'cgrid-title' : 'collection-grid-label'; ?>" data-collection-grid data-settings="<?php echo esc_attr( wp_json_encode( $js ) ); ?>" data-cols="<?php echo (int) $s['cols_d']; ?>">
	<?php if ( $s['label'] || $s['title'] || $s['text'] ) : ?>
		<header class="container cgrid__head">
			<?php if ( $s['label'] ) : ?><p class="label cgrid__label"><?php echo esc_html( $s['label'] ); ?></p><?php endif; ?>
			<?php if ( $s['title'] ) : ?><h2 class="display cgrid__title" id="cgrid-title" data-lines aria-label="<?php echo esc_attr( atelier_plain( $s['title'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $s['title'] ); // phpcs:ignore ?></span></h2><?php endif; ?>
			<?php if ( $s['text'] ) : ?><p class="lede cgrid__text" data-fade><?php echo esc_html( $s['text'] ); ?></p><?php endif; ?>
		</header>
	<?php endif; ?>
	<h2 class="screen-reader-text" id="collection-grid-label"><?php esc_html_e( 'Products', 'atelier-b2b' ); ?></h2>

	<?php
	if ( $s['filters'] ) {
		get_template_part( 'template-parts/collections/category-filters', null, array(
			'terms'     => $args['filter_terms'],
			'total'     => $args['total'],
			'active'    => $a['term'],
			'all_label' => $s['all_label'],
			'sticky'    => $s['sticky'],
		) );
	}
	?>

	<div class="container cgrid__body">
		<?php if ( $s['count'] ) : ?>
			<p class="cgrid__status label" role="status" aria-live="polite" data-status>
				<?php echo esc_html( sprintf( __( 'Showing %1$s of %2$s', 'atelier-b2b' ), $shown, $found ) ); ?>
			</p>
		<?php else : ?>
			<p class="screen-reader-text" role="status" aria-live="polite" data-status></p>
		<?php endif; ?>

		<div class="cgrid__list" data-list>
			<?php echo $args['cards_html']; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped template parts. ?>
		</div>

		<div class="cgrid__empty" data-empty<?php echo $found ? ' hidden' : ''; ?>>
			<p class="display cgrid__empty-title"><?php echo esc_html( $s['empty_title'] ); ?></p>
			<p class="lede"><?php echo esc_html( $s['empty_text'] ); ?></p>
		</div>

		<?php if ( $s['load_more'] ) : ?>
			<div class="cgrid__more">
				<button class="btn btn--outline-dark" type="button" data-load-more<?php echo $pages > 1 ? '' : ' hidden'; ?>><span><?php echo esc_html( $s['more_label'] ); ?></span></button>
			</div>
		<?php endif; ?>
	</div>
</section>
