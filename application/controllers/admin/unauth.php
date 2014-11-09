<?php

class Unauth extends PVA_Controller
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