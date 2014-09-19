<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PVA Application base controller.
 * 
 * Ensures the database is current with the application and automatically
 * handles login redirection and admin checks. Private areas of the application
 * should have a URL like domain.com/private/ and admin areas should have a URL
 * like domain.com/admin/. All other URLs will be treated as public.
 * 
 * General controller usage should be to instantiate and populate models, set the
 * necessary values in the $data array, and then call $this->_render('view_name');
 * where 'view_name' = the view to render. This will automatically render the 
 * view within the appropriate template.
 * 
 * @author Chuck Topinka
 *
 */
class PVA_Controller extends CI_Controller {
    
	public $data    = array();
	private $_access = 'public';
    
    function __construct()
    {
        parent::__construct();
        
        // Styling and profiling
        $this->output->enable_profiler(TRUE);
        
        log_message('debug', 'PVA Controller class initialized');
        
        // Register autoloader
        spl_autoload_register(array('PVA_Controller','_autoload'));
                
        // Ensure database is current
        $this->load->library('migration');
        
        if ( ! $this->migration->current())
        {
        	show_error($this->migration->error_string());
        	log_message('error', 'Migration error: '.$this->migration->error_string());
        }
        
        // Load PVA config file
        $this->config->load('pva_config');        
        
        $this->data['errors'] = array();
        $this->data['site_name'] = config_item('site_name');
        $this->data['meta_title'] = config_item('site_name');
        
        // Public, private or admin access
        $access = $this->uri->segment(1);
        
        if ($access == 'admin' OR $access == 'private')
        {
        	// Verify user logged in
        	$this->library->load('tank_auth');
        	
        	if ( ! $this->tank_auth->is_logged_in())
        	{
        		// User not logged in so redirect to login
        		redirect('/auth/login/');
        	}
        	
        	// Logged In User's userdata
        	$this->data['userdata'] = $this->session->all_userdata();
        }
        
        if ($access == 'admin')
        {
        	if($this->data['userdata']['admin'] < 2)
        	{
        		// NO admin Credentials found.  Redirect to UNAUTH page
        		redirect('/auth/unauth/');
        	}
        	
        	// Load Admin config file
        	$this->config->load('admin/admin_config');
        	
        	// Indicate admin area
        	$this->data['site_name']  .= ' - Admin';
        	$this->data['meta_title'] .= ' - Admin';
        	$this->_access = 'admin';
        }
        elseif ($access == 'private')
        {
        	// Any common stuff for private access goes here.
        	$this->_access = 'private';
        }
        
        
        
        /* FRONT END ALERT
         * If a session message is set shove into main data array
         * Change to read ('item', 'value') or an array?
         * OPTIONS:
         * $this->session->set_flashdata('success', 'MESSAGE'); Green
         * $this->session->set_flashdata('warning', 'MESSAGE'); Amber
         * $this->session->set_flashdata('error', 'MESSAGE'); Red
         * $this->session->set_flashdata('info', 'MESSAGE'); Blue
        */
        
        /* ADMIN Alert */
        if($this->session->flashdata('alert_message') != '')
        {
            $this->data['alert'] = TRUE;
            $this->data['alert_type'] = $this->session->flashdata('alert_type');
            $this->data['alert_message'] = $this->session->flashdata('alert_message');
        }
        else
        {
            $this->data['alert'] = FALSE;
        }
    }
    
    /**
     * Renders views as part of overall template
     * 
     * This method should be called to display a view to ensure it is wrapped in
     * the template as appropriate.
     * @param string|array $view The view(s) to render.
     * TODO Have the view default to the current controller name.
     */
    protected function _render($view)
    {
    	// Get the view and place in view_output
    	if (is_array($view))
    	{
    		foreach ($view as $subview)
    		{
    			$this->data['view_output'] .= $this->load->view($view, $this->data, TRUE);
    		}
    	}
    	else 
    	{
    		$this->data['view_output'] = $this->load->view($view, $this->data, TRUE);
    	}
    	
    	if ($this->_access == 'admin')
    	{
    		// Load the admin template
    		$this->load->view('templates/admin', $this->data);
    	}
    	else 
    	{
    		// Load the site template
    		$this->load->view('templates/pva', $this->data);
    	}
    }

    /**
     * PVA Application autoloader
     * 
     * Allows calling objects without having to load models first.
     * XXX Not sure this is the best way to do this.
     * @param string $class The class name to load.
     */
    protected function _autoload($class)
    {
   		if(strpos($class, 'CI') !== 0)
   		{
   			$path = array('core', 'libraries', 'models');
   			foreach ($path as $dir)
   			{
   				$file = APPPATH . 'libraries/' . $class . '.php';
   				if(file_exists($file) && is_file($file))
   					@include_once($file);
   			}
   		}    	 
    }
}

/* End of file PVA_Controller.php */
/* Location: ./application/core/PVA_Controller.php */