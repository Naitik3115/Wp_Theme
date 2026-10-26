<?php
/**
 * Elementor widget: About Us – Final CTA.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Atelier_About_CTA_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-about-cta';
	}

	public function get_title() {
		return esc_html__( 'Atelier About CTA', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'about', 'cta', 'atelier' );
	}

	protected function register_controls() {
		$d = atelier_content( 'about' );
		$d = isset( $d['cta'] ) ? $d['cta'] : array();

		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'label', array( 'label' => esc_html__( 'Label', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => $d['label'], 'label_block' => true ) );
		$this->add_control( 'heading', array( 'label' => esc_html__( 'Heading', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => $d['title'], 'label_block' => true ) );
		$this->add_control( 'description', array( 'label' => esc_html__( 'Supporting text', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => $d['text'], 'label_block' => true ) );
		$this->add_control( 'cta1_text', array( 'label' => esc_html__( 'Primary button', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => $d['cta_1']['label'] ) );
		$this->add_control( 'cta1_link', array( 'label' => esc_html__( 'Primary URL', 'atelier-b2b' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => $d['cta_1']['url'] ), 'placeholder' => 'https://' ) );
		$this->add_control( 'cta2_text', array( 'label' => esc_html__( 'Secondary button', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => $d['cta_2']['label'] ) );
		$this->add_control( 'cta2_link', array( 'label' => esc_html__( 'Secondary URL', 'atelier-b2b' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => $d['cta_2']['url'] ), 'placeholder' => 'https://' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style', array( 'label' => esc_html__( 'Style', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'background_color', array( 'label' => esc_html__( 'Background', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .about-cta' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'heading_color', array( 'label' => esc_html__( 'Heading color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .about-cta__title' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'heading_typography', 'selector' => '{{WRAPPER}} .about-cta__title' ) );
		$this->add_responsive_control( 'section_padding', array( 'label' => esc_html__( 'Section padding', 'atelier-b2b' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'rem', '%' ), 'selectors' => array( '{{WRAPPER}} .about-cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$link1 = isset( $s['cta1_link'] ) && is_array( $s['cta1_link'] ) ? $s['cta1_link'] : array();
		$link2 = isset( $s['cta2_link'] ) && is_array( $s['cta2_link'] ) ? $s['cta2_link'] : array();

		atelier_about_cta_section(
			array(
				'label'      => isset( $s['label'] ) ? $s['label'] : '',
				'title'      => isset( $s['heading'] ) ? $s['heading'] : '',
				'text'       => isset( $s['description'] ) ? $s['description'] : '',
				'cta_1'      => array(
					'label' => isset( $s['cta1_text'] ) ? $s['cta1_text'] : '',
					'url'   => ! empty( $link1['url'] ) ? $link1['url'] : '',
					'attrs' => atelier_link_attrs( $link1 ),
				),
				'cta_2'      => array(
					'label' => isset( $s['cta2_text'] ) ? $s['cta2_text'] : '',
					'url'   => ! empty( $link2['url'] ) ? $link2['url'] : '',
					'attrs' => atelier_link_attrs( $link2 ),
				),
				'section_id' => 'about-cta-' . sanitize_html_class( $this->get_id() ),
			)
		);
	}
}
