<?php
/**
 * Elementor widget: About Us – Our Commitment.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

class Atelier_About_Commitment_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-about-commitment';
	}

	public function get_title() {
		return esc_html__( 'Atelier About Commitment', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-check-circle';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'about', 'commitment', 'atelier' );
	}

	protected function register_controls() {
		$d = atelier_content( 'about' );
		$d = isset( $d['commitment'] ) ? $d['commitment'] : array();

		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'label', array( 'label' => esc_html__( 'Label', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => $d['label'], 'label_block' => true ) );
		$this->add_control( 'heading', array( 'label' => esc_html__( 'Heading', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => $d['title'], 'label_block' => true ) );
		$this->add_control( 'description', array( 'label' => esc_html__( 'Body text', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => $d['text'], 'label_block' => true ) );
		$this->add_control( 'image', array( 'label' => esc_html__( 'Image', 'atelier-b2b' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => atelier_asset_url( 'category_men' ) ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_points', array( 'label' => esc_html__( 'Focus points', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$rep = new Repeater();
		$rep->add_control( 'text', array( 'label' => esc_html__( 'Point', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => '', 'label_block' => true ) );
		$defaults = array();
		foreach ( isset( $d['points'] ) ? $d['points'] : array() as $point ) {
			$defaults[] = array( 'text' => $point );
		}
		$this->add_control( 'points', array( 'label' => esc_html__( 'Points', 'atelier-b2b' ), 'type' => Controls_Manager::REPEATER, 'fields' => $rep->get_controls(), 'default' => $defaults, 'title_field' => '{{{ text }}}' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style', array( 'label' => esc_html__( 'Style', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'panel_bg', array( 'label' => esc_html__( 'Content panel background', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .about-commit__body' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'heading_color', array( 'label' => esc_html__( 'Heading color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .about-commit__title' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'heading_typography', 'selector' => '{{WRAPPER}} .about-commit__title' ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$points = array();
		if ( ! empty( $s['points'] ) && is_array( $s['points'] ) ) {
			foreach ( $s['points'] as $row ) {
				if ( ! empty( $row['text'] ) ) {
					$points[] = $row['text'];
				}
			}
		}
		atelier_about_commitment_section(
			array(
				'label'      => isset( $s['label'] ) ? $s['label'] : '',
				'title'      => isset( $s['heading'] ) ? $s['heading'] : '',
				'text'       => isset( $s['description'] ) ? $s['description'] : '',
				'points'     => $points,
				'image'      => isset( $s['image'] ) ? $s['image'] : array(),
				'section_id' => 'about-commitment-' . sanitize_html_class( $this->get_id() ),
			)
		);
	}
}
