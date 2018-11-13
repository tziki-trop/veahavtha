<?php
function TZT_email_template_settings() {
 	// Section 
 	add_settings_section(
		'email_templates_section',
		'Dynamic Email Templates',
		'ibenic_email_templates_section',
		'email-templates'
	);
 	
 	// Field 
 	add_settings_field(
		'email_template_user',
		'User Registered',
		'email_template_user_cb',
		'email-templates',
		'email_templates_section'
	);
 	
 	// Value under which we are saving the data by $_POST
 	register_setting( 'email-templates', 'email_template_user' );
 }
 
 add_action( 'admin_init', 'ibenic_email_template_settings' );
 function ibenic_email_templates_section() {
 	echo '';
 }
 function email_template_user_cb() {
 	$content = get_option('email_template_user');
 	wp_editor( $content, 'email_template_user' );
 }
