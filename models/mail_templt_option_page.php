<?php
function TZT_email_template_submenu() {
	add_submenu_page(
		'options-general.php',
	        'Email Templates',
	        'Email Templates',
	        'manage_options',
	        'email-templates',
	        'ibenic_email_template_submenu_cb' );
}
add_action( 'admin_menu', 'ibenic_email_template_submenu' );
function ibenic_email_template_submenu_cb() {
	?>
	<div class="wrap">
		<h2><?php _e( 'Email Templates', 'your_textdomain'); ?></h2>
		<form method="post" action="options.php">
			<?php 
			settings_fields( 'email-templates' );	//pass slug name of page, also referred
                        
			do_settings_sections( 'email-templates' ); 	//pass slug name of page
			submit_button();
			?>
		</form>
	</div>
	<?php
}