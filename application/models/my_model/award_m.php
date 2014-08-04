<?php

class Award_m extends MY_Model
{
    protected $_table_name = 'awards';
    protected $_order_by = 'id'; 
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_dropdown()
    {
        $airports = parent::get();        
        $_airports = array();
        if(count($airports) > 0)
        {
            foreach($airports as $airport)
                $_airports[$airport->icao] = $airport->icao;        
        }        
        return $_airports;
    }
}