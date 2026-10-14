<?php

/**
 * Template Name: Search
 *
 * @package canopy
 * @author  Agência Upgrade <contato@agenciaupgrade.com.br>
 * @link    https://agenciaupgrade.com.br
 */

use Timber\Timber;

global $wp_query;

$context = Timber::context();
$context['found_posts'] = $wp_query instanceof \WP_Query ? (int) $wp_query->found_posts : 0;
Timber::render('templates/search.twig', $context);
