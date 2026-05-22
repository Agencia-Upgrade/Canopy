<?php

/**
 * Configuration overrides for WP_ENV === 'production'
 *
 * @package    canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 */

/**
 * LiteSpeed Cache — file-first configuration
 *
 * Constants override any value saved to the database by the plugin.
 * LITESPEED_CONF must be defined before any LITESPEED_CONF__* constants.
 * Reference: https://docs.litespeedtech.com/lscache/lscwp/constants/
 */
define('LITESPEED_CONF', 1);
defined('WP_CACHE') || define('WP_CACHE', true);

// Page cache
define('LITESPEED_CONF__CACHE__ENABLE', 1);
define('LITESPEED_CONF__CACHE__TTL_PUB', 604800);       // 7 days — public pages
define('LITESPEED_CONF__CACHE__TTL_FRONTPAGE', 604800);  // 7 days — homepage (auto-purge on deploy)
define('LITESPEED_CONF__CACHE__TTL_PRIV', 1800);         // 30 min — logged-in users

// Browser cache — resolves low TTL for fonts and static assets
define('LITESPEED_CONF__BROWSER__ENABLE', 1);
define('LITESPEED_CONF__BROWSER__TTL', 31536000); // 1 year — fonts, CSS, JS, images

// Auto-purge when WordPress updates content or plugins
define('LITESPEED_CONF__PURGE__UPGRADE', 1);

// Debug OFF in production
define('LITESPEED_CONF__DEBUG', 0);

// Suppress QUIC.cloud suggestion in admin (CDN handled by Cloudflare)
define('LITESPEED_CONF__CDN__QUIC', 0);
