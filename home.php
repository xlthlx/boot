<?php
/**
 * Homepage template.
 *
 * @package  WordPress
 * @subpackage  Boot
 */

$context = Timber::context();

global $paged;
if ( ! isset( $paged ) || ! $paged ) {
	$paged = 1;
}

$sticky = get_option( 'sticky_posts' );
$first  = array_slice( $sticky, 0, 1 );
$second = array_slice( $sticky, 1, 2 );

$offset = array();

if ( count( $first ) === 1 ) {
	$context['first_posts'] = new Timber\PostQuery( array( 'post__in' => $first, 'ignore_sticky_posts' => 1 ) );
	$offset                 = $first;
} else {
	$context['first_posts'] = new Timber\PostQuery( array( 'posts_per_page' => 1 ) );
	$offset[0]              = $context['first_posts'][0]->ID;
}

if ( count( $second ) === 2 ) {
	$context['second_posts'] = new Timber\PostQuery( array( 'post__in' => $second, 'ignore_sticky_posts' => 1 ) );
	$offset                  = $second;
} else {
	$context['second_posts'] = new Timber\PostQuery( array(
		'posts_per_page' => 2,
		'offset'         => 1,
		'post__not_in'   => $offset,
	) );
	$offset[1]               = $context['second_posts'][0]->ID;
	$offset[2]               = $context['second_posts'][1]->ID;
}

if ( ( count( $first ) === 1 ) && ( count( $second ) === 2 ) ) {
	$offset = array_slice( $sticky, 0, 3 );
}

$context['posts'] = new Timber\PostQuery( array( 'post__not_in' => $offset, 'paged' => $paged ) );

$context['category'] = Timber::get_terms( [
	'taxonomy'   => 'category',
	'hide_empty' => 1,
] );

Timber::render( array( 'home.twig' ), $context );
