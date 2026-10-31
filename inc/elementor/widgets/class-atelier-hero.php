<?php
/**
 * Elementor widget: Atelier Hero (fullscreen cinematic hero).
 * Renders through atelier_hero_section(), the same function as the static template part.
 * GSAP / CSS animations are the theme's existing ones (animations.js / animations.css).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Atelier_Hero_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-hero';
	}

	public function get_title() {
		return esc_html__( 'Atelier Hero', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'hero', 'banner', 'video', 'atelier' );
	}

	private function typo_and_color( $prefix, $label, $selector ) {
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => $prefix . '_typography',
			'label'    => $label,
			'selector' => $selector,
		) );
		$this->add_control( $prefix . '_color', array(
			/* translators: %s: element name */
			'label'     => sprintf( esc_html__( '%s color', 'atelier-b2b' ), $label ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( $selector => 'color: {{VALUE}};' ),
		) );
	}

	protected function register_controls() {

		/* ================= CONTENT ================= */
		$this->start_controls_section( 'section_text', array( 'label' => esc_html__( 'Text', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );

		$this->add_control( 'eyebrow', array(
			'label'       => esc_html__( 'Eyebrow', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Crafted for the world', 'atelier-b2b' ),
			'label_block' => true,
		) );

		$this->add_control( 'title', array(
			'label'       => esc_html__( 'Headline', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 4,
			'default'     => "WE CREATE\nWHAT BRANDS\nBECOME.",
			'description' => esc_html__( 'Each line break becomes a separate animated line.', 'atelier-b2b' ),
		) );

		$this->add_control( 'sub', array(
			'label'   => esc_html__( 'Subheading', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 3,
			'default' => esc_html__( 'Premium apparel manufacturing, thoughtfully crafted for ambitious brands across the globe.', 'atelier-b2b' ),
		) );

		$this->add_control( 'scroll_label', array(
			'label'       => esc_html__( 'Scroll indicator text (empty = hide)', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Scroll', 'atelier-b2b' ),
			'label_block' => true,
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_buttons', array( 'label' => esc_html__( 'Buttons', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );

		$this->add_control( 'cta1_text', array( 'label' => esc_html__( 'Primary text', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Explore our expertise', 'atelier-b2b' ), 'label_block' => true ) );
		$this->add_control( 'cta1_link', array( 'label' => esc_html__( 'Primary link', 'atelier-b2b' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => home_url( '/our-expertise/' ) ) ) );
		$this->add_control( 'cta2_text', array( 'label' => esc_html__( 'Secondary text (empty = hide)', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Discover collections', 'atelier-b2b' ), 'label_block' => true, 'separator' => 'before' ) );
		$this->add_control( 'cta2_link', array( 'label' => esc_html__( 'Secondary link', 'atelier-b2b' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => home_url( '/collections/' ) ) ) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_background', array( 'label' => esc_html__( 'Background media', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );

		$this->add_control( 'bg_type', array(
			'label'   => esc_html__( 'Type', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'auto'  => esc_html__( 'Auto (video if set, otherwise image)', 'atelier-b2b' ),
				'image' => esc_html__( 'Image only', 'atelier-b2b' ),
				'video' => esc_html__( 'Video (image is the poster)', 'atelier-b2b' ),
			),
			'default' => 'auto',
		) );

		$this->add_control( 'image', array(
			'label'       => esc_html__( 'Desktop / tablet landscape image', 'atelier-b2b' ),
			'type'        => Controls_Manager::MEDIA,
			'default'     => array( 'url' => '' ),
			'description' => esc_html__( 'Used from tablet landscape up (~768px+). Also the video poster fallback. Recommended 1920×1080.', 'atelier-b2b' ),
		) );

		$this->add_control( 'image_mobile', array(
			'label'       => esc_html__( 'Mobile portrait image', 'atelier-b2b' ),
			'type'        => Controls_Manager::MEDIA,
			'default'     => array( 'url' => '' ),
			'description' => esc_html__( 'Shown on phones first. Upload a tall portrait (e.g. 1080×1620) so the hero fills the screen cleanly.', 'atelier-b2b' ),
		) );

		$this->add_control( 'video', array(
			'label'       => esc_html__( 'Background video (MP4, optional)', 'atelier-b2b' ),
			'type'        => Controls_Manager::MEDIA,
			'media_types' => array( 'video' ),
			'default'     => array( 'url' => '' ),
			'description' => esc_html__( 'Muted, looping, autoplay. Keep it under ~6 MB. Video uses the landscape image as poster.', 'atelier-b2b' ),
		) );

		$this->add_control( 'poster', array(
			'label'       => esc_html__( 'Video poster (optional)', 'atelier-b2b' ),
			'type'        => Controls_Manager::MEDIA,
			'default'     => array( 'url' => '' ),
			'description' => esc_html__( 'Leave empty to reuse the landscape image.', 'atelier-b2b' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_motion', array( 'label' => esc_html__( 'Animation', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );

		$this->add_control( 'intro', array(
			'label'        => esc_html__( 'Entrance animation (headline reveal, image scale-in)', 'atelier-b2b' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		) );
		$this->add_control( 'parallax', array(
			'label'        => esc_html__( 'Background parallax on scroll', 'atelier-b2b' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		) );

		$this->end_controls_section();

		/* ================= LAYOUT (style tab) ================= */
		$this->start_controls_section( 'section_layout', array( 'label' => esc_html__( 'Layout', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );

		$this->add_responsive_control( 'min_height', array(
			'label'      => esc_html__( 'Minimum height', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'vh', 'px' ),
			'range'      => array( 'vh' => array( 'min' => 30, 'max' => 100 ), 'px' => array( 'min' => 300, 'max' => 1400 ) ),
			'selectors'  => array( '{{WRAPPER}} .hero' => 'min-height: {{SIZE}}{{UNIT}};' ),
			'description' => esc_html__( 'Empty = full screen height (100svh).', 'atelier-b2b' ),
		) );

		$this->add_responsive_control( 'align', array(
			'label'        => esc_html__( 'Text alignment', 'atelier-b2b' ),
			'type'         => Controls_Manager::CHOOSE,
			'options'      => array(
				'left'   => array( 'title' => esc_html__( 'Left', 'atelier-b2b' ), 'icon' => 'eicon-text-align-left' ),
				'center' => array( 'title' => esc_html__( 'Center', 'atelier-b2b' ), 'icon' => 'eicon-text-align-center' ),
				'right'  => array( 'title' => esc_html__( 'Right', 'atelier-b2b' ), 'icon' => 'eicon-text-align-right' ),
			),
			'prefix_class' => 'atelier-hero%s-align-',
		) );

		$this->add_responsive_control( 'valign', array(
			'label'        => esc_html__( 'Vertical position', 'atelier-b2b' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => array(
				''       => esc_html__( 'Bottom (default)', 'atelier-b2b' ),
				'center' => esc_html__( 'Middle', 'atelier-b2b' ),
				'start'  => esc_html__( 'Top', 'atelier-b2b' ),
			),
			'prefix_class' => 'atelier-hero%s-valign-',
		) );

		$this->add_responsive_control( 'content_padding', array(
			'label'      => esc_html__( 'Content padding', 'atelier-b2b' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'rem', 'vh', '%' ),
			'selectors'  => array( '{{WRAPPER}} .hero__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );

		$this->add_responsive_control( 'media_position', array(
			'label'     => esc_html__( 'Image / video focal point', 'atelier-b2b' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				''              => esc_html__( 'Default', 'atelier-b2b' ),
				'center center' => esc_html__( 'Center', 'atelier-b2b' ),
				'center top'    => esc_html__( 'Top center', 'atelier-b2b' ),
				'center 28%'    => esc_html__( 'Upper third (good for mobile)', 'atelier-b2b' ),
				'center bottom' => esc_html__( 'Bottom center', 'atelier-b2b' ),
				'left center'   => esc_html__( 'Left center', 'atelier-b2b' ),
				'right center'  => esc_html__( 'Right center', 'atelier-b2b' ),
			),
			'selectors' => array( '{{WRAPPER}} .hero__img, {{WRAPPER}} .hero__video' => 'object-position: {{VALUE}};' ),
		) );

		$this->end_controls_section();

		/* ================= TYPOGRAPHY / COLORS ================= */
		$this->start_controls_section( 'section_style_text', array( 'label' => esc_html__( 'Text', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->typo_and_color( 'eyebrow', esc_html__( 'Eyebrow', 'atelier-b2b' ), '{{WRAPPER}} .hero__eyebrow' );
		$this->typo_and_color( 'title', esc_html__( 'Headline', 'atelier-b2b' ), '{{WRAPPER}} .hero__title' );
		$this->typo_and_color( 'sub', esc_html__( 'Subheading', 'atelier-b2b' ), '{{WRAPPER}} .hero__sub' );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style_buttons', array( 'label' => esc_html__( 'Buttons', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'btn_typography', 'selector' => '{{WRAPPER}} .hero__ctas .btn' ) );
		$this->add_control( 'btn1_bg', array( 'label' => esc_html__( 'Primary background', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .btn--light' => 'background-color: {{VALUE}}; border-color: {{VALUE}};' ) ) );
		$this->add_control( 'btn1_color', array( 'label' => esc_html__( 'Primary text', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .btn--light' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'btn2_color', array( 'label' => esc_html__( 'Secondary text & border', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .btn--outline-light' => 'color: {{VALUE}}; border-color: {{VALUE}};' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style_overlay', array( 'label' => esc_html__( 'Overlay', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'overlay_top', array(
			'label'       => esc_html__( 'Top color (use the alpha slider for opacity)', 'atelier-b2b' ),
			'type'        => Controls_Manager::COLOR,
			'selectors'   => array( '{{WRAPPER}} .hero__overlay' => '--atelier-ov-top: {{VALUE}};' ),
		) );
		$this->add_control( 'overlay_bottom', array(
			'label'       => esc_html__( 'Bottom color (use the alpha slider for opacity)', 'atelier-b2b' ),
			'type'        => Controls_Manager::COLOR,
			'selectors'   => array( '{{WRAPPER}} .hero__overlay' => '--atelier-ov-bottom: {{VALUE}};' ),
		) );
		$this->add_control( 'overlay_opacity', array(
			'label'      => esc_html__( 'Overall overlay opacity', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => array( 'px' => array( 'min' => 0, 'max' => 1, 'step' => 0.05 ) ),
			'selectors'  => array( '{{WRAPPER}} .hero__overlay' => 'opacity: {{SIZE}};' ),
		) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$type      = $s['bg_type'];
		$video_url = ( 'image' !== $type && ! empty( $s['video']['url'] ) ) ? $s['video']['url'] : '';
		$landscape = ! empty( $s['image']['url'] ) || ! empty( $s['image']['id'] ) ? $s['image'] : 'hero_poster';
		$portrait  = ! empty( $s['image_mobile']['url'] ) || ! empty( $s['image_mobile']['id'] ) ? $s['image_mobile'] : ( atelier_asset_exists( 'hero_poster_mobile' ) ? 'hero_poster_mobile' : 'category_women' );
		$bg_html   = atelier_hero_responsive_bg_html( $landscape, $portrait );
		$poster_url = ! empty( $s['poster']['url'] ) ? $s['poster']['url'] : ( ! empty( $s['image']['url'] ) ? $s['image']['url'] : atelier_asset_url( 'hero_poster' ) );

		atelier_hero_section(
			array(
				'eyebrow'      => $s['eyebrow'],
				'title'        => $s['title'],
				'sub'          => $s['sub'],
				'cta_1'        => array( 'label' => $s['cta1_text'], 'url' => ! empty( $s['cta1_link']['url'] ) ? $s['cta1_link']['url'] : '', 'attrs' => atelier_link_attrs( $s['cta1_link'] ) ),
				'cta_2'        => array( 'label' => $s['cta2_text'], 'url' => ! empty( $s['cta2_link']['url'] ) ? $s['cta2_link']['url'] : '', 'attrs' => atelier_link_attrs( $s['cta2_link'] ) ),
				'bg_html'      => $bg_html,
				'video_url'    => $video_url,
				'poster_url'   => $poster_url,
				'scroll_label' => $s['scroll_label'],
				'parallax'     => 'yes' === $s['parallax'],
				'intro'        => 'yes' === $s['intro'],
			)
		);
	}
}
