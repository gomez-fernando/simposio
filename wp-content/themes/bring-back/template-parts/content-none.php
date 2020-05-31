<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bring_back
 */

?>

<div class="no-results not-found">
	<div class="page-header">
		<h1 class="page-title" style="font-size: 27px;"><?php esc_html_e( 'Nada Encontrado', 'bring-back' ); ?></h1>
	</div><!-- .page-header -->

	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :

			printf(
				'<p>' . wp_kses(
					/* translators: 1: link to WP admin new post page. */
					__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'bring-back' ),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				) . '</p>',
				esc_url( admin_url( 'post-new.php' ) )
			);

		elseif ( is_search() ) :
			?>

			<p><?php esc_html_e( 'Lo sentimos, pero nada coincide con sus términos de búsqueda. Intente nuevamente con algunas palabras clave diferentes.', 'bring-back' ); ?></p>
			<?php
			get_search_form();

		else :
			?>

			<p><?php esc_html_e( 'Parece que no podemos encontrar lo que estás buscando. Quizás la búsqueda pueda ayudar.', 'bring-back' ); ?></p>
			<?php
			get_search_form();

		endif;
		?>
	</div><!-- .page-content -->
</div><!-- .no-results -->
