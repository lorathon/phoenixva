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
		if (TRUE)
		{
			// Load XML
			//$xml = simplexml_load_string($input);
			
			// Authenticate
			
			// Parse message from ACARS client format to PVA system format
			$message = '';
			
			// Dispatch to appropriate handler
			$this->dispatch($message);
			log_message('debug', 'Returning to client');
			
			// Respond to the ACARS client
			//echo $xml->asXML();
		}
		echo "Done!";
	}
}