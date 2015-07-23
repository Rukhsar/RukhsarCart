<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/** 
 * Template class
 */ 
class Template {

	/**
	 * Config
	 *
	 * @access private 
	 */	
	private static $config;
		
	/**
	 * Path templates
	 * 
	 * @access private
	 */
	private $tpl_path = null;
	
	/**
	 * Values
	 * 
	 * @access private
	 */
	private $values = array();

	/**
	 * Constructor
	 *
	 * @access public
	 */	
	public function __construct($tpl_path) {
		
		self::$config = config_load('template');
		
		$this->tpl_path = $tpl_path;

	}

	/**
	 * Set a template variable
	 * 
	 * @access public
	 */
	public function set($name, $value = null) {
		
		if(is_array($name)) {
		
			foreach ($name as $key => $value) {
			
				$this->values[$key] = $value;
			
			}
		
		} else {
			
			$this->values[$name] = $value;
			
		}
		
    }

	/**
	 * Display the template file
	 * 
	 * @access public
	 */
	public function display($template) {

		if ($this->values) {
			
			extract($this->values);
			
		}  
		
		if (file_exists($this->tpl_path . $template . self::$config['template_extension'])) {

			ob_start();

			include($this->tpl_path . $template . self::$config['template_extension']);

			$output = ob_get_contents();

			ob_end_clean();
			
		} else {
			
			die('Template file '. $this->tpl_path . $template . self::$config['template_extension'] . ' not found.');
			
		}
		
		if ($output) echo $output;
	
	} 

}
    
?>
