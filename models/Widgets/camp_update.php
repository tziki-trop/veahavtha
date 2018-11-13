<?php
namespace Donations\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use ElementorPro\Base\Base_Widget;
use Donations\User;
use Donations\Pll;
use Donations\Widgets\Helpers;
use Donations\Campaign;
//use Donations;
//get_amount_to_display
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//protected $helper;
class TZT_camp_update extends Base_Widget {
/*public function __construct() {
    
	$this->helper = new \Donations\Widgets\Helpers\TZT_Form_Functions(); 
}*/
public function get_name() {
		return 'camp_update'; 
	}

	public function get_title() {
		return __( 'camp_update', 'client_to_google_sheet' );
	}

	public function get_icon() {
		return 'eicon-skill-bar';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}
    protected function _register_controls() {
    	   $this->start_controls_section(
			'update_style',
			[
				'label' => __( 'Colors', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		   );
           $this->add_control(
			'date_color',
			[
				'label' => __( 'date Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#A5DC86',
				'selectors' => [	'{{WRAPPER}} .date p' => 'color: {{VALUE}}',],
			]
            	);
				  $this->add_control(
			'date_bg_color',
			[
				'label' => __( 'date bg Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#A5DC86',
				'selectors' => [	'{{WRAPPER}} .date' => 'background-color: {{VALUE}}',
							 
							   ],
			]
            	);
		$this->add_control(
			'update_color',
			[
				'label' => __( 'bg Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'red',
				'selectors' => [	'{{WRAPPER}} .update p' => 'color: {{VALUE}}',],
			]
            	);
		  $this->add_control(
			'bg_color',
			[
				'label' => __( 'bg Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'red',
				'selectors' => [	'{{WRAPPER}} .update' => 'background-color: {{VALUE}}',],
			]
            	);
          
         $this->end_controls_section();
		   $this->start_controls_section(
			'my_border',
			[
				'label' => __( 'Margins', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		   );
            $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ell_border',
				'label' => __( 'Border', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .one_update',
			]
		);
			
				$this->add_control(
			'box_margin',
			[
				'label' => __( 'box_margin', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ '%' ],
				'selectors' => [
					'{{WRAPPER}} .one_update' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
               'default' => [
                 'top' => 0,
               'right' => 0,
                'bottom' => 0,
             'left' => 0,
            'isLinked' => false,
               ],
			]
		);
			$this->add_control(
			'box_padding',
			[
				'label' => __( 'box_padding', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ '%' ],
				'selectors' => [
					'{{WRAPPER}} .one_update' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
               'default' => [
                 'top' => 0,
               'right' => 0,
                'bottom' => 0,
             'left' => 0,
            'isLinked' => false,
               ],
			]
		);
			$this->add_control(
			'date_margin',
			[
				'label' => __( 'date margin', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ '%' ],
				'selectors' => [
					'{{WRAPPER}} .date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
               'default' => [
                 'top' => 0,
               'right' => 0,
                'bottom' => 0,
             'left' => 0,
            'isLinked' => false,
               ],
			]
		);
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
				'name' => 'date_typ',
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .date p',
			]
		);
			 
	
        	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'update_typography',
				'label' => __( 'update_typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .update p',
			]
		);
       $this->end_controls_section();
		         }
    /* */
  protected function render() {
	   $settings = $this->get_settings_for_display();
	   $camp   =   new \Donations\Campaign\TZT_menge_campaign();
       

	   $id = get_the_ID();
       $updates = $camp->get_updates($id);
       if(!is_array($updates))
         return;
      ?>
<div class="updates_widget">
    <?php
	 if(empty($updates)){     
         $pll   =   new \Donations\Pll\TZT_pll();
         echo $pll->get_string("No update yet");
         echo "</div>";
         return;
     }
        
	  

      
      date_default_timezone_set('Asia/Jerusalem');
 //  var_dump($updates);
      foreach($updates as $update){
        $time =  date ('m.d.y  h:i',$update['time'] );
        $update = $update['update'];
      
    ?>
<div class="one_update">
<div class="date"><p><?php echo $time ?></p></div>
<div class="update"><p><?php echo $update ?></p></div>

</div>
    
       
	
<?php
  }
      
      ?></div> <?php
    }

 protected function _content_template(){ 
	 ?>
<div class="one_update">
<div class="date"><p><?php echo date ('m.d.y  h:i' ) ?></p></div>
<div class="update"><p><?php echo "this id a test update" ?></p></div>

</div>

<?php
 }
}