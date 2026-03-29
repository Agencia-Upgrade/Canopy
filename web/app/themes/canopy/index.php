<?php

/**
 * Template Name: Index
 *
 * The main template file. This is used when WordPress can't find a more specific template file.
 *
 * @package    canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 */

use Timber\Timber;

$context = Timber::context();
Timber::render('templates/index.twig', $context);
