<?php
/**
 * Elementor widget: Our Offices (address + map).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Atelier_Offices_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-offices';
	}

	public function get_title() {
		return esc_html__( 'Atelier Offices', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'office', 'map', 'address', 'contact', 'atelier' );
	}

	protected function register_controls() {
		$defaults = atelier_content( 'offices' );

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control( 'heading', array(
			'label'   => esc_html__( 'Heading', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $defaults['title'],
		) );
		$this->add_control( 'address_label', array(
			'label'   => esc_html__( 'Address label', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $defaults['address_label'],
		) );
		$this->add_control( 'address', array(
			'label'       => esc_html__( 'Address', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => $defaults['address'],
			'label_block' => true,
		) );
		$this->add_control( 'map_query', array(
			'label'       => esc_html__( 'Map search query', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => $defaults['map_query'],
			'description' => esc_html__( 'Used to build a Google Maps embed (e.g. “Dhanmondi, Dhaka”).', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$this->add_control( 'map_embed', array(
			'label'       => esc_html__( 'Custom map embed (optional)', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'placeholder' => 'https://maps.google.com/maps?q=…&output=embed',
			'description' => esc_html__( 'Paste a Maps embed URL or full iframe HTML to override the query.', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control( 'section_padding', array(
			'label'      => esc_html__( 'Section padding', 'atelier-b2b' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'rem', '%' ),
			'selectors'  => array( '{{WRAPPER}} .offices' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );
		$this->add_control( 'background_color', array(
			'label'     => esc_html__( 'Background', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .offices' => 'background-color: {{VALUE}};' ),
		) );
		$this->add_control( 'heading_color', array(
			'label'     => esc_html__( 'Heading color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .offices .contact-page__heading' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typography',
			'selector' => '{{WRAPPER}} .offices .contact-page__heading',
		) );
		$this->add_responsive_control( 'map_height', array(
			'label'      => esc_html__( 'Map height', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'vh', 'rem' ),
			'range'      => array(
				'px' => array( 'min' => 180, 'max' => 720 ),
				'vh' => array( 'min' => 20, 'max' => 80 ),
			),
			'selectors'  => array( '{{WRAPPER}} .offices__map' => 'min-height: {{SIZE}}{{UNIT}};' ),
		) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		atelier_offices_section(
			array(
				'title'         => isset( $s['heading'] ) ? $s['heading'] : '',
				'address_label' => isset( $s['address_label'] ) ? $s['address_label'] : '',
				'address'       => isset( $s['address'] ) ? $s['address'] : '',
				'map_query'     => isset( $s['map_query'] ) ? $s['map_query'] : '',
				'map_embed'     => isset( $s['map_embed'] ) ? $s['map_embed'] : '',
			)
		);
	}
}
