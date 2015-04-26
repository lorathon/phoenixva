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
	
	protected $_acars_processor_path = '/cjtop/acars/acars_processor/';
	
	// Used to enable/disable the profiler (can be overriden by child controllers)
	protected $_profile_this = TRUE;
	
	/**
	 * Holds any parameters to be returned to the client.
	 * @var array
	 */
	protected $_params = array();
	
	/**
	 * Separator between input fields
	 * 
	 * Used by the asynch messaging system.
	 * 
	 * @var string
	 */
	protected $_field_separator = '&';
	
	function __construct()
	{
		parent::__construct();
		
		$this->output->enable_profiler($this->_profile_this);
		
		// Register autoloader
		spl_autoload_register(array('Acars_Base','_autoload'));
	}
	
	/**
	 * Dispatches asynchronous request
	 * 
	 * @param string $post_data to send
	 * @param string $post_to is the script to send the data to
	 * @param string $host is the host machine to connect to (defaults to current site)
	 * @param string $port is the port to connect on (defaults to 80)
	 */ 
	protected function dispatch($post_data, $post_to, $host = NULL, $port = '80')
	{
		log_message('debug','Dispatching message');
		$timeout = 2;
		if (is_null($host)) $host = $this->input->server('HTTP_HOST');
		
		$stream = stream_socket_client("{$host}:{$port}", $errno, $errstr, $timeout,
				STREAM_CLIENT_ASYNC_CONNECT|STREAM_CLIENT_CONNECT);
		if ($stream)
		{
			log_message('debug', 'Stream connected');
			$length = strlen($post_data);
			log_message('debug', '---------- POSTING TO ---------');
			log_message('debug', "{$host}:{$port}/{$post_to}");
			log_message('debug', '---------- POST MESSAGE ----------');
			log_message('debug', $post_data);
			log_message('debug', '---------- END POST ----------');
				
			fwrite($stream, "POST {$post_to} HTTP/1.1\r\n");
			fwrite($stream, "Host: {$host}\r\n");
			fwrite($stream, "Content-type: application/x-www-form-urlencoded\r\n");
			fwrite($stream, "Content-length: {$length}\r\n");
			fwrite($stream, "Accept: */*\r\n");
			fwrite($stream, "\r\n");
			fwrite($stream, $post_data);			
		}
		log_message('debug', 'Message dispatching complete');
	}
	
	/**
	 * Outputs an XML response
	 * 
	 * @param array $params containing the name/value pairs for the XML
	 * @param string $switch
	 */
	protected function sendXML($params, $switch = '') 
	{
		$this->output->enable_profiler(false);
		$xml = new SimpleXMLElement('<sitedata />');
	
		$info_xml = $xml->addChild('info');
		if($switch != '')
			$info_xml->addChild('xml_sw', $switch);
			
		foreach($params as $name => $value)	
		{
			$info_xml->addChild($name, $value);
		}
	
		header('Content-type: text/xml');
		$xml_string = $xml->asXML();

		log_message('debug', "Sending XML: \n".print_r($xml_string, true));
		
		echo $xml_string;
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