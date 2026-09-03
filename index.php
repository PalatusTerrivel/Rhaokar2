<?php
/**
 * Main Template File for Rhaokar Theme
 */

get_header();
?>

<div class="container my-5">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'contem-deuses p-4 mb-4' ); ?>>
				<h2 class="titulo-principal mb-3">
					<a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
						<?php the_title(); ?>
					</a>
				</h2>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;
		the_posts_pagination();
	else :
		?>
		<div class="contem-deuses p-4 text-center">
			<h2>Nenhum conteúdo encontrado</h2>
			<p>Desculpe, o conteúdo solicitado não foi encontrado.</p>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
