<?php

class Airlines extends PVA_Controller
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        	
	$obj = new Airline();
	$this->data['title'] = 'View All Airlines';
	$airlines = $obj->find_all();
	
	foreach($airlines as $airline)
        {            
            $category = $airline->get_category();            
	    $airline->category_name = $category->description;            
            $this->data['rows'][] = $airline;
        }
        
        $this->_render('admin/airlines');
    }     
}