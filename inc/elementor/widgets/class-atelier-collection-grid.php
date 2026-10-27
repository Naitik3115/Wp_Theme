<?php
/**
 * Elementor widget: Atelier Collection Grid – dynamic (from the Atelier Products CPT), filterable, with Quick View.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Atelier_Collection_Grid_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-collection-grid';
	}

	public function get_title() {
		return esc_html__( 'Atelier Collection Grid', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'collection', 'products', 'grid', 'filter', 'quick view', 'atelier' );
	}

	public function get_style_depends() {
		return array( 'atelier-collections' );
	}

	public function get_script_depends() {
		return array( 'atelier-collections' );
	}

	private function term_options() {
		$out   = array();
		$terms = get_terms( array( 'taxonomy' => 'atelier_collection', 'hide_empty' => false ) );
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				$out[ $t->slug ] = $t->name;
			}
		}
		return $out;
	}

	protected function register_controls() {

		/* ---- Heading ---- */
		$this->start_controls_section( 'section_heading', array( 'label' => esc_html__( 'Heading (optional)', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'label', array( 'label' => esc_html__( 'Eyebrow', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'label_block' => true ) );
		$this->add_control( 'title', array( 'label' => esc_html__( 'Heading (line breaks = lines)', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3 ) );
		$this->add_control( 'text', array( 'label' => esc_html__( 'Description', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3 ) );
		$this->end_controls_section();

		/* ---- Query ---- */
		$this->start_controls_section( 'section_query', array( 'label' => esc_html__( 'Products', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );

		$this->add_control( 'terms', array(
			'label'       => esc_html__( 'Show only these categories', 'atelier-b2b' ),
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'options'     => $this->term_options(),
			'label_block' => true,
			'description' => esc_html__( 'Empty = all categories. New products appear automatically.', 'atelier-b2b' ),
		) );

		$this->add_control( 'orderby', array(
			'label'   => esc_html__( 'Order by', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'menu'     => esc_html__( 'Custom order (Order field)', 'atelier-b2b' ),
				'featured' => esc_html__( 'Featured first', 'atelier-b2b' ),
				'newest'   => esc_html__( 'Newest first', 'atelier-b2b' ),
				'oldest'   => esc_html__( 'Oldest first', 'atelier-b2b' ),
				'title'    => esc_html__( 'Title A–Z', 'atelier-b2b' ),
			),
			'default' => 'menu',
		) );

		$this->add_control( 'per_page', array( 'label' => esc_html__( 'Products per page / per "Load more"', 'atelier-b2b' ), 'type' => Controls_Manager::NUMBER, 'min' => 1, 'max' => 60, 'default' => 9 ) );
		$this->add_control( 'load_more', array( 'label' => esc_html__( 'Load more button', 'atelier-b2b' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ) );
		$this->add_control( 'more_label', array( 'label' => esc_html__( 'Load more text', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Load more', 'atelier-b2b' ), 'condition' => array( 'load_more' => 'yes' ) ) );

		$this->end_controls_section();

		/* ---- Display ---- */
		$this->start_controls_section( 'section_display', array( 'label' => esc_html__( 'Display', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );

		$this->add_control( 'filters', array( 'label' => esc_html__( 'Category filter bar', 'atelier-b2b' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ) );
		$this->add_control( 'all_label', array( 'label' => esc_html__( '"All" label', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'All Collections', 'atelier-b2b' ), 'condition' => array( 'filters' => 'yes' ) ) );
		$this->add_control( 'sticky', array( 'label' => esc_html__( 'Sticky filter bar', 'atelier-b2b' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes', 'condition' => array( 'filters' => 'yes' ) ) );
		$this->add_control( 'hide_empty', array( 'label' => esc_html__( 'Hide categories without products', 'atelier-b2b' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '', 'condition' => array( 'filters' => 'yes' ) ) );
		$this->add_control( 'count', array( 'label' => esc_html__( 'Show "Showing X of Y"', 'atelier-b2b' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ) );
		$this->add_control( 'quick', array( 'label' => esc_html__( 'Quick View', 'atelier-b2b' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ) );
		$this->add_control( 'inquiry', array( 'label' => esc_html__( 'Inquiry page URL', 'atelier-b2b' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '' ), 'description' => esc_html__( 'Empty = /contact/. The product reference is added as ?product=…', 'atelier-b2b' ), 'condition' => array( 'quick' => 'yes' ) ) );
		$this->add_control( 'ref', array( 'label' => esc_html__( 'Show product reference', 'atelier-b2b' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ) );
		$this->add_control( 'season', array( 'label' => esc_html__( 'Show collection / season label', 'atelier-b2b' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ) );
		$this->add_control( 'featured', array(
			'label'   => esc_html__( 'Featured products', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array( 'wide' => esc_html__( 'Large wide cards', 'atelier-b2b' ), 'none' => esc_html__( 'Same as others', 'atelier-b2b' ) ),
			'default' => 'wide',
		) );
		$this->add_control( 'empty_title', array( 'label' => esc_html__( 'Empty state title', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Nothing here yet.', 'atelier-b2b' ), 'separator' => 'before' ) );
		$this->add_control( 'empty_text', array( 'label' => esc_html__( 'Empty state text', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => esc_html__( 'There are no products in this category at the moment. Please explore another collection.', 'atelier-b2b' ) ) );

		$this->end_controls_section();

		/* ---- Layout (style) ---- */
		$this->start_controls_section( 'section_layout', array( 'label' => esc_html__( 'Grid layout', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );

		$cols = array( '1' => '1', '2' => '2', '3' => '3', '4' => '4' );
		$this->add_control( 'cols_d', array( 'label' => esc_html__( 'Columns – desktop', 'atelier-b2b' ), 'type' => Controls_Manager::SELECT, 'options' => $cols, 'default' => '3', 'selectors' => array( '{{WRAPPER}} .cgrid' => '--cols-d: {{VALUE}};' ) ) );
		$this->add_control( 'cols_t', array( 'label' => esc_html__( 'Columns – tablet', 'atelier-b2b' ), 'type' => Controls_Manager::SELECT, 'options' => $cols, 'default' => '2', 'selectors' => array( '{{WRAPPER}} .cgrid' => '--cols-t: {{VALUE}};' ) ) );
		$this->add_control( 'cols_m', array( 'label' => esc_html__( 'Columns – mobile', 'atelier-b2b' ), 'type' => Controls_Manager::SELECT, 'options' => array( '1' => '1', '2' => '2' ), 'default' => '1', 'selectors' => array( '{{WRAPPER}} .cgrid' => '--cols-m: {{VALUE}};' ) ) );

		$this->add_control( 'aspect', array(
			'label'     => esc_html__( 'Image aspect ratio', 'atelier-b2b' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array( '4 / 5' => '4:5 (default)', '3 / 4' => '3:4', '2 / 3' => '2:3', '1 / 1' => '1:1', '4 / 3' => '4:3' ),
			'default'   => '4 / 5',
			'selectors' => array( '{{WRAPPER}} .cgrid' => '--aspect: {{VALUE}};' ),
		) );
		$this->add_control( 'aspect_wide', array(
			'label'     => esc_html__( 'Featured card aspect ratio', 'atelier-b2b' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array( '3 / 2' => '3:2 (default)', '16 / 9' => '16:9', '4 / 3' => '4:3', '4 / 5' => '4:5' ),
			'default'   => '3 / 2',
			'selectors' => array( '{{WRAPPER}} .cgrid' => '--aspect-wide: {{VALUE}};' ),
		) );
		$this->add_responsive_control( 'gap', array(
			'label'      => esc_html__( 'Gap', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem' ),
			'range'      => array( 'px' => array( 'min' => 0, 'max' => 120 ), 'rem' => array( 'min' => 0, 'max' => 8, 'step' => 0.25 ) ),
			'selectors'  => array( '{{WRAPPER}} .cgrid__list' => 'gap: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_control( 'bg', array( 'label' => esc_html__( 'Section background', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .cgrid' => 'background-color: {{VALUE}};' ) ) );
		$this->add_responsive_control( 'pad', array( 'label' => esc_html__( 'Section padding', 'atelier-b2b' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'rem', '%' ), 'selectors' => array( '{{WRAPPER}} .cgrid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );

		$this->end_controls_section();

		/* ---- Typography / colors ---- */
		$this->start_controls_section( 'section_style_text', array( 'label' => esc_html__( 'Typography & colors', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );

		$items = array(
			'heading' => array( esc_html__( 'Heading', 'atelier-b2b' ), '.cgrid__title' ),
			'filter'  => array( esc_html__( 'Filter links', 'atelier-b2b' ), '.cfilter__btn' ),
			'title'   => array( esc_html__( 'Product name', 'atelier-b2b' ), '.pcard__title' ),
			'cat'     => array( esc_html__( 'Product category line', 'atelier-b2b' ), '.pcard__cat' ),
		);
		foreach ( $items as $key => $it ) {
			$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => $key . '_typo', 'label' => $it[0], 'selector' => '{{WRAPPER}} ' . $it[1] ) );
			$this->add_control( $key . '_color', array( 'label' => $it[0] . ' ' . esc_html__( 'color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} ' . $it[1] => 'color: {{VALUE}};' ) ) );
		}
		$this->add_control( 'filter_active', array( 'label' => esc_html__( 'Active filter & underline color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .cfilter__btn.is-active' => 'color: {{VALUE}};', '{{WRAPPER}} .cfilter__ind' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'qv_bg', array( 'label' => esc_html__( 'Quick View pill background', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .pcard__qv' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'qv_color', array( 'label' => esc_html__( 'Quick View pill text', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .pcard__qv' => 'color: {{VALUE}};' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$y = function ( $k ) use ( $s ) {
			return isset( $s[ $k ] ) && 'yes' === $s[ $k ];
		};

		atelier_collection_render( array(
			'label'       => $s['label'],
			'title'       => $s['title'],
			'text'        => $s['text'],
			'terms'       => ! empty( $s['terms'] ) ? (array) $s['terms'] : array(),
			'orderby'     => $s['orderby'],
			'per_page'    => (int) $s['per_page'],
			'cols_d'      => (int) ( $s['cols_d'] ?? 3 ),
			'featured'    => $s['featured'],
			'filters'     => $y( 'filters' ),
			'sticky'      => $y( 'sticky' ),
			'count'       => $y( 'count' ),
			'quick'       => $y( 'quick' ),
			'load_more'   => $y( 'load_more' ),
			'ref'         => $y( 'ref' ),
			'season'      => $y( 'season' ),
			'hide_empty'  => $y( 'hide_empty' ),
			'inquiry'     => ! empty( $s['inquiry']['url'] ) ? $s['inquiry']['url'] : '',
			'all_label'   => $s['all_label'],
			'more_label'  => $s['more_label'],
			'empty_title' => $s['empty_title'],
			'empty_text'  => $s['empty_text'],
		) );
	}
}
