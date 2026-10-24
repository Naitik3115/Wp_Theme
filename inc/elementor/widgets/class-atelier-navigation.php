<?php
/**
 * Elementor widget: Atelier Navigation (WordPress menu + hamburger trigger).
 *
 * Reuses the theme's existing navigation classes so the original styling and the
 * 1180px breakpoint apply unchanged. The fullscreen mobile menu itself is rendered by
 * the theme (outside the header) from the same menu; this widget publishes its settings
 * to it through atelier_header_context().
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Atelier_Navigation_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-navigation';
	}

	public function get_title() {
		return esc_html__( 'Atelier Navigation', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'menu', 'nav', 'navigation', 'header', 'atelier' );
	}

	private function get_menu_options() {
		$options = array(
			'location:primary' => esc_html__( 'Primary Menu location (Appearance → Menus)', 'atelier-b2b' ),
		);
		$menus = wp_get_nav_menus();
		if ( $menus ) {
			foreach ( $menus as $menu ) {
				$options[ (string) $menu->term_id ] = $menu->name;
			}
		}
		return $options;
	}

	protected function register_controls() {

		/* ---- Content ---- */
		$this->start_controls_section( 'section_menu', array(
			'label' => esc_html__( 'Menu', 'atelier-b2b' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'menu', array(
			'label'   => esc_html__( 'WordPress menu', 'atelier-b2b' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $this->get_menu_options(),
			'default' => 'location:primary',
		) );

		$this->add_control( 'menu_note', array(
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => esc_html__( 'Add, remove and reorder links under Appearance → Menus. If the chosen menu is empty, the theme default links are shown.', 'atelier-b2b' ),
			'content_classes' => 'elementor-descriptor',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_mobile', array(
			'label' => esc_html__( 'Mobile menu', 'atelier-b2b' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'mobile_logo', array(
			'label'       => esc_html__( 'Logo inside mobile menu', 'atelier-b2b' ),
			'type'        => Controls_Manager::MEDIA,
			'default'     => array( 'url' => '' ),
			'description' => esc_html__( 'Use a dark logo. Leave empty to use the theme logo.', 'atelier-b2b' ),
		) );

		$this->add_control( 'mobile_cta_text', array(
			'label'       => esc_html__( 'Button text', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Partner with us', 'atelier-b2b' ),
			'label_block' => true,
		) );

		$this->add_control( 'mobile_cta_link', array(
			'label'   => esc_html__( 'Button link', 'atelier-b2b' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => home_url( '/contact/' ) ),
		) );

		$this->end_controls_section();

		/* ---- Style ---- */
		$this->start_controls_section( 'section_style_links', array(
			'label' => esc_html__( 'Links', 'atelier-b2b' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'link_typography',
			'selector' => '{{WRAPPER}} .nav__link',
		) );

		$this->add_control( 'link_color', array(
			'label'       => esc_html__( 'Link color', 'atelier-b2b' ),
			'type'        => Controls_Manager::COLOR,
			'description' => esc_html__( 'Leave empty to keep the automatic light/dark switching.', 'atelier-b2b' ),
			'selectors'   => array( '{{WRAPPER}} .nav__link' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'link_hover_color', array(
			'label'     => esc_html__( 'Link hover color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .nav__link:hover, {{WRAPPER}} .nav__link:focus-visible' => 'color: {{VALUE}};' ),
		) );

		$this->add_responsive_control( 'link_gap', array(
			'label'      => esc_html__( 'Space between links', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'      => array( 'px' => array( 'min' => 0, 'max' => 90 ) ),
			'selectors'  => array( '{{WRAPPER}} .nav__list' => 'gap: {{SIZE}}{{UNIT}};' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_burger', array(
			'label' => esc_html__( 'Hamburger', 'atelier-b2b' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'burger_color', array(
			'label'     => esc_html__( 'Hamburger color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .burger' => 'color: {{VALUE}};' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$menu     = ! empty( $s['menu'] ) ? $s['menu'] : 'location:primary';
		$items    = atelier_resolve_menu_items( $menu );
		$cta_url  = ! empty( $s['mobile_cta_link']['url'] ) ? $s['mobile_cta_link']['url'] : '';
		$cta_text = ! empty( $s['mobile_cta_text'] ) ? $s['mobile_cta_text'] : '';
		$logo_url = ! empty( $s['mobile_logo']['url'] ) ? $s['mobile_logo']['url'] : '';

		// Share with the theme-level mobile menu (same data source as the desktop menu).
		atelier_header_context( array(
			'menu'     => $menu,
			'cta_text' => $cta_text,
			'cta_url'  => $cta_url,
			'logo_url' => $logo_url,
		) );
		?>
		<nav class="nav__primary atelier-nav-widget" aria-label="<?php esc_attr_e( 'Primary', 'atelier-b2b' ); ?>">
			<ul class="nav__list">
				<?php foreach ( $items as $item ) : ?>
					<li class="nav__item">
						<a class="nav__link<?php echo ! empty( $item['current'] ) ? ' is-current' : ''; ?>" href="<?php echo esc_url( $item['url'] ); ?>"<?php echo ! empty( $item['current'] ) ? ' aria-current="page"' : ''; ?><?php echo ! empty( $item['target'] ) ? ' target="' . esc_attr( $item['target'] ) . '" rel="noopener"' : ''; ?>><?php echo esc_html( $item['label'] ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<button class="burger" type="button" aria-expanded="false" aria-controls="mobile-menu" data-menu-toggle>
			<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'atelier-b2b' ); ?></span>
			<span class="burger__line" aria-hidden="true"></span>
			<span class="burger__line" aria-hidden="true"></span>
		</button>
		<?php
	}
}
