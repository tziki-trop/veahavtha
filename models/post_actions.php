<?php
namespace Donations\PostAction;
use Donations\Campaign;
use Donations\User;
use Donations;
use WP_Query;
		  
class TZT_post_actions {
    protected $pll;
    protected $cpt;
    protected $post_id;
    protected $campaign;
	 protected $user;
    public function __construct($post_id = null) {
    $this->add_actions();
    $this->campaign = new \Donations\Campaign\TZT_menge_campaign($post_id);
	$this->user = new \Donations\User\TZT_menge_user();
	$this->pll = new \Donations\Pll\TZT_pll();
    }
	public function add_actions(){
		add_action( 'wp_ajax_add_donation',  [ $this,'add_donation'] );
add_action( 'wp_ajax_nopriv_add_donation',  [ $this,'add_donation' ]);
    add_action( 'wp_ajax_get_added_donation',  [ $this,'get_added_donation'] );
add_action( 'wp_ajax_nopriv_get_added_donation',  [ $this,'get_added_donation' ]);
		//edit_camp_page
		    add_action('admin_post_edit_camp_settings', [$this,'edit_camp_settings']);
		   add_action('admin_post_nopriv_add_campaign_lang', [$this,'add_campaign_lang']);
		    add_action('admin_post_edit_camp_page', [$this,'edit_camp_page']);
add_action('admin_post_nopriv_add_campaign', [$this,'add_campaign']);
		   add_action('admin_post_add_campaign', [$this,'add_campaign']);
		  // add_action('admin_post_nopriv_add_campaign_lang', [$this,'add_campaign_lang']);
		   add_action('admin_post_add_campaign_lang', [$this,'add_campaign_lang']);
        add_action('admin_post_add_bank', [$this,'add_bank']);
	
		
        add_action('admin_post_add_camp_update', [$this,'add_camp_update']);
		
          add_action('admin_post_add_user', [$this,'edit_user']);
		add_action('admin_post_nopriv_add_user', [$this,'add_user']);
	//	add_action('admin_post_nopriv_add_donation', [$this,'add_user']);
		add_action('admin_post_nopriv_make_donaction', [$this,'make_donaction']);
		add_action('admin_post_make_donaction', [$this,'make_donaction']);
		add_action('wp_ajax_nopriv_make_donaction_ajax', [$this,'make_donaction_ajax']);
        add_action('wp_ajax_make_donaction_ajax', [$this,'make_donaction_ajax']);

        add_action('make_card_com_payment', [$this,'make_card_com_payment']);
        add_shortcode('get_hok_for_cam',[$this,'get_hok_for_cam']);
    }
    public function get_hok_for_cam(){
      $hok =  get_post_meta(get_the_ID(), "hok", true);
      if($hok == 0 || $hok == false  || $hok == null)
      return;
      else{
          return "<select name=\"hok\"><option value=\"0\" selected=\"selected\">".pll__('one payment')."</option><option value=\"".$hok."\">
           ".pll__('multy payment')." (".$hok." ".pll__('payments').")
          </option></select>";
      }
    }
    public function add_camp_update(){
        $query_arr = array( 'sub-page' => 'update','camp_id' => $_POST['post_id']);
		if ( !wp_verify_nonce( $_POST['form_nonce'], 'validate' )){
              $this->get_page_url_with_error('no_validate',$query_arr);
            return;
        }
        if (!isset($_POST['post_id'])) {
                $this->get_page_url_with_error('no_post_id',$query_arr);
                return;
        	}
        else if(!$this->campaign->is_user_allou(get_current_user_id(),$_POST['post_id'])){
                     $this->get_page_url_with_error('user_nut_allow',$query_arr);
                       return;
        	}
        else{ 
				$post_id = $this->campaign->add_update($_POST);
                if(is_wp_error($post_id)){
                $this->get_page_url_with_error($post_id,$query_arr);
                return;
                }
                $query_arr['status'] = "success";
                $query_arr['description'] = "update_added";
                
                $url = add_query_arg($query_arr,get_permalink($_POST['current']));
			
		
			  wp_redirect($url);
	
    }
    }
    protected function set_errors($error){
        switch ($error) {
    case 0:
        return __( 'error', 'donations');
        break;
    case 1:
        return __( 'error: user email exists', 'donations');
       break;
    case 2:
      return __( 'error: validation nut ok', 'donations');
       break;
      case 3:
      return __( 'error: You do not have permission to perform this action
', 'donations');
       break;
     case 4:
      return __( 'error: Please fill in all fields', 'donations');
       break;
            case 5:
                return __('the camp is peanding aprovel','donations');
        default:
        return __( 'error', 'donations');
       break;

}
    }
	protected function get_page_url($option,$error = false){

		if (!isset($_POST['current'])){
			echo "ERROR!";
		    wp_die();
			}
		if($error === true) {  
       // $option = $this->set_errors($option);
		$url = add_query_arg(array(
            'status' => "error",
            'description' => $option
        ),get_permalink($_POST['current']));
        }
      else
	  $url = get_permalink($this->pll->get_post_lang((int)get_option($option),$this->pll->get($_POST['current'])));
		
		return $url;
	}
    protected function get_page_url_with_error($myerror,$qouery_arr = false){
       $vars = array('status' => 'error');  
           
        if(is_wp_error($myerror))
        $vars['error_code']  = $myerror->get_error_code();
        else
        $vars['error_code']  = $myerror;   
         if($qouery_arr !== false)
            $vars = array_merge($vars, $qouery_arr);
            
        $url = add_query_arg($vars,get_permalink($_POST['current']));
        wp_redirect($url);
           
    }
    public function get_added_donation(){
    
        $donaction_amount =   $this->campaign->get_amount_to_display($_POST['pid']);  
	    
        global $wp_query;
                 $args=array(  
						 		'post_type' => 'donations',
						 		'post_status' => 'publish',
						 	    'posts_per_page' => 2,
                                'post__not_in' => $_POST['ids'],
						 	    'tax_query' => array(
								'relation' => 'AND',
                                array(
                               'taxonomy' => 'organization',
                               'terms' => (int)$_POST['term']
                                ),		 
            )//post__not_in
		 );
    //   if($_POST['scrull'] !== 'true'){
      //     $args['date_query'] = array('before' => $_POST['date']);  
      //  }
        $my_query = new WP_Query($args);
          if( $my_query->have_posts() ){
            $ids = [];
            $posts = [];
            $date = '';
            while ($my_query->have_posts()) : $my_query->the_post(); 
            ob_start();
            elementor_theme_do_location( 'doners' );
            array_push($ids,get_the_ID());
            $content = ob_get_clean();
            array_push($posts,$content);
            $date = get_post_time();
	        endwhile;
            echo json_encode(array("new" => true,
                                   "ids" => $ids,
                                   "posts" => $posts,
                                   "status" => $donaction_amount,
                                  "date" => $date
                                  )
                            );
        
        
            }
          else {
         echo json_encode(array("new" => false));
          }
           wp_reset_query();
           wp_die();  		
    
    
    }
    public function make_card_com_payment($post_id){
        $payment_megger = new \Donations\TZT_menge_card_com('charg_token',(int)$post_id);
        $res = $payment_megger->ask_payment_with_token($post_id);

    }
	public function  make_donaction_ajax(){  
        
		$doner = array('name'=>'','email'=>'','doner_description'=>'');
			if (!isset($_POST['amount']) || !isset($_POST['currency']) || !isset($_POST['post_id'])) {
				
					echo array('status' => "error",'description' =>pll_e('no importent data') );
					wp_die();
			}
            foreach($doner as $detal => $value){
                if(isset($_POST[$detal]))
                    $doner[$detal] = $_POST[$detal];
                    
            }
			$donation_mengger = new \Donations\Donation\TZT_menge_Donations($_POST['post_id']);
            $donation_id = $donation_mengger->add($_POST['amount'],$_POST['currency'],$doner);
            
			$payment_megger = new \Donations\TZT_menge_card_com('get_url',(int)$_POST['post_id']);
			$invise =  array(
				array('description'  => $_POST['description'],'price' => $_POST['amount'],'quantity' => 1)
			); 
			$user = array('name'  => $_POST['name'],'email'  => $_POST['email']);
			$lang = $_POST['lang'];
            $cam = $_POST['post_id'];
			$payment = array('currency'  => $_POST['currency'],'amount'  => $_POST['amount'],'payments' => $_POST['payments']);
            $slug = get_permalink($_POST['post_id']);
			$res = $payment_megger->get_url($user,$payment,$lang,$donation_id,$invise,$slug);
			
			echo json_encode($res);

			 wp_die();
	}
    public function add_bank(){
        $query_arr = array( 'sub-page' => 'settings','camp_id' => $_POST['post_id']);
		
      	if ( !wp_verify_nonce( $_POST['form_nonce'], 'validate' )){
              $this->get_page_url_with_error('no_validate',$query_arr);
            return;
        }
			if (!isset($_POST['post_id'])) {
                $this->get_page_url_with_error('no_post_id',$query_arr);
                return;
        	}
			else if(!$this->campaign->is_user_allou(get_current_user_id(),$_POST['post_id'])){
                     $this->get_page_url_with_error('user_nut_allow',$query_arr);
                       return;
        	}
			else{ 
                $post_id = $this->campaign->add_bank($_POST);
                $query_arr['status'] = "success";
                $query_arr['description'] = "camp_wating_approvel";
                //add_bank
                $page = get_permalink($this->pll->get_post_lang((int)get_option("account_page"),$this->pll->get($_POST['current'])));

                $url = add_query_arg($query_arr,$page);
           // var_dump($page);
          //  wp_die();
      
			}
		
	       wp_redirect($url);
    }
	public function make_donaction(){
		if ( wp_verify_nonce( $_POST['form_nonce'], 'validate' )){
			if (!isset($_POST['amount']) || !isset($_POST['currency']) || !isset($_POST['post_id'])) {
					$url = $this->get_page_url(4,true);
					wp_redirect($url);
					wp_die();
			}
			$payment_megger = new \Donations\TZT_menge_card_com('get_url',(int)$_POST['post_id']);
			/*$user name email ,$payment currency amount,$lang,$cam*/
			$invise =  array(
				array('description'  => $_POST['description'],'price' => $_POST['amount'],'quantity' => 1)
			); 
			$user = array('name'  => $_POST['name'],'email'  => $_POST['email']);
			$lang = $_POST['lang'];
			$cam = $_POST['post_id'];
			$payment = array('currency'  => $_POST['currency'],'amount'  => $_POST['amount']);
			$res = $payment_megger->get_url($user,$payment,$lang,$cam,$invise);
			
			var_dump($res);
			wp_die();
			//public function get_url($user,$payment,$lang,$cam, $invoice = false){
		}
			else{
					$url = $this->get_page_url(2,true);
					wp_redirect($url);
					wp_die();
			}
		
	}
	public function add_donation(){
		      $arrresolt = [];    
		if(!isset($_POST['post_id'])){
			echo array('status' => "error",'error' =>'no post id' );
			 wp_die();
		}
		if(!isset($_POST['currency'])){
			echo array('status' => "error",'error' =>'no currency' );
			 wp_die();
		}
      if(!isset($_POST['fix_amount']) || !isset($_POST['other_amount'])){
		  echo array('status' => "error",'error' =>'no amount' );
			 wp_die();
	  }
		if((int)$_POST['fix_amount'] > 0)
			$amount = $_POST['fix_amount'];
		else $amount = (int)$_POST['other_amount'];
		
		$donation_mengger = new \Donations\Donation\TZT_menge_Donations($_POST['post_id']);
			$res = $donation_mengger->add($amount,$_POST['currency']);
	//	$res
			echo json_encode($res);
		//echo json_encode(array('status' => "seccse",'error' =>'k dude' ));
			 wp_die();
		//	$post_id = $this->campaign->add($_POST,false);	
		  
	}
    public function edit_user(){
        $user_id = $this->user->edit($_POST);
        if(is_wp_error($user_id)){
            $this->get_page_url_with_error($user_id);  
            return;
        } 
        $url = get_permalink($_POST['current']);
        wp_redirect($url);
        return;
    }
	public function add_user(){  
        $user_id = $this->user->add($_POST);
        if(is_wp_error($user_id)){
            $this->get_page_url_with_error($user_id);  
            return;
        }
		$user = get_user_by( 'id', $user_id ); 
        
      if( $user ) {
          wp_set_current_user( $user_id, $user->user_login );
          wp_set_auth_cookie( $user_id ); 
          do_action( 'wp_login', $user->user_login );
          $url = $this->get_page_url('add_campiagn_page');
	
        }
		else{
			$url = $this->get_page_url_with_error($user);
		}
		wp_redirect($url);
   
	}
    public function edit_camp_settings(){
		
		if ( wp_verify_nonce( $_POST['form_nonce'], 'validate' )){
			if (!isset($_POST['post_id'])) {
						$url = add_query_arg(array( 'sub-page' => 'settings','camp_id' => $_POST['post_id'],'lang' => $_POST['lang'],'status' => "error"),get_permalink($_POST['current']));	
			}
			else if(!$this->campaign->is_user_allou(get_current_user_id(),$_POST['post_id'])){
						$url = add_query_arg(
                            array( 'sub-page' => 'settings',
                                  'camp_id' => $_POST['post_id'],
                                  'lang' => $_POST['lang'],
                                  'status' => "error",
                                  'description' => urlencode(urlencode($this->set_errors(5)))),
                                  get_permalink($_POST['current']));	
		
			}
			else{ 
				$post_id = $this->campaign->edit_settings($_POST);
                if(is_wp_error($post_id)){
                $this->get_page_url_with_error($post_id,array( 'sub-page' => 'settings',
                                           'camp_id' => $_POST['post_id'],
                                           'lang' => $_POST['lang']));
                return;
                }
				$url = add_query_arg(array( 'sub-page' => 'settings',
                                           'camp_id' => $_POST['post_id'],
                                           'lang' => $_POST['lang'],
                                           'status' => "success",
                'description' => "camp_wating_approvel"),
                get_permalink($_POST['current']));	
	
			}
		}
		else{
			  $url = $this->get_page_url(4, true);
		}
			  wp_redirect($url);
	}
	public function edit_camp_page(){ 
		$query_arr = array( 'sub-page' => 'page','camp_id' => $_POST['post_id'],'lang' => $_POST['lang']);
		if ( !wp_verify_nonce( $_POST['form_nonce'], 'validate' )){
              $this->get_page_url_with_error('no_validate',$query_arr);
            return;
        }
			if (!isset($_POST['post_id'])) {
                $this->get_page_url_with_error('no_post_id',$query_arr);
                return;
        	}
			else if(!$this->campaign->is_user_allou(get_current_user_id(),$_POST['post_id'])){
                     $this->get_page_url_with_error('user_nut_allow',$query_arr);
                       return;
        	}
			else{ 
				$post_id = $this->campaign->edit($_POST);
                $query_arr['status'] = "success";
                $query_arr['description'] = "camp_wating_approvel";
                
                $url = add_query_arg($query_arr,get_permalink($_POST['current']));
			}
		
	
			  wp_redirect($url);
	}
	public function add_campaign(){
        // wp_redirect( $_SERVER['HTTP_REFERER'] );
		if ( wp_verify_nonce( $_POST['form_nonce'], 'validate' )){
                    error_log("add camp validat");

		   $post_id = $this->campaign->add($_POST);
                   // error_log("added");

            if(is_wp_error($post_id)){
                     error_log("is error");
                $this->get_page_url_with_error($post_id);
                return;
                }
             if($post_id === false){
                $this->get_page_url_with_error('error');
                 
                 return;
             }
	   
    		if (isset($_POST['submit'])) {
	        $url = $this->get_page_url('add_bank');
			$url = add_query_arg(array( 'camp_id' => $post_id),$url);	
			}
	     	else{
			$url = $this->get_page_url('add_campiagn_lang_page');
			$url = add_query_arg(array( 'camp_id' => $post_id),$url);
		        } 	
	   
		}
		else{
             $this->get_page_url_with_error('validation error');
             return;
	}
    
        //$url = $this->get_page_url('data camp error', true);   
        $this->campaign->send_mail("add_camp",(int)$post_id);
		  wp_redirect($url);
	}
	public function add_campaign_lang(){
		  if ( !wp_verify_nonce( $_POST['form_nonce'], 'validate' )){
             $this->get_page_url_with_error('validation error');	
		    	 return;
			 }
		   if (!isset($_POST['post_id'])) {
             $this->get_page_url_with_error('no camp id added');	
            wp_redirect($url);
			   return;
			}

		    if(!$this->campaign->is_user_allou(get_current_user_id(),$_POST['post_id'])){
			$this->get_page_url_with_error('user_nut_allow');
		     //wp_redirect($url);
				return;
			}
			$post_id = $this->campaign->add($_POST,false);	
		if (isset($_POST['submit'])) {
	        $url = $this->get_page_url('add_bank');
			$url = add_query_arg(array( 'camp_id' => $post_id),$url);	
			}
		else{
			$url = $this->get_page_url('add_campiagn_lang_page');
			$url = add_query_arg(array( 'camp_id' => $post_id),$url);
		} 

        wp_redirect($url);
	}
}
new TZT_post_actions();