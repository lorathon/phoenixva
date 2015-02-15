<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rank extends PVA_Model {
	
	// Rank properties
	public $rank       = NULL;
	public $rank_image = NULL;
	public $min_hours  = NULL;
	public $pay_rate   = NULL;
	public $short      = NULL;
	
	function __construct($id = NULL)
	{
		parent::__construct($id);
		
		// Set default order
		$this->_order_by = 'min_hours asc';
	}
	
	/**
	 * Finds the next rank.
	 * 
	 * @return boolean|object Fully populated Rank object or FALSE on failure
	 */
	function next_rank()
	{
		if (is_null($this->id)) return FALSE;
				
		// Query the database
		$this->db->where('min_hours >', $this->min_hours);
		$query = $this->db->get($this->_table_name, 1);
		 
		// Did we get a result?
		if ($query->num_rows() > 0)
		{
			$next_rank = new Rank();
			
			$row = $query->row();
			
			$next_rank->id = $row->id;
			$next_rank->rank = $row->rank;
			$next_rank->rank_image = $row->rank_image;
			$next_rank->min_hours = $row->min_hours;
			$next_rank->pay_rate = $row->pay_rate;
			$next_rank->short = $row->short;
			
			return $next_rank;
		}
		return FALSE;
	}
}