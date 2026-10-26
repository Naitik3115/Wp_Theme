<?php
/**
 * Elementor widget: About Us – Who We Are.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Atelier_About_Who_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-about-who';
	}

	public function get_title() {
		return esc_html__( 'Atelier About Who We Are', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'about', 'who', 'atelier' );
	}

	protected function register_controls() {
		$d = atelier_content( 'about' );
		$d = isset( $d['who'] ) ? $d['who'] : array();

		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'label', array( 'label' => esc_html__( 'Label', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => $d['label'], 'label_block' => true ) );
		$this->add_control( 'heading', array( 'label' => esc_html__( 'Heading', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => $d['title'], 'label_block' => true ) );
		$this->add_control( 'lede', array( 'label' => esc_html__( 'Lede', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => $d['lede'], 'label_block' => true ) );
		$this->add_control( 'description', array( 'label' => esc_html__( 'Body text', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => $d['text'], 'label_block' => true ) );
		$this->add_control( 'aside', array( 'label' => esc_html__( 'Aside caption', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => $d['aside'], 'label_block' => true ) );
		$this->add_control( 'image_1', array( 'label' => esc_html__( 'Primary image', 'atelier-b2b' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => atelier_asset_url( 'editorial' ) ) ) );
		$this->add_control( 'image_2', array( 'label' => esc_html__( 'Secondary image', 'atelier-b2b' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => atelier_asset_url( 'category_women' ) ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style', array( 'label' => esc_html__( 'Style', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'background_color', array( 'label' => esc_html__( 'Background', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .about-who' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'heading_color', array( 'label' => esc_html__( 'Heading color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .about-who__title' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'heading_typography', 'selector' => '{{WRAPPER}} .about-who__title' ) );
		$this->add_responsive_control( 'section_padding', array( 'label' => esc_html__( 'Section padding', 'atelier-b2b' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'rem', '%' ), 'selectors' => array( '{{WRAPPER}} .about-who' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		atelier_about_who_section(
			array(
				'label'      => isset( $s['label'] ) ? $s['label'] : '',
				'title'      => isset( $s['heading'] ) ? $s['heading'] : '',
				'lede'       => isset( $s['lede'] ) ? $s['lede'] : '',
				'text'       => isset( $s['description'] ) ? $s['description'] : '',
				'aside'      => isset( $s['aside'] ) ? $s['aside'] : '',
				'image_1'    => isset( $s['image_1'] ) ? $s['image_1'] : array(),
				'image_2'    => isset( $s['image_2'] ) ? $s['image_2'] : array(),
				'section_id' => 'about-who-' . sanitize_html_class( $this->get_id() ),
			)
		);
	}
}
