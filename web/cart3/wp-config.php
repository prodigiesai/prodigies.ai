<?php
define('WP_CACHE', true); // Added by SpeedyCache

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
define( 'DB_NAME', 'vivhjgte_wp663' );

/** Database username */
define( 'DB_USER', 'vivhjgte_wp663' );

/** Database password */
define( 'DB_PASSWORD', '5CpSY0(!m5' );

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
define( 'AUTH_KEY',         'eend9x1uolbqapycctr6n2qb6z6yxgvq710lhkvbmntpswk0aoy2n9ldb5brwtql' );
define( 'SECURE_AUTH_KEY',  '5joux5vygwniwjm4brni9ba1kt9gtvcqfngpa9hsnvqim0romcwy9yixcx9qiazm' );
define( 'LOGGED_IN_KEY',    'lnqn53mtsywpxm5xl8ykxildth14wtbx6pojvvbcb52x83efewhlfxzbjsul0hbx' );
define( 'NONCE_KEY',        'boflpfjo5gw71es5jvg8yh4jycqv0ps8qulpvzrn73vbczroccldxwixrkyztbgx' );
define( 'AUTH_SALT',        'fsv5hhvrxgdz0vaqyl13rjjo0zgmr7v4sea3qlqoo9ttoufmrg1dgubjit9i8la0' );
define( 'SECURE_AUTH_SALT', '86d49kfxcokdzo0lnbmvzxpwkyxcb0pizfes6veciilp6sepeqzhcmhfs1qfagg3' );
define( 'LOGGED_IN_SALT',   'dosqto84pslqrqhamvojyz5hlubbueimiizkbxbbuefs2cnbopssg4uuesyf5qv5' );
define( 'NONCE_SALT',       'pafzvultlenqpgx3n7khg4h5atyb0lplrfney4prryvubagwt2tnahqsqsndbvwu' );

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
$table_prefix = 'wp89_';

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
