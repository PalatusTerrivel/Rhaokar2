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

	// Sincroniza GIFs do Tema com a Pasta de Uploads do WordPress
	$upload_dir = wp_upload_dir();
	$target_gif_dir = $upload_dir['basedir'] . '/rhaokar/gifs';
	if ( ! file_exists( $target_gif_dir ) ) {
		wp_mkdir_p( $target_gif_dir );
	}

	$theme_gif_dir = get_stylesheet_directory() . '/img/gifs';
	if ( is_dir( $theme_gif_dir ) ) {
		$files = glob( $theme_gif_dir . '/*.{gif,webp}', GLOB_BRACE );
		if ( $files ) {
			foreach ( $files as $file ) {
				$filename = basename( $file );
				$dest = $target_gif_dir . '/' . $filename;
				if ( ! file_exists( $dest ) || filemtime( $file ) > filemtime( $dest ) ) {
					copy( $file, $dest );
				}
			}
		}
	}
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
	$theme_url  = esc_url( get_stylesheet_directory_uri() );
	$upload_dir = wp_upload_dir();
	$upload_url = esc_url( $upload_dir['baseurl'] . '/2026/09/' );
	?>
	<style id="rhaokar-rpg-spawner-css">
		.rhaokar-walking-sprite {
			position: absolute;
			bottom: 10px !important; /* Pés perfeitamente alinhados com o chão */
			z-index: 1 !important; /* Fica atrás da grama */
			pointer-events: none;
			will-change: transform, left;
		}

		/* Fonte de luz noturna aquecida para raças noturnas */
		.rhaokar-walking-sprite.night-light {
			filter: drop-shadow(0 0 14px rgba(255, 215, 100, 0.9)) drop-shadow(0 0 5px rgba(255, 140, 0, 0.95)) !important;
		}

		/* Rebaixamento da grama para cobrir suavemente apenas as canelas/pés */
		#grass, .rhaokar-grama-img {
			position: relative;
			z-index: 2 !important;
			height: 110px !important;
			margin-top: 50px !important;
		}

		.contem_grama, .rhaokar-grama-container, .rhaokar-grama-box {
			position: relative;
			overflow: hidden;
			height: 160px !important;
		}
	</style>

	<script id="rhaokar-rpg-spawner-js">
	(function() {
		var uploadsImgDir = '<?php echo $upload_url; ?>';
		var themeImgDir = '<?php echo $theme_url; ?>/img/gifs/';

		var RACES_DATA = [
			// Bearfolk (2.5m)
			{ name: 'bearfolk', height: 160, time: 'any', file: 'bearfolk.gif', facing: 'right' },
			// Orc (2.0m)
			{ name: 'orc', height: 128, time: 'any', file: 'orc.gif', facing: 'left' },
			// Warforged, Lionfolk, Human (1.7m - 1.9m)
			{ name: 'warforged', height: 122, time: 'any', file: 'warforged.gif', facing: 'right' },
			{ name: 'lionfolk', height: 116, time: 'any', file: 'lionfolk.gif', facing: 'right' },
			{ name: 'human day', height: 110, time: 'day', file: 'human-day.gif', facing: 'right' },
			{ name: 'human night', height: 110, time: 'night', file: 'human-night.gif', light: true, facing: 'right' },
			// Elf (1.6m)
			{ name: 'elf day', height: 102, time: 'day', file: 'elf-day.gif', facing: 'right' },
			{ name: 'elf night', height: 102, time: 'night', file: 'elf-night.gif', light: true, facing: 'right' },
			// Dwarf & Bugfolk (1.3m - 1.4m)
			{ name: 'dwarf 1', height: 90, time: 'any', file: 'dwarf-1.gif', facing: 'right' },
			{ name: 'dwarf 2', height: 90, time: 'any', file: 'dwarf-2.gif', facing: 'right' },
			{ name: 'bugfolk day', height: 84, time: 'day', file: 'bugfolk-day.gif', facing: 'right' },
			{ name: 'bugfolk day 3', height: 84, time: 'day', file: 'bugfolk-day-3.gif', facing: 'right' },
			{ name: 'bugfolk night', height: 84, time: 'night', file: 'bugfolk-night.gif', light: true, weight: 3, facing: 'right' },
			{ name: 'bugfolk night 2', height: 84, time: 'night', file: 'bugfolk-night-2.gif', light: true, weight: 3, facing: 'right' },
			// Gnome & Goblin (1.2m)
			{ name: 'gnome day', height: 78, time: 'day', file: 'gnome-day.gif', facing: 'left' },
			{ name: 'gnome day 2', height: 78, time: 'day', file: 'gnome-day-2.gif', facing: 'right' },
			{ name: 'goblin 1', height: 78, time: 'any', file: 'goblin-1.gif', weight: 3, isGoblin: true, facing: 'left' },
			{ name: 'goblin 2', height: 78, time: 'any', file: 'goblin-2.gif', weight: 3, isGoblin: true, facing: 'right' },
			// Halfling & Kobold (1.0m)
			{ name: 'halfling day', height: 65, time: 'day', file: 'halfling-day.gif', isHalfling: true, facing: 'right' },
			{ name: 'halfling day 2', height: 65, time: 'day', file: 'halfling-day-2.gif', isHalfling: true, facing: 'right' },
			{ name: 'halfling day 3', height: 65, time: 'day', file: 'halfling-day-3.gif', isHalfling: true, facing: 'right' },
			{ name: 'halfling day 4', height: 65, time: 'day', file: 'halfling-day-4.gif', isHalfling: true, facing: 'right' },
			{ name: 'halfling night', height: 65, time: 'night', file: 'halfling-night.gif', light: true, isHalfling: true, weight: 2, facing: 'right' },
			{ name: 'halfling night 2', height: 65, time: 'night', file: 'halfling-night-2.gif', light: true, isHalfling: true, weight: 2, facing: 'right' },
			{ name: 'halfling night 3', height: 65, time: 'night', file: 'halfling-night-3.gif', light: true, isHalfling: true, weight: 2, facing: 'left' },
			{ name: 'blue kobold', height: 65, time: 'any', file: 'blue-kobold.gif', facing: 'right' },
			{ name: 'green kobold', height: 65, time: 'any', file: 'green-kobold.gif', facing: 'left' },
			{ name: 'red kobold', height: 65, time: 'any', file: 'red-kobold.gif', facing: 'right' },
			{ name: 'red kobold 2', height: 65, time: 'any', file: 'red-kobold-2.gif', facing: 'right' }
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
						var primaryUrl = uploadsImgDir + chosen.file;
						var fallbackUrl = themeImgDir + chosen.file;

						img.src = primaryUrl;
						img.onerror = function() {
							if (this.src !== fallbackUrl) {
								this.src = fallbackUrl;
							}
						};

						img.className = 'rhaokar-walking-sprite' + (chosen.light ? ' night-light' : '');
						img.style.height = chosen.height + 'px';

						var startPos = isLeftToRight ? -150 - (index * 45) : container.offsetWidth + 50 + (index * 45);
						var endPos = isLeftToRight ? container.offsetWidth + 150 : -150;

						// Tratamento de orientação individual (Esquerda vs Direita):
						var isFacingLeftNatively = (chosen.facing === 'left');
						var transformFlip;

						if (isLeftToRight) {
							// Caminhando para a DIREITA ➡️
							transformFlip = isFacingLeftNatively ? 'scaleX(-1)' : 'scaleX(1)';
						} else {
							// Caminhando para a ESQUERDA ⬅️
							transformFlip = isFacingLeftNatively ? 'scaleX(1)' : 'scaleX(-1)';
						}

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


