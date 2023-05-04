<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'Drizzle' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         't!6YXy=HL;(|MS67oonagy9N#$o[yXF(C8_OehTZ+k,*2kb`qK5<R0!/B9vZmg8C' );
define( 'SECURE_AUTH_KEY',  '/*W`E@*,uU[]hVbT!>%bN~r_xYFx{d@#11JQo~_x$w0`/W0Q9!BCD9dFUW#y6O>U' );
define( 'LOGGED_IN_KEY',    ':7(@W/TI-QterxdAs*HF6:3-i]xl*6m0ADkmz@dDus$=B2,{7(bbSb!M~/4ilEnR' );
define( 'NONCE_KEY',        'o/Yc[/4uOz9L-)&(nSTfb&CTQk^I[+x+IKn>1L*$Af?Euk78UC&C!IhR_=`11<By' );
define( 'AUTH_SALT',        '{b@}^KReAY^2K.TDuI~2-sh]{To# :Z~,^%SJH{XYf4i=17ECBYj(IJxvp]y|Bu$' );
define( 'SECURE_AUTH_SALT', 'Uy1Y.XUr5Yc->ed/9~9z|Udz_-stm$e:L?THX3(/O!EmnRuXXl0CU_e-;%~RmP}/' );
define( 'LOGGED_IN_SALT',   '9+Ej3J$-o@_o0~_kgG8u={7.(N2#cjV?vl6[JHbwhf<b&4)NAG:tPl9t6t2IL]D^' );
define( 'NONCE_SALT',       '({,(81Tga8:.WGd=;zDY440dSyaltP{ttOW61G%h{JCKS(L6!5C}6Ik# 3ikj|@Y' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
