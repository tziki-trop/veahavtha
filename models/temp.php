<?php
//add_shortcode('heder_to_post','add_to_header_of_post');
add_shortcode('new_to_post','add_new_of_post');
add_shortcode('donation_to_post','add_donation_post');
add_shortcode('pro_bar','add_progres_bar');
;
function change_default_title( $title ){

$screen = get_current_screen();

if ( 'company' == $screen->post_type ){
$title = __( 'Company Name', 'donat');
    
}
else  if ( 'campaign' == $screen->post_type ){
$title = __( 'Campaign Name', 'donat');
}
return $title;
}
//add_filter( 'elementor/element_type/print_template', 'change_default_title' );
add_filter( 'enter_title_here', 'change_default_title' );
add_action( 'elementor/element/before_section_start', function( $element, $section_id, $args ) {
   /** @var \Elementor\Element_Base $element */
   if ('section_custom_css' === $section_id ) {

   	$element->start_controls_section(
   		'custom_section',
   		[
   			'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
   			'label' => __( 'Custom Section', 'plugin-name' ),
   		]
   	);

   	$element->add_control(
   		't',
   		[
   		'type' => \Elementor\Controls_Manager::NUMBER,
   		'label' => __( ' my very Custom Control', 'plugin-name' ),
   		]
   	);

   	$element->end_controls_section();
   }
}, 10, 3 );
add_action( 'elementor/frontend/element/before_render', function ( \Elementor\Element_Base $element ) {
    $id = $element->get_id();
	/*if ( ! $element->get_settings( 'my_custom_control' ) ) {
		return;
	}*/
    $id = $element->get_settings('t');
$click = "test(".$id.")"; 
	$element->add_render_attribute( '_wrapper', [
		'class' => 'class-'.$id,
		'onClick' => $click,
	] );
   /* $element->add_render_attribute( '_wrapper', [
		'class' => 'my-custom-class',
		'data-my_data' => 'my-data-value',
	] );*/
} );

function add_donation_post(){
    $id = get_queried_object_id();
    $field_name = "donat_val";
$values = get_field($field_name,$id);
$field = get_field_object($field_name,$id);  
$tefolt_val = get_field("donat_val_def",$id);
   $res ='';
    $checked = "";
if( $values ){
    $other = false;
    $res .= "<section id='donation_amount'>";
  foreach( $values as $value ){  
      $labal = $field['choices'][$value];
      if($value == $tefolt_val)
          $checked = "checked";
      if($value == "avg"){
        $avg =   get_field("donat_avg",$id);
          $labal =  $avg."<br><span id= 'avgdis'>".$field['choices'][$value]."</span>";
          $value = $avg;
      }
        if($value == "other"){
            $other = true;
       continue;
      }
     
      $res .= "<div>
  <input type='radio' id='control_".$value."' name='select' value='".$value."' ".$checked.">
  <label for='control_".$value."'>
    <h2>".$labal."</h2>
  </label>
</div>";
    //$icon =  $value . $field['choices'][$value] ; 
   // $res .= $icon."<br>";
  }
}
     $res .= "</section>";
    if($other)
        $res .= "<input type='text' id='control_other' name='select' placeholder='".__( 'chose amount', 'donat')."'>";
   
    return $res;
}
function add_progres_bar(){
        $id = get_queried_object_id();  
$cur_val = get_field("avg_donction",$id);
    return "<div class='w3-border'>
  <div id='myBar' class='w3-container w3-padding w3-green' style='width: ".$cur_val."%;'>
        <div class='w3-center' id='demo'>".$cur_val."%</div>
  </div>
</div>";
    
}
function add_to_header_of_post( ){
    global $post;
$post_id =  $post->ID;
  //  if($post_id == 74 ||$post_id == 113)
  return  acf_form_head();
 //   else return;
}
function add_new_of_post(){
return acf_form(array(
		'post_id'		=> 'new_post',
		'post_title'	=> true,
		'new_post'		=> array(
			'post_type'		=> 'campaign',
			'post_status'	=> 'publish'
		),
		'submit_value'	=> __( 'Add new campaign', 'donat'),
        'field_groups'	=> array( 15,25 )
	));
}
