<?php
/**
 * Header template for Hello Elementor Child (Rhaokar)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Animação de Nuvens do Rhaokar -->
<div class="contem_nuvem">
	<div id="clouds"></div>
</div>

<!-- Barra de Navegação Bootstrap Oficial do Rhaokar -->
<nav class="navbar navbar-expand-sm bg-dark navbar-dark shadow-sm">
	<div class="container-fluid">
		<a class="navbar-brand font-weight-bold" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#rhaokarNavbar">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="rhaokarNavbar">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="racasDropdown" data-toggle="dropdown">As Raças</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="<?php echo esc_url( home_url( '/racas/' ) ); ?>">Ver Todas as Raças</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="<?php echo esc_url( home_url( '/racas/beastfolk/' ) ); ?>">O Povo-Fera</a>
						<a class="dropdown-item" href="<?php echo esc_url( home_url( '/racas/elfos/' ) ); ?>">Os Elfos</a>
						<a class="dropdown-item" href="<?php echo esc_url( home_url( '/racas/forjados/' ) ); ?>">Os Forjados</a>
						<a class="dropdown-item" href="<?php echo esc_url( home_url( '/racas/draconicos/' ) ); ?>">Os Dracônicos</a>
						<a class="dropdown-item" href="<?php echo esc_url( home_url( '/racas/reptilianos/' ) ); ?>">Os Reptilianos</a>
						<a class="dropdown-item" href="<?php echo esc_url( home_url( '/racas/anoes/' ) ); ?>">Os Anões</a>
						<a class="dropdown-item" href="<?php echo esc_url( home_url( '/racas/limos/' ) ); ?>">Os Limos</a>
						<a class="dropdown-item" href="<?php echo esc_url( home_url( '/racas/povo-inseto/' ) ); ?>">O Povo-Inseto</a>
						<a class="dropdown-item" href="<?php echo esc_url( home_url( '/racas/orcs/' ) ); ?>">Os Orcs</a>
					</div>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="cenarioDropdown" data-toggle="dropdown">O Cenário</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="<?php echo esc_url( home_url( '/deuses/' ) ); ?>">Os Deuses</a>
						<a class="dropdown-item" href="<?php echo esc_url( home_url( '/reinos/' ) ); ?>">Os Reinos</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo esc_url( home_url( '/diario/' ) ); ?>">Diário das Campanhas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo esc_url( home_url( '/magia/' ) ); ?>">Magia</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo esc_url( home_url( '/alquimia/' ) ); ?>">Alquimia</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo esc_url( home_url( '/tecnologia/' ) ); ?>">Tecnologia</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo esc_url( home_url( '/regras/' ) ); ?>">Regras Alternativas</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
