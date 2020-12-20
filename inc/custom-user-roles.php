<?php
/**
 * Register custom user roles examples.
 * https://developer.wordpress.org/reference/functions/add_role/
 *
 * @package  WordPress
 * @subpackage  Boot
 */

/**
 * Register custom roles for front end only user type.
 */
function boot_register_front_end_user() {

	add_role(
		'member',
		'Member',
		[
			'read'    => true,
			'level_1' => true,
		]
	);

}

add_action( 'after_setup_theme', 'boot_register_front_end_user' );

/**
 * Hide the admin bar for front end only user roles.
 */
function boot_hide_admin_bar() {
	if ( ! current_user_can( 'edit_posts' ) && ! is_admin() ) {
		show_admin_bar( false );
	}
}

add_action( 'after_setup_theme', 'boot_hide_admin_bar' );

/**
 * Don't let front end users into wp-admin.
 */
function boot_protect_wp_admin() {
	if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && is_admin() && ! current_user_can( 'edit_posts' ) ) {
		wp_redirect( home_url() );
		exit;
	}
}

add_action( 'admin_init', 'boot_protect_wp_admin' );
