<?php
namespace Donations\Pll;
use Polylang;
class TZT_pll {
  protected $strings;
    public function __construct() {
		
    } 
    public function reg_string(){
        $this->set_array_strings();
        foreach($this->strings as $string){
        pll_register_string('elementor-hello-theme', $string);
        }
    } 
    public function get_string($str, $print = false){
       // if($print)        
        return pll__($str);
    }
    
    public function get_string_array(){
        return $this->strings;
    }
    protected function set_array_strings(){
        $this->strings = array(
            'one payment',
            'multy payment',
            'payments',
            'No campaign yet',  
            'No update yet',
            'pending',
            'activ',
            'successfully',
            'ended',
            'camp_wating_approvel',
            'update_added',
            'empty_user_login',
            'existing_user_login',
            'start_and_time_error',
            'no_lang',
            'user_nut_allow',
            'no_post_id',
            'no_validate',
        'The campaign ends in hours',
        'days',
        'Campaign pending approval',
        'The campaign will start in',
        'Campaign ended successfully',
        'Campaign ended',
        'hours',
        'error',
        'no user', 
        'no email',
        'validation error',
        'the camp is peanding aprovel',
        'user nut allow',
        'no importent data'
        );
    }
	public function set($post_id, $lang ){
		global $polylang;
		PLL()->model->post->set_language($post_id, $lang );
		
	}
	public function add($main,$lang,$secen){
		global $polylang;
	PLL()->model->post->save_translations( $main, array($lang => $secen) );
	}
	public function all(){
		return pll_languages_list();
	}
    public function current_lang(){
		return pll_current_language();
	}
	public function get_page_transletion_ids($post_id){
	 global $polylang;
	$translationIds =	PLL()->model->post->get_translations($post_id);
		return $translationIds;
	}
	public function get_page_transletion_id_by_lang($post_id){
	 global $polylang;
	 $translationIds =	PLL()->model->post->get_translations($post_id );
	
		return $translationIds;
	}
	public function all_lang($post_id){
			  global $polylang;
	$translationIds =	PLL()->model->post->get_translations($post_id);
   // $translationIds = $polylang->model->get_translations('post', $post_id);
    $currentLang = pll_get_post($post_id, pll_current_language());
    $all_lang = [];
    foreach ($translationIds as $key=>$translationID){
        if($translationID != $currentLang){
            $availableLang = $polylang->model->get_languages_list();
            foreach( $availableLang as $lang){
                if($key != $lang->slug){
					$all_lang[$key] =  $translationID;
            
                }
            }
        }
    }

    return $all_lang;
	}
	public function get_term_lang($term_id,$lang){
		
		global $polylang;
		return pll_get_term(  $term_id,  $lang );
	}
	public function get_post_lang($post_id,$lang = true){
		if($lang === true)
		$lang	= pll_current_language();
		global $polylang;
		return pll_get_post(  $post_id,  $lang );
	}
	public function get_all_post_langs($post_id){
    global $polylang;
    return PLL()->model->post->get_translations($post_id);
     
    }
	public function all_post_lang($post_id = null){
		  global $polylang;
    $translationIds =	PLL()->model->post->get_translations($post_id);
    $currentLang = pll_get_post($post_id, pll_current_language());
	$availableLang = $polylang->model->get_languages_list();
    $all_lang = [];
		 foreach( $availableLang as $lang){
			 if($lang->slug == $currentLang)
				 continue;
			    if (empty($translationIds[$lang->slug]))
                 $all_lang[$lang->slug] =  $lang->slug;
		 }
		return $all_lang;
	}
	public function get($post_id){
	return pll_get_post_language($post_id);
	}
	
}