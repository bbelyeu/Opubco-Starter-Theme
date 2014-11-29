<?php get_header(); ?>

	<main role="main">
		<!-- section -->
		<section>
		<?php
		// Start the Loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			get_template_part( 'content', 'page' );

		endwhile;
		?>

		</section>
		<!-- /section -->
		<?php get_sidebar(); ?>

		<div class="clear"></div>
	</main>


<?php get_footer(); ?>