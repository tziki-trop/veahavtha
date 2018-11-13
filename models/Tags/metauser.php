<?php
namespace Donations\Tags;
use Elementor\Core\DynamicTags\Tag;
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_Meta_user_as_Tag extends \Elementor\Core\DynamicTags\Tag {

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
		return 'user-meta-variable';
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
		return __( 'User Meta', 'elementor-pro' );
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
	 $val =	get_user_meta(get_current_user_id(),$param_name, true );
  // $val =  get_post_meta(get_queried_object_id(),$param_name,true);
		if ($val == ''||$val == null ) {
			//$value = $this->get_settings( 'text_if_none' );
			return;
		}
	  
		echo $val;
	}
}