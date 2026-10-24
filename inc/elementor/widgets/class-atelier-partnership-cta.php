<?php
/**
 * Elementor widget: editable partnership call to action.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Atelier_Partnership_CTA_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-partnership-cta';
	}

	public function get_title() {
		return esc_html__( 'Atelier Partnership CTA', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'partnership', 'cta', 'contact', 'atelier' );
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
			'default'     => esc_html__( 'Let’s build something together', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$this->add_control( 'heading', array(
			'label'       => esc_html__( 'Heading', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => "YOUR VISION.\nOUR CRAFT.",
			'label_block' => true,
		) );
		$this->add_control( 'description', array(
			'label'       => esc_html__( 'Description', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => 'From concept to creation, we partner with brands to bring exceptional apparel to life.',
			'label_block' => true,
		) );
		$this->add_control( 'button_text', array(
			'label'   => esc_html__( 'Button text', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Start a conversation', 'atelier-b2b' ),
		) );
		$this->add_control( 'button_link', array(
			'label'       => esc_html__( 'Button URL', 'atelier-b2b' ),
			'type'        => Controls_Manager::URL,
			'default'     => array( 'url' => atelier_url( 'contact' ) ),
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
			'selectors'  => array( '{{WRAPPER}} .partner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
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
			'selectors'  => array( '{{WRAPPER}} .partner__inner' => 'max-width: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'foot_spacing', array(
			'label'      => esc_html__( 'Content top spacing', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem', 'vw' ),
			'range'      => array(
				'px'  => array( 'min' => 0, 'max' => 200 ),
				'rem' => array( 'min' => 0, 'max' => 12, 'step' => 0.1 ),
				'vw'  => array( 'min' => 0, 'max' => 15 ),
			),
			'selectors' => array( '{{WRAPPER}} .partner__foot' => 'margin-top: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'content_alignment', array(
			'label'   => esc_html__( 'Desktop content alignment', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'left'   => esc_html__( 'Left', 'atelier-b2b' ),
				'center' => esc_html__( 'Center', 'atelier-b2b' ),
				'right'  => esc_html__( 'Right', 'atelier-b2b' ),
			),
			'default'   => 'left',
			'selectors' => array( '{{WRAPPER}} .partner__inner, {{WRAPPER}} .partner__text' => 'text-align: {{VALUE}};' ),
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
			'selectors' => array( '{{WRAPPER}} .partner' => 'background-color: {{VALUE}};' ),
		) );
		$this->add_control( 'heading_color', array(
			'label'     => esc_html__( 'Heading color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .partner__title' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typography',
			'selector' => '{{WRAPPER}} .partner__title',
		) );
		$this->add_control( 'eyebrow_color', array(
			'label'     => esc_html__( 'Eyebrow color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .partner__label' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'eyebrow_typography',
			'selector' => '{{WRAPPER}} .partner__label',
		) );
		$this->add_control( 'description_color', array(
			'label'     => esc_html__( 'Description color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .partner__text' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'description_typography',
			'selector' => '{{WRAPPER}} .partner__text',
		) );
		$this->add_control( 'button_background', array(
			'label'     => esc_html__( 'Button background', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .partner__foot .btn--solid' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				'{{WRAPPER}} .partner__foot .btn--solid::before' => 'background-color: {{VALUE}};',
			),
		) );
		$this->add_control( 'button_color', array(
			'label'     => esc_html__( 'Button text color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .partner__foot .btn--solid' => 'color: {{VALUE}};',
				'{{WRAPPER}} .partner__foot .btn--solid::before' => 'color: {{VALUE}};',
			),
		) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$title_id = 'partner-title-' . sanitize_html_class( $this->get_id() );
		$link = isset( $s['button_link'] ) && is_array( $s['button_link'] ) ? $s['button_link'] : array();
		?>
		<section class="partner" id="partner-<?php echo esc_attr( sanitize_html_class( $this->get_id() ) ); ?>"<?php echo ! empty( $s['heading'] ) ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ' aria-label="' . esc_attr__( 'Partnership', 'atelier-b2b' ) . '"'; ?>>
			<div class="container partner__inner">
				<?php if ( ! empty( $s['eyebrow'] ) ) : ?><p class="label partner__label"><?php echo esc_html( $s['eyebrow'] ); ?></p><?php endif; ?>
				<?php if ( ! empty( $s['heading'] ) ) : ?><h2 class="display partner__title" id="<?php echo esc_attr( $title_id ); ?>" data-lines aria-label="<?php echo esc_attr( atelier_plain( $s['heading'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $s['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></h2><?php endif; ?>
				<div class="partner__foot">
					<?php if ( ! empty( $s['description'] ) ) : ?><p class="lede partner__text" data-fade><?php echo esc_html( $s['description'] ); ?></p><?php endif; ?>
					<?php if ( ! empty( $s['button_text'] ) && ! empty( $link['url'] ) ) : ?>
						<div data-fade data-delay="0.12"><a class="btn btn--solid btn--lg" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo atelier_link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><span><?php echo esc_html( $s['button_text'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
