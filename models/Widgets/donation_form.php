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
class TZT_Donation__Widget extends Base_Widget {
/*public function __construct() {
    
	$this->helper = new \Donations\Widgets\Helpers\TZT_Form_Functions(); 
}*/
public function get_name() {
		return 'camp_donation'; 
	}

	public function get_title() {
		return __( 'donation form', 'client_to_google_sheet' );
	}

	public function get_icon() {
		return 'eicon-skill-bar';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}
    protected function _register_controls() {
           $this->start_controls_section(
					
			'widget_texts',
			[
				'label' => __( 'Texts', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
			$this->add_control(
			'button_text', [
				'label' => __( 'button_text', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
			$this->add_control(
			'other_text', [
				'label' => __( 'button_text', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
		 $this->add_control(
			'currency_befor',
			[
				'label' => __( 'Currency Befor?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'true',
				'default' => '',
			]
		);
		
		$this->end_controls_section();
		   $this->start_controls_section(
			'progres_style',
			[
				'label' => __( 'Colors', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		   );
           $this->add_control(
			'checked_color',
			[
				'label' => __( 'Checked Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#A5DC86',
				'selectors' => [	'{{WRAPPER}} .donation_form .switch-field input:checked + label' => 'background-color: {{VALUE}}',
							    '{{WRAPPER}} .donation_form input[type="number"]'=> 'background-color: {{VALUE}}',
							   ],
			]
            	);
				  $this->add_control(
			'input_color',
			[
				'label' => __( 'Input Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#A5DC86',
				'selectors' => [	'{{WRAPPER}} .donation_form .switch-field label' => 'background-color: {{VALUE}}',
							 
							   ],
			]
            	);
		
		  $this->add_control(
			'button_color',
			[
				'label' => __( 'button Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'red',
				'selectors' => [	'{{WRAPPER}} input[type="submit"]' => 'background-color: {{VALUE}}',],
			]
            	);
          
         $this->end_controls_section();
		   $this->start_controls_section(
			'input_border',
			[
				'label' => __( 'Margins', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		   );
			$this->add_control(
			'input_border_radius',
			[
				'label' => __( 'Border radius', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .donation_form .switch-field label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .donation_form input[type="number"]' =>'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
               'default' => [
                 'top' => 25,
               'right' => 25,
                'bottom' => 25,
             'left' => 25,
            'isLinked' => true,
               ],
			]
		);
				$this->add_control(
			'col1_margin',
			[
				'label' => __( 'First row', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ '%' ],
				'selectors' => [
					'{{WRAPPER}} .donation_form .switch-field .col1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'col2_margin',
			[
				'label' => __( 'Secen row', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ '%' ],
				'selectors' => [
					'{{WRAPPER}} .donation_form .switch-field .col2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'other_margin',
			[
				'label' => __( 'Secen row', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ '%' ],
				'selectors' => [
					'{{WRAPPER}} .donation_form .other_wprpper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'name' => 'text_style_typography',
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .switch-field label , {{WRAPPER}} .donation_form input[type="number"] ',
			]
		);
			  $this->add_control(
			'input_text_color',
			[
				'label' => __( 'Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#331060',
				'selectors' => [	'{{WRAPPER}} .switch-field label' => 'color: {{VALUE}}',],
			]
            	);
		
		
	
		  $this->end_controls_section();
					   $this->start_controls_section(
			'submit_style',
			[
				'label' => __( 'Submit', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		   );
        
        	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'submit_typography',
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .form_type_submit input',
			]
		);
          $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'submit_box_shadow',
				'label' => __( 'Box Shadow', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .form_type_submit input',
			]
		);
        	$this->add_responsive_control(
			'submit_padding',
			[
				'label' => __( 'submit_padding', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .form_type_submit input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'submit_color',
			[
				'label' => __( 'Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'red',
				'selectors' => [	'{{WRAPPER}} .form_type_submit input' => 'color: {{VALUE}}',],
			]
            	);
		  $this->add_control(
			'submit_bg_color',
			[
				'label' => __( 'Background Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#e4e1e1',
				'selectors' => [	'{{WRAPPER}} .form_type_submit input' => 'background-color: {{VALUE}}',],
			]
            	);
			$this->add_control(
			'submit_border_radius',
			[
				'label' => __( 'Border radius', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .form_type_submit input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
               'default' => [
                 'top' => 25,
               'right' => 25,
                'bottom' => 25,
             'left' => 25,
            'isLinked' => true,
               ],
			]
		);
		  	$this->add_control(
			'submit_hover',
			[
				'label' => __( 'Hover', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'Default', 'your-plugin' ),
				'label_on' => __( 'Custom', 'your-plugin' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();
//$this->end_popover();
		
		
			  $this->add_control(
			'submit_hover_color',
			[
				'label' => __( 'Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'red',
				'selectors' => [	'{{WRAPPER}} .form_type_submit input:hover' => 'color: {{VALUE}}',],
			]
            	);
		  $this->add_control(
			'submit_hover_bg_color',
			[
				'label' => __( 'Background Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#e4e1e1',
				'selectors' => [	'{{WRAPPER}} .form_type_submit input:hover' => 'background-color: {{VALUE}}',],
			]
            	);
			$this->add_control(
			'submit_hover_border_radius',
			[
				'label' => __( 'Border radius', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .form_type_submit input:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
               'default' => [
                 'top' => 25,
               'right' => 25,
                'bottom' => 25,
             'left' => 25,
            'isLinked' => true,
               ],
			]
		);
	    	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'submit_hover_typography',
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .form_type_submit input:hover',
			]
		);

		$this->end_popover();

		$this->end_controls_section();
		         }
    /* */
  protected function render() {
	   $settings = $this->get_settings_for_display();
	  $camp  = new \Donations\Campaign\TZT_menge_campaign();
	  $currency = $camp->get_currency(get_queried_object_id());
      if($settings['currency_befor'] === 'true'){
		  $dir = "ltr";
	
		  }
	  else {
		  $dir = "rtl";
	  }
       //  error_log( "tet" );

?>

<div class="donation_widget <?php echo $dir; ?>">
 <form class="form donation_form">
 <input type="hidden" name="action" value="add_new_donation">
	 <input type="hidden" name="currency" value="<?php echo $currency['code']; ?>">
	  <input type="hidden" name="post_id" value="<?php echo get_queried_object_id(); ?>">
    <div class="switch-field flex">
   <div class="col1">
      <input type="radio" id="fix_amount_20" name="fix_amount" value="20" checked/>
      <label class="donation_label" for="fix_amount_20">20<?php echo $currency['name']; ?></label>
      </div>
		 <div class="col2">
      <input type="radio" id="fix_amount_50" name="fix_amount" value="50" />
      <label class="donation_label" for="fix_amount_50">50<?php echo $currency['name']; ?></label>
       </div><div class="col1">
            <input type="radio" id="fix_amount_100" name="fix_amount" value="100" />
      <label  class="donation_label" for="fix_amount_100">100<?php echo $currency['name']; ?></label>
       </div><div class="col2">
      <input type="radio" id="fix_amount_200" name="fix_amount" value="200">
      <label  class="donation_label" for="fix_amount_200">200<?php echo $currency['name']; ?></label>
      </div>
		<div class="other_wprpper">
       <input  type="radio" id="fix_amount_other" name="fix_amount" value="">
       <input type="number" class="other hidden" type="text" name="other_amount" />
  <label class="donation_label donation_form_other" for="fix_amount_other">
			<?php echo $settings['other_text']; ?>
			</label>
     </div>
    </div>
	  <div class="form_type_submit">
    <input id="add_donation" type="submit" value="<?php echo $settings['button_text']; ?> "> 
	 </div>
    </form>
	</div>
<?php
    }

 protected function _content_template(){ 
	 ?>
<div class="donation_widget">
 <form class="form donation_form">
 <input type="hidden" name="action" value="add_new_donation">
    <div class="switch-field flex">
   <div class="col1">
      <input type="radio" id="fix_amount_20" name="fix_amount" value="20" checked/>
      <label class="donation_label" for="fix_amount_20">20</label>
      </div>
		 <div class="col2">
      <input type="radio" id="fix_amount_50" name="fix_amount" value="50" />
      <label class="donation_label" for="fix_amount_50">50</label>
       </div><div class="col1">
            <input type="radio" id="fix_amount_100" name="fix_amount" value="100" />
      <label  class="donation_label" for="fix_amount_100">100</label>
       </div><div class="col2">
      <input type="radio" id="fix_amount_200" name="fix_amount" value="200">
      <label  class="donation_label" for="fix_amount_200">200</label>
      </div>
       <input  type="radio" id="fix_amount_other" name="fix_amount" value="">
       <input type="number" class="other hidden" type="text" name="other_amount" />
  <label class="donation_label donation_form_other" for="fix_amount_other">other</label>
     

    </div>
	 <div class="form_type_submit">
    <input type="submit">
		 </div>
    </form>
	</div>
<?php
 }
}