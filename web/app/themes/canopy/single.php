<?php

/**
 * Template Name: Single
 *
 * The template for displaying single posts.
 *
 * @package    canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 */

use Timber\Timber;

$context = Timber::context();
Timber::render('templates/single.twig', $context);
