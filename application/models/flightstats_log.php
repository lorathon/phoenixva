<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Flightstats_log extends PVA_Model
{    
    public $type		= NULL;
    public $version             = NULL;
    public $fs                  = NULL;
    public $note                = NULL;
    
    public function __construct()
    {
        parent::__construct();
    }    
}

