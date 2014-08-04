<?php

class Finance_Prices_m extends MY_Model
{
    protected $_table_name = 'finance_prices';
    protected $_order_by = 'id'; 
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('calculations');
    }
    
    public function economy_price($distance)
    {        
        $priceMin = parent::get_by("name = 'PRICE_ECO_MIN'", TRUE);
        $priceMax = parent::get_by("name = 'PRICE_ECO_MAX'", TRUE);
        
        return $this->calculations->random_pricing($distance, $priceMin->value, $priceMax->value);
    }
    
    public function business_price($distance)
    {        
        $priceMin = parent::get_by("name = 'PRICE_BUS_MIN'", TRUE);
        $priceMax = parent::get_by("name = 'PRICE_BUS_MAX'", TRUE);
        
        return $this->calculations->random_pricing($distance, $priceMin->value, $priceMax->value);
    }
    
    public function first_price($distance)
    {        
        $priceMin = parent::get_by("name = 'PRICE_FIR_MIN'", TRUE);
        $priceMax = parent::get_by("name = 'PRICE_FIR_MAX'", TRUE);
        
        return $this->calculations->random_pricing($distance, $priceMin->value, $priceMax->value);
    }
    
    public function cargo_price($distance)
    {        
        $priceMin = parent::get_by("name = 'PRICE_CAR_MIN'", TRUE);
        $priceMax = parent::get_by("name = 'PRICE_CAR_MAX'", TRUE);
        
        return $this->calculations->random_pricing($distance, $priceMin->value, $priceMax->value);
    }
}