<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Session_log extends PVA_Model {
	
	/**
	 * ID of the user that instantiated this session originally
	 * 
	 * @var int
	 */
	public $user_id = NULL;
	
	/**
	 * IP address used by this user when the session was created
	 * 
	 * @var string
	 */
	public $ip_address = NULL;
	
	public function __construct($id=NULL)
	{
		$this->_timestamps = TRUE;
		$this->_order_by = 'modified desc';
		parent::__construct($id);
	}
	
	/**
	 * Adds or updates a log in the database.
	 * 
	 * The modified field is actually the last time the user logged in from
	 * a particular IP address.
	 * 
	 * @param int $user
	 * @param string $ip
	 */
	public function add_log($user, $ip)
	{
		$this->user_id = $user;
		$this->ip_address = $ip;
		
		// Use the find method to populate the ID if user/ip combo already exists
		if ($this->find())
		{
			$this->save();
		}
		else
		{
			$this->save();
		}
	}
	
	/**
	 * DO NOT USE
	 * 
	 * Use the add_log() function instead.
	 * (non-PHPdoc)
	 * @see PVA_Model::save()
	 */
	public function save()
	{
		// Override save function since it should never be used.
		return FALSE;
	}
}