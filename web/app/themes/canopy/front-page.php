<?php

/**
 * Template Name: Front Page
 *
 * Unsets `post` when Reading is “latest posts” so the first loop item
 * is not treated as front-page body copy.
 *
 * @package canopy
 * @author  Agência Upgrade <contato@agenciaupgrade.com.br>
 * @link    https://agenciaupgrade.com.br
 */

use Timber\Timber;

$context = Timber::context();

if (!is_singular('page')) {
    unset($context['post']);
}

Timber::render('templates/front-page.twig', $context);
