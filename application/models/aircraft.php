<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aircraft extends PVA_model
{    
    public $equip		= NULL;
    public $category		= NULL;
    public $carrier_count	= NULL;
    public $operator_count	= NULL;
    public $flight_count	= NULL;
    
    private $_schedules_table = 'schedules';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function create_equip()
    {	
	$this->db->select('equip')
		->select('COUNT(DISTINCT `carrier`) as carrier_count')
		->select('COUNT(DISTINCT `operator`) as operator_count')
		->select('COUNT(`id`) as flight_count')
		->from($this->_schedules_table)
		->group_by('equip');
	
	$query = $this->db->get();
	
	$rows = $this->_get_objects($query);
	
	if($rows)
	{
	    foreach($rows as $row)
	    {
		$aircraft = new Aircraft();
		$aircraft->equip = $row->equip;
		$aircraft->find();
		$aircraft->carrier_count = $row->carrier_count;
		$aircraft->flight_count = $row->flight_count;
		$aircraft->operator_count = $row->operator_count;
		$aircraft->category = 0;
		$aircraft->save();
	    }
	}
    
    }
}

