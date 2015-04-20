<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Installer extends PVA_Controller
{
	public function migrate($version = NULL)
	{
		$this->load->library('migration');
		
		if (is_null($version))
		{
			log_message('debug', 'Migrating to current version based on config.');
			if (!$this->migration->current())
			{
				show_error($this->migration->error_string());
			}
		}
		else 
		{
			log_message('debug', 'Migrating to version number '.$version);
			if (!$this->migration->version($version))
			{
				show_error($this->migration->error_string());
			}
		}
	}
}