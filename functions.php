<?php
/**
 * Hello Elementor Child (Rhaokar) Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue Parent and Child Styles & Scripts
 */
function rhaokar_child_enqueue_assets() {
	// Estilo do Tema Pai (Hello Elementor)
	wp_enqueue_style( 'hello-elementor-parent-style', get_template_directory_uri() . '/style.css' );

	// Bootstrap CSS do Rhaokar
	wp_enqueue_style( 'rhaokar-bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array(), '4.3.1' );

	// Estilos Honeycomb e Animações
	wp_enqueue_style( 'rhaokar-honeycomb', get_stylesheet_directory_uri() . '/css/honeycomb_e_efeitos.css', array(), '1.0' );

	// Estilo do Tema Filho (com Fontes RPG e Cores Globais)
	wp_enqueue_style( 'rhaokar-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'hello-elementor-parent-style' ), '1.0.1' );

	// Scripts JS do Rhaokar
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'rhaokar-bootstrap-js', get_stylesheet_directory_uri() . '/js/bootstrap.bundle.min.js', array( 'jquery' ), '4.3.1', true );
}
add_action( 'wp_enqueue_scripts', 'rhaokar_child_enqueue_assets' );

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
 * Cria automaticamente as Páginas do Rhaokar no menu "Páginas > Todas as páginas"
 */
function rhaokar_auto_create_pages() {
	$pages = array(
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
			wp_insert_post( array(
				'post_type'    => 'page',
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_content' => '<!-- Conteúdo da página ' . $title . ' -->',
			) );
		}
	}
}
add_action( 'after_switch_theme', 'rhaokar_auto_create_pages' );
add_action( 'admin_init', 'rhaokar_auto_create_pages' );
