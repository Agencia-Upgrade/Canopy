<?php

/**
 * Template Name: Archive
 *
 * The template for displaying archive pages.
 *
 * @package    canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 */

use Timber\Timber;

$context = Timber::context();
Timber::render('templates/archive.twig', $context);
