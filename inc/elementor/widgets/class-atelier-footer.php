<?php
/**
 * Elementor widget: editable global Atelier footer.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

class Atelier_Footer_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-footer';
	}

	public function get_title() {
		return esc_html__( 'Atelier Global Footer', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-footer';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'footer', 'contact', 'social', 'atelier' );
	}

	private function link_repeater() {
		$repeater = new Repeater();
		$repeater->add_control( 'label', array(
			'label'       => esc_html__( 'Link text', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Page', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$repeater->add_control( 'url', array(
			'label'       => esc_html__( 'URL', 'atelier-b2b' ),
			'type'        => Controls_Manager::URL,
			'default'     => array( 'url' => '' ),
			'placeholder' => 'https://',
		) );
		return $repeater;
	}

	protected function register_controls() {
		$content = atelier_content( 'footer' );
		$privacy = function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ? get_privacy_policy_url() : atelier_url( 'privacy-policy' );
		$column_1 = array();
		foreach ( $content['col_1'] as $item ) {
			$column_1[] = array( 'label' => $item['label'], 'url' => array( 'url' => $item['url'] ) );
		}
		$column_2 = array();
		foreach ( $content['col_2'] as $item ) {
			$url = 'Privacy Policy' === $item['label'] ? $privacy : $item['url'];
			$column_2[] = array( 'label' => $item['label'], 'url' => array( 'url' => $url ) );
		}
		$social = array();
		foreach ( $content['social'] as $item ) {
			$social[] = array( 'label' => $item['label'], 'url' => array( 'url' => $item['url'] ) );
		}

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Footer content', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control( 'statement', array(
			'label'       => esc_html__( 'Large statement', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => $content['statement'],
			'label_block' => true,
		) );
		$this->add_control( 'cta_text', array(
			'label'   => esc_html__( 'Top button text', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $content['cta']['label'],
		) );
		$this->add_control( 'cta_link', array(
			'label'       => esc_html__( 'Top button URL', 'atelier-b2b' ),
			'type'        => Controls_Manager::URL,
			'default'     => array( 'url' => $content['cta']['url'] ),
			'placeholder' => 'https://',
		) );
		$this->add_control( 'logo', array(
			'label'       => esc_html__( 'Footer logo (optional)', 'atelier-b2b' ),
			'type'        => Controls_Manager::MEDIA,
			'default'     => array( 'url' => '' ),
			'description' => esc_html__( 'Leave empty to use the theme logo.', 'atelier-b2b' ),
		) );
		$this->add_control( 'intro', array(
			'label'       => esc_html__( 'Brand introduction', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => $content['intro'],
			'label_block' => true,
		) );

		$this->add_control( 'column_1_title', array(
			'label'   => esc_html__( 'First link column label (screen readers)', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Footer', 'atelier-b2b' ),
		) );
		$links = $this->link_repeater();
		$this->add_control( 'column_1', array(
			'label'       => esc_html__( 'First link column', 'atelier-b2b' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $links->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => $column_1,
		) );

		$this->add_control( 'column_2_title', array(
			'label'   => esc_html__( 'Second link column label (screen readers)', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Footer secondary', 'atelier-b2b' ),
		) );
		$links_2 = $this->link_repeater();
		$this->add_control( 'column_2', array(
			'label'       => esc_html__( 'Second link column', 'atelier-b2b' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $links_2->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => $column_2,
		) );

		$this->add_control( 'email', array(
			'label'   => esc_html__( 'Email address', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $content['email'],
		) );
		$this->add_control( 'phone', array(
			'label'   => esc_html__( 'Phone number', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $content['phone'],
		) );
		$this->add_control( 'location', array(
			'label'   => esc_html__( 'Location', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $content['location'],
		) );
		$social_repeater = $this->link_repeater();
		$this->add_control( 'social', array(
			'label'       => esc_html__( 'Social links', 'atelier-b2b' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $social_repeater->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => $social,
		) );
		$this->add_control( 'terms_url', array(
			'label'       => esc_html__( 'Terms & Conditions URL', 'atelier-b2b' ),
			'type'        => Controls_Manager::URL,
			'default'     => array( 'url' => $content['terms_url'] ),
			'placeholder' => 'https://',
		) );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout & spacing', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control( 'footer_padding', array(
			'label'      => esc_html__( 'Footer padding', 'atelier-b2b' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'rem', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .footer' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
				'{{WRAPPER}} .footer__top, {{WRAPPER}} .footer__mid, {{WRAPPER}} .footer__bottom' => 'padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
			),
		) );
		$this->add_responsive_control( 'column_gap', array(
			'label'      => esc_html__( 'Column spacing', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem' ),
			'range'      => array(
				'px'  => array( 'min' => 0, 'max' => 120 ),
				'rem' => array( 'min' => 0, 'max' => 8, 'step' => 0.1 ),
			),
			'selectors' => array( '{{WRAPPER}} .footer__mid' => 'column-gap: {{SIZE}}{{UNIT}}; row-gap: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'top_gap', array(
			'label'      => esc_html__( 'Statement/button spacing', 'atelier-b2b' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem' ),
			'range'      => array(
				'px'  => array( 'min' => 0, 'max' => 120 ),
				'rem' => array( 'min' => 0, 'max' => 8, 'step' => 0.1 ),
			),
			'selectors'  => array( '{{WRAPPER}} .footer__top' => 'gap: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'statement_alignment', array(
			'label'   => esc_html__( 'Statement alignment', 'atelier-b2b' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => array(
				'left'   => array( 'title' => esc_html__( 'Left', 'atelier-b2b' ), 'icon' => 'eicon-text-align-left' ),
				'center' => array( 'title' => esc_html__( 'Center', 'atelier-b2b' ), 'icon' => 'eicon-text-align-center' ),
				'right'  => array( 'title' => esc_html__( 'Right', 'atelier-b2b' ), 'icon' => 'eicon-text-align-right' ),
			),
			'default'   => 'left',
			'selectors' => array( '{{WRAPPER}} .footer__statement' => 'text-align: {{VALUE}};' ),
		) );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Colors & typography', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control( 'background_color', array(
			'label'     => esc_html__( 'Background color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .footer' => 'background-color: {{VALUE}};' ),
		) );
		$this->add_control( 'text_color', array(
			'label'     => esc_html__( 'Main text color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .footer' => 'color: {{VALUE}};',
				'{{WRAPPER}} .footer__statement' => 'color: {{VALUE}};',
				'{{WRAPPER}} .footer__top-link' => 'color: {{VALUE}};',
			),
		) );
		$this->add_control( 'muted_color', array(
			'label'     => esc_html__( 'Secondary text color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .footer__intro' => 'color: {{VALUE}};',
				'{{WRAPPER}} .footer__bottom' => 'color: {{VALUE}};',
			),
		) );
		$this->add_control( 'accent_color', array(
			'label'     => esc_html__( 'Accent color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .footer__social a' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .footer__top, {{WRAPPER}} .footer__bottom' => 'border-color: {{VALUE}};',
			),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'statement_typography',
			'selector' => '{{WRAPPER}} .footer__statement',
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'links_typography',
			'selector' => '{{WRAPPER}} .footer__list a, {{WRAPPER}} .footer__list span',
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'intro_typography',
			'selector' => '{{WRAPPER}} .footer__intro',
		) );
		$this->add_control( 'button_background', array(
			'label'     => esc_html__( 'Top button background', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .footer__top .btn' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				'{{WRAPPER}} .footer__top .btn::before' => 'background-color: {{VALUE}};',
			),
		) );
		$this->add_control( 'button_text_color', array(
			'label'     => esc_html__( 'Top button text color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .footer__top .btn' => 'color: {{VALUE}};',
				'{{WRAPPER}} .footer__top .btn::before' => 'color: {{VALUE}};',
			),
		) );
		$this->end_controls_section();
	}

	private function render_links( $items ) {
		if ( empty( $items ) || ! is_array( $items ) ) {
			return;
		}
		foreach ( $items as $item ) {
			$label = isset( $item['label'] ) ? trim( $item['label'] ) : '';
			$link  = isset( $item['url'] ) && is_array( $item['url'] ) ? $item['url'] : array();
			if ( '' === $label ) {
				continue;
			}
			echo '<li>';
			if ( ! empty( $link['url'] ) ) {
				printf( '<a href="%1$s"%2$s>%3$s</a>', esc_url( $link['url'] ), atelier_link_attrs( $link ), esc_html( $label ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo esc_html( $label );
			}
			echo '</li>';
		}
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$title_id = 'footer-statement-' . sanitize_html_class( $this->get_id() );
		$cta_link = isset( $s['cta_link'] ) && is_array( $s['cta_link'] ) ? $s['cta_link'] : array();
		$logo = isset( $s['logo'] ) && is_array( $s['logo'] ) ? $s['logo'] : array();
		$terms = isset( $s['terms_url'] ) && is_array( $s['terms_url'] ) ? $s['terms_url'] : array();
		$privacy = function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ? get_privacy_policy_url() : atelier_url( 'privacy-policy' );
		$email = isset( $s['email'] ) ? sanitize_email( $s['email'] ) : '';
		$phone = isset( $s['phone'] ) ? $s['phone'] : '';
		$phone_href = preg_replace( '/[^0-9+]/', '', $phone );
		?>
		<footer class="footer" id="colophon-<?php echo esc_attr( sanitize_html_class( $this->get_id() ) ); ?>">
			<div class="container footer__top">
				<?php if ( ! empty( $s['statement'] ) ) : ?><h2 class="footer__statement display" id="<?php echo esc_attr( $title_id ); ?>" data-lines aria-label="<?php echo esc_attr( atelier_plain( $s['statement'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $s['statement'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></h2><?php endif; ?>
				<?php if ( ! empty( $s['cta_text'] ) && ! empty( $cta_link['url'] ) ) : ?><a class="btn btn--light btn--lg" href="<?php echo esc_url( $cta_link['url'] ); ?>"<?php echo atelier_link_attrs( $cta_link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><span><?php echo esc_html( $s['cta_text'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a><?php endif; ?>
			</div>

			<div class="container footer__mid">
				<div class="footer__col footer__col--brand">
					<a class="brand brand--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
						<?php
						if ( ! empty( $logo['id'] ) ) {
							echo wp_get_attachment_image( absint( $logo['id'] ), 'full', false, array( 'class' => 'brand__logo', 'alt' => get_bloginfo( 'name' ), 'loading' => 'eager' ) );
						} elseif ( ! empty( $logo['url'] ) ) {
							printf( '<img class="brand__logo" src="%1$s" alt="%2$s" loading="eager">', esc_url( $logo['url'] ), esc_attr( get_bloginfo( 'name' ) ) );
						} else {
							atelier_logo();
						}
						?>
					</a>
					<?php if ( ! empty( $s['intro'] ) ) : ?><p class="footer__intro"><?php echo esc_html( $s['intro'] ); ?></p><?php endif; ?>
				</div>

				<?php if ( ! empty( $s['column_1'] ) ) : ?>
					<nav class="footer__col" aria-label="<?php echo esc_attr( ! empty( $s['column_1_title'] ) ? $s['column_1_title'] : __( 'Footer', 'atelier-b2b' ) ); ?>">
						<ul class="footer__list"><?php $this->render_links( $s['column_1'] ); ?></ul>
					</nav>
				<?php endif; ?>
				<?php if ( ! empty( $s['column_2'] ) ) : ?>
					<nav class="footer__col" aria-label="<?php echo esc_attr( ! empty( $s['column_2_title'] ) ? $s['column_2_title'] : __( 'Footer secondary', 'atelier-b2b' ) ); ?>">
						<ul class="footer__list"><?php $this->render_links( $s['column_2'] ); ?></ul>
					</nav>
				<?php endif; ?>

				<div class="footer__col footer__col--contact">
					<ul class="footer__list">
						<?php if ( $email ) : ?><li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li><?php endif; ?>
						<?php if ( $phone ) : ?><li><a href="tel:<?php echo esc_attr( $phone_href ); ?>"><?php echo esc_html( $phone ); ?></a></li><?php endif; ?>
						<?php if ( ! empty( $s['location'] ) ) : ?><li><span><?php echo esc_html( $s['location'] ); ?></span></li><?php endif; ?>
					</ul>
					<?php if ( ! empty( $s['social'] ) && is_array( $s['social'] ) ) : ?>
						<ul class="footer__social" aria-label="<?php esc_attr_e( 'Social media', 'atelier-b2b' ); ?>">
							<?php foreach ( $s['social'] as $item ) :
								$label = isset( $item['label'] ) ? trim( $item['label'] ) : '';
								$link = isset( $item['url'] ) && is_array( $item['url'] ) ? $item['url'] : array();
								if ( '' === $label || empty( $link['url'] ) ) {
									continue;
								}
								?>
								<li><a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo atelier_link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( $label ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>

			<div class="container footer__bottom">
				<p class="footer__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'atelier-b2b' ); ?></p>
				<ul class="footer__legal">
					<li><a href="<?php echo esc_url( $privacy ); ?>"><?php esc_html_e( 'Privacy Policy', 'atelier-b2b' ); ?></a></li>
					<?php if ( ! empty( $terms['url'] ) ) : ?><li><a href="<?php echo esc_url( $terms['url'] ); ?>"<?php echo atelier_link_attrs( $terms ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php esc_html_e( 'Terms & Conditions', 'atelier-b2b' ); ?></a></li><?php endif; ?>
				</ul>
				<a class="footer__top-link" href="#top" data-back-to-top><span><?php esc_html_e( 'Back to top', 'atelier-b2b' ); ?></span><svg viewBox="0 0 16 16" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M8 14V2M3 7l5-5 5 5"/></svg></a>
			</div>
		</footer>
		<?php
	}
}
