<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Airline extends PVA_Model {
	
	/* Airline properties */
	public $fs		= NULL;
	public $iata		= NULL;
	public $icao		= NULL;
	public $name		= NULL;
	public $active		= NULL;
	public $category	= NULL;
	public $fuel_discount	= 0;
	public $airline_image	= NULL;
	public $total_schedules	= 0;
	public $total_pireps	= 0;
	public $total_hours	= 0;
	public $regional	= 0;
	
	function __construct($id = NULL)
	{
		parent::__construct($id);
	}
}