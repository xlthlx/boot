<?php
/**
 * Register custom meta fields.
 * Based on CMB2: see vendor/cmb2/cmb2/example-functions.php
 *
 * @package  WordPress
 * @subpackage  Boot
 */

/**
 * Manually render a field.
 *
 * @param array $field_args Array of field arguments.
 * @param CMB2_Field $field The field object.
 */
function boot_render_row_cb( $field_args, $field ) {
	$classes     = $field->row_classes();
	$id          = $field->args( 'id' );
	$label       = $field->args( 'name' );
	$name        = $field->args( '_name' );
	$value       = $field->escaped_value();
	$description = $field->args( 'description' );
	?>
	<div class="custom-field-row <?php echo esc_attr( $classes ); ?>">
		<p><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label></p>
		<p><input id="<?php echo esc_attr( $id ); ?>" type="text" name="<?php echo esc_attr( $name ); ?>"
				  value="<?php echo $value; ?>"/></p>
		<p class="description"><?php echo esc_html( $description ); ?></p>
	</div>
	<?php
}

/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function boot_register_demo_metabox() {
	$cmb_demo = new_cmb2_box( array(
			'id'           => 'boot_demo_metabox',
			'title'        => esc_html__( 'Test Custom fields', 'cmb2' ),
			'object_types' => array( 'page' ), // Post type
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true, // Show field names on the left
			'cmb_styles'   => false, // false to disable the CMB stylesheet
			'closed'       => true, // true to keep the metabox closed by default
			'classes'      => 'extra-class', // Extra cmb2-wrap classes
	) );

	$cmb_demo->add_field( array(
			'name'       => esc_html__( 'Text', 'cmb2' ),
			'desc'       => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'         => 'boot_demo_text',
			'type'       => 'text',
			'on_front'   => false, // Optionally designate a field to wp-admin only
			'repeatable' => true,
			'column'     => true, // Display field value in the admin post-listing columns
	) );

	$cmb_demo->add_field( array(
			'name'   => esc_html__( 'Test Text Small', 'cmb2' ),
			'desc'   => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'     => 'boot_demo_textsmall',
			'type'   => 'text_small',
			'column' => array(
					'name'     => esc_html__( 'Column Title', 'cmb2' ), // Set the admin column title
					'position' => 2, // Set as the second column.
			),
	) );

	$cmb_demo->add_field( array(
			'name' => esc_html__( 'Text Medium', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_demo_textmedium',
			'type' => 'text_medium',
	) );

	$cmb_demo->add_field( array(
			'name'       => esc_html__( 'Read-only Disabled Field', 'cmb2' ),
			'desc'       => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'         => 'boot_demo_readonly',
			'type'       => 'text_medium',
			'default'    => esc_attr__( 'Hey there, I\'m a read-only field', 'cmb2' ),
			'save_field' => false, // Disables the saving of this field.
			'attributes' => array(
					'disabled' => 'disabled',
					'readonly' => 'readonly',
			),
	) );

	$cmb_demo->add_field( array(
			'name'          => esc_html__( 'Custom Rendered Field', 'cmb2' ),
			'desc'          => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'            => 'boot_demo_render_row_cb',
			'type'          => 'text',
			'render_row_cb' => 'boot_render_row_cb',
	) );

	$cmb_demo->add_field( array(
			'name'      => esc_html__( 'Website URL', 'cmb2' ),
			'desc'      => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'        => 'boot_demo_url',
			'type'      => 'text_url',
			'protocols' => array(
					'http',
					'https',
					'ftp',
					'ftps',
					'mailto',
					'news',
					'irc',
					'gopher',
					'nntp',
					'feed',
					'telnet'
			), // Array of allowed protocols
	) );

	$cmb_demo->add_field( array(
			'name' => esc_html__( 'Text Email', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_demo_email',
			'type' => 'text_email',
	) );

	$cmb_demo->add_field( array(
			'name'        => esc_html__( 'Time', 'cmb2' ),
			'desc'        => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'          => 'boot_demo_time',
			'type'        => 'text_time',
			'time_format' => 'H:i', // Set to 24hr format
	) );

	$cmb_demo->add_field( array(
			'name' => esc_html__( 'Time zone', 'cmb2' ),
			'desc' => esc_html__( 'Time zone', 'cmb2' ),
			'id'   => 'boot_demo_timezone',
			'type' => 'select_timezone',
	) );

	$cmb_demo->add_field( array(
			'name'        => esc_html__( 'Date Picker', 'cmb2' ),
			'desc'        => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'          => 'boot_demo_textdate',
			'type'        => 'text_date',
			'date_format' => 'd/m/Y',
	) );

	$cmb_demo->add_field( array(
			'name'              => esc_html__( 'Date Picker (UNIX timestamp)', 'cmb2' ),
			'desc'              => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'                => 'boot_demo_textdate_timestamp',
			'type'              => 'text_date_timestamp',
			'timezone_meta_key' => 'boot_demo_timezone',
		// Optionally make this field honor the timezone selected in the select_timezone specified above
	) );

	$cmb_demo->add_field( array(
			'name' => esc_html__( 'Date/Time Picker Combo (UNIX timestamp)', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_demo_datetime_timestamp',
			'type' => 'text_datetime_timestamp',
	) );


	$cmb_demo->add_field( array(
			'name' => esc_html__( 'Date/Time Picker/Time zone Combo (serialized DateTime object)', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_demo_datetime_timestamp_timezone',
			'type' => 'text_datetime_timestamp_timezone',
	) );

	$cmb_demo->add_field( array(
			'name'         => esc_html__( 'Money', 'cmb2' ),
			'desc'         => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'           => 'boot_demo_textmoney',
			'type'         => 'text_money',
			'before_field' => '£', // override '$' symbol if needed
			'repeatable'   => true,
	) );

	$cmb_demo->add_field( array(
			'name'       => esc_html__( 'Color Picker', 'cmb2' ),
			'desc'       => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'         => 'boot_demo_colorpicker',
			'type'       => 'colorpicker',
			'default'    => '#ffffff',
			'options'    => array(
					'alpha' => true, // Make this a rgba color picker.
			),
			'attributes' => array(
					'data-colorpicker' => json_encode( array(
							'palettes' => array( '#3dd0cc', '#ff834c', '#4fa2c0', '#0bc991', ),
					) ),
			),
	) );

	$cmb_demo->add_field( array(
			'name' => esc_html__( 'Text Area', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_demo_textarea',
			'type' => 'textarea',
	) );

	$cmb_demo->add_field( array(
			'name' => esc_html__( 'Text Area Small', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_demo_textareasmall',
			'type' => 'textarea_small',
	) );

	$cmb_demo->add_field( array(
			'name'       => esc_html__( 'Text Area for Code', 'cmb2' ),
			'desc'       => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'         => 'boot_demo_textarea_code',
			'type'       => 'textarea_code',
			'attributes' => array(
					'data-codeeditor' => json_encode( array(
							'codemirror' => array(
									'lineNumbers' => false,
									'mode'        => 'css',
							),
					) ),
			),
	) );

	$cmb_demo->add_field( array(
			'name'             => esc_html__( 'Select', 'cmb2' ),
			'desc'             => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'               => 'boot_demo_select',
			'type'             => 'select',
			'show_option_none' => true,
			'options'          => array(
					'standard' => esc_html__( 'Option One', 'cmb2' ),
					'custom'   => esc_html__( 'Option Two', 'cmb2' ),
					'none'     => esc_html__( 'Option Three', 'cmb2' ),
			),
	) );

	$cmb_demo->add_field( array(
			'name'             => esc_html__( 'Radio inline', 'cmb2' ),
			'desc'             => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'               => 'boot_demo_radio_inline',
			'type'             => 'radio_inline',
			'show_option_none' => 'No Selection',
			'options'          => array(
					'standard' => esc_html__( 'Option One', 'cmb2' ),
					'custom'   => esc_html__( 'Option Two', 'cmb2' ),
					'none'     => esc_html__( 'Option Three', 'cmb2' ),
			),
	) );

	$cmb_demo->add_field( array(
			'name'    => esc_html__( 'Radio', 'cmb2' ),
			'desc'    => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'      => 'boot_demo_radio',
			'type'    => 'radio',
			'options' => array(
					'option1' => esc_html__( 'Option One', 'cmb2' ),
					'option2' => esc_html__( 'Option Two', 'cmb2' ),
					'option3' => esc_html__( 'Option Three', 'cmb2' ),
			),
	) );

	$cmb_demo->add_field( array(
			'name'       => esc_html__( 'Taxonomy Radio', 'cmb2' ),
			'desc'       => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'         => 'boot_demo_text_taxonomy_radio',
			'type'       => 'taxonomy_radio', // Or `taxonomy_radio_inline`/`taxonomy_radio_hierarchical`
			'taxonomy'   => 'category', // Taxonomy Slug
			'inline'     => true, // Toggles display to inline
			'query_args' => array(
					'orderby'    => 'slug',
					'hide_empty' => true,
			),
	) );

	$cmb_demo->add_field( array(
			'name'     => esc_html__( 'Taxonomy Select', 'cmb2' ),
			'desc'     => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'       => 'boot_demo_taxonomy_select',
			'type'     => 'taxonomy_select', // Or `taxonomy_select_hierarchical`
			'taxonomy' => 'category', // Taxonomy Slug
	) );

	$cmb_demo->add_field( array(
			'name'     => esc_html__( 'Taxonomy Multi Checkbox', 'cmb2' ),
			'desc'     => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'       => 'boot_demo_multitaxonomy',
			'type'     => 'taxonomy_multicheck', // Or `taxonomy_multicheck_inline`/`taxonomy_multicheck_hierarchical`
			'taxonomy' => 'post_tag', // Taxonomy Slug
			'inline'   => true, // Toggles display to inline
	) );

	$cmb_demo->add_field( array(
			'name' => esc_html__( 'Checkbox', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_demo_checkbox',
			'type' => 'checkbox',
	) );

	$cmb_demo->add_field( array(
			'name'     => esc_html__( 'Multi Checkbox', 'cmb2' ),
			'desc'     => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'       => 'boot_demo_multicheckbox',
			'type'     => 'multicheck',
			'multiple' => true, // Store values in individual rows
			'options'  => array(
					'check1' => esc_html__( 'Check One', 'cmb2' ),
					'check2' => esc_html__( 'Check Two', 'cmb2' ),
					'check3' => esc_html__( 'Check Three', 'cmb2' ),
			),
			'inline'   => true, // Toggles display to inline
	) );

	$cmb_demo->add_field( array(
			'name'    => esc_html__( 'Wysiwyg', 'cmb2' ),
			'desc'    => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'      => 'boot_demo_wysiwyg',
			'type'    => 'wysiwyg',
			'options' => array(
					'textarea_rows' => 5,
			),
	) );

	$cmb_demo->add_field( array(
			'name' => esc_html__( 'Image', 'cmb2' ),
			'desc' => esc_html__( 'Upload an image or enter a URL.', 'cmb2' ),
			'id'   => 'boot_demo_image',
			'type' => 'file',
	) );

	$cmb_demo->add_field( array(
			'name'         => esc_html__( 'Multiple Files', 'cmb2' ),
			'desc'         => esc_html__( 'Upload or add multiple images/attachments.', 'cmb2' ),
			'id'           => 'boot_demo_file_list',
			'type'         => 'file_list',
			'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
	) );

	$cmb_demo->add_field( array(
			'name' => esc_html__( 'oEmbed', 'cmb2' ),
			'desc' => sprintf(
			/* translators: %s: link to codex.wordpress.org/Embeds */
					esc_html__( 'Enter a youtube, twitter, or instagram URL. Supports services listed at %s.', 'cmb2' ),
					'<a href="https://wordpress.org/support/article/embeds/">codex.wordpress.org/Embeds</a>'
			),
			'id'   => 'boot_demo_embed',
			'type' => 'oembed',
	) );

	$cmb_demo->add_field( array(
			'name'         => 'Field Parameters',
			'id'           => 'boot_demo_parameters',
			'type'         => 'text',
			'before_row'   => '<p>Testing <b>"before_row"</b> parameter</p>',
			'before'       => '<p>Testing <b>"before"</b> parameter</p>',
			'before_field' => '<p>Testing <b>"before_field"</b> parameter</p>',
			'after_field'  => '<p>Testing <b>"after_field"</b> parameter</p>',
			'after'        => '<p>Testing <b>"after"</b> parameter</p>',
			'after_row'    => '<p>Testing <b>"after_row"</b> parameter</p>',
	) );

}

add_action( 'cmb2_admin_init', 'boot_register_demo_metabox' );

/**
 * Hook in and add a metabox to demonstrate repeatable grouped fields.
 */
function boot_register_repeatable_group_field_metabox() {
	$cmb_group = new_cmb2_box( array(
			'id'           => 'boot_group_metabox',
			'title'        => esc_html__( 'Repeating Field Group', 'cmb2' ),
			'object_types' => array( 'post' ),
	) );

	// $group_field_id is the field id string, so in this case: 'boot_group_demo'
	$group_field_id = $cmb_group->add_field( array(
			'id'          => 'boot_group_demo',
			'type'        => 'group',
			'description' => esc_html__( 'Generates reusable form entries', 'cmb2' ),
			'options'     => array(
					'group_title'    => esc_html__( 'Entry {#}', 'cmb2' ),
				// {#} gets replaced by row number
					'add_button'     => esc_html__( 'Add Another Entry', 'cmb2' ),
					'remove_button'  => esc_html__( 'Remove Entry', 'cmb2' ),
					'sortable'       => true,
					'closed'         => true,
				// true to have the groups closed by default
					'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ),
				// Performs confirmation before removing group.
			),
	) );

	$cmb_group->add_group_field( $group_field_id, array(
			'name'       => esc_html__( 'Entry Title', 'cmb2' ),
			'id'         => 'title',
			'type'       => 'text',
			'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$cmb_group->add_group_field( $group_field_id, array(
			'name'        => esc_html__( 'Description', 'cmb2' ),
			'description' => esc_html__( 'Write a short description for this entry', 'cmb2' ),
			'id'          => 'description',
			'type'        => 'textarea_small',
	) );

	$cmb_group->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Entry Image', 'cmb2' ),
			'id'   => 'image',
			'type' => 'file',
	) );

	$cmb_group->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Image Caption', 'cmb2' ),
			'id'   => 'image_caption',
			'type' => 'text',
	) );

}

add_action( 'cmb2_admin_init', 'boot_register_repeatable_group_field_metabox' );

/**
 * Hook in and add a metabox to add fields to the user profile pages.
 */
function boot_register_user_profile_metabox() {
	$cmb_user = new_cmb2_box( array(
			'id'               => 'boot_user_edit',
			'title'            => esc_html__( 'User Profile Metabox', 'cmb2' ),
		// Doesn't output for user boxes
			'object_types'     => array( 'user' ),
		// Tells CMB2 to use user_meta vs post_meta
			'show_names'       => true,
			'new_user_section' => 'add-new-user',
		// where form will show on new user page. 'add-existing-user' is only other valid option.
	) );

	$cmb_user->add_field( array(
			'name'     => esc_html__( 'Extra Info', 'cmb2' ),
			'desc'     => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'       => 'boot_user_extra_info',
			'type'     => 'title',
			'on_front' => false,
	) );

	$cmb_user->add_field( array(
			'name' => esc_html__( 'Avatar', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_user_avatar',
			'type' => 'file',
	) );

	$cmb_user->add_field( array(
			'name' => esc_html__( 'Facebook URL', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_user_facebookurl',
			'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
			'name' => esc_html__( 'Twitter URL', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_user_twitterurl',
			'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
			'name' => esc_html__( 'Google+ URL', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_user_googleplusurl',
			'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
			'name' => esc_html__( 'Linkedin URL', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_user_linkedinurl',
			'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
			'name' => esc_html__( 'User Field', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_user_user_text_field',
			'type' => 'text',
	) );

}

add_action( 'cmb2_admin_init', 'boot_register_user_profile_metabox' );

/**
 * Hook in and add a metabox to add fields to taxonomy terms.
 */
function boot_register_taxonomy_metabox() {
	$cmb_term = new_cmb2_box( array(
			'id'               => 'boot_term_edit',
			'title'            => esc_html__( 'Category Metabox', 'cmb2' ),
		// Doesn't output for term boxes
			'object_types'     => array( 'term' ),
		// Tells CMB2 to use term_meta vs post_meta
			'taxonomies'       => array( 'category', 'post_tag' ),
		// Tells CMB2 which taxonomies should have these fields
			'new_term_section' => true,
		// Will display in the "Add New Category" section
	) );

	$cmb_term->add_field( array(
			'name'     => esc_html__( 'Extra Info', 'cmb2' ),
			'desc'     => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'       => 'boot_term_extra_info',
			'type'     => 'title',
			'on_front' => false,
	) );

	$cmb_term->add_field( array(
			'name' => esc_html__( 'Term Image', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_term_avatar',
			'type' => 'file',
	) );

	$cmb_term->add_field( array(
			'name' => esc_html__( 'Term Field', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => 'boot_term_term_text_field',
			'type' => 'text',
	) );

}

add_action( 'cmb2_admin_init', 'boot_register_taxonomy_metabox' );


