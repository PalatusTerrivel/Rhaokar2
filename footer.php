<?php
/**
 * Footer template for Hello Elementor Child (Rhaokar)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
	if ( function_exists( 'hello_elementor_display_header_footer' ) && hello_elementor_display_header_footer() ) {
		get_template_part( 'template-parts/footer' );
	}
}
?>

<!-- Animação de Grama do Rhaokar -->
<div class="contem_grama">
	<div id="grass"></div>
</div>

<?php wp_footer(); ?>
</body>
</html>
