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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wardrobe' );

/** Database username */
define( 'DB_USER', 'wardrobe' );

/** Database password */
define( 'DB_PASSWORD', 'wardrobe123@' );

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
define( 'AUTH_KEY',         '45!K|*e?|Y >%I+7L}ESm_*B&^J?*{Asy3hrT<PN%]IdZZ5(.3pQ$DyCHTu$dQ;<' );
define( 'SECURE_AUTH_KEY',  'pM965yYr~gc3k{^.{w%&c1oV@vv{jI;KiQrlDW1_dD{@Z*@lR[P#g?Jlbkb}-SF:' );
define( 'LOGGED_IN_KEY',    '9PN(DMtL7`zOI)&~x^))>,P*hs{<dTTx($|:+;7W<m!(jv9i+s~ADmA<DJW<x+M,' );
define( 'NONCE_KEY',        ';?/hcVjjH;bEWW!p!zW#&L,t7u8xg=X $@QMM>ty,R4LRT%)4rk_CC}lryt(]ka7' );
define( 'AUTH_SALT',        '?cDnnyuKy.WSI%2}ACs;nl.nrDPQmYt54<qPoJ?Py`Eo%J[t_TU$#YUWl6yXblY[' );
define( 'SECURE_AUTH_SALT', 'snWxI(vi r:!?|ZGF(u@6(..[27Y:4fo;/YErF>`[gK`PR[bmM@ooixXV6vYK],}' );
define( 'LOGGED_IN_SALT',   '#zx(SjA-EN-5<q!O^XcsS]KItvAg0rC*f/UXU=Re*6`1<:N>OLq]bE4ss`Gl(*)-' );
define( 'NONCE_SALT',       'zd5}V.<1Y]V<JXS.%:A*^]lSI@XD9atL%QU]Y9=.#^qg1y c%)!dSAgEwVH.U]~+' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
