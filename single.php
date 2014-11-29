<?php get_header(); ?>

	<main role="main">
	<!-- section -->
	<section>

	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<?php get_template_part( 'content', get_post_format() ); ?>

	<?php endwhile; ?>

	<?php else: ?>
		
		<?php get_template_part( 'content', 'none' ); ?>

	<?php endif; ?>

	</section>
	<!-- /section -->

	<?php get_sidebar(); ?>

	<div class="clear"></div>

	</main>


<?php get_footer(); ?>