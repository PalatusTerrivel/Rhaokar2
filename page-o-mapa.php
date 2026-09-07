<?php
/**
 * Template Name: Mapa do Mundo de Rhaokar
 * Template Post Type: page
 * Description: Template exclusivo e independente para o Mapa Hexagonal do Mundo de Rhaokar
 */

get_header();
?>

<div class="contem_nuvem position-relative">
	<div id="clouds" class="rhaokar-nuvem-box"></div>
</div>

<main id="primary" class="site-main py-2">
	<?php
	if ( class_exists( 'Rhaokar_HexMap_Manager' ) ) {
		echo Rhaokar_HexMap_Manager::get_instance()->render_mapa_shortcode( array() );
	} else {
		echo do_shortcode( '[rhaokar_mapa]' );
	}
	?>
</main>

<?php
get_footer();
