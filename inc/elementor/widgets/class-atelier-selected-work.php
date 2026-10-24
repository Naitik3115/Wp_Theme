<?php
/**
 * Elementor widget: editable Selected Work editorial showcase.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

class Atelier_Selected_Work_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-selected-work';
	}

	public function get_title() {
		return esc_html__( 'Atelier Selected Work', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'selected work', 'showcase', 'portfolio', 'collections', 'atelier' );
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
			'default'     => esc_html__( '02 / Selected work', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$this->add_control( 'heading', array(
			'label'       => esc_html__( 'Heading', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => "THE ART OF\nEVERYDAY WEAR.",
			'label_block' => true,
		) );
		$this->add_control( 'description', array(
			'label'       => esc_html__( 'Description', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => 'Thoughtfully developed apparel, made to represent the identity of every brand we partner with.',
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
			'default' => 'showcase_01',
		) );
		$repeater->add_control( 'index', array(
			'label'   => esc_html__( 'Number', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '01',
		) );
		$repeater->add_control( 'title', array(
			'label'       => esc_html__( 'Title', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Collection title', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$repeater->add_control( 'category', array(
			'label'       => esc_html__( 'Category', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Collection category', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$repeater->add_control( 'link', array(
			'label'       => esc_html__( 'Link (optional)', 'atelier-b2b' ),
			'type'        => Controls_Manager::URL,
			'default'     => array( 'url' => '' ),
			'placeholder' => 'https://',
		) );
		$repeater->add_control( 'image_ratio', array(
			'label'   => esc_html__( 'Image aspect ratio', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'4 / 5.3' => 'Portrait 1 (4:5.3)',
				'4 / 5'   => 'Portrait 2 (4:5)',
				'16 / 10' => 'Landscape (16:10)',
				'1 / 1'   => 'Square (1:1)',
				'3 / 2'   => 'Landscape (3:2)',
			),
			'default' => '4 / 5.3',
		) );
		$repeater->add_control( 'image_position', array(
			'label'   => esc_html__( 'Image position', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'center center' => esc_html__( 'Center', 'atelier-b2b' ),
				'center top'    => esc_html__( 'Top', 'atelier-b2b' ),
				'center bottom' => esc_html__( 'Bottom', 'atelier-b2b' ),
				'left center'   => esc_html__( 'Left', 'atelier-b2b' ),
				'right center'  => esc_html__( 'Right', 'atelier-b2b' ),
			),
			'default' => 'center center',
		) );

		$defaults = array();
		foreach ( atelier_content( 'showcase' )['items'] as $item ) {
			$defaults[] = array(
				'index'          => $item['index'],
				'title'          => $item['title'],
				'category'       => $item['category'],
				'fallback_image' => $item['image'],
				'link'           => array( 'url' => $item['url'] ),
				'image_ratio'    => 'showcase_03' === $item['image'] ? '16 / 10' : ( 'showcase_02' === $item['image'] ? '4 / 5' : '4 / 5.3' ),
				'image_position' => 'center center',
			);
		}
		$this->add_control( 'items', array(
			'label'       => esc_html__( 'Selected work (drag to reorder)', 'atelier-b2b' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => $defaults,
		) );
		$this->add_control( 'cta_text', array(
			'label'   => esc_html__( 'Button text', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'View all collections', 'atelier-b2b' ),
		) );
		$this->add_control( 'cta_link', array(
			'label'       => esc_html__( 'Button URL', 'atelier-b2b' ),
			'type'        => Controls_Manager::URL,
			'default'     => array( 'url' => atelier_url( 'collections' ) ),
			'placeholder' => 'https://',
		) );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout & spacing', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control( 'section_padding', array(
			'label'      => esc_html__( 'Section padding', 'atelier-b2b' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'rem', '%' ),
			'selectors'  => array( '{{WRAPPER}} .showcase' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'card_gap', array(
			'label'      => esc_html__( 'Work item spacing', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem', '%' ),
			'range'      => array(
				'px'  => array( 'min' => 0, 'max' => 160 ),
				'rem' => array( 'min' => 0, 'max' => 10, 'step' => 0.1 ),
				'%'   => array( 'min' => 0, 'max' => 20 ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .showcase__grid' => 'gap: {{SIZE}}{{UNIT}};',
			),
		) );
		$this->add_responsive_control( 'content_width', array(
			'label'      => esc_html__( 'Content max width', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'vw', '%' ),
			'range'      => array(
				'px' => array( 'min' => 600, 'max' => 1920 ),
				'vw' => array( 'min' => 50, 'max' => 100 ),
				'%'  => array( 'min' => 50, 'max' => 100 ),
			),
			'selectors'  => array( '{{WRAPPER}} .showcase > .container' => 'max-width: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'cta_spacing', array(
			'label'      => esc_html__( 'Button top spacing', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem', 'vw' ),
			'range'      => array( 'px' => array( 'min' => 0, 'max' => 200 ), 'rem' => array( 'min' => 0, 'max' => 12, 'step' => 0.1 ), 'vw' => array( 'min' => 0, 'max' => 15 ) ),
			'selectors'  => array( '{{WRAPPER}} .showcase__cta' => 'margin-top: {{SIZE}}{{UNIT}};' ),
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
			'label'     => esc_html__( 'Background color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .showcase' => 'background-color: {{VALUE}};' ),
		) );
		$this->add_control( 'heading_color', array(
			'label'     => esc_html__( 'Heading color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .showcase .section-head__title' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typography',
			'selector' => '{{WRAPPER}} .showcase .section-head__title',
		) );
		$this->add_control( 'eyebrow_color', array(
			'label'     => esc_html__( 'Eyebrow color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .showcase .section-head__label' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'eyebrow_typography',
			'selector' => '{{WRAPPER}} .showcase .section-head__label',
		) );
		$this->add_control( 'description_color', array(
			'label'     => esc_html__( 'Description color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .showcase .section-head__aside .lede' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'description_typography',
			'selector' => '{{WRAPPER}} .showcase .section-head__aside .lede',
		) );
		$this->add_control( 'item_title_color', array(
			'label'     => esc_html__( 'Work title color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .work__title' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'item_title_typography',
			'selector' => '{{WRAPPER}} .work__title',
		) );
		$this->add_control( 'item_meta_color', array(
			'label'     => esc_html__( 'Category and number color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .work__index' => 'color: {{VALUE}};',
				'{{WRAPPER}} .work__cat' => 'color: {{VALUE}};',
			),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'item_category_typography',
			'selector' => '{{WRAPPER}} .work__cat',
		) );
		$this->add_control( 'button_color', array(
			'label'     => esc_html__( 'Button color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .showcase__cta .btn' => 'color: {{VALUE}}; border-color: {{VALUE}};' ),
		) );
		$this->end_controls_section();
	}

	private function render_work_image( $image, $fallback_asset, $fallback_index, $title ) {
		if ( ! empty( $image['id'] ) ) {
			echo wp_get_attachment_image(
				absint( $image['id'] ),
				'large',
				false,
				array(
					'class'    => 'work__img',
					'alt'      => $title,
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
			return;
		}
		if ( ! empty( $image['url'] ) ) {
			printf( '<img class="work__img" src="%1$s" alt="%2$s" loading="lazy" decoding="async">', esc_url( $image['url'] ), esc_attr( $title ) );
			return;
		}

		$assets = atelier_assets();
		if ( ! isset( $assets[ $fallback_asset ] ) ) {
			$items = atelier_content( 'showcase' )['items'];
			$item_count = count( $items );
			$fallback_asset = $item_count ? $items[ $fallback_index % $item_count ]['image'] : '';
		}
		if ( $fallback_asset ) {
			atelier_image( $fallback_asset, array( 'class' => 'work__img', 'alt' => $title, 'sizes' => '(min-width:1024px) 45vw, 90vw' ) );
		}
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$items = ! empty( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		$title_id = 'showcase-title-' . sanitize_html_class( $this->get_id() );
		$cta_link = isset( $s['cta_link'] ) && is_array( $s['cta_link'] ) ? $s['cta_link'] : array();
		?>
		<section class="showcase section" id="showcase-<?php echo esc_attr( sanitize_html_class( $this->get_id() ) ); ?>"<?php echo ! empty( $s['heading'] ) ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ' aria-label="' . esc_attr__( 'Selected work', 'atelier-b2b' ) . '"'; ?>>
			<div class="container section-head">
				<?php if ( ! empty( $s['eyebrow'] ) ) : ?><p class="label section-head__label"><?php echo esc_html( $s['eyebrow'] ); ?></p><?php endif; ?>
				<?php if ( ! empty( $s['heading'] ) ) : ?><h2 class="display section-title section-head__title" id="<?php echo esc_attr( $title_id ); ?>" data-lines aria-label="<?php echo esc_attr( atelier_plain( $s['heading'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $s['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></h2><?php endif; ?>
				<div class="section-head__aside"><?php if ( ! empty( $s['description'] ) ) : ?><p class="lede"><?php echo esc_html( $s['description'] ); ?></p><?php endif; ?></div>
			</div>

			<div class="container">
				<?php if ( $items ) : ?>
					<div class="showcase__grid">
						<?php foreach ( $items as $index => $item ) :
							$position = ( $index % 4 ) + 1;
							$item_title = isset( $item['title'] ) ? $item['title'] : '';
							$link = isset( $item['link'] ) && is_array( $item['link'] ) ? $item['link'] : array();
							$ratio = isset( $item['image_ratio'] ) ? preg_replace( '/[^0-9.\\/ ]/', '', $item['image_ratio'] ) : '4 / 5.3';
							$image_position = isset( $item['image_position'] ) ? sanitize_text_field( $item['image_position'] ) : 'center center';
							?>
							<article class="work work--<?php echo esc_attr( $position ); ?>">
								<?php if ( ! empty( $link['url'] ) ) : ?><a class="work__link" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo atelier_link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php else : ?><div class="work__link"><?php endif; ?>
									<span class="work__fig" data-clip style="aspect-ratio:<?php echo esc_attr( $ratio ); ?>">
										<?php $this->render_work_image( isset( $item['image'] ) ? $item['image'] : array(), isset( $item['fallback_image'] ) ? sanitize_key( $item['fallback_image'] ) : '', $index, $item_title ); ?>
										<span class="work__tint" aria-hidden="true"></span>
									</span>
									<span class="work__cap">
										<span class="work__index" aria-hidden="true"><?php echo esc_html( ! empty( $item['index'] ) ? $item['index'] : sprintf( '%02d', $index + 1 ) ); ?></span>
										<span class="work__meta">
											<span class="work__title"><?php echo esc_html( $item_title ); ?></span>
											<?php if ( ! empty( $item['category'] ) ) : ?><span class="work__cat"><?php echo esc_html( $item['category'] ); ?></span><?php endif; ?>
										</span>
										<?php echo atelier_arrow( 'work__arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									</span>
								<?php if ( ! empty( $link['url'] ) ) : ?></a><?php else : ?></div><?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $s['cta_text'] ) && ! empty( $cta_link['url'] ) ) : ?>
					<div class="showcase__cta"><a class="btn btn--outline-dark" href="<?php echo esc_url( $cta_link['url'] ); ?>"<?php echo atelier_link_attrs( $cta_link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><span><?php echo esc_html( $s['cta_text'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
