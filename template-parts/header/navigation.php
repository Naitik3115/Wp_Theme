<?php
/**
 * Main navigation: logo left, links centred, CTA right.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
$cta = atelier_content( 'nav_cta' );
?>
<div class="nav">
	<div class="nav__inner container">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' – ' . __( 'Home', 'atelier-b2b' ) ); ?>">
			<?php atelier_logo(); ?>
		</a>

		<nav class="nav__primary" aria-label="<?php esc_attr_e( 'Primary', 'atelier-b2b' ); ?>">
			<ul class="nav__list">
				<?php foreach ( atelier_nav_items() as $item ) : ?>
					<li class="nav__item">
						<a class="nav__link<?php echo $item['current'] ? ' is-current' : ''; ?>" href="<?php echo esc_url( $item['url'] ); ?>"<?php echo $item['current'] ? ' aria-current="page"' : ''; ?><?php echo ! empty( $item['target'] ) ? ' target="' . esc_attr( $item['target'] ) . '" rel="noopener"' : ''; ?>><?php echo esc_html( $item['label'] ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>

		<div class="nav__actions">
			<a class="btn btn--nav" href="<?php echo esc_url( $cta['url'] ); ?>"><span><?php echo esc_html( $cta['label'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore ?></a>
			<button class="burger" type="button" aria-expanded="false" aria-controls="mobile-menu" data-menu-toggle>
				<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'atelier-b2b' ); ?></span>
				<span class="burger__line" aria-hidden="true"></span>
				<span class="burger__line" aria-hidden="true"></span>
			</button>
		</div>
	</div>
</div>
