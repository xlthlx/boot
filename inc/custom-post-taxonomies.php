<?php
/**
 * Register custom post types, taxonomies, and related functions.
 * https://github.com/johnbillion/extended-cpts
 *
 * @package  WordPress
 * @subpackage  Boot
 */

/**
 * Register post types and taxonomies example.
 */
add_action( 'init', function () {

	register_extended_post_type(
		'portfolio',
		[
			'has_archive'      => false,
			'capability_type'  => 'post',
			'menu_icon'        => 'dashicons-portfolio',
			'supports'         => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author' ],
			'delete_with_user' => false,
			'admin_cols'       => [
				'logo'    => [
					'title'          => 'Logo',
					'featured_image' => 'thumbnail',
					'width'          => 50,
					'height'         => 50,
				],
				'company' => [
					'title'    => 'Company',
					'taxonomy' => 'company',
				],
				'date'    => [
					'title'      => 'Publish date',
					'post_field' => 'post_date',
					'default'    => 'DESC',
				],
			],
			'admin_filters'    => [
				'company' => [
					'title'    => 'Company',
					'taxonomy' => 'company',
				],
			],
		],
		[
			'singular' => 'Portfolio',
			'plural'   => 'Portfolio',
			'slug'     => 'portfolio'
		]
	);

	register_extended_taxonomy(
		'company',
		[
			'portfolio',
		],
		[
			'meta_box'     => 'radio',
			'hierarchical' => false,
		],
		[
			'singular' => 'Company',
			'plural'   => 'Companies',
			'slug'     => 'company',
		]
	);

} );

/**
 * Re-label Posts as Blogs in wp-admin.
 */
function boot_change_post_labels() {
	$get_post_type              = get_post_type_object( 'post' );
	$labels                     = $get_post_type->labels;
	$labels->name               = 'Blogs';
	$labels->singular_name      = 'Blog';
	$labels->add_new            = 'Add Blog';
	$labels->add_new_item       = 'Add Blogs';
	$labels->edit_item          = 'Edit Blog';
	$labels->new_item           = 'Blog';
	$labels->view_item          = 'View Blog';
	$labels->search_items       = 'Search Blogs';
	$labels->not_found          = 'No Blogs found';
	$labels->not_found_in_trash = 'No Blogs found in Trash';
	$labels->all_items          = 'All Blogs';
	$labels->menu_name          = 'Blogs';
	$labels->name_admin_bar     = 'Blogs';
}

add_action( 'init', 'boot_change_post_labels' );

/**
 * Re-label Tags to Topics in wp-admin.
 */
function boot_label_tags_topics() {
	global $wp_taxonomies;
	$labels                             = &$wp_taxonomies['post_tag']->labels;
	$labels->name                       = 'Topics';
	$labels->singular_name              = 'Topics';
	$labels->search_items               = 'Search Topics';
	$labels->all_items                  = 'Topics';
	$labels->separate_items_with_commas = 'Separate Topics with commas';
	$labels->choose_from_most_used      = 'Choose from the most used Topics';
	$labels->popular_items              = 'Popular Topics';
	$labels->edit_item                  = 'Edit Topic';
	$labels->view_item                  = 'View Topic';
	$labels->update_item                = 'Update Topic';
	$labels->add_new_item               = 'Add new Topic';
	$labels->new_item_name              = 'New Topic';
	$labels->add_or_remove_items        = 'Add or remove Topic';
	$labels->not_found                  = 'Topic not found';
	$labels->no_terms                   = 'No Topics';
	$labels->items_list_navigation      = 'Topics list navigation';
	$labels->items_list                 = 'List of Topics';
	$labels->back_to_items              = '← Back to Topics';
	$labels->menu_name                  = 'Topics';
}

add_action( 'init', 'boot_label_tags_topics' );
