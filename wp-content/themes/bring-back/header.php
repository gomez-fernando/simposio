<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bring_back
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>



<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-134520141-38"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-134520141-38');
    </script>





<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'bring-back' ); ?></a>
<?php wp_body_open(); ?>
<?php

$pre_loader = get_theme_mod( 'enable_preloader', true );

if( esc_attr( $pre_loader ) == true ) :

?>
<!-- #preloader start -->
<div id="preloader" class="text-center">
    <div class="d-table">
        <div class="d-table-cell align-middle">
            <div class="spinner-grow" role="status">
                <span class="sr-only"><?php esc_html_e( 'Loading...', 'bring-back' ); ?></span>
            </div>
        </div>
    </div>
</div>
    <!-- #preloader end -->
<?php endif; ?>
<!-- #preloader end -->
<div id="page" class="site-wrap">
    <!-- .header start -->
    <header id="masthead" class="header site-header" style="background-image: url('https://www.doctaforum.org/2021/simposiosepar1234/wp-content/themes/bring-back/images/background.png');">
        <div class="header-wrapper">
            <div class="container">
                <!-- .main-menu start -->
                <div class="row main-menu">
                    <nav class="navbar navbar-expand-lg col-12 text-capitalize pb-0 pt-0">
                        <!-- .logo start -->
                        <div class="logo-wrap">
                            <a href="https://www.separ.es/" class="custom-logo-link" rel="home" target="_blank">
                                <img class="custom-logo" src="https://www.doctaforum.org/2021/simposiosepar1234/wp-content/themes/bring-back/images/logo-cliente.png">
                            </a>
                            
                        </div>
                        <!-- .logo end -->

                        <!-- .navbar-toggler start -->
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bring-back-main-menu-id" aria-controls="bring-back-main-menu-id" aria-expanded="false">
                            <span></span>
                            <span></span>
                        </button>
                        <!-- .navbar-toggler end -->

                        <!-- .navbar-collapse start -->
                        <div class="collapse navbar-collapse" id="bring-back-main-menu-id">
                            <?php

                            wp_nav_menu( array(
                                    'theme_location'    => 'menu-1',
                                    'container'			=> '',
                                    'menu_class'		=> 'navbar-nav ml-auto',
                                    'fallback_cb'		=> 'WP_Bootstrap_Navwalker::fallback',
                                    'walker'			=> new WP_Bootstrap_Navwalker()
                                )
                            );

                            // Custom link ( header )
                            $custom_nav_link = get_theme_mod( 'custom_nav_link' );
                            $custom_nav_title = get_theme_mod( 'custom_nav_title' );
                            // Search
                            $enable_search = get_theme_mod( 'enable_search' );

                            if ( esc_url( $custom_nav_link ) != '' ) {
                                ?>

                                <a href="<?php echo esc_url( $custom_nav_link ); ?>" class="btn header-contact-btn"><?php echo esc_html( $custom_nav_title ); ?></a>

                                <?php
                            }

                            if ( true == esc_attr( $enable_search ) ) {

                                ?>

                                <div class="dropdown header-search">
                                    <a href="#" class="dropdown-toggle d-lg-inline-block d-none" id="search-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont-search-2"></i></a>
                                    <form class="dropdown-menu form-inline" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                                        <h3 class="mb-2 mt-0 d-lg-inline-block d-none"><?php esc_html_e( 'Search', 'bring-back' ); ?></h3>
                                        <div class="input-group position-relative">
                                            <input type="text" class="form-control" name="s" id="s" placeholder="<?php esc_attr_e( 'Search here', 'bring-back' ); ?>" />
                                            <button type="submit" class="btn btn-primary"><i class="icofont-search-2"></i></button>
                                        </div>

                                    </form>
                                </div>
                            <?php } ?>

                        </div>
                        <!-- .navbar-collapse end -->
                    </nav>
                </div>
                <!-- .main-menu end -->
            </div>
        </div><!-- .header-wrapper end --->

        <?php

        /**
         * Hero Banner
         */
        // Header Area
        $hero_area = get_theme_mod( 'enable_hero_area', false );
        // Header Banner/Ads
        $header_banner = get_theme_mod( 'header_ads_show', false );

        if ( is_front_page() && true == esc_attr( $hero_area ) ) :
            // Variable of hero banner
            $hero_title = get_theme_mod( 'hero_title' );
            $hero_content = get_theme_mod( 'hero_content' );
            $hero_btn_text = get_theme_mod( 'hero_btn_text' );
            $hero_btn_url = get_theme_mod( 'hero_btn_url', '#' );
            // Get URL only
            $hero_banner = get_theme_mod( 'hero_banner', get_template_directory_uri() . '/images/hero-banner.png' );
            // Animation
            $enable_hero_banner_animation = get_theme_mod( 'enable_hero_banner_animation', false );
            $enable_hero_shape_animation = get_theme_mod( 'enable_hero_shape_animation', false );
            ?>
            <!-- .hero-area start -->
            <div class="hero-area">
                <div class="container">
                    <div class="row">

                            <!-- <div class="col-lg-7 col-12 offset-lg-5 hero-banner hero-has-banner-animation"> -->
                            <div class="col-lg-7 col-12 offset-lg-5 hero-banner">
                                <img src="https://www.doctaforum.org/2021/simposiosepar1234/wp-content/themes/bring-back/images/caja-inicio.png" class="img-fluid">
                            </div>

                        <div class="col-lg-5 col-12">
                            <div class="hero-content">
                                    
                                    <!-- <h3 style="color: #000000;">cuenta atras</h3> -->
                                    <!-- cuenta atras -->
                                    <?php echo do_shortcode('[waiting name="Downtown"]');?>
                                    <!-- cuenta atras -->


                                    <br>
                                    
                                    <p class="m-0" style="color: #000000;text-align: center;font-size: 15px;">Añade a tu calendario</p>

                                    <!-- calendario -->
                                    <div class="calendario2">
                                        <script type="text/javascript">(function () {
                                                if (window.addtocalendar)if(typeof window.addtocalendar.start == "function")return;
                                                if (window.ifaddtocalendar == undefined) { window.ifaddtocalendar = 1;
                                                    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                                                    s.type = 'text/javascript';s.charset = 'UTF-8';s.async = true;
                                                     // s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://addtocalendar.com/atc/1.5/atc.min.js';
                                                    s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://www.doctaforum.org/2021/simposiosepar1234/wp-content/themes/bring-back/js/atc.min.js';
                                                    var h = d[g]('body')[0];h.appendChild(s); }})();
                                        </script>


                                        <div class="calendarriobarcelona">
                                            <span class="addtocalendar atc-style-blue">
                                                <var class="atc_event">
                                                    <var class="atc_date_start">2021-01-22 12:00:00</var>
                                                    <var class="atc_date_end">2021-01-23 12:00:00</var>
                                                    <var class="atc_timezone">Europe/Madrid</var>
                                                    <var class="atc_title">I Simposio Nacional SEPAR Joven: Un enfoque más allá de la medicina</var>
                                                    <var class="atc_description">I Simposio Nacional SEPAR Joven: Un enfoque más allá de la medicina</var>
                                                    <var class="atc_location">Hotel RIU Plaza de España, Calle Gran Vía, 84, 28013 Madrid</var>
                                                    <var class="atc_organizer_email">simposiosepar@doctaforum.com</var>
                                                </var>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- calendario -->
                                   
                                   <!--  <a href="" class="btn mt-35 centrado">dsfsfsdfsd</a> -->


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- .hero-area end -->
        <?php endif;

        /**
         * Breadcrumbs
         */
        if( class_exists('bring_back_Toolkit') ) {
            echo do_shortcode('[bb_breadcrumbs]' );
        }

        /**
         * Header Banner
         */
        if ( esc_attr( $header_banner ) == true ) : ?>

            <div class="header-banner text-center position-relative bg-white pt-5">

                <?php
                if ( get_header_image() ) :
                    $header_banner_url = get_theme_mod( 'ads_url' );

                    if ( esc_url( $header_banner_url ) != '' ) {
                        ?>
                        <a class="position-absolute" href="<?php echo esc_url( $header_banner_url );  ?>"></a>
                    <?php } ?>
                    <img src="<?php header_image(); ?>"  alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" class="img-fluid">
                <?php endif; ?>

            </div>

        <?php

//        elseif ( get_header_image() && !class_exists('Kirki') ) : {
//            ?>
<!--        <div class="header-banner text-center position-relative bg-white pt-5">-->
<!--            <img src="--><?php //header_image(); ?><!--"  alt="--><?php //echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?><!--" width="--><?php //echo esc_attr( get_custom_header()->width ); ?><!--" height="--><?php //echo esc_attr( get_custom_header()->height ); ?><!--" class="img-fluid">-->
<!--        </div>-->
<!--            --><?php
//        }

        endif;
        ?>

    </header>
    <!-- .header end -->