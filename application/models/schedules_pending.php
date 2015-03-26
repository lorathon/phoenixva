<?php

class Schedules_pending extends PVA_Model {
	
	/* schedule properties */
	public $flight_id       = NULL;
	public $carrier         = NULL;
	public $operator        = NULL;
	public $flight_num      = NULL;
	public $dep_airport     = NULL;
	public $arr_airport     = NULL;
	public $equip           = NULL;
	public $tail_number 	= NULL;
	public $flight_type     = NULL;
	public $regional        = NULL;
	public $dep_time_local  = NULL;
	public $dep_time_utc    = NULL;
	public $dep_terminal    = NULL;
	public $dep_gate        = NULL;
	public $block_time      = NULL;
	public $taxi_out_time   = NULL;
	public $air_time        = NULL;
	public $taxi_in_time    = NULL;	
	public $arr_time_local  = NULL;
	public $arr_time_utc    = NULL;
	public $arr_terminal    = NULL;
	public $arr_gate        = NULL;
	public $downline_apt	= NULL;
	public $downline_fltId	= NULL;
        public $version         = NULL;

	
	
	
	function __construct()
	{
		parent::__construct();
		
                $this->_table_name = 'schedules_pending';
		// Set default order
		//$this->_order_by = 'dep_airport asc';
	}
	
}