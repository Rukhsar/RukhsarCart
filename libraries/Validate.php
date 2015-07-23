<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/** 
 * Validate class
 */ 
class Validate {

	/**
	 * Error
	 *
	 * @access private 
	 */		
	private $error;
	
	/**
	 * Constructor
	 * 
	 * @access public 
	 */	  
	public function __construct() {

		$this->error = new Error();
		
	}

	/**
	 * Validate email address
	 * 
	 * @access public 
	 */	
	public function email($value, $message) {
	
		if (empty($value)) {
		
			$this->error->set_error($message);
		
		} else if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
		
			$this->error->set_error($message);
		
		}
	
	}

	/**
	 * Validate string
	 * 
	 * @access public 
	 */	
	public function required($value, $message) {
	
		if (empty($value))
			$this->error->set_error($message);
	
	}

	/**
	 * Validate number
	 * 
	 * @access public 
	 */	
	public function numeric($value, $message) {
	
		if (empty($value)) {
		
			$this->error->set_error($message);
		
		} else if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
		
			$this->error->set_error($message);
		
		}
	
	}

	/**
	 * Match one field to another
	 * 
	 * @access public 
	 */	
	public function matches($value_1, $value_2, $message) {
	
		if (empty($value_1) || empty($value_2)) {
		
			$this->error->set_error($message);
		
		} else if (!strcmp($value_1, $value_2) == 0) {
		
			$this->error->set_error($message);
		
		}		
	
	}
	
}

?>
