<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Static page controller
 * 
 * Checks the application for a corresponding view in /views/pages/ and if it
 * doesn't exist it will check the database.
 * 
 * @author Chuck
 *
 */
class Pages extends PVA_Controller {
	
	/**
	 * Finds the page to render.
	 * 
	 * @param string $page The page to render.
	 */
	public function view($page = 'home')
	{
		log_message('debug','Pages Controller viewing '.$page);
		
		// Load the session
		$this->load->library('session');
		
		// File or database
		if ( ! file_exists(APPPATH.'/views/pages/'.$page.'.php'))
		{
			// Try to get page from database
			log_message('debug','Getting page from database.');
			// No page in database, no page exists
			show_404();
		}
		
		if ($page == 'typeahead')
		{
			$this->load->helper('url');
			$this->data['scripts'] = array();
			$this->data['scripts'][] = '<script src="'.base_url('assets/js/typeahead.bundle.js').'"></script>';
			$this->data['scripts'][] = '<script src="'.base_url('assets/js/prefetch.js').'"></script>';
		}		
		
		$this->data['title'] = ucwords($page);
		$this->_render('pages/'.$page);
	}
}