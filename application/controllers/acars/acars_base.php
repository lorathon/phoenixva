<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PVA ACARS base controller.
 *
 * Super lightweight controller for handling the ACARS client communications.
 *
 * Other ACARS related controllers should extend Acars_Base_Controller.
 *
 * @author Chuck Topinka
 *
*/
class Acars_Base extends CI_Controller {
	
	// Used to enable/disable the profiler (can be overriden by child controllers)
	protected $_profile_this = TRUE;
	
	function __construct()
	{
		parent::__construct();
		
		$this->output->enable_profiler($this->_profile_this);
		
		// Register autoloader
		spl_autoload_register(array('Acars_Base','_autoload'));
	}
	
	/**
	 * PVA Application ACARS autoloader
	 *
	 * Allows calling objects without having to load models first. This is a
	 * stripped down version of the autoloader used by PVA_Controller.
	 * 
	 * @param string $class The class name to load.
	 */
	protected function _autoload($class)
	{
		if (substr($class,0,3) != 'CI_')
		{
			log_message('debug', 'Autoloading '.$class);
			$file = APPPATH.'models/'.strtolower($class).'.php';
			log_message('debug', 'Looking for file '.$file);
			if ($this->load_file($file))
			{
				break;
			}
		}
	}	
}