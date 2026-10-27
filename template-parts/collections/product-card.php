<?php
/**
 * Single product card (editorial – no price, rating or cart).
 * $args: product (array), index (int), opts (grid args).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$p     = $args['product'];
$o     = $args['opts'];
$wide  = ( 'wide' === $o['featured'] ) && $p['featured'];
$sizes = $wide ? '(min-width:1100px) 66vw, (min-width:768px) 100vw, 100vw' : '(min-width:1100px) 33vw, (min-width:768px) 50vw, 100vw';
$cats  = implode( ', ', $p['categories'] );
$meta  = array_filter( array( $cats, $o['season'] ? $p['season'] : '' ) );
$tag   = $o['quick'] ? 'button' : 'div';

$main = atelier_col_img( $p['thumb_id'], 'pcard__img-el', $sizes, true );
$hov  = '';
if ( $p['hover_id'] ) {
	$src    = wp_get_attachment_image_url( $p['hover_id'], 'large' );
	$srcset = wp_get_attachment_image_srcset( $p['hover_id'], 'large' );
	if ( $src ) {
		$hov = '<img class="pcard__img-el" alt="" aria-hidden="true" decoding="async" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="' . esc_url( $src ) . '"' . ( $srcset ? ' data-srcset="' . esc_attr( $srcset ) . '"' : '' ) . ' sizes="' . esc_attr( $sizes ) . '">';
	}
}
?>
<article class="pcard<?php echo $wide ? ' is-wide' : ''; ?><?php echo $hov ? ' has-hover' : ''; ?>" data-product-id="<?php echo (int) $p['id']; ?>" style="--i:<?php echo (int) ( $args['index'] % 12 ); ?>">
	<<?php echo $tag; // phpcs:ignore ?> class="pcard__media"<?php echo $o['quick'] ? ' type="button" data-quick-view="' . (int) $p['id'] . '" aria-haspopup="dialog" aria-label="' . esc_attr( sprintf( /* translators: %s: product name */ __( 'Quick view: %s', 'atelier-b2b' ), $p['title'] ) ) . '"' : ''; // phpcs:ignore ?>>
		<span class="pcard__img pcard__img--a">
			<?php echo $main ? $main : '<span class="pcard__noimg">' . esc_html__( 'Image coming soon', 'atelier-b2b' ) . '</span>'; // phpcs:ignore ?>
		</span>
		<?php if ( $hov ) : ?>
			<span class="pcard__img pcard__img--b" data-hover-img><?php echo $hov; // phpcs:ignore ?></span>
		<?php endif; ?>
		<?php if ( $o['quick'] ) : ?>
			<span class="pcard__qv" aria-hidden="true"><span><?php esc_html_e( 'Quick view', 'atelier-b2b' ); ?></span><svg viewBox="0 0 16 16" width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M8 2v12M2 8h12"/></svg></span>
		<?php endif; ?>
	</<?php echo $tag; // phpcs:ignore ?>>

	<div class="pcard__meta">
		<div class="pcard__text">
			<h3 class="pcard__title"><?php echo esc_html( $p['title'] ); ?></h3>
			<?php if ( $meta ) : ?>
				<p class="pcard__cat"><?php echo esc_html( implode( ' · ', $meta ) ); ?></p>
			<?php endif; ?>
			<?php if ( $o['ref'] && $p['ref'] ) : ?>
				<p class="pcard__ref"><?php echo esc_html( sprintf( /* translators: %s: reference */ __( 'Ref. %s', 'atelier-b2b' ), $p['ref'] ) ); ?></p>
			<?php endif; ?>
		</div>
		<?php echo atelier_arrow( 'pcard__arrow' ); // phpcs:ignore ?>
	</div>
</article>
