<?php
/**
 * Elementor widget: Contact page (hero, details, form).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

class Atelier_Contact_Form_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-contact-form';
	}

	public function get_title() {
		return esc_html__( 'Atelier Contact Page', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'contact', 'form', 'atelier' );
	}

	protected function register_controls() {
		$defaults = atelier_content( 'contact' );
		$footer   = atelier_content( 'footer' );

		$this->start_controls_section(
			'section_hero',
			array(
				'label' => esc_html__( 'Hero', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control( 'heading', array(
			'label'       => esc_html__( 'Heading', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => $defaults['title'],
			'label_block' => true,
		) );

		$social_rep = new Repeater();
		$social_rep->add_control( 'label', array(
			'label'   => esc_html__( 'Label', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Instagram',
		) );
		$social_rep->add_control( 'url', array(
			'label'       => esc_html__( 'URL', 'atelier-b2b' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => 'https://',
		) );
		$social_defaults = array();
		foreach ( $footer['social'] as $s ) {
			$social_defaults[] = array(
				'label' => $s['label'],
				'url'   => array( 'url' => $s['url'] ),
			);
		}
		$this->add_control( 'social', array(
			'label'       => esc_html__( 'Social links', 'atelier-b2b' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $social_rep->get_controls(),
			'default'     => $social_defaults,
			'title_field' => '{{{ label }}}',
		) );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_intro',
			array(
				'label' => esc_html__( 'Get In Touch', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control( 'intro_title', array(
			'label'   => esc_html__( 'Title', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $defaults['intro_title'],
		) );
		$this->add_control( 'intro_text', array(
			'label'       => esc_html__( 'Text', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => $defaults['intro_text'],
			'label_block' => true,
		) );
		$this->add_control( 'email', array(
			'label'   => esc_html__( 'Email', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $footer['email'],
		) );
		$this->add_control( 'phone', array(
			'label'   => esc_html__( 'Phone', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $footer['phone'],
		) );
		$this->add_control( 'location', array(
			'label'   => esc_html__( 'Location', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $footer['location'],
		) );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_form',
			array(
				'label' => esc_html__( 'Form', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control( 'button_text', array(
			'label'   => esc_html__( 'Submit button', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $defaults['button'],
		) );
		$this->add_control( 'form_source', array(
			'label'   => esc_html__( 'Form source', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'built_in'  => esc_html__( 'Theme built-in form', 'atelier-b2b' ),
				'shortcode' => esc_html__( 'Shortcode (Elementor Form / CF7 / WPForms)', 'atelier-b2b' ),
			),
			'default' => 'built_in',
		) );
		$this->add_control( 'form_shortcode', array(
			'label'       => esc_html__( 'Form shortcode', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'placeholder' => '[contact-form-7 id="123"]',
			'condition'   => array( 'form_source' => 'shortcode' ),
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
			'selectors'  => array( '{{WRAPPER}} .contact-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );
		$this->add_control( 'background_color', array(
			'label'     => esc_html__( 'Background', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .contact-page' => 'background-color: {{VALUE}};' ),
		) );
		$this->add_control( 'heading_color', array(
			'label'     => esc_html__( 'Hero heading color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .contact-page__title' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typography',
			'selector' => '{{WRAPPER}} .contact-page__title',
		) );
		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$fields = atelier_content( 'contact' );
		$fields = isset( $fields['fields'] ) ? $fields['fields'] : array();
		$social = array();

		if ( ! empty( $s['social'] ) && is_array( $s['social'] ) ) {
			foreach ( $s['social'] as $row ) {
				$url = ! empty( $row['url']['url'] ) ? $row['url']['url'] : ( ! empty( $row['url'] ) && is_string( $row['url'] ) ? $row['url'] : '#' );
				if ( empty( $row['label'] ) ) {
					continue;
				}
				$social[] = array( 'label' => $row['label'], 'url' => $url );
			}
		}

		atelier_contact_form_section(
			array(
				'title'          => isset( $s['heading'] ) ? $s['heading'] : '',
				'intro_title'    => isset( $s['intro_title'] ) ? $s['intro_title'] : '',
				'intro_text'     => isset( $s['intro_text'] ) ? $s['intro_text'] : '',
				'email'          => isset( $s['email'] ) ? $s['email'] : '',
				'phone'          => isset( $s['phone'] ) ? $s['phone'] : '',
				'location'       => isset( $s['location'] ) ? $s['location'] : '',
				'social'         => $social,
				'button'         => isset( $s['button_text'] ) ? $s['button_text'] : '',
				'fields'         => $fields,
				'form_source'    => ! empty( $s['form_source'] ) ? $s['form_source'] : 'built_in',
				'form_shortcode' => ! empty( $s['form_shortcode'] ) ? $s['form_shortcode'] : '',
				'notice_html'    => atelier_contact_notice(),
				'uid'            => 'contact-' . sanitize_html_class( $this->get_id() ),
			)
		);
	}
}
