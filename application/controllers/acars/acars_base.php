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
	
	protected function dispatch($message)
	{
		log_message('debug','Dispatching message');
		$timeout = 2;
		$this->load->helper('url');
		$host = 'dev.phoenixva.org';
		
		// CSRF
		$stream = stream_socket_client("{$host}:80", $errno, $errstr, $timeout,
				STREAM_CLIENT_ASYNC_CONNECT|STREAM_CLIENT_CONNECT);
		if ($stream)
		{
			log_message('debug', 'Stream connected');
			$post_data = $this->security->get_csrf_token_name().'='.$this->security->get_csrf_hash();
			$post_data .= '&'.$message;
			$length = strlen($post_data);
			log_message('debug', '---------- POST MESSAGE ----------');
			log_message('debug', $post_data);
			log_message('debug', '---------- END POST MESSAGE ----------');
				
			fwrite($stream, "POST /cjtop/acars/processor HTTP/1.1\r\n");
			fwrite($stream, "Host: {$host}\r\n");
			fwrite($stream, "Content-type: application/x-www-form-urlencoded\r\n");
			fwrite($stream, "Content-length: {$length}\r\n");
			fwrite($stream, "Accept: */*\r\n");
			fwrite($stream, "\r\n");
			fwrite($stream, $post_data);
			
			/* For testing only
			while (!feof($stream))
			{
				log_message('debug', fgets($stream, 1024));
			}
			fclose($stream);
			*/
		}
		log_message('debug', 'Message dispatching complete');
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
				log_message('debug', 'File '.$file.' loaded');
				break;
			}
		}
	}	
}