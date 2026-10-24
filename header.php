<?php
/**
 * Global header.
 *
 * Renders the active Elementor header (CPT "atelier_header") when available,
 * otherwise the original static PHP header. Never outputs a blank header.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

$overlay        = atelier_has_hero();
$state_class    = $overlay ? 'is-top' : 'is-scrolled is-solid';
$elementor_html = atelier_render_elementor_header(); // '' when Elementor header is unavailable.
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#F5F0E8">
	<script>document.documentElement.classList.add('js');</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="top"></div>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'atelier-b2b' ); ?></a>

<?php if ( '' !== $elementor_html ) : ?>
<header id="site-header" class="site-header atelier-site-header atelier-elementor-header <?php echo esc_attr( $state_class ); ?>" data-header data-overlay="<?php echo $overlay ? 'true' : 'false'; ?>">
	<?php echo $elementor_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor-rendered, already escaped. ?>
</header>
<?php else : ?>
<header id="site-header" class="site-header <?php echo esc_attr( $state_class ); ?>" data-header data-overlay="<?php echo $overlay ? 'true' : 'false'; ?>">
	<?php get_template_part( 'template-parts/header/announcement-bar' ); ?>
	<?php get_template_part( 'template-parts/header/navigation' ); ?>
</header>
<?php endif; ?>

<?php get_template_part( 'template-parts/header/mobile-menu' ); ?>

<main id="main" class="site-main">
