<?php
namespace Donations\User;
//use Polylang;
class TZT_menge_user {   

    protected $user;
    public function __construct($user = null) {
		if(!function_exists('is_user_logged_in'))
        include(ABSPATH . "wp-includes/pluggable.php"); 
        if($user == null && is_user_logged_in())	
         $this->user = get_current_user_id();
		else $this->user = $user;
     
    }
   public function add_campiagn($post_id){
	   return $this->add_meta('campiagn_id',$post_id);
	 //  return add_user_meta( $this->user, 'campiagn_id',$post_id,false);
   }
	  public function get_campiagn(){
		return get_user_meta( $this->user, 'campiagn_id',false);
   }
	private function add_meta($key,$val){
		return add_user_meta( $this->user, $key,$val,false);
	}
	private function add_metas($metas){
		foreach ( $metas  as $key => $value){
			$this->add_meta($key,$value);
		}
		return true;
	}
    public function edit($args){
       // var_dump($args);
      //  wp_die();
        foreach ( $args  as $key => $value){
			if(substr($key,0,5) == "meta_" && $value != ''){
            update_user_meta($this->user, $key, $value);
            continue;
			}        
        }
    return $this->user;
    }
    public function add($args){
        foreach ( $args  as $key => $value){
			if(substr($key,0,5) == "meta_"){
				$user_meta[$key] = $value;
				continue;
			}
			else{
				$user_param[$key] = $value;
				}
        	}
        if(isset($args['password']))
            $pas = $args['password'];
        else $pas = wp_generate_password();
        $user_param['locale']  =   get_locale();
		$user_param['user_login'] = $args['user_email'];
        $user_param['user_pass'] = $pas;
		
		$user_param['role'] = 'campaigner';
		$user_param['show_admin_bar_front'] = false;
		$this->user = wp_insert_user($user_param);
        if(!is_wp_error($this->user))
		$this->add_metas($user_meta);
        return  $this->user;
    }

}
