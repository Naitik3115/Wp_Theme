<?php
/**
 * Default page template.
 *
 * Elementor-built pages render full-width (theme header/footer only).
 * Classic editor pages keep the simple title + prose layout.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	if ( atelier_page_uses_elementor() ) :
		?>
		<div class="atelier-page atelier-page--elementor">
			<?php the_content(); ?>
		</div>
		<?php
	else :
		?>
		<div class="page-wrap container">
			<article <?php post_class(); ?>>
				<h1 class="display page-title"><?php the_title(); ?></h1>
				<div class="prose"><?php the_content(); ?></div>
			</article>
		</div>
		<?php
	endif;
endwhile;

get_footer();
