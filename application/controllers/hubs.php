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
	public function view($icao, $page = NULL)
	{
		log_message('debug', 'Hub page called');
				
		if (is_null($icao))
		{
			// Hub is required
			log_message('debug', 'No hub provided, redirecting.');
			$this->index();
		}
		
		if (strlen($icao) > 4)
		{
			// Handle slug being passed in
			$parts = explode('-', $icao, 3);
			if (count($parts) > 1)
			{
				$icao = $parts[1];
			}
			
			if (count($parts) > 2)
			{
				$page = $parts[2];
			}
			log_message('debug', 'Splitting hub slug');
			log_message('debug', 'ICAO = '.$icao);
			log_message('debug', 'Page = '.$page);
			
			// Redirect to keep out duplicate content
			$this->load->helper('url');
			$this->session->keep_flashdata('alerts');
			redirect("hubs/{$icao}/{$page}");
		}
		
		$airport = new Airport();
		$airport->icao = $icao;
		$airport->find();
		
		if (!$airport->is_hub())
		{
			$this->_alert($airport->name.' ('.$airport->icao.') is not a crew center.', 'danger');
			$this->index();
			return FALSE;
		}
				
		$this->data['meta_title'] = 'PVA Crew Centers: '.$airport->get_full_name();
		$this->data['title'] = $airport->get_full_name();
		$this->data['body'] = '<p>This page has no content.</p>';
		$this->data['icao'] = $icao;
		$this->data['page'] = $page;
		$this->data['pages'] = $this->_hub_navigation($icao);
		$this->data['breadcrumb']['hubs'] = 'Crew Centers';

		$user = new User();
		$user->hub = $airport->id;
		$user->activated = 1;
		$this->data['pilots'] = $user->find_all();
		
		// Get the list of pending transfers for managers and above.
		if ($this->session->userdata('is_manager'))
		{
			$transfers = new User();
			$transfers->hub_transfer = $airport->id;
			$transfers->activated = 1;
			$this->data['transfers'] = $transfers->find_all();
		}

		$article = new Article();
		$article->slug = $article->build_slug('hub', array($icao, $page));
		$article->find();
				
		if ($article->body_html)
		{
			$this->data['body'] = $article->body_html;
		}
		
		if ($page == 'logbook')
		{
			$this->data['body'] .= '<p>Logbook not yet implemented</p>';
		}
		
		if (!is_null($page) && $page != 'logbook')
		{
			$this->data['article_id'] = $article->id;
		}
		
		$this->_render();
	}
	
	public function transfer()
	{
		log_message('debug', 'Hub transfer called');
		$this->data['title'] = 'Hub Transfer';
		$this->data['body'] = '<h1>Transfer capability not yet implemented.</h1>';
		$this->_render('article');
	}
			
	/**
	 * Returns an array of article links for the selected hub.
	 * 
	 * @param string $icao of the hub to search
	 * @return array of Article objects
	 */
	protected function _hub_navigation($icao)
	{
		log_message('debug', 'Creating hub navigation for '.$icao);
		$navigation = array();
		
		$article = new Article();
		// Add the dash at the end so the home page isn't included (it's automatic)
		$article->slug = "hub-{$icao}-";
		$nav_list = $article->find_all(TRUE);
		if ($nav_list)
		{
			foreach ($nav_list as $item)
			{
				// Don't include default pages
				if (!strstr($item->slug, 'logbook'))
				{
					$navigation[$item->slug] = $item->title;
				}
			}
		}
		return $navigation;
	}
}