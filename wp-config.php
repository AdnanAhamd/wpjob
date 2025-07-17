<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wpjob' );

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
define( 'AUTH_KEY',         'vK6#Jo2n-A@H_cUq7ESb/kePtI#DHh<~!g_(#H]nM[d^Z~H_ox3z5~=>S?/*++=u' );
define( 'SECURE_AUTH_KEY',  '^FfaInsw&lZ!IlBBnxiOvaEJ!}ul_!1a|pEwf`t;|W^!VJCHeLf5J1l&5Ouq{JxV' );
define( 'LOGGED_IN_KEY',    'IQxvW8bW(e@m1ln~]`%au5V9o=V54U8`&@]G#PVa!2);};M9BQu+K82YpOVU{ee+' );
define( 'NONCE_KEY',        '8[cgUi<biIPxI?S#VcFX#KRnL<[N~v6bDQ&F!?~.vgc*vtj9}0mH/&c~Q1tdmFnP' );
define( 'AUTH_SALT',        'N=`G-r4BUs/(/m^8Cx$M,KJhkJXIT5bNy^R6B}cu On0DLNr=aoHGW$;BaIJ18vq' );
define( 'SECURE_AUTH_SALT', '3@q;rW4GA,?}=Z[pd.r8U_T7oTzW9hs*q@g`F]m*d>1(]lI@9qjp<|lwFZd65>N|' );
define( 'LOGGED_IN_SALT',   'U s[/YCa+bEhSt(Ei$QY^PL63lF|o:K4qCxd%s8}7K$a1|8UbJb!R5TZt>mGkkc;' );
define( 'NONCE_SALT',       'Wcc;BSRH0,8])?``&.%w&)(g&jbxiWU~&{ovHZ$kRz,4XBN#Y<,;y$&Ey(r3,wB)' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
