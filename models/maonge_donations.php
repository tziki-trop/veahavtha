<?php
namespace Donations\Donation;
use Polylang;
use Donations\Pll;
use Donations\Cpt;
use Donations\User;
use Donations\Campaign;
use Donations\TZT_menge_currency;

class TZT_menge_Donations {
    protected $cpt;
    protected $post_id;
	protected $user;
	protected $pll;
	protected $currency;
	protected $camp;

    public function __construct($post_id = null) {
         $this->currency   =   new \Donations\TZT_menge_currency();
        $this->pll = new \Donations\Pll\TZT_pll();
        $this->post_id = $post_id;
        $this->cpt = new \Donations\Cpt\TZT_menge_cpt();
		$this->user = new \Donations\User\TZT_menge_user();
		 $this->camp = new \Donations\Campaign\TZT_menge_campaign($post_id);
    }
	public function add($amount,$currency,$doner){
        
        $donation_id =  $this->camp->get_donation_id($this->post_id);
		$post_id = $this->add_donation_post($amount,$currency,$donation_id,$doner);
		return $post_id;
	}
    public function publish_donation($post_id, $publish = false){
        $vals = [];
        $term = $this->cpt->get_taxonomy($post_id,'organization');
       // $term = get_the_terms( (int)$post_id, "organization" );
        if(is_wp_error($term)){
            $error_string = $term->get_error_message();
            error_log($error_string);
         }
        $vals['id'] = $this->cpt->get_camp_id($term);
        $vals['amount'] = $this->cpt->get($post_id,'amount');
        $vals['currency'] = $this->cpt->get($post_id,'currency');
        error_log($vals['id']);
        if($publish)
        $vals['res'] = $this->cpt->change_status($post_id,'publish');
        else{
        $donation_id =  $this->camp->add_donation($vals['id'],$vals['amount'],$vals['currency'],$post_id);
        }
       // change_status($id,$status)
        return $vals;
    }
	protected function add_donation_post($amount,$currency,$term_id,$post_meta){
      //  $post_meta['donation_id'] = $term_id;
	    $post_meta['amount'] = $amount;
		$post_meta['currency'] = $currency;
		$post_term['organization'] = array((int)$term_id);
        $post_param['meta_input'] = $post_meta;
		$post_param['tax_input'] = $post_term;
		$post_param['post_status'] = 'draft';
		$post_param['post_type'] = 'donations';
		$post_param['post_title'] = __( 'Donation: ', 'client_to_google_sheet' )." ".$amount.$currency;

      $post_id =   $this->cpt->insert($post_param);
      wp_set_object_terms($post_id, (int)$term_id, 'organization', true);

       return $post_id;
	}
}