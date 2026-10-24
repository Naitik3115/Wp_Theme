<?php
/**
 * Elementor widget: Atelier Marquee (seamless infinite text ticker).
 *
 * Uses the theme's existing atelier_marquee() helper and CSS, so the animation is identical to
 * the original static announcement bar. Can also be reused later for the trust marquee.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

class Atelier_Marquee_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-marquee';
	}

	public function get_title() {
		return esc_html__( 'Atelier Marquee', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-animation-text';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'marquee', 'ticker', 'announcement', 'text', 'atelier' );
	}

	protected function register_controls() {

		$this->start_controls_section( 'section_items', array(
			'label' => esc_html__( 'Messages', 'atelier-b2b' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$repeater = new Repeater();
		$repeater->add_control( 'text', array(
			'label'       => esc_html__( 'Text', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Your message', 'atelier-b2b' ),
			'label_block' => true,
		) );

		$this->add_control( 'items', array(
			'label'       => esc_html__( 'Messages', 'atelier-b2b' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => array(
				array( 'text' => 'Premium apparel manufacturing' ),
				array( 'text' => 'Designed for global brands' ),
				array( 'text' => 'Quality without compromise' ),
				array( 'text' => 'Your vision, our craft' ),
			),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_options', array(
			'label' => esc_html__( 'Animation & layout', 'atelier-b2b' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'speed', array(
			'label'       => esc_html__( 'Seconds per loop (lower = faster)', 'atelier-b2b' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 5,
			'max'         => 300,
			'step'        => 1,
			'default'     => 45,
		) );

		$this->add_control( 'size', array(
			'label'   => esc_html__( 'Preset', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'sm' => esc_html__( 'Small – announcement bar', 'atelier-b2b' ),
				'lg' => esc_html__( 'Large – statement ticker', 'atelier-b2b' ),
			),
			'default' => 'sm',
		) );

		$this->add_control( 'show_separator', array(
			'label'        => esc_html__( 'Show separator', 'atelier-b2b' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		) );

		$this->add_control( 'pause_hover', array(
			'label'        => esc_html__( 'Pause on hover', 'atelier-b2b' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style', array(
			'label' => esc_html__( 'Text & separator', 'atelier-b2b' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'text_typography',
			'selector' => '{{WRAPPER}} .marquee__text',
		) );

		$this->add_control( 'text_color', array(
			'label'     => esc_html__( 'Text color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .marquee__text' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'sep_color', array(
			'label'     => esc_html__( 'Separator color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .marquee .sep' => 'color: {{VALUE}};' ),
		) );

		$this->add_responsive_control( 'sep_space', array(
			'label'      => esc_html__( 'Space around separator', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'      => array( 'px' => array( 'min' => 4, 'max' => 120 ) ),
			'selectors'  => array(
				'{{WRAPPER}} .marquee .sep'         => 'margin-inline: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .atelier-marquee--nosep .marquee__item' => 'padding-inline: {{SIZE}}{{UNIT}};',
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = array();

		if ( ! empty( $s['items'] ) && is_array( $s['items'] ) ) {
			foreach ( $s['items'] as $row ) {
				if ( isset( $row['text'] ) && '' !== trim( $row['text'] ) ) {
					$items[] = $row['text'];
				}
			}
		}
		if ( ! $items ) {
			return;
		}

		$classes = array( 'marquee--' . ( 'lg' === $s['size'] ? 'lg' : 'sm' ) );
		if ( 'yes' === $s['pause_hover'] ) {
			$classes[] = 'marquee--pause';
		}

		$wrap = 'atelier-marquee-widget' . ( 'yes' === $s['show_separator'] ? '' : ' atelier-marquee--nosep' );

		echo '<div class="' . esc_attr( $wrap ) . '">';
		atelier_marquee(
			$items,
			array(
				'class'  => implode( ' ', $classes ),
				'speed'  => max( 5, (int) $s['speed'] ),
				'repeat' => 'lg' === $s['size'] ? 2 : 3,
			)
		);
		echo '</div>';
	}
}
