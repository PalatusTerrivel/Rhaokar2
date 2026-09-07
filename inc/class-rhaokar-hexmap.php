<?php
/**
 * Rhaokar HexMap Manager - Módulo de Gerenciamento do Hexcrawl de Rhaokar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rhaokar_HexMap_Manager {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		add_action( 'init', array( $this, 'register_cpt_hex_mapeado' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_hex_meta_boxes' ) );
		add_action( 'save_post_hex_mapeado', array( $this, 'save_hex_meta_box_data' ) );

		// Shortcodes do Mapa
		add_shortcode( 'rhaokar_mapa', array( $this, 'render_mapa_shortcode' ) );
		add_shortcode( 'rhaokar_hexmap', array( $this, 'render_mapa_shortcode' ) );
		add_shortcode( 'mapa_rhaokar', array( $this, 'render_mapa_shortcode' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_map_assets' ) );
	}

	/**
	 * Lista Oficial de Tipos de Terrenos do Rhaokar
	 */
	public static function get_terrain_types() {
		return array(
			'agua_profunda'                  => 'Água Profunda',
			'agua_rasa'                      => 'Água Rasa',
			'bosque'                         => 'Bosque',
			'bosque_nevado'                  => 'Bosque Nevado',
			'floresta'                       => 'Floresta',
			'floresta_nevada'                => 'Floresta Nevada',
			'floresta_nevada_rio'            => 'Floresta Nevada com Rio',
			'floresta_nevada_trilho'         => 'Floresta Nevada com Trilho',
			'montanha'                       => 'Montanha',
			'montanha_nevada'                => 'Montanha Nevada',
			'montanha_nevada_trilho'         => 'Montanha Nevada com Trilho',
			'planicie'                       => 'Planície',
			'planicie_nevada'                => 'Planície Nevada',
			'planicie_nevada_trilho'         => 'Planície Nevada com Trilho',
			'deserto'                        => 'Deserto',
			'selva'                          => 'Selva',
			'montanha_com_floresta'          => 'Montanha com Floresta',
			'montanha_com_floresta_nevada'   => 'Montanha com Floresta Nevada',
			'montanha_com_selva'             => 'Montanha com Selva',
			'pantano'                        => 'Pântano',
			'vulcao'                         => 'Vulcão',
			'mana_wastes'                    => 'Ermos de Mana',
			'aetheomir'                      => 'Reino: Aetheomir',
			'catarna'                        => 'Reino: Catarna',
			'cordileiras_vermelhas'          => 'Reino: Cordilheiras Vermelhas',
			'demios'                         => 'Reino: Demios',
			'galorfindil'                    => 'Reino: Galorfindil',
			'gliorith'                       => 'Reino: Gliorith',
			'huntston'                       => 'Reino: Huntston',
			'kaareth'                        => 'Reino: Kaareth',
			'koba'                           => 'Reino: Koba',
			'mao_do_aqueronte'               => 'Reino: Mão do Aqueronte',
			'montanha_de_ferro'              => 'Reino: Montanha de Ferro',
			'montanha_de_mithral'            => 'Reino: Montanha de Mithral',
			'orcshardhaven'                  => 'Reino: Orcshardhaven',
			'shogunato'                      => 'Reino: Shogunato',
			'capital'                        => 'Cidade: Capital',
			'capital_nevada'                 => 'Cidade: Capital Nevada',
			'hamlet'                         => 'Cidade: Hamlet / Aldeia',
			'hamlet_snow'                    => 'Cidade: Hamlet Nevado',
			'large_city'                     => 'Cidade Grande',
			'large_city_snow'                => 'Cidade Grande Nevada',
			'large_town'                     => 'Vila Grande',
			'large_town_snow'                => 'Vila Grande Nevada',
			'metropolis'                     => 'Metrópole',
			'metropolis_snow'                => 'Metrópole Nevada',
			'military_fort'                  => 'Forte Militar',
			'mine'                           => 'Mina / Escavação',
			'oasis'                          => 'Oásis',
			'ruin'                           => 'Ruína Antiga',
			'ruin_large'                     => 'Ruína Grande',
			'small_city'                     => 'Cidade Pequena',
			'small_city_snow'                => 'Cidade Pequena Nevada',
			'small_town'                     => 'Vila Pequena',
			'small_town_snow'                => 'Vila Pequena Nevada',
			'thorp'                          => 'Povoado / Thorp',
			'thorp_snow'                     => 'Povoado Nevado',
			'village'                        => 'Vilarejo',
			'village_snow'                   => 'Vilarejo Nevado',
		);
	}

	/**
	 * Matriz Oficial de Coordenadas dos 37 Sub-tiles (`A15` a `G18`)
	 */
	public static function get_subtile_matrix() {
		return array(
			'Linha A' => array( 'A15', 'A16', 'A17', 'A18' ),
			'Linha B' => array( 'B14', 'B15', 'B16', 'B17', 'B18' ),
			'Linha C' => array( 'C14', 'C15', 'C16', 'C17', 'C18', 'C19' ),
			'Linha D' => array( 'D13', 'D14', 'D15', 'D16', 'D17', 'D18', 'D19' ),
			'Linha E' => array( 'E14', 'E15', 'E16', 'E17', 'E18', 'E19' ),
			'Linha F' => array( 'F14', 'F15', 'F16', 'F17', 'F18' ),
			'Linha G' => array( 'G15', 'G16', 'G17', 'G18' ),
		);
	}

	/**
	 * Registra o Custom Post Type `hex_mapeado`
	 */
	public function register_cpt_hex_mapeado() {
		$labels = array(
			'name'               => 'Hexágonos Mapeados',
			'singular_name'      => 'Hexágono Mapeado',
			'menu_name'          => 'Hexcrawl Rhaokar',
			'add_new'            => 'Mapear Novo Hex',
			'add_new_item'       => 'Mapear Novo Hexágono (Ex: U7, V8)',
			'edit_item'          => 'Editar Hexágono Mapeado',
			'new_item'           => 'Novo Hexágono',
			'view_item'          => 'Ver Hexágono',
			'search_items'       => 'Buscar Hexágonos Mapeados',
			'not_found'          => 'Nenhum hexágono encontrado',
			'not_found_in_trash' => 'Nenhum hexágono na lixeira',
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => 'hex-mapeado' ),
			'capability_type'     => 'post',
			'has_archive'         => false,
			'hierarchical'        => false,
			'menu_position'       => 6,
			'menu_icon'           => 'dashicons-location-alt',
			'supports'            => array( 'title' ),
		);

		register_post_type( 'hex_mapeado', $args );
	}

	/**
	 * Registra o Meta Box do Hexcrawl no Admin
	 */
	public function add_hex_meta_boxes() {
		add_meta_box(
			'rhaokar_hex_details_meta_box',
			'🗺️ Configuração dos 37 Sub-hexágonos & Conteúdo do Hex',
			array( $this, 'render_hex_meta_box' ),
			'hex_mapeado',
			'normal',
			'high'
		);
	}

	/**
	 * Renderiza o Formulário do Admin com os 37 Sub-tiles
	 */
	public function render_hex_meta_box( $post ) {
		wp_nonce_field( 'rhaokar_save_hex_meta', 'rhaokar_hex_nonce' );

		$saved_data = get_post_meta( $post->ID, '_rhaokar_hex_data', true );
		if ( ! is_array( $saved_data ) ) {
			$saved_data = array();
		}

		$terrains = self::get_terrain_types();
		$matrix   = self::get_subtile_matrix();
		?>
		<style>
			.rhaokar-admin-hex-box { background: #1d2127; color: #e0e6ed; padding: 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #3a424d; }
			.rhaokar-admin-row-title { font-weight: bold; color: #ffd700; margin: 12px 0 6px 0; border-bottom: 1px solid #3a424d; padding-bottom: 4px; font-size: 0.95rem; }
			.rhaokar-admin-subtile-card { background: #252a32; border: 1px solid #4a5463; border-radius: 6px; padding: 10px; margin-bottom: 10px; }
			.rhaokar-admin-subtile-header { font-weight: bold; color: #52c41a; font-size: 0.9rem; margin-bottom: 6px; display: flex; justify-content: space-between; }
			.rhaokar-admin-field-group { margin-bottom: 6px; }
			.rhaokar-admin-field-group label { display: block; font-size: 0.78rem; color: #a0aec0; font-weight: 600; margin-bottom: 2px; }
			.rhaokar-admin-field-group input[type="text"], .rhaokar-admin-field-group select, .rhaokar-admin-field-group textarea { width: 100%; background: #141619; color: #fff; border: 1px solid #3a424d; border-radius: 4px; padding: 4px 8px; font-size: 0.85rem; }
		</style>

		<div class="rhaokar-admin-hex-box">
			<p style="margin-top: 0; color: #ffd700; font-weight: bold;">
				ℹ️ Defina o código do Hex no Título do Post acima (Exemplos: <code>U7</code>, <code>V8</code>, <code>W11</code>).
			</p>
			<p class="description" style="color: #a0aec0;">
				Preencha o terreno, localização e conteúdo para cada um dos 37 sub-tiles abaixo:
			</p>

			<?php foreach ( $matrix as $row_name => $tiles ) : ?>
				<div class="rhaokar-admin-row-title"><?php echo esc_html( $row_name ); ?> (<?php echo count( $tiles ); ?> Sub-tiles)</div>
				<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 10px;">
					<?php foreach ( $tiles as $tile_code ) : 
						$tile_info = $saved_data[ $tile_code ] ?? array();
						$curr_type = $tile_info['type'] ?? 'planicie';
						$curr_loc  = $tile_info['localizacao'] ?? '';
						$curr_npcs = $tile_info['npcs'] ?? '';
						$curr_monsters = $tile_info['monstros'] ?? '';
						$curr_rumors   = $tile_info['rumores'] ?? '';
						$curr_ruins    = $tile_info['ruinas'] ?? '';
					?>
						<div class="rhaokar-admin-subtile-card">
							<div class="rhaokar-admin-subtile-header">
								<span>Tile <?php echo esc_html( $tile_code ); ?></span>
							</div>

							<div class="rhaokar-admin-field-group">
								<label>Tipo de Terreno / Estrutura:</label>
								<select name="rhaokar_hex[<?php echo esc_attr( $tile_code ); ?>][type]">
									<?php foreach ( $terrains as $t_key => $t_label ) : ?>
										<option value="<?php echo esc_attr( $t_key ); ?>" <?php selected( $curr_type, $t_key ); ?>>
											<?php echo esc_html( $t_label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="rhaokar-admin-field-group">
								<label>Localização (Continente > Reino > Cidade):</label>
								<input type="text" name="rhaokar_hex[<?php echo esc_attr( $tile_code ); ?>][localizacao]" value="<?php echo esc_attr( $curr_loc ); ?>" placeholder="Ex: Rhaokar > Galorfindil > Forte da Vigia">
							</div>

							<div class="rhaokar-admin-field-group">
								<label>NPCs Notáveis:</label>
								<textarea name="rhaokar_hex[<?php echo esc_attr( $tile_code ); ?>][npcs]" rows="2" placeholder="Ex: Capitão Vane (Comandante do Forte)"><?php echo esc_textarea( $curr_npcs ); ?></textarea>
							</div>

							<div class="rhaokar-admin-field-group">
								<label>Monstros / Ameacas:</label>
								<textarea name="rhaokar_hex[<?php echo esc_attr( $tile_code ); ?>][monstros]" rows="2" placeholder="Ex: Bando de Goblins das Neves"><?php echo esc_textarea( $curr_monsters ); ?></textarea>
							</div>

							<div class="rhaokar-admin-field-group">
								<label>Rumores & Pistas:</label>
								<textarea name="rhaokar_hex[<?php echo esc_attr( $tile_code ); ?>][rumores]" rows="2" placeholder="Ex: Dizem que há ruínas ao norte"><?php echo esc_textarea( $curr_rumors ); ?></textarea>
							</div>

							<div class="rhaokar-admin-field-group">
								<label>Ruínas & Pontos de Interesse:</label>
								<textarea name="rhaokar_hex[<?php echo esc_attr( $tile_code ); ?>][ruinas]" rows="2" placeholder="Ex: Cripta Antiga dos Reis Nômades"><?php echo esc_textarea( $curr_ruins ); ?></textarea>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Salva Dados do Meta Box
	 */
	public function save_hex_meta_box_data( $post_id ) {
		if ( ! isset( $_POST['rhaokar_hex_nonce'] ) || ! wp_verify_nonce( $_POST['rhaokar_hex_nonce'], 'rhaokar_save_hex_meta' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['rhaokar_hex'] ) && is_array( $_POST['rhaokar_hex'] ) ) {
			$clean_data = array();
			foreach ( $_POST['rhaokar_hex'] as $tile_code => $fields ) {
				$clean_data[ sanitize_text_field( $tile_code ) ] = array(
					'type'        => sanitize_text_field( $fields['type'] ?? 'planicie' ),
					'localizacao' => sanitize_text_field( $fields['localizacao'] ?? '' ),
					'npcs'        => sanitize_textarea_field( $fields['npcs'] ?? '' ),
					'monstros'    => sanitize_textarea_field( $fields['monstros'] ?? '' ),
					'rumores'     => sanitize_textarea_field( $fields['rumores'] ?? '' ),
					'ruinas'      => sanitize_textarea_field( $fields['ruinas'] ?? '' ),
				);
			}
			update_post_meta( $post_id, '_rhaokar_hex_data', $clean_data );
		}
	}

	/**
	 * Carrega os Scripts e Estilos do Mapa
	 */
	public function enqueue_map_assets() {
		$theme_uri = get_stylesheet_directory_uri();
		$ver       = time();

		wp_register_script( 'stuquery', $theme_uri . '/js/stuquery.js', array( 'jquery' ), '1.0', true );
		wp_register_script( 'stuquery-hexmap', $theme_uri . '/js/stuquery.hexmap.js', array( 'stuquery' ), '1.0', true );
		wp_register_style( 'stuquery-hexmap-css', $theme_uri . '/css/stuquery.hexmap.css', array(), '1.0' );

		wp_register_style( 'rhaokar-hexmap-custom-css', $theme_uri . '/css/rhaokar-hexmap.css', array( 'stuquery-hexmap-css' ), $ver );
		wp_register_script( 'rhaokar-hexmap-interactive', $theme_uri . '/js/rhaokar-hexmap-interactive.js', array( 'jquery', 'stuquery-hexmap' ), $ver, true );
	}

	/**
	 * Renderiza o Mapa Global via Shortcode `[rhaokar_mapa]`
	 */
	public function render_mapa_shortcode( $atts ) {
		wp_enqueue_style( 'rhaokar-bootstrap' );
		wp_enqueue_style( 'stuquery-hexmap-css' );
		wp_enqueue_style( 'rhaokar-hexmap-custom-css' );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'stuquery' );
		wp_enqueue_script( 'stuquery-hexmap' );
		wp_enqueue_script( 'rhaokar-hexmap-interactive' );

		// Busca todos os Hexágonos Mapeados cadastrados no CPT
		$mapped_query = new WP_Query( array(
			'post_type'      => 'hex_mapeado',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		) );

		$mapped_hexes_data = array();
		if ( $mapped_query->have_posts() ) {
			while ( $mapped_query->have_posts() ) {
				$mapped_query->the_post();
				$title = strtoupper( trim( get_the_title() ) );
				$data  = get_post_meta( get_the_ID(), '_rhaokar_hex_data', true );
				if ( ! empty( $title ) ) {
					$mapped_hexes_data[ $title ] = array(
						'title' => get_the_title(),
						'tiles' => is_array( $data ) ? $data : array(),
					);
				}
			}
			wp_reset_postdata();
		}

		$hex_data_obj = array(
			'mappedHexes' => $mapped_hexes_data,
			'terrains'    => self::get_terrain_types(),
			'matrix'      => self::get_subtile_matrix(),
		);

		wp_localize_script( 'rhaokar-hexmap-interactive', 'rhaokarHexData', $hex_data_obj );

		$theme_uri = get_stylesheet_directory_uri();
		$ver       = time();

		ob_start();
		?>
		<!-- REGRAS CSS DO MAPA E SUPORTE AO ELEMENTOR -->
		<style id="rhaokar-hexmap-fail-safe-css">
			#hexmap-8 code,
			.rhaokar-hexmap-container code,
			.rhaokar-map-outer-container code {
				display: none !important;
				opacity: 0 !important;
				visibility: hidden !important;
				height: 0 !important;
				width: 0 !important;
				overflow: hidden !important;
				position: absolute !important;
				left: -9999px !important;
			}
			.rhaokar-modal-backdrop {
				display: none !important;
				position: fixed !important;
				top: 0 !important;
				left: 0 !important;
				width: 100vw !important;
				height: 100vh !important;
				background: rgba(0, 0, 0, 0.85) !important;
				z-index: 999999 !important;
				align-items: center !important;
				justify-content: center !important;
				padding: 20px !important;
				box-sizing: border-box !important;
			}
			.rhaokar-modal-backdrop.rhaokar-open {
				display: flex !important;
			}
			.rhaokar-modal-dialog {
				background: #1e2228 !important;
				color: #e0e6ed !important;
				border: 2px solid #b8860b !important;
				border-radius: 8px !important;
				max-width: 950px !important;
				width: 100% !important;
				max-height: 90vh !important;
				overflow-y: auto !important;
				padding: 20px !important;
				box-shadow: 0 15px 40px rgba(0,0,0,0.95) !important;
				position: relative !important;
			}
		</style>
		<link rel="stylesheet" href="<?php echo esc_url( $theme_uri . '/css/stuquery.hexmap.css' ); ?>?ver=<?php echo $ver; ?>">
		<link rel="stylesheet" href="<?php echo esc_url( $theme_uri . '/css/rhaokar-hexmap.css' ); ?>?ver=<?php echo $ver; ?>">

		<script>
			window.rhaokarHexData = <?php echo json_encode( $hex_data_obj ); ?>;
		</script>
		<script src="<?php echo esc_url( $theme_uri . '/js/stuquery.js' ); ?>?ver=<?php echo $ver; ?>"></script>
		<script src="<?php echo esc_url( $theme_uri . '/js/stuquery.hexmap.js' ); ?>?ver=<?php echo $ver; ?>"></script>
		<script src="<?php echo esc_url( $theme_uri . '/js/rhaokar-hexmap-interactive.js' ); ?>?ver=<?php echo $ver; ?>"></script>

		<div class="container-fluid rhaokar-map-outer-container py-3">
			<div class="text-center mb-3">
				<h1 class="rhaokar-map-main-title"><i class="dashicons dashicons-location-alt"></i> Mapa do Mundo de Rhaokar</h1>
				<p class="text-muted small">Passe o mouse ou toque nos hexágonos para identificar áreas mapeadas e explorar o Hexcrawl.</p>
			</div>

			<!-- MAPA GLOBAL HEXAGONAL -->
			<div id="rhaokar-world-hex-wrapper" class="position-relative text-center">
				<div id="hexmap-8" class="rhaokar-hexmap-container">
					<code style="display:none !important; visibility:hidden !important; opacity:0 !important; height:0 !important; width:0 !important; font-size:0 !important; overflow:hidden !important; position:absolute !important; text-indent:-9999px !important;"><?php
					$possible_map_paths = array(
						get_stylesheet_directory() . '/cenario/Mapa_rhaokar.html',
						get_stylesheet_directory() . '/Mapa_rhaokar.html',
						get_template_directory() . '/cenario/Mapa_rhaokar.html',
						get_template_directory() . '/Mapa_rhaokar.html',
						ABSPATH . 'wp-content/themes/hello-elementor-child/cenario/Mapa_rhaokar.html',
						ABSPATH . 'wp-content/themes/hello-elementor-child/Mapa_rhaokar.html',
						ABSPATH . 'wp-content/themes/hello-elementor-child-master/cenario/Mapa_rhaokar.html',
						ABSPATH . 'wp-content/themes/Rhaokar/cenario/Mapa_rhaokar.html',
					);

					$json_found = false;
					foreach ( $possible_map_paths as $html_map_path ) {
						if ( file_exists( $html_map_path ) ) {
							$content = file_get_contents( $html_map_path );
							$start = strpos( $content, '<code>' );
							$end   = strpos( $content, '</code>' );
							if ( false !== $start && false !== $end ) {
								$json_str = substr( $content, $start + 6, $end - ( $start + 6 ) );
								echo trim( $json_str );
								$json_found = true;
								break;
							}
						}
					}
					if ( ! $json_found ) {
						echo '{"layout":"even-r","hexes":{}}';
					}
					?></code>
				</div>
			</div>

			<!-- MODAL MEDIEVAL INTERATIVO DE SUB-HEXÁGONOS E DETALHES -->
			<div class="rhaokar-modal-backdrop" id="rhaokar-subhex-modal">
				<div class="rhaokar-modal-dialog rhaokar-modal-lg">
					<div class="rhaokar-modal-header">
						<h4 class="m-0 font-weight-bold text-warning" id="rhaokar-modal-hex-title">
							<i class="dashicons dashicons-location"></i> Detalhes do Hexágono
						</h4>
						<button type="button" class="rhaokar-modal-close" onclick="rhaokarCloseSubhexModal()">&times;</button>
					</div>
					<div class="rhaokar-modal-body p-3">
						<div class="row">
							<!-- MINI-MAPA DE 37 SUB-TILES -->
							<div class="col-lg-7 mb-3 mb-lg-0">
								<h6 class="text-warning border-bottom border-secondary pb-1 font-weight-bold">
									🗺️ Visão do Mini-mapa (37 Sub-tiles):
								</h6>
								<p class="small text-muted mb-2">Clique em um sub-tile para inspecionar os detalhes:</p>
								<div id="rhaokar-subtile-grid-container" class="p-2 rounded bg-dark border border-secondary text-center">
									<!-- Injetado dinamicamente via JS -->
								</div>
							</div>

							<!-- PAINEL DE CONTEÚDO E DETALHES DO SUB-TILE SELECIONADO -->
							<div class="col-lg-5">
								<h6 class="text-warning border-bottom border-secondary pb-1 font-weight-bold" id="rhaokar-subtile-detail-title">
									🔍 Selecione um Sub-tile
								</h6>
								<div id="rhaokar-subtile-detail-content" class="p-3 rounded bg-dark border border-secondary small text-light" style="min-height: 280px;">
									<em class="text-muted d-block text-center mt-4">Clique em qualquer hexágono do mini-mapa ao lado para visualizar os locais, NPCs, monstros e rumores.</em>
								</div>
							</div>
						</div>
					</div>
					<div class="text-right p-2 border-top border-secondary">
						<button type="button" class="btn btn-secondary btn-sm" onclick="rhaokarCloseSubhexModal()">Fechar Mapa</button>
					</div>
				</div>
			</div>
		</div>

		<script>
			if (typeof tryInitRhaokarHexMap === 'function') {
				tryInitRhaokarHexMap();
			}
		</script>
		<?php
		return ob_get_clean();
	}
}

// Inicializa a classe do gerenciador do mapa
Rhaokar_HexMap_Manager::get_instance();
