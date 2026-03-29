<?php

/**
 * Template Name: Search
 *
 * The template for displaying search results.
 *
 * @package    canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 */

use Timber\Timber;

$context = Timber::context();
Timber::render('templates/search.twig', $context);
