<?php

class Unauth extends MY_Controller
{
    function __construct()
    {
        parent::__construct(); 
    }
    
    public function index()
    {
        $this->data['subview'] = 'admin/unauth_access';
        $this->load->view('admin/_layout_modal', $this->data);
    }
}