<?php
/**
 * Hello Elementor Child (Rhaokar) Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue Parent and Child Styles & Scripts com Cache Busting
 */
function rhaokar_child_enqueue_assets() {
	$ver = time(); // Força a atualização do cache de estilos a cada carregamento

	// Estilo do Tema Pai (Hello Elementor)
	wp_enqueue_style( 'hello-elementor-parent-style', get_template_directory_uri() . '/style.css' );

	// Bootstrap CSS do Rhaokar
	wp_enqueue_style( 'rhaokar-bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array(), '4.3.1' );

	// Estilos Honeycomb e Animações
	wp_enqueue_style( 'rhaokar-honeycomb', get_stylesheet_directory_uri() . '/css/honeycomb_e_efeitos.css', array(), $ver );

	// Estilo do Tema Filho (com Fontes RPG e Cores Globais)
	wp_enqueue_style( 'rhaokar-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'hello-elementor-parent-style' ), $ver );

	// Scripts JS do Rhaokar
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
 * Cria automaticamente a Página Inicial e todas as Páginas do Rhaokar
 * e corrige Links Permanentes (Regras de 404)
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

			// Se for a página inicial, define como Front Page estática do WordPress
			if ( 'home' === $slug && $page_id ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $page_id );
			}
		}
	}

	// Força a atualização das regras de URL do WordPress para ELIMINAR erros 404
	flush_rewrite_rules();
}
add_action( 'admin_init', 'rhaokar_auto_setup_pages' );
add_action( 'after_switch_theme', 'rhaokar_auto_setup_pages' );
