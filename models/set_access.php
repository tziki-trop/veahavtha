<?php
namespace Donations\Accsess;
use Donations;

		  
class TZT_set_access {
  protected $pll;
    public function __construct() {
$this->add_actions();
$this->pll = new \Donations\Pll\TZT_pll();
    }
	public function add_actions(){
	
		add_action( 'wp', [$this,'set_access'] );

    }
	protected function get_user_role(){
		if( is_user_logged_in() ) {
    $user = wp_get_current_user();
    $role = ( array ) $user->roles;
    return $role[0];
  } else {
    return false;
  }
	}
	protected function get_diabold_pages(){
		return array(get_option('add_campiagn_lang_page'),
					 get_option('add_bank'),
					 get_option('account_page'),
					 get_option('add_campiagn_page'),
					 get_option('user_page')
					 );

		
	}
	protected function cpt_users($post_id){
		$camp = new \Donations\Campaign\TZT_menge_campaign();
		return $camp->is_user_allou(get_current_user_id(),$post_id);
	
	}
	protected function is_page_allow($page_id){
				foreach($this->get_diabold_pages() as $page){
				if($page_id === (int)$page)
					return $page;
				$langs = $this->pll->get_page_transletion_ids((int)$page);
				foreach($langs as $lang){
						if($page_id === (int)$lang)
					    return $page;
				}
			}
		return true;
	}
	public function set_access(){
        
		$role = $this->get_user_role();
		if($role === "administrator")
			return;
		if(is_page()){
			$page_id = get_queried_object_id();
			$is_page_allow =  $this->is_page_allow($page_id);
			if($is_page_allow === true)
				return;
				if(is_user_logged_in() && (int)$is_page_allow === (int)get_option('account_page'))
				return;
	    	if(!is_user_logged_in() && (int)$is_page_allow === (int)get_option('user_page'))
				return;
			else if((int)$is_page_allow === (int)get_option('user_page')){
				   $url = get_permalink($this->pll->get_post_lang((int)get_option('add_campiagn_page')));
	            	wp_redirect($url);
				    return;
			}
			
			if($role === 'campaigner' && (int)$is_page_allow === (int)get_option('add_campiagn_page'))
				return;
			if (isset($_GET['camp_id']) && $this->cpt_users($_GET['camp_id']))
				return;
		   if(is_user_logged_in()){
			 $url = get_permalink($this->pll->get_post_lang((int)get_option('account_page')));	    
	    	}
			else{
	   $url = get_permalink($this->pll->get_post_lang((int)get_option('login_page')));
}
		wp_redirect($url);
           // $numArray = array_map('intval', get_user_meta(get_current_user_id(),'campiagn_id',false));
 
     //   var_dump($arr);
  
		
		}

}
		
}
new TZT_set_access();