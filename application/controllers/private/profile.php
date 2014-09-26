<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends PVA_Controller {
		
	/**
	 * Short circuit to the view
	 */
	function index()
	{
		$this->view();	
	}
	
	/**
	 * Display a pilot profile
	 * 
	 * Defaults to the currently logged in pilot if no ID is provided.
	 * 
	 * @param string $id of the pilot to display
	 */
	function view($id = NULL)
	{
		if (is_null($id)) 
		{
			// Default to current user
			$id = $this->session->userdata('user_id');
		}
				
		// Make sure this page is not cached
		$this->_no_cache();
		
		// Own profile?
		$this->data['own_profile'] = ($id == $this->session->userdata('user_id'));
		
		// Get the user
		$user = new User();
		$user->id = $id;
		$user->find();
		
		if ($user->name)
		{
			// User found
			
			$this->data['title'] = $user->name;
		}
		else 
		{
			// User doesn't exist
			$this->data['title'] = 'No Pilot Found';
			$this->data['errors'][] = 'A pilot with that ID could not be located.';
		}
		$this->_render();
	}
}