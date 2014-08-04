<?php

class User_Award_m extends MY_Model
{
    protected $_table_name = 'user_awards';
    protected $_order_by = 'id';
    protected $_timestamps = TRUE;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function grant_award($user_id, $award_id)
    {
        if($this->check_award($user_id, $award_id))
            return;
        
        $data = array(
            'user_id'   => $user_id,
            'award_id'  => $award_id            
            );        
        parent::save($data);
    }
    
    public function remove_award($user_id, $award_id)
    {
        $data = array(
            'user_id'   => $user_id,
            'award_id'  => $award_id            
            );
        $row = parent::get_by($data, TRUE);
        parent::delete($row->id);
    }
    
    protected function check_award($user_id, $award_id)
    {
        $data = array(
            'user_id'   => $user_id,
            'award_id'  => $award_id            
            );
        $row = parent::get_by($data, TRUE);
        
        if(count($row) == 0)
            // return FALSE if no record found
            return FALSE;
        else
            // return TRUE in record found
            return TRUE;
    }
}