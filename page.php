<?php
/**
 * Default page template (placeholder until inner pages are designed).
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="page-wrap container">
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class(); ?>>
			<h1 class="display page-title"><?php the_title(); ?></h1>
			<div class="prose"><?php the_content(); ?></div>
		</article>
	<?php endwhile; ?>
</div>
<?php
get_footer();
