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
 * @package WordPress
 */

// Include the Bedrock autoloader to load dependency classes
require_once __DIR__ . '/../vendor/roots/bedrock-autoloader/autoload.php';

// Use Composer autoload for anything else.
require_once __DIR__ . '/../vendor/autoload.php';

// Now load the configuration from roots/wp-config
require_once __DIR__ . '/../config/application.php';

/* That's all, stop editing! Happy blogging. */
