<?php
/**
 * Elementor widget: filterable product catalog (dual-image cards, no links).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

class Atelier_Product_Catalog_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-product-catalog';
	}

	public function get_title() {
		return esc_html__( 'Atelier Product Catalog', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_keywords() {
		return array( 'products', 'catalog', 'filter', 'category', 'atelier' );
	}

	public function get_style_depends() {
		return array( 'atelier-collections' );
	}

	public function get_script_depends() {
		return array( 'atelier-collections-filter' );
	}

	protected function register_controls() {
		$c = atelier_content( 'collections' );

		$this->start_controls_section(
			'section_intro',
			array(
				'label' => esc_html__( 'Intro', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control( 'eyebrow', array(
			'label'       => esc_html__( 'Eyebrow', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => $c['eyebrow'],
			'label_block' => true,
		) );
		$this->add_control( 'heading', array(
			'label'       => esc_html__( 'Heading', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => $c['title'],
			'label_block' => true,
		) );
		$this->add_control( 'description', array(
			'label'       => esc_html__( 'Description', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => $c['sub'],
			'label_block' => true,
		) );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_filters',
			array(
				'label' => esc_html__( 'Category filters', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control( 'filters_label', array(
			'label'   => esc_html__( 'Filters label', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $c['filters_label'],
		) );
		$this->add_control( 'all_label', array(
			'label'   => esc_html__( '“All” button', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $c['all_label'],
		) );
		$this->add_control( 'count_label', array(
			'label'       => esc_html__( 'Count format', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => $c['count_label'],
			'description' => esc_html__( 'Use %d for the visible product count.', 'atelier-b2b' ),
		) );
		$this->add_control( 'empty_text', array(
			'label'   => esc_html__( 'Empty state', 'atelier-b2b' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $c['empty'],
		) );

		$filter_rep = new Repeater();
		$filter_rep->add_control( 'title', array(
			'label'       => esc_html__( 'Label', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Category', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$filter_rep->add_control( 'slug', array(
			'label'       => esc_html__( 'Slug', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'category',
			'description' => esc_html__( 'Must match each product’s category slug.', 'atelier-b2b' ),
		) );

		$filter_defaults = array();
		foreach ( atelier_content( 'categories' )['items'] as $cat ) {
			$filter_defaults[] = array(
				'title' => $cat['title'],
				'slug'  => isset( $cat['slug'] ) ? $cat['slug'] : sanitize_title( $cat['title'] ),
			);
		}
		$this->add_control( 'filters', array(
			'label'       => esc_html__( 'Filters (drag to reorder)', 'atelier-b2b' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $filter_rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => $filter_defaults,
		) );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_products',
			array(
				'label' => esc_html__( 'Products', 'atelier-b2b' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$prod = new Repeater();
		$prod->add_control( 'title', array(
			'label'       => esc_html__( 'Product title', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Product', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$prod->add_control( 'category', array(
			'label'       => esc_html__( 'Category slug', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'essentials',
			'description' => esc_html__( 'e.g. menswear, womenswear — must match a filter slug.', 'atelier-b2b' ),
		) );
		$prod->add_control( 'category_label', array(
			'label'       => esc_html__( 'Category label', 'atelier-b2b' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Essentials', 'atelier-b2b' ),
			'label_block' => true,
		) );
		$prod->add_control( 'image', array(
			'label'   => esc_html__( 'Primary image', 'atelier-b2b' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => array( 'url' => '' ),
		) );
		$prod->add_control( 'fallback_image', array(
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'showcase_01',
		) );
		$prod->add_control( 'image_hover', array(
			'label'       => esc_html__( 'Hover image', 'atelier-b2b' ),
			'type'        => Controls_Manager::MEDIA,
			'default'     => array( 'url' => '' ),
			'description' => esc_html__( 'Second look shown on hover. Cards do not link anywhere.', 'atelier-b2b' ),
		) );
		$prod->add_control( 'fallback_image_hover', array(
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'category_essential',
		) );

		$prod_defaults = array();
		foreach ( $c['items'] as $i => $item ) {
			$prod_defaults[] = array(
				'title'               => $item['title'],
				'category'            => $item['category'],
				'category_label'      => $item['category_label'],
				'image'               => array( 'url' => '' ),
				'fallback_image'      => $item['image'],
				'image_hover'         => array( 'url' => '' ),
				'fallback_image_hover'=> isset( $item['image_hover'] ) ? $item['image_hover'] : $item['image'],
			);
		}
		$this->add_control( 'products', array(
			'label'       => esc_html__( 'Products (drag to reorder)', 'atelier-b2b' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $prod->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => $prod_defaults,
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
			'label'     => esc_html__( 'Background', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .collections-intro' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .collections-catalog' => 'background-color: {{VALUE}};',
			),
		) );
		$this->add_control( 'heading_color', array(
			'label'     => esc_html__( 'Heading color', 'atelier-b2b' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .collections-intro__title' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typography',
			'selector' => '{{WRAPPER}} .collections-intro__title',
		) );
		$this->add_responsive_control( 'section_padding', array(
			'label'      => esc_html__( 'Catalog padding', 'atelier-b2b' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'rem', '%' ),
			'selectors'  => array( '{{WRAPPER}} .collections-catalog' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );
		$this->end_controls_section();
	}

	/**
	 * Resolve uploaded media or theme fallback asset key.
	 *
	 * @param array  $media    Elementor MEDIA control.
	 * @param string $fallback Asset key.
	 * @return array|string
	 */
	private function resolve_image( $media, $fallback ) {
		if ( ! empty( $media['id'] ) || ! empty( $media['url'] ) ) {
			return $media;
		}
		return $fallback ? $fallback : '';
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$filters = ! empty( $s['filters'] ) ? $s['filters'] : array();
		$products = ! empty( $s['products'] ) ? $s['products'] : array();
		$active  = isset( $_GET['category'] ) ? sanitize_title( wp_unslash( $_GET['category'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$valid   = array();
		foreach ( $filters as $f ) {
			if ( ! empty( $f['slug'] ) ) {
				$valid[] = sanitize_title( $f['slug'] );
			}
		}
		if ( $active && ! in_array( $active, $valid, true ) ) {
			$active = '';
		}

		$count_fmt = ! empty( $s['count_label'] ) ? $s['count_label'] : '%d products';
		$visible   = 0;
		foreach ( $products as $item ) {
			$slug = isset( $item['category'] ) ? sanitize_title( $item['category'] ) : '';
			if ( ! $active || $slug === $active ) {
				++$visible;
			}
		}

		$title_id = 'collections-title-' . sanitize_html_class( $this->get_id() );
		?>
		<section class="collections-intro section" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
			<div class="container collections-intro__inner">
				<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
					<p class="label collections-intro__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $s['heading'] ) ) : ?>
					<h1 class="display collections-intro__title" id="<?php echo esc_attr( $title_id ); ?>" data-lines aria-label="<?php echo esc_attr( atelier_plain( $s['heading'] ) ); ?>">
						<span aria-hidden="true"><?php echo atelier_lines( $s['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					</h1>
				<?php endif; ?>
				<?php if ( ! empty( $s['description'] ) ) : ?>
					<p class="lede collections-intro__sub" data-fade><?php echo esc_html( $s['description'] ); ?></p>
				<?php endif; ?>
			</div>
		</section>

		<section
			class="collections-catalog section"
			data-collections-filter
			data-active="<?php echo esc_attr( $active ); ?>"
			data-count-label="<?php echo esc_attr( $count_fmt ); ?>"
			aria-label="<?php esc_attr_e( 'Product catalog', 'atelier-b2b' ); ?>"
		>
			<div class="container">
				<div class="collections-toolbar">
					<?php if ( ! empty( $s['filters_label'] ) ) : ?>
						<p class="label collections-toolbar__label"><?php echo esc_html( $s['filters_label'] ); ?></p>
					<?php endif; ?>
					<div class="collections-filters" role="toolbar" aria-label="<?php echo esc_attr( $s['filters_label'] ); ?>">
						<button type="button" class="collections-filter<?php echo '' === $active ? ' is-active' : ''; ?>" data-filter="" aria-pressed="<?php echo '' === $active ? 'true' : 'false'; ?>">
							<?php echo esc_html( $s['all_label'] ); ?>
						</button>
						<?php foreach ( $filters as $filter ) :
							$slug = sanitize_title( $filter['slug'] );
							$is   = ( $active === $slug );
							?>
							<button type="button" class="collections-filter<?php echo $is ? ' is-active' : ''; ?>" data-filter="<?php echo esc_attr( $slug ); ?>" aria-pressed="<?php echo $is ? 'true' : 'false'; ?>">
								<?php echo esc_html( $filter['title'] ); ?>
							</button>
						<?php endforeach; ?>
					</div>
					<p class="collections-toolbar__count" data-collections-count aria-live="polite">
						<?php echo esc_html( sprintf( $count_fmt, $visible ) ); ?>
					</p>
				</div>

				<div class="collections-grid" data-collections-grid>
					<?php
					foreach ( $products as $i => $item ) {
						$slug = isset( $item['category'] ) ? sanitize_title( $item['category'] ) : '';
						atelier_collections_product_card(
							array(
								'title'          => $item['title'],
								'category'       => $slug,
								'category_label' => $item['category_label'],
								'index'          => str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ),
								'image'          => $this->resolve_image( isset( $item['image'] ) ? $item['image'] : array(), isset( $item['fallback_image'] ) ? $item['fallback_image'] : '' ),
								'image_hover'    => $this->resolve_image( isset( $item['image_hover'] ) ? $item['image_hover'] : array(), isset( $item['fallback_image_hover'] ) ? $item['fallback_image_hover'] : '' ),
							),
							(bool) ( $active && $slug !== $active )
						);
					}
					?>
				</div>

				<p class="collections-empty<?php echo ( 0 === $visible ) ? '' : ' is-hidden'; ?>" data-collections-empty <?php echo ( 0 === $visible ) ? '' : 'hidden'; ?>>
					<?php echo esc_html( $s['empty_text'] ); ?>
				</p>
			</div>
		</section>
		<?php
	}
}
