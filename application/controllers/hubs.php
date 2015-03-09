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
			$icao = $parts[1];
			if (count($parts) > 2)
			{
				$page = $parts[2];
			}
			log_message('debug', 'Splitting hub slug');
			log_message('debug', 'ICAO = '.$icao);
			log_message('debug', 'Page = '.$page);
			
			// Redirect to keep out duplicate content
			$this->load->helper('url');
			redirect("hubs/{$icao}/{$page}");
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

		$article = new Article();
		$article->slug = $this->_build_slug($icao, $page);
		$article->find();
				
		if ($article->body)
		{
			$this->data['body'] = $article->body;
		}
		
		if ($page == 'logbook')
		{
			$this->data['body'] .= '<p>Logbook not yet implemented</p>';
		}
		
		
		$this->_render();
	}
	
	/**
	 * Creates a new page for a hub
	 * 
	 * @param string $icao of the hub to create a page for
	 */
	public function create_page($icao)
	{
		log_message('debug', 'Hub page create called');
		$this->_check_access('manager');
		
		$this->data['meta_title'] = 'PVA Admin: Create Hub Page';
		$this->data['title'] = 'Create Hub Page';

		$this->load->helper('url');
		$this->data['scripts'][] = base_url('assets/sceditor/jquery.sceditor.bbcode.min.js');
		$this->data['stylesheets'][] = base_url('assets/sceditor/themes/default.min.css');
		
		$this->data['slug'] = 'hub-'.$icao.'-';
		$this->data['edit_mode'] = FALSE;
		$this->data['pagetitle'] = '';
		$this->data['pagebody'] = '';
		
		$this->_render('admin/page_form');
	}
		
	/**
	 * Edits a page for a hub
	 * 
	 * Hubs can have multiple pages.
	 */
	public function edit_page($icao, $page = NULL)
	{
		log_message('debug', 'Hub page edit called');
		$this->_check_access('manager');
		
		$this->data['meta_title'] = 'PVA Admin: Edit Hub Page';
		$this->data['title'] = 'Edit Hub Page';
		
		$this->load->helper('url');
		$this->data['scripts'][] = base_url('assets/sceditor/jquery.sceditor.bbcode.min.js');
		$this->data['stylesheets'][] = base_url('assets/sceditor/themes/default.min.css');
		
		$this->data['slug'] = $this->_build_slug($icao, $page);
		$this->data['edit_mode'] = TRUE; 
		$this->data['pagetitle'] = 'Crew Center Home';
		if ($page == 'logbook')
		{
			$this->data['pagetitle'] = 'Logbook';
		}
		$this->data['pagebody'] = '';
		
		$article = new Article();
		$article->slug = $this->_build_slug($icao, $page);
		$article->find();
		
		if ($article->title)
		{
			$this->data['pagetitle'] = $article->title;
			$this->data['meta_title'] = 'PVA Admin: Edit Hub Page';
			$this->data['title'] = 'Edit Hub Page';
		}
		if ($article->body)
		{
			$this->data['pagebody'] = $article->body;
		}
		$this->session->set_flashdata('return_url','hubs/'.$icao);
		
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
		// Add the dash at the end so the home page isn't included (it's automatic)
		$article->slug = $this->_build_slug($icao).'-';
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