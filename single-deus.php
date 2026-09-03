<?php
/**
 * Template Name: Single Deus - Rhaokar
 * Template Post Type: deus
 */

get_header();

while ( have_posts() ) :
	the_post();
	$post_id = get_the_ID();

	// Dados Gerais da Divindade
	$tendencia = get_post_meta( $post_id, 'tendencia', true );
	$arma = get_post_meta( $post_id, 'arma_favorecida', true );
	$ascensao = get_post_meta( $post_id, 'ascensao', true );
	$descricao_deus = get_post_meta( $post_id, 'descricao_deus', true );
	$obrigacoes = get_post_meta( $post_id, 'obrigacoes_juramentos', true );
	$simbolo_texto = get_post_meta( $post_id, 'simbolo_sagrado_texto', true );
	$simbolo_img = get_post_meta( $post_id, 'simbolo_sagrado_imagem', true );
	$portfolio = get_post_meta( $post_id, 'portfolio_repeater', true );

	// Domínios
	$pf1_dominios = get_post_meta( $post_id, 'pf1_dominios_repeater', true );
	$dnd35_dominios = get_post_meta( $post_id, 'dnd35_dominios_repeater', true );
	?>

<div class="container my-4">
	<div class="row">
		<div class="col-md-4 text-center mb-4">
			<div class="contem-deuses p-3">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'medium', array( 'class' => 'img-fluid rounded shadow mb-3' ) ); ?>
				<?php endif; ?>

				<h2><?php the_title(); ?></h2>

				<?php if ( $tendencia ) : ?>
					<p><strong>Alinhamento:</strong> <?php echo esc_html( $tendencia ); ?></p>
				<?php endif; ?>

				<?php if ( $arma ) : ?>
					<p><strong>Arma Favorecida:</strong> <?php echo esc_html( $arma ); ?></p>
				<?php endif; ?>

				<?php if ( $simbolo_texto || $simbolo_img ) : ?>
					<p><strong>Símbolo Sagrado:</strong> <?php echo esc_html( $simbolo_texto ); ?></p>
					<?php if ( $simbolo_img ) : ?>
						<?php echo wp_get_attachment_image( $simbolo_img, 'thumbnail', false, array( 'class' => 'img-fluid style-symbol' ) ); ?>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>

		<div class="col-md-8">
			<div class="contem-deuses p-4">
				<?php if ( $ascensao ) : ?>
					<div class="mb-4">
						<h4>História da Ascensão</h4>
						<p><?php echo nl2br( esc_html( $ascensao ) ); ?></p>
					</div>
				<?php endif; ?>

				<?php if ( $descricao_deus || get_the_content() ) : ?>
					<div class="mb-4">
						<h4>Lore & Descrição</h4>
						<?php echo nl2br( esc_html( $descricao_deus ) ); ?>
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

				<?php if ( $obrigacoes ) : ?>
					<div class="mb-4">
						<h4>Obrigações & Juramentos</h4>
						<p><?php echo nl2br( esc_html( $obrigacoes ) ); ?></p>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $portfolio ) && is_array( $portfolio ) ) : ?>
					<div class="mb-4">
						<h4>Portfólio</h4>
						<ul>
							<?php foreach ( $portfolio as $item ) : ?>
								<?php if ( ! empty( $item['item'] ) ) : ?>
									<li><?php echo esc_html( $item['item'] ); ?></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<!-- Domínios com Abas por Sistema -->
				<div class="mt-4">
					<h4>Domínios Concedidos</h4>
					<ul class="nav nav-tabs" id="deusDominiosTabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active font-weight-bold" id="pf1-dom-tab" data-toggle="tab" href="#pf1-dom" role="tab">Pathfinder 1e</a>
						</li>
						<li class="nav-item">
							<a class="nav-link font-weight-bold" id="dnd35-dom-tab" data-toggle="tab" href="#dnd35-dom" role="tab">D&D 3.5</a>
						</li>
					</ul>

					<div class="tab-content p-3 border border-top-0" id="deusDominiosTabsContent">
						<div class="tab-pane fade show active" id="pf1-dom" role="tabpanel">
							<?php if ( ! empty( $pf1_dominios ) && is_array( $pf1_dominios ) ) : ?>
								<ul>
									<?php foreach ( $pf1_dominios as $item ) : ?>
										<?php if ( ! empty( $item['dominio'] ) ) : ?>
											<li><?php echo esc_html( $item['dominio'] ); ?></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							<?php else : ?>
								<p class="text-muted">Nenhum domínio específico para Pathfinder 1e cadastrado.</p>
							<?php endif; ?>
						</div>

						<div class="tab-pane fade" id="dnd35-dom" role="tabpanel">
							<?php if ( ! empty( $dnd35_dominios ) && is_array( $dnd35_dominios ) ) : ?>
								<ul>
									<?php foreach ( $dnd35_dominios as $item ) : ?>
										<?php if ( ! empty( $item['dominio'] ) ) : ?>
											<li><?php echo esc_html( $item['dominio'] ); ?></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							<?php else : ?>
								<p class="text-muted">Nenhum domínio específico para D&D 3.5 cadastrado.</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
endwhile;
get_footer();
