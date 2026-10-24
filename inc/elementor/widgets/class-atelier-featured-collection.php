<?php
/**
 * Elementor widget: editable editorial collection feature.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Atelier_Featured_Collection_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-featured-collection';
	}

	public function get_title() {
		return esc_html__( 'Atelier Editorial Collection', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-image-box';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'editorial', 'collection', 'feature', 'atelier' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control( 'eyebrow', array(
			'label'       => esc_html__( 'Eyebrow', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Beyond the Ordinary / 2026', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$this->add_control( 'heading', array(
			'label'       => esc_html__( 'Heading', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => "DESIGNED\nTO MAKE\nA STATEMENT.",
			'label_block' => true,
		) );
		$this->add_control( 'description', array(
			'label'       => esc_html__( 'Description', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => 'Every collection begins with an idea. Through thoughtful materials, refined craftsmanship, and attention to detail, we transform creative visions into apparel that speaks for itself.',
			'label_block' => true,
		) );
		$this->add_control( 'image', array(
			'label'   => esc_html__( 'Collection image', 'atelier-b2b' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => array( 'url' => '' ),
		) );
		$this->add_control( 'cta_text', array(
			'label'   => esc_html__( 'Button text', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Explore the collection', 'atelier-b2b' ),
		) );
		$this->add_control( 'cta_link', array(
			'label'       => esc_html__( 'Button URL', 'atelier-b2b' ),
			'type'        => Controls_Manager::URL,
			'default'     => array( 'url' => atelier_url( 'collections' ) ),
			'placeholder' => 'https://',
		) );
		$this->add_control( 'index', array(
			'label'   => esc_html__( 'Rule number', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '01',
		) );
		$this->add_control( 'tag', array(
			'label'   => esc_html__( 'Rule label', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Featured collection', 'atelier-b2b' ),
		) );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Image & layout', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control( 'image_position', array(
			'label'   => esc_html__( 'Image position', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'center center' => esc_html__( 'Center', 'atelier-b2b' ),
				'center top'    => esc_html__( 'Top', 'atelier-b2b' ),
				'center bottom' => esc_html__( 'Bottom', 'atelier-b2b' ),
				'left center'   => esc_html__( 'Left', 'atelier-b2b' ),
				'right center'  => esc_html__( 'Right', 'atelier-b2b' ),
			),
			'default'   => 'center center',
			'selectors' => array( '{{WRAPPER}} .feature__img' => 'object-position: {{VALUE}};' ),
		) );
		$this->add_responsive_control( 'image_min_height', array(
			'label'      => esc_html__( 'Image minimum height', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'vh', 'rem' ),
			'range'      => array(
				'px'  => array( 'min' => 200, 'max' => 1400 ),
				'vh'  => array( 'min' => 20, 'max' => 100 ),
				'rem' => array( 'min' => 12, 'max' => 80 ),
			),
			'selectors'  => array( '{{WRAPPER}} .parallax-wrap' => 'min-height: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'column_gap', array(
			'label'      => esc_html__( 'Column spacing', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem', '%' ),
			'range'      => array( 'px' => array( 'min' => 0, 'max' => 160 ), 'rem' => array( 'min' => 0, 'max' => 10, 'step' => 0.1 ), '%' => array( 'min' => 0, 'max' => 20 ) ),
			'selectors'  => array( '{{WRAPPER}} .feature__grid' => 'column-gap: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'body_padding', array(
			'label'      => esc_html__( 'Content spacing', 'atelier-b2b' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'rem', '%' ),
			'selectors'  => array( '{{WRAPPER}} .feature__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );
		$this->add_control( 'content_alignment', array(
			'label'   => esc_html__( 'Content vertical alignment', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'flex-start' => esc_html__( 'Top', 'atelier-b2b' ),
				'center'     => esc_html__( 'Center', 'atelier-b2b' ),
				'flex-end'   => esc_html__( 'Bottom', 'atelier-b2b' ),
			),
			'default'   => 'center',
			'selectors' => array( '{{WRAPPER}} .feature__body' => 'justify-content: {{VALUE}};' ),
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
			'selectors' => array( '{{WRAPPER}} .feature' => 'background-color: {{VALUE}};' ),
		) );
		$this->add_control( 'text_color', array(
			'label'     => esc_html__( 'Main text color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .feature' => 'color: {{VALUE}};',
				'{{WRAPPER}} .feature__title' => 'color: {{VALUE}};',
			),
		) );
		$this->add_control( 'eyebrow_color', array(
			'label'     => esc_html__( 'Eyebrow color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .feature__eyebrow' => 'color: {{VALUE}};' ),
		) );
		$this->add_control( 'description_color', array(
			'label'     => esc_html__( 'Description color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .feature__text' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typography',
			'selector' => '{{WRAPPER}} .feature__title',
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'eyebrow_typography',
			'selector' => '{{WRAPPER}} .feature__eyebrow',
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'description_typography',
			'selector' => '{{WRAPPER}} .feature__text',
		) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$title_id = 'feature-title-' . sanitize_html_class( $this->get_id() );
		$image = isset( $s['image'] ) && is_array( $s['image'] ) ? $s['image'] : array();
		$link = isset( $s['cta_link'] ) && is_array( $s['cta_link'] ) ? $s['cta_link'] : array();
		?>
		<section class="feature" id="feature-<?php echo esc_attr( sanitize_html_class( $this->get_id() ) ); ?>"<?php echo ! empty( $s['heading'] ) ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ' aria-label="' . esc_attr__( 'Featured collection', 'atelier-b2b' ) . '"'; ?>>
			<div class="feature__grid">
				<figure class="feature__figure" data-clip>
					<div class="parallax-wrap">
						<div class="parallax-img" data-parallax>
							<?php
							if ( ! empty( $image['id'] ) ) {
								echo wp_get_attachment_image( absint( $image['id'] ), 'full', false, array( 'class' => 'feature__img', 'alt' => get_post_meta( absint( $image['id'] ), '_wp_attachment_image_alt', true ), 'loading' => 'lazy', 'decoding' => 'async' ) );
							} elseif ( ! empty( $image['url'] ) ) {
								printf( '<img class="feature__img" src="%1$s" alt="%2$s" loading="lazy" decoding="async">', esc_url( $image['url'] ), esc_attr__( 'Featured collection', 'atelier-b2b' ) );
							} else {
								atelier_image( 'editorial', array( 'class' => 'feature__img', 'sizes' => '(min-width:1024px) 57vw, 100vw' ) );
							}
							?>
						</div>
					</div>
				</figure>
				<div class="feature__body">
					<?php if ( ! empty( $s['eyebrow'] ) ) : ?><p class="label feature__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></p><?php endif; ?>
					<?php if ( ! empty( $s['heading'] ) ) : ?><h2 class="display feature__title" id="<?php echo esc_attr( $title_id ); ?>" data-lines aria-label="<?php echo esc_attr( atelier_plain( $s['heading'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $s['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></h2><?php endif; ?>
					<?php if ( ! empty( $s['index'] ) || ! empty( $s['tag'] ) ) : ?>
						<div class="rule" aria-hidden="true">
							<?php if ( ! empty( $s['index'] ) ) : ?><span class="rule__num"><?php echo esc_html( $s['index'] ); ?></span><?php endif; ?>
							<i class="rule__line"></i>
							<?php if ( ! empty( $s['tag'] ) ) : ?><span class="rule__tag"><?php echo esc_html( $s['tag'] ); ?></span><?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $s['description'] ) ) : ?><p class="feature__text" data-fade><?php echo esc_html( $s['description'] ); ?></p><?php endif; ?>
					<?php if ( ! empty( $s['cta_text'] ) && ! empty( $link['url'] ) ) : ?>
						<div data-fade data-delay="0.15"><a class="btn btn--outline-light" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo atelier_link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><span><?php echo esc_html( $s['cta_text'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
