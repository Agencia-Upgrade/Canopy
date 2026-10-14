<?php

/**
 * Template Name: Front Page
 *
 * Unsets `post` when Reading is “latest posts” so the first loop item
 * is not treated as front-page body copy. Renders the posts feed
 * (`index.twig`) in that mode; static front page keeps its hero template.
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

if (get_option('show_on_front') === 'posts') {
    Timber::render('templates/index.twig', $context);

    return;
}

Timber::render('templates/front-page.twig', $context);
