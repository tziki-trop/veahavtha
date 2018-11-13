<?php
namespace Donations\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use ElementorPro\Base\Base_Widget;
use Donations\Campaign;
use WP_Query;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//protected $helper;
class TZT_Doners_Widget extends Base_Widget {

public function get_name() {
		return 'doners_widget'; 
	}

	public function get_title() {
		return __( 'doners widget', 'client_to_google_sheet' );
	}

	public function get_icon() {
		return 'fa fa-file-text';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}
     protected function _register_controls() {
       $this->end_controls_section();
		   $this->start_controls_section(
			'text_style',
			[
				'label' => __( 'Text', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		   );
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} p',
			]
		);
           $this->end_controls_section();
		
    }
    protected function set_query($id){
       // use Donations\Campaign;
global $wp_query;
        $camp = new \Donations\Campaign\TZT_menge_campaign();
        $donation_id = $camp->get_donation_id($id);
        //var_dump($donation_id);
                 $args=array(
						 		'post_type' => 'donations',
						 		'post_status' => 'publish',
						 	    'posts_per_page' => 3,
						 		   'tax_query' => array(
								'relation' => 'AND',
                                array(
                               'taxonomy' => 'organization',
                               'terms' => (int)$donation_id),		 
            )//post__not_in
		 );
        $my_query = new WP_Query($args);
        $query = $this->get_query($my_query);
        $data['ids'] = $query['ids'];
        $data['date'] = $query['date'];
        $data['term'] = (int)$donation_id;
        $data['pid'] = $id;
        wp_reset_query();
        wp_localize_script( 'widget_js', 'donations', $data ); 
	
    }
    protected function get_query($my_query){
            if( $my_query->have_posts() ){
                $ids = [];
        	?>

            <div class="all_donation"> 
             <?php  
            $date = '';
            $index = 0;
            while ($my_query->have_posts()) : $my_query->the_post(); 
             elementor_theme_do_location( 'doners' );
            array_push($ids,get_the_ID());
            if($index === 0)
            $date = get_post_time();
            $index++; 
            endwhile;
                ?> </div><?php
                  return array('ids' =>$ids,'date' => $date);
        
            }
          else return false;
		
    }
     protected function render() {
         $id = get_queried_object_id();
      //   $button_link = add_query_arg(array('sub-page' =>"edit_camp" ,'camp_id' => get_the_ID()),get_permalink(get_queried_object_id()));
 //var_dump($id);
	   $this->set_query($id);

    }
}
