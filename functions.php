<?php
/**
 * Tema Standalone Oficial do Rhaokar - Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Carrega CSS e Scripts JS do Tema Rhaokar
 */
function rhaokar_enqueue_theme_assets() {
	$ver = time();
	$theme_uri = get_stylesheet_directory_uri();

	// Bootstrap CSS
	wp_enqueue_style( 'rhaokar-bootstrap', $theme_uri . '/css/bootstrap.min.css', array(), '4.3.1' );

	// Honeycomb e Efeitos
	wp_enqueue_style( 'rhaokar-honeycomb', $theme_uri . '/css/honeycomb_e_efeitos.css', array(), $ver );

	// Estilo Principal do Tema
	wp_enqueue_style( 'rhaokar-main-style', $theme_uri . '/style.css', array( 'rhaokar-bootstrap' ), $ver );

	// jQuery e Bootstrap JS
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'rhaokar-bootstrap-js', $theme_uri . '/js/bootstrap.bundle.min.js', array( 'jquery' ), '4.3.1', true );
}
add_action( 'wp_enqueue_scripts', 'rhaokar_enqueue_theme_assets' );

/**
 * Injeta Estilos Inline com URLs Absolutas para Garantir o Fundo, Nuvens e Grama
 */
function rhaokar_inject_inline_head_styles() {
	$theme_url = esc_url( get_stylesheet_directory_uri() );
	?>
	<style id="rhaokar-standalone-inline-css">
		html, body, #page, #content, .site, main, article {
			background-color: #ACE6FF !important;
		}
		#clouds {
			background: url('<?php echo $theme_url; ?>/img/Sprite-NuvemSite.png') repeat-x 0 bottom #ACE6FF !important;
		}
		#grass {
			background: url('<?php echo $theme_url; ?>/img/Sprite-GramaSite.png') repeat-x 0 0 transparent !important;
		}
		.contem-deuses, .contem-racas {
			background: url('<?php echo $theme_url; ?>/img/papyrus1.svg') repeat, #f9f2e7 !important;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'rhaokar_inject_inline_head_styles', 999 );

/**
 * Registra Localização dos Menus
 */
function rhaokar_register_theme_menus() {
	register_nav_menus( array(
		'rhaokar-header-menu' => __( 'Menu Superior Rhaokar', 'rhaokar' ),
	) );
}
add_action( 'init', 'rhaokar_register_theme_menus' );

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

/**
 * Fallback de segurança para funções do tema Hello Elementor
 */
if ( ! function_exists( 'hello_elementor_display_header_footer' ) ) {
	function hello_elementor_display_header_footer() {
		return false;
	}
}

