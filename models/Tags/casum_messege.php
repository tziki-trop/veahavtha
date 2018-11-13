<?php
namespace Donations\Tags;
use Elementor\Core\DynamicTags\Tag;
use Donations\Pll;
use Donations\Campaign;
use \Datetime;

if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_casum_messege extends \Elementor\Core\DynamicTags\Tag {

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
		return 'casum-messege';
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
		return __( 'casum_messege', 'elementor-pro' );
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
    protected function get_time($big,$smull){
        $bigd = new DateTime(date('m/d/Y H:i:s', $big));
        $smulld = new DateTime(date('m/d/Y H:i:s', $smull));
        $interval = $bigd->diff($smulld);
        $days =  (int)$interval->format('%a');
    $time = $big - $smull;
    $res['days'] = $days;
    if($days >  0){
      $secens =  strtotime($days.' day 1 second', 0);
  $time = $time - $secens;
       }  
    $res['time'] = $time;
    return $res;
    }
	public function render() {
		$param_name = $this->get_settings( 'get_name' );

        	if ( ! $param_name ) {
			return;
		}
$camp = new \Donations\Campaign\TZT_menge_campaign();
$status =  get_post_status(get_the_ID());
$start =   $camp->get_camp_setting(get_the_ID(),'start');
$end =   $camp->get_camp_setting(get_the_ID(),'end');
$start = strtotime($start);
$end = strtotime($end);
$now = strtotime("now");
$pll = new \Donations\Pll\TZT_pll();
if("pending" === $status){
     echo $pll->get_string('Campaign pending approval');  
     return;
    }
if("successfully" === $status || "ended" === $status ){
       echo $pll->get_string('Campaign ended');
      return;
    }
if("activ" === $status){
    if($now < $start){
        $val = $pll->get_string('The campaign will start in');  
        $time = $this->get_time($start,$now);
        if($time['days'] >  0){
        $val .= " <span id='days'>".$time['days']."</span> ";
        $val .=  "<span id=\"daysstring\">".$pll->get_string('days')."</span> ";  
       }
        $val .= " <span id='timer'>".$time['time']."</span>";

} 
    else if ($now < $end){
    $time = $this->get_time($end,$now);
    $val =   $pll->get_string('The campaign ends in hours')." ";
       if($time['days'] >  0){
        $val .= " <span id='days'>".$time['days']."</span> ";
        $val .=  "<span id=\"daysstring\">".$pll->get_string('days')."</span> ";  
        }  
        $val .= " <span id='timer'>".$time['time']."</span> </p>";
}
   	echo (string)$val;
	}
    }
}