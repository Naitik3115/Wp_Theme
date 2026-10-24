<?php
/**
 * Canvas used to edit / preview an Atelier home section in Elementor.
 * Does not call get_header()/get_footer(), so the global header and other sections never render here.
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
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="atelier-section-stage">
	<main id="main">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</main>
	<p class="atelier-section-note"><?php esc_html_e( 'Section canvas – the site header is not shown here.', 'atelier-b2b' ); ?></p>
</div>
<?php wp_footer(); ?>
</body>
</html>
