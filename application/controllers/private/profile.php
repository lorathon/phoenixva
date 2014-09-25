<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends PVA_Controller {
		
	function index()
	{
		// Display the profile for the logged in user.
		
		// Make sure this page is not cached
		$this->_no_cache();
		
		$this->data['title'] = $this->session->userdata('name');
		$this->_render();
	}
	
	function view($id = NULL)
	{
		if (is_null($id)) 
		{
			// Default to current user
			$this->index();
		}
		
		// Display the profile for the selected user.
		
		// Make sure this page is not cached
		$this->_no_cache();
		
		// Get the user
		$user = new User();
		$user->id = $id;
		$user->find();
		
		$this->data['title'] = $user->name;
		
		$this->_render();
	}
}