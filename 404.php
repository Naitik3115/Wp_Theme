<?php
/**
 * 404 template.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="page-wrap container">
	<p class="label"><?php esc_html_e( 'Error 404', 'atelier-b2b' ); ?></p>
	<h1 class="display page-title"><?php esc_html_e( 'This page has been moved or no longer exists.', 'atelier-b2b' ); ?></h1>
	<a class="btn btn--solid" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span><?php esc_html_e( 'Back to homepage', 'atelier-b2b' ); ?></span><?php echo atelier_arrow(); // phpcs:ignore ?></a>
</div>
<?php
get_footer();
