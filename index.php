<?php
/**
 * Fallback template (blog / archives).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="page-wrap container">
	<?php if ( have_posts() ) : ?>
		<h1 class="display page-title"><?php echo is_home() ? esc_html__( 'Journal', 'atelier-b2b' ) : wp_kses_post( get_the_archive_title() ); ?></h1>
		<div class="post-list">
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'post-list__item' ); ?>>
				<h2 class="post-list__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="prose"><?php the_excerpt(); ?></div>
			</article>
		<?php endwhile; ?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<h1 class="display page-title"><?php esc_html_e( 'Nothing here yet.', 'atelier-b2b' ); ?></h1>
	<?php endif; ?>
</div>
<?php
get_footer();
