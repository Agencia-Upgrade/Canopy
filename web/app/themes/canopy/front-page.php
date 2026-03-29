<?php

/**
 * Template Name: Front Page
 *
 * The template for displaying the front page.
 *
 * @package    canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 */

use Timber\Timber;

$context = Timber::context();
Timber::render('templates/front-page.twig', $context);
