<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Hubs pages
 * 
 * @author Chuck
 *
 */
class Hubs extends PVA_Controller {
	
	/**
	 * Displays list of hub airports
	 */
	function index()
	{
		log_message('debug', 'Hub index called');
		$this->data['meta_title'] = 'Phoenix Virtual Airways Crew Centers';
		$this->data['title'] = 'Crew Centers';
		$this->data['hubs'] = array();
		$airport = new Airport();
		if ($hubs = $airport->find_hubs())
		{
			log_message('debug', 'Hubs retrieved.');
				
			foreach ($hubs as $hub)
			{
				$this->data['hubs'][$hub->id] = $hub->get_full_name();
			}
		}
		$count = count($this->data['hubs']);
		$this->data['meta_description'] = 
				"PVA operates from {$count} crew centers throughout the world. 
				 Check out what each crew center has to offer.";
		
		$this->_render();
	}
	
	/**
	 * Displays the requested hub page
	 */
	function view($id)
	{
		log_message('debug', 'Hub page called');
		
		if (is_null($id))
		{
			// Hub is required
			log_message('debug', 'No hub provided, redirecting.');
			redirect('/hubs');
		}
		
		$airport = new Airport($id);
		
		$this->data['meta_title'] = 'Phoenix Virtual Airways Crew Centers: '.$airport->get_full_name();
		$this->data['title'] = $airport->get_full_name();
		
		$user = new User();
		$user->hub = $id;
		$this->data['pilots'] = $user->find_all();
		
		
		$this->_render();
	}
	
	/**
	 * Creates a new page for a hub
	 * 
	 * Hubs can have multiple pages.
	 */
	function create_page()
	{
		log_message('debug', 'Hub page create called');
	}
}