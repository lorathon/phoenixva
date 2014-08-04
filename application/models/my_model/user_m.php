<?php

class User_m extends MY_Model
{
    protected $_table_name			= 'users';			// user accounts
	protected $_profile_table_name	= 'user_profiles';	// user profiles
    protected $_order_by            = 'id ASC';      
    
    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_status_type($status = 0)
	{
		$data = parent::get_by('users.status = ' . $status);
		return $data;		
	}
    
    public function get_with_profile($id = NULL, $single = FALSE)
    {
        $this->db->select('users.*, u.*, users.id as id');
        $this->db->join('user_profiles as u', 'u.user_id = users.id', 'left');
        $this->db->order_by('users.id ASC');
        if($id == NULL)
        {
            $data = parent::get($id, $single);
        }
        else
        {
            $data =  parent::get_by('users.id = ' . $id, $single);
        }
        return $data;        
    }
    
    public function save_account($data, $user_id)
    {
        $user_id = intval($user_id);        
        $this->db->set($data);
        $this->db->where('id', $user_id);
        $this->db->update($this->_table_name);
    }
    
    public function save_profile($data, $user_id)
    {
        $user_id = intval($user_id);        
        $this->db->set($data);
        $this->db->where('user_id', $user_id);
        $this->db->update($this->_profile_table_name);
    }
    
}