<?php

class Article extends PVA_Controller
{
    protected $_table_name = 'articles';
    protected $_order_by = 'id';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {     
        
    }     
}