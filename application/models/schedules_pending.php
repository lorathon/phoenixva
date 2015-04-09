<?php

class Schedules_pending extends PVA_Model {
	
	/* schedule properties */
	public $carrier         = NULL;
	public $operator        = NULL;
	public $flight_num      = NULL;
	public $dep_airport     = NULL;
	public $arr_airport     = NULL;
	public $equip           = NULL;
	public $service_type    = NULL;
        public $service_classes = NULL;
	public $regional        = NULL;
        public $brand           = NULL;
	public $dep_time_local  = NULL;
	public $dep_time_utc    = NULL;
	public $dep_terminal    = NULL;
	public $block_time      = NULL;	
	public $arr_time_local  = NULL;
	public $arr_time_utc    = NULL;
	public $arr_terminal    = NULL;
        public $sun             = NULL;
        public $mon             = NULL;
        public $tue             = NULL;
        public $wed             = NULL;
        public $thu             = NULL;
        public $fri             = NULL;
        public $sat             = NULL;
        public $version         = NULL;
        public $created         = NULL;
        

	
	
	
	function __construct()
	{
		parent::__construct();
		
                $this->_table_name = 'schedules_pending';
		// Set default order
		//$this->_order_by = 'dep_airport asc';
	}
	
}