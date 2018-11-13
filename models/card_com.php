<?php
namespace Donations;
use Donations\Donation;
//Donations\Currency\TZT_menge_currency
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_menge_card_com {
    protected $settings = [];
	protected $url;
    public function __construct($action) {
        $this->set_action($action);
    }
    protected function set_action($action){
        $this->set_campny_settings();
        if($action === "get_url"){
           $this->url = "https://secure.cardcom.solutions/Interface/LowProfile.aspx";
            $this->settings['SuccessRedirectUrl'] = get_permalink(get_option('SuccessRedirectUrl'));
            $this->settings['ErrorRedirectUrl'] = get_permalink(get_option('ErrorRedirectUrl'));
	      
        }
        if($action === "check_res"){
            	$this->url = "https://secure.cardcom.solutions/Interface/BillGoldGetLowProfileIndicator.aspx";
        }
    }
	protected function set_campny_settings(){
		// set login
		$this->settings['TerminalNumber'] = get_option('TerminalNumber');
		$this->settings['UserName'] = get_option('UserName');
		
    }
	public function get_url($user,$payment,$lang,$id, $invoice = false ,$slug = false){
		    if($slug != false)
            $this->settings['SuccessRedirectUrl'] = $slug."/tnx";
              
            if($invoice != false){
			$this->settings['InvoiceHead.CustName'] = $user['name'];
			$this->settings['InvoiceHead.Email'] = $user['email'];
			$this->settings['InvoiceHead.CoinID'] = $this->set_currency($payment['currency']);
			$this->settings['InvoiceHead.SendByEmail'] = true;
			//SendByEmail
			$index = 1;
			foreach($invoice as $line){
				$this->settings['InvoiceLines'.$index.'.Description'] = $line['description'];
				$this->settings['InvoiceLines'.$index.'.Price'] = $line['price'];
				$this->settings['InvoiceLines'.$index.'.Quantity'] = $line['quantity'];
				//InvoiceLines1.Descriptio
				$index++;
			}
		}
		//language
		$this->settings['Language'] = $lang;
		$this->settings['ReturnValue'] = $id;
		$this->settings['Operation'] = 1;
		$this->settings['SumToBill'] = $payment['amount'];
		$this->settings['APILevel'] = 10;
		$this->settings['CoinID'] = $this->set_currency($payment['currency']);

		$this->settings['codepage'] = '65001';
		$this->settings['IndicatorUrl'] = get_site_url()."/cardcomres";
      //  return $this->settings;
       // $this->addParameter('IndicatorUrl',get_site_url()."/cardcomres");
		$res = $this->make_request('ResponseCode');
		return $res;
		//wp_redirect($res['url']);
	
	}

	    public function get_res(){
  /*  $donation_mengger = new \Donations\Donation\TZT_menge_Donations();
     $res = $donation_mengger->publish_donation((int)$_GET['ReturnValue']);
  var_dump($res);
          		wp_die();*/
        if(isset($_GET['lowprofilecode'])){
			$this->settings['lowprofilecode'] = $_GET['lowprofilecode'];
            $operation = $_GET['Operation'];
				$url = add_query_arg($this->settings,$this->url);
			    $response = wp_remote_get( $url);
			 $body = wp_remote_retrieve_body($response);
            $output = array();
            parse_str($body,$output);
            //ResponseCode
            if(array_key_exists('ResponseCode',$output)){
                if((int)$output['ResponseCode'] != 0 )
                    echo "error";
            		wp_die();
            }
              if((int)$operation === 1) {
                  if((int)$output['DealResponse'] != 0 )
                    echo "error";
            		wp_die();
                  
              }
    $donation_mengger = new \Donations\Donation\TZT_menge_Donations();
    $res = $donation_mengger->publish_donation((int)$output['ReturnValue'],true);
                        		wp_die();

	
            
            
			try {
 			$json = json_decode( $body );
 
			} catch ( Exception $ex ) {
				$json = null;
			} // end try/catch
 
			var_dump($output);
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