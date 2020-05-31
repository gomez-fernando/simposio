<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'simposiosepar');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'fernando');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'fernando');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '{q#_tUD3`_V{{jzTOd]=<MP`Eqk#:R9X3]}#~2%]),^Z:XVX:NSRaanH^A&@UL32');
define('SECURE_AUTH_KEY', '7cUc),g)9N(|/yWNs,eK8B^M?,O!-TFnw}%_x:Lc;}jsoOy2R]+-2ZkYRmv,qOB*');
define('LOGGED_IN_KEY', 'VbFCb+p-21*en$/Er}vGw9f&HQLH*p2A/dldYRo#QRR:zXPur7p#+|bhb2zLk)nG');
define('NONCE_KEY', 'h?Z)zsGlmA9s{p#/Rwl2}lZA/9IRh(v,!<o$BDF%98%<~G?RAX?G+T&BLJDFs2TN');
define('AUTH_SALT', ']hU- RigB:q9xbg*9UnDgI0dx->Ye}2QMCfhu^Hx=n_<puc[tn-lp8&RW=PB(,WA');
define('SECURE_AUTH_SALT', 'VNIE0^d7R1uMT5~lI5UUqxNLP8Ycttyy[swWjw+fg?GCn0%2`-NeD?Y{8|!ex6JV');
define('LOGGED_IN_SALT', '[{2RBYDtc}jof5KXx>uYJ<ZOWTwNi~CoET>/7r@.dLp]C]8f}Q<jfCkydk$pR+U}');
define('NONCE_SALT', '5BzK7V()QQ+p,:fuw:cs/A}iJ^6taRVH3jW}I.@z0qx7]-yk;Vr}k:jzTC9]H!7*');


/*deshabilita acualizaciones automaticas*/
define( 'AUTOMATIC_UPDATER_DISABLED', true );


/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

