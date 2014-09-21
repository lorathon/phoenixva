<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends PVA_Controller {
	
	function index()
	{
		// Display the profile for the logged in user.
		$this->_render();
	}
	
	function view($id = NULL)
	{
		// Display the profile for the selected user.
		$this->_render();
	}
}