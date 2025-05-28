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
define( 'DB_NAME', 'vivhjgte_wp126' );

/** Database username */
define( 'DB_USER', 'vivhjgte_wp126' );

/** Database password */
define( 'DB_PASSWORD', '8)J.oS8pY6' );

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
define( 'AUTH_KEY',         '51owt2awpdoq3jrekzkwwhq6gjft10dwjnwt6agpr3nt1rdisrorbbe7pctkijcf' );
define( 'SECURE_AUTH_KEY',  'nikoqp8qhelcaqysubocyh93favnpr1sow796sh2zavxeqdzlbyl8ntrxl6hncne' );
define( 'LOGGED_IN_KEY',    'esaw5tpx8cfesiq2buo1awcokh50se4e3uz08prs8e0zesikealgnw6fc43ol8c2' );
define( 'NONCE_KEY',        'nmkq2g2fasrnmhhdxjtuvqrf5rjr8k7mkazcci2gzvdxdwce7e8ky9993xeltdhl' );
define( 'AUTH_SALT',        'q1scm5x3btfebu1pig5wg9vyi0rsa69b4ufwre11e1sxcbcznbbuwf13ykyrbfac' );
define( 'SECURE_AUTH_SALT', 'rog7oa4wzw75yanv8qlsdte4sqm6lzmnneam1ulcowbduc5hfs3ggrjplguhe9kl' );
define( 'LOGGED_IN_SALT',   'kgomonoc4gesqtyghp08yjpgvwq33qutxyktekrjfgjeq8ou6slqk5bmg6o3fa3i' );
define( 'NONCE_SALT',       'xnwqud2jge7zd1rhndsqiomg9q4wkvcagknvtwnaoxhihwosyo3onofvdmujkopw' );

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
$table_prefix = 'wpmd_';

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
