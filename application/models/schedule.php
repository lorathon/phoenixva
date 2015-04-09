<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends PVA_Model {
	
	/* schedule properties */
	public $flight_id       = NULL;
	public $carrier_id	= NULL;
	public $operator_id	= NULL;
	public $flight_num      = NULL;
	public $dep_airport_id	= NULL;
	public $arr_airport_id	= NULL;
	public $airframe_id	= NULL;
	public $schedule_cat_id	= NULL;
	public $regional        = NULL;
	public $brand		= NULL;
	public $dep_time_local  = NULL;
	public $dep_time_utc    = NULL;
	public $dep_terminal    = NULL;
	public $block_time      = NULL;
	public $arr_time_local  = NULL;
	public $arr_time_utc    = NULL;
	public $arr_terminal    = NULL;
        public $version         = NULL;
	
	function __construct()
	{
		parent::__construct();
	}
	
}