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
//require __DIR__ . '/ChromePhp.php';
//include 'ChromePhp.php';
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//protected $helper;
class TZT_Form_Widget extends Base_Widget {
/*public function __construct() {
    
	$this->helper = new \Donations\Widgets\Helpers\TZT_Form_Functions(); 
}*/
public function get_name() {
		return 'post_form';
	}

	public function get_title() {
		return __( 'Post Form', 'client_to_google_sheet' );
	}

	public function get_icon() {
		return 'fa fa-wpforms';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}
    protected function _register_controls() {
        		$this->start_controls_section(
					
			'feilds',
			[
				'label' => __( 'Feilds', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
	 
	    
		//$repeater = new \Elementor\Repeater();
	    $repeaters = array(
			'top'=> '',
			'right'=> '',
			'left'=>'',
			'bottom'=> ''
		);
		foreach($repeaters as  $key => $repeater){
			$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'filed_name'.$key, [
				'label' => __( 'Name', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
		//$repeater->start_popover();
        //$this->end_popover();
			
			$repeater->add_control(
			'filed_type'.$key,
			[
				'label' => __( 'Field Type', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'dynamic' =>  true,
				'default' => 'text',
				'options' => [
					'lang' => __( 'lang', 'client_to_google_sheet' ),
					'category' => __( 'category', 'client_to_google_sheet' ),
					'hidden' => __( 'hidden', 'client_to_google_sheet' ),
					'text'  => __( 'text', 'client_to_google_sheet' ),
					'textarea' => __( 'textarea', 'client_to_google_sheet' ),
					'number' => __( 'number', 'client_to_google_sheet' ),
					'email' => __( 'email', 'client_to_google_sheet' ),
                    'tel' => __( 'phone', 'client_to_google_sheet' ),
                    'select' => __( 'multybole optin', 'client_to_google_sheet' ),
                    'html' => __( 'HTML', 'client_to_google_sheet' ),
					'submit' => __( 'Submit', 'client_to_google_sheet' ),
					'file' =>__( 'File', 'client_to_google_sheet' ),
					'post' =>__( 'Post ID', 'client_to_google_sheet' ),
                   
				],
			]
		);
		$repeater->add_control(
			'filed_label'.$key, [
				'label' => __( 'Label', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
				'label_block' => true,
		   
			]
		);


		$repeater->add_control(
			'filed_value'.$key, [
				'label' => __( 'Value', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
				'dynamic'=>array('active'=>'true')
			]
		);
		$repeater->add_control(
			'filed_class'.$key, [
				'label' => __( 'Class', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
		$repeater->add_control(
			'filed_id'.$key, [
				'label' => __( 'ID', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
	     $repeater->add_control(
			'required'.$key,
			[
				'label' => __( 'Required?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'required',
				'default' => '',
			]
		);
		   $repeater->add_control(
			'readonly'.$key,
			[
				'label' => __( 'Readonly?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'readonly',
				'default' => '',
			]
		);
		 $repeater->add_responsive_control(
        	'filed_width'.$key,
			[
				'label' => __( 'Width', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'25'  => __( '25%', 'client_to_google_sheet' ),
					'33' => __( '33%', 'client_to_google_sheet' ),
					'50' => __( '50%', 'client_to_google_sheet' ),
					'75' => __( '75%', 'client_to_google_sheet' ),
					'100' => __( '100%', 'client_to_google_sheet' ),
				],
				'default' => 50,
           
				
			]
		);
	//	$repeater->end_popover();
			$this->add_control(
			$key,
			[
				'label' => $key,
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
		
				'title_field' => '{{{ filed_name'.$key.' }}}',
			]
		);
		}
		  $this->end_controls_section();
		   $this->start_controls_section(
			'label_style',
			[
				'label' => __( 'Label', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		   );
        
        	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} label',
			]
		);
		  $this->end_controls_section();
			   $this->start_controls_section(
			'feilds_style',
			[
				'label' => __( 'Feilds', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		   );
        
        	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'feilds_typography',
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} input, {{WRAPPER}} textarea, {{WRAPPER}} select ',
			]
		);
		  $this->add_control(
			'feilds_bg_color',
			[
				'label' => __( 'Background Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#e4e1e1',
				'selectors' => [	'{{WRAPPER}} input ,{{WRAPPER}} textarea,{{WRAPPER}} select' => 'background-color: {{VALUE}}',],
			]
            	);
			$this->add_control(
			'feilds_border_radius',
			[
				'label' => __( 'Border radius', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} input ,{{WRAPPER}} textarea,{{WRAPPER}} select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		  $this->end_controls_section();
		         }
    /* */
  protected function render() {
	   $settings = $this->get_settings_for_display();
	  $helper = new \Donations\Widgets\Helpers\TZT_Form_Functions($settings); 
	 echo $helper->set_form();
	//  $this->helper->set_form($settings);
	  /*
	  ?>
<div class="content post_form dis_flax">
 <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
<?php
	  foreach (  $settings['fileds'] as $item ) {
		  
		  $classs = " width".$item['filed_width']."  width_mobile".$item['filed_width_mobile']."  width_tablet".$item['filed_width_tablet'];
		  if($item['filed_type'] == "html"){
	  ?>
	 <div class="form_field form_html <?php echo $classs; ?>">
		 <p><?php echo $item['filed_value']; ?></p>
		 
	 </div>
	 <?php
			  continue;
		  }
		    if($item['filed_type'] == "category"){
			  $item['filed_type'] = "hidden";
			$item['filed_value'] =  $_GET['camp_id'];
		  }
		  if($item['filed_type'] == "post"){
			  $item['filed_type'] = "hidden";
			  //$user = new \Donations\User\TZT_menge_user();
			//  $item['filed_value'] = $user->get_campiagn();
			$item['filed_value'] =  $_GET['camp_id'];
		  }
		  if($item['filed_type'] == "lang"){
			   $pll =  new \Donations\Pll\TZT_pll();
		$langs =  $pll->all_post_lang($_GET['camp_id']);
				  ?>
			    <select name="lang">
	
	<?php	foreach($langs as $lang){ ?>
	<option value="<?php echo $lang;?>"><?php echo $lang;?></option> 
	<?php }	?>
    </select><?php
				   continue;
		  }
	 ?>
	     <div class="form_field form_type_<?php echo $item['filed_type']; ?>  <?php echo $classs; ?>">
		 <label for="<?php echo $item['filed_id']; ?>">
			 <?php echo $item['filed_label']; ?>
		
         <input type="<?php echo $item['filed_type']; ?>" 
				name="<?php echo $item['filed_name']; ?>" 
				id="<?php echo $item['filed_id']; ?>"
				value="<?php echo $item['filed_value']; ?>"
				class="<?php echo $item['filed_class']; ?>"
				<?php echo $item['required']; ?> <?php echo $item['readonly']; ?>  
				>
		 </label>
		 </div>
	 
	 <?php
		  
	  }
	  
 ?>
 </form></div>	  
<?php */
    }

    protected function _content_template(){
        ?>
    <div class="content post_form dis_flax">
	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
			<# _.each( settings.fileds, function( item ) { #>
			<div class="form_field form_type_{{{ item.filed_type }}} width{{{ item.filed_width }}}  width_mobile{{{ item.filed_width_mobile }}}  width_tablet{{{ item.filed_width_tablet }}}">
				 <label for="{{{ item.filed_id }}}">
		{{{ item.filed_label }}}
		<br>
        <input type="{{{ item.filed_type }}}" 
				name="{{{ item.filed_name }}}" 
				id="{{{ item.filed_id }}}>"
				value="{{{ item.filed_value }}}"
				class="{{{ item.filed_class }}}"
				{{{ item.required }}} {{{ item.readonly }}} 
				>
		</label>
		</div>
		<# }); #>
	</form>
	</div>
		<?php
    }
}