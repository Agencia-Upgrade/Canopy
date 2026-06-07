<?php

/**
 * Configuration overrides for WP_ENV === 'staging'
 *
 * @package    canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 */

use Roots\WPConfig\Config;

use function Env\env;

// Disable loopback cron (mirror production); run a real cron job instead.
Config::define('DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?? true);

/**
 * Staging mirrors production but with indexing disabled.
 *
 * LiteSpeed Cache is optional; its constants apply only on LiteSpeed servers
 * (see config/environments/production.php for the rationale). On nginx/Apache
 * caching is left to the web server and CDN.
 */
if (str_contains($_SERVER['SERVER_SOFTWARE'] ?? '', 'LiteSpeed')) {
    define('LITESPEED_CONF', 1);
    defined('WP_CACHE') || define('WP_CACHE', true);

    define('LITESPEED_CONF__CACHE__ENABLE', 1);
    define('LITESPEED_CONF__CACHE__TTL_PUB', 604800);        // 7 days — public pages
    define('LITESPEED_CONF__CACHE__TTL_FRONTPAGE', 604800);  // 7 days — homepage
    define('LITESPEED_CONF__CACHE__TTL_PRIV', 1800);         // 30 min — logged-in users
    define('LITESPEED_CONF__BROWSER__ENABLE', 1);
    define('LITESPEED_CONF__BROWSER__TTL', 31536000);        // 1 year — fonts, CSS, JS, images
    define('LITESPEED_CONF__PURGE__UPGRADE', 1);
    define('LITESPEED_CONF__DEBUG', 0);
    define('LITESPEED_CONF__CDN__QUIC', 0);
}

// Prevent staging from being indexed by search engines (always, any server)
define('DISALLOW_INDEXING', true);
