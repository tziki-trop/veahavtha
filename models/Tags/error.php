<?php
namespace Donations\Tags;
use Elementor\Core\DynamicTags\Tag;
use WP_Error;
use Donations\Pll;
//use WP_Locale_Switcher;

if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_Meta_Get_as_Tag extends \Elementor\Core\DynamicTags\Tag {

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
		return 'meta-variable-error';
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
		return __( 'Meta Variable Get', 'elementor-pro' );
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
		$this->add_control(
			'status',
			[
				'label' => __( 'status', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'error',
				'options' => [
					'error'=> __( 'error', 'client_to_google_sheet' ),
					'success' => __( 'success', 'client_to_google_sheet' ),
			],
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
		$status = $this->get_settings( 'status' );
        	if ( ! $param_name ) {
			return;
		}
		   if(!isset($_GET['status']))
		     return;
		if($_GET['status'] !== $status)
			return;
        $pll = new \Donations\Pll\TZT_pll();
   
        if($_GET['status'] === "error"){
           echo $pll->get_string($_GET['error_code']);
            return;
        }
        if($_GET['status'] === "success"){
           echo $pll->get_string($_GET['description']);
            return;
        }
   //         echo 
 //   echo  $error->get_error_message((string)$_GET['error_code']);
	//	echo (string)$_GET[$param_name];
	}
}