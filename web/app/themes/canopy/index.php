<?php

/**
 * Template Name: Index
 *
 * Fallback, and the posts page when a static front is set.
 *
 * @package canopy
 * @author  Agência Upgrade <contato@agenciaupgrade.com.br>
 * @link    https://agenciaupgrade.com.br
 */

use Timber\Timber;

$context = Timber::context();
Timber::render('templates/index.twig', $context);
