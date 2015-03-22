<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Processor extends PVA_Controller
{
	public function index()
	{
		// Just testing
		$sleep = 5;
		sleep($sleep);
		log_message('debug', 'Processing complete');
		$this->data['body'] = "<h2>Processor testing output.</h2><p>{$sleep} seconds elapsed.</p>";
		$this->_render('article');
	}
}
