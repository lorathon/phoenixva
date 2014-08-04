<?php

class MY_Controller extends CI_Controller
{
    public $data = array();
    
    function __construct()
    {
        parent::__construct();
        
        // Load Site config file
        $this->config->load('PVA_config');        
        
        $this->data['errors'] = array();
        $this->data['site_name'] = config_item('site_name');
        $this->data['meta_title'] = config_item('site_name');
        
        // Logged In User's userdata
        $this->data['userdata'] = $this->session->all_userdata();
        
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
    
}