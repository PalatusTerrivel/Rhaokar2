<?php
/**
 * Archive Template for Raças
 */

get_header();
?>

<div class="container my-5">
	<div class="text-center mb-5">
		<h1 class="display-4 font-weight-bold">As Raças de Rhaokar</h1>
		<p class="lead">Conheça os povos, culturas e características raciais do cenário</p>
	</div>

	<div class="row">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				$pf1_tipo = get_post_meta( get_the_ID(), 'pf1_tipo', true );
				$pf1_tamanho = get_post_meta( get_the_ID(), 'pf1_tamanho', true );
				?>
				<div class="col-md-4 mb-4">
					<div class="contem-deuses text-center p-4 h-100 d-flex flex-column justify-content-between">
						<div>
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="mb-3">
									<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'img-fluid rounded-circle shadow' ) ); ?>
								</div>
							<?php endif; ?>

							<h3 class="h4 font-weight-bold"><?php the_title(); ?></h3>
							
							<?php if ( $pf1_tipo || $pf1_tamanho ) : ?>
								<p class="small text-muted mb-2">
									<?php echo esc_html( implode( ' • ', array_filter( array( $pf1_tipo, $pf1_tamanho ) ) ) ); ?>
								</p>
							<?php endif; ?>

							<p class="card-text small"><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
						</div>

						<div class="mt-3">
							<a href="<?php the_permalink(); ?>" class="btn btn-dark btn-block">
								Ver Ficha Completa
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
				<p>Nenhuma raça cadastrada ainda.</p>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
