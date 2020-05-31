<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bring_back
 */

// Footer Top ( CTA )
$enable_footer_top =  get_theme_mod( 'enable_footer_top', false );
$footer_top_text = get_theme_mod( 'footer_top_text', '' );
$footer_top_btn_text = get_theme_mod( 'footer_top_btn_text', '' );
$footer_top_btn_url = get_theme_mod( 'footer_top_btn_url', '' );

// Footer Copy Right
$footer_copyright = get_theme_mod( 'footer_copyright', esc_html( 'Bring Back powered by themetim' ) );

// Footer Layout

$footer_columns = get_theme_mod( 'footer_columns', '4' );

?>

<!-- .footer start -->
<?php if ( is_front_page() ) :  ?>
    <footer class="footer" style="">
<?php else :  ?>
    <footer class="footer" style="border-top: 1px solid;">
<?php endif;  ?>


    <?php if ( true == esc_attr( $enable_footer_top ) ) : ?>
    <!-- .footer-top start -->
    <div class="container footer-top wow slideInUp" data-wow-delay=".1s">
        <div class="row">
            <div class="col-12">
                <div class="footer-top-wrapper d-lg-flex align-items-lg-center">

                    <?php if ( esc_html( $footer_top_text ) != '' ) : ?>
                    <h2 class="float-left"><?php echo esc_html( $footer_top_text ); ?></h2>
                    <?php endif;

                    if ( esc_html( $footer_top_btn_text ) != '' ) : ?>
                    <a href="<?php echo esc_url( $footer_top_btn_url ); ?>" class="btn ml-lg-auto"><?php echo esc_html( $footer_top_btn_text ); ?></a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
    <!-- .footer-top end -->
    <?php endif; ?>

     <?php if ( is_active_sidebar( 'footer-widget' ) or is_active_sidebar( 'footer-widget-2' ) or is_active_sidebar( 'footer-widget-3' ) or is_active_sidebar( 'footer-widget-4' ) ) : ?>
    <!-- .footer-middle start -->
    <div class="container footer-middle">
        <div class="row">
            <?php get_template_part( 'footer-layout/layout' ); ?>
        </div>
    </div>
    <!-- .footer-middle end -->
    <?php endif; ?>





<!-- modal-->
<div class="modal fade" id="modalcookie1" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" style="font-size: 18px;">Aviso legal y Condiciones de Uso</h3>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" class="spanclose">×</span><span class="sr-only">Close</span></button>
      </div>
      <div class="modal-body">
<iframe src="https://www.doctaforum.org/--AvisoLegal-Cookies/Aviso-Legal-ES-para-Doctaforum-Congresos.php" height="700px" style="background: #FFFFFF;width: 100%;"></iframe>    </div>
      </div>
    </div>
  </div>
</div>
<!-- modal-->

<!-- modal-->
<div class="modal fade" id="modalcookie2" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" style="font-size: 18px;">Política de Cookies</h3>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" class="spanclose">×</span><span class="sr-only">Close</span></button>
      </div>
      <div class="modal-body">
<iframe src="https://www.doctaforum.org/--AvisoLegal-Cookies/Cookies-ES-2daCapa.php" height="300px" style="background: #FFFFFF;width: 100%;"></iframe> 
      </div>
    </div>
  </div>
</div>
<!-- modal--></div>



</footer>
<!-- .footer end -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
