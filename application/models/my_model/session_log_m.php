<?php

class Session_Log_m extends PVA_Model {
    protected $_table_name			= 'session_log';	
    protected $_order_by            = 'id ASC';
    protected $_timestamps          = TRUE;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function log($user_id)
    {
        $ip_address = $this->input->ip_address();
        
        if(trim($user_id) == '')
            return FALSE;
        if(trim($ip_address) == '')
            return FALSE;
        
        $data = array(
                'user_id'       => $user_id,
                'ip_address'    => $ip_address,
                );
        
        $row_id = $this->check_log($user_id, $ip_address);
        
        if($row_id === FALSE)
        {
            // create a new seesion log record 
            parent::save($data);
        }
        else
        {            
            // update session log record
            parent::save($data, $row_id);
        }
    }
	
    public function check_log($user_id, $ip_address)
    {  
        $where = "user_id = " . $user_id . " AND `ip_address` = '" . $ip_address . "'";        
        $data = parent::get_by($where, TRUE);
        
        if(count($data) > 0)
            return $data->id;
        else
            return FALSE;
    }
    
}