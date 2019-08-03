<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
@ini_set( 'upload_max_filesize' , '300M' );
@ini_set( 'post_max_size', '300M');
@ini_set( 'memory_limit', '300M' );
@ini_set( 'max_execution_time', '300' );
@ini_set( 'max_input_time', '300' );
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'hYoaNAWh/=!ex>KyJ/.0wLZq7gy4qks#Za/Q=#E/6GD%(eNti2eR_!(/O#]b?*[:' );
define( 'SECURE_AUTH_KEY',  '5/]vt_~c)v]b+,]W*[HTTF:Yk=P)hD;`e7h?D0#Oot,TXhDw@RkNlGD158z![ee_' );
define( 'LOGGED_IN_KEY',    'i#_o&>.*>=YbApq}V@I9>WFBT@!wslTbvgFBte)~_u~z%;aO*Xtd|jW54,?]RgrR' );
define( 'NONCE_KEY',        '{<zVkk*}3p2Z)Z^l;%rQJZ.k*b6&~ x,p@eX]PC g?^c]wv93FJ{!NcdErzZ3nem' );
define( 'AUTH_SALT',        'U~CaZkiK[c=dqbp)l|QPzkl=!O*s%4?&XC70^$CRi0tkjZ]VI92@lVndf5oJ IL3' );
define( 'SECURE_AUTH_SALT', '2SB>-08gp{#r!.#@3.HZ@5ZU&>vojmDKD]m5u(4n7T+j>)mdW<NyS>;x5^w/xEjM' );
define( 'LOGGED_IN_SALT',   'jmSgo}4dT:hVW@s115sy(/-FUP]h:Yd06KP5 ^B9VAe:]Z?R|3cI!$JSAB3=1m1k' );
define( 'NONCE_SALT',       '~3i!a=~e]uU* HjLI7TxBrnAMd9g*@fZS#MSm# E~/*/.=Si{G:|w.a!>8-^G0T0' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
