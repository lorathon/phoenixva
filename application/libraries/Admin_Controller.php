<?php

/**
 * Admin controller
 * @author Chuck
 * @deprecated Use PVA_Controller instead.
 */
class Admin_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        
        // Admin meta title
        $this->data['meta_title'] = 'PVA - Admin';
        
        // Load helps/libraries/model to be used throughout Admin
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // Load Grocery CRUD
        $this->load->library('grocery_CRUD');
        
        // Load Admin config file
        $this->config->load('admin/admin_config');
        
        // Load Callback Model
        $this->load->model('callback_model');
        
                
        /*
         * Check login for each admin call
         * If logged in then allow through
         * If NOT logged in show login form
        */
        if (!$this->tank_auth->is_logged_in()) {
            // User not logged in so redirect to login
            redirect('/auth/login/');
        } else {
            // User is logged in. Check for Admin Credentials
            if($this->data['userdata']['admin'] < 2)
            {
                // NO admin Credentials found.  Redirect to UNAUTH page
                redirect('admin/unauth');
            }
        }
    }    
}