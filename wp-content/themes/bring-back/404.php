<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package bring_back
 */

get_header();
?>

    <main id="main" class="site-main min">
        <!-- .page-404 start -->
        <section class="page-404 pt-90">
            <div class="container">
                <div class="row">
                    <div class="error-404 not-found col-12">
                        <h1 class="page-title" style="font-size: 27px;"><?php esc_html_e( 'Oops! Página no encontrada.', 'bring-back' ); ?></h1>
                        <br>
                        <div class="page-content">
                            <p><?php esc_html_e( 'Parece que no se encontró nada en esta ubicación. Tal vez intente uno de los enlaces a continuación o una búsqueda?', 'bring-back' ); ?></p>

                            <?php
                            get_search_form();
                            ?>

                        </div><!-- .page-content -->
                    </div><!-- .error-404 -->
                </div>
            </div>
        </section>
        <!-- .page-404 end -->
    </main><!-- #main -->

<?php
get_footer();
