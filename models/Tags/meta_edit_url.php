<?php
namespace Donations\Tags;
use Elementor\Core\DynamicTags\Tag;
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_Meta_Edit_Url_as_Tag extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'meta-edit-url-variable';
	}

	public function get_title() {
		return __( 'Meta Edit Variable', 'elementor-pro' );
	}

	public function get_group() {
		return 'meta-variables';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
	}
protected function _register_controls() {

		
     
		$this->add_control(
			'get_name',
			[
				'label' => __( 'Page Name', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				
			]
		);
		
		
	}

	public function render() {
		$param_name = $this->get_settings( 'get_name' );

        	if ( ! $param_name ) 
			return;
        if ( $param_name  === "edit_camp_url"){
        echo add_query_arg(array('sub-page' =>"settings" ,'camp_id' => get_the_ID()),get_permalink(get_queried_object_id()));
        return;
        }
if(!isset($_GET['camp_id']))
              return;
		
$val = add_query_arg(array( 'sub-page' => $param_name , 'camp_id'=> $_GET['camp_id']),get_permalink(get_queried_object_id()));
   if($param_name === "camp_page")
	$val = get_permalink($_GET['camp_id']);
  
  // $val =  get_post_meta(get_queried_object_id(),$param_name,true);
		if ($val == ''||$val == null ) {
			//$value = $this->get_settings( 'text_if_none' );
			return;
		}
	  
		echo $val;
	}
}