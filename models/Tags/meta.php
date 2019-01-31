<?php
namespace Donations\Tags;
use Elementor\Core\DynamicTags\Tag;
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_Meta_as_Tag extends \Elementor\Core\DynamicTags\Tag {

	/**
	* Get Name
	*
	* Returns the Name of the tag
	*
	* @since 2.0.0
	* @access public
	*
	* @return string
	*/
	public function get_name() {
		return 'meta-variable';
	}

	/**
	* Get Title
	*
	* Returns the title of the Tag
	*
	* @since 2.0.0
	* @access public
	*
	* @return string
	*/
	public function get_title() {
		return __( 'Meta Variable', 'elementor-pro' );
	}
   
	/**
	* Get Group
	*
	* Returns the Group of the tag
	*
	* @since 2.0.0
	* @access public
	*
	* @return string
	*/
	public function get_group() {
		return 'meta-variables';
	}

	/**
	* Get Categories
	*
	* Returns an array of tag categories
	*
	* @since 2.0.0
	* @access public
	*
	* @return array
	*/
	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	/**
	* Register Controls
	*
	* Registers the Dynamic tag controls
	*
	* @since 2.0.0
	* @access protected
	*
	* @return void
	*/
	protected function _register_controls() {

		
     
		$this->add_control(
			'get_name',
			[
				'label' => __( 'Meta Name', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				
			]
		);
		
		
	}

	/**
	* Render
	*
	* Prints out the value of the Dynamic tag
	*
	* @since 2.0.0
	* @access public
	*
	* @return void
	*/
	public function render() {
		$param_name = $this->get_settings( 'get_name' );

        	if ( ! $param_name ) {
			return;
		}
 
		if($param_name === "donated" || $param_name === "amount" || $param_name === "donaters"){
      $camp   =   new \Donations\Campaign\TZT_menge_campaign();
      $donaction_amount =   $camp->get_amount_to_display(get_queried_object_id());  
	  $val =  $donaction_amount[$param_name];
			if($val === 0 || $val === "0")
	          $val = '&#48;';
			if($param_name === "donated")
		    $val = "<span id=\"amount\">".number_format($val,0)."</span>".$donaction_amount['currensy'];
            if($param_name === "donaters")  
			$val = "<span id=\"donaters\">".$val."</span>";
			if($param_name === "amount")
			$val = number_format($val,0);

//number_format($val,0)
	}
		else if($param_name === "content"){
                $content_post = get_post(get_queried_object_id());
                $content = $content_post->post_content;
                $val = apply_filters('the_content', $content);
          
        }
        else{
            $val =  get_post_meta(get_queried_object_id(),$param_name,true);
		if ($val === ''||$val === null) {
			return;		
		}
		}
		
		echo (string)$val;
	}
}