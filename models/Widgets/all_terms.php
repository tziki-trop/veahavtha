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
class TZT_Terms_Widget extends Base_Widget {
/*public function __construct() {
    
	$this->helper = new \Donations\Widgets\Helpers\TZT_Form_Functions(); 
}*/
public function get_name() {
		return 'all_terms'; 
	}

	public function get_title() {
		return __( 'All Category\'s', 'client_to_google_sheet' );
	}

	public function get_icon() {
		return 'fa fa-file-text';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}
	protected function _register_controls() {
		  		$this->start_controls_section(
					
			'all_content',
			[
				'label' => __( 'Content', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
				$this->add_control(
			'category', [
				'label' => __( 'Category', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
		     $this->add_control(
			'carusel',
			[
				'label' => __( 'carusel?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'carusel',
				'default' => '',
			]
		);
		  	 	$this->add_responsive_control(
			'posts_per_row', [
				'label' => __( 'posts per row', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'show_label' => true,
				'default' => 3,
			]
		);
		 
		
		 
		$this->end_controls_section();
		  $this->start_controls_section(
			'style',
			[
				'label' => __( 'Style', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		   );
				$this->add_control(
			'img_width', [
				'label' => __( 'Img width', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'show_label' => true,
			]
		);
				$this->add_control(
			'element_margin', [
				'label' => __( 'element margin', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'show_label' => true,
			]
		);
		//element_margin
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .category_name',
			]
		);
			$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'icon_size', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 15,
						'max' => 150,
						'step' => 5,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .flex-center-vertically i ' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		//img_width
	}
  
  protected function render() {
	$settings = $this->get_settings_for_display();
	    $id = $this->get_id();
	    if($settings['carusel'] === 'carusel'){
			       $data = [];
				 //  $id = $this->get_id();
				   $carusel = true;
				   $query_ppp  = -1;
				   $carusel_div = "owl-carousel owl-theme first\" data-carusel=\"carusel_".$id;
	
				   $data = [];
				   $data['settings'] = array('nav' => true,
											'responsive'=> array('desktop' => $settings['posts_per_row'],
																'mobile' => $settings['posts_per_row_mobile'],
															   'tablet' => $settings['posts_per_row_tablet'] ),
											 'margin' => $settings['element_margin']
											);
			   }
 
   $terms = get_terms( $settings['category'], array( 'hide_empty' => false,) );
	  $arrterms = [];
	  

	 // $data['items_parts'] = array('hide_empty' => false)
	  ?>
<div class="all_terms <?php echo $carusel_div; ?>">

	<?php
	  $index = 0;
	  foreach($terms as $term){	
	   
        $image_id = get_term_meta ( $term-> term_id, 'category-image-id', true ); 
         if ( $image_id ) { 
		//	 if($index < $iten_incrusel ){
			 ?>
	     <a class="item"  href="<?php echo get_term_link($term); ?>" data-item_num="<?php echo $index;?>" >
		<div class="category_one_image_wrapper">
		<?php  echo wp_get_attachment_image ( $image_id, array($settings['img_width'],$settings['img_width']) ); ?>
			
	    </div>
		<div>
			<p class="category_name"><?php echo str_replace(" ","<br>",$term -> name); ?></p>
		</div>
	</a>
	<?php	
				 $index++;
			// }
		
	$img_src = wp_get_attachment_image_src( $image_id, array($settings['img_width'],$settings['img_width']) );
?>

	<?php

	    } 
	  } 

  wp_localize_script( 'widget_js', 'carusel_'.$id, $data ); 
	 ?>
</div>
<?php
	
    }

    protected function _content_template(){ 

    }
}