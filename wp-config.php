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
// define('DB_NAME', 'elemprende');

// /** Tu nombre de usuario de MySQL */
// define('DB_USER', 'root');

// /** Tu contraseña de MySQL */
// define('DB_PASSWORD', '');

// /** Host de MySQL (es muy probable que no necesites cambiarlo) */
// define('DB_HOST', 'localhost');

// /** Codificación de caracteres para la base de datos. */
// define('DB_CHARSET', 'utf8mb4');

// /** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
// define('DB_COLLATE', '');

// ** MySQL settings - You can get this info from your web host ** //
// $url = parse_url(getenv('DATABASE_URL') ? getenv('DATABASE_URL') : getenv('CLEARDB_DATABASE_URL'));

/** The name of the database for WordPress */
define('DB_NAME', 'elemprende');

/** MySQL database username */
define('DB_USER', 'b6b6a903184873');

/** MySQL database password */
define('DB_PASSWORD', '3d11f4ab');

/** MySQL hostname */
define('DB_HOST', 'devinstance.ccgeehskphbz.us-east-1.rds.amazonaws.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

// ** FTP CONFIGURACIÓN PARA AUTO-FTP ** //
define('FTP_HOST', 'localhost');
define('FTP_USER', 'admin');
define('FTP_PASS', 'HarryPotter929292');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'WQU[rY`d5[p/F`I}wgK&Za{L%az,l=K_9xG9zE@|F/.l|[2lkKo wZ8Td7pbXMM=');
define('SECURE_AUTH_KEY', 'PbN7pzt5ulT/siXAD$OeY8k_gm.hEG$-V{cv:CQ(6A;EkVH9/JRXAwQs|Cu9M5-o');
define('LOGGED_IN_KEY', 'HUd8;}](mAM5#/A*JqNcuH$hbE6JMOt:pk24ZJ[0a}@NwF4`Gu2x:/Kn6:+3^EkA');
define('NONCE_KEY', 'j_0qM 6[J/mG$VHb8La|^EX5#qO<>!_iRfcM!vjk!N:s0Wa_)P`/%UdQF+-Z]2Fs');
define('AUTH_SALT', '0jYXu&fZ[ }(},F0&PL^~N%`]a-f*pdA=D0I}Up4K|8&MhjY}ZCH,=@YP`K5SW{6');
define('SECURE_AUTH_SALT', '3J<OtH(g 01l@x^:OUNxJS0jmgR;B!,^U_2g1Y(J{Uvtd107R)_if$.U8bJ;s,e,');
define('LOGGED_IN_SALT', '-s5s^-:8k[|T;Ygih.hx:vlTq`zQ;{C13Q*+<|eP~DwV^B{9,mpm?&;w(9fm<M4d');
define('NONCE_SALT', 'h;W6WEtm(oQN^;FbcT:nUF0tW;M(&j=CKJC&gr~r1qxDXqzQyW;MzyjaB.:}9t7A');

// define('WP_HOME','https://www.elemprende.com');
// define('WP_SITEURL','https://www.elemprende.com');
// define('AUTH_KEY',         getenv('AUTH_KEY'));
// define('SECURE_AUTH_KEY',  getenv('SECURE_AUTH_KEY'));
// define('LOGGED_IN_KEY',    getenv('LOGGED_IN_KEY'));
// define('NONCE_KEY',        getenv('NONCE_KEY'));
// define('AUTH_SALT',        getenv('AUTH_SALT'));
// define('SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT'));
// define('LOGGED_IN_SALT',   getenv('LOGGED_IN_SALT'));
// define('NONCE_SALT',       getenv('NONCE_SALT'));

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

// define('FORCE_SSL_ADMIN', true);
// in some setups HTTP_X_FORWARDED_PROTO might contain 
// a comma-separated list e.g. http,https
// so check for https existence
// if (strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false)
// 	   $_SERVER['HTTPS']='on';
	   
/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
set_time_limit(120);
