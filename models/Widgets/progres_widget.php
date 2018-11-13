<?php
namespace Donations\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use ElementorPro\Base\Base_Widget;
use Donations\User;
use Donations\Pll;
use Donations\Widgets\Helpers;
use Donations\Campaign;
use Donations;
//get_amount_to_display
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//protected $helper;
class TZT_Progres_Widget extends Base_Widget {
/*public function __construct() {
    
	$this->helper = new \Donations\Widgets\Helpers\TZT_Form_Functions(); 
}*/
public function get_name() {
		return 'camp_progres'; 
	}

	public function get_title() {
		return __( 'camp progres', 'client_to_google_sheet' );
	}

	public function get_icon() {
		return 'eicon-skill-bar';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}
    protected function _register_controls() {
    /*<div class="progres_worrper">
  
    <p>55%</p>
  
    <div class="progres_pr"></div>
</div>*/
		   $this->start_controls_section(
			'progres_style',
			[
				'label' => __( 'Colors', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		   );
        		  $this->add_control(
			'progres_worrper_color',
			[
				'label' => __( 'Worpper Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#331060',
				'selectors' => [	'{{WRAPPER}} .progres_worrper' => 'background-color: {{VALUE}}',],
			]
            	);
		  $this->add_control(
			'progres_pr_color',
			[
				'label' => __( 'Progres Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#e4e1e1',
				'selectors' => [	'{{WRAPPER}} .progres_pr' => 'background-color: {{VALUE}}',],
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
				'name' => 'text_style_typography',
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} p',
			]
		);
			  $this->add_control(
			'input_text_color',
			[
				'label' => __( 'Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#331060',
				'selectors' => [	'{{WRAPPER}} p' => 'color: {{VALUE}}',],
			]
            	);
		 	$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .progres_worrper',
			]
		);
	
		  $this->end_controls_section();
		         }
    /* */
  protected function render() {
	   $settings = $this->get_settings_for_display();

        $camp   =   new \Donations\Campaign\TZT_menge_campaign();
	  $id = get_the_ID();
  //  var_dump($camp->get_updates($id));
	 
      $donaction_amount =   $camp->get_amount_to_display($id);  
	  $amount =  $donaction_amount['amount'];
	  $donated =  $donaction_amount['donated'];
	  $doners =  $donaction_amount['donaters'];

	  if($amount === 0 || $donated === 0){
		 $procent = 0;
          $procent_width = 0;
		  }
	  else { 
          $procent = $donated / $amount * 100;
            if($procent > 100)
                $procent_width = 100;
          else $procent_width = $procent;
            }

?>
<div class="progres_worrper">
  
        <p><span id="pro_amount"><?php echo round($procent, 2); ?></span>%</p>
  
    <div class="progres_pr" style="width: <?php echo round($procent_width, 2); ?>%;"></div>
</div>
<?php
    }

 protected function _content_template(){ 
	 ?>
<div class="progres_worrper">
  
        <p>55%</p>
  
    <div class="progres_pr"></div>
</div>
<?php
 }
}