<?php
/**
 * Collections page intro.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$c = atelier_content( 'collections' );
?>
<section class="collections-intro section" aria-labelledby="collections-title">
	<div class="container collections-intro__inner">
		<p class="label collections-intro__eyebrow"><?php echo esc_html( $c['eyebrow'] ); ?></p>
		<h1 class="display collections-intro__title" id="collections-title" data-lines aria-label="<?php echo esc_attr( atelier_plain( $c['title'] ) ); ?>">
			<span aria-hidden="true"><?php echo atelier_lines( $c['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
		</h1>
		<?php if ( ! empty( $c['sub'] ) ) : ?>
			<p class="lede collections-intro__sub" data-fade><?php echo esc_html( $c['sub'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
