<h1><?php the_title(); ?></h1>

<!-- article -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php the_content(); ?>

	<?php //comments_template( '', true ); // Remove if you don't want comments ?>

	<br class="clear">

	<?php edit_post_link(); ?>

</article>