<?php
namespace Donations\Tags;
use Donations\Pll;
use Elementor\Core\DynamicTags\Tag;
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_Meta_by_id_Tag extends \Elementor\Core\DynamicTags\Tag {

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
		return 'meta-variable-by-get-id';
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
		return __( 'Edit Post Variable', 'elementor-pro' );
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
        if(!isset($_GET['camp_id']))
            return;
			$id = (int)$_GET['camp_id'];
		    if(isset($_GET['lang'])){
				 $pll =  new \Donations\Pll\TZT_pll();
				 $ids =  $pll->get_page_transletion_id_by_lang($id);
				 if(array_key_exists($_GET['lang'],$ids))
				 $id = $ids[$_GET['lang']];
				 else return;
			}
     switch ($param_name) {
    case 'title':
       $val = get_the_title($id);
        break;
     case 'content':
				//$my_postid = 12;//This is page id or post id
                $content_post = get_post($id);
                $content = $content_post->post_content;
                $val = apply_filters('the_content', $content);
            break;
			case 'status':
                $pll = new \Donations\Pll\TZT_pll();
				$val  = get_post_status( $id);
                $val = $pll->get_string($val);  
       break;
            default:
               $val =  get_post_meta($id,$param_name,true);
                break;
}
 
		echo wp_strip_all_tags($val);
	}
}