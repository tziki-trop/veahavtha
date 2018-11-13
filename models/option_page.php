<?php
namespace Donations\Option;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//require __DIR__ . '/google-api-php-client-2.2.2/vendor/autoload.php';
class TZT_option_page {
    public function __construct() {
		$this->add_actions();
 
	}
private function add_actions() {
        add_action('admin_menu', [$this,'create_menu_page']);
   //     add_action( 'admin_init', [$this,'email_template_settings'] );
    //    add_action( 'admin_menu', [$this,'email_template_submenu'] );
    

	}
public function email_template_submenu() {
	add_submenu_page(
		'options-general.php',
	        'Email Templates',
	        'Email Templates',
	        'manage_options',
	        'email-templates',
	        [$this,'ibenic_email_template_submenu_cb'] );
}
public function ibenic_email_template_submenu_cb() {
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
public function email_template_settings() {
 	// Section 
 	add_settings_section(
		'email_templates_section',
		'Dynamic Email Templates',
		[$this,'ibenic_email_templates_section'],
		'email-templates'
	);
 	
 	// Field 
 	add_settings_field(
		'email_template_user',
		'User Registered',
		[$this,'email_template_user_cb'],
		'email-templates',
		'email_templates_section'
	);
 	
 	// Value under which we are saving the data by $_POST
 	register_setting( 'email-templates', 'email_template_user' );
 }
    public function  ibenic_email_templates_section() {
 	echo '';
 }
    public function email_template_user_cb() {
 	$content = get_option('email_template_user');
 	wp_editor( $content, 'email_template_user' );
 }
public function create_menu_page() {

	//create new top-level menu
	add_menu_page(__( 'Donation Settings', 'client_to_google_sheet' ),
                  __( 'Donation Settings', 'client_to_google_sheet' ), 'administrator',
                  __FILE__, [ $this,'main_settings_page'] ,
                  'dashicons-editor-table' );

	//call register settings function
	add_action( 'admin_init', [ $this,'register_plugin_settings'] );
}


public function register_plugin_settings() {
    register_setting('Donation_Settings', 'add_bank' );
	 register_setting('Donation_Settings', 'login_page' );
	 register_setting('Donation_Settings', 'account_page' );
      register_setting('Donation_Settings', 'user_page' );
      register_setting( 'Donation_Settings', 'add_campiagn_page' );
	register_setting( 'Donation_Settings', 'add_campiagn_lang_page' );
	
	    register_setting('Cardcom_Settings', 'TerminalNumber' );
	 register_setting('Cardcom_Settings', 'UserName' );
	 register_setting('Cardcom_Settings', 'SuccessRedirectUrl' );
      register_setting('Cardcom_Settings', 'ErrorRedirectUrl' );

	    register_setting('Currencys', 'USD_ILS' );
	 register_setting('Currencys', 'EUR_ILS' );

}

public function main_settings_page() {
?>
<div class="wrap">
<h1><?php  echo __( 'Donation_Settings', 'client_to_google_sheet' ); ?></h1>

<form method="post" action="options.php">
    <?php settings_fields( 'Donation_Settings' ); ?>
    <?php do_settings_sections( 'Donation_Settings' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php  echo __( 'User Page', 'client_to_google_sheet' ); ?></th>
        <td><input type="text" name="user_page" value="<?php echo esc_attr( get_option('user_page') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><?php  echo __( 'Add Campiagn Page', 'client_to_google_sheet' )?></th>
        <td><input type="text" name="add_campiagn_page" value="<?php echo esc_attr( get_option('add_campiagn_page') ); ?>" /></td>
        </tr>
		  <tr valign="top">
        <th scope="row"><?php  echo __( 'Add Campiagn Lang Page', 'client_to_google_sheet' )?></th>
        <td><input type="text" name="add_campiagn_lang_page" value="<?php echo esc_attr( get_option('add_campiagn_lang_page') ); ?>" /></td>
        </tr>
		  <tr valign="top">
        <th scope="row"><?php  echo __( 'Add bank Page', 'client_to_google_sheet' )?></th>
        <td><input type="text" name="add_bank" value="<?php echo esc_attr( get_option('add_bank') ); ?>" /></td>
        </tr>
		  <tr valign="top">
        <th scope="row"><?php  echo __( 'login page', 'client_to_google_sheet' )?></th>
        <td><input type="text" name="login_page" value="<?php echo esc_attr( get_option('login_page') ); ?>" /></td>
        </tr>
		  <tr valign="top">
        <th scope="row"><?php  echo __( 'account page', 'client_to_google_sheet' )?></th>
        <td><input type="text" name="account_page" value="<?php echo esc_attr( get_option('account_page') ); ?>" /></td>
        </tr>
  
    </table>
   
    <?php submit_button(); ?>

</form>
</div>
<div class="wrap">
<h1><?php  echo __( 'Cardcom Settings', 'client_to_google_sheet' ); ?></h1>

<form method="post" action="options.php">
    <?php settings_fields( 'Cardcom_Settings' ); ?>
    <?php do_settings_sections( 'Cardcom_Settings' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php  echo __( 'TerminalNumber', 'client_to_google_sheet' ); ?></th>
        <td><input type="text" name="TerminalNumber" value="<?php echo esc_attr( get_option('TerminalNumber') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><?php  echo __( 'UserName', 'client_to_google_sheet' )?></th>
        <td><input type="text" name="UserName" value="<?php echo esc_attr( get_option('UserName') ); ?>" /></td>
        </tr>
                <tr valign="top">
        <th scope="row"><?php  echo __( 'SuccessRedirectUrl', 'client_to_google_sheet' )?></th>
        <td><input type="text" name="SuccessRedirectUrl" value="<?php echo esc_attr( get_option('SuccessRedirectUrl') ); ?>" /></td>
        </tr>
                <tr valign="top">
        <th scope="row"><?php  echo __( 'ErrorRedirectUrl', 'client_to_google_sheet' )?></th>
        <td><input type="text" name="ErrorRedirectUrl" value="<?php echo esc_attr( get_option('ErrorRedirectUrl') ); ?>" /></td>
        </tr>
  
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
 
<div class="wrap">
<h1><?php  echo __( 'Currencys Settings', 'client_to_google_sheet' ); ?></h1>

<form method="post" action="options.php">
    <?php settings_fields( 'Currencys' ); ?>
    <?php do_settings_sections( 'Currencys' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php  echo __( 'USD_ILS', 'client_to_google_sheet' ); ?></th>
        <td>
            <input type="text" name="USD_ILS" value="<?php echo esc_attr( get_option('USD_ILS') ); ?>" />
            </td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><?php  echo __( 'EUR_ILS', 'client_to_google_sheet' )?></th>
        <td>
            <input type="text" name="EUR_ILS" value="<?php echo esc_attr( get_option('EUR_ILS') ); ?>" />
            </td>
        </tr>
             
  
    </table>
    
    <?php submit_button(); ?>

</form>
</div>


<?php }

}
new TZT_option_page();