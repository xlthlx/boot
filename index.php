<?php
/**
 * The main template file.
 *
 * @package  WordPress
 * @subpackage  Boot
 */

$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();
$templates        = array( 'index.twig' );

Timber::render( $templates, $context );
