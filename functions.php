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
		html, body, #page, #content, .site, main, article, .contem_nuvem, .rhaokar-nuvem-box {
			transition: background 1.5s ease-in-out, filter 1.5s ease-in-out !important;
		}

		/* 1. AMANHECER (05:00 - 08:00) */
		html.sky-sunrise, body.sky-sunrise,
		html.sky-sunrise body,
		body.sky-sunrise #page, body.sky-sunrise #content, body.sky-sunrise .site, body.sky-sunrise main,
		body.sky-sunrise .contem_nuvem, body.sky-sunrise .rhaokar-nuvem-box, body.sky-sunrise #clouds {
			background: linear-gradient(90deg, #FF7E5F 0%, #FEB47B 40%, #ACE6FF 100%) !important;
		}

		/* 2. DIA CLARO (08:00 - 17:00) */
		html.sky-day, body.sky-day,
		html.sky-day body,
		body.sky-day #page, body.sky-day #content, body.sky-day .site, body.sky-day main,
		body.sky-day .contem_nuvem, body.sky-day .rhaokar-nuvem-box, body.sky-day #clouds {
			background: #ACE6FF !important;
		}

		/* 3. ANOITECER / PÔR DO SOL (17:00 - 19:00) */
		html.sky-sunset, body.sky-sunset,
		html.sky-sunset body,
		body.sky-sunset #page, body.sky-sunset #content, body.sky-sunset .site, body.sky-sunset main,
		body.sky-sunset .contem_nuvem, body.sky-sunset .rhaokar-nuvem-box, body.sky-sunset #clouds {
			background: linear-gradient(90deg, #8E2DE2 0%, #4A00E0 30%, #FF6B6B 70%, #FF8E53 100%) !important;
		}

		/* 4. NOITE (19:00 - 05:00) */
		html.sky-night, body.sky-night,
		html.sky-night body,
		body.sky-night #page, body.sky-night #content, body.sky-night .site, body.sky-night main,
		body.sky-night .contem_nuvem, body.sky-night .rhaokar-nuvem-box, body.sky-night #clouds {
			background: linear-gradient(180deg, #0B1D3A 0%, #102A45 50%, #1A365D 100%) !important;
		}

		/* Brilho Noturno das Nuvens */
		body.sky-night #clouds, body.sky-night .rhaokar-nuvem-img {
			filter: brightness(0.6) hue-rotate(190deg) !important;
		}

		#clouds {
			background-image: url('<?php echo $theme_url; ?>/img/Sprite-NuvemSite.png') !important;
			background-repeat: repeat-x !important;
			background-size: auto 185px !important;
			animation: rhaokar-move-clouds 25s linear infinite !important;
		}
		#grass {
			background-image: url('<?php echo $theme_url; ?>/img/Sprite-GramaSite.png') !important;
			background-repeat: repeat-x !important;
			animation: rhaokar-skew-grass 3s infinite alternate ease-in-out !important;
		}
		.contem-deuses, .contem-racas {
			background: url('<?php echo $theme_url; ?>/img/papyrus1.svg') repeat, #f9f2e7 !important;
		}
	</style>

	<script id="rhaokar-sky-theme-js">
	(function() {
		function updateSkyTheme() {
			var urlParams = new URLSearchParams(window.location.search);
			var forceSky = urlParams.get('sky');
			var hour = new Date().getHours();
			var themeClass = 'sky-night';

			if (forceSky) {
				themeClass = 'sky-' + forceSky;
			} else {
				if (hour >= 5 && hour < 8) {
					themeClass = 'sky-sunrise';
				} else if (hour >= 8 && hour < 17) {
					themeClass = 'sky-day';
				} else if (hour >= 17 && hour < 19) {
					themeClass = 'sky-sunset';
				} else {
					themeClass = 'sky-night';
				}
			}

			var classes = ['sky-sunrise', 'sky-day', 'sky-sunset', 'sky-night'];
			for (var i = 0; i < classes.length; i++) {
				if (document.documentElement) document.documentElement.classList.remove(classes[i]);
				if (document.body) document.body.classList.remove(classes[i]);
			}

			if (document.documentElement) document.documentElement.classList.add(themeClass);
			if (document.body) document.body.classList.add(themeClass);
		}

		updateSkyTheme();
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', updateSkyTheme);
		}
	})();
	</script>
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

