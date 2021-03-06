<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Static page controller
 * 
 * Checks the application for a corresponding view in /views/pages/ and if it
 * doesn't exist it will check the database.
 * 
 * @author Chuck
 *
 */
class Pages extends PVA_Controller
{

    /**
     * Finds the page to render.
     * 
     * @param string $page The page to render.
     */
    public function view($page = 'home')
    {
	$this->load->helper('url');

	log_message('debug', 'Pages Controller viewing ' . $page);

	// Load the session
	$this->load->library('session');

	// Handle special pages
	if (substr($page, 0, 4) == 'hub-')
	{
	    $this->session->keep_flashdata('alerts');
	    redirect("hubs/{$page}");
	}

	if (substr($page, 0, 6) == 'event-')
	{
	    $this->session->keep_flashdata('alerts');
	    redirect("events/{$page}");
	}

	// File or database
	if (!file_exists(APPPATH . '/views/pages/' . $page . '.php'))
	{
	    // Try to get page from database
	    log_message('debug', 'Getting page from database: ' . $page);

	    $article = new Article();
	    $article->slug = $page;
	    $article->find();

	    if ($article->title)
	    {
		$this->data['title'] = $article->title;
		$this->data['body'] = $article->body_html;
		$this->data['pubdate'] = $article->pubdate;
		$this->_render('article');
		return FALSE;
	    }
	    else
	    {
		// No page in database, no page exists
		show_404();
	    }
	}

	// default page title
	$this->data['title'] = ucwords($page);


	if ($page == 'home')
	{
	    // page specific stylesheets
	    $this->data['stylesheets'][] = base_url('assets/vendor/owlcarousel/owl.carousel.css');
	    $this->data['stylesheets'][] = base_url('assets/vendor/owlcarousel/owl.theme.css');
	    $this->data['stylesheets'][] = base_url('assets/vendor/rs-plugin/css/settings.css');
	    $this->data['stylesheets'][] = base_url('assets/vendor/circle-flip-slideshow/css/component.css');

	    // page specific javascripts
	    $this->data['scripts'][] = base_url('assets/vendor/owlcarousel/owl.carousel.js');
	    $this->data['scripts'][] = base_url('assets/vendor/owlcarousel/owl.carousel.js');
	    $this->data['scripts'][] = base_url('assets/vendor/rs-plugin/js/jquery.themepunch.revolution.min.js');
	    $this->data['scripts'][] = base_url('assets/vendor/rs-plugin/js/jquery.themepunch.tools.min.js');
	    $this->data['scripts'][] = base_url('assets/vendor/jflickrfeed/jflickrfeed.js');
	    $this->data['scripts'][] = base_url('assets/vendor/jquery.appear/jquery.appear.js');
	    $this->data['scripts'][] = base_url('assets/vendor/jquery.easing/jquery.easing.js');
	    $this->data['scripts'][] = base_url('assets/vendor/jquery-cookie/jquery-cookie.js');
	    $this->data['scripts'][] = base_url('assets/vendor/jquery.validation/jquery.validation.js');
	}

	if ($page == 'about')
	{
	    // page specific stylesheets

	    // page specific javascripts
	    $this->data['scripts'][] = base_url('assets/vendor/jquery.appear/jquery.appear.js');
	    $this->data['scripts'][] = base_url('assets/vendor/jquery.easing/jquery.easing.js');
	    $this->data['scripts'][] = base_url('assets/vendor/modernizr/modernizr.js');
	    $this->data['scripts'][] = base_url('assets/js/theme.init.js');
	}	

	$this->_render('pages/' . $page);
    }

}
