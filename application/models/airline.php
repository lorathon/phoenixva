<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Airline extends PVA_Model {
	
	/* Airline properties */
	public $fs		= NULL;
	public $iata		= NULL;
	public $icao		= NULL;
	public $name		= NULL;
	public $active		= NULL;
	public $category	= NULL;
	public $fuel_discount	= NULL;
	public $airline_image	= NULL;
	public $total_schedules	= NULL;
	public $total_pireps	= NULL;
	public $total_hours	= NULL;
	public $regional	= NULL;	
	
	protected $_cat		= NULL;
	
	function __construct($id = NULL)
	{
		parent::__construct($id);
	}
	
	function get_category()
	{
	    if(is_null($this->_cat))
	    {
		$this->_cat = new Airline_category();
		$this->_cat->value = $this->category;
		$this->_cat->find();
	    }
	    return $this->_cat;
	}
	
	function get_dropdown()
        {
	    $this->_limit = NULL;
	    $this->_order_by = 'name ASC';
	    
            $rows = $this->find_all();
            
            $data = array();
	    $data[0] = '-- NONE --';
            foreach($rows as $row)
            {
                $data[$row->id] = $row->name;
            }      
            return $data;
        }
	
	function get_category_dropdown()
        {
	    $cat = new Airline_category();
	    $rows = $cat->find_all();
            
            $data = array();
            foreach($rows as $row)
            {
                $data[$row->value] = $row->description;
            }      
            return $data;
        }
}

class Airline_category extends PVA_Model {
    
	public $value	    = NULL;
	public $description = NULL;
	public $passenger   = NULL;
	public $cargo	    = NULL;
	
	function __construct($id = NULL)
	{	    
	    parent::__construct($id);
	    $this->_table_name = 'airlines_categories';
	}
	
}