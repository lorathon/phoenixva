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
	
	/**
	 * User ID for the incoming request
	 * 
	 * @var integer
	 */
	protected $_user_id;
	
	/**
	 * User's password for the incoming request
	 * 
	 * @var string
	 */
	protected $_password;
	
	/**
	 * Authorization token
	 * 
	 * Used to identify the user after login.
	 * 
	 * @var string
	 */
	protected $_auth_token;
	
	/**
	 * Identifier for this ACARS client
	 * 
	 * @var string
	 */
	protected $_client;
    
	/**
	 * Used to enable/disable the profiler (can be overriden by child controllers)
	 * 
	 * @var boolean
	 */
	protected $_profile_this = TRUE;
	
	/**
	 * Holds any parameters to be returned to the client.
	 * @var array
	 */
	protected $_params = array();
	
	/**
	 * URI path to the ACARS processor
	 * 
	 * @var string
	 */
	const ACARS_PROCESSOR_PATH = '/cjtop/acars/acars_processor/';
	
	/**
	 * Separator between input fields
	 * 
	 * Used by the asynch messaging system.
	 * 
	 * @var string
	 */
	const FIELD_SEPARATOR = '&';
	
	function __construct()
	{
		parent::__construct();
		
		$this->output->enable_profiler($this->_profile_this);
				
		// Register autoloader
		spl_autoload_register(array('Acars_Base','_autoload'));
		
		if (! is_null($this->_auth_token))
		{
			$session = new Acars_session(array('authToken' => (string)$this->_auth_token));
			$this->_user_id = $session->user_id;
		}
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
	protected function sendXML($switch = '') 
	{
		$this->output->enable_profiler(false);
		$xml = new SimpleXMLElement('<'.$this->_client.' />');
	
		if($switch != '')
			$xml->addChild('switch', $switch);
		
		$info_xml = $xml->addChild('data');
		
		foreach($this->_params as $name => $value)	
		{
			$info_xml->addChild($name, $value);
		}
	
		header('Content-type: text/xml');
		$xml_string = $xml->asXML();

		log_message('debug', "Sending XML: \n".print_r($xml_string, true));
		
		echo $xml_string;
	}
	
	/**
	 * Logs the user in and creates an ACARS session
	 * 
	 * @return User|boolean User object on success or false on failure
	 */
	protected function login()
	{
	    $user = new User();
	    $user->id = $this->_user_id;
	    $user->password = $this->_password;
	    $user->last_ip = $_SERVER['REMOTE_ADDR'];
	    if ($user->login())
	    {
	        $session = new Acars_session();
	        $session->create($user->id, $user->last_ip, $this->_client);
	        $this->_auth_token = $session->authToken;
	        return $user;
	    }
	    
	    return FALSE;
	}
	
	/**
	 * Gets the latest bid for the user
	 * 
	 * @return Schedule object representing the user's next bid or empty if no bids.
	 */
	protected function get_bid()
	{
	    // Get the bid
	    $schedule = new Schedule();
	    $bid = $schedule->get_bids($this->_user_id);
	    
	    if ($bid)
	    {
	        return $bid[0];  // XXX get_bids should probably return an object or an array
	    }
	    else 
	    {
	        return $schedule;
	    }
	}
	
	protected function position_report($position)
	{
	    // Set any defaults
	    $position->user_id = $this->_user_id;
	    $position->ip_address = $_SERVER['REMOTE_ADDR'];
	    
	    // Prep the message
	    $message = implode(self::FIELD_SEPARATOR, get_object_vars($position));
	    
	    // Dispatch to appropriate handler
	    //$this->dispatch($message, $this->_acars_processor_path.'update');
	}
	
	protected function file_pirep($pirep)
	{
	    // Set any defaults
	    
	    // Prep the message
	    $message = implode(self::FIELD_SEPARATOR, get_object_vars($pirep));
	    
	    // Dispatch to appropriate handler
	    //$this->dispatch($message, $this->_acars_processor_path.'pirep');
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
			$path = array('models','libraries','core');
			foreach ($path as $dir)
			{
				$file_class = $class;
				if ($dir == 'models')
				{
					$file_class = strtolower($class);
				}
				$file = APPPATH.$dir.'/'.$file_class.'.php';
				log_message('debug', 'Looking for file '.$file);
				if ($this->load_file($file))
				{
					log_message('debug', 'File '.$file.' loaded');
					break;
				}
			}
		}
		else
		{
			log_message('debug', 'Autoloading CI core class '.$class);
			$file = BASEPATH.'core/'.substr($class,3).'.php';
			$this->load_file($file);
		}
	}
	
	private function load_file($file)
	{
		if (file_exists($file) && is_file($file))
		{
			log_message('debug', 'Autoloading file '.$file);
			@include_once($file);
			return TRUE;
		}
		return FALSE;
	}
}