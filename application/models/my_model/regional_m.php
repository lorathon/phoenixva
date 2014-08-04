<?php

class Regional_m extends MY_Model
{
    protected $_table_name = 'airlines_regional';
    protected $_order_by = 'icao'; 
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_dropdown()
    {
        $data = parent::get();        
        $_data = array();
        if(count($data) > 0)
        {
            foreach($data as $row)
                $_data[$row->icao] = $row->icao;        
        }        
        return $_data;
    }
}