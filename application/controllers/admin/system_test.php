<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class System_test extends PVA_Controller
{
	public function index()
	{
		$this->load->library('unit_test');
		
		$this->unit->run(Calculations::hours_to_minutes('00:00'), 0, 'Calculate 00:00 in minutes');
		
		echo $this->unit->report();
	}
}
