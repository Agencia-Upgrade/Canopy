<?php

/**
 * Template Name: Archive
 *
 * @package canopy
 * @author  Agência Upgrade <contato@agenciaupgrade.com.br>
 * @link    https://agenciaupgrade.com.br
 */

use Timber\Timber;

$context = Timber::context();
Timber::render('templates/archive.twig', $context);
