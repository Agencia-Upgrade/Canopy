<?php

/**
 * Configuration overrides for WP_ENV === 'staging'
 *
 * @package    canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 */

/**
 * Staging mirrors production but with indexing disabled.
 */
define('LITESPEED_CONF', 1);
defined('WP_CACHE') || define('WP_CACHE', true);

define('LITESPEED_CONF__CACHE__ENABLE', 1);
define('LITESPEED_CONF__CACHE__TTL_PUB', 604800);
define('LITESPEED_CONF__CACHE__TTL_FRONTPAGE', 604800);
define('LITESPEED_CONF__CACHE__TTL_PRIV', 1800);
define('LITESPEED_CONF__BROWSER__ENABLE', 1);
define('LITESPEED_CONF__BROWSER__TTL', 31536000);
define('LITESPEED_CONF__PURGE__UPGRADE', 1);
define('LITESPEED_CONF__DEBUG', 0);
define('LITESPEED_CONF__CDN__QUIC', 0);

// Prevent staging from being indexed by search engines
define('DISALLOW_INDEXING', true);
