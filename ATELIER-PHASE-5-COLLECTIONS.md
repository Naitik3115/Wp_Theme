# ATELIER – PHASE 5: Premium Dynamic Collections Page (Elementor Free)

## 1. Overview

A dynamic, editorial **Collections** page that sits on top of the existing theme without touching the header, footer or homepage sections.

- **Atelier Products** (`atelier_product`) + **Collection Categories** (`atelier_collection`) – native WordPress CPT/taxonomy, no ACF, no WooCommerce. Main image = featured image, short description = excerpt, custom order = *Order* field, plus meta boxes (Media Library) for reference, season label, fabric, colours, *Featured*, **hover image** and **gallery**.
- **Page template “Atelier Collections”** – assign it to any page; with no Elementor content it renders the default composition (editorial hero → sticky filter bar → asymmetric grid → closing CTA). If the page is built with Elementor, the Elementor content is printed instead.
- **Three Elementor Free widgets** (category “Atelier”): **Atelier Collection Grid** (all query / layout / filter / Quick View controls, content comes from the CPT), **Atelier Collections Hero**, **Atelier Collections CTA**.
- **Filtering without reload** + **Load More** that respects the active category, via a public read-only REST endpoint (`/wp-json/atelier/v1/collection`). The first page is server-rendered (SEO-friendly); `?collection=slug` deep-links a category.
- **Hover image crossfade** with subtle scale; secondary images are fetched lazily (when a card nears the viewport on hover-capable devices, or on hover/focus), never all at load. No secondary image → subtle zoom. On touch devices the *Quick view* pill is always visible.
- **Quick View side drawer**: lazy-loaded gallery (REST `/wp-json/atelier/v1/product/<id>`), thumbnails + prev/next + arrow keys, Esc to close, focus trap, scroll lock, focus restore, reduced-motion aware. CTA **Inquire about this product** → contact page with `?product=REF&product_name=TITLE`.
- No prices, discounts, ratings, cart or checkout anywhere.
- Collection CSS/JS are registered globally but **enqueued only** on the Collections template (or where a widget is used, via Elementor’s style/script dependencies). Existing GSAP reveals (`data-lines`, `data-fade`, `data-clip`, `data-parallax`) are reused – no new animation library.

> **Heads-up about Phase 4:** the Phase 4 package (Categories + Editorial Collection + “Atelier Homepage” template) was **not delivered** – my Phase 4 build step failed and I did not notice before you sent Phase 5. This Phase 5 patch is therefore built on the **Phase 3** state and is self-contained. The only existing file it changes is `functions.php` (one added line). When Phase 4 is produced, its `functions.php` simply needs the same one line added (`require_once … '/inc/collections/collections-loader.php';`). A guarded copy of `atelier_media_img()` is included here so the two phases do not clash.

## 2. File manifest

| File | Action |
|---|---|
| `functions.php` | **MODIFY** (full replacement) |

| `inc/collections/collections-loader.php` | ADD |
| `inc/collections/register-post-type.php` | ADD |
| `inc/collections/register-taxonomy.php` | ADD |
| `inc/collections/product-meta.php` | ADD |
| `inc/collections/product-query.php` | ADD |
| `inc/collections/collection-helpers.php` | ADD |
| `inc/collections/collection-demo.php` | ADD |

| `inc/elementor/widgets/class-atelier-collection-grid.php` | ADD |
| `inc/elementor/widgets/class-atelier-collections-hero.php` | ADD |
| `inc/elementor/widgets/class-atelier-collections-cta.php` | ADD |

| `page-templates/template-atelier-collections.php` | ADD |

| `template-parts/collections/collection-hero.php` | ADD |
| `template-parts/collections/category-filters.php` | ADD |
| `template-parts/collections/product-grid.php` | ADD |
| `template-parts/collections/product-card.php` | ADD |
| `template-parts/collections/quick-view.php` | ADD |
| `template-parts/collections/collection-cta.php` | ADD |

| `assets/css/collections.css` | ADD |
| `assets/css/admin-product.css` | ADD |

| `assets/js/collections.js` | ADD |
| `assets/js/admin-product.js` | ADD |

| `elementor-templates/atelier-collections-page.json` | ADD |

**REMOVE:** nothing. No existing file is deleted.

Existing files that are *used but not modified*: `inc/helpers.php`, `inc/sections-markup.php` (`atelier_link_attrs`), `inc/asset-config.php`, `inc/content-config.php`, `inc/enqueue-assets.php` (registers the GSAP / main script handles), `inc/elementor/*` (Phase 2/3: “Atelier” widget category, `atelier_elementor_active()`, `atelier_is_elementor_editor()`), `assets/css/main.css|responsive.css|animations.css`, `assets/js/animations.js|main.js`.

## 3. Installation

1. **Back up** `wp-content/themes/atelier-b2b/`.
2. Upload the files from the patch zip into the theme folder keeping the paths in the manifest (new folders: `inc/collections/`, `template-parts/collections/`, `elementor-templates/`; existing folders `page-templates/`, `assets/css|js`, `inc/elementor/widgets/` get new files). Overwrite `functions.php`.
3. Reload any admin page. You should see **Atelier Products** in the sidebar (with *Collection Categories* and *Demo content* under it). No permalink flush is needed (the CPT/taxonomy are non-public; data is read through the REST API).

## 4. Collection Categories (taxonomy)

*Atelier Products → Collection Categories*: add as many as you like (e.g. Menswear, Womenswear, Streetwear, Essentials, Activewear, Custom Apparel). Optional **Display order** (1 = first in the filter bar); unordered categories follow alphabetically. A product can have several categories. Parent/child categories are allowed, but the filter bar lists all categories flat.

## 5. Adding products

*Atelier Products → Add New*
- **Title** – product name.
- **Main image** (right sidebar “Main image”) – required for a good card; uses the Media Library.
- **Excerpt** – short description shown in Quick View (if empty, the first ~45 words of the editor content are used).
- **Product details** box – reference/SKU, collection/season label, fabric/material (optional), colours (optional, comma separated), **Featured** (large wide card).
- **Hover image & gallery** box – secondary hover image (same aspect ratio as the main image works best) and extra gallery images (drag to reorder). The main image is always first in the Quick View gallery.
- **Attributes → Order** – custom display order (lower first). Order options in the widget: custom order, featured first, newest, oldest, title.
- Assign **Collection Categories**, then Publish. New products appear automatically (no widget change needed).

**Demo content (optional):** *Atelier Products → Demo content → Create demo content* adds 6 categories and 10 clearly labelled *Sample Product* items that use the theme’s placeholder images, so you can preview the page. *Remove demo content* deletes exactly those items (products, their sideloaded images, empty demo categories). No product facts are invented.

## 6. Create the Collections page

1. Pages → Add New → title **Collections**.
2. Page Attributes → Template → **Atelier Collections** → Publish.
3. Open the page: hero, filter bar, grid and CTA appear with your products. (The page’s own editor content is not used in this default mode.)

### Editing the layout with Elementor Free
1. On that page click **Edit with Elementor** (it opens empty – the default composition is a PHP fallback).
2. Import the template: *Elementor → Templates → Saved Templates → Import* `elementor-templates/atelier-collections-page.json` (the button link `/contact/` is relative; change it if your contact page differs).
3. Back in the page editor: folder icon → *My Templates* → **Insert** “Atelier Collections Page”. You get three containers with **Atelier Collections Hero**, **Atelier Collection Grid**, **Atelier Collections CTA**. Edit, **Update**. From now on the template prints the Elementor content (header/footer remain the global ones; no recursion because the template prints `the_content()` only).
4. To go back to the default composition: in Elementor use *Settings → … Discard / Delete content* or simply switch the page to *Default Template*… (see rollback).

The widgets can also be used on any other page (their CSS/JS load automatically via Elementor).

**Collection Grid widget controls:** eyebrow / heading / description; show only selected categories; order by; products per page; Load more on/off + label; filter bar on/off, “All” label, sticky, hide empty categories; “Showing X of Y”; Quick View on/off + inquiry page URL; show reference / season; featured style (wide / same); empty-state texts; columns for desktop / tablet / mobile; image aspect ratio and featured aspect ratio; gap; section background and padding; typography + colours for heading, filters, product name and category line; active-filter/underline colour; Quick View pill colours. (The Quick View drawer itself is a global element and follows the theme styling.)

## 7. Inquiry destination
Quick View’s CTA links to `<inquiry URL>?product=<REF or title>&product_name=<title>`. Default inquiry URL = `/contact/`; change per grid (widget control), or globally with the filter `atelier_inquiry_url`. To pre-fill a Contact Form 7 / other form from `?product=…`, use that plugin’s URL-parameter / default-value feature – the theme does not ship a form.

## 8. Behaviour notes
- Products have **no public single pages / archives / sitemap entries** (CPT `public => false`); they are shown through the grid and Quick View only.
- Filter counts use the category’s published-product count; “All” counts distinct products in the allowed set. Empty categories stay visible (elegant empty state) unless *Hide categories without products* is on.
- Load More + filtering: the page counter resets when the category changes; “Load more” always fetches the next page of the *active* category with the widget’s per-page and order settings.
- Elementor editor: grid JS initialises once per widget via `elementor/frontend/init` (guarded by `data-ready`); the Quick View drawer is not printed in the editor; hover/scroll animations do not run there.

## 9. Testing checklist (requires a live WordPress staging site)

- [ ] Menu shows Atelier Products / Collection Categories; creating categories and a product with main + hover + gallery images saves and reloads correctly (nonce, capability, sanitisation).
- [ ] Demo content creates and removes cleanly.
- [ ] Page with template *Atelier Collections* renders hero → filters → grid → CTA with the global header (solid, no overlap) and footer; no duplicate header/footer.
- [ ] Filter clicks: no page reload, underline slides to the active filter, counts correct, empty category shows the empty state, URL gets `?collection=slug`, reload keeps the category.
- [ ] Load More appends the next page of the active category and hides when finished; works after switching category.
- [ ] Hover: primary → secondary crossfade with subtle scale, no flicker; hover image requested only when needed (Network tab); no secondary image → zoom only.
- [ ] Touch/mobile: *Quick view* pill visible without hover; filter bar scrolls horizontally; 1-column (or 2) layout; no horizontal page overflow at 320 px.
- [ ] Quick View: opens with slide animation, gallery thumbs/prev/next/arrow keys, Esc closes, Tab stays inside, body doesn’t scroll, focus returns to the card, CTA goes to contact with `?product=`; error state + retry if offline.
- [ ] OS “reduce motion”: no transitions/animations, content visible.
- [ ] Elementor: widgets appear under “Atelier”; editing/Update works; the editor preview shows the grid; no duplicate listeners (filters fire once).
- [ ] Homepage, header and footer unchanged. Browser console has no errors.

## 10. Rollback
1. Quick: set the page’s template back to *Default Template* (the Collections design disappears; products remain stored).
2. Hide the new admin features: remove the single `collections-loader.php` line from `functions.php` (all Phase 5 code is inert; products/terms stay in the database).
3. Full rollback: restore your backed-up `functions.php` and delete the added files. Optional data clean-up: *Demo content → Remove*, then trash products/categories.

## 11. Verification status
Done in the build environment: PHP lint (8.3) of every PHP file and a lint of the Phase 3 theme + this patch merged; JS syntax check of `collections.js` / `admin-product.js`; JSON validity; a stubbed-WordPress render of the card, grid, filter bar, hero, CTA and Quick View templates (no PHP notices, balanced tags, product name escaped, hover image deferred via `data-src`, quick view button omitted when disabled); argument-sanitisation test of the REST/query helper. **Not verified** (no WordPress, Elementor or browser here): real WP_Query/REST behaviour, meta-box saving, Media Library pickers, demo sideloading, Elementor widget rendering and the editor, every visual/animation detail, and focus/keyboard behaviour in browsers. Treat section 9 as required before launch.

## 12. Complete code of every new / replaced file

### `functions.php`  —  MODIFY (replace)

```php
<?php
/**
 * Atelier B2B – theme bootstrap.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

define( 'ATELIER_VERSION', '1.3.0' );

require_once get_template_directory() . '/inc/theme-setup.php';
require_once get_template_directory() . '/inc/asset-config.php';
require_once get_template_directory() . '/inc/content-config.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/sections-markup.php'; // Phase 3: shared Hero / Trust markup (static + Elementor).
require_once get_template_directory() . '/inc/enqueue-assets.php';

require_once get_template_directory() . '/inc/collections/collections-loader.php'; // Phase 5: products, collections page, Quick View.

// Phase 2 + 3: optional Elementor Free integration (falls back to static PHP automatically).
require_once get_template_directory() . '/inc/elementor/elementor-integration.php';
```

### `inc/collections/collections-loader.php`  —  ADD

```php
<?php
/**
 * Phase 5 loader: Atelier Products (CPT), Collection Categories (taxonomy), REST data, assets,
 * Collections page template routing, optional demo content and Elementor widgets.
 *
 * Kill-switch for the Elementor widgets only: nothing to disable – they simply do not register
 * when Elementor is inactive.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$atelier_col_dir = __DIR__;

require_once $atelier_col_dir . '/register-post-type.php';
require_once $atelier_col_dir . '/register-taxonomy.php';
require_once $atelier_col_dir . '/product-meta.php';
require_once $atelier_col_dir . '/product-query.php';
require_once $atelier_col_dir . '/collection-helpers.php';
require_once $atelier_col_dir . '/collection-demo.php';

unset( $atelier_col_dir );

/** Register the three Collections widgets (extends the "Atelier" category created in Phase 2). */
function atelier_col_register_widgets( $widgets_manager ) {
	$base = get_template_directory() . '/inc/elementor/widgets/';
	require_once $base . 'class-atelier-collection-grid.php';
	require_once $base . 'class-atelier-collections-hero.php';
	require_once $base . 'class-atelier-collections-cta.php';

	$widgets = array(
		new Atelier_Collection_Grid_Widget(),
		new Atelier_Collections_Hero_Widget(),
		new Atelier_Collections_Cta_Widget(),
	);
	foreach ( $widgets as $widget ) {
		if ( method_exists( $widgets_manager, 'register' ) ) {
			$widgets_manager->register( $widget );
		} else {
			$widgets_manager->register_widget_type( $widget );
		}
	}
}
add_action( 'elementor/widgets/register', 'atelier_col_register_widgets' );
```

### `inc/collections/register-post-type.php`  —  ADD

```php
<?php
/**
 * Custom post type: Atelier Products (showcase items – no prices, cart or checkout).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_register_product_cpt() {
	register_post_type(
		'atelier_product',
		array(
			'labels'              => array(
				'name'               => __( 'Atelier Products', 'atelier-b2b' ),
				'singular_name'      => __( 'Atelier Product', 'atelier-b2b' ),
				'menu_name'          => __( 'Atelier Products', 'atelier-b2b' ),
				'all_items'          => __( 'All Products', 'atelier-b2b' ),
				'add_new'            => __( 'Add New', 'atelier-b2b' ),
				'add_new_item'       => __( 'Add New Product', 'atelier-b2b' ),
				'edit_item'          => __( 'Edit Product', 'atelier-b2b' ),
				'new_item'           => __( 'New Product', 'atelier-b2b' ),
				'search_items'       => __( 'Search Products', 'atelier-b2b' ),
				'not_found'          => __( 'No products found.', 'atelier-b2b' ),
				'not_found_in_trash' => __( 'No products in Trash.', 'atelier-b2b' ),
				'featured_image'     => __( 'Main image', 'atelier-b2b' ),
				'set_featured_image' => __( 'Set main image', 'atelier-b2b' ),
				'remove_featured_image' => __( 'Remove main image', 'atelier-b2b' ),
				'use_featured_image' => __( 'Use as main image', 'atelier-b2b' ),
			),
			// Products are shown through the grid + Quick View only: no single pages / archives / search.
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'menu_position'       => 57,
			'menu_icon'           => 'dashicons-tag',
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
			'taxonomies'          => array( 'atelier_collection' ),
		)
	);
}
add_action( 'init', 'atelier_register_product_cpt' );
```

### `inc/collections/register-taxonomy.php`  —  ADD

```php
<?php
/**
 * Taxonomy: Collection Categories (unlimited, hierarchical, products can have several) + optional term order.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_register_collection_taxonomy() {
	register_taxonomy(
		'atelier_collection',
		array( 'atelier_product' ),
		array(
			'labels'            => array(
				'name'          => __( 'Collection Categories', 'atelier-b2b' ),
				'singular_name' => __( 'Collection Category', 'atelier-b2b' ),
				'menu_name'     => __( 'Collection Categories', 'atelier-b2b' ),
				'all_items'     => __( 'All Categories', 'atelier-b2b' ),
				'add_new_item'  => __( 'Add New Category', 'atelier-b2b' ),
				'edit_item'     => __( 'Edit Category', 'atelier-b2b' ),
				'search_items'  => __( 'Search Categories', 'atelier-b2b' ),
			),
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_in_rest'      => false,
			'rewrite'           => false,
			'query_var'         => false,
		)
	);
}
add_action( 'init', 'atelier_register_collection_taxonomy' );

/* ---- Display order for the filter bar (lower first, blank = after ordered terms, then A–Z) ---- */
function atelier_term_order_field_add() {
	wp_nonce_field( 'atelier_term_order', 'atelier_term_order_nonce' );
	echo '<div class="form-field"><label for="atelier_term_order">' . esc_html__( 'Display order', 'atelier-b2b' ) . '</label><input type="number" name="atelier_term_order" id="atelier_term_order" value="" class="small-text"><p>' . esc_html__( 'Position in the filter bar (1 = first). Optional.', 'atelier-b2b' ) . '</p></div>';
}
add_action( 'atelier_collection_add_form_fields', 'atelier_term_order_field_add' );

function atelier_term_order_field_edit( $term ) {
	wp_nonce_field( 'atelier_term_order', 'atelier_term_order_nonce' );
	$val = get_term_meta( $term->term_id, '_atelier_term_order', true );
	echo '<tr class="form-field"><th scope="row"><label for="atelier_term_order">' . esc_html__( 'Display order', 'atelier-b2b' ) . '</label></th><td><input type="number" name="atelier_term_order" id="atelier_term_order" value="' . esc_attr( $val ) . '" class="small-text"><p class="description">' . esc_html__( 'Position in the filter bar (1 = first). Optional.', 'atelier-b2b' ) . '</p></td></tr>';
}
add_action( 'atelier_collection_edit_form_fields', 'atelier_term_order_field_edit' );

function atelier_term_order_save( $term_id ) {
	if ( ! isset( $_POST['atelier_term_order_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['atelier_term_order_nonce'] ) ), 'atelier_term_order' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	$val = isset( $_POST['atelier_term_order'] ) ? sanitize_text_field( wp_unslash( $_POST['atelier_term_order'] ) ) : '';
	if ( '' === $val ) {
		delete_term_meta( $term_id, '_atelier_term_order' );
	} else {
		update_term_meta( $term_id, '_atelier_term_order', (int) $val );
	}
}
add_action( 'created_atelier_collection', 'atelier_term_order_save' );
add_action( 'edited_atelier_collection', 'atelier_term_order_save' );
```

### `inc/collections/product-meta.php`  —  ADD

```php
<?php
/**
 * Product fields (native meta boxes + Media Library): reference, season/collection label, fabric, colours,
 * featured flag, hover image, gallery. Main image = featured image; short description = excerpt;
 * custom order = "Order" in the Attributes box (menu_order).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_product_add_meta_boxes() {
	add_meta_box( 'atelier_product_details', __( 'Product details', 'atelier-b2b' ), 'atelier_product_details_box', 'atelier_product', 'normal', 'high' );
	add_meta_box( 'atelier_product_media', __( 'Hover image & gallery', 'atelier-b2b' ), 'atelier_product_media_box', 'atelier_product', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'atelier_product_add_meta_boxes' );

function atelier_product_admin_assets( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || 'atelier_product' !== $screen->post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_style( 'atelier-admin-product', get_template_directory_uri() . '/assets/css/admin-product.css', array(), ATELIER_VERSION );
	wp_enqueue_script( 'atelier-admin-product', get_template_directory_uri() . '/assets/js/admin-product.js', array( 'jquery', 'jquery-ui-sortable' ), ATELIER_VERSION, true );
	wp_localize_script( 'atelier-admin-product', 'atelierProductAdmin', array(
		'title'  => __( 'Choose images', 'atelier-b2b' ),
		'button' => __( 'Use selected', 'atelier-b2b' ),
	) );
}
add_action( 'admin_enqueue_scripts', 'atelier_product_admin_assets' );

function atelier_product_details_box( $post ) {
	wp_nonce_field( 'atelier_product_meta', 'atelier_product_meta_nonce' );
	$v = function ( $k ) use ( $post ) {
		return get_post_meta( $post->ID, $k, true );
	};
	?>
	<table class="form-table atelier-fields" role="presentation">
		<tr><th><label for="atelier_ref"><?php esc_html_e( 'Product reference / SKU', 'atelier-b2b' ); ?></label></th>
			<td><input type="text" class="regular-text" id="atelier_ref" name="atelier_ref" value="<?php echo esc_attr( $v( '_atelier_ref' ) ); ?>"><p class="description"><?php esc_html_e( 'Shown on the card and sent with the inquiry link.', 'atelier-b2b' ); ?></p></td></tr>
		<tr><th><label for="atelier_season"><?php esc_html_e( 'Collection / season label', 'atelier-b2b' ); ?></label></th>
			<td><input type="text" class="regular-text" id="atelier_season" name="atelier_season" value="<?php echo esc_attr( $v( '_atelier_season' ) ); ?>"></td></tr>
		<tr><th><label for="atelier_fabric"><?php esc_html_e( 'Fabric / material (optional)', 'atelier-b2b' ); ?></label></th>
			<td><input type="text" class="large-text" id="atelier_fabric" name="atelier_fabric" value="<?php echo esc_attr( $v( '_atelier_fabric' ) ); ?>"></td></tr>
		<tr><th><label for="atelier_colors"><?php esc_html_e( 'Colours (optional)', 'atelier-b2b' ); ?></label></th>
			<td><input type="text" class="large-text" id="atelier_colors" name="atelier_colors" value="<?php echo esc_attr( $v( '_atelier_colors' ) ); ?>"><p class="description"><?php esc_html_e( 'Comma separated, e.g. "Sand, Chestnut".', 'atelier-b2b' ); ?></p></td></tr>
		<tr><th><?php esc_html_e( 'Featured', 'atelier-b2b' ); ?></th>
			<td><label><input type="checkbox" name="atelier_featured" value="1" <?php checked( $v( '_atelier_featured' ), '1' ); ?>> <?php esc_html_e( 'Show as a large featured card in the grid', 'atelier-b2b' ); ?></label></td></tr>
	</table>
	<p class="description"><?php esc_html_e( 'Main image = "Main image" box on the right. Short description = Excerpt. Custom order = "Order" in Attributes (lower first).', 'atelier-b2b' ); ?></p>
	<?php
}

/** Render a media-picker field (hidden input holds comma-separated attachment IDs). */
function atelier_product_media_field( $name, $ids, $multiple ) {
	$ids = array_filter( array_map( 'absint', (array) $ids ) );
	echo '<div class="atelier-media-field" data-multiple="' . ( $multiple ? '1' : '0' ) . '">';
	echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( implode( ',', $ids ) ) . '">';
	echo '<ul class="atelier-media-list">';
	foreach ( $ids as $id ) {
		echo '<li data-id="' . (int) $id . '">' . wp_get_attachment_image( $id, 'thumbnail' ) . '<button type="button" class="atelier-media-remove" aria-label="' . esc_attr__( 'Remove', 'atelier-b2b' ) . '">&times;</button></li>';
	}
	echo '</ul>';
	echo '<button type="button" class="button atelier-media-select">' . ( $multiple ? esc_html__( 'Add gallery images', 'atelier-b2b' ) : esc_html__( 'Choose hover image', 'atelier-b2b' ) ) . '</button>';
	echo '</div>';
}

function atelier_product_media_box( $post ) {
	$hover   = get_post_meta( $post->ID, '_atelier_hover_image', true );
	$gallery = get_post_meta( $post->ID, '_atelier_gallery', true );
	echo '<p><strong>' . esc_html__( 'Secondary (hover) image', 'atelier-b2b' ) . '</strong><br><span class="description">' . esc_html__( 'Crossfades in when visitors hover the card. Same aspect ratio as the main image works best.', 'atelier-b2b' ) . '</span></p>';
	atelier_product_media_field( 'atelier_hover_image', $hover ? array( $hover ) : array(), false );
	echo '<hr><p><strong>' . esc_html__( 'Additional gallery images (Quick View)', 'atelier-b2b' ) . '</strong><br><span class="description">' . esc_html__( 'Drag to reorder. The main image is always the first gallery image.', 'atelier-b2b' ) . '</span></p>';
	atelier_product_media_field( 'atelier_gallery', $gallery ? explode( ',', $gallery ) : array(), true );
}

/** Keep only real attachment IDs. */
function atelier_sanitize_attachment_ids( $raw ) {
	$out = array();
	foreach ( array_filter( array_map( 'absint', explode( ',', (string) $raw ) ) ) as $id ) {
		if ( 'attachment' === get_post_type( $id ) ) {
			$out[] = $id;
		}
	}
	return array_values( array_unique( $out ) );
}

function atelier_product_save_meta( $post_id ) {
	if ( ! isset( $_POST['atelier_product_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['atelier_product_meta_nonce'] ) ), 'atelier_product_meta' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text = array(
		'atelier_ref'    => '_atelier_ref',
		'atelier_season' => '_atelier_season',
		'atelier_fabric' => '_atelier_fabric',
		'atelier_colors' => '_atelier_colors',
	);
	foreach ( $text as $field => $key ) {
		$val = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
		if ( '' === $val ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $val );
		}
	}

	if ( ! empty( $_POST['atelier_featured'] ) ) {
		update_post_meta( $post_id, '_atelier_featured', '1' );
	} else {
		delete_post_meta( $post_id, '_atelier_featured' ); // "EXISTS" = featured (used for ordering).
	}

	$hover = isset( $_POST['atelier_hover_image'] ) ? atelier_sanitize_attachment_ids( sanitize_text_field( wp_unslash( $_POST['atelier_hover_image'] ) ) ) : array();
	if ( $hover ) {
		update_post_meta( $post_id, '_atelier_hover_image', $hover[0] );
	} else {
		delete_post_meta( $post_id, '_atelier_hover_image' );
	}

	$gallery = isset( $_POST['atelier_gallery'] ) ? atelier_sanitize_attachment_ids( sanitize_text_field( wp_unslash( $_POST['atelier_gallery'] ) ) ) : array();
	if ( $gallery ) {
		update_post_meta( $post_id, '_atelier_gallery', implode( ',', $gallery ) );
	} else {
		delete_post_meta( $post_id, '_atelier_gallery' );
	}
}
add_action( 'save_post_atelier_product', 'atelier_product_save_meta' );

/* ---- List table columns ---- */
add_filter( 'manage_atelier_product_posts_columns', function ( $cols ) {
	$new = array();
	foreach ( $cols as $k => $label ) {
		if ( 'title' === $k ) {
			$new['atelier_thumb'] = __( 'Image', 'atelier-b2b' );
		}
		$new[ $k ] = $label;
	}
	$new['atelier_ref']      = __( 'Reference', 'atelier-b2b' );
	$new['atelier_featured'] = __( 'Featured', 'atelier-b2b' );
	$new['atelier_order']    = __( 'Order', 'atelier-b2b' );
	return $new;
} );
add_action( 'manage_atelier_product_posts_custom_column', function ( $col, $id ) {
	if ( 'atelier_thumb' === $col ) {
		echo get_the_post_thumbnail( $id, array( 56, 56 ) ) ?: '&mdash;'; // phpcs:ignore
	} elseif ( 'atelier_ref' === $col ) {
		echo esc_html( get_post_meta( $id, '_atelier_ref', true ) );
	} elseif ( 'atelier_featured' === $col ) {
		echo '1' === get_post_meta( $id, '_atelier_featured', true ) ? '&#9733;' : '&mdash;';
	} elseif ( 'atelier_order' === $col ) {
		echo (int) get_post_field( 'menu_order', $id );
	}
}, 10, 2 );
```

### `inc/collections/product-query.php`  —  ADD

```php
<?php
/**
 * Product queries + REST endpoints (grid pages for filtering / Load More, and lazy Quick View data).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

/** Whitelisted, sanitised grid arguments (also used for REST input). */
function atelier_collection_args( $in = array() ) {
	$in = wp_parse_args(
		(array) $in,
		array(
			'terms'    => array(),   // Allowed term slugs (empty = all).
			'term'     => '',        // Active filter slug.
			'orderby'  => 'menu',
			'per_page' => 9,
			'page'     => 1,
			'featured' => 'wide',
			'quick'    => true,
			'ref'      => true,
			'season'   => true,
		)
	);

	$terms = is_array( $in['terms'] ) ? $in['terms'] : explode( ',', (string) $in['terms'] );
	$terms = array_values( array_filter( array_map( 'sanitize_title', $terms ) ) );

	$orders = array( 'menu', 'featured', 'newest', 'oldest', 'title' );

	return array(
		'terms'    => $terms,
		'term'     => sanitize_title( (string) $in['term'] ),
		'orderby'  => in_array( $in['orderby'], $orders, true ) ? $in['orderby'] : 'menu',
		'per_page' => max( 1, min( 60, (int) $in['per_page'] ) ),
		'page'     => max( 1, (int) $in['page'] ),
		'featured' => 'none' === $in['featured'] ? 'none' : 'wide',
		'quick'    => filter_var( $in['quick'], FILTER_VALIDATE_BOOLEAN ),
		'ref'      => filter_var( $in['ref'], FILTER_VALIDATE_BOOLEAN ),
		'season'   => filter_var( $in['season'], FILTER_VALIDATE_BOOLEAN ),
	);
}

/** Tax query for the allowed set and/or active term. */
function atelier_collection_tax_query( $a ) {
	$tax = array();
	if ( $a['terms'] ) {
		$tax[] = array( 'taxonomy' => 'atelier_collection', 'field' => 'slug', 'terms' => $a['terms'] );
	}
	if ( $a['term'] ) {
		$tax[] = array( 'taxonomy' => 'atelier_collection', 'field' => 'slug', 'terms' => array( $a['term'] ) );
	}
	if ( count( $tax ) > 1 ) {
		$tax['relation'] = 'AND';
	}
	return $tax;
}

/** @return WP_Query */
function atelier_collection_query( $a ) {
	$args = array(
		'post_type'      => 'atelier_product',
		'post_status'    => 'publish',
		'posts_per_page' => $a['per_page'],
		'paged'          => $a['page'],
		'tax_query'      => atelier_collection_tax_query( $a ), // phpcs:ignore WordPress.DB.SlowDBQuery
		'no_found_rows'  => false,
	);

	switch ( $a['orderby'] ) {
		case 'featured':
			$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
				'relation' => 'OR',
				'feat'     => array( 'key' => '_atelier_featured', 'compare' => 'EXISTS' ),
				array( 'key' => '_atelier_featured', 'compare' => 'NOT EXISTS' ),
			);
			$args['orderby'] = array( 'feat' => 'DESC', 'menu_order' => 'ASC', 'date' => 'DESC' );
			break;
		case 'newest':
			$args['orderby'] = array( 'date' => 'DESC' );
			break;
		case 'oldest':
			$args['orderby'] = array( 'date' => 'ASC' );
			break;
		case 'title':
			$args['orderby'] = array( 'title' => 'ASC' );
			break;
		default:
			$args['orderby'] = array( 'menu_order' => 'ASC', 'date' => 'DESC' );
	}

	return new WP_Query( $args );
}

/**
 * Terms for the filter bar (ordered by "Display order", then name) with product counts.
 *
 * @return array[] slug, name, count
 */
function atelier_collection_filter_terms( $allowed, $hide_empty = false ) {
	$terms = get_terms( array( 'taxonomy' => 'atelier_collection', 'hide_empty' => false, 'slug' => $allowed ? $allowed : '' ) );
	if ( is_wp_error( $terms ) || ! $terms ) {
		return array();
	}
	usort( $terms, function ( $a, $b ) {
		$oa = get_term_meta( $a->term_id, '_atelier_term_order', true );
		$ob = get_term_meta( $b->term_id, '_atelier_term_order', true );
		$oa = '' === $oa ? PHP_INT_MAX : (int) $oa;
		$ob = '' === $ob ? PHP_INT_MAX : (int) $ob;
		return $oa === $ob ? strcasecmp( $a->name, $b->name ) : ( $oa < $ob ? -1 : 1 );
	} );

	$out = array();
	foreach ( $terms as $t ) {
		if ( $hide_empty && ! $t->count ) {
			continue;
		}
		$out[] = array( 'slug' => $t->slug, 'name' => $t->name, 'count' => (int) $t->count );
	}
	return $out;
}

/** Total products in the allowed set (the "All" count; avoids double-counting multi-category products). */
function atelier_collection_total( $allowed ) {
	$q = new WP_Query( array(
		'post_type'      => 'atelier_product',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'tax_query'      => atelier_collection_tax_query( array( 'terms' => $allowed, 'term' => '' ) ), // phpcs:ignore WordPress.DB.SlowDBQuery
	) );
	return (int) $q->found_posts;
}

/* ============================== REST ============================== */
function atelier_collection_register_rest() {
	register_rest_route( 'atelier/v1', '/collection', array(
		'methods'             => 'GET',
		'callback'            => 'atelier_rest_collection',
		'permission_callback' => '__return_true',
	) );
	register_rest_route( 'atelier/v1', '/product/(?P<id>\d+)', array(
		'methods'             => 'GET',
		'callback'            => 'atelier_rest_product',
		'permission_callback' => '__return_true',
		'args'                => array( 'id' => array( 'validate_callback' => 'is_numeric' ) ),
	) );
}
add_action( 'rest_api_init', 'atelier_collection_register_rest' );

function atelier_rest_collection( WP_REST_Request $req ) {
	$a = atelier_collection_args( array(
		'terms'    => $req->get_param( 'terms' ),
		'term'     => $req->get_param( 'term' ),
		'orderby'  => $req->get_param( 'orderby' ),
		'per_page' => $req->get_param( 'per_page' ),
		'page'     => $req->get_param( 'page' ),
		'featured' => $req->get_param( 'featured' ),
		'quick'    => null === $req->get_param( 'quick' ) ? true : $req->get_param( 'quick' ),
		'ref'      => null === $req->get_param( 'ref' ) ? true : $req->get_param( 'ref' ),
		'season'   => null === $req->get_param( 'season' ) ? true : $req->get_param( 'season' ),
	) );

	$q    = atelier_collection_query( $a );
	$html = atelier_collection_cards_html( $q, $a );

	return rest_ensure_response( array(
		'html'  => $html,
		'found' => (int) $q->found_posts,
		'pages' => (int) $q->max_num_pages,
		'page'  => $a['page'],
		'shown' => ( $a['page'] - 1 ) * $a['per_page'] + (int) $q->post_count,
	) );
}

function atelier_rest_product( WP_REST_Request $req ) {
	$post = get_post( (int) $req['id'] );
	if ( ! $post || 'atelier_product' !== $post->post_type || 'publish' !== $post->post_status ) {
		return new WP_Error( 'atelier_not_found', __( 'Product not found.', 'atelier-b2b' ), array( 'status' => 404 ) );
	}
	return rest_ensure_response( atelier_product_quickview_data( $post ) );
}
```

### `inc/collections/collection-helpers.php`  —  ADD

```php
<?php
/**
 * Collections helpers: asset registration, product data, markup assembly, page-template routing.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

const ATELIER_COLLECTIONS_TEMPLATE = 'page-templates/template-atelier-collections.php';

function atelier_is_collections_page() {
	return is_page_template( ATELIER_COLLECTIONS_TEMPLATE );
}

add_filter( 'body_class', function ( $classes ) {
	if ( atelier_is_collections_page() ) {
		$classes[] = 'atelier-collections-page';
	}
	return $classes;
} );

/* ---------------- Assets: registered always, enqueued only where needed ---------------- */
function atelier_collections_register_assets() {
	$uri = get_template_directory_uri();

	wp_register_style( 'atelier-collections', $uri . '/assets/css/collections.css', array( 'atelier-responsive' ), ATELIER_VERSION );
	wp_register_script(
		'atelier-collections',
		$uri . '/assets/js/collections.js',
		array( 'atelier-main' ),
		ATELIER_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

	$default = home_url( '/contact/' );
	wp_localize_script( 'atelier-collections', 'atelierCollections', array(
		'rest'    => esc_url_raw( rest_url( 'atelier/v1/' ) ),
		'inquiry' => esc_url_raw( apply_filters( 'atelier_inquiry_url', $default ) ),
		'i18n'    => array(
			'loading'  => __( 'Loading…', 'atelier-b2b' ),
			'error'    => __( 'We could not load this item. Please try again.', 'atelier-b2b' ),
			'retry'    => __( 'Try again', 'atelier-b2b' ),
			'showing'  => __( 'Showing %1$s of %2$s', 'atelier-b2b' ),
			'of'       => __( 'Image %1$s of %2$s', 'atelier-b2b' ),
		),
	) );
}
add_action( 'wp_enqueue_scripts', 'atelier_collections_register_assets', 5 );

/** The collections page template loads everything (incl. existing GSAP reveals); the widget declares its own deps. */
function atelier_collections_enqueue_page_assets() {
	if ( ! atelier_is_collections_page() ) {
		return;
	}
	wp_enqueue_style( 'atelier-collections' );
	wp_enqueue_script( 'atelier-collections' );

	// Reuse the theme's existing GSAP reveals ([data-lines], [data-fade], [data-clip], [data-parallax]).
	$uri = get_template_directory_uri();
	wp_enqueue_script( 'atelier-animations', $uri . '/assets/js/animations.js', array( 'atelier-scrolltrigger', 'atelier-main' ), ATELIER_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
}
add_action( 'wp_enqueue_scripts', 'atelier_collections_enqueue_page_assets', 20 );

/* ---------------- Product data ---------------- */
function atelier_product_terms_names( $post_id ) {
	$terms = get_the_terms( $post_id, 'atelier_collection' );
	return ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'name' ) : array();
}

/** Short description: excerpt, else trimmed content. */
function atelier_product_short_description( $post ) {
	$text = has_excerpt( $post ) ? $post->post_excerpt : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 45 );
	return trim( (string) $text );
}

/** Data for one grid card. */
function atelier_product_card_data( $post ) {
	$id       = $post->ID;
	$thumb    = get_post_thumbnail_id( $id );
	$hover_id = (int) get_post_meta( $id, '_atelier_hover_image', true );
	return array(
		'id'         => $id,
		'title'      => get_the_title( $post ),
		'ref'        => get_post_meta( $id, '_atelier_ref', true ),
		'season'     => get_post_meta( $id, '_atelier_season', true ),
		'categories' => atelier_product_terms_names( $id ),
		'featured'   => '1' === get_post_meta( $id, '_atelier_featured', true ),
		'thumb_id'   => $thumb ? (int) $thumb : 0,
		'hover_id'   => $hover_id && 'attachment' === get_post_type( $hover_id ) ? $hover_id : 0,
	);
}

/** Data for the Quick View drawer (lazy REST payload). */
function atelier_product_quickview_data( $post ) {
	$id      = $post->ID;
	$ids     = array();
	$thumb   = get_post_thumbnail_id( $id );
	if ( $thumb ) {
		$ids[] = (int) $thumb;
	}
	$hover = (int) get_post_meta( $id, '_atelier_hover_image', true );
	$extra = array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $id, '_atelier_gallery', true ) ) ) );
	foreach ( $extra as $x ) {
		$ids[] = $x;
	}
	if ( $hover && ! in_array( $hover, $ids, true ) ) {
		$ids[] = $hover;
	}
	$ids = array_values( array_unique( $ids ) );

	$gallery = array();
	foreach ( $ids as $att ) {
		$full = wp_get_attachment_image_url( $att, 'large' );
		if ( ! $full ) {
			continue;
		}
		$alt       = get_post_meta( $att, '_wp_attachment_image_alt', true );
		$gallery[] = array(
			'src'    => $full,
			'srcset' => (string) wp_get_attachment_image_srcset( $att, 'large' ),
			'thumb'  => wp_get_attachment_image_url( $att, 'thumbnail' ),
			'alt'    => $alt ? $alt : get_the_title( $post ),
		);
	}

	$colors = array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $id, '_atelier_colors', true ) ) ) );

	return array(
		'id'          => $id,
		'title'       => get_the_title( $post ),
		'ref'         => (string) get_post_meta( $id, '_atelier_ref', true ),
		'season'      => (string) get_post_meta( $id, '_atelier_season', true ),
		'categories'  => atelier_product_terms_names( $id ),
		'description' => atelier_product_short_description( $post ),
		'fabric'      => (string) get_post_meta( $id, '_atelier_fabric', true ),
		'colors'      => array_values( $colors ),
		'gallery'     => $gallery,
	);
}

/** Render the product cards of a query (shared by the page render and REST). */
function atelier_collection_cards_html( $query, $a ) {
	ob_start();
	$i = ( $a['page'] - 1 ) * $a['per_page'];
	foreach ( $query->posts as $post ) {
		get_template_part( 'template-parts/collections/product-card', null, array(
			'product' => atelier_product_card_data( $post ),
			'index'   => $i++,
			'opts'    => $a,
		) );
	}
	return ob_get_clean();
}

/** Remember that the Quick View drawer must be printed once in the footer. */
function atelier_collections_need_quickview() {
	static $needed = false;
	if ( ! $needed ) {
		$needed = true;
		add_action( 'wp_footer', function () {
			get_template_part( 'template-parts/collections/quick-view' );
		}, 5 );
	}
}

/**
 * Render the filter bar + grid. Used by the Elementor widget and by the default page template.
 *
 * @param array $s Settings: heading{label,title,text}, terms[], orderby, per_page, columns{d,t,m}, featured, filters,
 *                 sticky, count, quick, load_more, ref, season, hide_empty, inquiry, empty_title, empty_text, all_label.
 */
function atelier_collection_render( $s = array() ) {
	$s = wp_parse_args(
		$s,
		array(
			'label'      => '',
			'title'      => '',
			'text'       => '',
			'terms'      => array(),
			'orderby'    => 'menu',
			'per_page'   => 9,
			'cols_d'     => 3,
			'featured'   => 'wide',
			'filters'    => true,
			'sticky'     => true,
			'count'      => true,
			'quick'      => true,
			'load_more'  => true,
			'ref'        => true,
			'season'     => true,
			'hide_empty' => false,
			'inquiry'    => '',
			'all_label'  => __( 'All Collections', 'atelier-b2b' ),
			'more_label' => __( 'Load more', 'atelier-b2b' ),
			'empty_title' => __( 'Nothing here yet.', 'atelier-b2b' ),
			'empty_text'  => __( 'There are no products in this category at the moment. Please explore another collection.', 'atelier-b2b' ),
		)
	);

	$initial = isset( $_GET['collection'] ) ? sanitize_title( wp_unslash( $_GET['collection'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$a       = atelier_collection_args( array(
		'terms'    => $s['terms'],
		'term'     => $initial,
		'orderby'  => $s['orderby'],
		'per_page' => $s['per_page'],
		'featured' => $s['featured'],
		'quick'    => $s['quick'],
		'ref'      => $s['ref'],
		'season'   => $s['season'],
	) );

	$filter_terms = atelier_collection_filter_terms( $a['terms'], $s['hide_empty'] );
	$valid_slugs  = wp_list_pluck( $filter_terms, 'slug' );
	if ( $a['term'] && ! in_array( $a['term'], $valid_slugs, true ) ) {
		$a['term'] = '';
	}

	$query = atelier_collection_query( $a );

	if ( $a['quick'] ) {
		atelier_collections_need_quickview();
	}

	get_template_part( 'template-parts/collections/product-grid', null, array(
		's'            => $s,
		'a'            => $a,
		'query'        => $query,
		'filter_terms' => $filter_terms,
		'total'        => atelier_collection_total( $a['terms'] ),
		'cards_html'   => atelier_collection_cards_html( $query, $a ),
	) );
}

/** Contact-page URL used as inquiry destination. */
function atelier_collections_inquiry_url( $custom = '' ) {
	return $custom ? $custom : apply_filters( 'atelier_inquiry_url', home_url( '/contact/' ) );
}

/** <img> from an attachment ID with the theme's sizes/lazy defaults. */
function atelier_col_img( $id, $class, $sizes, $lazy = true, $size = 'large' ) {
	if ( ! $id ) {
		return '';
	}
	return wp_get_attachment_image( $id, $size, false, array(
		'class'    => $class,
		'sizes'    => $sizes,
		'loading'  => $lazy ? 'lazy' : 'eager',
		'decoding' => 'async',
	) );
}

/** Does this Collections page use Elementor content? (then the template prints the_content instead of the defaults) */
function atelier_collections_page_uses_elementor() {
	if ( ! function_exists( 'atelier_elementor_active' ) || ! atelier_elementor_active() ) {
		return false;
	}
	$id = get_queried_object_id();
	return $id && 'builder' === get_post_meta( $id, '_elementor_edit_mode', true );
}

/**
 * <img> for an Elementor MEDIA control value (or '' if empty); uses configured dimensions/alt when the URL is a
 * theme asset. Guarded so it can also live in sections-markup.php without clashing.
 */
if ( ! function_exists( 'atelier_media_img' ) ) {
	function atelier_media_img( $media, $args = array(), $fallback_key = '' ) {
		$args = wp_parse_args( $args, array( 'class' => '', 'loading' => 'lazy', 'sizes' => '100vw', 'alt' => '' ) );

		if ( ! empty( $media['id'] ) ) {
			$attr = array( 'class' => $args['class'], 'loading' => $args['loading'], 'sizes' => $args['sizes'], 'decoding' => 'async' );
			if ( '' !== $args['alt'] ) {
				$attr['alt'] = $args['alt'];
			}
			$html = wp_get_attachment_image( (int) $media['id'], 'full', false, $attr );
			if ( $html ) {
				return $html;
			}
		}
		if ( empty( $media['url'] ) ) {
			return '';
		}

		$dims = '';
		$alt  = $args['alt'];
		if ( $fallback_key ) {
			$assets = atelier_assets();
			if ( isset( $assets[ $fallback_key ] ) && atelier_asset_url( $fallback_key ) === $media['url'] ) {
				$dims = ' width="' . (int) $assets[ $fallback_key ]['w'] . '" height="' . (int) $assets[ $fallback_key ]['h'] . '"';
				if ( '' === $alt && isset( $assets[ $fallback_key ]['alt'] ) ) {
					$alt = $assets[ $fallback_key ]['alt'];
				}
			}
		}
		return sprintf(
			'<img src="%1$s"%2$s alt="%3$s" class="%4$s" loading="%5$s" decoding="async" sizes="%6$s">',
			esc_url( $media['url'] ),
			$dims,
			esc_attr( $alt ),
			esc_attr( $args['class'] ),
			esc_attr( $args['loading'] ),
			esc_attr( $args['sizes'] )
		);
	}
}
```

### `inc/collections/collection-demo.php`  —  ADD

```php
<?php
/**
 * Optional demo content (Atelier Products → Demo content): sample categories + products using the theme's
 * PLACEHOLDER images, so the Collections page can be previewed immediately. Clearly labelled, removable.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function () {
	add_submenu_page( 'edit.php?post_type=atelier_product', __( 'Demo content', 'atelier-b2b' ), __( 'Demo content', 'atelier-b2b' ), 'manage_options', 'atelier-demo-products', 'atelier_demo_page' );
} );

function atelier_demo_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'atelier-b2b' ) );
	}
	$done = isset( $_GET['atelier_demo'] ) ? sanitize_key( wp_unslash( $_GET['atelier_demo'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Atelier Products – Demo content', 'atelier-b2b' ); ?></h1>
		<?php if ( 'created' === $done ) : ?><div class="notice notice-success"><p><?php esc_html_e( 'Demo content created.', 'atelier-b2b' ); ?></p></div><?php endif; ?>
		<?php if ( 'removed' === $done ) : ?><div class="notice notice-success"><p><?php esc_html_e( 'Demo content removed.', 'atelier-b2b' ); ?></p></div><?php endif; ?>
		<p><?php esc_html_e( 'Creates 7 sample categories and 10 sample products that use the theme’s placeholder images (not real photography, no real product data). Replace or remove them before launch.', 'atelier-b2b' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:1em">
			<input type="hidden" name="action" value="atelier_demo_create">
			<?php wp_nonce_field( 'atelier_demo_create' ); ?>
			<?php submit_button( __( 'Create demo content', 'atelier-b2b' ), 'primary', 'submit', false ); ?>
		</form>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block">
			<input type="hidden" name="action" value="atelier_demo_remove">
			<?php wp_nonce_field( 'atelier_demo_remove' ); ?>
			<?php submit_button( __( 'Remove demo content', 'atelier-b2b' ), 'secondary', 'submit', false ); ?>
		</form>
	</div>
	<?php
}

/** Copy a theme image into the Media Library once; returns attachment ID. */
function atelier_demo_sideload( $asset_key, &$cache ) {
	if ( isset( $cache[ $asset_key ] ) ) {
		return $cache[ $asset_key ];
	}
	$assets = atelier_assets();
	if ( ! atelier_asset_exists( $asset_key ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$src = get_template_directory() . '/assets/' . $assets[ $asset_key ]['file'];
	$tmp = wp_tempnam( basename( $src ) );
	if ( ! $tmp || ! copy( $src, $tmp ) ) {
		return 0;
	}
	$id = media_handle_sideload( array( 'name' => 'demo-' . basename( $src ), 'tmp_name' => $tmp ), 0 );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp ); // phpcs:ignore
		return 0;
	}
	update_post_meta( $id, '_atelier_demo', '1' );
	update_post_meta( $id, '_wp_attachment_image_alt', 'Placeholder image' );
	$cache[ $asset_key ] = (int) $id;
	return (int) $id;
}

add_action( 'admin_post_atelier_demo_create', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'atelier-b2b' ), 403 );
	}
	check_admin_referer( 'atelier_demo_create' );
	@set_time_limit( 120 ); // phpcs:ignore

	$cats = array( 'Menswear', 'Womenswear', 'Streetwear', 'Essentials', 'Activewear', 'Custom Apparel' );
	$term = array();
	foreach ( $cats as $i => $name ) {
		$t = term_exists( $name, 'atelier_collection' );
		if ( ! $t ) {
			$t = wp_insert_term( $name, 'atelier_collection' );
		}
		if ( ! is_wp_error( $t ) ) {
			$term[ $name ] = (int) ( is_array( $t ) ? $t['term_id'] : $t );
			update_term_meta( $term[ $name ], '_atelier_term_order', $i + 1 );
			update_term_meta( $term[ $name ], '_atelier_demo', '1' );
		}
	}

	$imgs = array( 'category_men', 'category_women', 'category_street', 'category_essential', 'category_active', 'showcase_01', 'showcase_02', 'showcase_03', 'showcase_04', 'editorial' );
	$rows = array(
		array( 'Sample Product 01', 'DEMO-001', 'Menswear', 'category_men', 'showcase_01', true ),
		array( 'Sample Product 02', 'DEMO-002', 'Womenswear', 'category_women', 'showcase_02', false ),
		array( 'Sample Product 03', 'DEMO-003', 'Streetwear', 'category_street', 'showcase_04', false ),
		array( 'Sample Product 04', 'DEMO-004', 'Essentials', 'category_essential', 'category_men', false ),
		array( 'Sample Product 05', 'DEMO-005', 'Activewear', 'category_active', 'category_women', true ),
		array( 'Sample Product 06', 'DEMO-006', 'Menswear', 'showcase_04', 'category_street', false ),
		array( 'Sample Product 07', 'DEMO-007', 'Womenswear', 'editorial', 'showcase_01', false ),
		array( 'Sample Product 08', 'DEMO-008', 'Custom Apparel', 'showcase_02', 'category_essential', false ),
		array( 'Sample Product 09', 'DEMO-009', 'Streetwear', 'showcase_03', 'category_active', false ),
		array( 'Sample Product 10', 'DEMO-010', 'Essentials', 'category_women', 'showcase_03', false ),
	);

	$cache = array();
	foreach ( $rows as $n => $r ) {
		$id = wp_insert_post( array(
			'post_type'    => 'atelier_product',
			'post_status'  => 'publish',
			'post_title'   => $r[0],
			'post_excerpt' => 'Placeholder description. Replace with your own product copy.',
			'menu_order'   => $n + 1,
		) );
		if ( is_wp_error( $id ) || ! $id ) {
			continue;
		}
		update_post_meta( $id, '_atelier_demo', '1' );
		update_post_meta( $id, '_atelier_ref', $r[1] );
		update_post_meta( $id, '_atelier_season', 'Sample collection' );
		if ( $r[5] ) {
			update_post_meta( $id, '_atelier_featured', '1' );
		}
		if ( isset( $term[ $r[2] ] ) ) {
			wp_set_object_terms( $id, array( $term[ $r[2] ] ), 'atelier_collection' );
		}
		$main  = atelier_demo_sideload( $r[3], $cache );
		$hover = atelier_demo_sideload( $r[4], $cache );
		$g1    = atelier_demo_sideload( $imgs[ $n % count( $imgs ) ], $cache );
		if ( $main ) {
			set_post_thumbnail( $id, $main );
		}
		if ( $hover ) {
			update_post_meta( $id, '_atelier_hover_image', $hover );
		}
		$gallery = array_unique( array_filter( array( $hover, $g1 ) ) );
		if ( $gallery ) {
			update_post_meta( $id, '_atelier_gallery', implode( ',', $gallery ) );
		}
	}
	wp_safe_redirect( add_query_arg( 'atelier_demo', 'created', admin_url( 'edit.php?post_type=atelier_product&page=atelier-demo-products' ) ) );
	exit;
} );

add_action( 'admin_post_atelier_demo_remove', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'atelier-b2b' ), 403 );
	}
	check_admin_referer( 'atelier_demo_remove' );

	$products = get_posts( array( 'post_type' => 'atelier_product', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_atelier_demo', 'meta_value' => '1' ) ); // phpcs:ignore
	foreach ( $products as $id ) {
		wp_delete_post( $id, true );
	}
	$atts = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_atelier_demo', 'meta_value' => '1' ) ); // phpcs:ignore
	foreach ( $atts as $id ) {
		wp_delete_attachment( $id, true );
	}
	$terms = get_terms( array( 'taxonomy' => 'atelier_collection', 'hide_empty' => false, 'meta_key' => '_atelier_demo', 'meta_value' => '1' ) ); // phpcs:ignore
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $t ) {
			if ( 0 === (int) $t->count ) {
				wp_delete_term( $t->term_id, 'atelier_collection' );
			}
		}
	}
	wp_safe_redirect( add_query_arg( 'atelier_demo', 'removed', admin_url( 'edit.php?post_type=atelier_product&page=atelier-demo-products' ) ) );
	exit;
} );
```

### `inc/elementor/widgets/class-atelier-collection-grid.php`  —  ADD

```php
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
			'cols_d'      => (int) $s['cols_d'],
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
```

### `inc/elementor/widgets/class-atelier-collections-hero.php`  —  ADD

```php
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
		$ia = ! empty( $s['image_a']['url'] ) ? $s['image_a'] : array( 'url' => atelier_asset_url( 'showcase_01' ) );
		$ib = ! empty( $s['image_b']['url'] ) ? $s['image_b'] : array( 'url' => atelier_asset_url( 'showcase_03' ) );
		$a  = atelier_media_img( $ia, array( 'class' => 'chero__img', 'loading' => 'eager', 'sizes' => '(min-width:1024px) 34vw, 90vw' ), 'showcase_01' );
		$b  = atelier_media_img( $ib, array( 'class' => 'chero__img', 'sizes' => '(min-width:1024px) 28vw, 70vw' ), 'showcase_03' );

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
```

### `inc/elementor/widgets/class-atelier-collections-cta.php`  —  ADD

```php
<?php
/**
 * Elementor widget: Atelier Collections CTA (closing inquiry call-to-action).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Atelier_Collections_Cta_Widget extends Widget_Base {

	public function get_name() {
		return 'atelier-collections-cta';
	}

	public function get_title() {
		return esc_html__( 'Atelier Collections CTA', 'atelier-b2b' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return array( 'atelier' );
	}

	public function get_style_depends() {
		return array( 'atelier-collections' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'label', array( 'label' => esc_html__( 'Eyebrow', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Let’s build something together', 'atelier-b2b' ), 'label_block' => true ) );
		$this->add_control( 'title', array( 'label' => esc_html__( 'Headline', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => "SHARE YOUR\nCONCEPT." ) );
		$this->add_control( 'text', array( 'label' => esc_html__( 'Description', 'atelier-b2b' ), 'type' => Controls_Manager::TEXTAREA, 'default' => esc_html__( 'Tell us what you’re building, and we’ll take it from there.', 'atelier-b2b' ) ) );
		$this->add_control( 'cta_text', array( 'label' => esc_html__( 'Button text', 'atelier-b2b' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Start an inquiry', 'atelier-b2b' ) ) );
		$this->add_control( 'cta_link', array( 'label' => esc_html__( 'Button link', 'atelier-b2b' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => home_url( '/contact/' ) ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style', array( 'label' => esc_html__( 'Style', 'atelier-b2b' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'bg', array( 'label' => esc_html__( 'Background', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ccta' => 'background-color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'title_typo', 'label' => esc_html__( 'Headline', 'atelier-b2b' ), 'selector' => '{{WRAPPER}} .ccta__title' ) );
		$this->add_control( 'title_color', array( 'label' => esc_html__( 'Headline color', 'atelier-b2b' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ccta__title' => 'color: {{VALUE}};' ) ) );
		$this->add_responsive_control( 'pad', array( 'label' => esc_html__( 'Padding', 'atelier-b2b' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'rem' ), 'selectors' => array( '{{WRAPPER}} .ccta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		get_template_part( 'template-parts/collections/collection-cta', null, array(
			'label' => $s['label'],
			'title' => $s['title'],
			'text'  => $s['text'],
			'cta'   => array(
				'label' => $s['cta_text'],
				'url'   => ! empty( $s['cta_link']['url'] ) ? $s['cta_link']['url'] : '',
				'attrs' => atelier_link_attrs( $s['cta_link'] ),
			),
		) );
	}
}
```

### `page-templates/template-atelier-collections.php`  —  ADD

```php
<?php
/**
 * Template Name: Atelier Collections
 * Template Post Type: page
 *
 * Without Elementor content the page shows the default composition (hero → filterable product grid → CTA).
 * Once the page is built/edited with Elementor (e.g. by inserting the "Atelier Collections Page" template)
 * the Elementor content is printed instead. Header and footer are the global ones.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
get_header();

if ( atelier_collections_page_uses_elementor() || atelier_is_elementor_editor() ) {
	while ( have_posts() ) {
		the_post();
		the_content();
	}
} else {
	$assets = atelier_assets();
	ob_start();
	atelier_image( 'showcase_01', array( 'class' => 'chero__img', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width:1024px) 34vw, 90vw' ) );
	$img_a = ob_get_clean();
	ob_start();
	atelier_image( 'showcase_03', array( 'class' => 'chero__img', 'sizes' => '(min-width:1024px) 28vw, 70vw' ) );
	$img_b = ob_get_clean();

	get_template_part( 'template-parts/collections/collection-hero', null, array(
		'eyebrow'      => __( 'The Atelier Edit', 'atelier-b2b' ),
		'title'        => "EXPLORE OUR\nCOLLECTIONS.",
		'text'         => __( 'A considered selection of apparel, crafted for brands that value design, quality, and individuality.', 'atelier-b2b' ),
		'scroll'       => __( 'Scroll', 'atelier-b2b' ),
		'anchor'       => '#collection-grid',
		'image_a_html' => $img_a,
		'image_b_html' => $img_b,
	) );

	atelier_collection_render();

	get_template_part( 'template-parts/collections/collection-cta', null, array(
		'label' => __( 'Let’s build something together', 'atelier-b2b' ),
		'title' => "SHARE YOUR\nCONCEPT.",
		'text'  => __( 'Tell us what you’re building, and we’ll take it from there.', 'atelier-b2b' ),
		'cta'   => array( 'label' => __( 'Start an inquiry', 'atelier-b2b' ), 'url' => atelier_url( 'contact' ) ),
	) );
}

get_footer();
```

### `template-parts/collections/collection-hero.php`  —  ADD

```php
<?php
/**
 * Collections page hero (editorial, asymmetric). $args: eyebrow, title, text, scroll, image_a_html, image_b_html, anchor.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$d = $args;
?>
<section class="chero" aria-labelledby="chero-title">
	<div class="container chero__grid">
		<div class="chero__text">
			<?php if ( $d['eyebrow'] ) : ?><p class="label chero__eyebrow"><?php echo esc_html( $d['eyebrow'] ); ?></p><?php endif; ?>
			<h1 class="display chero__title" id="chero-title" data-lines aria-label="<?php echo esc_attr( atelier_plain( $d['title'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $d['title'] ); // phpcs:ignore ?></span></h1>
		</div>

		<?php if ( $d['image_a_html'] ) : ?>
		<figure class="chero__fig chero__fig--a" data-clip>
			<div class="parallax-wrap"><div class="parallax-img" data-parallax><?php echo $d['image_a_html']; // phpcs:ignore ?></div></div>
		</figure>
		<?php endif; ?>

		<div class="chero__foot">
			<?php if ( $d['text'] ) : ?><p class="lede chero__desc" data-fade><?php echo esc_html( $d['text'] ); ?></p><?php endif; ?>
			<?php if ( $d['scroll'] ) : ?>
				<a class="chero__scroll" href="<?php echo esc_attr( $d['anchor'] ); ?>"><span class="chero__scroll-line" aria-hidden="true"></span><span class="label"><?php echo esc_html( $d['scroll'] ); ?></span></a>
			<?php endif; ?>
		</div>

		<?php if ( $d['image_b_html'] ) : ?>
		<figure class="chero__fig chero__fig--b" data-clip>
			<div class="parallax-wrap"><div class="parallax-img" data-parallax><?php echo $d['image_b_html']; // phpcs:ignore ?></div></div>
		</figure>
		<?php endif; ?>
	</div>
</section>
```

### `template-parts/collections/category-filters.php`  —  ADD

```php
<?php
/**
 * Category filter bar. $args: terms[], total, active (slug), all_label, sticky.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$active = $args['active'];
?>
<nav class="cfilter<?php echo $args['sticky'] ? ' is-sticky' : ''; ?>" aria-label="<?php esc_attr_e( 'Collection categories', 'atelier-b2b' ); ?>" data-filters>
	<div class="cfilter__scroll" data-filter-scroll>
		<ul class="cfilter__list">
			<li>
				<button class="cfilter__btn<?php echo '' === $active ? ' is-active' : ''; ?>" type="button" data-term="" aria-pressed="<?php echo '' === $active ? 'true' : 'false'; ?>">
					<span class="cfilter__name"><?php echo esc_html( $args['all_label'] ); ?></span><sup class="cfilter__count"><?php echo (int) $args['total']; ?></sup>
				</button>
			</li>
			<?php foreach ( $args['terms'] as $t ) : ?>
				<li>
					<button class="cfilter__btn<?php echo $active === $t['slug'] ? ' is-active' : ''; ?>" type="button" data-term="<?php echo esc_attr( $t['slug'] ); ?>" aria-pressed="<?php echo $active === $t['slug'] ? 'true' : 'false'; ?>">
						<span class="cfilter__name"><?php echo esc_html( $t['name'] ); ?></span><sup class="cfilter__count"><?php echo (int) $t['count']; ?></sup>
					</button>
				</li>
			<?php endforeach; ?>
		</ul>
		<span class="cfilter__ind" aria-hidden="true" data-filter-ind></span>
	</div>
</nav>
```

### `template-parts/collections/product-grid.php`  —  ADD

```php
<?php
/**
 * Collections: heading, filter bar, grid, count, Load More, empty state.
 * $args: s (settings), a (query args), query, filter_terms, total, cards_html.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$s     = $args['s'];
$a     = $args['a'];
$q     = $args['query'];
$found = (int) $q->found_posts;
$pages = (int) $q->max_num_pages;
$shown = (int) $q->post_count;

$js = array(
	'terms'    => $a['terms'],
	'orderby'  => $a['orderby'],
	'per_page' => $a['per_page'],
	'featured' => $a['featured'],
	'quick'    => $a['quick'],
	'ref'      => $a['ref'],
	'season'   => $a['season'],
	'term'     => $a['term'],
	'page'     => 1,
	'pages'    => $pages,
	'found'    => $found,
	'inquiry'  => atelier_collections_inquiry_url( $s['inquiry'] ),
);
?>
<section class="cgrid" id="collection-grid" aria-labelledby="<?php echo $s['title'] ? 'cgrid-title' : 'collection-grid-label'; ?>" data-collection-grid data-settings="<?php echo esc_attr( wp_json_encode( $js ) ); ?>" data-cols="<?php echo (int) $s['cols_d']; ?>">
	<?php if ( $s['label'] || $s['title'] || $s['text'] ) : ?>
		<header class="container cgrid__head">
			<?php if ( $s['label'] ) : ?><p class="label cgrid__label"><?php echo esc_html( $s['label'] ); ?></p><?php endif; ?>
			<?php if ( $s['title'] ) : ?><h2 class="display cgrid__title" id="cgrid-title" data-lines aria-label="<?php echo esc_attr( atelier_plain( $s['title'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $s['title'] ); // phpcs:ignore ?></span></h2><?php endif; ?>
			<?php if ( $s['text'] ) : ?><p class="lede cgrid__text" data-fade><?php echo esc_html( $s['text'] ); ?></p><?php endif; ?>
		</header>
	<?php endif; ?>
	<h2 class="screen-reader-text" id="collection-grid-label"><?php esc_html_e( 'Products', 'atelier-b2b' ); ?></h2>

	<?php
	if ( $s['filters'] ) {
		get_template_part( 'template-parts/collections/category-filters', null, array(
			'terms'     => $args['filter_terms'],
			'total'     => $args['total'],
			'active'    => $a['term'],
			'all_label' => $s['all_label'],
			'sticky'    => $s['sticky'],
		) );
	}
	?>

	<div class="container cgrid__body">
		<?php if ( $s['count'] ) : ?>
			<p class="cgrid__status label" role="status" aria-live="polite" data-status>
				<?php echo esc_html( sprintf( __( 'Showing %1$s of %2$s', 'atelier-b2b' ), $shown, $found ) ); ?>
			</p>
		<?php else : ?>
			<p class="screen-reader-text" role="status" aria-live="polite" data-status></p>
		<?php endif; ?>

		<div class="cgrid__list" data-list>
			<?php echo $args['cards_html']; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped template parts. ?>
		</div>

		<div class="cgrid__empty" data-empty<?php echo $found ? ' hidden' : ''; ?>>
			<p class="display cgrid__empty-title"><?php echo esc_html( $s['empty_title'] ); ?></p>
			<p class="lede"><?php echo esc_html( $s['empty_text'] ); ?></p>
		</div>

		<?php if ( $s['load_more'] ) : ?>
			<div class="cgrid__more">
				<button class="btn btn--outline-dark" type="button" data-load-more<?php echo $pages > 1 ? '' : ' hidden'; ?>><span><?php echo esc_html( $s['more_label'] ); ?></span></button>
			</div>
		<?php endif; ?>
	</div>
</section>
```

### `template-parts/collections/product-card.php`  —  ADD

```php
<?php
/**
 * Single product card (editorial – no price, rating or cart).
 * $args: product (array), index (int), opts (grid args).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$p     = $args['product'];
$o     = $args['opts'];
$wide  = ( 'wide' === $o['featured'] ) && $p['featured'];
$sizes = $wide ? '(min-width:1100px) 66vw, (min-width:768px) 100vw, 100vw' : '(min-width:1100px) 33vw, (min-width:768px) 50vw, 100vw';
$cats  = implode( ', ', $p['categories'] );
$meta  = array_filter( array( $cats, $o['season'] ? $p['season'] : '' ) );
$tag   = $o['quick'] ? 'button' : 'div';

$main = atelier_col_img( $p['thumb_id'], 'pcard__img-el', $sizes, true );
$hov  = '';
if ( $p['hover_id'] ) {
	$src    = wp_get_attachment_image_url( $p['hover_id'], 'large' );
	$srcset = wp_get_attachment_image_srcset( $p['hover_id'], 'large' );
	if ( $src ) {
		$hov = '<img class="pcard__img-el" alt="" aria-hidden="true" decoding="async" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="' . esc_url( $src ) . '"' . ( $srcset ? ' data-srcset="' . esc_attr( $srcset ) . '"' : '' ) . ' sizes="' . esc_attr( $sizes ) . '">';
	}
}
?>
<article class="pcard<?php echo $wide ? ' is-wide' : ''; ?><?php echo $hov ? ' has-hover' : ''; ?>" data-product-id="<?php echo (int) $p['id']; ?>" style="--i:<?php echo (int) ( $args['index'] % 12 ); ?>">
	<<?php echo $tag; // phpcs:ignore ?> class="pcard__media"<?php echo $o['quick'] ? ' type="button" data-quick-view="' . (int) $p['id'] . '" aria-haspopup="dialog" aria-label="' . esc_attr( sprintf( /* translators: %s: product name */ __( 'Quick view: %s', 'atelier-b2b' ), $p['title'] ) ) . '"' : ''; // phpcs:ignore ?>>
		<span class="pcard__img pcard__img--a">
			<?php echo $main ? $main : '<span class="pcard__noimg">' . esc_html__( 'Image coming soon', 'atelier-b2b' ) . '</span>'; // phpcs:ignore ?>
		</span>
		<?php if ( $hov ) : ?>
			<span class="pcard__img pcard__img--b" data-hover-img><?php echo $hov; // phpcs:ignore ?></span>
		<?php endif; ?>
		<?php if ( $o['quick'] ) : ?>
			<span class="pcard__qv" aria-hidden="true"><span><?php esc_html_e( 'Quick view', 'atelier-b2b' ); ?></span><svg viewBox="0 0 16 16" width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M8 2v12M2 8h12"/></svg></span>
		<?php endif; ?>
	</<?php echo $tag; // phpcs:ignore ?>>

	<div class="pcard__meta">
		<div class="pcard__text">
			<h3 class="pcard__title"><?php echo esc_html( $p['title'] ); ?></h3>
			<?php if ( $meta ) : ?>
				<p class="pcard__cat"><?php echo esc_html( implode( ' · ', $meta ) ); ?></p>
			<?php endif; ?>
			<?php if ( $o['ref'] && $p['ref'] ) : ?>
				<p class="pcard__ref"><?php echo esc_html( sprintf( /* translators: %s: reference */ __( 'Ref. %s', 'atelier-b2b' ), $p['ref'] ) ); ?></p>
			<?php endif; ?>
		</div>
		<?php echo atelier_arrow( 'pcard__arrow' ); // phpcs:ignore ?>
	</div>
</article>
```

### `template-parts/collections/quick-view.php`  —  ADD

```php
<?php
/**
 * Quick View side drawer (printed once in the footer; filled lazily from the REST endpoint).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="qv" id="atelier-qv" role="dialog" aria-modal="true" aria-labelledby="qv-title" aria-hidden="true" hidden data-qv>
	<div class="qv__backdrop" data-qv-close></div>
	<div class="qv__panel" tabindex="-1">
		<button class="qv__close" type="button" data-qv-close aria-label="<?php esc_attr_e( 'Close quick view', 'atelier-b2b' ); ?>"><span aria-hidden="true"></span></button>

		<div class="qv__state" data-qv-state role="status" aria-live="polite"></div>

		<div class="qv__content" data-qv-content hidden>
			<div class="qv__gallery">
				<div class="qv__stage" data-qv-stage>
					<img class="qv__img is-on" alt="" data-qv-img="0">
					<img class="qv__img" alt="" aria-hidden="true" data-qv-img="1">
					<button class="qv__nav qv__nav--prev" type="button" data-qv-prev aria-label="<?php esc_attr_e( 'Previous image', 'atelier-b2b' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 12H5M11 6l-6 6 6 6"/></svg></button>
					<button class="qv__nav qv__nav--next" type="button" data-qv-next aria-label="<?php esc_attr_e( 'Next image', 'atelier-b2b' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
					<span class="qv__counter label" data-qv-counter aria-live="polite"></span>
				</div>
				<ul class="qv__thumbs" data-qv-thumbs></ul>
			</div>

			<div class="qv__info">
				<p class="label qv__cat" data-qv-cat></p>
				<h2 class="display qv__title" id="qv-title" data-qv-title></h2>
				<dl class="qv__facts" data-qv-facts></dl>
				<p class="qv__desc" data-qv-desc></p>
				<a class="btn btn--solid" href="#" data-qv-cta><span><?php esc_html_e( 'Inquire about this product', 'atelier-b2b' ); ?></span><?php echo atelier_arrow(); // phpcs:ignore ?></a>
			</div>
		</div>
	</div>
</div>
```

### `template-parts/collections/collection-cta.php`  —  ADD

```php
<?php
/**
 * Collections page closing CTA. $args: label, title, text, cta{label,url,attrs}.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$d = $args;
?>
<section class="ccta" aria-labelledby="ccta-title">
	<div class="container ccta__inner">
		<?php if ( $d['label'] ) : ?><p class="label ccta__label"><?php echo esc_html( $d['label'] ); ?></p><?php endif; ?>
		<h2 class="display ccta__title" id="ccta-title" data-lines aria-label="<?php echo esc_attr( atelier_plain( $d['title'] ) ); ?>"><span aria-hidden="true"><?php echo atelier_lines( $d['title'] ); // phpcs:ignore ?></span></h2>
		<div class="ccta__foot">
			<?php if ( $d['text'] ) : ?><p class="lede ccta__text" data-fade><?php echo esc_html( $d['text'] ); ?></p><?php endif; ?>
			<?php if ( ! empty( $d['cta']['label'] ) && ! empty( $d['cta']['url'] ) ) : ?>
			<div data-fade data-delay="0.12"><a class="btn btn--solid btn--lg" href="<?php echo esc_url( $d['cta']['url'] ); ?>"<?php echo isset( $d['cta']['attrs'] ) ? $d['cta']['attrs'] : ''; // phpcs:ignore ?>><span><?php echo esc_html( $d['cta']['label'] ); ?></span><?php echo atelier_arrow(); // phpcs:ignore ?></a></div>
			<?php endif; ?>
		</div>
	</div>
</section>
```

### `assets/css/collections.css`  —  ADD

```css
/* ==========================================================================
   Atelier B2B – Collections page (Phase 5). Loaded only on the Collections template
   or where an Atelier Collections widget is used. All selectors are component-scoped.
   ========================================================================== */

/* Elementor wrapper reset (containers add padding / gaps by default) */
.atelier-col-root,.atelier-col-root .e-con-inner{--gap:0px;--row-gap:0px;--column-gap:0px;--padding-top:0px;--padding-right:0px;--padding-bottom:0px;--padding-left:0px;--padding-block-start:0px;--padding-block-end:0px;--padding-inline-start:0px;--padding-inline-end:0px;--widgets-spacing:0px;max-width:none}
.atelier-col-root .elementor-widget{width:100%;max-width:100%;--container-widget-width:100%}
.atelier-col-root .elementor-widget:not(:last-child){margin-block-end:0}

/* 01 · Editorial hero -------------------------------------------------------- */
.chero{background:var(--c-bg);padding-top:calc(var(--bar-h) + var(--nav-h) + clamp(2rem,5vw,4.5rem));padding-bottom:clamp(3rem,7vw,6rem);overflow:hidden}
.chero__grid{display:grid;grid-template-columns:minmax(0,1fr);gap:clamp(1.6rem,4vw,2.4rem)}
.chero__eyebrow{display:flex;align-items:center;gap:1rem;color:var(--c-brown);margin-bottom:clamp(1rem,2.4vw,1.8rem)}
.chero__eyebrow::before{content:"";width:2.5rem;height:1px;background:currentColor}
.chero__title{font-size:clamp(2rem,10.4vw,5.6rem);line-height:.92;letter-spacing:-.05em;color:var(--c-text)}
.chero__fig{margin:0;position:relative}
.chero__fig .parallax-wrap{aspect-ratio:4/5}
.chero__fig--b{width:72%;margin-left:auto}
.chero__fig--b .parallax-wrap{aspect-ratio:4/3}
.chero__img{width:100%;height:100%;object-fit:cover}
.chero__foot{display:flex;flex-direction:column;gap:1.6rem;max-width:30rem}
.chero__desc{color:var(--c-text)}
.chero__scroll{display:inline-flex;align-items:center;gap:1rem;color:var(--c-brown)}
.chero__scroll-line{display:block;width:1px;height:3rem;background:currentColor;animation:scrollCue 2.6s var(--ease) infinite}
.chero__fig--a{order:2}
.chero__foot{order:3}
.chero__fig--b{order:4}

@media (min-width:1024px){
  .chero{padding-bottom:clamp(5rem,8vw,9rem)}
  .chero__grid{grid-template-columns:repeat(12,minmax(0,1fr));column-gap:var(--gap);row-gap:0}
  .chero__title{font-size:clamp(3rem,7.2vw,8.6rem)}
  .chero__text{grid-column:1/9;grid-row:1}
  .chero__fig--a{grid-column:9/13;grid-row:1/span 3;order:0}
  .chero__fig--a .parallax-wrap{aspect-ratio:3/4.3}
  .chero__foot{grid-column:1/5;grid-row:2;order:0;align-self:start;margin-top:clamp(2rem,5vw,5rem)}
  .chero__fig--b{grid-column:5/9;grid-row:2/span 2;order:0;width:100%;margin-top:clamp(2rem,6vw,7rem)}
}

/* 02 · Filter bar ------------------------------------------------------------ */
.cfilter{background:rgba(245,240,232,.93);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px);border-block:1px solid rgba(118,90,70,.16);z-index:40}
.cfilter.is-sticky{position:sticky;top:0}
.cfilter__scroll{position:relative;overflow-x:auto;scrollbar-width:none;padding-inline:var(--gutter);overscroll-behavior-x:contain}
.cfilter__scroll::-webkit-scrollbar{display:none}
.cfilter__list{display:flex;gap:clamp(1.6rem,3.2vw,3.4rem);width:max-content;min-width:100%}
.cfilter__btn{position:relative;display:inline-flex;align-items:flex-start;padding:1.35rem 0 1.3rem;font-family:var(--f-head);font-weight:600;font-size:.72rem;line-height:1;letter-spacing:.16em;text-transform:uppercase;color:var(--c-text);opacity:.55;white-space:nowrap;transition:opacity .4s var(--ease),color .4s var(--ease)}
.cfilter__btn:hover,.cfilter__btn:focus-visible{opacity:1}
.cfilter__btn.is-active{opacity:1;color:var(--c-brown)}
.cfilter__count{margin-left:.5em;font-family:var(--f-serif);font-style:italic;font-weight:500;font-size:1.05rem;letter-spacing:0;line-height:.7;text-transform:none}
.cfilter__ind{position:absolute;left:0;bottom:0;height:2px;width:var(--w,0);background:var(--c-brown);transform:translateX(var(--x,0));transition:transform .65s var(--ease-out),width .65s var(--ease-out)}

/* 03 · Product grid ---------------------------------------------------------- */
.cgrid{--cols:var(--cols-m,1);--aspect:4 / 5;--aspect-wide:3 / 2;background:var(--c-bg);padding-bottom:var(--section-y)}
.cgrid__head{display:grid;gap:1.4rem;padding-block:clamp(3rem,7vw,6rem) clamp(1.5rem,3vw,2.5rem)}
.cgrid__title{font-size:clamp(2rem,8vw,6rem)}
.cgrid__text{max-width:34rem}
.cgrid__body{padding-top:clamp(1.6rem,3vw,2.8rem)}
.cgrid__status{color:var(--c-brown);margin-bottom:clamp(1.6rem,3vw,2.6rem)}
.cgrid__list{display:grid;grid-template-columns:repeat(var(--cols),minmax(0,1fr));grid-auto-flow:dense;gap:clamp(2.4rem,5vw,5rem) clamp(1rem,2vw,2rem);transition:opacity .3s var(--ease),transform .5s var(--ease-out)}
.cgrid__list.is-swapping{opacity:0;transform:translateY(14px)}
.cgrid__empty{padding-block:clamp(3rem,8vw,8rem);text-align:left;max-width:40rem}
.cgrid__empty[hidden]{display:none}
.cgrid__empty-title{font-size:clamp(1.8rem,5vw,3.4rem);margin-bottom:1rem}
.cgrid__more{display:flex;justify-content:center;margin-top:clamp(3.5rem,7vw,6rem)}
.cgrid__more [hidden]{display:none}
.cgrid__more .btn[aria-busy="true"]{opacity:.55;pointer-events:none}

@media (min-width:768px){.cgrid{--cols:var(--cols-t,2)}}
@media (min-width:1100px){
  .cgrid{--cols:var(--cols-d,3)}
  .cgrid[data-cols="3"] .cgrid__list>.pcard:not(.is-wide):nth-child(3n+2){margin-top:clamp(2rem,6vw,6rem)}
}

/* Card */
.pcard{position:relative;min-width:0}
.pcard.is-wide{grid-column:1/-1}
@media (min-width:1100px){
  .pcard.is-wide{grid-column:span 2}
  .cgrid[data-cols="1"] .pcard.is-wide{grid-column:auto}
}
.pcard__media{position:relative;display:block;width:100%;aspect-ratio:var(--aspect);overflow:hidden;padding:0;background:var(--c-beige);border-radius:2px;color:inherit;text-align:left;cursor:default}
button.pcard__media{cursor:pointer}
.pcard.is-wide .pcard__media{aspect-ratio:var(--aspect-wide)}
.pcard__img{position:absolute;inset:0;display:block}
.pcard__img-el{display:block;width:100%;height:100%;object-fit:cover;transform:scale(1);transition:transform 1.5s var(--ease-out)}
.pcard__img--b{opacity:0;transition:opacity 1s var(--ease)}
.pcard__img--b .pcard__img-el{transform:scale(1.07)}
.pcard__noimg{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-family:var(--f-head);font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--c-brown)}
.pcard__media:hover .pcard__img--a .pcard__img-el,.pcard__media:focus-visible .pcard__img--a .pcard__img-el{transform:scale(1.045)}
.pcard__media:hover .pcard__img--b,.pcard__media:focus-visible .pcard__img--b{opacity:1}
.pcard__media:hover .pcard__img--b .pcard__img-el,.pcard__media:focus-visible .pcard__img--b .pcard__img-el{transform:scale(1)}

.pcard__qv{position:absolute;left:50%;bottom:1.1rem;z-index:2;display:inline-flex;align-items:center;gap:.7em;padding:.85em 1.3em;border-radius:999px;background:var(--c-light);color:var(--c-dark);font-family:var(--f-head);font-weight:600;font-size:.64rem;line-height:1;letter-spacing:.16em;text-transform:uppercase;white-space:nowrap;opacity:0;transform:translate(-50%,14px);transition:opacity .5s var(--ease),transform .7s var(--ease-out)}
.pcard__media:hover .pcard__qv,.pcard__media:focus-visible .pcard__qv{opacity:1;transform:translate(-50%,0)}
@media (hover:none){.pcard__qv{opacity:1;transform:translate(-50%,0)}}

.pcard__meta{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-top:1rem;padding-top:.9rem;border-top:1px solid rgba(118,90,70,.22)}
.pcard__title{font-family:var(--f-head);font-weight:500;font-size:clamp(1.1rem,1.5vw,1.5rem);line-height:1.1;letter-spacing:-.03em;text-transform:uppercase;color:var(--c-text)}
.pcard__cat{margin-top:.35rem;font-family:var(--f-head);font-weight:600;font-size:.64rem;line-height:1.5;letter-spacing:.14em;text-transform:uppercase;color:var(--c-brown)}
.pcard__ref{margin-top:.2rem;font-family:var(--f-serif);font-style:italic;font-size:1rem;color:var(--c-accent)}
.pcard__arrow{display:inline-flex;font-size:1.4rem;color:var(--c-brown);transition:transform .6s var(--ease-out)}
.pcard:hover .pcard__arrow{transform:translate(4px,-4px)}

/* Entrance (only once collections.js is running, so nothing is hidden without JS) */
.cgrid.is-ready .pcard:not(.is-in){opacity:0;transform:translateY(28px)}
.cgrid.is-ready .pcard{transition:opacity .9s var(--ease) calc(var(--i) * 60ms),transform 1.1s var(--ease-out) calc(var(--i) * 60ms)}

/* Quick View drawer ---------------------------------------------------------- */
html.qv-open{overflow:hidden}
.qv{position:fixed;inset:0;z-index:300;visibility:hidden}
.qv[hidden]{display:none}
.qv.is-open{visibility:visible}
.qv__backdrop{position:absolute;inset:0;background:rgba(73,56,45,.42);-webkit-backdrop-filter:blur(3px);backdrop-filter:blur(3px);opacity:0;transition:opacity .5s var(--ease)}
.qv.is-open .qv__backdrop{opacity:1}
.qv__panel{position:absolute;top:0;right:0;bottom:0;display:flex;flex-direction:column;width:min(68rem,100%);background:var(--c-bg);color:var(--c-text);transform:translateX(100%);transition:transform .75s var(--ease-out);outline:none}
.qv.is-open .qv__panel{transform:none}
.qv__close{position:absolute;top:1rem;right:1rem;z-index:6;width:2.8rem;height:2.8rem;border-radius:50%;background:var(--c-light);color:var(--c-dark);box-shadow:0 0 0 1px rgba(118,90,70,.2);transition:background-color .4s,color .4s}
.qv__close:hover{background:var(--c-brown);color:var(--c-light)}
.qv__close span{position:absolute;inset:0}
.qv__close span::before,.qv__close span::after{content:"";position:absolute;left:50%;top:50%;width:1.1rem;height:1.5px;background:currentColor}
.qv__close span::before{transform:translate(-50%,-50%) rotate(45deg)}
.qv__close span::after{transform:translate(-50%,-50%) rotate(-45deg)}
.qv__state{position:absolute;inset:0;z-index:5;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1.4rem;padding:2rem;text-align:center;font-family:var(--f-serif);font-style:italic;font-size:1.4rem;background:var(--c-bg)}
.qv__state[hidden]{display:none}
.qv__content{flex:1;min-height:0;overflow-y:auto;display:grid;align-content:start}
.qv__content[hidden]{display:none}
.qv__stage{position:relative;aspect-ratio:4/5;background:var(--c-beige);overflow:hidden}
.qv__img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0;transition:opacity .7s var(--ease)}
.qv__img.is-on{opacity:1}
.qv__nav{position:absolute;bottom:1rem;z-index:3;display:inline-flex;align-items:center;justify-content:center;width:3rem;height:3rem;border-radius:50%;background:var(--c-light);color:var(--c-dark);transition:background-color .4s,color .4s}
.qv__nav:hover{background:var(--c-brown);color:var(--c-light)}
.qv__nav--prev{right:4.4rem}
.qv__nav--next{right:1rem}
.qv__nav[hidden],.qv__counter[hidden]{display:none}
.qv__counter{position:absolute;left:1rem;bottom:1.2rem;z-index:3;padding:.5em .8em;background:var(--c-light);color:var(--c-dark);border-radius:999px}
.qv__thumbs{display:flex;gap:.6rem;padding:.8rem 1rem;overflow-x:auto;scrollbar-width:thin}
.qv__thumbs:empty{display:none}
.qv__thumb{flex:0 0 4rem;aspect-ratio:4/5;overflow:hidden;border-radius:2px;opacity:.55;outline-offset:2px;transition:opacity .3s}
.qv__thumb img{width:100%;height:100%;object-fit:cover}
.qv__thumb[aria-current="true"],.qv__thumb:hover{opacity:1;box-shadow:0 0 0 1.5px var(--c-brown)}
.qv__info{display:flex;flex-direction:column;align-items:flex-start;gap:1.2rem;padding:clamp(1.6rem,4vw,3.4rem)}
.qv__cat{color:var(--c-brown)}
.qv__title{font-size:clamp(1.9rem,4vw,3.2rem);line-height:.98}
.qv__facts{display:grid;grid-template-columns:auto 1fr;gap:.55rem 1.4rem;margin:0;width:100%;padding-block:1rem;border-block:1px solid rgba(118,90,70,.22)}
.qv__facts:empty{display:none}
.qv__facts dt{font-family:var(--f-head);font-weight:600;font-size:.64rem;letter-spacing:.16em;text-transform:uppercase;color:var(--c-brown);padding-top:.25rem}
.qv__facts dd{margin:0}
.qv__desc{max-width:34rem;white-space:pre-line}
.qv__desc:empty{display:none}
.qv__info .btn{margin-top:.6rem}
@media (min-width:900px){
  .qv__content{grid-template-columns:1.05fr 1fr}
  .qv__gallery{align-self:start;position:sticky;top:0}
}

/* 06 · Closing CTA ----------------------------------------------------------- */
.ccta{background:var(--c-bg-2);padding-block:clamp(5rem,12vw,11rem);overflow:hidden}
.ccta__label{color:var(--c-brown);margin-bottom:clamp(1.2rem,3vw,2.2rem)}
.ccta__title{font-size:clamp(2rem,9.2vw,9rem);line-height:.9;letter-spacing:-.05em}
.ccta__foot{display:flex;flex-direction:column;align-items:flex-start;gap:1.8rem;margin-top:clamp(2.2rem,5vw,4rem);padding-top:clamp(1.6rem,3vw,2.4rem);border-top:1px solid rgba(118,90,70,.3)}
.ccta__text{max-width:28ch}
@media (min-width:768px){.ccta__foot{flex-direction:row;align-items:flex-end;justify-content:space-between}}

/* Reduced motion */
@media (prefers-reduced-motion:reduce){
  .pcard,.pcard__img-el,.pcard__img--b,.pcard__qv,.cgrid__list,.qv__panel,.qv__backdrop,.qv__img,.cfilter__ind{transition:none!important}
  .cgrid.is-ready .pcard:not(.is-in){opacity:1;transform:none}
  .chero__scroll-line{animation:none}
}
```

### `assets/css/admin-product.css`  —  ADD

```css
.atelier-media-list{display:flex;flex-wrap:wrap;gap:8px;margin:0 0 10px;padding:0;list-style:none}
.atelier-media-list li{position:relative;width:84px;height:84px;background:#eee;cursor:move}
.atelier-media-list img{width:100%;height:100%;object-fit:cover;display:block}
.atelier-media-remove{position:absolute;top:2px;right:2px;width:22px;height:22px;border:0;border-radius:50%;background:#1d2327;color:#fff;line-height:20px;cursor:pointer}
.atelier-fields th{width:220px}
```

### `assets/js/collections.js`  —  ADD

```javascript
/* Atelier B2B – Collections: category filtering (no reload), Load More, hover-image preloading, Quick View drawer.
   One init per grid root ([data-ready] guard) so Elementor editor re-renders never duplicate listeners. */
(function () {
  'use strict';

  var cfg = window.atelierCollections || { rest: '/wp-json/atelier/v1/', inquiry: '/contact/', i18n: {} };
  var t = cfg.i18n || {};
  var htmlEl = document.documentElement;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var canHover = window.matchMedia('(hover: hover)').matches;
  var saveData = navigator.connection && navigator.connection.saveData;

  function fmt(s, a, b) { return String(s || '').replace('%1$s', a).replace('%2$s', b); }
  function safeUrl(u) { return /^https?:\/\//i.test(u) || u.charAt(0) === '/' ? u : ''; }

  /* =========================== Quick View =========================== */
  var qv = (function () {
    var el = document.getElementById('atelier-qv');
    if (!el) { return null; }

    var panel = el.querySelector('.qv__panel');
    var stateEl = el.querySelector('[data-qv-state]');
    var content = el.querySelector('[data-qv-content]');
    var layers = [el.querySelector('[data-qv-img="0"]'), el.querySelector('[data-qv-img="1"]')];
    var thumbsEl = el.querySelector('[data-qv-thumbs]');
    var counter = el.querySelector('[data-qv-counter]');
    var titleEl = el.querySelector('[data-qv-title]');
    var catEl = el.querySelector('[data-qv-cat]');
    var facts = el.querySelector('[data-qv-facts]');
    var desc = el.querySelector('[data-qv-desc]');
    var cta = el.querySelector('[data-qv-cta]');
    var prevBtn = el.querySelector('[data-qv-prev]');
    var nextBtn = el.querySelector('[data-qv-next]');
    var closeBtn = el.querySelector('.qv__close');

    var cache = {}, data = null, idx = 0, layer = 0, lastFocus = null, base = cfg.inquiry, token = 0, timer = 0;

    function setState(msg, retry) {
      stateEl.textContent = '';
      if (msg) {
        var p = document.createElement('p');
        p.textContent = msg;
        stateEl.appendChild(p);
        if (retry) {
          var b = document.createElement('button');
          b.type = 'button'; b.className = 'btn btn--outline-dark';
          var s = document.createElement('span'); s.textContent = t.retry || 'Try again';
          b.appendChild(s); b.addEventListener('click', retry);
          stateEl.appendChild(b);
        }
      }
      stateEl.hidden = !msg;
    }

    function load(id) {
      if (cache[id]) { return Promise.resolve(cache[id]); }
      return fetch(cfg.rest + 'product/' + encodeURIComponent(id), { headers: { Accept: 'application/json' } })
        .then(function (r) { if (!r.ok) { throw new Error('http ' + r.status); } return r.json(); })
        .then(function (d) { cache[id] = d; return d; });
    }

    function addFact(label, value) {
      if (!value) { return; }
      var dt = document.createElement('dt'); dt.textContent = label;
      var dd = document.createElement('dd'); dd.textContent = value;
      facts.appendChild(dt); facts.appendChild(dd);
    }

    function show(i, instant) {
      var g = data.gallery;
      if (!g.length) { return; }
      idx = (i + g.length) % g.length;
      var item = g[idx];
      var next = instant ? layer : 1 - layer;
      var img = layers[next];
      var done = function () {
        layers[layer].classList.remove('is-on'); layers[layer].setAttribute('aria-hidden', 'true');
        img.classList.add('is-on'); img.removeAttribute('aria-hidden');
        layer = next;
      };
      img.onload = done;
      img.alt = item.alt || '';
      if (item.srcset) { img.srcset = item.srcset; img.sizes = '(min-width:900px) 34rem, 100vw'; } else { img.removeAttribute('srcset'); }
      img.src = safeUrl(item.src);
      if (img.complete && img.naturalWidth) { done(); }

      [].forEach.call(thumbsEl.children, function (li, n) {
        li.firstChild.setAttribute('aria-current', n === idx ? 'true' : 'false');
      });
      counter.textContent = fmt(t.of || '%1$s / %2$s', idx + 1, g.length);
      // Warm the neighbour image only now (galleries are never preloaded in advance).
      [1, -1].forEach(function (d) { var n = g[(idx + d + g.length) % g.length]; if (n && n.src) { (new Image()).src = safeUrl(n.src); } });
    }

    function render(d) {
      data = d; idx = 0;
      titleEl.textContent = d.title || '';
      catEl.textContent = [(d.categories || []).join(', '), d.season].filter(Boolean).join(' · ');
      facts.textContent = '';
      addFact('Ref.', d.ref);
      addFact('Fabric', d.fabric);
      addFact('Colours', (d.colors || []).join(', '));
      desc.textContent = d.description || '';

      var sep = base.indexOf('?') === -1 ? '?' : '&';
      cta.href = base + sep + 'product=' + encodeURIComponent(d.ref || d.title) + '&product_name=' + encodeURIComponent(d.title || '');

      thumbsEl.textContent = '';
      var multi = (d.gallery || []).length > 1;
      (d.gallery || []).forEach(function (g, n) {
        var li = document.createElement('li');
        var b = document.createElement('button');
        b.type = 'button'; b.className = 'qv__thumb';
        b.setAttribute('aria-label', fmt(t.of || 'Image %1$s of %2$s', n + 1, d.gallery.length));
        var im = document.createElement('img'); im.alt = ''; im.loading = 'lazy'; im.src = safeUrl(g.thumb || g.src);
        b.appendChild(im);
        b.addEventListener('click', function () { show(n); });
        li.appendChild(b); thumbsEl.appendChild(li);
      });
      thumbsEl.hidden = !multi;
      prevBtn.hidden = nextBtn.hidden = counter.hidden = !multi;
      layers[0].classList.remove('is-on'); layers[1].classList.remove('is-on');
      show(0, true);
    }

    function focusables() {
      return [].filter.call(el.querySelectorAll('a[href], button:not([disabled])'), function (n) {
        return n.offsetParent !== null && !n.hidden;
      });
    }

    function open(id, trigger, inquiry) {
      window.clearTimeout(timer);
      lastFocus = trigger || document.activeElement;
      base = inquiry || cfg.inquiry;
      el.hidden = false;
      el.setAttribute('aria-hidden', 'false');
      void el.offsetWidth;                       // let the browser register the closed state
      el.classList.add('is-open');
      htmlEl.classList.add('qv-open');
      content.hidden = true;
      setState(t.loading || 'Loading…');
      window.setTimeout(function () { closeBtn.focus(); }, reduce ? 0 : 120);

      var mine = ++token;
      load(id).then(function (d) {
        if (mine !== token) { return; }
        setState('');
        content.hidden = false;
        render(d);
      }).catch(function () {
        if (mine !== token) { return; }
        setState(t.error || 'Could not load.', function () { open(id, lastFocus, base); });
      });
    }

    function close() {
      if (el.hidden) { return; }
      token++;
      el.classList.remove('is-open');
      el.setAttribute('aria-hidden', 'true');
      htmlEl.classList.remove('qv-open');
      timer = window.setTimeout(function () { el.hidden = true; }, reduce ? 0 : 750);
      if (lastFocus && lastFocus.focus) { lastFocus.focus(); }
    }

    el.addEventListener('click', function (e) { if (e.target.closest('[data-qv-close]')) { close(); } });
    prevBtn.addEventListener('click', function () { if (data) { show(idx - 1); } });
    nextBtn.addEventListener('click', function () { if (data) { show(idx + 1); } });
    document.addEventListener('keydown', function (e) {
      if (el.hidden || !el.classList.contains('is-open')) { return; }
      if (e.key === 'Escape') { e.preventDefault(); close(); return; }
      if (e.key === 'ArrowLeft' && data) { show(idx - 1); }
      if (e.key === 'ArrowRight' && data) { show(idx + 1); }
      if (e.key === 'Tab') {
        var f = focusables();
        if (!f.length) { e.preventDefault(); panel.focus(); return; }
        var first = f[0], last = f[f.length - 1];
        if (e.shiftKey && (document.activeElement === first || document.activeElement === panel)) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });

    return { open: open };
  })();

  /* =========================== Grid =========================== */
  function initGrid(root) {
    if (!root || root.getAttribute('data-ready')) { return; }
    root.setAttribute('data-ready', '1');

    var st = {};
    try { st = JSON.parse(root.getAttribute('data-settings') || '{}'); } catch (err) { st = {}; }

    var list = root.querySelector('[data-list]');
    var status = root.querySelector('[data-status]');
    var empty = root.querySelector('[data-empty]');
    var more = root.querySelector('[data-load-more]');
    var filters = root.querySelector('[data-filters]');
    var scroller = root.querySelector('[data-filter-scroll]');
    var ind = root.querySelector('[data-filter-ind]');
    var btns = filters ? [].slice.call(filters.querySelectorAll('[data-term]')) : [];

    var state = { term: st.term || '', page: 1, pages: st.pages || 1, found: st.found || 0, req: 0 };
    var cache = {};

    root.classList.add('is-ready');

    /* ---- entrance + hover-image preloading ---- */
    var revealIO = 'IntersectionObserver' in window ? new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('is-in'); revealIO.unobserve(en.target); }
      });
    }, { rootMargin: '0px 0px -6% 0px' }) : null;

    function loadHover(card) {
      var img = card.querySelector('img[data-src]');
      if (!img) { return; }
      if (img.getAttribute('data-srcset')) { img.srcset = img.getAttribute('data-srcset'); img.removeAttribute('data-srcset'); }
      img.src = img.getAttribute('data-src');
      img.removeAttribute('data-src');
    }

    var hoverIO = (canHover && !saveData && 'IntersectionObserver' in window) ? new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) { return; }
        hoverIO.unobserve(en.target);
        var run = function () { loadHover(en.target); };
        if (window.requestIdleCallback) { window.requestIdleCallback(run, { timeout: 2500 }); } else { window.setTimeout(run, 400); }
      });
    }, { rootMargin: '150px 0px' }) : null;

    function watch(scope) {
      [].forEach.call(scope.querySelectorAll('.pcard'), function (card) {
        if (revealIO) { revealIO.observe(card); } else { card.classList.add('is-in'); }
        if (card.classList.contains('has-hover') && hoverIO) { hoverIO.observe(card); }
      });
    }
    watch(list);

    list.addEventListener('pointerenter', function (e) {
      var c = e.target.closest && e.target.closest('.pcard.has-hover'); if (c) { loadHover(c); }
    }, true);
    list.addEventListener('focusin', function (e) {
      var c = e.target.closest('.pcard.has-hover'); if (c) { loadHover(c); }
    });

    /* ---- Quick View trigger (delegated) ---- */
    root.addEventListener('click', function (e) {
      var b = e.target.closest('[data-quick-view]');
      if (b && qv) { e.preventDefault(); qv.open(b.getAttribute('data-quick-view'), b, st.inquiry); }
    });

    /* ---- helpers ---- */
    function updateStatus() {
      var shown = list.querySelectorAll('.pcard').length;
      if (status) { status.textContent = fmt(t.showing || 'Showing %1$s of %2$s', shown, state.found); }
      if (empty) { empty.hidden = state.found > 0; }
      if (more) { more.hidden = state.page >= state.pages; more.removeAttribute('aria-busy'); }
    }

    function moveInd() {
      if (!ind) { return; }
      var active = filters.querySelector('.is-active');
      if (!active) { return; }
      ind.style.setProperty('--x', active.offsetLeft + 'px');
      ind.style.setProperty('--w', active.offsetWidth + 'px');
      if (scroller && scroller.scrollWidth > scroller.clientWidth) {
        scroller.scrollTo({ left: active.offsetLeft - (scroller.clientWidth - active.offsetWidth) / 2, behavior: reduce ? 'auto' : 'smooth' });
      }
    }

    function fetchPage(term, page) {
      var key = term + '|' + page;
      if (cache[key]) { return Promise.resolve(cache[key]); }
      var q = new URLSearchParams({
        term: term, page: page, per_page: st.per_page, orderby: st.orderby, featured: st.featured,
        terms: (st.terms || []).join(','), quick: st.quick ? 1 : 0, ref: st.ref ? 1 : 0, season: st.season ? 1 : 0
      });
      return fetch(cfg.rest + 'collection?' + q.toString(), { headers: { Accept: 'application/json' } })
        .then(function (r) { if (!r.ok) { throw new Error('http ' + r.status); } return r.json(); })
        .then(function (d) { cache[key] = d; return d; });
    }

    function sleep(ms) { return new Promise(function (res) { window.setTimeout(res, reduce ? 0 : ms); }); }

    /* ---- filtering ---- */
    function setTerm(term) {
      if (term === state.term) { return; }
      state.term = term;
      var mine = ++state.req;

      btns.forEach(function (b) {
        var on = b.getAttribute('data-term') === term;
        b.classList.toggle('is-active', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      moveInd();

      try {
        var u = new URL(window.location.href);
        if (term) { u.searchParams.set('collection', term); } else { u.searchParams.delete('collection'); }
        window.history.replaceState(null, '', u.toString());
      } catch (err) { /* ignore */ }

      list.classList.add('is-swapping');
      Promise.all([fetchPage(term, 1), sleep(260)]).then(function (res) {
        if (mine !== state.req) { return; }
        var d = res[0];
        list.innerHTML = d.html;
        state.page = 1; state.pages = d.pages; state.found = d.found;
        watch(list);
        updateStatus();
        window.requestAnimationFrame(function () { list.classList.remove('is-swapping'); });
      }).catch(function () {
        if (mine !== state.req) { return; }
        list.classList.remove('is-swapping');
        if (status) { status.textContent = t.error || 'Could not load.'; }
      });
    }

    btns.forEach(function (b) {
      b.addEventListener('click', function () { setTerm(b.getAttribute('data-term') || ''); });
    });

    /* ---- load more ---- */
    if (more) {
      more.addEventListener('click', function () {
        if (state.page >= state.pages || more.getAttribute('aria-busy') === 'true') { return; }
        more.setAttribute('aria-busy', 'true');
        var mine = state.req;
        var next = state.page + 1;
        fetchPage(state.term, next).then(function (d) {
          if (mine !== state.req) { return; }
          var tmp = document.createElement('div');
          tmp.innerHTML = d.html;
          var first = null;
          while (tmp.firstElementChild) {
            var node = tmp.firstElementChild;
            if (!first) { first = node; }
            list.appendChild(node);
          }
          state.page = next; state.pages = d.pages; state.found = d.found;
          watch(list);
          updateStatus();
          var f = first && first.querySelector('button.pcard__media');
          if (f) { f.focus({ preventScroll: true }); }
        }).catch(function () {
          more.removeAttribute('aria-busy');
          if (status) { status.textContent = t.error || 'Could not load.'; }
        });
      });
    }

    moveInd();
    window.addEventListener('resize', moveInd);
    if (document.fonts && document.fonts.ready) { document.fonts.ready.then(moveInd); }
    window.addEventListener('load', moveInd);
  }

  function initAll() { [].forEach.call(document.querySelectorAll('[data-collection-grid]'), initGrid); }
  initAll();

  // Elementor editor / dynamic re-renders: initialise newly inserted grids exactly once.
  window.addEventListener('elementor/frontend/init', function () {
    if (window.elementorFrontend && window.elementorFrontend.hooks) {
      window.elementorFrontend.hooks.addAction('frontend/element_ready/atelier-collection-grid.default', function ($el) {
        var node = $el && $el[0] ? $el[0] : $el;
        initGrid(node.querySelector ? node.querySelector('[data-collection-grid]') : null);
      });
    }
  });
})();
```

### `assets/js/admin-product.js`  —  ADD

```javascript
/* Atelier Products – Media Library pickers (hover image, sortable gallery). */
jQuery(function ($) {
  'use strict';
  $('.atelier-media-field').each(function () {
    var $f = $(this);
    var multi = String($f.data('multiple')) === '1';
    var $in = $f.find('input[type="hidden"]');
    var $list = $f.find('.atelier-media-list');
    var frame;

    function sync() {
      var ids = [];
      $list.children().each(function () { ids.push($(this).data('id')); });
      $in.val(ids.join(','));
    }

    $f.on('click', '.atelier-media-select', function (e) {
      e.preventDefault();
      if (!frame) {
        frame = wp.media({
          title: atelierProductAdmin.title,
          button: { text: atelierProductAdmin.button },
          library: { type: 'image' },
          multiple: multi ? 'add' : false
        });
        frame.on('select', function () {
          if (!multi) { $list.empty(); }
          frame.state().get('selection').each(function (att) {
            var a = att.toJSON();
            if ($list.find('[data-id="' + a.id + '"]').length) { return; }
            var url = (a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url;
            $list.append(
              $('<li>').attr('data-id', a.id)
                .append($('<img>').attr('src', url).attr('alt', ''))
                .append($('<button type="button" class="atelier-media-remove" aria-label="Remove">&times;</button>'))
            );
          });
          sync();
        });
      }
      frame.open();
    });

    $f.on('click', '.atelier-media-remove', function () {
      $(this).closest('li').remove();
      sync();
    });

    if (multi && $list.sortable) { $list.sortable({ update: sync, tolerance: 'pointer' }); }
  });
});
```

### `elementor-templates/atelier-collections-page.json`  —  ADD

```json
{
  "version": "0.4",
  "title": "Atelier Collections Page",
  "type": "page",
  "content": [
    {
      "id": "e5f6a01",
      "elType": "container",
      "isInner": false,
      "settings": {
        "content_width": "full",
        "flex_direction": "column",
        "padding": {
          "unit": "px",
          "top": "0",
          "right": "0",
          "bottom": "0",
          "left": "0",
          "isLinked": true
        },
        "css_classes": "atelier-col-root atelier-col-root--hero"
      },
      "elements": [
        {
          "id": "e5f6a02",
          "elType": "widget",
          "widgetType": "atelier-collections-hero",
          "isInner": false,
          "settings": {
            "eyebrow": "The Atelier Edit",
            "title": "EXPLORE OUR\nCOLLECTIONS.",
            "text": "A considered selection of apparel, crafted for brands that value design, quality, and individuality.",
            "scroll": "Scroll"
          },
          "elements": []
        }
      ]
    },
    {
      "id": "e5f6a10",
      "elType": "container",
      "isInner": false,
      "settings": {
        "content_width": "full",
        "flex_direction": "column",
        "padding": {
          "unit": "px",
          "top": "0",
          "right": "0",
          "bottom": "0",
          "left": "0",
          "isLinked": true
        },
        "css_classes": "atelier-col-root atelier-col-root--grid"
      },
      "elements": [
        {
          "id": "e5f6a11",
          "elType": "widget",
          "widgetType": "atelier-collection-grid",
          "isInner": false,
          "settings": {
            "orderby": "menu",
            "per_page": 9,
            "load_more": "yes",
            "more_label": "Load more",
            "filters": "yes",
            "all_label": "All Collections",
            "sticky": "yes",
            "count": "yes",
            "quick": "yes",
            "ref": "yes",
            "season": "yes",
            "featured": "wide"
          },
          "elements": []
        }
      ]
    },
    {
      "id": "e5f6a20",
      "elType": "container",
      "isInner": false,
      "settings": {
        "content_width": "full",
        "flex_direction": "column",
        "padding": {
          "unit": "px",
          "top": "0",
          "right": "0",
          "bottom": "0",
          "left": "0",
          "isLinked": true
        },
        "css_classes": "atelier-col-root atelier-col-root--cta"
      },
      "elements": [
        {
          "id": "e5f6a21",
          "elType": "widget",
          "widgetType": "atelier-collections-cta",
          "isInner": false,
          "settings": {
            "label": "Let’s build something together",
            "title": "SHARE YOUR\nCONCEPT.",
            "text": "Tell us what you’re building, and we’ll take it from there.",
            "cta_text": "Start an inquiry",
            "cta_link": {
              "url": "/contact/",
              "is_external": "",
              "nofollow": "",
              "custom_attributes": ""
            }
          },
          "elements": []
        }
      ]
    }
  ],
  "page_settings": []
}
```
