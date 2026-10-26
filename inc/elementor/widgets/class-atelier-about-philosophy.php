<?php
/**
 * Elementor widget: About Us – Our Philosophy.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

class Atelier_About_Philosophy_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-about-philosophy';
	}

	public function get_title() {
		return esc_html__( 'Atelier About Philosophy', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-posts-masonry';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'about', 'philosophy', 'pillars', 'atelier' );
	}

	protected function register_controls() {
		$d = atelier_content( 'about' );
		$d = isset( $d['philosophy'] ) ? $d['philosophy'] : array();

		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'label', array( 'label' => esc_html__( 'Label', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => $d['label'], 'label_block' => true ) );
		$this->add_control( 'heading', array( 'label' => esc_html__( 'Heading', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => $d['title'], 'label_block' => true ) );
		$this->add_control( 'description', array( 'label' => esc_html__( 'Intro text', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => $d['text'], 'label_block' => true ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_pillars', array( 'label' => esc_html__( 'Pillars', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$rep = new Repeater();
		$rep->add_control( 'index', array( 'label' => esc_html__( 'Index', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => '01' ) );
		$rep->add_control( 'title', array( 'label' => esc_html__( 'Title', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => '', 'label_block' => true ) );
		$rep->add_control( 'text', array( 'label' => esc_html__( 'Text', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => '', 'label_block' => true ) );
		$rep->add_control( 'image', array( 'label' => esc_html__( 'Image', 'atelier-b2b' ), 'type' => Controls_Manager::MEDIA ) );

		$defaults = array();
		foreach ( isset( $d['pillars'] ) ? $d['pillars'] : array() as $p ) {
			$defaults[] = array(
				'index' => $p['index'],
				'title' => $p['title'],
				'text'  => $p['text'],
				'image' => array( 'url' => atelier_asset_url( $p['image'] ) ),
			);
		}
		$this->add_control( 'pillars', array( 'label' => esc_html__( 'Pillars', 'atelier-b2b' ), 'type' => Controls_Manager::REPEATER, 'fields' => $rep->get_controls(), 'default' => $defaults, 'title_field' => '{{{ title }}}' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style', array( 'label' => esc_html__( 'Style', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'background_color', array( 'label' => esc_html__( 'Background', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .about-phil' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'heading_color', array( 'label' => esc_html__( 'Heading color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .about-phil__title' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'heading_typography', 'selector' => '{{WRAPPER}} .about-phil__title' ) );
		$this->add_responsive_control( 'section_padding', array( 'label' => esc_html__( 'Section padding', 'atelier-b2b' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'rem', '%' ), 'selectors' => array( '{{WRAPPER}} .about-phil' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$pillars = array();
		if ( ! empty( $s['pillars'] ) && is_array( $s['pillars'] ) ) {
			foreach ( $s['pillars'] as $row ) {
				$pillars[] = array(
					'index' => isset( $row['index'] ) ? $row['index'] : '',
					'title' => isset( $row['title'] ) ? $row['title'] : '',
					'text'  => isset( $row['text'] ) ? $row['text'] : '',
					'image' => isset( $row['image'] ) ? $row['image'] : array(),
				);
			}
		}
		atelier_about_philosophy_section(
			array(
				'label'      => isset( $s['label'] ) ? $s['label'] : '',
				'title'      => isset( $s['heading'] ) ? $s['heading'] : '',
				'text'       => isset( $s['description'] ) ? $s['description'] : '',
				'pillars'    => $pillars,
				'section_id' => 'about-philosophy-' . sanitize_html_class( $this->get_id() ),
			)
		);
	}
}
