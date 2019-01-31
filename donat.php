<?php
/**
 * @package Business_Management
 * @version 1.0
 */
/*
Plugin Name: Donat
Plugin URI: https://wordpress.org/plugins/donat/
Description: This is the donat plugin for my site
Author: Tziki Trop
Version: 1.0
Author URI: http://pooslestudio.co.il/
Text Domain: donations
*/

namespace Donations;
use WP_Query;
use WP_Error;
use Donations\Pll;
use Donations\Donation;
use Donations;
//use Donations\
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 //apply_filters( 'elementor/template_library/sources/local/register_post_type_args', array $args )

class TZT_Donations {
    public function __construct() {
         $this->add_plugin_actions();
         $this->add_requires();
		  //    error_log( print_r( "test" ) );

    }
     static function install() {
            // do not generate any output here

     }
     public function update_currencys(){
      $currency_mengger =  new \Donations\TZT_menge_currency(); 
      $currency_mengger->get_currencys_from_api();
      return;
     }
	public function add_css_and_js(){
 //<link rel="stylesheet" href="/bower_components/owl.carousel/dist/assets/owl.carousel.min.css" />
      wp_enqueue_script('owl_widget_js', plugin_dir_url(__FILE__) . 'js/owl.carousel.min.js', array( 'jquery' ), '1.0.0', true);
		//owl.theme.default.min.css
		wp_enqueue_style( 'owl_widget_def_css', plugin_dir_url(__FILE__) . 'css/owl.theme.default.min.css',false,'1.1','all');
		 wp_enqueue_style( 'owl_widget_css', plugin_dir_url(__FILE__) . 'css/owl.carousel.min.css',false,'1.1','all');
      wp_enqueue_style( 'form_widget_css', plugin_dir_url(__FILE__) . 'css/form_widget.css',false,'1.1','all');
      wp_enqueue_script('circle_js', plugin_dir_url(__FILE__) . 'js/circle/dist/circle-progress.js', array( 'jquery' ), '1.0.0', true);
		wp_enqueue_style( 'camp_widget_css', plugin_dir_url(__FILE__) . 'css/camp_widget.css',false,'1.1','all');
		     wp_enqueue_script('widget_js', plugin_dir_url(__FILE__) . 'js/widgetjs.js', array( 'jquery' ), '1.0.0', true);
		 //<script src="jquery-circle-progress/dist/circle-progress.js"></script>

    }
    public function reg_my_tag($dynamic_tags){
			\Elementor\Plugin::$instance->dynamic_tags->register_group( 'meta-variables', [
		'title' => 'Meta Variables' 
	] );
          require __DIR__ . '/models/Tags/meta.php';
		require __DIR__ . '/models/Tags/metauser.php';
		require __DIR__ . '/models/Tags/user.php';
		require __DIR__ . '/models/Tags/meta_url.php';
        require __DIR__ . '/models/Tags/meta_edit.php';
        require __DIR__ . '/models/Tags/meta_edit_url.php';
		require __DIR__ . '/models/Tags/error.php';
		require __DIR__ . '/models/Tags/meta_doner.php';
        require __DIR__ . '/models/Tags/casum_messege.php';
       
             $dynamic_tags->register_tag( new \Donations\Tags\TZT_casum_messege() );
             $dynamic_tags->register_tag( new \Donations\Tags\TZT_casum_messege() );
   
	 $dynamic_tags->register_tag( new \Donations\Tags\TZT_meta_doner() );
   
    $dynamic_tags->register_tag( new \Donations\Tags\TZT_Meta_by_id_Tag() );
    $dynamic_tags->register_tag( new \Donations\Tags\TZT_Meta_Edit_Url_as_Tag() );

	$dynamic_tags->register_tag( new \Donations\Tags\TZT_Meta_Get_as_Tag() );
	
	$dynamic_tags->register_tag( new \Donations\Tags\TZT_Meta_Url_as_Tag() );
	$dynamic_tags->register_tag( new \Donations\Tags\TZT_Meta_as_Tag() );
		$dynamic_tags->register_tag( new \Donations\Tags\TZT_User_as_Tag() );
		$dynamic_tags->register_tag( new \Donations\Tags\TZT_Meta_user_as_Tag() );
	}
    public function on_widgets_registered() {
    require  __DIR__ . '/models/Widgets/camp_update.php';		
		require  __DIR__ . '/models/Widgets/progres_widget.php';
		require  __DIR__ . '/models/Widgets/embed.php';
		require  __DIR__ . '/models/Widgets/all_terms.php';
		require __DIR__ . '/models/Widgets/form_widgets.php'; 
		require __DIR__ . '/models/Widgets/input_widget.php'; 
		require __DIR__ . '/models/Widgets/archive_widget.php'; 
		require __DIR__ . '/models/Widgets/form_helpers.php'; 
		require __DIR__ . '/models/Widgets/video_camp.php';
		require __DIR__ . '/models/Widgets/donation_form.php';
		require __DIR__ . '/models/Widgets/doners_widget.php';
		        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Donations\Widgets\TZT_camp_update() );
	
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Donations\Widgets\TZT_Doners_Widget() );
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Donations\Widgets\TZT_Progres_Widget() );
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Donations\Widgets\TZT_Donation__Widget() );
	
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Donations\Widgets\TZT_Video_Widget() );
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Donations\Widgets\TZT_Terms_Widget() );
       \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Donations\Widgets\TZT_Form_Widget() );
	    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Donations\Widgets\TZT_Input_Widget() );
	 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Donations\Widgets\TZT_Archive_Widget() );
	
	
	}
    public function add_requires(){
		 require  __DIR__ . '/models/menge_currency.php'; 
	require  __DIR__ . '/models/cat_img.php';		
	require  __DIR__ . '/models/pll.php';
	require  __DIR__ . '/models/option_page.php';
    require  __DIR__ . '/models/menge_users.php';
    require  __DIR__ . '/models/menge_cpt.php'; 
    require  __DIR__ . '/models/menge_campaign.php'; 
	require  __DIR__ . '/models/post_actions.php';
	require  __DIR__ . '/models/set_access.php';
      require __DIR__ . '/models/maonge_donations.php';
		require __DIR__ . '/models/card_com.php';
        require __DIR__ . '/models/mail/mail_press.php';
		
       
    }
    public function register_elementor_locations( $elementor_theme_manager ) {

	$elementor_theme_manager->register_location(
		'doners',
		[
			'label' => __( 'Doners', 'popupelementor' ),
			'multiple' => true,
			'edit_in_content' => true,
		]
	);
        
	$elementor_theme_manager->register_location(
		'tnx',
		[
			'label' => __( 'Tnx', 'popupelementor' ),
			'multiple' => true,
			'edit_in_content' => true,
		]
	);
        $pll  = new \Donations\Pll\TZT_pll();
        $langs = $pll->all();
        foreach ($langs as $lang){
        $elementor_theme_manager->register_location(
		'arc_'.$lang,
		[
			'label' =>  "myarc_".$lang,
			'multiple' => true,
			'edit_in_content' => true,
		]
    );  
    
        $elementor_theme_manager->register_location(
	
            'sin_'.$lang,
		[
			'label' =>  "sin_".$lang,
			'multiple' => true,
			'edit_in_content' => true,
		]
	);
        $elementor_theme_manager->register_location(
	
            'hdr_'.$lang,
		[
			'label' =>  "hdr_".$lang,
			'multiple' => true,
			'edit_in_content' => true,
		]
	);
        $elementor_theme_manager->register_location(
	
            'footer_'.$lang,
		[
			'label' =>  "footer_".$lang,
			'multiple' => true,
			'edit_in_content' => true,
		]
	);
        }
    }
    public function add_plugin_actions(){
        add_action( 'show_user_profile', [$this,'user_field'] );
add_action( 'edit_user_profile', [$this,'user_field'] );
add_action( 'personal_options_update', [$this,'save_custom_user_profile_fields'] );
add_action( 'edit_user_profile_update', [$this,'save_custom_user_profile_fields'] );

        add_action('update_currencys_daily',[$this,'update_currencys']);
        add_action( 'elementor/theme/register_locations', [$this,'register_elementor_locations'] );
		add_action("elementor/frontend/section/before_render", [ $this,'before_section_render']);
		add_action("elementor/frontend/section/after_render", [ $this,'after_section_render']);
		add_action('elementor/element/before_section_start', [ $this,'add_section_contrulers'],10, 2);
		
		add_action('wp_enqueue_scripts', [ $this,'add_css_and_js']);
        add_action( 'init', [ $this,'create_post_type'] );
        add_action( 'castum_reg_cpt', [ $this,'create_post_type'] );

        add_action( 'init', [ $this,'register_strings'] );
      
        add_action('plugins_loaded', [ $this,'wan_load_textdomain']);
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );
		add_action( 'elementor/dynamic_tags/register_tags',[$this,'reg_my_tag']);
 		add_filter('query_vars', array($this, 'add_query_vars'), 0);
		add_action('parse_request', array($this, 'sniff_requests'), 0);
		add_action('init', array($this, 'add_endpoint'), 0);
        add_filter('manage_organization_custom_column', [$this,'add_feature_group_column_content'], 10, 3 );
        add_filter('manage_edit-organization_columns', [$this,'add_feature_group_column'] );
        add_action( 'get_tnx_content', [$this,'tnx_page_content'] );
        add_action( 'publish_donations', [$this,'status_donation_publish'], 10, 2 );
        add_action( 'publish_campaign', [$this,'status_campaign_publish'], 10, 2 );
        add_action('end_camp',[$this,'status_campaign_end']);
        
        add_action( 'elementor_pro/posts/query/user_query',[$this,'user_query']);
        add_action('after_setup_theme', [$this,'remove_admin_bar']);
 
        add_action('init',[$this,"reriecr_deshbord"]);
        add_action( 'edit_form_after_title', [ $this,'edit_form_before_editor'] );
        add_action( 'add_meta_boxes_mail_temp', [$this,'global_notice_meta_box' ]);
        add_action( 'save_post_mail_temp', [$this,'save_global_notice_meta_box_data' ]);
        add_filter( 'manage_mail_temp_posts_columns', [$this,'set_custom_mail_temp_column'] );
        add_action( 'manage_mail_temp_posts_custom_column' , [$this,'custom_mail_temp_column'], 10, 2 );
        add_action('wp_head', [$this,'add_track_to_hdr']);
        add_filter( 'pll_the_language_link', [$this,'filter_pll_translation_urlsadfasdfaf'], 10, 2 ); 

    } 
    public function filter_pll_translation_urlsadfasdfaf( $url, $language_slug ) {
        if(is_archive()){
          //  echo "test: <br>";
         //   echo $language_slug;

           // if( $language_slug == "he")
            if( $language_slug == "en")
            return "/en/campaign/"; 
            return "/campaign/"; 

        }
        return $url;
     }
            
   // add the filter 
    public function add_track_to_hdr(){
        $gtags = array('UA-122534366-1');
        $fbs = array('243478923146791');
        $currency_set = '';
        global $wp_query;
        if ( is_singular( 'campaign' )) {
            $page_id = get_queried_object_id();
            $camp_mengger =  new \Donations\Campaign\TZT_menge_campaign($page_id); 
            $id = $camp_mengger->get_camp_source($page_id);
            $gtag =   get_post_meta( $id, 'analytics', true );
            $fb =   get_post_meta( $id, 'facebook', true );
            $currency = get_post_meta( $page_id, 'currency', true );
            $currency_set = "gtag('set', {
                'currency': '".$currency."'
               });";
            if(!empty($gtag)){
                array_push($gtags,$gtag);
            }
            if(!empty($fb)){
                array_push($fbs,$fb);
            }

        }

        ?>
                <!-- Global site tag (gtag.js) - Google Analytics -->
                <script async src="https://www.googletagmanager.com/gtag/js?id=UA-122534366-1"></script>
                <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
             <?php
              foreach($gtags as $gtag){
                  echo "gtag('config', '".$gtag."');";
              }
              echo $currency_set;
              ?>
           
            </script>
            <!-- Facebook Pixel Code -->
            <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            <?php
              foreach($fbs as $fb){
                  echo "fbq('init', '".$fb."');";
              }
            ?>
           // fbq('init', '1366369253482048');  
            fbq('track', 'PageView');
            </script>
            <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=243478923146791&ev=PageView&noscript=1"
            /></noscript>
            <!-- End Facebook Pixel Code -->
            <script src="https://code.jquery.com/jquery-1.12.4.js" type="text/javascript"></script>
 
 <script src="https://www.negishim.com/accessibility/accessibility_pro_group255.js" type="text/javascript"></script>
 <script type="text/javascript">
     accessibility_rtl = true;
     pixel_from_side = 20;
     pixel_from_start = 100;
 </script>

 
        <?php
   

    }
    public function set_custom_mail_temp_column($columns){
            $columns['type'] = __( 'Type', 'business-management' );
          $columns['lang'] = __( 'Lang', 'business-management' );

       return $columns;
    }
    public function custom_mail_temp_column( $column, $post_id ) {
    switch ( $column ) {
  case 'type' :
            echo get_post_meta( $post_id , 'mail_type' , true ); 
            break;
  case 'lang' :
            echo get_post_meta( $post_id , 'mail_lang' , true ); 
            break;

    }
}
    public function save_global_notice_meta_box_data( $post_id ) {
    $error = false;
    // Check if our nonce is set.
    if ( ! isset( $_POST['mail_temp_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['mail_temp_nonce'], 'mail_temp_nonce' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
     if ( ! current_user_can('administrator') ) {
            return;
        }
    

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['mail_type'] ) || ! isset( $_POST['mail_lang'] ) ) {
        return;
    }
          $args=array(
                'post__not_in' => array($post_id),
                'post_status' => 'publish',
                'post_type' => 'mail_temp',
                'posts_per_page' => -1,
                'meta_query' => array(
                        'relation' => 'AND',
                        array(
                           'key' => 'mail_type',
                           'value' => $_POST['mail_type'],
                           'compare' => '=',
                        ),
                        array(
                           'key' => 'mail_lang',
                           'value' => $_POST['mail_lang'],
                           'compare' => '=',
                        ),
                    
                )
		 );
                  $my_query =  new WP_Query($args);
                  if( $my_query->have_posts() ){
               
                 $error = new WP_Error( 'typ_exsist', __( "Email Templet already exists for this language
", "my_textdomain" ) );
                 $_SESSION['mail_type_errors'] = $error->get_error_message();
                  return false;
                  }
        
    // Sanitize user input.
    $type = sanitize_text_field( $_POST['mail_type'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, 'mail_type', $type );
    $lang = sanitize_text_field( $_POST['mail_lang'] );
    update_post_meta( $post_id, 'mail_lang', $lang );
}


    public function edit_form_before_editor($post) {
	if(get_post_type($post) != 'mail_temp')
		return;      
 $notes = array("amount",
                                  "currency",
                                  "update",
                                   "title",
                                   "content",
                                   "link",
                         "username","useremail"
                                 );
    ob_start();?>
<div class="sp">
	<table style="width:100%">
  <tr>
        <?php
        foreach($notes as $note){
        ?>
	  <th>{{<?php echo strtoupper( $note ); ?>}}</th>
     <?php
        }
      ?>
  </tr>
</table>
</div>

<?php
	$output_string = ob_get_contents();
	ob_end_clean();
   // var_dump(get_post_meta($post->ID));
	echo $output_string;
}

    public function reriecr_deshbord(){
if( is_admin() && !defined('DOING_AJAX') && ( current_user_can('campaigner') || current_user_can('contributor') ) ){
   // wp_redirect(home_url());
  //  exit;
  } 
    }
   public function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}
 public function user_query($query){
     $user_post = get_user_meta(get_current_user_id(),'campiagn_id',false);
     $pids = [];
   //  var_dump($user_post);
     if (is_array($user_post) && !empty($user_post)){
        foreach ($user_post as $pid) {
            array_push($pids,(int)$pid);
        }
    	$query->set( 'post__in', $pids );
          $meta =  array(
	'relation' => 'AND',
	array(
		'key' => 'source',
		 'value' => 'this',
		 'compare' => 'LIKE',
	));
     
         //$query->set( 'post_author', get_current_user_id() );
           
           $query->set( 'meta_query', $meta );
             $query->set( 'post_status', array('publish',
                                               'pending', 
                                               'draft',
                                               'activ', 
                                               'ended', 
                                               'successfully') );
   

     }
     else{
       $query->set( 'post__in', array(0));      
     }
    } 
public function status_campaign_end($id){
      $camp_mengger =  new \Donations\Campaign\TZT_menge_campaign($id); 
      $camp_mengger->end_camp($id);
      return;   
}
public function status_campaign_publish($ID , $post ){
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
      $camp_mengger =  new \Donations\Campaign\TZT_menge_campaign($ID); 
      $camp_mengger->start($ID);
      return;
       
}
public function status_donation_publish( $ID , $post ) 
{
    
    // update_post_meta($ID,'test1','test2');
    $donation_mengger = new \Donations\Donation\TZT_menge_Donations( $ID );
    $res = $donation_mengger->publish_donation( $ID );
	return;

}
  //  activ
    public function register_strings(){
        $pll  = new \Donations\Pll\TZT_pll();
        $pll->reg_string();
    }
 public function set_pll_with_elementor( $post_id){
	
	 	if ( function_exists( 'pll_get_post' ) ) {
	
		$translation_post_id = pll_get_post( $post_id , pll_current_language());
	
		if ( null === $translation_post_id ) {
	
			// the current language is not defined yet
			return $post_id;
		} elseif ( false === $translation_post_id ) {
	
			//no translation yet
			return $post_id;
		} elseif ( $translation_post_id > 0 ) {
			// return translated post id
			return $translation_post_id;
		}
	}
	return $post_id;

 }   
public function add_feature_group_column( $columns ){
    $columns['feature_group'] = __( 'Group', 'my_plugin' );
    return $columns;
}
public function add_feature_group_column_content( $content, $column_name, $term_id ){
   // global $feature_groups;

    if( $column_name !== 'feature_group' ){
        return $content;
    }

    $term_id = absint( $term_id );
    $feature_group = get_term_meta( $term_id, 'camp_id', true );

    if( !empty( $feature_group ) ){
        $content .= esc_attr( $feature_group );
    }

    return $content;
}
    	 public function add_query_vars($vars){
            // $vars[] = 'cardcom';
            // $vars[] = 'campaignname'; 
            // $vars[] = 'tnxs'; 
              array_push($vars,'cardcom');
              array_push($vars,'campaignname');
             
		return $vars;
	}
    public function add_endpoint(){
        add_rewrite_rule('^campaign/(.+)/tnx','index.php?campaignname=$matches[1]','top');
       add_rewrite_rule('[^/]+/campaign/(.+)/tnx','index.php?campaignname=$matches[1]','top');

        add_rewrite_rule('^cardcomres','index.php?cardcom=1','top');
	}
    public function tnx_page_content(){
        global $wp;

 $post = get_page_by_path( $wp->query_vars['campaignname'], OBJECT, 'campaign' );
  //var_dump(  $id = $post->ID);
 $args = array(  'p'=> $post->ID,'post_type' => 'campaign');
  global $wp_query;     
    $res = '';
        $my_query = new WP_Query($args);
         ob_start();
         while ($my_query->have_posts()) : $my_query->the_post(); 
         $camp_mengger =  new \Donations\Campaign\TZT_menge_campaign(get_the_ID()); 
         $id = $camp_mengger->get_camp_source(get_the_ID());
         $gtag =   get_post_meta( $id, 'analytics', true );
         $fb =   get_post_meta( $id, 'facebook', true );
         echo "<script>";
        if(!empty($gtag)){
            echo "gtag('config', '".$gtag."');"; 
         }
         if(!empty($fb)){
            echo "fbq('init', '".$fb."'); fbq('track', 'PageView');";
         }
         echo "  var tontrack = localStorage.getItem('tontrack');
         debugger;
         tontrack = JSON.parse(tontrack)
         gtag('set', {
            'currency': tontrack.currency
           });
          gtag('event', 'purchase_event', {
            'event_category': 'purchase',
            'event_label': tontrack.name,
            'value': tontrack.amount
          });
         fbq('track', 'Purchase', {
            value: tontrack.amount,
            currency: tontrack.currency,
          });
          var timeStampInMs = window.performance && window.performance.now && window.performance.timing && window.performance.timing.navigationStart ? window.performance.now() + window.performance.timing.navigationStart : Date.now();

          gtag('event', 'purchase', {
            'transaction_id': timeStampInMs,
            'value': tontrack.amount,
            'currency': tontrack.currency,
            'items': [
              {
                'id': tontrack.id,
                'name': tontrack.name,
                'quantity': 1,
                'price': tontrack.amount
              },
            ]
          }); 
         </script>";
        elementor_theme_do_location( 'tnx' );
            endwhile;
      $content = ob_get_clean();
	  echo $content;
        
    
    }
	   public function sniff_requests(){
		global $wp;
		if(isset($wp->query_vars['cardcom'])){
            do_action('castum_reg_cpt');
           $payment_megger = new \Donations\TZT_menge_card_com('check_res');
            $payment_megger->get_res();
        }
     
}
public function test_card_com_res(){

       $payment_megger = new \Donations\TZT_menge_card_com('check_res');
        $payment_megger->get_res();
 
}

	public function add_section_contrulers($element,$section_id){
				
	if("section_custom_css" !== $section_id || "section" !== $element->get_type())
		return;
$element->start_controls_section(
					
			'section_for_settings',
			[
				'label' => __( 'Form settings', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
			$element->add_control(
			'is_form',
			[
				'label' => __( 'is form?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
	        	'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'true',
				'default' => 'no',
				
			]
		); 
		//form_action
		$element->add_control(
			'form_action',
			[
				'label' => __( 'form action', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'add_campaign'  => __( 'add campaign', 'client_to_google_sheet' ),
					'add_user' => __( 'add user', 'client_to_google_sheet' ),
					'add_bank' => __( 'add bank', 'client_to_google_sheet' ),
					
                    'add_campaign_lang' => __( 'add campaign lang', 'client_to_google_sheet' ),
					'make_donaction' => __( 'add donation', 'client_to_google_sheet' ),
					'edit_camp_page' => __( 'edit_camp_page', 'client_to_google_sheet' ),
					'edit_camp_settings' => __( 'edit_camp_settings', 'client_to_google_sheet' ),
                   'add_camp_update' => __( 'add_camp_update', 'client_to_google_sheet' ),
                   
				],
			]
		);
		$element->end_controls_section();
        
$element->start_controls_section(
					
			'section_for_settings_sub',
			[
				'label' => __( 'Sub page settings', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
			$element->add_control(
			'is_sub',
			[
				'label' => __( 'is sub?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
	        	'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'true',
				'default' => 'no',
				
			]
		); 
		//form_action
		$element->add_control(
			'sub_action',
			[
				'label' => __( 'form action', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
				'edit_camp'  => __( 'edit camp', 'client_to_google_sheet' ),
				'settings' => __( 'camp settings', 'client_to_google_sheet' ),
                'page' =>  __( 'camp page', 'client_to_google_sheet' ),
                'bank' =>  __( 'bank page', 'client_to_google_sheet' ),
                'add_lang' =>  __( 'add lang page', 'client_to_google_sheet' ),
                'update' =>  __( 'update', 'client_to_google_sheet' ),
                
				],
			]
		);
		$element->end_controls_section();
	
	}
	//section_custom_css
	public function before_section_render($element){
      //  var_dump($element);
		$settings = $element->get_settings_for_display();
		if("true" === $settings['is_form']){
		//	return;
		if($settings['form_action'] === "add_campaign" || $settings['form_action'] === "add_campaign_lang" || $settings['form_action'] === "edit_camp_page")
			$enctype = "enctype=\"multipart/form-data\"";
		else $enctype ='';
		?>
           <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" <?php echo $enctype; ?>>
           <input type="hidden" name="action" value="<?php echo $settings['form_action']; ?>">
		  <input type="hidden" name="current" value="<?php echo get_queried_object_id(); ?>">
	      <?php wp_nonce_field( 'validate', 'form_nonce' ); ?>
	   <?php 
            }
        if("true" === $settings['is_sub']){
            if(isset($_GET['sub-page']) && $_GET['sub-page'] === $settings['sub_action'])
                return;
             ?>
               <div class="hidden">
            <?php 
        }
           
	}
	public function after_section_render($element){
		$settings = $element->get_settings_for_display();
		if("true" === $settings['is_form']){
			//return;
		?>
    </form>
    <?php
        }
         if("true" === $settings['is_sub']){
            if(isset($_GET['sub-page']) && $_GET['sub-page'] === $settings['sub_action'])
                return;
             ?>
               </div>
            <?php 
        }
	}
    public function wan_load_textdomain() {
	load_plugin_textdomain( 'donations', false, dirname( plugin_basename(__FILE__) ) . '/' );
    }
public function global_notice_meta_box_callback($post){
  //  var_dump($_SESSION);
    if (isset($_SESSION) && array_key_exists( 'mail_type_errors', $_SESSION ) ) {?>
    <div class="error">
        <p><?php echo $_SESSION['mail_type_errors']; ?></p>
    </div><?php

    unset( $_SESSION['mail_type_errors'] );
}
    wp_nonce_field( 'mail_temp_nonce', 'mail_temp_nonce' );

    $value = get_post_meta( $post->ID, 'mail_type', true );
 $lang = get_post_meta( $post->ID, 'mail_lang', true );
      
?>
<p>סוג המייל</p>
<select id="mail_type" name="mail_type">
<?php
foreach ($this->get_meil_type() as $key => $value) {
?>
<option value="<?php echo $key ?>"<?=$value == $key ? ' selected="selected"' : '';?>><?php echo $value ?></option>

<?php } ?>
</select>

<p>שפה</p>
<select id ="lang_mail" name="mail_lang">
<option value="he_IL"<?=$lang == 'he_IL' ? ' selected="selected"' : '';?>>he</option>
<option value="en_US"<?=$lang == 'en_US' ? ' selected="selected"' : '';?>>en</option>
</select>
<?php

}
protected  function get_meil_type(){
    return array(
        'add_update' => "הוספת עדכון לקמפיין",
        'add_camp' => "הוספת  קמפיין",
        'camp_approv' => "קמפיין אושר",
        'new_donation' => "תרומה חדשה",
        'start_camp' => "קמפיין התחיל",
    );
}
public function global_notice_meta_box() {

    add_meta_box(
        'global-notice',
        __( 'mail type', 'sitepoint' ),
        [$this,'global_notice_meta_box_callback'],
         'mail_temp', 
   'side', 
   'high'
    );

}
public function create_post_type() {
         if( !session_id() )
  {
    session_start();
  }
 register_post_type( 'mail_temp',
    array(
      'labels' => array(
        'name' => __( 'mail_temp', 'donat'),
        'singular_name' => __( 'mail_temp', 'donat'),
        'add_new' => __('Add mail_temp','donat'),      
          'add_new_item' => __('Add mail_temp','donat')
      ),
     //   'register_meta_box_cb' => [$this,'global_notice_meta_box'],
        'show_in_menu' => true,
        'show_ui' => true,
      'public' => false,
      'has_archive' => false,
      'supports' => array('custom-fields','title','editor')
    )
  );
     register_post_type( 'campaign',
    array(
      'labels' => array(
        'name' => __( 'Campaign', 'donat'),
        'singular_name' => __( 'Campaign', 'donat'),
        'add_new' => __('Add Campaign','donat'),      
          'add_new_item' => __('Add Campaign','donat')
      ),
      'public' => true,
      'has_archive' => true,
      'supports' => array('custom-fields','title','editor','thumbnail')
    )
  );

		  $labels = array(
    'name' => __( 'Category', 'taxonomy general name' ),
    'singular_name' => __( 'Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Category' ),
    'popular_items' => __( 'Popular Category' ),
    'all_items' => __( 'All Category' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Category' ), 
    'update_item' => __( 'Update Category' ),
    'update_item' => __( 'Update Category' ),
    'add_new_item' => __( 'Add New Category' ),
    'new_item_name' => __( 'New Category Name' ),
    'separate_items_with_commas' => __( 'Separate Categorys with commas' ),
    'add_or_remove_items' => __( 'Add or remove Category' ),
    'choose_from_most_used' => __( 'Choose from the most used Category' ),
    'menu_name' => __( 'Category' ),
  ); 
         register_taxonomy('category','campaign',array(
	'public' => true,
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'category' ),
	 'show_admin_column' => true
  ));
	register_post_status( 'activ', array(
		'label'                     => __( 'activ', 'plugin-domain' ),
		'public'                    => true,
	    'post_type'                 => array( 'campaign' ),
        'internal'                    => true,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'show_in_metabox_dropdown'  => true,
		'show_in_inline_dropdown'   => true,
		'dashicon'                  => 'dashicons-businessman',
        'label_count'               => _n_noop( 'Activ <span class="count">(%s)</span>', 'Activ <span class="count">(%s)</span>' ),

	) );
        register_post_status( 'successfully', array(
		'label'                     => __( 'Successfully completed', 'plugin-domain' ),
		'public'                    => true,
	    'post_type'                 => array( 'campaign' ),
      //  'public'                    => true,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'show_in_metabox_dropdown'  => true,
		'show_in_inline_dropdown'   => true,
		'dashicon'                  => 'dashicons-businessman',
	) );
        register_post_status( 'ended', array(
		'label'                     => __( 'Unsuccessfully completed', 'plugin-domain' ),
		'public'                    => true,
	    'post_type'                 => array( 'campaign' ),
    //    'public'                    => true,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'show_in_metabox_dropdown'  => true,
		'show_in_inline_dropdown'   => true,
		'dashicon'                  => 'dashicons-businessman',
	) );
    //Successfully completed
 
    register_post_type( 'donations',
    array(
      'labels' => array(
        'name' => __( 'Donations', 'donat'),
        'singular_name' => __( 'Donations', 'donat'),
        'add_new' => __('Add Donations','donat'),      
          'add_new_item' => __('Add Donations','donat')
      ),
      'public' => true,
      'has_archive' => false,
 'supports' => array('custom-fields','title')
    )
  );
		

  $labels = array(
    'name' => __( 'Organization', 'taxonomy general name' ),
    'singular_name' => __( 'Organization', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Tags' ),
    'popular_items' => __( 'Popular Tags' ),
    'all_items' => __( 'All Tags' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Tag' ), 
    'update_item' => __( 'Update Tag' ),
    'add_new_item' => __( 'Add New Tag' ),
    'new_item_name' => __( 'New Tag Name' ),
    'separate_items_with_commas' => __( 'Separate tags with commas' ),
    'add_or_remove_items' => __( 'Add or remove tags' ),
    'choose_from_most_used' => __( 'Choose from the most used tags' ),
    'menu_name' => __( 'Tags' ),
  ); 

  register_taxonomy('organization','donations',array(
	'public' => true,
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'update_count_callback' => '_update_post_term_count',
    'rewrite' => array( 'slug' => 'organization' ),
	 'show_admin_column' => true
  ));

           $result = add_role(
    'campaigner',
    __( 'Campaign Mengger' ),
    array(
        'read'         => true,  // true allows this capability
        'edit_posts'   => false,
        'delete_posts' => false, 
        'edit_private_pages' => true,
        'edit_posts' => true,
        'read_private_posts' => true,// Use false to explicitly deny
    )
    );
}
//$lingo = array('en' => 'English', 'md' => '普通話', 'es' => 'Español', 'fr' => 'Français', 'pt' => 'Português');

public function user_field( $user ) {
   // $gender = get_the_author_meta( 'dealing', $user->ID);
  //  $company = esc_attr( get_the_author_meta( 'company', $user->ID ) );
    ?>
    <h3><?php _e('קבלת התראות'); ?></h3>
    <table class="form-table">
    <tr>
            <th>
                <label for="alert"><?php __('התראות'); ?>
            </label></th>
            <td>
            <span class="description"><?php _e('התראות?'); ?></span><br>
           
    <?php
    foreach ($this->get_meil_type() as $key => $value) {
        $user_val =  get_user_meta($user->ID, $key , true);

    ?>
     <label>
     <input type="checkbox" name="<?php echo $key ?>"
      <?php if ($user_val == 'yes' ) { ?>checked="checked"<?php }?>
       value="yes"><?php echo $value ?><br />
     </label>
    <?php } ?>
            </td>
        </tr>
        
    </table>
    <?php 
}


public function save_custom_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return FALSE;
        foreach ($this->get_meil_type() as $key => $value) {
            update_usermeta( $user_id, $key, $_POST[$key] );
        }    

}


}
//register_activation_hook( __FILE__, array( 'TZT_Donations', 'install' ) );
new TZT_Donations();