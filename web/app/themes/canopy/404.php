<?php

/**
 * Template Name: 404 Not Found
 *
 * The template for displaying 404 errors.
 *
 * @package    canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 */

use Timber\Timber;

$context = Timber::context();
Timber::render('templates/404.twig', $context);
