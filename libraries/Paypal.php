<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/** 
 * Cart class
 */ 
class Paypal {

	/**
	 * Config
	 *
	 * @access private 
	 */	
	private static $config;

	/**
	 * Array contains the POST values for IPN
	 *
	 * @access private 
	 */		
	private $data = array();
	
	/**
	 * Holds the IPN response from paypal
	 *
	 * @access private 
	 */	
	private $paypal_response;
 	
	/**
	 * Constructor
	 * 
	 * @access public 
	 */	  
	public function __construct() {
	
		self::$config = config_load('cart');
		$this->paypal_response = '';
		
	}

	/**
	 * Validate ipn
	 * 
	 * @access public 
	 */
	public function validate_ipn($paypal_post_vars) {
		
		$this->data = $paypal_post_vars;
		
		if (self::$config['paypal_sandbox'])
			$fp = @fsockopen("ssl://www.sandbox.paypal.com", 443, $errno, $errstr, 30); 
		else
			$fp = @fsockopen("ssl://www.paypal.com", 443, $errno, $errstr, 30); 

		  if(!$fp) {
			  
			 $this->log_error("PHP fsockopen() error: " . $errstr , "");
			 
		  } else {
			
			$response = "&cmd=_notify-validate";
			
			foreach($this->data as $key => $value) {
				
				$value = urlencode(stripslashes($value));
				$response .= "&" . $key . "=" . $value;
				
			}
			
			$response .= "&cmd=_notify-validate";

			fputs($fp, "POST /cgi-bin/webscr HTTP/1.1\r\n");

			if (self::$config['paypal_sandbox'])
				fputs($fp, "Host: www.sandbox.paypal.com\r\n");
			else
				fputs($fp, "Host: www.paypal.com\r\n");
				
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");				
			fputs($fp, "Content-length: " . strlen($response) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n" );
			fputs($fp, $response . "\r\n\r\n");
			
			while (!feof($fp)) { 

				$this->paypal_response .= fgets($fp, 1024);
				$this->log_response();
				
			}

			fclose( $fp );
									  
		  }	  	
	}

	/**
	 * Valid IPN transaction
	 * 
	 * @access public 
	 */
	public function is_verified() {

		if (strpos($this->paypal_response, "VERIFIED") !== false)
			return true;
		else
			return false;
			
	} 
	
	/**
	 * Get payment status
	 * 
	 * @access public 
	 */
	public function get_payment_status() {
		
		return $this->data['payment_status'];
		
	}
	
	/**
	 * Get order id
	 * 
	 * @access public 
	 */
	public function get_order_id() {
				
		return $this->data['custom'];
		
	}

	/**
	 * Log error
	 * 
	 * @access private 
	 */	
	private function log_error($message) {
		 
		$text  = '[' . date('d/m/Y G:i:s') . '] - '; 
		$text .= 'FAIL: ' . $message . "\n";

		$fp = fopen(self::$config['log_path'] . 'log_errors.log', 'a');
		fwrite($fp, $text); 

		fclose($fp);

	}

	/**
	 * Log response
	 * 
	 * @access public 
	 */
	public function log_response() {
		
		$text  = '[' . date('d/m/Y G:i:s') . '] - '; 
		$text .= "IPN POST Vars from Paypal:\n";
		
		foreach ($this->data as $key => $value)
			$text .= $key . "=" . $value . ", ";

		$text .= "\n\nIPN Response from Paypal Server:\n " . $this->paypal_response;

		$fp = fopen(self::$config['log_path'] . 'log_responses.log', 'a');
		fwrite($fp, $text);

		fclose($fp);

	}

	/**
	 * Log payment
	 * 
	 * @access public 
	 */
	public function log_payment($message) {
		
		$text  = '[' . date('d/m/Y G:i:s') . '] - '; 
		$text .= $message . "\n";

        $text .= "\nIPN Response from Paypal Server:\n " . $this->paypal_response;
        
		$fp = fopen(self::$config['log_path'] . 'log_payments.log', 'a');
		fwrite($fp, $text); 

		fclose($fp);  

	}
			
}

?>
