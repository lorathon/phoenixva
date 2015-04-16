<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Acars_processor extends PVA_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}
	
	public function update()
	{
		if ($this->form_validation->run())
		{
			
		}
	}
	
	public function file_pirep()
	{
		if ($this->form_validation->run())
		{
			
		}
	}
}
