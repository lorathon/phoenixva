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
		$this->data['title'] = 'Crew Centers';
		log_message('debug', 'Hub index called');
		$this->data['hubs'] = array();
		$airport = new Airport();
		if ($hubs = $airport->find_hubs())
		{
			log_message('debug', 'Hubs retrieved.');
				
			foreach ($hubs as $hub)
			{
				$this->data['hubs'][$hub->id] = $hub->icao.' - '.$hub->name;
			}
		}
		$this->_render();
	}
}