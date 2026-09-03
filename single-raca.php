<?php
/**
 * Template Name: Single Raça - Rhaokar
 * Template Post Type: raca
 */

get_header();

while ( have_posts() ) :
	the_post();
	$post_id = get_the_ID();

	// Dados Pathfinder 1e
	$pf1_tipo = get_post_meta( $post_id, 'pf1_tipo', true );
	$pf1_tamanho = get_post_meta( $post_id, 'pf1_tamanho', true );
	$pf1_atributos = get_post_meta( $post_id, 'pf1_atributos', true );
	$pf1_qualidades = get_post_meta( $post_id, 'pf1_qualidades_especiais', true );
	$pf1_pericias = get_post_meta( $post_id, 'pf1_pericias', true );
	$pf1_talentos = get_post_meta( $post_id, 'pf1_talentos', true );
	$pf1_idiomas = get_post_meta( $post_id, 'pf1_idiomas', true );

	// Dados D&D 3.5
	$dnd35_ajuste = get_post_meta( $post_id, 'dnd35_ajuste_nivel', true );
	$dnd35_tipo = get_post_meta( $post_id, 'dnd35_tipo', true );
	$dnd35_tamanho = get_post_meta( $post_id, 'dnd35_tamanho', true );
	$dnd35_atributos = get_post_meta( $post_id, 'dnd35_atributos', true );
	$dnd35_qualidades = get_post_meta( $post_id, 'dnd35_qualidades_especiais', true );
	$dnd35_pericias = get_post_meta( $post_id, 'dnd35_pericias', true );
	$dnd35_talentos = get_post_meta( $post_id, 'dnd35_talentos', true );
	$dnd35_idiomas = get_post_meta( $post_id, 'dnd35_idiomas', true );
	?>

<div class="container my-4">
	<div class="row">
		<div class="col-12 text-center mb-3">
			<h1 class="display-4 font-weight-bold"><?php the_title(); ?></h1>
		</div>
	</div>

	<div class="contem-deuses p-4">
		<!-- Navegação por Abas para Alternar o Sistema de Regras -->
		<ul class="nav nav-tabs" id="racaTabs" role="tablist">
			<li class="nav-item">
				<a class="nav-link active font-weight-bold" id="pf1-tab" data-toggle="tab" href="#pf1-content" role="tab">
					Pathfinder 1e
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link font-weight-bold" id="dnd35-tab" data-toggle="tab" href="#dnd35-content" role="tab">
					D&D 3.5 <?php echo ! empty( $dnd35_ajuste ) ? ' (Ajuste: ' . esc_html( $dnd35_ajuste ) . ')' : ''; ?>
				</a>
			</li>
		</ul>

		<div class="tab-content mt-3" id="racaTabsContent">
			<!-- ABA PATHFINDER 1E -->
			<div class="tab-pane fade show active container" id="pf1-content" role="tabpanel">
				<div class="contem-racas p-3">
					<div class="row">
						<div class="col-md-3 text-center mb-3">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium', array( 'class' => 'img-fluid rounded shadow' ) ); ?>
							<?php endif; ?>
						</div>
						<div class="col-md-9">
							<h3>Ficha de Raça - Pathfinder 1e</h3>
							<?php the_content(); ?>
							
							<ul class="list-unstyled">
								<?php if ( $pf1_tipo ) : ?>
									<li><strong>Tipo:</strong> <?php echo esc_html( $pf1_tipo ); ?></li>
								<?php endif; ?>
								<?php if ( $pf1_tamanho ) : ?>
									<li><strong>Tamanho:</strong> <?php echo esc_html( $pf1_tamanho ); ?></li>
								<?php endif; ?>
							</ul>

							<?php if ( ! empty( $pf1_atributos ) && is_array( $pf1_atributos ) ) : ?>
								<h5>Atributos:</h5>
								<ul>
									<?php foreach ( $pf1_atributos as $item ) : ?>
										<?php if ( ! empty( $item['modificador'] ) ) : ?>
											<li><?php echo esc_html( $item['modificador'] ); ?></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( ! empty( $pf1_qualidades ) && is_array( $pf1_qualidades ) ) : ?>
								<h5>Qualidades Especiais:</h5>
								<ul>
									<?php foreach ( $pf1_qualidades as $item ) : ?>
										<li>
											<strong><?php echo esc_html( $item['nome'] ); ?>:</strong>
											<?php echo esc_html( $item['descricao'] ); ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( ! empty( $pf1_pericias ) && is_array( $pf1_pericias ) ) : ?>
								<h5>Perícias:</h5>
								<ul>
									<?php foreach ( $pf1_pericias as $item ) : ?>
										<li><?php echo esc_html( $item['pericia'] ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( ! empty( $pf1_talentos ) && is_array( $pf1_talentos ) ) : ?>
								<h5>Talentos & Evoluções:</h5>
								<ul>
									<?php foreach ( $pf1_talentos as $item ) : ?>
										<li>
											<strong><?php echo esc_html( $item['nome_talento'] ); ?>:</strong>
											<?php echo esc_html( $item['descricao_talento'] ); ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( ! empty( $pf1_idiomas ) && is_array( $pf1_idiomas ) ) : ?>
								<h5>Idiomas:</h5>
								<ul>
									<?php foreach ( $pf1_idiomas as $item ) : ?>
										<li><?php echo esc_html( $item['nome_idioma'] ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<!-- ABA D&D 3.5 -->
			<div class="tab-pane fade container" id="dnd35-content" role="tabpanel">
				<div class="contem-racas p-3">
					<div class="row">
						<div class="col-md-3 text-center mb-3">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium', array( 'class' => 'img-fluid rounded shadow' ) ); ?>
							<?php endif; ?>
						</div>
						<div class="col-md-9">
							<h3>Ficha de Raça - D&D 3.5</h3>
							<?php if ( $dnd35_ajuste ) : ?>
								<div class="alert alert-warning">
									<strong>Ajuste de Nível (Level Adjustment):</strong> <?php echo esc_html( $dnd35_ajuste ); ?>
								</div>
							<?php endif; ?>

							<ul class="list-unstyled">
								<?php if ( $dnd35_tipo ) : ?>
									<li><strong>Tipo:</strong> <?php echo esc_html( $dnd35_tipo ); ?></li>
								<?php endif; ?>
								<?php if ( $dnd35_tamanho ) : ?>
									<li><strong>Tamanho:</strong> <?php echo esc_html( $dnd35_tamanho ); ?></li>
								<?php endif; ?>
							</ul>

							<?php if ( ! empty( $dnd35_atributos ) && is_array( $dnd35_atributos ) ) : ?>
								<h5>Atributos:</h5>
								<ul>
									<?php foreach ( $dnd35_atributos as $item ) : ?>
										<?php if ( ! empty( $item['modificador'] ) ) : ?>
											<li><?php echo esc_html( $item['modificador'] ); ?></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( ! empty( $dnd35_qualidades ) && is_array( $dnd35_qualidades ) ) : ?>
								<h5>Qualidades Especiais:</h5>
								<ul>
									<?php foreach ( $dnd35_qualidades as $item ) : ?>
										<li>
											<strong><?php echo esc_html( $item['nome'] ); ?>:</strong>
											<?php echo esc_html( $item['descricao'] ); ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( ! empty( $dnd35_pericias ) && is_array( $dnd35_pericias ) ) : ?>
								<h5>Perícias:</h5>
								<ul>
									<?php foreach ( $dnd35_pericias as $item ) : ?>
										<li><?php echo esc_html( $item['pericia'] ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( ! empty( $dnd35_talentos ) && is_array( $dnd35_talentos ) ) : ?>
								<h5>Talentos:</h5>
								<ul>
									<?php foreach ( $dnd35_talentos as $item ) : ?>
										<li>
											<strong><?php echo esc_html( $item['nome_talento'] ); ?>:</strong>
											<?php echo esc_html( $item['descricao_talento'] ); ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( ! empty( $dnd35_idiomas ) && is_array( $dnd35_idiomas ) ) : ?>
								<h5>Idiomas:</h5>
								<ul>
									<?php foreach ( $dnd35_idiomas as $item ) : ?>
										<li><?php echo esc_html( $item['nome_idioma'] ); ?></li>
									<?php endforeach; ?>
								</ul>
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
