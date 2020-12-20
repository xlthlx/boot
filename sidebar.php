<?php
/**
 * The Template for the sidebar containing the main widget area.
 *
 * @package  WordPress
 * @subpackage  Boot
 */

$context = array();
$context['dynamic_sidebar'] = Timber::get_widgets('dynamic_sidebar');
Timber::render('sidebar.twig', $context);
