<?php
/**
 * Archive Template for Deuses
 */

get_header();
?>

<div class="container my-5">
	<div class="text-center mb-5">
		<h1 class="display-4 font-weight-bold">Os Deuses de Rhaokar</h1>
		<p class="lead">As divindades, entidades maiores e poderes do panteão</p>
	</div>

	<div class="row">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				$tendencia = get_post_meta( get_the_ID(), 'tendencia', true );
				$arma = get_post_meta( get_the_ID(), 'arma_favorecida', true );
				?>
				<div class="col-md-3 mb-4">
					<div class="contem-deuses text-center p-3 h-100 d-flex flex-column justify-content-between">
						<div>
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="mb-3">
									<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'img-fluid rounded-circle shadow' ) ); ?>
								</div>
							<?php endif; ?>

							<h3 class="h5 font-weight-bold"><?php the_title(); ?></h3>

							<?php if ( $tendencia ) : ?>
								<p class="small mb-1"><strong>Alinhamento:</strong> <?php echo esc_html( $tendencia ); ?></p>
							<?php endif; ?>

							<?php if ( $arma ) : ?>
								<p class="small text-muted mb-2"><strong>Arma:</strong> <?php echo esc_html( $arma ); ?></p>
							<?php endif; ?>
						</div>

						<div class="mt-3">
							<a href="<?php the_permalink(); ?>" class="btn btn-outline-dark btn-sm btn-block">
								Ver Detalhes
							</a>
						</div>
					</div>
				</div>
				<?php
			endwhile;
			the_posts_pagination();
		else :
			?>
			<div class="col-12 text-center">
				<p>Nenhuma divindade cadastrada ainda.</p>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
