<?php
namespace Donations\Campaign;
use Donations\Cpt;
use Donations\User;
use Polylang;
use Donations;
use WP_Error;
use Donations\Email;

class TZT_menge_campaign {
    protected $cpt;
    protected $post_id;
	protected $user;
	protected $pll;
	protected $currency;
    protected $custom_meta_fields = [];
    public function __construct($post_id = null) {
         $this->currency   =   new \Donations\TZT_menge_currency();
		   $this->pll = new \Donations\Pll\TZT_pll();
        $this->post_id = $post_id;
      $this->cpt = new \Donations\Cpt\TZT_menge_cpt();
		$this->user = new \Donations\User\TZT_menge_user();
    }
    public function add_bank($args){
        $id = $this->get_camp_source($args['post_id']);
      //  add_post_meta(,'code_bank',$args['code_bank'])
       $this->cpt->update_meta($id,'code_bank',$args['code_bank']);
       $this->cpt->update_meta($id,'name_bank',$args['name_bank']);
       $this->cpt->update_meta($id,'branch_bank',$args['branch_bank']);
       $this->cpt->update_meta($id,'account_bank',$args['account_bank']);
       return $id;
    }
    public function edit_settings($args){
        $stert = strtotime($args['start']);
        $end = strtotime($args['end']);
        if($end < $stert){
        return new WP_Error( 'start_and_time_error', __( "I've fallen and can't get up", "my_textdomain" ) );
        }
        $id = $this->get_camp_source($args['post_id']);
    	foreach ( $args  as $key => $value){
            if($value === '' || $value === null)
                continue;
            $this->cpt->update_meta($id,$key,$value);
            if($key === "category"){
            $this->cpt->set_taxonomy($id, $value, 'category',true);
            $all_langs =  $this->pll->get_all_post_langs($id);
            foreach ( $all_langs  as $lang => $pis){
           $term_id = $this->pll->get_term_lang($value,$lang);
        	$this->cpt->set_taxonomy($pis, (int)$term_id, 'category',true);
            }
            }
        }
      
        return $id;        
    }
     public function get_updates($id){
     $updates_json =  $this->cpt->get($id,'update',false);
         $updates = [];
       if(is_array($updates_json) && !empty($updates_json)){
            foreach ( $updates_json  as $updat){
                if(is_array($updat))
                 array_push($updates,$updat);
               
          }
       }
           
    return $updates;
    }
   
    public function add_update($args){
     if(!array_key_exists('lang',$args)){
          return new WP_Error( 'no_lang', __( "I've fallen and can't get up", "my_textdomain" ) );  
       } 
        $update = array("time" => strtotime("now"),"update" => $args['update']) ;

           $id = $this->get_camp_source($args['post_id']);
            $all_langs =  $this->pll->get_all_post_langs($id);
            foreach ( $all_langs  as $lang => $pis){
                if($args['lang'] === $lang) 
                 $this->cpt->set($pis,'update',$update,false);
          }
     $this->send_mail("add_update",$id , array('update' => $args['update'] ));

    }
    protected function send_mail($mail_type,$post_id,$mor_dubamics = []){
        $users = json_decode($this->get_users($post_id));
		if(!is_array($users))
			return false;
                $content_post = get_post($post_id);
                $content = $content_post->post_content;
        
          $defaultOptions = array("amount" => 0,
                                  "currency" => "ILS",
                                  "update" => "",
                                   "title" => get_the_title( $post_id ),
                                   "content" => apply_filters('the_content', $content),
                                   "link" => get_permalink($post_id),
                                 );
          $dynamicData = array_merge($defaultOptions, $mor_dubamics);
    
        foreach($users as $user){
         $user_info = get_userdata( $user );
         $dynamicData['username'] =	get_user_meta($user,'meta_name', true );
	     $dynamicData['useremail'] = $user_info->user_email;
         $local =   get_user_locale( $user );
         new \Donations\Email\TZT_Email( $dynamicData['useremail'], $dynamicData, $mail_type , $local);
           }
         $blogusers = get_users( 'role=administrator' );
       foreach ( $blogusers as $user ) {
         $dynamicData['username'] =	get_user_meta($user,'meta_name', true );
	     $dynamicData['useremail'] = $user_info->user_email;
         $local =   get_user_locale( $user );
         new \Donations\Email\TZT_Email( $dynamicData['useremail'], $dynamicData, $mail_type , $local);
          }

    }
    public function edit($args){
	   if(!array_key_exists('lang',$args)){
          return new WP_Error( 'no_lang', __( "I've fallen and can't get up", "my_textdomain" ) );
         
       }
        $lang = $args['lang'];
	    $all_langs =  $this->pll->get_page_transletion_ids($args['post_id']);
	    if(!array_key_exists($lang,$all_langs)){
			
			return $this->add($args , false);
		}
            $id = $all_langs[$lang];
	   		foreach ( $args  as $key => $value){
            if($value === '' || $value === null)
					continue;
			if(substr($key,0,5) == "post_"){
			if($key === "post_content"){
				$arr = explode(" ",$value,50);
                $last = array_pop($arr);
                $post_param[$key] =  implode(" ",$arr)." <!--more--> ".$last;
				continue;
			}
				$post_param[$key] = $value;
				continue;
		
			}
			else{
				$post_meta[$key] = $value;
				}
	}
	  // foreach($post_meta as $key => $val){
     //      $this->cpt->update_meta($id,$key,$val)
    //   }
	    $post_param['meta_input'] = $post_meta;
		$post_param['post_status'] = 'pending';
		$post_param['post_type'] = 'campaign';
	    $post_param['ID'] = $id;
        wp_update_post( $post_param );
	    //$id = $this->cpt->insert($post_param);
        $thumbnail_id = media_handle_upload('img', $id );
        if(!is_wp_error($thumbnail_id))
		set_post_thumbnail( $id, $thumbnail_id ); 
        $this->uploud_img_galery($id,"galery_imgs");
	    $this->cpt->change_status($id,'pending');
         return  $id;
		  
   }
    protected function uploud_img_galery($post_id,$post_name){
     if( 'POST' == $_SERVER['REQUEST_METHOD']  ) {
    
    if ( $_FILES ) { 
    
    $files = $_FILES[$post_name];  
    foreach ($files['name'] as $key => $value) {
            if ($files['name'][$key]) { 
                $file = array( 
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key], 
                    'tmp_name' => $files['tmp_name'][$key], 
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                ); 
                $_FILES = array ($post_name => $file); 
                foreach ($_FILES as $file => $array) {   
                    $newupload = $this->my_handle_attachment($file,$post_id); 
                }
            } 
        } 
    }
//wp_die();
}
    }
    protected function my_handle_attachment($file_handler,$post_id,$set_thu=false) {
  // check to make sure its a successful upload
  if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');

  $attach_id = media_handle_upload( $file_handler, $post_id );
  if ( is_numeric( $attach_id ) ) {
    update_post_meta( $post_id, '_my_file_upload', $attach_id );
  }
  return $attach_id;
}
    public function start($id){
            $id = $this->get_camp_source($id);
            $this->send_mail("start_camp",$id);
            $all_langs =  $this->pll->get_all_post_langs($id);
            foreach ( $all_langs  as $lang => $pis){
            $this->cpt->change_status($pis,'activ');
            }
        	 $end =   $this->get_camp_setting($id,'end');
            if($end != null && $end != false && $end != '') {
             $end = strtotime($end);
           //  wp_schedule_event( time(), 'hourly', 'my_task_hook' );
            wp_schedule_single_event( $end, 'end_camp',  array($id) );

            }
                
            return true;        
   
    }
    public function end_camp($id){
        
            $id = $this->get_camp_source($id);
            $end =   $this->get_camp_setting($id,'end');
            if(strtotime($end) > strtotime("now")) {
            wp_schedule_single_event( $end, 'end_camp',  array($id) );
            return;
            }
            $amount  = $this->get_camp_setting_by_id($id,"amount",0);
            $sum  = $this->get_camp_setting_by_id($id,"donated",0);
		    if((int)$amount > (int)$sum)
                $status = 'ended' ;   
            else $status = 'successfully';
            
            $all_langs =  $this->pll->get_all_post_langs($id);
            foreach ( $all_langs  as $lang => $pis){
            $this->cpt->change_status($pis,$status);
            }
    }
    public function add($args , $first = true){
                    error_log("added started" );
		 $stert = strtotime($args['start']);
        $end = strtotime($args['end']);
        if($end < $stert){
        return new WP_Error( 'start_and_time_error', __( "I've fallen and can't get up", "my_textdomain" ) );
        }
        $lang = $args['lang'];
		$post_param = [];
		$post_meta = [];
		foreach ( $args  as $key => $value){
			if(substr($key,0,5) == "post_"){
			if($key === "post_content"){
				$arr = explode(" ",$value,50);
                $last = array_pop($arr);
                $post_param[$key] =  implode(" ",$arr)." <!--more--> ".$last;
				continue;
			}
				$post_param[$key] = $value;
				continue;
		
			}
			else{
				  $post_meta[$key] = $value;
				}
	}
        $post_param['meta_input'] = $post_meta;
		$post_param['post_status'] = 'pending';
		$post_param['post_type'] = 'campaign';
       $post_param['post_author'] = get_current_user_id();
    
        global $polylang;
        $post_id =   $this->cpt->insert($post_param);
        
        $this->pll->set($post_id, $lang );

       if($first){
		   $this->user->add_campiagn($post_id);
		   $donation_id =  $this->cpt->insert_taxonomy($post_id,$post_param['post_title']);
           if(is_wp_error($donation_id))
                  return $donation_id; 
           $this->send_mail("add_camp",$post_id);

		   $this->cpt->set_taxonomy($post_id, $post_param['meta_input']['category'], 'category',true);
		   $this->cpt->set($post_id,'donation_id',$donation_id['term_taxonomy_id']);
		   $this->cpt->set($post_id,'source','this');
		   $this->set_camp_setting($post_id,'amount',$post_meta['amount']);
		   $this->set_camp_setting($post_id,'currency',$post_meta['currency']);
		   $this->set_camp_setting($post_id,'analytics',$post_meta['analytics']);
		   $this->set_camp_setting($post_id,'facebook',$post_meta['facebook']);
		   $this->set_camp_setting($post_id,'donated',0);
		   $this->set_camp_setting($post_id,'donaters',0);
		   $this->set_camp_setting($post_id,'users',json_encode(array(get_current_user_id())));
		  // $this->set_camp_setting($post_id,'youtube',$post_meta['youtube']);
		   //analyticsget_post_meta
            
	   }
        else{
			$this->pll->add($args['post_id'], $lang ,$post_id );			
		    $term_id =  $this->cpt->get_taxonomy($args['post_id'],'category');
			$term_id = $this->pll->get_term_lang($term_id,$lang);
			$this->cpt->set_taxonomy($post_id, $term_id, 'category',true);
	        $this->cpt->set($post_id,'source',$args['post_id']);
		}
        $thumbnail_id = media_handle_upload('img', $post_id );
		set_post_thumbnail( $post_id, $thumbnail_id );
	
        return  $post_id;
    }
	protected function get_users($post_id){
		return $this->get_camp_setting($post_id,'users');
	}
	public function is_user_allou($user_id,$post_id){
		$users = json_decode($this->get_users($post_id));
		if(!is_array($users))
			return false;
		foreach($users as $user){
			if((int)$user === (int)$user_id)
				return true;
		}
		return false;
	}
	public function get_camp_setting($post_id,$settings,$def = ''){
		$source = $this->cpt->get($post_id,'source');
		if($source === "this")
			$val = $this->cpt->get($post_id,$settings);
		else $val = $this->cpt->get((int)$source,$settings);
		if($val === '' || $val === null )
   		$val = $def;
		return $val;
	}
    public function get_camp_setting_by_id($post_id,$settings,$def = ''){
	
		$val = $this->cpt->get($post_id,$settings);
		if($val === '' || $val === null || $val === false )
   		$val = $def;
		return $val;
	}
    public function get_camp_source($post_id){
		$source = $this->cpt->get($post_id,'source');
	//return $source;
		if($source === "this")
		return $post_id;
		else return (int)$source;
   		
	}
	public function get_currency($post_id){
		$currency['code'] = $this->get_camp_setting_by_id($post_id,"currency","ILS");
		$currency['name'] =   $this->currency->get_name_by_code($currency['code']);
		return $currency;
	}
	public function get_amount_to_display($post_id){
		$res = [];
		$id = $this->get_camp_source($post_id);
		$sum  = $this->get_camp_setting_by_id($id,"amount",0);
		$currency  = $this->get_camp_setting_by_id($post_id,"currency","ILS");
		$res['amount'] =  round($this->currency->convert($currency,$sum),2);
		$sum  = $this->get_camp_setting_by_id($id,"donated",0);
		$res['donated'] = round($this->currency->convert($currency,$sum),2);
		$res['donaters']  = $this->get_camp_setting_by_id($id,"donaters",0);
		$res['currensy']  = $this->currency->get_name_by_code($currency);
		 return $res;
	}
    public function set_camp_setting($post_id,$key,$val){
		$source = $this->cpt->get($post_id,'source');
		if($source === "this")
		return $this->cpt->set($post_id,$key,$val);
		else return $this->cpt->set($source,$key,$val);
   		
	}
    public function updateall($status, $meta , $user , $content, $title){
                          $post_data = array(
                 'post_content' => $content,
           'post_title'   => $title,
        'post_type' => 'campaign',
            'post_status'  => $status,
   'meta_input' => $meta,
            );
     return  $this->cpt->update($post_data);
    }
    public function get_donation_id($post_id){
     		  $id =  $this->get_camp_source($post_id);
            return $this->cpt->get($id,'donation_id');
        
    }
    public function add_donation($post_id,$amount,$currency){
		  $id =  $this->get_camp_source($post_id);
          $this->send_mail("new_donation",$id,array('amount' =>$amount, 'currency' => $currency));
		   if($currency != "ILS")
			   $amount = $this->currency->convert_to($currency,"ILS",$amount);
		//	$amount = $this->currency->convert($currency,(int)$amount);
        $sum_amount = $amount + (int)$this->cpt->get($id,'donated');
        $sum_doners = 1 + (int)$this->cpt->get($id,'donaters');
        $this->update_amount($id,$sum_amount,$sum_doners);
          return $this->cpt->get($id,'donation_id');
        
    }
    private function update_amount($post_id,$amount,$doners){
        $this->cpt->update_meta($post_id,'donaters',$doners);
        $this->cpt->update_meta($post_id,'donated',$amount);
		return;
       // $this->cpt->set('avarag',$amount / $doners);
    }
    public function remove_donation($amount){
        $sum_amount = $amount - $this->cpt->get('donated');  
        $sum_doners =  $this->cpt->get('donaters') - 1;
        $this->update_amount($sum_amount,$sum_doners);
          return true; 
        
    }
}
//new TZT_menge_campaign();