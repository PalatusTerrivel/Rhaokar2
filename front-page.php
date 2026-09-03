<?php
/**
 * Template Name: Home Page - Rhaokar
 */

get_header();
?>

<div class="text-center my-4">
	<h1 class="titulo-principal display-3">Rhaokar</h1>
	<h2 class="h4 text-muted">É o cenário de campanha onde se passarão os jogos</h2> 
</div>

<div class="container d-flex justify-content-center my-4">
	<div class="row">
		<div class="col align-self-center">
			<ul class="center p-0" style="list-style-type:none;">
				<li class="mb-2">
					<a href="<?php echo esc_url( home_url( '/racas/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white" font-family="'NewRocker', Times, serif">
								As Raças
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-2">
					<a href="<?php echo esc_url( home_url( '/deuses/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white" font-family="'NewRocker', Times, serif">
								Os Deuses
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-2">
					<a href="<?php echo esc_url( home_url( '/reinos/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white" font-family="'NewRocker', Times, serif">
								Os Reinos
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-2">
					<a href="<?php echo esc_url( home_url( '/diario/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="18" fill="white" font-family="'NewRocker', Times, serif">
								Diário da Campanha
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-2">
					<a href="<?php echo esc_url( home_url( '/magia/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white" font-family="'NewRocker', Times, serif">
								A Magia
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-2">
					<a href="<?php echo esc_url( home_url( '/alquimia/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white" font-family="'NewRocker', Times, serif">
								A Alquimia
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-2">
					<a href="<?php echo esc_url( home_url( '/tecnologia/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white" font-family="'NewRocker', Times, serif">
								A Tecnologia
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-2">
					<a href="<?php echo esc_url( home_url( '/regras/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="18" fill="white" font-family="'NewRocker', Times, serif">
								Regras Alternativas
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-2">
					<a href="#" data-toggle="modal" data-target="#conteudoModal2">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white" font-family="'NewRocker', Times, serif">
								Créditos
							</text>
						</svg>  
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>

<!-- Modal de Créditos -->
<div id="conteudoModal2" class="modal fade">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Créditos do Site</h4>
				<button class="close" data-dismiss="modal" aria-label="fechar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul>
					<li><b>Cenário:</b> Paulo "Palatus" Oliveira, criação do cenário</li>
					<li><b>Cenário:</b> Meus Jogadores, que deram muitas ideias para aplicar no cenário</li>
					<li><b>Artes:</b> Brenno Progetti, responsável por todos os ícones e sprites em pixel art</li>
					<li><b>Apoio Técnico:</b> Douglas "Doug" dos Anjos, sem o qual o mapa não ia funcionar nunca e ajuda com toda a parte técnica.</li>
				</ul>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div> 
</div>

<!-- Suporte a Conteúdo do Elementor / WordPress -->
<div class="container my-4">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</div>

<?php
get_footer();
