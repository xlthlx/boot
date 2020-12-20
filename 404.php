<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package  WordPress
 * @subpackage  Boot
 */

$context = Timber::context();
Timber::render( '404.twig', $context );
