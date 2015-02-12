<?php

class Awards extends PVA_Controller
{
    protected $_table_name = 'awards';
    protected $_order_by = 'id';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('award');
    }
    
    public function index()
    {    
        $award = New Award();
        $awards = $award->find_all();
        $this->_no_cache();
        $this->data['awards'] = $awards;
        $this->data['types'] = config_item('award_types');
        $this->_render('admin/awards');
    }     
}