<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'acars_base.php';
/**
 * ACARS controller for the kACARS client by Jeffrey Kobus (www.fs-products.net).
 *
 * @author Chuck Topinka
 * @author Jeffrey Kobus
 *
*/
class Kacars extends Acars_Base
{
	public function index()
	{
		$input = file_get_contents('php://input');
		if ($input)
		{
			// Load XML
			$xml = simplexml_load_string($input);
			
			// Check credentials
			
			// Dispatch to appropriate handler
			$timeout = 2;
			$this->load->helper('url');
			$stream = stream_socket_client(site_url(), $errno, $errstr, $timeout,
					STREAM_CLIENT_ASYNC_CONNECT|STREAM_CLIENT_CONNECT);
			if ($stream)
			{
				fwrite($stream, '');
			}
			// Drop back out asynchronously
			echo $xml->asXML();
		}
		echo "Done!";
	}
}