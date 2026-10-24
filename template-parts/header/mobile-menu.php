<?php
/**
 * Fullscreen mobile menu (sibling of the header so position:fixed is not trapped by the header transform).
 *
 * Uses the same menu source as the desktop navigation: when the Elementor header is
 * active, the "Atelier Navigation" widget publishes its settings via atelier_header_context().
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$ctx       = atelier_header_context();
$cta       = atelier_content( 'nav_cta' );
$cta_text  = ! empty( $ctx['cta_text'] ) ? $ctx['cta_text'] : $cta['label'];
$cta_url   = ! empty( $ctx['cta_url'] ) ? $ctx['cta_url'] : $cta['url'];
$logo_url  = ! empty( $ctx['logo_url'] ) ? $ctx['logo_url'] : '';
$menu_list = atelier_resolve_menu_items( isset( $ctx['menu'] ) ? $ctx['menu'] : '' );
$i         = 0;
?>
<div id="mobile-menu" class="mobile-menu" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu', 'atelier-b2b' ); ?>" aria-hidden="true" inert data-mobile-menu>
	<div class="mobile-menu__top">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Home', 'atelier-b2b' ); ?>">
			<?php if ( $logo_url ) : ?>
				<img class="brand__logo" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php else : ?>
				<?php atelier_logo(); ?>
			<?php endif; ?>
		</a>
		<button class="mobile-menu__close" type="button" data-menu-close>
			<span class="mobile-menu__close-label"><?php esc_html_e( 'Close', 'atelier-b2b' ); ?></span>
			<span class="mobile-menu__close-icon" aria-hidden="true"></span>
		</button>
	</div>

	<nav class="mobile-menu__nav" aria-label="<?php esc_attr_e( 'Mobile', 'atelier-b2b' ); ?>">
		<ul>
			<?php foreach ( $menu_list as $item ) : ?>
				<li style="--i:<?php echo (int) $i++; ?>">
					<a href="<?php echo esc_url( $item['url'] ); ?>"<?php echo ! empty( $item['current'] ) ? ' aria-current="page"' : ''; ?><?php echo ! empty( $item['target'] ) ? ' target="' . esc_attr( $item['target'] ) . '" rel="noopener"' : ''; ?>><?php echo esc_html( $item['label'] ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>

	<?php if ( $cta_text && $cta_url ) : ?>
	<div class="mobile-menu__foot" style="--i:<?php echo (int) $i; ?>">
		<a class="btn btn--solid btn--block" href="<?php echo esc_url( $cta_url ); ?>"><span><?php echo esc_html( $cta_text ); ?></span><?php echo atelier_arrow(); // phpcs:ignore ?></a>
	</div>
	<?php endif; ?>
</div>
