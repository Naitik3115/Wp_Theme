<?php
/**
 * Elementor widget: About Us hero.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Atelier_About_Hero_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-about-hero';
	}

	public function get_title() {
		return esc_html__( 'Atelier About Hero', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'about', 'hero', 'atelier' );
	}

	protected function register_controls() {
		$d = atelier_content( 'about' );
		$d = isset( $d['hero'] ) ? $d['hero'] : array();

		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'eyebrow', array( 'label' => esc_html__( 'Eyebrow', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => $d['eyebrow'], 'label_block' => true ) );
		$this->add_control( 'heading', array( 'label' => esc_html__( 'Heading', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => $d['title'], 'label_block' => true ) );
		$this->add_control( 'description', array( 'label' => esc_html__( 'Supporting text', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => $d['sub'], 'label_block' => true ) );
		$this->add_control( 'scroll_label', array( 'label' => esc_html__( 'Scroll label', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => $d['scroll'] ) );
		$this->add_control( 'cta1_text', array( 'label' => esc_html__( 'Primary button', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => $d['cta_1']['label'] ) );
		$this->add_control( 'cta1_link', array( 'label' => esc_html__( 'Primary URL', 'atelier-b2b' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => $d['cta_1']['url'] ) ) );
		$this->add_control( 'cta2_text', array( 'label' => esc_html__( 'Secondary button', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => $d['cta_2']['label'] ) );
		$this->add_control( 'cta2_link', array( 'label' => esc_html__( 'Secondary URL', 'atelier-b2b' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => $d['cta_2']['url'] ) ) );
		$this->add_control( 'image', array( 'label' => esc_html__( 'Background image (optional; replaces the homepage video)', 'atelier-b2b' ), 'type' => Controls_Manager::MEDIA, 'default' => array() ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style', array( 'label' => esc_html__( 'Style', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_responsive_control( 'section_padding', array( 'label' => esc_html__( 'Content padding', 'atelier-b2b' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'rem', '%' ), 'selectors' => array( '{{WRAPPER}} .hero__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_control( 'heading_color', array( 'label' => esc_html__( 'Heading color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .hero__title' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'heading_typography', 'selector' => '{{WRAPPER}} .hero__title' ) );
		$this->add_control( 'overlay_note', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => esc_html__( 'The homepage video, poster and overlay are used by default. Choose a background image above to replace the video.', 'atelier-b2b' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$link1 = isset( $s['cta1_link'] ) && is_array( $s['cta1_link'] ) ? $s['cta1_link'] : array();
		$link2 = isset( $s['cta2_link'] ) && is_array( $s['cta2_link'] ) ? $s['cta2_link'] : array();
		atelier_about_hero_section(
			array(
				'eyebrow'    => isset( $s['eyebrow'] ) ? $s['eyebrow'] : '',
				'title'      => isset( $s['heading'] ) ? $s['heading'] : '',
				'sub'        => isset( $s['description'] ) ? $s['description'] : '',
				'scroll'     => isset( $s['scroll_label'] ) ? $s['scroll_label'] : '',
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
				'image'      => isset( $s['image'] ) ? $s['image'] : array(),
				'section_id' => 'about-hero-' . sanitize_html_class( $this->get_id() ),
			)
		);
	}
}
