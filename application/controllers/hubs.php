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
		
		$airport = new Airport();
		$airport->icao = $icao;
		$airport->find();
		
		if (!$airport->is_hub())
		{
			$this->data['errors'][] = $airport->name.' ('.$airport->icao.') is not a crew center.';
			$this->index();
			return FALSE;
		}
		
		$this->data['meta_title'] = 'PVA Crew Centers: '.$airport->get_full_name();
		$this->data['icao'] = $icao;
		$this->data['pages'] = $this->_hub_navigation($icao);
		$this->data['breadcrumb']['hubs'] = 'Crew Centers';

		$user = new User();
		$user->hub = $airport->id;
		$this->data['pilots'] = $user->find_all();

		$article = new Article();
		$article->slug = $this->_build_slug($icao, $page);
		$article->find();
		
		if (is_null($article->title))
		{
			$this->data['title'] = $airport->get_full_name();
		}
		else 
		{
			$this->data['title'] = $airport->icao.': '.$article->title;
		}
		
		if (is_null($article->body))
		{
			$this->data['body'] = 'This page has no content.';
		}
		else 
		{
			$this->data['body'] = $article->body;
		}
		
		$this->_render();
	}
	
	/**
	 * Edits a new page for a hub
	 * 
	 * Hubs can have multiple pages.
	 */
	public function edit_page($icao, $page = NULL)
	{
		log_message('debug', 'Hub page edit called');
		$this->_check_access('manager');
		$this->data['scripts'][] = base_url('assets/sceditor/jquery.sceditor.bbcode.min.js');
		$this->data['slug'] = $this->_build_slug($icao, $page);
		$this->_render('admin/page_form');
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
		$article->slug = $this->_build_slug($icao);
		$nav_list = $article->find_all(TRUE);
		if ($nav_list)
		{
			foreach ($nav_list as $item)
			{
				$navigation[$item->slug] = $item->title;
			}
		}
		return $navigation;
	}
	
	protected function _build_slug($icao, $page = NULL)
	{
		$slug = 'hub-'.$icao;
		if (!is_null($page))
		{
			$slug .= '-'.$page;
		}
		return $slug;
	}
}