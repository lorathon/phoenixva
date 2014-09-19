<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PVA Application base controller.
 * 
 * Ensures the database is current with the application and automatically
 * handles login redirection and admin checks. Private areas of the application
 * should have a URL like domain.com/private/ and admin areas should have a URL
 * like domain.com/admin/. All other URLs will be treated as public.
 * @author Chuck Topinka
 *
 */
class PVA_Controller extends CI_Controller {
    
	public $data = array();
    
    function __construct()
    {
        parent::__construct();
        
        // Styling and profiling
        $this->output->enable_profiler(TRUE);
        
        log_message('debug', 'PVA Controller class initialized');
        
        // Register autoloader
        spl_autoload_register(array('PVA_Controller','autoload'));
                
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
        }
        elseif ($access == 'private')
        {
        	// Any common stuff for private access goes here.
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
     * PVA Application autoloader
     * 
     * Allows calling objects without having to load models first.
     * XXX Not sure this is the best way to do this.
     * @param unknown $class
     */
    function autoload($class)
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