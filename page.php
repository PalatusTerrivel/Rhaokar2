<?php
/**
 * Default Page Template for Hello Elementor Child (Rhaokar)
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<main id="content" class="site-main" role="main">
		<div class="container my-4">
			<?php the_content(); ?>
		</div>
	</main>
	<?php
endwhile;

get_footer();
