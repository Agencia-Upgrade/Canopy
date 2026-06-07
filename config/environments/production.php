<?php

/**
 * Configuration overrides for WP_ENV === 'production'
 *
 * @package    canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 */

use Roots\WPConfig\Config;

use function Env\env;

/**
 * Disable WP's loopback cron in production: it would otherwise fire on visitor
 * requests, adding latency under load. Schedule a real cron instead, e.g.
 *   * /5 * * * *  cd /path/to/site && wp cron event run --due-now --quiet
 * Override per host with DISABLE_WP_CRON in .env.
 */
Config::define('DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?? true);

/**
 * LiteSpeed Cache — file-first configuration, applied only on LiteSpeed servers
 *
 * The litespeed-cache plugin is optional (see composer.json `suggest`). Its
 * page cache only works when the host runs LiteSpeed/OpenLiteSpeed, so the
 * constants below are defined only when SERVER_SOFTWARE reports LiteSpeed.
 * On nginx/Apache, page and browser caching are left to the web server and
 * the CDN (Cloudflare). When defined, these constants override any value the
 * plugin saved to the database.
 *
 * Reference: https://docs.litespeedtech.com/lscache/lscwp/constants/
 */
if (str_contains($_SERVER['SERVER_SOFTWARE'] ?? '', 'LiteSpeed')) {
    // LITESPEED_CONF enables file-based config; it must come before any LITESPEED_CONF__* key.
    define('LITESPEED_CONF', 1);
    defined('WP_CACHE') || define('WP_CACHE', true);

    // Page cache
    define('LITESPEED_CONF__CACHE__ENABLE', 1);
    define('LITESPEED_CONF__CACHE__TTL_PUB', 604800);        // 7 days — public pages
    define('LITESPEED_CONF__CACHE__TTL_FRONTPAGE', 604800);  // 7 days — homepage (auto-purge on deploy)
    define('LITESPEED_CONF__CACHE__TTL_PRIV', 1800);         // 30 min — logged-in users

    // Browser cache — resolves low TTL for fonts and static assets
    define('LITESPEED_CONF__BROWSER__ENABLE', 1);
    define('LITESPEED_CONF__BROWSER__TTL', 31536000); // 1 year — fonts, CSS, JS, images

    // Auto-purge when WordPress updates content or plugins
    define('LITESPEED_CONF__PURGE__UPGRADE', 1);

    // Debug OFF in production
    define('LITESPEED_CONF__DEBUG', 0);

    // Suppress the QUIC.cloud suggestion in admin — the CDN is Cloudflare.
    define('LITESPEED_CONF__CDN__QUIC', 0);
}
