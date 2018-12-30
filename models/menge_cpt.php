<?php
namespace Donations\Cpt;

if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_menge_cpt {
    protected $post_id;

   // 'organization','donations'
    public function __construct($post_id = null) {
 
      $this->post_id = $post_id;
        // $this->args = $args;
    }
    public function change_status($id,$status){
        $current_status = get_post_status( $id );
        if($current_status === $status)
        return;
        return   wp_update_post(array(
        'ID'    =>  $id,
        'post_status'   =>  $status
        ));

    }
	public function insert_taxonomy($id,$name){
        $term_id = wp_insert_term( $name,'organization');
        if(is_wp_error($term_id)){
            return $term_id;
           
        }
        $res = add_term_meta($term_id['term_id'], 'camp_id', $id, true); 
		return $term_id;
	}
    public function insert($args){
        //if($this->post_id != null) return false;
      return wp_insert_post( $args );
        
    }
    public function get_camp_id($term_id){
      return  get_term_meta((int)$term_id,'camp_id', true );

    }
		 public function get_taxonomy($post_id,$taxonomy){
             $terms = wp_get_object_terms( $post_id, $taxonomy );
             
             if(is_wp_error($terms)){
                $error_string = $terms->get_error_message();
                 error_log($taxonomy);
                return $terms;
             }
             if(!is_array($terms))
             return $terms;
			 else return $terms[0]->term_taxonomy_id;

	 }

	 public function set_taxonomy($post_id, $terms, $taxonomy,$no_insert = false){
        $terms =  wp_set_post_terms( $post_id, array((int)$terms), $taxonomy,false );
        if(is_wp_error($terms)){
            $error_string = $terms->get_error_message();
             error_log($taxonomy);
            return $terms;
         }
          wp_update_term_count_now(array((int)$terms), $taxonomy);
		
          return wp_update_term_count(array((int)$terms), $taxonomy, true);
	 }
    public function update($args){

       return wp_update_post( $args );
    }
      public function get($id,$key = true,$singel = true){
      
        return   get_post_meta( $id, $key, $singel );
    }
       public function set($id,$key,$val,$singel = true){
    	 return  add_post_meta($id,$key,$val,$singel);
    }
	   public function update_meta($id,$key,$val){

		 return  update_post_meta($id,$key,$val);
   
    }
  
}