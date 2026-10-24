<?php
/**
 * Elementor widget: Atelier Trust Marquee (label + seamless infinite ticker).
 * Renders through atelier_trust_section(), the same function as the static template part.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

class Atelier_Trust_Marquee_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-trust-marquee';
	}

	public function get_title() {
		return esc_html__( 'Atelier Trust Marquee', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'marquee', 'trust', 'ticker', 'atelier' );
	}

	protected function register_controls() {

		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );

		$this->add_control( 'label', array(
			'label'       => esc_html__( 'Small label (empty = hide)', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Your trusted apparel manufacturing partner', 'atelier-b2b' ),
			'label_block' => true,
		) );

		$repeater = new Repeater();
		$repeater->add_control( 'text', array(
			'label'       => esc_html__( 'Text', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Your message', 'atelier-b2b' ),
			'label_block' => true,
		) );

		$this->add_control( 'items', array(
			'label'       => esc_html__( 'Items (add, remove, drag to reorder)', 'atelier-b2b' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => array(
				array( 'text' => 'Premium Quality' ),
				array( 'text' => 'Global Sourcing' ),
				array( 'text' => 'Precision Manufacturing' ),
				array( 'text' => 'Responsible Production' ),
				array( 'text' => 'Made for your brand' ),
			),
		) );

		$this->add_control( 'separator', array(
			'label'   => esc_html__( 'Separator', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'star' => esc_html__( 'Asterisk icon (default)', 'atelier-b2b' ),
				'text' => esc_html__( 'Custom symbol / text', 'atelier-b2b' ),
				'none' => esc_html__( 'None', 'atelier-b2b' ),
			),
			'default' => 'star',
		) );

		$this->add_control( 'separator_text', array(
			'label'     => esc_html__( 'Symbol', 'atelier-b2b' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => '✳',
			'condition' => array( 'separator' => 'text' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_motion', array( 'label' => esc_html__( 'Animation', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );

		$this->add_control( 'speed', array(
			'label'   => esc_html__( 'Seconds per loop (lower = faster)', 'atelier-b2b' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 5,
			'max'     => 400,
			'default' => 60,
		) );

		$this->add_control( 'direction', array(
			'label'   => esc_html__( 'Direction', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'left'  => esc_html__( 'Right to left (default)', 'atelier-b2b' ),
				'right' => esc_html__( 'Left to right', 'atelier-b2b' ),
			),
			'default' => 'left',
		) );

		$this->add_control( 'pause_hover', array(
			'label'        => esc_html__( 'Pause on hover', 'atelier-b2b' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->end_controls_section();

		/* ---- Style ---- */
		$this->start_controls_section( 'section_style_box', array( 'label' => esc_html__( 'Section', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );

		$this->add_control( 'bg_color', array(
			'label'     => esc_html__( 'Background color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .trust' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_responsive_control( 'padding', array(
			'label'      => esc_html__( 'Padding', 'atelier-b2b' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'rem', '%' ),
			'selectors'  => array( '{{WRAPPER}} .trust' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_text', array( 'label' => esc_html__( 'Text', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );

		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'text_typography', 'label' => esc_html__( 'Marquee typography', 'atelier-b2b' ), 'selector' => '{{WRAPPER}} .marquee__text' ) );
		$this->add_control( 'text_color', array( 'label' => esc_html__( 'Marquee text color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .marquee__text' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'label_typography', 'label' => esc_html__( 'Label typography', 'atelier-b2b' ), 'selector' => '{{WRAPPER}} .trust__label', 'separator' => 'before' ) );
		$this->add_control( 'label_color', array( 'label' => esc_html__( 'Label color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .trust__label' => 'color: {{VALUE}};' ) ) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_sep', array( 'label' => esc_html__( 'Separator', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );

		$this->add_control( 'sep_color', array( 'label' => esc_html__( 'Color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .marquee .sep' => 'color: {{VALUE}};' ) ) );
		$this->add_responsive_control( 'sep_size', array(
			'label'      => esc_html__( 'Size', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem' ),
			'range'      => array( 'px' => array( 'min' => 10, 'max' => 120 ), 'rem' => array( 'min' => 0.5, 'max' => 8, 'step' => 0.1 ) ),
			'selectors'  => array( '{{WRAPPER}} .marquee .sep' => 'font-size: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'sep_space', array(
			'label'      => esc_html__( 'Spacing', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'      => array( 'px' => array( 'min' => 4, 'max' => 160 ) ),
			'selectors'  => array( '{{WRAPPER}} .marquee .sep' => 'margin-inline: {{SIZE}}{{UNIT}};' ),
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

		if ( 'none' === $s['separator'] ) {
			$sep = '';
		} elseif ( 'text' === $s['separator'] && '' !== trim( (string) $s['separator_text'] ) ) {
			$sep = $s['separator_text'];
		} else {
			$sep = null; // Default asterisk icon.
		}

		atelier_trust_section(
			array(
				'label'     => $s['label'],
				'items'     => $items,
				'speed'     => max( 5, (int) $s['speed'] ),
				'repeat'    => 2,
				'direction' => $s['direction'],
				'separator' => $sep,
				'pause'     => 'yes' === $s['pause_hover'],
			)
		);
	}
}
