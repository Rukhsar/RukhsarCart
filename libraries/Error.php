<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/** 
 * Error class
 */ 
class Error {
		
	/**
	 * Errors
	 *
	 * @access private 
	 */		
	private $errors = array();

	/**
	 * Session
	 *
	 * @access private 
	 */		
	private $session;
		
	/**
	 * Constructor
	 * 
	 * @access public 
	 */	  
	public function __construct() {
		
		$this->session = new Session();

		if (!$this->session->get('errors'))
			$this->session->set('errors', array());
					
	}
	
	/**
	 * Set errors
	 * 
	 * @access public 
	 */	
	public function set_error($message) {
		
		$this->errors[] = $message;
		
		$this->session->set('errors', $this->errors);
					
	}

	/**
	 * Display errors
	 * 
	 * @access public 
	 */		
	public function display_errors() {
			
		return $this->session->get('errors');
		
	}

	/**
	 * Returns whether has errors
	 * 
	 * @access public 
	 */		
	public function has_errors() {
		
		return (count($this->session->get('errors')) > 0 ) ? true : false;
		
	}

	/**
	 * Clear errors
	 * 
	 * @access public 
	 */		
	public function clear_errors() {
			
		$this->session->delete('errors');
		$this->session->set('errors', array());
		
	}
		
}

?>
