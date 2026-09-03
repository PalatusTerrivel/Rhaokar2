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

	// Estilo do Tema Filho
	wp_enqueue_style( 'rhaokar-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'hello-elementor-parent-style' ), '1.0.0' );

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
