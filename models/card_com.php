<?php
namespace Donations;
use Donations\Donation;
//Donations\Currency\TZT_menge_currency
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_menge_card_com {
    protected $settings = [];
	protected $url;
    public function __construct($action,$pid = null) {
        $this->set_action($action, $pid);
    }  
    
    protected function set_action($action, $pid){
        
        $this->set_campny_settings($pid);
        if($action === "get_url"){
           $this->url = "https://secure.cardcom.solutions/Interface/LowProfile.aspx";
            $this->settings['SuccessRedirectUrl'] = get_permalink(get_option('SuccessRedirectUrl'));
            $this->settings['ErrorRedirectUrl'] = get_permalink(get_option('ErrorRedirectUrl'));
	      
        }
        if($action === "check_res"){
            	$this->url = "https://secure.cardcom.solutions/Interface/BillGoldGetLowProfileIndicator.aspx";
        }
        if($action == "charg_token"){
            $this->url = "https://secure.cardcom.solutions/interface/ChargeToken.aspx";

        }
    }
	protected function set_campny_settings($pid){
		// set login
		$this->settings['TerminalNumber'] = get_option('TerminalNumber');
        $this->settings['UserName'] = get_option('UserName');
        if($pid != null){
            $terminal = get_post_meta($pid,'TerminalNumber',true);
            if($terminal != false && $terminal != ''){
                $this->settings['TerminalNumber'] = get_post_meta($pid,'TerminalNumber',true);
                $this->settings['UserName'] = get_post_meta($pid,'UserName',true);
            }
        }
		
    }
    protected function add_payment_job($post_id){
        update_post_meta($post_id,'last_payment',strtotime("now"));
        $hok = (int)get_post_meta($post_id,'payments',true);
        $payment = (int)get_post_meta($post_id,'rem_payments',true);
       // update_post_meta($post_id,'last_payment',strtotime("now"));
        update_post_meta($post_id,'error_log',$hok > $payment);

        if($hok > $payment){
        wp_schedule_single_event( strtotime( '+1 months' ), 'make_card_com_payment', array(  $post_id  ));
        update_post_meta($post_id,'next_payment',strtotime( '+1 months' ));

        }
    }
	public function get_url($user,$payment,$lang,$id, $invoice = false ,$slug = false){
		    if($slug != false)
            $this->settings['SuccessRedirectUrl'] = $slug."/tnx";
              
            if($invoice != false){
			$this->settings['InvoiceHead.CustName'] = $user['name'];
			$this->settings['InvoiceHead.Email'] = $user['email'];
			$this->settings['InvoiceHead.CoinID'] = $this->set_currency($payment['currency']);
			$this->settings['InvoiceHead.SendByEmail'] = true;
			$index = 1;
			foreach($invoice as $line){
				$this->settings['InvoiceLines'.$index.'.Description'] = $line['description'];
				$this->settings['InvoiceLines'.$index.'.Price'] = $line['price'];
				$this->settings['InvoiceLines'.$index.'.Quantity'] = $line['quantity'];
				$index++;
			}
        }
        update_post_meta($id, 'description', $invoice[0]['description']);

        //update_post_meta($id, 'payment', $payment['amount']);

        //language
		$this->settings['Language'] = $lang;
        $this->settings['ReturnValue'] = $id;
        error_log($id);
        $this->settings['Operation'] = 1;
        if(isset($payment['payments']) && $payment['payments'] > 1){
        $this->settings['Operation'] = 2;
        $this->$settings['CreateTokenDeleteDate'] = date('d/m/Y', strtotime('+'.$payment['payments'].' months'));      
        update_post_meta($id, 'payments', $payment['payments']);
        update_post_meta($id, 'amount', $payment['amount'] * $payment['payments']);

        }
		$this->settings['SumToBill'] = $payment['amount'];
		$this->settings['APILevel'] = 10;
		$this->settings['CoinID'] = $this->set_currency($payment['currency']);

		$this->settings['codepage'] = '65001';
		$this->settings['IndicatorUrl'] = get_site_url()."/cardcomres";
        $res = $this->make_request('ResponseCode');
        // save LowProfileCode peameter on order to use it in "get res"
		return $res;
	
	}
    public function ask_payment_with_token($post_id){
       // $settings = $this->get_campny_settings();
        //get user nd donction data
        $user['currency'] = get_post_meta($post_id,'currency',true);
        $user['name'] = get_post_meta($post_id,'name',true);
        $user['email'] = get_post_meta($post_id,'email',true);
        $user['amount'] =  get_post_meta($post_id,'amount',true);
        $user['amount'] = (int)$user['amount'] / (int)get_post_meta($post_id,'payments',true);
        $user['form_name'] =  get_post_meta($post_id,'description',true);
       // $user['phone'] =  get_post_meta($post_id,'ph)one',true);
       // $invoice[0] = array('price' => $user['amount'],'quantity' => 1,'description' => $user['form_name']);
        // get token data
        $this->settings['TokenToCharge.Token'] = get_post_meta($post_id,'Token',true);
        
        $this->settings['TokenToCharge.CardValidityMonth'] = get_post_meta($post_id,'CardValidityMonth',true);
        
        $this->settings['TokenToCharge.SumToBill'] =  $user['amount'];
        
        $this->settings['TokenToCharge.CardValidityYear'] =  get_post_meta($post_id,'CardValidityYear',true);
        
        $this->settings['TokenToCharge.IdentityNumber'] =  get_post_meta($post_id,'CardOwnerID',true);
        
        $this->settings['TokenToCharge.CoinID'] = 1;
        $this->settings['TokenToCharge.NumOfPayments'] = 1;
        $this->settings['TokenToCharge.RefundInsteadOfCharge'] = false;
        $this->settings['codepage'] = '65001';
     //   if($invoice != false){
			$this->settings['InvoiceHead.CustName'] = $user['name'];
			$this->settings['InvoiceHead.Email'] = $user['email'];
			$this->settings['InvoiceHead.CoinID'] = $this->set_currency($user['currency']);
			$this->settings['InvoiceHead.SendByEmail'] = true;
			$index = 1;
		//	foreach($invoice as $line){
				$this->settings['InvoiceLines'.$index.'.Description'] = $user['form_name'];
				$this->settings['InvoiceLines'.$index.'.Price'] = $user['amount'];
				$this->settings['InvoiceLines'.$index.'.Quantity'] = 1;
			//	$index++;
		//	}
     //   }
      //  $this->settings = $this->set_invoic($user,$invoice,$settings);
        $url = "https://secure.cardcom.solutions/interface/ChargeToken.aspx";
        $RequestBody =  http_build_query($this->settings);
        $args = array(
        'method' => 'POST',
        'timeout' => 5,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => $RequestBody,
        'cookies' => array()
        );
        $response =  wp_remote_post($url,$args);

        $body = wp_remote_retrieve_body($response);
        if ( is_wp_error( $body ) ) {
			    update_post_meta($post_id,'error_log',"is_wp_error");
            return false;
        }
        $output = array();
        parse_str($body,$output);
        if($output['ResponseCode'] != 0){
        update_post_meta($post_id,'error_log',$output['Description']);
            return false;
        }
        $payment = get_post_meta($post_id,'rem_payments',true);
        $payment = $payment + 1;
        update_post_meta($post_id,'rem_payments',$payment);
        $this->add_payment_job($post_id);
        return true;
//ResponseCode
    }
	public function get_res(){
        if(isset($_GET['lowprofilecode'])){
			$this->settings['lowprofilecode'] = $_GET['lowprofilecode'];
            $operation = $_GET['Operation'];
			$url = add_query_arg($this->settings,$this->url);
			$response = wp_remote_get( $url);
			$body = wp_remote_retrieve_body($response);
            $output = array();
            parse_str($body,$output);
            //ResponseCode
           // var_dump($output);
            if(array_key_exists('ResponseCode',$output)){
                if((int)$output['ResponseCode'] != 0 ){
                    echo "error";
                    wp_die();
                }
            }
            if(!array_key_exists('ReturnValue',$output)){
                echo "error";
                wp_die()  ;  
            }
            $pid = (int)$output['ReturnValue'];
             if((int)$operation === 1) {
                  if((int)$output['DealResponse'] != 0 ){
                    echo "error";
            		wp_die();
                  }
                  $donation_mengger = new \Donations\Donation\TZT_menge_Donations();
                  $res = $donation_mengger->publish_donation($pid,true);
             
              }
              if((int)$operation === 2) {
                if((int)$output['DealResponse'] != 0 ){
                    add_post_meta($pid,'error_log',"error DealResponse");
                    return;
                    }
                if((int)$output['TokenResponse'] != 0 ){
                        add_post_meta($pid,'error_log',"error TokenResponse");
                        return;
                        }
                        
                    add_post_meta($pid,'Token',$output['Token']);
                    add_post_meta($pid,'TokenExDate',$output['TokenExDate']);
                    add_post_meta($pid,'CardOwnerID',$output['CardOwnerID']);
                    add_post_meta($pid,'CardValidityYear',$output['CardValidityYear']);
                    add_post_meta($pid,'CardValidityMonth',$output['CardValidityMonth']);
                    $payment = get_post_meta($pid,'rem_payments',true);
                    if($payment == '' || $payment == false )
                    $payment = 0;
                    $payment = $payment + 1;
                    update_post_meta($pid,'rem_payments',$payment);
                    $this->add_payment_job($pid);
                    $donation_mengger = new \Donations\Donation\TZT_menge_Donations();
                    $res = $donation_mengger->publish_donation($pid,true);
               
              }
            
            
            header("HTTP/1.1 200 OK");
            wp_die();
        }
    }

	protected function make_request($errorparameter){
    
        $RequestBody =  http_build_query($this->settings);
                   $args = array(
        'method' => 'POST',
        'timeout' => 5,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => $RequestBody,
        'cookies' => array()
        );
        $response =   wp_remote_post($this->url,$args);

        $body = wp_remote_retrieve_body($response);
           if ( is_wp_error( $body ) ) {
               //return "wp_error";
			    return array('status' => false, 'output' => "wp_error");
           }
        $output = array();
        parse_str($body,$output);
       // $errorparameter
        if($output[$errorparameter] != 0){
            return array('status' => false, 'output' => $output['Description']);
        }
        
        return array('status' => true, 'output' => $output['url']);
    }
	protected function set_currency($currency){
		//978
             switch ($currency) { 
            case "ILS":
                $currency = 1;
                    break;
            case "USD":
                  $currency = 2;
                  break;
            case "EUR":
                $currency = 978;
               break;
      }
		return $currency;
	}
	
}
//new TZT_menge_card_com();