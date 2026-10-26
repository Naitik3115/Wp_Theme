<?php
/**
 * Elementor widget: editable featured categories carousel.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

class Atelier_Categories_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-categories';
	}

	public function get_title() {
		return esc_html__( 'Atelier Categories', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-posts-carousel';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'categories', 'carousel', 'slider', 'atelier' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Section content', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'eyebrow', array(
			'label'       => esc_html__( 'Eyebrow', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( '01 / What we create', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$this->add_control( 'heading', array(
			'label'       => esc_html__( 'Heading', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => "CATEGORIES\nOF EXPRESSION.",
			'label_block' => true,
		) );
		$this->add_control( 'description', array(
			'label'       => esc_html__( 'Description', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => 'From everyday essentials to statement collections, explore apparel crafted around your brand’s vision.',
			'label_block' => true,
		) );

		$repeater = new Repeater();
		$repeater->add_control( 'image', array(
			'label'   => esc_html__( 'Image', 'atelier-b2b' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => array( 'url' => '' ),
		) );
		$repeater->add_control( 'fallback_image', array(
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'category_men',
		) );
		$repeater->add_control( 'title', array(
			'label'       => esc_html__( 'Title', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Category', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$repeater->add_control( 'number', array(
			'label'   => esc_html__( 'Number', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '01',
		) );
		$repeater->add_control( 'link', array(
			'label'       => esc_html__( 'Link (optional)', 'atelier-b2b' ),
			'type'        => Controls_Manager::URL,
			'default'     => array( 'url' => '' ),
			'placeholder' => 'https://',
		) );

		$defaults = array();
		foreach ( atelier_content( 'categories' )['items'] as $index => $item ) {
			$defaults[] = array(
				'title'  => $item['title'],
				'number' => sprintf( '%02d', $index + 1 ),
				'image'          => array( 'url' => '' ),
				'fallback_image' => $item['image'],
				'link'           => array( 'url' => $item['url'] ),
			);
		}
		$this->add_control( 'items', array(
			'label'       => esc_html__( 'Categories (drag to reorder)', 'atelier-b2b' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => $defaults,
		) );
		$this->add_control( 'slider_speed', array(
			'label'       => esc_html__( 'Arrow scroll duration (ms)', 'atelier-b2b' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 3000,
			'step'        => 50,
			'default'     => 650,
			'description' => esc_html__( 'Set to 0 to use native smooth scrolling. Drag/swipe always work.', 'atelier-b2b' ),
		) );
		$this->add_control( 'show_arrows', array(
			'label'        => esc_html__( 'Show navigation arrows', 'atelier-b2b' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'atelier-b2b' ),
			'label_off'    => esc_html__( 'No', 'atelier-b2b' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		) );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Image & layout', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control( 'image_ratio', array(
			'label'   => esc_html__( 'Image aspect ratio', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'3 / 4'  => '3:4',
				'2 / 3'  => '2:3',
				'4 / 5'  => '4:5',
				'1 / 1'  => '1:1',
				'4 / 3'  => '4:3',
			),
			'default' => '3 / 4',
			'selectors' => array( '{{WRAPPER}} .cat-card' => 'aspect-ratio: {{VALUE}};' ),
		) );
		$this->add_control( 'image_position', array(
			'label'   => esc_html__( 'Image focus', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'center center' => esc_html__( 'Center', 'atelier-b2b' ),
				'center top'    => esc_html__( 'Top', 'atelier-b2b' ),
				'center bottom' => esc_html__( 'Bottom', 'atelier-b2b' ),
				'left center'   => esc_html__( 'Left', 'atelier-b2b' ),
				'right center'  => esc_html__( 'Right', 'atelier-b2b' ),
			),
			'default'   => 'center center',
			'selectors' => array( '{{WRAPPER}} .cat-card__img' => 'object-position: {{VALUE}};' ),
		) );
		$this->add_responsive_control( 'slide_width', array(
			'label'      => esc_html__( 'Card width', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'vw', 'px', '%' ),
			'range'      => array(
				'vw' => array( 'min' => 20, 'max' => 90 ),
				'px' => array( 'min' => 180, 'max' => 800 ),
				'%'  => array( 'min' => 20, 'max' => 100 ),
			),
			'selectors'  => array( '{{WRAPPER}} .slider__slide' => 'width: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'card_gap', array(
			'label'      => esc_html__( 'Card spacing', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem' ),
			'range'      => array( 'px' => array( 'min' => 0, 'max' => 100 ), 'rem' => array( 'min' => 0, 'max' => 6, 'step' => 0.1 ) ),
			'selectors'  => array( '{{WRAPPER}} .slider__track' => 'gap: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'section_padding', array(
			'label'      => esc_html__( 'Section padding', 'atelier-b2b' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'rem', '%' ),
			'selectors'  => array( '{{WRAPPER}} .categories' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Colors & typography', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control( 'background_color', array(
			'label'     => esc_html__( 'Section background', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .categories' => 'background-color: {{VALUE}};' ),
		) );
		$this->add_control( 'heading_color', array(
			'label'     => esc_html__( 'Heading color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .section-head__title' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typography',
			'selector' => '{{WRAPPER}} .section-head__title',
		) );
		$this->add_control( 'eyebrow_color', array(
			'label'     => esc_html__( 'Eyebrow color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .section-head__label' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'eyebrow_typography',
			'selector' => '{{WRAPPER}} .section-head__label',
		) );
		$this->add_control( 'description_color', array(
			'label'     => esc_html__( 'Description color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .section-head__aside .lede' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'description_typography',
			'selector' => '{{WRAPPER}} .section-head__aside .lede',
		) );
		$this->add_control( 'card_title_color', array(
			'label'     => esc_html__( 'Card title color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .cat-card' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'card_title_typography',
			'selector' => '{{WRAPPER}} .cat-card__title',
		) );
		$this->add_control( 'navigation_color', array(
			'label'     => esc_html__( 'Navigation color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .slider-btn' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				'{{WRAPPER}} .slider__progress span' => 'background-color: {{VALUE}};',
			),
		) );
		$this->end_controls_section();
	}

	/**
	 * Render an uploaded image or the matching built-in category image.
	 */
	private function render_category_image( $image, $fallback_asset, $fallback_index, $title ) {
		if ( ! empty( $image['id'] ) ) {
			echo wp_get_attachment_image(
				absint( $image['id'] ),
				'large',
				false,
				array(
					'class'    => 'cat-card__img',
					'alt'      => $title,
					'loading'  => 'lazy',
					'decoding' => 'async',
					'draggable' => 'false',
				)
			);
			return;
		}
		if ( ! empty( $image['url'] ) ) {
			printf( '<img class="cat-card__img" src="%1$s" alt="%2$s" loading="lazy" decoding="async" draggable="false">', esc_url( $image['url'] ), esc_attr( $title ) );
			return;
		}

		$assets = atelier_assets();
		if ( ! isset( $assets[ $fallback_asset ] ) ) {
			$items = atelier_content( 'categories' )['items'];
			$fallback_asset = isset( $items[ $fallback_index ] ) ? $items[ $fallback_index ]['image'] : '';
		}
		if ( $fallback_asset ) {
			atelier_image( $fallback_asset, array( 'class' => 'cat-card__img', 'alt' => $title, 'sizes' => '(min-width:1024px) 42vw, (min-width:768px) 60vw, 80vw' ) );
		}
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$items = ! empty( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		if ( ! $items ) {
			return;
		}
		$title_id = 'categories-title-' . sanitize_html_class( $this->get_id() );
		?>
		<section class="categories section" id="categories-<?php echo esc_attr( sanitize_html_class( $this->get_id() ) ); ?>"<?php echo ! empty( $s['heading'] ) ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ' aria-label="' . esc_attr__( 'Product categories', 'atelier-b2b' ) . '"'; ?>>
			<div class="container section-head">
				<?php if ( ! empty( $s['eyebrow'] ) ) : ?><p class="label section-head__label"><?php echo esc_html( $s['eyebrow'] ); ?></p><?php endif; ?>
				<?php if ( ! empty( $s['heading'] ) ) : ?>
					<h2 class="display section-title section-head__title" id="<?php echo esc_attr( $title_id ); ?>" data-lines aria-label="<?php echo esc_attr( atelier_plain( $s['heading'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $s['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></h2>
				<?php endif; ?>
				<div class="section-head__aside">
					<?php if ( ! empty( $s['description'] ) ) : ?><p class="lede"><?php echo esc_html( $s['description'] ); ?></p><?php endif; ?>
					<?php if ( empty( $s['show_arrows'] ) || 'yes' === $s['show_arrows'] ) : ?>
					<div class="slider-controls" data-slider-controls>
						<button class="slider-btn" type="button" data-slider-prev aria-label="<?php esc_attr_e( 'Previous category', 'atelier-b2b' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 12H5M11 6l-6 6 6 6"/></svg></button>
						<button class="slider-btn" type="button" data-slider-next aria-label="<?php esc_attr_e( 'Next category', 'atelier-b2b' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="slider" data-slider data-slider-speed="<?php echo esc_attr( max( 0, absint( $s['slider_speed'] ) ) ); ?>" role="region" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Product categories', 'atelier-b2b' ); ?>">
				<div class="slider__viewport" data-slider-viewport>
					<ul class="slider__track">
						<?php foreach ( $items as $index => $item ) :
							$title = isset( $item['title'] ) ? $item['title'] : '';
							$link  = isset( $item['link'] ) && is_array( $item['link'] ) ? $item['link'] : array();
							$href  = ! empty( $link['url'] ) ? $link['url'] : '';
							?>
							<li class="slider__slide" aria-roledescription="slide" aria-label="<?php echo esc_attr( ( $index + 1 ) . ' / ' . count( $items ) ); ?>">
								<?php if ( $href ) : ?><a class="cat-card" href="<?php echo esc_url( $href ); ?>"<?php echo atelier_link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput ?> draggable="false"><?php else : ?><div class="cat-card"><?php endif; ?>
									<span class="cat-card__media"><?php $this->render_category_image( isset( $item['image'] ) ? $item['image'] : array(), isset( $item['fallback_image'] ) ? sanitize_key( $item['fallback_image'] ) : '', $index, $title ); ?></span>
									<span class="cat-card__shade" aria-hidden="true"></span>
									<span class="cat-card__num" aria-hidden="true"><?php echo esc_html( ! empty( $item['number'] ) ? $item['number'] : sprintf( '%02d', $index + 1 ) ); ?></span>
									<span class="cat-card__arrow"><?php echo atelier_arrow( 'cat-card__arrow-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
									<span class="cat-card__title"><?php echo esc_html( $title ); ?></span>
								<?php if ( $href ) : ?></a><?php else : ?></div><?php endif; ?>
							</li>
						<?php endforeach; ?>
						<li class="slider__end" aria-hidden="true"></li>
					</ul>
				</div>
				<div class="container"><div class="slider__progress" aria-hidden="true"><span data-slider-progress></span></div></div>
				<div class="drag-cursor" data-drag-cursor aria-hidden="true"><span><?php esc_html_e( 'Drag', 'atelier-b2b' ); ?></span></div>
			</div>
		</section>
		<?php
	}
}
