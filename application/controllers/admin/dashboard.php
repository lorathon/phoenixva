<?php

class Dashboard extends PVA_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['sidebar'] = 'admin/dashboard/sidebar';
    }
    
    public function index()
    {
        $this->load->model('my_model/user_m');
        // Display Standard View
        $this->data['new_users'] = $this->user_m->get_status_type(array_search('New Registration', config_item('user_status')));
        $this->data['subview'] = 'admin/dashboard/index';
        $this->load->view('admin/_layout_main', $this->data);   
    }        
}