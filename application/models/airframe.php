<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Airframe extends PVA_Model
{
    /* Airframe properties */

    public $iata = NULL;
    public $icao = NULL;
    public $name = NULL;
    public $aircraft_sub_id = NULL;
    public $category = NULL;
    public $regional = NULL;
    public $turboprop = NULL;
    public $jet = NULL;
    public $widebody = NULL;
    public $pax_first = NULL;
    public $pax_business = NULL;
    public $pax_economy = NULL;
    public $payload = NULL;
    public $max_range = NULL;
    public $oew = NULL;
    public $mzfw = NULL;
    public $mtow = NULL;
    public $mlw = NULL;
    public $enabled = NULL;

    public function __construct($id = NULL)
    {
        $this->_order_by = 'name ASC';
        $this->_timestamps = TRUE;
        parent::__construct($id);
    }
    
    /**
     * Updates airframes on any
     * Aircraft_sub catergory changes
     */
    function update_categories()
    {
        $category = $this->category;
        $this->category = NULL;
        $this->_limit = $this->find_all(FALSE, TRUE);
        $airframes = $this->find_all();
        foreach ($airframes as $obj)
        {
            $obj->category = $category;
            $obj->save();
        }
        $this->category = $category;
    }
    
    public function datatable()
    {
        $this->datatables->select('id,iata,icao,name,aircraft_sub_id,category,pax_first,pax_business,pax_economy,payload,max_range,oew,mzfw,mtow,mlw')->from('airframes');
        echo $this->datatables->generate();
    }

}