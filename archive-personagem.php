<?php
/**
 * Template Name: O Hall dos Heróis e Vilões (Archive Personagens)
 * Post Type: personagem
 */

get_header();

// Garantia de funções auxiliares de cálculo de XP
if ( ! function_exists( 'rhaokar_dnd35_clean_num' ) ) {
	function rhaokar_dnd35_clean_num( $val, $default = 0 ) {
		if ( $val === null || $val === '' || $val === false ) {
			return $default;
		}
		if ( is_numeric( $val ) ) {
			return intval( $val );
		}
		if ( is_string( $val ) ) {
			$cleaned = preg_replace( '/[^\d\-]/', '', $val );
			return ( $cleaned !== '' ) ? intval( $cleaned ) : $default;
		}
		return intval( $val );
	}
}

if ( ! function_exists( 'rhaokar_dnd35_xp_for_level' ) ) {
	function rhaokar_dnd35_xp_for_level( $level ) {
		if ( $level <= 1 ) {
			return 0;
		}
		return (int) ( ( $level * ( $level - 1 ) / 2 ) * 1000 );
	}
}

// Query de todos os personagens se for usado como Template de Página
$is_page = is_page();
if ( $is_page ) {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$personagens_query = new WP_Query(
		array(
			'post_type'      => 'personagem',
			'posts_per_page' => 24,
			'paged'          => $paged,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
} else {
	global $wp_query;
	$personagens_query = $wp_query;
}
?>

<style>
/* Estilos Exclusivos do Hall dos Heróis e Vilões - Rhaokar */
.hall-container {
	background: #141619;
	color: #e0e6ed;
	font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
	border: 2px solid #b8860b;
	border-radius: 8px;
	padding: 30px 20px;
	box-shadow: 0 10px 30px rgba(0,0,0,0.85);
	margin-top: 25px;
	margin-bottom: 45px;
}
.hall-header {
	border-bottom: 2px solid #b8860b;
	padding-bottom: 20px;
	margin-bottom: 30px;
	text-align: center;
}
.hall-title {
	font-family: 'Cinzel', Georgia, serif;
	color: #ffd700;
	font-weight: 700;
	text-shadow: 0 2px 6px rgba(0,0,0,0.9);
	font-size: 2.3rem;
}
.hall-subtitle {
	color: #a0aec0;
	font-size: 1.05rem;
}

/* Painel de Filtros */
.hall-filter-panel {
	background: #1e2228;
	border: 1px solid #3a424d;
	border-radius: 8px;
	padding: 15px 20px;
	margin-bottom: 30px;
}
.hall-filter-btn {
	background: #252a32;
	border: 1px solid #4a5463;
	color: #a0aec0;
	font-size: 0.85rem;
	padding: 6px 14px;
	border-radius: 20px;
	transition: all 0.25s ease;
	cursor: pointer;
	margin: 3px;
	font-weight: 600;
}
.hall-filter-btn:hover, .hall-filter-btn.active {
	background: #b8860b;
	color: #111;
	border-color: #ffd700;
	box-shadow: 0 0 10px rgba(255, 215, 0, 0.4);
}

/* Cards dos Personagens */
.character-card-wrap {
	transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.character-card-wrap:hover {
	transform: translateY(-5px);
}
.character-card {
	background: #1e2228;
	border: 1px solid #3a424d;
	border-radius: 8px;
	overflow: hidden;
	height: 100%;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	box-shadow: 0 4px 15px rgba(0,0,0,0.6);
	transition: border-color 0.3s ease;
}
.character-card-wrap:hover .character-card {
	border-color: #b8860b;
}
.character-img-box {
	position: relative;
	width: 100%;
	height: 200px;
	background: #0d0f12;
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 10px;
	border-bottom: 1px solid #2e353e;
	overflow: hidden;
}
.character-img {
	max-width: 100%;
	max-height: 100%;
	width: auto;
	height: auto;
	object-fit: contain;
	border-radius: 6px;
	box-shadow: 0 4px 10px rgba(0,0,0,0.7);
	transition: transform 0.3s ease;
}
.character-card-wrap:hover .character-img {
	transform: scale(1.06);
}
.character-no-img {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	height: 100%;
	color: #5a6578;
}

/* Badges sobrepostas na imagem */
.badge-status-overlay {
	position: absolute;
	top: 10px;
	left: 10px;
	font-size: 0.72rem;
	font-weight: 700;
	padding: 4px 10px;
	border-radius: 12px;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.7);
}
.badge-status-ativo {
	background-color: #52c41a !important;
	color: #fff !important;
}
.badge-status-aposentado {
	background-color: #faad14 !important;
	color: #111 !important;
}
.badge-status-morto {
	background-color: #ff4d4f !important;
	color: #fff !important;
}

.badge-sistema-overlay {
	position: absolute;
	top: 10px;
	right: 10px;
	background: rgba(0,0,0,0.75);
	border: 1px solid #b8860b;
	color: #ffd700;
	font-size: 0.7rem;
	font-weight: 700;
	padding: 3px 8px;
	border-radius: 4px;
}

/* Conteúdo do Card */
.character-card-content {
	padding: 16px;
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
}
.character-name {
	font-family: 'Cinzel', Georgia, serif;
	color: #ffd700;
	font-size: 1.2rem;
	font-weight: 700;
	margin-bottom: 6px;
	line-height: 1.3;
}
.character-classes {
	color: #e6c667;
	font-size: 0.88rem;
	font-weight: 600;
	margin-bottom: 8px;
}
.character-xp-box {
	background: #141619;
	border: 1px solid #2e353e;
	border-radius: 6px;
	padding: 8px 10px;
	margin-bottom: 14px;
}
.character-btn-ficha {
	background: linear-gradient(180deg, #b8860b 0%, #8b6508 100%);
	border: 1px solid #ffd700;
	color: #111 !important;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	font-size: 0.85rem;
	padding: 8px 16px;
	border-radius: 5px;
	transition: all 0.25s ease;
	text-align: center;
	display: block;
	text-decoration: none !important;
}
.character-btn-ficha:hover {
	background: linear-gradient(180deg, #ffd700 0%, #b8860b 100%);
	box-shadow: 0 0 12px rgba(255, 215, 0, 0.6);
	color: #000 !important;
}
</style>

<div class="container hall-container">

	<!-- CABEÇALHO DO HALL -->
	<div class="hall-header">
		<h1 class="hall-title"><i class="dashicons dashicons-groups" style="font-size: 2.2rem; vertical-align: middle; margin-right: 8px;"></i> O Hall dos Heróis e Vilões</h1>
		<p class="hall-subtitle mb-0">Galeria de Fichas de Personagens das Campanhas de Rhaokar</p>
	</div>

	<!-- BARRA DE FILTROS & BUSCA -->
	<div class="hall-filter-panel">
		<div class="row align-items-center">
			<div class="col-md-7 mb-3 mb-md-0">
				<div class="d-flex flex-wrap align-items-center">
					<span class="mr-2 text-warning small font-weight-bold">STATUS:</span>
					<button type="button" class="hall-filter-btn active" onclick="rhaokarFilterHall('status', 'all', this)">Todos</button>
					<button type="button" class="hall-filter-btn" onclick="rhaokarFilterHall('status', 'ativo', this)">🟢 Ativos</button>
					<button type="button" class="hall-filter-btn" onclick="rhaokarFilterHall('status', 'aposentado', this)">🟡 Aposentados</button>
					<button type="button" class="hall-filter-btn" onclick="rhaokarFilterHall('status', 'morto', this)">💀 Mortos</button>
				</div>
			</div>
			<div class="col-md-5">
				<div class="input-group">
					<input type="text" id="rhaokar-search-input" class="form-control form-control-sm bg-dark text-light border-secondary" placeholder="Buscar por nome ou classe..." onkeyup="rhaokarSearchHall()">
					<div class="input-group-append">
						<span class="input-group-text bg-dark border-secondary text-warning"><i class="dashicons dashicons-search"></i></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- GRID DE PERSONAGENS -->
	<div class="row" id="rhaokar-hall-grid">
		<?php
		if ( $personagens_query->have_posts() ) :
			while ( $personagens_query->have_posts() ) :
				$personagens_query->the_post();
				$post_id = get_the_ID();

				// Meta Dados do Personagem
				$sistema = get_post_meta( $post_id, 'sistema_personagem', true ) ?: 'dnd35';
				$nome = get_post_meta( $post_id, 'dnd35_nome', true ) ?: get_the_title();
				$raca = get_post_meta( $post_id, 'dnd35_raca', true );
				$status = get_post_meta( $post_id, 'dnd35_status', true ) ?: 'ativo';
				$imagem_id = get_post_meta( $post_id, 'dnd35_imagem', true );
				$imagem_url = $imagem_id ? wp_get_attachment_image_url( $imagem_id, 'medium' ) : get_the_post_thumbnail_url( $post_id, 'medium' );

				// Nível e Classes
				$classes_raw = get_post_meta( $post_id, 'dnd35_classes', true );
				$nivel_total = 0;
				$classes_str_arr = array();
				if ( is_array( $classes_raw ) && ! empty( $classes_raw ) ) {
					foreach ( $classes_raw as $c ) {
						$lvl = intval( $c['nivel_classe'] ?? 0 );
						$nivel_total += $lvl;
						if ( ! empty( $c['nome_classe'] ) ) {
							$classes_str_arr[] = esc_html( $c['nome_classe'] ) . ' ' . $lvl;
						}
					}
				}
				$classes_str = implode( ' / ', $classes_str_arr );

				// XP
				$lvl_for_xp = max( 1, $nivel_total );
				if ( $sistema === 'pf1' ) {
					$pf1_trilha_xp = get_post_meta( $post_id, 'pf1_xp_tabela', true ) ?: 'normal';
					$min_xp_for_lvl = rhaokar_pf1_xp_for_level( $lvl_for_xp, $pf1_trilha_xp );
					$next_lvl_xp    = rhaokar_pf1_xp_for_level( $lvl_for_xp + 1, $pf1_trilha_xp );
				} else {
					$min_xp_for_lvl = rhaokar_dnd35_xp_for_level( $lvl_for_xp );
					$next_lvl_xp    = rhaokar_dnd35_xp_for_level( $lvl_for_xp + 1 );
				}
				$raw_xp = get_post_meta( $post_id, 'dnd35_xpatual', true );
				$xp_atual = ( $raw_xp !== '' && $raw_xp !== false ) ? rhaokar_dnd35_clean_num( $raw_xp, $min_xp_for_lvl ) : $min_xp_for_lvl;
				if ( $xp_atual < $min_xp_for_lvl ) {
					$xp_atual = $min_xp_for_lvl;
				}
				$xp_range = $next_lvl_xp - $min_xp_for_lvl;
				$xp_progress = ( $xp_range > 0 ) ? min( 100, max( 0, round( ( ( $xp_atual - $min_xp_for_lvl ) / $xp_range ) * 100 ) ) ) : 100;
				?>

				<div class="col-12 col-sm-6 col-md-3 col-lg-3 col-xl-3 mb-4 hall-card-item" 
					 data-status="<?php echo esc_attr( strtolower( $status ) ); ?>" 
					 data-sistema="<?php echo esc_attr( strtolower( $sistema ) ); ?>"
					 data-search="<?php echo esc_attr( strtolower( $nome . ' ' . $classes_str . ' ' . $raca ) ); ?>">
					
					<div class="character-card-wrap h-100">
						<div class="character-card">
							
							<!-- IMAGEM E BADGES -->
							<div class="character-img-box">
								<?php if ( $imagem_url ) : ?>
									<img src="<?php echo esc_url( $imagem_url ); ?>" alt="<?php echo esc_attr( $nome ); ?>" class="character-img">
								<?php else : ?>
									<div class="character-no-img">
										<i class="dashicons dashicons-id" style="font-size: 3rem; width: auto; height: auto;"></i>
										<span class="small text-muted mt-1">Sem Foto</span>
									</div>
								<?php endif; ?>

								<!-- BADGE DE STATUS -->
								<?php if ( $status === 'morto' ) : ?>
									<span class="badge-status-overlay badge-status-morto">💀 MORTO</span>
								<?php elseif ( $status === 'aposentado' ) : ?>
									<span class="badge-status-overlay badge-status-aposentado">🟡 APOSENTADO</span>
								<?php else : ?>
									<span class="badge-status-overlay badge-status-ativo">🟢 ATIVO</span>
								<?php endif; ?>

								<!-- BADGE DE SISTEMA -->
								<span class="badge-sistema-overlay">
									<?php echo ( $sistema === 'pf1' ) ? 'Pathfinder 1e' : 'D&D 3.5'; ?>
								</span>
							</div>

							<!-- CONTEÚDO DO CARD -->
							<div class="character-card-content">
								<div>
									<h3 class="character-name"><?php echo esc_html( $nome ); ?></h3>
									<div class="character-classes">
										<?php echo esc_html( $classes_str ?: 'Sem Classe' ); ?>
										<span class="text-warning">(Nível <?php echo $nivel_total; ?>)</span>
									</div>
									<?php if ( $raca ) : ?>
										<div class="small text-muted mb-2">
											<strong>Raça:</strong> <?php echo esc_html( $raca ); ?>
										</div>
									<?php endif; ?>
								</div>

								<div>
									<!-- CAIXA DE XP -->
									<div class="character-xp-box">
										<div class="d-flex justify-content-between align-items-center small mb-1">
											<span class="text-warning font-weight-bold">XP:</span>
											<span class="text-light font-weight-bold"><?php echo number_format( $xp_atual, 0, ',', '.' ); ?> XP</span>
										</div>
										<div class="progress" style="height: 6px; background-color: #1a1d22; border-radius: 3px; overflow: hidden;">
											<div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $xp_progress; ?>%;" aria-valuenow="<?php echo $xp_progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>

									<!-- BOTÃO FICHA -->
									<a href="<?php the_permalink(); ?>" class="character-btn-ficha">
										📜 Ficha
									</a>
								</div>
							</div>

						</div>
					</div>
				</div>

				<?php
			endwhile;
			wp_reset_postdata();
		else :
			?>
			<div class="col-12 text-center py-5">
				<p class="lead text-muted">Nenhum personagem cadastrado no Hall ainda.</p>
			</div>
		<?php endif; ?>
	</div>
</div>

<script id="rhaokar-hall-filter-script">
var currentStatusFilter = 'all';

function rhaokarFilterHall(type, val, btn) {
	if (type === 'status') {
		currentStatusFilter = val;
		var btns = btn.parentNode.querySelectorAll('.hall-filter-btn');
		btns.forEach(function(b) { b.classList.remove('active'); });
		btn.classList.add('active');
	}
	applyHallFilters();
}

function rhaokarSearchHall() {
	applyHallFilters();
}

function applyHallFilters() {
	var searchVal = document.getElementById('rhaokar-search-input').value.toLowerCase().trim();
	var items = document.querySelectorAll('.hall-card-item');

	items.forEach(function(item) {
		var status = item.getAttribute('data-status') || '';
		var search = item.getAttribute('data-search') || '';

		var matchStatus = (currentStatusFilter === 'all' || status === currentStatusFilter);
		var matchSearch = (!searchVal || search.indexOf(searchVal) !== -1);

		if (matchStatus && matchSearch) {
			item.style.display = 'block';
		} else {
			item.style.display = 'none';
		}
	});
}
</script>

<?php
get_footer();
