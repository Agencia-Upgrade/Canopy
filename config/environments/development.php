<?php

/**
 * Configuration overrides for WP_ENV === 'development'
 *
 * @package    canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 */

use Roots\WPConfig\Config;

use function Env\env;

Config::define('SAVEQUERIES', true);
Config::define('WP_DEBUG', true);
Config::define('WP_DEBUG_DISPLAY', true);
Config::define('WP_DEBUG_LOG', env('WP_DEBUG_LOG') ?? true);
Config::define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
Config::define('SCRIPT_DEBUG', true);
Config::define('DISALLOW_INDEXING', true);

ini_set('display_errors', '1');

// Enable plugin and theme updates and installation from the admin
Config::define('DISALLOW_FILE_MODS', false);

// Cache OFF in development (Docker/Nginx — LiteSpeed not present)
define('LITESPEED_CONF', 1);
define('LITESPEED_CONF__CACHE__ENABLE', 0);
define('LITESPEED_CONF__BROWSER__ENABLE', 0);
defined('WP_CACHE') || define('WP_CACHE', false);
