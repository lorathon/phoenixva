<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Schedules_pending extends PVA_Model
{
    /* Schedules_pending properties */
    public $carrier = NULL;
    public $operator = NULL;
    public $flight_num = NULL;
    public $dep_airport = NULL;
    public $arr_airport = NULL;
    public $equip = NULL;
    public $service_type = NULL;
    public $service_classes = NULL;
    public $regional = NULL;
    public $brand = NULL;
    public $dep_time_local = NULL;
    public $dep_time_utc = NULL;
    public $dep_terminal = NULL;
    public $block_time = NULL;
    public $arr_time_local = NULL;
    public $arr_time_utc = NULL;
    public $arr_terminal = NULL;
    public $sun = NULL;
    public $mon = NULL;
    public $tue = NULL;
    public $wed = NULL;
    public $thu = NULL;
    public $fri = NULL;
    public $sat = NULL;
    public $version = NULL;
    public $consumed = NULL;
    public $created = NULL;
    public $modified = NULL;

    function __construct($id = NULL)
    {
	$this->_table_name = 'schedules_pending';
	$this->_timestamps = TRUE;
	parent::__construct($id);
    }
    
}
