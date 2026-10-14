<?php

/**
 * Template Name: 404 Not Found
 *
 * @package canopy
 * @author  Agência Upgrade <contato@agenciaupgrade.com.br>
 * @link    https://agenciaupgrade.com.br
 */

use Timber\Timber;

$context = Timber::context();
Timber::render('templates/404.twig', $context);
