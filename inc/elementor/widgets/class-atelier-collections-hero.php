<?php
/**
 * Elementor widget: Atelier Collections Hero (editorial page introduction).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Atelier_Collections_Hero_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-collections-hero';
	}

	public function get_title() {
		return esc_html__( 'Atelier Collections Hero', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_style_depends() {
		return array( 'atelier-collections' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'eyebrow', array( 'label' => esc_html__( 'Eyebrow', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'The Atelier Edit', 'atelier-b2b' ), 'label_block' => true ) );
		$this->add_control( 'title', array( 'label' => esc_html__( 'Headline', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => "EXPLORE OUR\nCOLLECTIONS." ) );
		$this->add_control( 'text', array( 'label' => esc_html__( 'Description', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => esc_html__( 'A considered selection of apparel, crafted for brands that value design, quality, and individuality.', 'atelier-b2b' ) ) );
		$this->add_control( 'scroll', array( 'label' => esc_html__( 'Scroll indicator text (empty = hide)', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Scroll', 'atelier-b2b' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_images', array( 'label' => esc_html__( 'Images', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'image_a', array( 'label' => esc_html__( 'Large portrait image', 'atelier-b2b' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => '' ) ) );
		$this->add_control( 'image_b', array( 'label' => esc_html__( 'Small landscape image', 'atelier-b2b' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => '' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style', array( 'label' => esc_html__( 'Style', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'bg', array( 'label' => esc_html__( 'Background', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .chero' => 'background-color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'title_typo', 'label' => esc_html__( 'Headline', 'atelier-b2b' ), 'selector' => '{{WRAPPER}} .chero__title' ) );
		$this->add_control( 'title_color', array( 'label' => esc_html__( 'Headline color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .chero__title' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'eyebrow_color', array( 'label' => esc_html__( 'Eyebrow color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .chero__eyebrow' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'text_color', array( 'label' => esc_html__( 'Description color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .chero__desc' => 'color: {{VALUE}};' ) ) );
		$this->add_responsive_control( 'pad', array( 'label' => esc_html__( 'Padding', 'atelier-b2b' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'rem', 'vh' ), 'selectors' => array( '{{WRAPPER}} .chero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s  = $this->get_settings_for_display();
		$a  = '';
		$b  = '';
		if ( ! atelier_is_collections_latest_layout() ) {
			$ia = ! empty( $s['image_a']['url'] ) ? $s['image_a'] : array( 'url' => atelier_asset_url( 'showcase_01' ) );
			$ib = ! empty( $s['image_b']['url'] ) ? $s['image_b'] : array( 'url' => atelier_asset_url( 'showcase_03' ) );
			$a  = atelier_media_img( $ia, array( 'class' => 'chero__img', 'loading' => 'eager', 'sizes' => '(min-width:1024px) 34vw, 90vw' ), 'showcase_01' );
			$b  = atelier_media_img( $ib, array( 'class' => 'chero__img', 'sizes' => '(min-width:1024px) 28vw, 70vw' ), 'showcase_03' );
		}

		get_template_part( 'template-parts/collections/collection-hero', null, array(
			'eyebrow'      => $s['eyebrow'],
			'title'        => $s['title'],
			'text'         => $s['text'],
			'scroll'       => $s['scroll'],
			'anchor'       => '#collection-grid',
			'image_a_html' => $a,
			'image_b_html' => $b,
		) );
	}
}
