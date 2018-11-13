<?php
namespace Donations;
//Donations\Currency\TZT_menge_currency
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_menge_currency {
    protected $currencys = [];
	protected $currnsys_names = [];
    public function __construct() {
        $this->set();
        //$this->get_currencys_from_api();
      
    }
    protected function set(){
         $this->currencys = array("USD" => get_option('USD_ILS')
                                  ,"ILS" => 1
                                  , "EUR" => get_option('EUR_ILS')
                                 );
 
        $this->currnsys_names = array(
			array('currensy_code' => "ILS",
				  'currensy_name' => "&#x20aa;",
				   'currensy_num_code' => 1,
				 ),
			array('currensy_code' => "USD",
				  'currensy_name' => "&#36;",
				   'currensy_num_code' => 2,
				 ),
            array('currensy_code' => "EUR",
				  'currensy_name' => "&euro;",
				   'currensy_num_code' => 3,
				 ),
			);
    }
	public function get_all_names(){

		return $this->currnsys_names;

	}
    public function get_currencys_from_api(){
        foreach($this->currnsys_names as $currency){
        if($currency['currensy_code'] === 'ILS')
        continue;
        $url = "http://free.currencyconverterapi.com/api/v5/convert?q=".$currency['currensy_code']."_ILS&compact=y";
        $request = wp_remote_get( $url );
        if( is_wp_error( $request ) ) {
	    return false; // Bail early
        }
        $body = wp_remote_retrieve_body( $request );
        $data = json_decode( $body );
        if( ! empty( $data ) ) {
            switch ($currency['currensy_code']) {
            case "USD":
                    update_option( 'USD_ILS', $data->USD_ILS->val );
                    break;
            case "EUR":
                    update_option( 'EUR_ILS', $data->EUR_ILS->val );
                    break;
      }
    }
      }
        return true;
    }
		public function get_name_by_code($code){
foreach($this->currnsys_names as $currnsy){
	if($currnsy['currensy_code'] === $code)
		return $currnsy['currensy_name'];
    }
      return "ILS";
	}
	public function convert($to,$amount){
		if(array_key_exists((string)$to,$this->currencys) )
		return $amount / $this->currencys[$to];
		else  $amount / $this->currencys["ILS"];
	}
    public function convert_to($from,$to,$amount){
		if(!array_key_exists((string)$from,$this->currencys) )
			$from = "ILS";
		if($to === "ILS")
		return $amount * $this->currencys[$from];
		else{
			$ils = $amount * $this->currencys[$from];
			return  $ils / $this->currencys[$to];
		}
		/*if(array_key_exists((string)$from,$this->currencys) )
		return $amount * $this->currencys[$from];
		else  $amount * $this->currencys["ILS"];*/
	}
}