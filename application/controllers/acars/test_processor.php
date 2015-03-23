<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Test_processor extends PVA_Controller
{
	private $separator = '****************************************************';
	public function index()
	{
		log_message('debug', $this->separator);
		log_message('debug', 'ACARS Test Processor called');
		log_message('debug', '----- POSTed DATA -----');
		foreach ($this->input->post() as $key => $value)
		{
			log_message('debug', "{$key} => {$value}");
		}
		log_message('debug', '----- END POST -----');
		$sleep = 5;
		log_message('debug', "Sleeping {$sleep} seconds.");
		sleep($sleep);
		log_message('debug', 'Processing complete');
		log_message('debug', $this->separator);
	}
}
