<?php
/**
 * Template Name: Home Page - Rhaokar
 */

get_header();
?>

<div class="text-center my-4">
	<h1 class="titulo-principal display-3 font-weight-bold">Rhaokar</h1>
	<h2 class="h5 text-dark">É o cenário de campanha onde se passarão os jogos</h2> 
</div>

<div class="container d-flex justify-content-center my-4">
	<div class="row w-100 justify-content-center">
		<div class="col-12 text-center">
			<ul class="list-unstyled d-inline-block text-center p-0 m-0">
				<li class="mb-3">
					<a href="<?php echo esc_url( home_url( '/racas/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white">
								As Raças
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-3">
					<a href="<?php echo esc_url( home_url( '/deuses/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white">
								Os Deuses
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-3">
					<a href="<?php echo esc_url( home_url( '/reinos/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white">
								Os Reinos
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-3">
					<a href="<?php echo esc_url( home_url( '/diario/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="18" fill="white">
								Diário da Campanha
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-3">
					<a href="<?php echo esc_url( home_url( '/magia/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white">
								A Magia
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-3">
					<a href="<?php echo esc_url( home_url( '/alquimia/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white">
								A Alquimia
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-3">
					<a href="<?php echo esc_url( home_url( '/tecnologia/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white">
								A Tecnologia
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-3">
					<a href="<?php echo esc_url( home_url( '/regras/' ) ); ?>">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="18" fill="white">
								Regras Alternativas
							</text>
						</svg>
					</a>
				</li>
				<li class="mb-3">
					<a href="#conteudoModal2" data-toggle="modal" data-target="#conteudoModal2">
						<svg class="botao" style="width: 270px; height: 70px;">
							<polygon points="262,40 225,64 75,64 40,40 75,20 225,20" stroke="grey" fill="black" stroke-width="5"/>
							<text x="150" y="45" dominant-baseline="middle" text-anchor="middle" font-size="25" fill="white">
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
<div id="conteudoModal2" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title font-weight-bold">Créditos do Site</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul class="list-group list-group-flush text-left">
					<li class="list-group-item"><b>Cenário:</b> Paulo "Palatus" Oliveira, criação do cenário</li>
					<li class="list-group-item"><b>Cenário:</b> Meus Jogadores, que deram muitas ideias para aplicar no cenário</li>
					<li class="list-group-item"><b>Artes:</b> Brenno Progetti, responsável por todos os ícones e sprites em pixel art</li>
					<li class="list-group-item"><b>Apoio Técnico:</b> Douglas "Doug" dos Anjos, ajuda com toda a parte técnica.</li>
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div> 
</div>

<?php
get_footer();
