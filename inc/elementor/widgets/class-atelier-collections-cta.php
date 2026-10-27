<?php
/**
 * Elementor widget: Atelier Collections CTA (closing inquiry call-to-action).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Atelier_Collections_Cta_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-collections-cta';
	}

	public function get_title() {
		return esc_html__( 'Atelier Collections CTA', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_style_depends() {
		return array( 'atelier-collections' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'label', array( 'label' => esc_html__( 'Eyebrow', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Let’s build something together', 'atelier-b2b' ), 'label_block' => true ) );
		$this->add_control( 'title', array( 'label' => esc_html__( 'Headline', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => "SHARE YOUR\nCONCEPT." ) );
		$this->add_control( 'text', array( 'label' => esc_html__( 'Description', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => esc_html__( 'Tell us what you’re building, and we’ll take it from there.', 'atelier-b2b' ) ) );
		$this->add_control( 'cta_text', array( 'label' => esc_html__( 'Button text', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Start an inquiry', 'atelier-b2b' ) ) );
		$this->add_control( 'cta_link', array( 'label' => esc_html__( 'Button link', 'atelier-b2b' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => home_url( '/contact/' ) ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style', array( 'label' => esc_html__( 'Style', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'bg', array( 'label' => esc_html__( 'Background', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ccta' => 'background-color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'title_typo', 'label' => esc_html__( 'Headline', 'atelier-b2b' ), 'selector' => '{{WRAPPER}} .ccta__title' ) );
		$this->add_control( 'title_color', array( 'label' => esc_html__( 'Headline color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ccta__title' => 'color: {{VALUE}};' ) ) );
		$this->add_responsive_control( 'pad', array( 'label' => esc_html__( 'Padding', 'atelier-b2b' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'rem' ), 'selectors' => array( '{{WRAPPER}} .ccta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		get_template_part( 'template-parts/collections/collection-cta', null, array(
			'label' => $s['label'],
			'title' => $s['title'],
			'text'  => $s['text'],
			'cta'   => array(
				'label' => $s['cta_text'],
				'url'   => ! empty( $s['cta_link']['url'] ) ? $s['cta_link']['url'] : '',
				'attrs' => atelier_link_attrs( $s['cta_link'] ),
			),
		) );
	}
}
