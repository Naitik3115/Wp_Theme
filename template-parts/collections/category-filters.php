<?php
/**
 * Category filter bar. $args: terms[], total, active (slug), all_label, sticky.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$active = $args['active'];
?>
<nav class="cfilter<?php echo $args['sticky'] ? ' is-sticky' : ''; ?>" aria-label="<?php esc_attr_e( 'Collection categories', 'atelier-b2b' ); ?>" data-filters>
	<div class="cfilter__scroll" data-filter-scroll>
		<ul class="cfilter__list">
			<li>
				<button class="cfilter__btn<?php echo '' === $active ? ' is-active' : ''; ?>" type="button" data-term="" aria-pressed="<?php echo '' === $active ? 'true' : 'false'; ?>">
					<span class="cfilter__name"><?php echo esc_html( $args['all_label'] ); ?></span><sup class="cfilter__count"><?php echo (int) $args['total']; ?></sup>
				</button>
			</li>
			<?php foreach ( $args['terms'] as $t ) : ?>
				<li>
					<button class="cfilter__btn<?php echo $active === $t['slug'] ? ' is-active' : ''; ?>" type="button" data-term="<?php echo esc_attr( $t['slug'] ); ?>" aria-pressed="<?php echo $active === $t['slug'] ? 'true' : 'false'; ?>">
						<span class="cfilter__name"><?php echo esc_html( $t['name'] ); ?></span><sup class="cfilter__count"><?php echo (int) $t['count']; ?></sup>
					</button>
				</li>
			<?php endforeach; ?>
		</ul>
		<span class="cfilter__ind" aria-hidden="true" data-filter-ind></span>
	</div>
</nav>
