<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aircraft_pending extends PVA_Model
{    
    public $equip		= NULL;
    public $name		= NULL;    
    public $regional            = NULL;
    public $turboprop           = NULL;
    public $jet                 = NULL;
    public $widebody            = NULL;
    public $created             = NULL;
    public $modified            = NULL;
    
    public function __construct()
    {
        parent::__construct();
        $this->_table_name = 'aircraft_pending';
        $this->_timestamps = TRUE;
    }
}

