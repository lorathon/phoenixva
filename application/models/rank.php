<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rank extends PVA_Model {
	
	// Rank properties
	public $rank       = NULL;
	public $rank_image = NULL;
	public $min_hours  = NULL;
	public $pay_rate   = NULL;
	public $short      = NULL;
	
	function __construct()
	{
		parent::__construct();
		
		// Set default order
		$this->_order_by = 'min_hours asc';
	}
}