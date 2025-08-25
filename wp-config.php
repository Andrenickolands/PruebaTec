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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'Fd<I!0FKc!BJ:JC20$&[rN>N^8{$Y1;Dv1^=ZRB!cJF5M/:*j2veLZkc1=Lu Gk?' );
define( 'SECURE_AUTH_KEY',   '6M]}fGc):>|y9~xr|sSNZeTCOWYwUW aT{us)5A&lIbKgSWghS*Mxk.3)z!N_bN$' );
define( 'LOGGED_IN_KEY',     'SUm8w#9(_A=9}WBrJn,xtzw:}7n:NJ@ob?p7@*IeS<GC.6Y[p9C)&cmHYC:mE*><' );
define( 'NONCE_KEY',         '0mtl}iRvW]l6,gCdH6n@SntCGJI4VO^0*!!rj_NCGDzAfQF0Eri$GxfL))WoD0lQ' );
define( 'AUTH_SALT',         '[KS%3aEB/(4KJ{2j1B`iU@I<5g&mH7&?#A)([A}{Ev)UO 1%~[(|bqv^>&5h}MZd' );
define( 'SECURE_AUTH_SALT',  '2q]3w#:2K=K?dmF9Db(D%82$ ojp.QXMLAfV}ad h`>)%tDEvpFRk;X+3=B/b|Yc' );
define( 'LOGGED_IN_SALT',    'SM );3MD.,ss:fDn}#g~ibv:h)9c7Y/Ns(U X[}?t^EHfc1&g R))peJZ]fzhY]T' );
define( 'NONCE_SALT',        '&[moe=+=4A+*#x8CQ?#-=j`-QYbkSfa>-u:i).YX^QIdO!K+WoF74s8Z qOdl{SQ' );
define( 'WP_CACHE_KEY_SALT', ' H;Q2p d+d35r[07yR|1qMN=5c@/.,lJ-X]N:RI9S^&TqB<7PjMW0y e8}ydhs$w' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
