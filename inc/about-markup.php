<?php
/**
 * Shared markup for About Us sections (static template parts + Elementor widgets).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

/** About page Elementor slot keys. */
function atelier_about_slot_keys() {
	return array( 'about_hero', 'about_who', 'about_philosophy', 'about_commitment', 'about_cta' );
}

/**
 * Render an image from Elementor MEDIA control, or fall back to a theme asset key.
 *
 * @param array  $media Elementor media array (id/url/alt).
 * @param string $fallback_key atelier_assets() key.
 * @param array  $args class, loading, sizes, fetchpriority, alt.
 */
function atelier_el_media( $media, $fallback_key, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'class'         => '',
			'loading'       => 'lazy',
			'sizes'         => '100vw',
			'fetchpriority' => '',
			'alt'           => '',
		)
	);

	$media = is_array( $media ) ? $media : array();

	if ( ! empty( $media['id'] ) ) {
		$attr = array(
			'class'    => $args['class'],
			'loading'  => $args['loading'],
			'decoding' => 'async',
			'sizes'    => $args['sizes'],
		);
		if ( $args['fetchpriority'] ) {
			$attr['fetchpriority'] = $args['fetchpriority'];
		}
		$alt = $args['alt'] ? $args['alt'] : get_post_meta( absint( $media['id'] ), '_wp_attachment_image_alt', true );
		echo wp_get_attachment_image( absint( $media['id'] ), 'full', false, array_merge( $attr, array( 'alt' => $alt ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		return;
	}

	if ( ! empty( $media['url'] ) ) {
		printf(
			'<img class="%1$s" src="%2$s" alt="%3$s" loading="%4$s" decoding="async" sizes="%5$s"%6$s>',
			esc_attr( $args['class'] ),
			esc_url( $media['url'] ),
			esc_attr( $args['alt'] ? $args['alt'] : ( isset( $media['alt'] ) ? $media['alt'] : '' ) ),
			esc_attr( $args['loading'] ),
			esc_attr( $args['sizes'] ),
			$args['fetchpriority'] ? ' fetchpriority="' . esc_attr( $args['fetchpriority'] ) . '"' : ''
		);
		return;
	}

	if ( $fallback_key ) {
		atelier_image( $fallback_key, $args );
	}
}

/**
 * About hero.
 *
 * @param array $d eyebrow, title, sub, scroll, cta_1/cta_2, media_type, image, video, poster, image_key.
 */
function atelier_about_hero_section( $d ) {
	$d = wp_parse_args(
		$d,
		array(
			'eyebrow'    => '',
			'title'      => '',
			'sub'        => '',
			'scroll'     => '',
			'cta_1'      => array(),
			'cta_2'      => array(),
			'media_type' => 'auto',
			'image'      => array(),
			'video'      => array(),
			'poster'     => array(),
			'image_key'  => '',
			'section_id' => 'about-hero',
		)
	);
	$bg_html   = '';
	$image     = is_array( $d['image'] ) ? $d['image'] : array();
	$video     = is_array( $d['video'] ) ? $d['video'] : array();
	$poster    = is_array( $d['poster'] ) ? $d['poster'] : array();
	$has_image = ! empty( $image['id'] ) || ! empty( $image['url'] );
	$has_video = ! empty( $video['url'] );
	$media_type = in_array( $d['media_type'], array( 'auto', 'image', 'video' ), true ) ? $d['media_type'] : 'auto';
	$video_url = '';
	$poster_url = ! empty( $poster['url'] ) ? $poster['url'] : '';
	$use_video = 'video' === $media_type
		|| ( 'auto' === $media_type && ( $has_video || ( ! $has_image && empty( $d['image_key'] ) ) ) );

	if ( $use_video ) {
		$video_url  = ! empty( $video['url'] ) ? $video['url'] : atelier_asset_url( 'hero_video' );
		$image_url  = ! empty( $image['url'] ) ? $image['url'] : ( ! empty( $image['id'] ) ? wp_get_attachment_image_url( absint( $image['id'] ), 'full' ) : '' );
		$poster_url = $poster_url ? $poster_url : ( $image_url ? $image_url : atelier_asset_url( 'hero_poster' ) );
	} elseif ( $has_image ) {
		ob_start();
		atelier_el_media(
			$image,
			'',
			array(
				'class'         => 'hero__img',
				'loading'       => 'eager',
				'fetchpriority' => 'high',
				'sizes'         => '100vw',
			)
		);
		$bg_html = ob_get_clean();
		$poster_url = ! empty( $image['url'] ) ? $image['url'] : wp_get_attachment_image_url( absint( $image['id'] ), 'full' );
	} elseif ( $d['image_key'] ) {
		ob_start();
		atelier_image( $d['image_key'], array( 'class' => 'hero__img', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '100vw' ) );
		$bg_html   = ob_get_clean();
		$poster_url = atelier_asset_url( $d['image_key'] );
	} else {
		ob_start();
		atelier_image( 'hero_poster', array( 'class' => 'hero__img', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '100vw' ) );
		$bg_html = ob_get_clean();
		$poster_url = atelier_asset_url( 'hero_poster' );
	}

	atelier_hero_section(
		array(
			'eyebrow'      => $d['eyebrow'],
			'title'        => $d['title'],
			'sub'          => $d['sub'],
			'cta_1'        => $d['cta_1'],
			'cta_2'        => $d['cta_2'],
			'bg_html'      => $bg_html,
			'video_url'    => $video_url,
			'poster_url'   => $poster_url,
			'scroll_label' => $d['scroll'] ? $d['scroll'] : __( 'Scroll', 'atelier-b2b' ),
			'scroll_target' => '#about-who',
			'section_id'   => $d['section_id'],
		)
	);
}

/**
 * Who We Are.
 *
 * @param array $d label, title, lede, text, aside, image_1, image_2, image_1_key, image_2_key.
 */
function atelier_about_who_section( $d ) {
	$d = wp_parse_args(
		$d,
		array(
			'label'       => '',
			'title'       => '',
			'lede'        => '',
			'text'        => '',
			'aside'       => '',
			'image_1'     => array(),
			'image_2'     => array(),
			'image_1_key' => 'editorial',
			'image_2_key' => 'category_women',
			'section_id'  => 'about-who',
		)
	);
	$title_id = $d['section_id'] . '-title';
	?>
	<section class="about-who section" id="<?php echo esc_attr( $d['section_id'] ); ?>"<?php echo $d['title'] ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ' aria-label="' . esc_attr__( 'Who we are', 'atelier-b2b' ) . '"'; ?>>
		<div class="container about-who__inner">
			<?php if ( $d['label'] ) : ?><p class="label about-who__label" data-fade><?php echo esc_html( $d['label'] ); ?></p><?php endif; ?>
			<div class="about-who__grid">
				<figure class="about-who__figure about-who__figure--primary" data-clip>
					<div class="about-who__frame">
						<div class="about-who__parallax" data-parallax>
							<?php atelier_el_media( $d['image_1'], $d['image_1_key'], array( 'class' => 'about-who__img', 'sizes' => '(min-width:1024px) 52vw, 100vw' ) ); ?>
						</div>
					</div>
				</figure>
				<div class="about-who__copy">
					<?php if ( $d['title'] ) : ?>
						<h2 class="display about-who__title" id="<?php echo esc_attr( $title_id ); ?>" data-lines aria-label="<?php echo esc_attr( atelier_plain( $d['title'] ) ); ?>">
							<span aria-hidden="true"><?php echo atelier_lines( $d['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						</h2>
					<?php endif; ?>
					<?php if ( $d['lede'] ) : ?><p class="lede about-who__lede" data-fade><?php echo esc_html( $d['lede'] ); ?></p><?php endif; ?>
					<?php if ( $d['text'] ) : ?><p class="about-who__text" data-fade data-delay="0.08"><?php echo esc_html( $d['text'] ); ?></p><?php endif; ?>
				</div>
				<figure class="about-who__figure about-who__figure--secondary" data-clip>
					<div class="about-who__frame about-who__frame--sm">
						<?php atelier_el_media( $d['image_2'], $d['image_2_key'], array( 'class' => 'about-who__img', 'sizes' => '(min-width:1024px) 28vw, 70vw' ) ); ?>
					</div>
					<?php if ( $d['aside'] ) : ?><figcaption class="about-who__aside" data-fade><?php echo esc_html( $d['aside'] ); ?></figcaption><?php endif; ?>
				</figure>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Philosophy.
 *
 * @param array $d label, title, text, pillars[] {index,title,text,image,image_key}.
 */
function atelier_about_philosophy_section( $d ) {
	$d = wp_parse_args(
		$d,
		array(
			'label'      => '',
			'title'      => '',
			'text'       => '',
			'pillars'    => array(),
			'section_id' => 'about-philosophy',
		)
	);
	$title_id = $d['section_id'] . '-title';
	?>
	<section class="about-phil section" id="<?php echo esc_attr( $d['section_id'] ); ?>"<?php echo $d['title'] ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ' aria-label="' . esc_attr__( 'Our philosophy', 'atelier-b2b' ) . '"'; ?>>
		<div class="container about-phil__inner">
			<header class="about-phil__head">
				<?php if ( $d['label'] ) : ?><p class="label about-phil__label" data-fade><?php echo esc_html( $d['label'] ); ?></p><?php endif; ?>
				<?php if ( $d['title'] ) : ?>
					<h2 class="display about-phil__title" id="<?php echo esc_attr( $title_id ); ?>" data-lines aria-label="<?php echo esc_attr( atelier_plain( $d['title'] ) ); ?>">
						<span aria-hidden="true"><?php echo atelier_lines( $d['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					</h2>
				<?php endif; ?>
				<?php if ( $d['text'] ) : ?><p class="lede about-phil__text" data-fade><?php echo esc_html( $d['text'] ); ?></p><?php endif; ?>
			</header>
			<div class="about-phil__pillars">
				<?php foreach ( $d['pillars'] as $i => $p ) :
					$p = wp_parse_args(
						$p,
						array(
							'index'     => '',
							'title'     => '',
							'text'      => '',
							'image'     => array(),
							'image_key' => '',
						)
					);
					?>
					<article class="about-phil__pillar" data-fade data-delay="<?php echo esc_attr( number_format( $i * 0.1, 2, '.', '' ) ); ?>">
						<figure class="about-phil__media" data-clip>
							<?php atelier_el_media( $p['image'], $p['image_key'], array( 'class' => 'about-phil__img', 'sizes' => '(min-width:1024px) 30vw, 90vw' ) ); ?>
						</figure>
						<div class="about-phil__body">
							<?php if ( $p['index'] ) : ?><span class="about-phil__index" aria-hidden="true"><?php echo esc_html( $p['index'] ); ?></span><?php endif; ?>
							<?php if ( $p['title'] ) : ?><h3 class="about-phil__pillar-title"><?php echo esc_html( $p['title'] ); ?></h3><?php endif; ?>
							<?php if ( $p['text'] ) : ?><p class="about-phil__pillar-text"><?php echo esc_html( $p['text'] ); ?></p><?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Commitment.
 *
 * @param array $d label, title, text, points[], image, image_key.
 */
function atelier_about_commitment_section( $d ) {
	$d = wp_parse_args(
		$d,
		array(
			'label'      => '',
			'title'      => '',
			'text'       => '',
			'points'     => array(),
			'image'      => array(),
			'image_key'  => 'category_men',
			'section_id' => 'about-commitment',
		)
	);
	$title_id = $d['section_id'] . '-title';
	?>
	<section class="about-commit section" id="<?php echo esc_attr( $d['section_id'] ); ?>"<?php echo $d['title'] ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ' aria-label="' . esc_attr__( 'Our commitment', 'atelier-b2b' ) . '"'; ?>>
		<div class="about-commit__grid">
			<div class="about-commit__body">
				<?php if ( $d['label'] ) : ?><p class="label about-commit__label" data-fade><?php echo esc_html( $d['label'] ); ?></p><?php endif; ?>
				<?php if ( $d['title'] ) : ?>
					<h2 class="display about-commit__title" id="<?php echo esc_attr( $title_id ); ?>" data-lines aria-label="<?php echo esc_attr( atelier_plain( $d['title'] ) ); ?>">
						<span aria-hidden="true"><?php echo atelier_lines( $d['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					</h2>
				<?php endif; ?>
				<?php if ( $d['text'] ) : ?><p class="about-commit__text" data-fade><?php echo esc_html( $d['text'] ); ?></p><?php endif; ?>
				<?php if ( ! empty( $d['points'] ) ) : ?>
					<ul class="about-commit__points" data-fade data-delay="0.1">
						<?php foreach ( $d['points'] as $point ) :
							$point = is_array( $point ) ? ( isset( $point['text'] ) ? $point['text'] : '' ) : $point;
							if ( '' === $point ) {
								continue;
							}
							?>
							<li><?php echo esc_html( $point ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<figure class="about-commit__figure" data-clip>
				<div class="about-commit__frame">
					<div class="about-commit__parallax" data-parallax>
						<?php atelier_el_media( $d['image'], $d['image_key'], array( 'class' => 'about-commit__img', 'sizes' => '(min-width:1024px) 55vw, 100vw' ) ); ?>
					</div>
				</div>
			</figure>
		</div>
	</section>
	<?php
}

/**
 * Closing CTA.
 *
 * @param array $d label, title, text, cta_1 {label,url,attrs}, cta_2 {label,url,attrs}.
 */
function atelier_about_cta_section( $d ) {
	$d = wp_parse_args(
		$d,
		array(
			'label'      => '',
			'title'      => '',
			'text'       => '',
			'cta_1'      => array(),
			'cta_2'      => array(),
			'section_id' => 'about-cta',
		)
	);
	$title_id = $d['section_id'] . '-title';
	$cta_1    = wp_parse_args( $d['cta_1'], array( 'label' => '', 'url' => '', 'attrs' => '' ) );
	$cta_2    = wp_parse_args( $d['cta_2'], array( 'label' => '', 'url' => '', 'attrs' => '' ) );
	?>
	<section class="about-cta section" id="<?php echo esc_attr( $d['section_id'] ); ?>"<?php echo $d['title'] ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ' aria-label="' . esc_attr__( 'Next chapter', 'atelier-b2b' ) . '"'; ?>>
		<div class="container about-cta__inner">
			<?php if ( $d['label'] ) : ?><p class="label about-cta__label" data-fade><?php echo esc_html( $d['label'] ); ?></p><?php endif; ?>
			<?php if ( $d['title'] ) : ?>
				<h2 class="display about-cta__title" id="<?php echo esc_attr( $title_id ); ?>" data-lines aria-label="<?php echo esc_attr( atelier_plain( $d['title'] ) ); ?>">
					<span aria-hidden="true"><?php echo atelier_lines( $d['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</h2>
			<?php endif; ?>
			<div class="about-cta__foot">
				<?php if ( $d['text'] ) : ?><p class="lede about-cta__text" data-fade><?php echo esc_html( $d['text'] ); ?></p><?php endif; ?>
				<div class="about-cta__actions" data-fade data-delay="0.12">
					<?php if ( $cta_1['label'] && $cta_1['url'] ) : ?>
						<a class="btn btn--solid btn--lg" href="<?php echo esc_url( $cta_1['url'] ); ?>"<?php echo $cta_1['attrs']; // phpcs:ignore WordPress.Security.EscapeOutput ?>><span><?php echo esc_html( $cta_1['label'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
					<?php endif; ?>
					<?php if ( $cta_2['label'] && $cta_2['url'] ) : ?>
						<a class="btn btn--outline-dark btn--lg" href="<?php echo esc_url( $cta_2['url'] ); ?>"<?php echo $cta_2['attrs']; // phpcs:ignore WordPress.Security.EscapeOutput ?>><span><?php echo esc_html( $cta_2['label'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}
