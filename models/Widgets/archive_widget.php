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
use Donations\User; 
use Donations\Pll;
use Donations;
use Donations\Widgets\Helpers;
use WP_Query;
//require __DIR__ . '/ChromePhp.php';
//include 'ChromePhp.php';
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//protected $helper;
class TZT_Archive_Widget extends Base_Widget {
/*public function __construct() {
    
	$this->helper = new \Donations\Widgets\Helpers\TZT_Form_Functions(); 
}*/
public function get_name() {
		return 'archive_widget'; 
	}

	public function get_title() {
		return __( 'archive_widget', 'client_to_google_sheet' );
	}

	public function get_icon() {
		return 'fa fa-file-text';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}
	 protected function _register_controls() {
		 	$this->start_controls_section(
					
			'content',
			[
				'label' => __( 'Content', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
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
		     $this->add_control(
			'current_query',
			[
				'label' => __( 'Current query?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
   
         	$this->add_control(
			'camp_status',
			[
				'label' => __( 'Show Elements', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => [
					'publish'  => __( 'publish', 'plugin-domain' ),
					'pending' => __( 'pending', 'plugin-domain' ),
					'activ' => __( 'activ', 'plugin-domain' ),
					'ended' => __( 'ended', 'plugin-domain' ),
					'successfully' => __( 'successfully', 'plugin-domain' ),
				
                ],
				'default' => [ 'publish', 'activ' ],
			]
		);
            $this->add_control(
			'user_query',
			[
				'label' => __( 'user query?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'yes',
			]
		);
         //
		 	 	$this->add_responsive_control(
			'posts_per_page', [
				'label' => __( 'posts per page', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'show_label' => true,
				'default' => 3,
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
		 
		
		 //posts_per_page
		 $this->end_controls_section(); 
		 	$this->start_controls_section(
					
			'texts',
			[ 
				'label' => __( 'Text\'s', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		  	$this->add_control(
			'button_text', [
				'label' => __( 'Button text', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
		  	$this->add_control(
			'doners', [
				'label' => __( 'Doners', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
		  	$this->add_control(
			'amount', [
				'label' => __( 'Amount', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
		 $this->end_controls_section(); 
		   	$this->start_controls_section(
					
			'conteiner',
			[
				'label' => __( 'Conteiner', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		  $this->add_control(
			'conteiner_bg_color',
			[
				'label' => __( 'Background Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [	'{{WRAPPER}} .one_post_worrper' => 'background-color: {{VALUE}}',],
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
						'min' => 30,
						'max' => 100,
						'step' => 5,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .category_image_wrapper img' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .category_image_wrapper img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		 $this->end_controls_section();
		  	$this->start_controls_section(
					
			'img',
			[
				'label' => __( 'Image', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		 	$this->add_responsive_control(
			'img_height',
			[
				'label' => __( 'Height', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 300,
						'step' => 5,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'selectors' => [
					'{{WRAPPER}} .post_img img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		 $this->end_controls_section();
		  	$this->start_controls_section(
					
			'row',
			[
				'label' => __( 'Rows', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		 		$this->add_control(
			'row_margin',
			[
				'label' => __( 'Spase betein lines', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
               'default' => [
                 'top' => 10,
               'right' => 0,
                'bottom' => 10,
             'left' => 0,
            'isLinked' => false,
               ],
			]
		);
	//row

		$this->end_controls_section();
		 	  	$this->start_controls_section(
					
			'status_text_style',
			[
				'label' => __( 'Status Text', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
 	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'p_h_typography',
				'label' => __( 'Typography Titles', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .camp_status p.title',
			]
		);//content title
		 	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'p_c_typography',
				'label' => __( 'Typography Titles', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .camp_status p.content',
			]
		);
$this->end_controls_section();
		 		 	 	  	$this->start_controls_section(
					
			'button_text_style',
			[
				'label' => __( 'Button Text', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
 	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 
				'name' => 'post_button_typography',
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .post_button .elementor-button-text , {{WRAPPER}} .post_button .elementor-button-icon i',
			]
		);
		 	 		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'border radius', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .post_button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					
			'content_text_style',
			[
				'label' => __( 'Content Text', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
 	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_h_typography',
				'label' => __( 'Typography Titles', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .post_title',
			]
		);//content title
		 	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_c_typography',
				'label' => __( 'Typography Titles', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .post_content',
			]
		);
$this->end_controls_section();
		 	$this->start_controls_section(
					
			'progres',
			[
				'label' => __( 'Color\'s', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		       $this->add_control(
			'fill_color',
			[
				'label' => __( 'fill_color', 'popuplabels' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'red',
				'selectors' => [	'{{WRAPPER}} .post_button a' => 'background-color: {{VALUE}}',							   ],
			]
            	);
		        $this->add_control(
			'inside_text_color',
			[
				'label' => __( 'inside_text_color', 'popuplabels' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'green',
			     'selectors' => [	'{{WRAPPER}} .post_button .elementor-button-text' => 'color: {{VALUE}}',
								'{{WRAPPER}} .post_button .elementor-button-icon i' => 'color: {{VALUE}}',
								 '{{WRAPPER}} .camp_status .content' => 'color: {{VALUE}}'],
		
				//elementor-button-text
			]
            	);
		 $this->end_controls_section();
		  	$this->start_controls_section(
					
			'cat_titls',
			[
				'label' => __( 'cat_titls\'s', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		       $this->add_control(
			'background_color_title',
			[
				'label' => __( 'background_color_title', 'popuplabels' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'red',
				'selectors' => [	'{{WRAPPER}} .all_posts_cat .category_name' => 'background-color: {{VALUE}}',							   ],
			]
            	);
		        $this->add_control(
			'text_color_title',
			[
				'label' => __( 'inside_text_color', 'popuplabels' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'green',
			     'selectors' => [	'{{WRAPPER}} .all_posts_cat .category_name' => 'color: {{VALUE}}']
			]
            	);
		 	 	$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'inside_text_Typography',
				'label' => __( 'Typography Titles', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}}  .all_posts_cat .category_name',
			]
		);
		$this->add_responsive_control(
			'icon_nov_size', [
				'label' => __( 'Icon size', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'show_label' => true,
				'default' => 16,
                'selectors' => [
					'{{WRAPPER}} .owl-nav' => 'font-size: {{SIZE}}px;',  
                ],
                
			]
		);
        $this->add_control(
			'icon_color',
			[
				'label' => __( 'Icons Color', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .owl-nav' => 'color: {{VALUE}}',
				],
             
			]
		);   
		 $this->end_controls_section();
	 }
  protected function set_query(){
      global $wp_query;

      $settings = $this->get_settings_for_display();

      $edit = false;
	  if($settings['current_query'] === 'yes'){
		  /// arcive post
		    
		   $my_query = $wp_query;
		  if(is_archive()){
			  $my_query = [];
			    $terms = get_terms('category', array( 'hide_empty' => false,) );

                  foreach($terms as $term){	
             //                $term = get_term( $term->term_id, 'category' ); 
            //   echo $count = $term->count;

			         $args=array(
						 		'post_type' => 'campaign',
                       //  'post_status' => 'any',

						 		'post_status' => $settings['camp_status'],
						 			'posts_per_page' => -1,
						 		   'tax_query' => array(
									   		'relation' => 'AND',
                           array(
                               'taxonomy' => 'category',
                               'terms' => $term->term_id,
                           ),	 
            )
		 );
		  array_push($my_query,array('query' => new WP_Query($args),'term' => $term ) );
             //  var_dump(	$terms);
		  }
		  }
		  	/// category post
		   if(is_category()){
			       $args=array(
                       'post_type' => 'campaign',
                       'post_status' => $settings['camp_status'],
					   'tax_query' => array(
                       'relation' => 'AND',
                           array(
                               'taxonomy' => 'category',
                               'terms' => get_queried_object_id(),
                           ),
			 
            ));
			 //tax_query
			  $my_query = new WP_Query($args);
		 }
	  }
	  /// castum post
      
	  else if($settings['user_query'] === 'yes'){
	      $user_post = get_user_meta(get_current_user_id(),'campiagn_id',false);
               $pids = [];
        foreach ($user_post as $pid) {
            array_push($pids,(int)$pid);
        }
	      $args=array(
         'post_type' => 'campaign',
         'post_status' => $settings['camp_status'],
         'lang' => '',
         'post__in' => $user_post,
		 'meta_query' => array(
	'relation' => 'AND',
	array(
		'key' => 'source',
		 'value' => 'this',
		 'compare' => 'LIKE',
	),)
		 ); 
          $edit = true;
        //  wp_reset_query();
         $my_query = new WP_Query($args);
        //	var_dump($my_query);
    }
      else{
	
	      $args=array(
           'post_type' => 'campaign',
			'post_status' => $settings['camp_status'],
			  'posts_per_page' => 6,
		 );
		  
          $my_query = new WP_Query($args);

 
	  }
	  
	  
	  /// get juery
	  if(!is_array ($my_query))
      $this->get_query($my_query,$settings,$edit);
	  else {
		  ?>
<div class="all_posts_cat">
<?php
		   
		  foreach($my_query as $query){
		        if( !$query['query']->have_posts() ){
					if(count ($my_query) === 1){
						$pll = new \Donations\Pll\TZT_pll();
						echo $pll->get_string('No campaign yet');
					}
					continue;
				}
			   $image_id = get_term_meta ( $query['term']->term_id, 'category-image-id', true ); 
			  		  ?>
	           <div class="cat all_post">
				<div class="img_worper">
				<?php  echo wp_get_attachment_image ( $image_id, array(100,100) ); ?>
				</div><div class="text_worper">
				<p class="category_name"><?php echo $query['term'] -> name; ?></p>
				</div></div>
                <?php
			  //  $image_id = get_term_meta ( $term-> term_id, 'category-image-id', true ); 
			    $this->get_query($query['query'],$settings,$edit);
		  }
		  		  ?>
			</div>
			<?php
	  }
  }
	protected function get_query($my_query,$settings,$edit){
			  	      $ppp =  $settings['posts_per_page'];
	          if(wp_is_mobile() && $settings['posts_per_page_mobile'])
			  $ppp = $settings['posts_per_page_mobile'];
	           /// set carusel
	            $carusel = false;
            	$query_ppp = $ppp;
           	     $carusel_div = '';
	    if($settings['carusel'] === 'carusel'){
				   $id = $this->get_id();
				   $carusel = true;
				   $query_ppp  = -1;
				   $carusel_div = "owl-carousel owl-theme first\" data-carusel=\"carusel_".$id;
				   $arrterms = [];
				   $data = [];
				   $data['settings'] = array('dots'=> false,
				   							 'nav' => true,
											'responsive'=> array('desktop' => $settings['posts_per_row'],
																'mobile' => $settings['posts_per_row_mobile'],
															   'tablet' => $settings['posts_per_row_tablet'] )
											);
			   }
		        if( $my_query->have_posts() ){
         //  echo "tet";
           // echo 1;
			?>

            <div class="all_posts <?php echo $carusel_div; ?>"> 
                <?php   
			$index = 0;
			$print_it = true;
            while ($my_query->have_posts()) : $my_query->the_post(); 
           //  echo "tet";
          
			//if($carusel)
			//array_push($arrterms,$this->render_one_post(get_the_ID(),$settings, $index));
			 $this->render_one_post(get_the_ID(),$settings, $index, $edit);
			$index++;
			if($index == $ppp)
				$print_it = false;
            endwhile;
		
		
		?>
</div><?php
			if($carusel){
  $data['items'] = $arrterms;
  wp_localize_script( 'widget_js', 'carusel_'.$id, $data ); 
				}
        }
	}
  protected function render_one_post($id,$settings, $index = 0, $edit = false){
	    $button_link = get_permalink();
      if($edit)
$button_link = add_query_arg(array('sub-page' =>"edit_camp" ,'camp_id' => get_the_ID()),get_permalink(get_queried_object_id()));
    
        //  $button_link = get_permalink(get_queried_object_id); 
	   $camp   =   new \Donations\Campaign\TZT_menge_campaign();

      $donaction_amount =   $camp->get_amount_to_display($id);  
	  $amount =  (int)$donaction_amount['amount'];
	  $donated =  (int)$donaction_amount['donated'];
	  $doners =  (int)$donaction_amount['donaters'];
	  $currensy = $donaction_amount['currensy'];
    //  var_dump($donaction_amount);
	  if($amount === 0 || $donated === 0){ 
		 $procent = 0;
		  }
	  else  $procent = $donated / $amount;

	 // var_dump($settings);
     $post_width = 100 / $settings['posts_per_row'];
	  if(wp_is_mobile() && $settings['posts_per_row_mobile'])
		$post_width = 100 / $settings['posts_per_row_mobile'];
	  /*style="width: <?php echo $post_width; ?>%;"*/
ob_start();	  ?>

<div class="one_post item"  data-item_num="<?php echo $index;?>">
	<div class="one_post_worrper">
	    <?php
	   //   var_dump(pll_get_post( get_the_ID() ));
		  	 $terms = wp_get_post_terms( get_the_ID(), 'category' );
	      if(!empty( $terms ) &&  !is_wp_error( $terms )){
		  $image_id = get_term_meta ( $terms[0] -> term_id, 'category-image-id', true ); ?>
   
       <div class="category_image_wrapper">
         <?php if ( $image_id ) { ?>
           <?php echo wp_get_attachment_image ( $image_id, array($settings['icon_size']["size"],$settings['icon_size']["size"]) ); ?>
         <?php  }?>
       </div>
	   <?php  }?>
  <div class="row post_img">

	  <img
		   src="<?php echo get_the_post_thumbnail_url(); ?>"
		   width="<?php  echo $settings['img_dimension']['width']; ?>"
		  
		   alt="test">
	  </div>
	 <div class="row flex_row">
		 
		  <div class="colmn col_30">
			<div class="circle_progress" 
				 data-fill-color="<?php echo $settings['fill_color']; ?>"
				 data-text-color="<?php echo $settings['inside_text_color']; ?>"
				 data-value="<?php echo round($procent, 2); ?>"
				 data-progres-val="<?php echo round($procent, 2); ?>"
				 data-progres-size="100" >
			  </div> 
		  </div>
		   <div class="colmn col_70">
			<div class="camp_status">
				<p class="title"><?php echo $settings['doners']; ?></p>
				
				<p class="content"><?php echo $doners; ?></p>
			    <p class="title"><?php echo $settings['amount']; ?></p>
				
				<p class="content"><?php echo $donated; ?><span><?php echo $currensy; ?></span></p>
			  </div> 
		  </div>
     
	</div>

	 <div class="row post_title">
	<h3><?php echo  the_title(); ?></h3>
		 </div>
	
		 <div class="row post_content">
		 <?php 
     // echo the_excerpt(); 
      echo get_post_meta(get_the_ID(),'short',true);
             ?>
	  </div>
	<div class="row post_button">
		<a href="<?php echo  $button_link; ?>" class="elementor-button-link elementor-button elementor-size-xs" role="button">
		<span class="elementor-button-content-wrapper">
		<span class="elementor-button-icon elementor-align-icon-left">
		<i class="fa fa-arrow-circle-left" aria-hidden="true" style="font-family: FontAwesome!important;"></i>
		</span>
		<span class="elementor-button-text"><?php echo $settings['button_text']; ?></span>
		</span>
		</a>
		</div>
</div>
</div>

<?php
	  $content = ob_get_clean();
	  echo $content;
  }
  protected function render() {
	   $this->set_query();

    }

    protected function _content_template(){ 

    }
}