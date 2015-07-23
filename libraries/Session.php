<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/** 
 * Session class
 */
class Session {
	
	/**
     * Constructor
     *
     * @access public
     */			
  	public function __construct() {	
		
		if (!session_id())
			session_start();
		
	}
	
	/**
     * Set session
     *
     * @access public
     */	
	public function set($name, $value) {
	
		$_SESSION[$name] = $value;
	
	}

	/**
     * Get session
     *
     * @access public
     */	
	public function get($name) {
	
		if (isset($_SESSION[$name]))
			return $_SESSION[$name];
		else
			return false;
	
	}

	/**
     * Delete session
     *
     * @access public
     */	
	public function delete($name) {
	
		unset($_SESSION[$name]);
	
	}

	/**
     * Destroy session
     *
     * @access public
     */	
	public function destroy() {
		
		$_SESSION = array();
		session_destroy();
		
	}
	
}

?>