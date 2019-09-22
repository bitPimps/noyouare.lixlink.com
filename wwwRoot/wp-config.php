<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'WPCACHEHOME', '/home/lixlink/noyouare.lixlink.com/wp-content/plugins/wp-super-cache/' );
define('WP_CACHE', true); //Added by WP-Cache Manager
define('DB_NAME', 'ellisfamdb');

/** MySQL database username */
define('DB_USER', '3116amdb');

/** MySQL database password */
define('DB_PASSWORD', 'f1sht1nk');

/** MySQL hostname */
define('DB_HOST', 'ellisfamdb.lixlink.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'WGzsb@`{+x}^<[.oKoWy2+w5e+[yh/+]6_YI<=zebUAb&-)h}_bRvZrG|L#Kkzue');
define('SECURE_AUTH_KEY',  '-mW3>9$9UF5qa@d(ofI|/aX7&!y|RIjnOuv-lha90Qb8{UDI3^Lx,V>n]t8ytl*7');
define('LOGGED_IN_KEY',    'Ibbi_Pa[} <2[/]RQ`pvtOQ0O(3TgKt[Vl]lZSR]X?ki[(py+|@&~{e{&*N6m7Bd');
define('NONCE_KEY',        'Q)3lpUstd#^e703z4h7!VEYe=jMkM%iD@-}8Pa7~X?YHg7z.?]{{@vSJ.3n:j~<7');
define('AUTH_SALT',        'ySX0 sT-5Z+vU<1*4GZF[|h^pC-(>%V<q1{b*Lp}M$kFk?|=&E2U+?wbkh&F>BFp');
define('SECURE_AUTH_SALT', 'kTmjA=Y_f1z#Yv[1!e:lFowZSZx-Fqm |p3jub&a] c|t1MK+L|d=BY&)!%wiE1U');
define('LOGGED_IN_SALT',   '8](x{TN.JR8p|d]1>VMiXn_OsMn=N0i>rqyF}`q3heUZ,#-wiW>%H4u1}``H8>gZ');
define('NONCE_SALT',       'hIu}vu`B0uHf0uH6Li3&tO t^b?F;l{}21wtfy<<|sLBpfG.O(*#.7,=/+PiaQ%e');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
