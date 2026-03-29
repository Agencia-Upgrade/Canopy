<?php

/**
 * Template Name: Page
 *
 * The template for displaying pages.
 *
 * @package    canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 */

use Timber\Timber;

$context = Timber::context();
Timber::render('templates/page.twig', $context);
