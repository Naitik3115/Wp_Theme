<?php
/**
 * Canvas used to edit / preview an "Atelier Header" in Elementor.
 * Intentionally does NOT call get_header() so the global header is never rendered recursively.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'atelier-header-preview' ); ?>>
<?php wp_body_open(); ?>
<div class="atelier-preview-stage">
	<header class="site-header atelier-site-header atelier-elementor-header is-top">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</header>
	<p class="atelier-preview-note"><?php esc_html_e( 'Header preview – shown over a sample hero background (transparent state). Scroll behaviour is disabled while editing.', 'atelier-b2b' ); ?></p>
</div>
<?php wp_footer(); ?>
</body>
</html>
