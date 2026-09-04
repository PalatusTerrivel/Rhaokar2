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

/**
 * Injeta o Gerenciador de Raças Animadas Caminhando no Gramado do Rhaokar
 */
function rhaokar_inject_rpg_spawner_script() {
	$theme_url = esc_url( get_stylesheet_directory_uri() );
	?>
	<style id="rhaokar-rpg-spawner-css">
		.rhaokar-walking-sprite {
			position: absolute;
			bottom: 5px;
			z-index: 1 !important; /* Fica atrás da grama */
			pointer-events: none;
			will-change: transform, left;
		}

		/* Fonte de luz noturna aquecida para raças noturnas */
		.rhaokar-walking-sprite.night-light {
			filter: drop-shadow(0 0 14px rgba(255, 215, 100, 0.9)) drop-shadow(0 0 5px rgba(255, 140, 0, 0.95)) !important;
		}

		/* Garantir que a grama fique na frente */
		#grass, .rhaokar-grama-img {
			position: relative;
			z-index: 2 !important;
		}

		.contem_grama, .rhaokar-grama-container, .rhaokar-grama-box {
			position: relative;
			overflow: hidden;
		}
	</style>

	<script id="rhaokar-rpg-spawner-js">
	(function() {
		var themeImgDir = '<?php echo $theme_url; ?>/img/gifs/';

		var RACES_DATA = [
			// Bearfolk (2.5m)
			{ name: 'bearfolk', height: 180, time: 'any', file: 'bearfolk.webp' },
			// Orc (2.0m)
			{ name: 'orc', height: 144, time: 'any', file: 'orc.webp' },
			// Warforged, Lionfolk, Human (1.7m - 1.9m)
			{ name: 'warforged', height: 136, time: 'any', file: 'warforged.webp' },
			{ name: 'lionfolk', height: 130, time: 'any', file: 'lionfolk.webp' },
			{ name: 'human day', height: 122, time: 'day', file: 'human day.webp' },
			{ name: 'human night', height: 122, time: 'night', file: 'human night.webp', light: true },
			// Elf (1.6m)
			{ name: 'elf day', height: 115, time: 'day', file: 'elf day.webp' },
			{ name: 'elf night', height: 115, time: 'night', file: 'elf night.webp', light: true },
			// Dwarf & Bugfolk (1.3m - 1.4m)
			{ name: 'dwarf 1', height: 100, time: 'any', file: 'dwarf 1.webp' },
			{ name: 'dwarf 2', height: 100, time: 'any', file: 'dwarf 2.webp' },
			{ name: 'bugfolk day', height: 94, time: 'day', file: 'bugfolk day.webp' },
			{ name: 'bugfolk day 3', height: 94, time: 'day', file: 'bugfolk day 3.webp' },
			{ name: 'bugfolk night', height: 94, time: 'night', file: 'bugfolk night.webp', light: true, weight: 3 },
			{ name: 'bugfolk night 2', height: 94, time: 'night', file: 'bugfolk night 2.webp', light: true, weight: 3 },
			// Gnome & Goblin (1.2m)
			{ name: 'gnome day', height: 86, time: 'day', file: 'gnome day.webp' },
			{ name: 'gnome day 2', height: 86, time: 'day', file: 'gnome day 2.webp' },
			{ name: 'goblin 1', height: 86, time: 'any', file: 'goblin 1.webp', weight: 3, isGoblin: true },
			{ name: 'goblin 2', height: 86, time: 'any', file: 'goblin 2.webp', weight: 3, isGoblin: true },
			// Halfling & Kobold (1.0m)
			{ name: 'halfling day', height: 72, time: 'day', file: 'halfling day.webp', isHalfling: true },
			{ name: 'halfling day 2', height: 72, time: 'day', file: 'halfling day 2.webp', isHalfling: true },
			{ name: 'halfling day 3', height: 72, time: 'day', file: 'halfling day 3.webp', isHalfling: true },
			{ name: 'halfling day 4', height: 72, time: 'day', file: 'halfling day 4.webp', isHalfling: true },
			{ name: 'halfling night', height: 72, time: 'night', file: 'halfling night.webp', light: true, isHalfling: true, weight: 2 },
			{ name: 'halfling night 2', height: 72, time: 'night', file: 'halfling night 2.webp', light: true, isHalfling: true, weight: 2 },
			{ name: 'halfling night 3', height: 72, time: 'night', file: 'halfling night 3.webp', light: true, isHalfling: true, weight: 2 },
			{ name: 'blue kobold', height: 72, time: 'any', file: 'blue kobold.webp' },
			{ name: 'green kobold', height: 72, time: 'any', file: 'green kobold.webp' },
			{ name: 'red kobold', height: 72, time: 'any', file: 'red kobold.webp' },
			{ name: 'red kobold 2', height: 72, time: 'any', file: 'red kobold 2.webp' }
		];

		function isNightTime() {
			var isNightClass = document.body.classList.contains('sky-night') || document.documentElement.classList.contains('sky-night');
			if (isNightClass) return true;
			var hour = new Date().getHours();
			return (hour >= 19 || hour < 5);
		}

		function getEligiblePool() {
			var isNight = isNightTime();
			var pool = [];

			for (var i = 0; i < RACES_DATA.length; i++) {
				var r = RACES_DATA[i];
				var allowed = false;

				if (r.time === 'any') allowed = true;
				else if (r.time === 'night' && isNight) allowed = true;
				else if (r.time === 'day' && !isNight) allowed = true;

				if (allowed) {
					var w = r.weight || 1;
					for (var k = 0; k < w; k++) {
						pool.push(r);
					}
				}
			}
			return pool;
		}

		function spawnWalkers() {
			var containers = document.querySelectorAll('.contem_grama, .rhaokar-grama-container, .rhaokar-grama-box');
			if (!containers.length) return;

			var pool = getEligiblePool();
			if (!pool.length) return;

			var chosen = pool[Math.floor(Math.random() * pool.length)];
			var isLeftToRight = Math.random() > 0.5;

			var groupCount = 1;
			if (chosen.isGoblin && Math.random() < 0.25) {
				groupCount = Math.floor(Math.random() * 3) + 3; // Bando de 3 a 5 goblins
			} else if (chosen.isHalfling && Math.random() < 0.30) {
				groupCount = 2; // Dupla de halflings
			}

			containers.forEach(function(container) {
				for (var g = 0; g < groupCount; g++) {
					(function(index) {
						var img = document.createElement('img');
						img.src = themeImgDir + chosen.file;
						img.className = 'rhaokar-walking-sprite' + (chosen.light ? ' night-light' : '');
						img.style.height = chosen.height + 'px';

						var startPos = isLeftToRight ? -150 - (index * 45) : container.offsetWidth + 50 + (index * 45);
						var endPos = isLeftToRight ? container.offsetWidth + 150 : -150;
						var transformFlip = isLeftToRight ? 'scaleX(1)' : 'scaleX(-1)';

						img.style.transform = transformFlip;
						img.style.left = startPos + 'px';

						container.appendChild(img);

						var duration = 16000 + Math.random() * 4000;
						var startTime = null;

						function animate(timestamp) {
							if (!startTime) startTime = timestamp;
							var progress = (timestamp - startTime) / duration;

							if (progress < 1) {
								var currentPos = startPos + (endPos - startPos) * progress;
								img.style.left = currentPos + 'px';
								requestAnimationFrame(animate);
							} else {
								if (img.parentNode) img.parentNode.removeChild(img);
							}
						}

						setTimeout(function() {
							requestAnimationFrame(animate);
						}, index * 350);
					})(g);
				}
			});
		}

		document.addEventListener('DOMContentLoaded', function() {
			spawnWalkers();
			setInterval(spawnWalkers, 14000);
		});
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'rhaokar_inject_rpg_spawner_script', 999 );


