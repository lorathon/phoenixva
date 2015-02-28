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
	public function index()
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
				$this->data['hubs'][$hub->icao] = $hub->get_full_name();
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
	public function view($icao)
	{
		log_message('debug', 'Hub page called');
		
		if (is_null($icao))
		{
			// Hub is required
			log_message('debug', 'No hub provided, redirecting.');
			redirect('/hubs');
		}
		
		$airport = new Airport();
		$airport->icao = $icao;
		$airport->find();
		
		$this->data['meta_title'] = 'Phoenix Virtual Airways Crew Centers: '.$airport->get_full_name();
		$this->data['title'] = $airport->get_full_name();
		
		$user = new User();
		$user->hub = $airport->id;
		$this->data['pilots'] = $user->find_all();
		
		$this->_render();
	}
	
	/**
	 * Creates a new page for a hub
	 * 
	 * Hubs can have multiple pages.
	 */
	public function create_page()
	{
		log_message('debug', 'Hub page create called');
	}
	
	/**
	 * Returns an array of articles for the selected hub.
	 * 
	 * @param string $icao of the hub to search
	 * @return array of Article objects
	 */
	protected function _hub_navigation($icao)
	{
		log_message('debug', 'Creating hub navigation for '.$icao);
		$article = new Article();
		$article->slug = $icao;
		return $article->find_all(TRUE);
	}
}