<?php

/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package    WordPress
 */

// Fix for Windows installs
// @link https://github.com/roots/roots/issues/464
if (strpos($_SERVER['REQUEST_URI'], '/wp/') === 0) {
    $_SERVER['REQUEST_URI'] = str_replace('/wp/', '/', $_SERVER['REQUEST_URI']);
}

require dirname(__DIR__) . '/config/application.php';

require ABSPATH . 'wp-blog-header.php';
