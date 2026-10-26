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
		$this->add_control( 'image', array( 'label' => esc_html__( 'Background image', 'atelier-b2b' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => atelier_asset_url( 'hero_poster' ) ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style', array( 'label' => esc_html__( 'Style', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_responsive_control( 'section_padding', array( 'label' => esc_html__( 'Content padding', 'atelier-b2b' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'rem', '%' ), 'selectors' => array( '{{WRAPPER}} .about-hero__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_control( 'heading_color', array( 'label' => esc_html__( 'Heading color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .about-hero__title' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'heading_typography', 'selector' => '{{WRAPPER}} .about-hero__title' ) );
		$this->add_control( 'overlay_note', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => esc_html__( 'Overlay uses the theme gradient. Swap the background image to refresh the hero look.', 'atelier-b2b' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		atelier_about_hero_section(
			array(
				'eyebrow'    => isset( $s['eyebrow'] ) ? $s['eyebrow'] : '',
				'title'      => isset( $s['heading'] ) ? $s['heading'] : '',
				'sub'        => isset( $s['description'] ) ? $s['description'] : '',
				'scroll'     => isset( $s['scroll_label'] ) ? $s['scroll_label'] : '',
				'image'      => isset( $s['image'] ) ? $s['image'] : array(),
				'image_key'  => 'hero_poster',
				'section_id' => 'about-hero-' . sanitize_html_class( $this->get_id() ),
			)
		);
	}
}
