<?php
/**
 * Hello Elementor Child (Rhaokar) Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Ativa o suporte do Elementor para TODOS os Tipos de Conteúdo (Páginas, Posts, Raças, Deuses, Reinos, Logs, Personagens)
 */
function rhaokar_enable_elementor_support() {
	$cpts = array( 'page', 'post', 'raca', 'deus', 'reino', 'log_campanha', 'personagem' );
	update_option( 'elementor_cpt_support', $cpts );
}
add_action( 'after_setup_theme', 'rhaokar_enable_elementor_support' );
add_action( 'admin_init', 'rhaokar_enable_elementor_support' );

/**
 * Injeta Estilos Absolutos no Cabeçalho para forçar o Fundo Azul-Céu, Nuvens, Grama e Fontes RPG
 */
function rhaokar_custom_head_styles() {
	$theme_url = esc_url( get_stylesheet_directory_uri() );
	?>
	<style id="rhaokar-inline-css">
		/* Força fundo azul-céu oficial em 100% da tela */
		html, body, #page, #content, .site, .site-main, main, article, .elementor, .e-con, .elementor-page {
			background-color: #ACE6FF !important;
		}
		body {
			margin: 0;
			padding: 0;
			font-family: 'Almendra', 'Times New Roman', serif;
		}
		.titulo-principal {
			font-family: 'NewRocker', 'Times New Roman', serif !important;
			color: #000 !important;
		}
		.botao text {
			font-family: 'NewRocker', 'Times New Roman', serif !important;
		}
		/* Animação de Nuvens com URL Absoluta */
		.contem_nuvem {
			display: block;
			width: 100%;
			overflow: hidden;
			height: 185px;
			background-color: #ACE6FF;
		}
		#clouds {
			background: url('<?php echo $theme_url; ?>/img/Sprite-NuvemSite.png') repeat-x 0 bottom #ACE6FF !important;
			height: 185px;
		}
		/* Animação de Grama com URL Absoluta */
		.contem_grama {
			display: block;
			width: 100%;
			overflow: hidden;
			height: 185px;
			background-color: transparent;
		}
		#grass {
			background: url('<?php echo $theme_url; ?>/img/Sprite-GramaSite.png') repeat-x 0 0 transparent !important;
			height: 190px;
		}
		/* Cards de Papiro */
		.contem-deuses, .contem-racas {
			background: url('<?php echo $theme_url; ?>/img/papyrus1.svg') repeat, #f9f2e7 !important;
			border: 3px solid #8b5a2b !important;
			border-radius: 8px;
			box-shadow: 0 4px 15px rgba(0,0,0,0.25);
		}
	</style>
	<?php
}
add_action( 'wp_head', 'rhaokar_custom_head_styles', 999 );

/**
 * Carrega CSS/JS do Tema
 */
function rhaokar_child_enqueue_assets() {
	$ver = time();

	wp_enqueue_style( 'hello-elementor-parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'rhaokar-bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array(), '4.3.1' );
	wp_enqueue_style( 'rhaokar-honeycomb', get_stylesheet_directory_uri() . '/css/honeycomb_e_efeitos.css', array(), $ver );
	wp_enqueue_style( 'rhaokar-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'hello-elementor-parent-style' ), $ver );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'rhaokar-bootstrap-js', get_stylesheet_directory_uri() . '/js/bootstrap.bundle.min.js', array( 'jquery' ), '4.3.1', true );
}
add_action( 'wp_enqueue_scripts', 'rhaokar_child_enqueue_assets', 99 );

/**
 * Registra Localizações de Menus do Tema
 */
function rhaokar_child_register_menus() {
	register_nav_menus( array(
		'rhaokar-header-menu' => __( 'Menu Superior Rhaokar', 'rhaokar-child' ),
	) );
}
add_action( 'init', 'rhaokar_child_register_menus' );

/**
 * Cria automaticamente as Páginas do Rhaokar
 */
function rhaokar_auto_setup_pages() {
	$pages = array(
		'home'       => 'Página Inicial',
		'racas'      => 'As Raças',
		'deuses'     => 'Os Deuses',
		'reinos'     => 'Os Reinos',
		'diario'     => 'Diário da Campanha',
		'magia'      => 'A Magia',
		'alquimia'   => 'A Alquimia',
		'tecnologia' => 'A Tecnologia',
		'regras'     => 'Regras Alternativas',
	);

	foreach ( $pages as $slug => $title ) {
		$page_check = get_page_by_path( $slug );
		if ( ! isset( $page_check->ID ) ) {
			$page_id = wp_insert_post( array(
				'post_type'    => 'page',
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_content' => '',
			) );

			if ( 'home' === $slug && $page_id ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $page_id );
			}
		}
	}

	flush_rewrite_rules();
}
add_action( 'admin_init', 'rhaokar_auto_setup_pages' );
