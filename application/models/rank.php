<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rank extends PVA_Model {
	
	// Rank properties
	public $rank       = NULL;
	public $rank_image = NULL;
	public $min_hours  = NULL;
	public $pay_rate   = NULL;
	public $short      = NULL;
	public $max_cat	    = NULL;
	
	protected $_users	    = NULL;
	protected $_user_count	    = NULL;
		
	public function __construct($id = NULL)
	{
		parent::__construct($id);
		
		// Set default order
		$this->_order_by = 'min_hours asc';
	}
	
	/**
	 * Finds the next rank.
	 * 
	 * This function can be used to find the next rank either above or below
	 * the current rank.
	 * 
	 * @param boolean $down Set to TRUE to find the previous rank.
	 * @return boolean|object Fully populated Rank object or FALSE on failure
	 */
	public function next_rank($down = FALSE)
	{
		if (is_null($this->id)) return FALSE;
				
		// Query the database
		($down) ? $op = '<' : $op = '>';
		$this->db->where('min_hours '.$op, $this->min_hours);
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
	
	/**
     * Return array of Users with
     * rank of rank_id
     * 
     * @return array of User Objects
     */
	public function get_users()
	{
	    if(is_null($this->_users))
	    {
		$user = new User();
		$user->rank_id = $this->id;
		$this->_users = $user->find_all();
	    }	    
	    return $this->_users;
	}
	
	/**
	 * Return number of user with
	 * rank of rank_id
	 *
	 * @return int of Users with rank
	 */
	public function get_user_count()
	{
	    if ( is_null($this->_user_count))
	    {		
		$user = new User();
		$user->rank_id = $this->id;
		$users = $user->find_all();
		$this->_user_count = ($users) ? count($users) : 0;
	    }
	    return $this->_user_count;
	}
	
	/**
	 * Checks if this rank can fly the provided aircraft.
	 * 
	 * @param integer $aircraft_id of the Airline_aircraft to check
	 * @return boolean TRUE if the rank can fly the aircraft.
	 */
	public function check_aircraft_category($aircraft_id)
	{
		$aircraft = new Airline_aircraft($aircraft_id);
		return $this->check_airframe_category($aircraft->airframe_id);
	}
	
	/**
	 * Checks if this rank can fly the provided airframe.
	 * 
	 * @param integer $airframe_id for the airframe to check
	 * @return boolean TRUE if the rank can fly the airframe.
	 */
	public function check_airframe_category($airframe_id)
	{
		$airframe = new Airframe($aircraft->airframe_id);
		return ($airframe->category > $this->max_cat);
	}
}