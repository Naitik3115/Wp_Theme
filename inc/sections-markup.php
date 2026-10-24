<?php
/**
 * Shared markup for the Hero and Trust marquee sections.
 * Used by BOTH the static template parts (fallback) and the Elementor widgets,
 * so the two can never drift apart visually.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

/** ' target="_blank" rel="noopener nofollow"' from an Elementor URL control value. */
function atelier_link_attrs( $link ) {
	$out = '';
	if ( ! empty( $link['is_external'] ) ) {
		$out .= ' target="_blank"';
	}
	$rel = array();
	if ( ! empty( $link['is_external'] ) ) {
		$rel[] = 'noopener';
	}
	if ( ! empty( $link['nofollow'] ) ) {
		$rel[] = 'nofollow';
	}
	if ( $rel ) {
		$out .= ' rel="' . esc_attr( implode( ' ', $rel ) ) . '"';
	}
	return $out;
}

/**
 * Hero section.
 *
 * @param array $d eyebrow, title (newline = new line), sub, cta_1/cta_2 {label,url,attrs}, bg_html,
 *                 video_url, poster_url, scroll_label (''=hidden), parallax (bool), intro (bool).
 */
function atelier_hero_section( $d ) {
	$d = wp_parse_args(
		$d,
		array(
			'eyebrow'      => '',
			'title'        => '',
			'sub'          => '',
			'cta_1'        => array(),
			'cta_2'        => array(),
			'bg_html'      => '',
			'video_url'    => '',
			'poster_url'   => '',
			'scroll_label' => '',
			'parallax'     => true,
			'intro'        => true,
		)
	);
	$cta = function ( $c, $class, $arrow ) {
		if ( empty( $c['label'] ) || empty( $c['url'] ) ) {
			return;
		}
		?>
				<a class="btn <?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $c['url'] ); ?>"<?php echo isset( $c['attrs'] ) ? $c['attrs'] : ''; // phpcs:ignore ?>><span><?php echo esc_html( $c['label'] ); ?></span><?php echo $arrow ? atelier_arrow() : ''; // phpcs:ignore ?></a>
		<?php
	};
	?>
<section class="hero<?php echo $d['intro'] ? '' : ' hero--static'; ?>" id="hero" aria-labelledby="hero-title">
	<div class="hero__media"<?php echo $d['parallax'] ? ' data-hero-media' : ''; ?>>
		<div class="hero__media-inner">
			<?php if ( $d['video_url'] ) : ?>
				<video class="hero__video" autoplay muted loop playsinline preload="metadata"<?php echo $d['poster_url'] ? ' poster="' . esc_url( $d['poster_url'] ) . '"' : ''; ?> aria-hidden="true" tabindex="-1" data-hero-video>
					<source src="<?php echo esc_url( $d['video_url'] ); ?>" type="video/mp4">
				</video>
			<?php else : ?>
				<?php echo $d['bg_html']; // phpcs:ignore WordPress.Security.EscapeOutput -- built by WP image functions. ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="hero__overlay" aria-hidden="true"></div>

	<div class="hero__content container">
		<?php if ( $d['eyebrow'] ) : ?>
			<p class="hero__eyebrow label"><?php echo esc_html( $d['eyebrow'] ); ?></p>
		<?php endif; ?>

		<h1 class="hero__title display" id="hero-title" aria-label="<?php echo esc_attr( atelier_plain( $d['title'] ) ); ?>">
			<span aria-hidden="true"><?php echo atelier_lines( $d['title'] ); // phpcs:ignore ?></span>
		</h1>

		<div class="hero__foot">
			<?php if ( $d['sub'] ) : ?>
				<p class="hero__sub lede"><?php echo esc_html( $d['sub'] ); ?></p>
			<?php endif; ?>
			<div class="hero__ctas">
				<?php $cta( $d['cta_1'], 'btn--light', true ); ?>
				<?php $cta( $d['cta_2'], 'btn--outline-light', false ); ?>
			</div>
		</div>
	</div>

	<?php if ( $d['scroll_label'] ) : ?>
	<a class="hero__scroll" href="#trust" aria-label="<?php esc_attr_e( 'Scroll to content', 'atelier-b2b' ); ?>">
		<span class="hero__scroll-label"><?php echo esc_html( $d['scroll_label'] ); ?></span>
		<span class="hero__scroll-line" aria-hidden="true"></span>
	</a>
	<?php endif; ?>
</section>
	<?php
}

/**
 * Trust marquee section.
 *
 * @param array $d label, items[], speed, repeat, direction (left|right), separator (null=star, ''=none, string),
 *                 pause (bool).
 */
function atelier_trust_section( $d ) {
	$d = wp_parse_args(
		$d,
		array(
			'label'     => '',
			'items'     => array(),
			'speed'     => 60,
			'repeat'    => 2,
			'direction' => 'left',
			'separator' => null,
			'pause'     => false,
		)
	);
	if ( ! $d['items'] ) {
		return;
	}
	$class = 'marquee--lg' . ( $d['pause'] ? ' marquee--pause' : '' ) . ( 'right' === $d['direction'] ? ' marquee--reverse' : '' );
	?>
<section class="trust" id="trust" aria-label="<?php echo esc_attr( $d['label'] ? $d['label'] : __( 'Our strengths', 'atelier-b2b' ) ); ?>">
	<?php if ( $d['label'] ) : ?>
		<p class="trust__label label container"><?php echo esc_html( $d['label'] ); ?></p>
	<?php endif; ?>
	<?php
	atelier_marquee(
		$d['items'],
		array(
			'class'     => $class,
			'speed'     => $d['speed'],
			'repeat'    => $d['repeat'],
			'separator' => $d['separator'],
		)
	);
	?>
</section>
	<?php
}
