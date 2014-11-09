<?php

/**
 * Private controller
 * @author Chuck
 * @deprecated Use PVA_Controller instead. 
 */
class Private_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        
        // Load helps/libraries/model to be used throughout Private Areas
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // Load Grocery CRUD
        $this->load->library('grocery_CRUD');               
        
        /*
         * Check login for each private call
         * If logged in then allow through
         * If NOT logged in show login form
        */
        if (!$this->tank_auth->is_logged_in()) {
            // User not logged in so redirect to login
            redirect('/auth/login/');
        } 
    }
}