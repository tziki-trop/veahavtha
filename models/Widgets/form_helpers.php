<?php
namespace Donations\Widgets\Helpers;
use Donations\Pll;
use Donations;
class TZT_Form_Functions {
      protected $groups;
	protected $worper_class = [];
	protected $type;
	protected $currency;

   
    public function __construct($settings,$type = null) {
	 $this->currency   =   new \Donations\TZT_menge_currency();
		$this->worper_class = array("desktop" =>$settings['display_type'],
									"mobile" =>$settings['display_type_mobile'],
									"tablet" =>$settings['display_type_tablet'] );
		if($type !== null){
		$this->type = $type;
		 $this->groups = array(''=> $settings[$type] );
			
		}
		else {
			   $this->groups = array('top'=> $settings['top']
						  ,'right' => $settings['right']
						  ,'left' => $settings['left'] 
		                   ,'bottom'=> $settings['bottom']
						  );
		}
    }
	private function get_worrper_class(){
		return $this->worper_class["desktop"]." mobile_".$this->worper_class["mobile"]." tablet_".$this->worper_class["tablet"];
	}
	private function set_div_class($field,$key){
		$classs = " width".$field['filed_width'.$key]."  width_mobile".$field["filed_width".$key."_mobile"]."  width_tablet".$field["filed_width".$key."_tablet"];
		return "form_field form_type_".$field['filed_type'.$key]." ".$classs;
	}
	private function set_div($field,$key){
		return "<div class=\"".$this->set_div_class($field,$key)."\">";
	}
	private function textarea_field($field,$key){
		$html = $this->set_div($field,$key);
		$html .= "<label for=\"".$field['filed_id'.$key]."\">".$field['filed_label'.$key]."</label>";
		$html .= "<textarea rows=\"".$field['filed_rows'.$key]."\"";
		$html .= "name=\"".$field['filed_name'.$key]."\"";
		$html .= "id=\"".$field['filed_id'.$key]."\"";
		$html .= "value=\"".$field['filed_value'.$key]."\"";
		$html .= "class=\"".$field['filed_class'.$key]."\"";
		$html .= $field['required'.$key]." ".$field['readonly'.$key].">";
		$html .= $field['filed_value'.$key]."</textarea></div>";
        return $html;
	}
	private function default_field($field,$key){
    
        if($field['filed_type'] === "date" && $field['min_date'] === "min"){
            if($field['filed_name'] === "start")
            $min =  "min=\"".date('Y-m-d')."\""; 
            if($field['filed_name'] === "end")
            $min =  "min=\"".date("Y-m-d", strtotime('tomorrow'))."\"";     
        }
        else {
            $min = '';
        }
         if($field['filed_type'] === "file" && $field['filed_name'] === "galery_imgs[]")
             $multiple = " multiple";
        else $multiple = '';
        $html = $this->set_div($field,$key);
		$html .= "<label for=\"".$field['filed_id'.$key]."\">".$field['filed_label'.$key]."</label>";
		$html .= "<input type=\"".$field['filed_type'.$key]."\"";
		$html .= "name=\"".$field['filed_name'.$key]."\"";
		$html .= "id=\"".$field['filed_id'.$key]."\"";
		$html .= "value=\"".$field['filed_value'.$key]."\"";
		$html .= "class=\"".$field['filed_class'.$key]."\"";
        $html .= $min.$multiple;
		$html .= $field['required'.$key]." ".$field['readonly'.$key]."></div>";
        return $html;
	}
	private function post_field($field,$key){
		   if( isset($_GET['camp_id']))
		   return "<input type=\"hidden\" name=\"".$field['filed_name'.$key]."\""."value=\"".$_GET['camp_id']."\">";
		   else return '';
	}
	private function hidden_field($field,$key){
	    return "<input type=\"hidden\" name=\"".$field['filed_name'.$key]."\""."value=\"".$field['filed_value'.$key]."\">";
	}
	private function currency_field($field,$key){
		$currencys = $this->currency->get_all_names();
	
		        $html = $this->set_div($field,$key);
	         	$html .= "<label for=\"".$field['filed_id'.$key]."\">".$field['filed_label'.$key]."</label>";
		    	$html .= "<select name=\"currency\">";
			foreach($currencys as $currency){

				$html .= "<option value=\"".$currency['currensy_code']."\">".$currency['currensy_name']."</option>";
			}
			$html .= "</select></div>";

			return $html;
	}
	private function category_field($field,$key){
            if( isset($_GET['camp_id'])){
                 $terms = wp_get_object_terms( $_GET['camp_id'], 'category' );
                if(!is_wp_error($terms) && !empty($terms))
			     $field['filed_value'.$key] =  $terms[0]->term_taxonomy_id;
            }
		//	$post_id =  $_GET['camp_id'];
				    $terms = get_terms( $field['filed_name'.$key], array( 'hide_empty' => false, ) );
		if(empty( $terms ) ||  is_wp_error( $terms ))
			return '';
		        $html = $this->set_div($field,$key);
	         	$html .= "<label for=\"".$field['filed_id'.$key]."\">".$field['filed_label'.$key]."</label>";
		    	$html .= "<select name=\"category\"";
	        	$html .= "id=\"".$field['filed_id'.$key]."\">";
			foreach($terms as $term){
				$selected = "";
				if($field['filed_value'.$key] == $term->term_id)
				$selected = "selected";
				//$html .= var_export($term);
				$html .= "<option value=\"".$term->term_id."\" ".$selected.">".$term->name."</option>";
			}
			$html .= "</select></div>";

			return $html;
	}
	private function html_field($field,$key){
	    $html = $this->set_div($field,$key);
		$html .= "<p>".$field['filed_value'.$key]."</p>";
;		$html .= "</div>";
        return $html;
	}
	private function hidden_lang_field($field,$key){
		    return "<input type=\"hidden\" name=\"".$field['filed_name'.$key]."\""."value=\"".pll_current_language()."\">";
	
	}
	private function lang_field($field,$key){
	    $pll =  new \Donations\Pll\TZT_pll();
		if( isset($_GET['camp_id']))
			$post_id =  $_GET['camp_id'];
			
		
			else $post_id =  null;
		if(isset($_GET['sub-page']))
			$post_id =  null;
		$langs =  $pll->all_post_lang($post_id);
		$html = $this->set_div($field,$key);
		//	$html .= "<select name=\"lang\">";
			$html .= "<select name=\"lang\"";
	        	$html .= "id=\"".$field['filed_id'.$key]."\">";
	     	$selected = '';
			foreach($langs as $lang){
				if(isset($_GET['lang']) && $_GET['lang'] === $lang)
					$selected = "selected";
				$html .= "<option value=\"".$lang."\" ".$selected.">".$lang."</option>";
				$selected = '';
			}
			$html .= "</select></div>";

			return $html;
	}
     private function set_feiled($field,$key){
	   switch ($field['filed_type'.$key]) {
	  case "category":
	 return $this->category_field($field,$key);
    break;
	case "currency":
	 return $this->currency_field($field,$key);
    break;		   
    case "post":
	 return $this->post_field($field,$key);
    break;
	case "textarea":
	  return  $this->textarea_field($field,$key);
    break;
    case "lang":	
     return  $this->lang_field($field,$key);
        break;
     case "hidden_lang":	
     return  $this->hidden_lang_field($field,$key);
        break;
			   
     case "hidden":	
     return  $this->hidden_field($field,$key);
        break;
	 case "html":
		return  $this->html_field($field,$key);
        break;
    default:
    return  $this->default_field($field,$key);
			   
}	
   }
	   public function set_feileds($fields,$key,$template = false){
		   if(!$template){
		   $html = '';
		  foreach($fields as $field){
			  
			$html .=  $this->set_feiled($field,$key);
			  
		  }
		   return $html;
			   }
		   else{
		//	$classs = " width".$field['filed_width'.$key]."  width_mobile".$field["filed_width".$key."_mobile"]."  width_tablet".$field["filed_width".$key."_tablet"];
			   $html = "<# _.each( settings.".$this->type.", function( item ) { #>"; 
			   $html .= "<div class=\"form_field form_type_{{{ item.filed_type }}  width{{{ item.filed_width }}} width_mobile{{{ item.filed_width_mobile }}}  width_tablet{{{ item.filed_width_tablet}}} \">";
			//   $html .= "<# if ( item.filed_type != 'hidden' ) { #>";
			 //  $html .= $this->set_div($field,$key);
		  	$html .= "<label for=\"{{{ item.filed_id }}}\">{{{ item.filed_label }}}</label>";
		$html .= "<input type=\"{{{ item.filed_type }}}\"";
		$html .= "name=\"{{{ item.filed_name }}}\"";
		$html .= "id=\"{{{ item.filed_id }}}\"";
		$html .= "value=\"{{{ item.filed_value }}}\"";
		$html .= "class=\"{{{ item.filed_class }}}\"";
		$html .= "{{{ item.required }}} {{{ item.readonly }}} ></div>";
		$html .=   "<# }); #>";
			   //{{{ item.width }}}
			     return $html;
		   }
	  
   }
	private function set_feilds_groups(){
		      $html = '';
		     foreach($this->groups as $key => $group){
			$html .="<div class=\"fild_group  fild_group".$key." ".$this->get_worrper_class()."\">";
			$html .=  $this->set_feileds($group,$key);
			$html .= "</div>";
		  }
		   return $html;
	}
	private function set_inputs_groups($template = false){
		   $html = '';
		     foreach($this->groups as $key => $group){
			$html .="<div class=\"fild_group  fild_group_inputs ".$this->get_worrper_class()."\">";
			$html .=  $this->set_feileds($group,'',$template);
			$html .= "</div>";
		  }
		   return $html;
	}
	public function set_form(){
		   $html = "<div class=\"content post_form dis_flax\">";
           $html .= "<form action=".esc_url( admin_url('admin-post.php') )." method=\"post\">";
		   $html .= $this->set_feilds_groups(); 
	       $html .= "</form></div>";
  
          return $html;
	   }
	public function set_input_templet(){
	
    	  $html = $this->set_inputs_groups(true); 
          return $html;
	}
	public function set_inputs(){
		   $html = $this->set_inputs_groups(); 
          return $html;
	   }
}