<?php

class Schedule_m extends MY_Model
{
    protected $_table_name = '';
    protected $_table_name_active = 'schedules_active';
    protected $_table_name_pending = 'schedules_pending';
    protected $_table_name_archive = 'schedules_archive';
    protected $_order_by = 'id';
    protected $_timestamps = TRUE;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function move_pending($id = NULL)
    {
        if($id === NULL)
            return FALSE;
        
        // Retrieve the schedule from the pending table by id
        $this->_table_name = $this->_table_name_pending;
        $data = parent::get($id, TRUE);
        /* Check to be sure that a schedule has been retrieved from the pending table
         * return FALSE
        */
        if(empty($data))
            return FALSE;
                
        // Insert the retrieved schedule into the active table (including the id)
        $this->db->set((array)$data);
        $this->db->insert($this->_table_name_active);
        /* Check to ensure that the schedule was inserted into the active table
         * return FALSE
        */
        if($this->db->affected_rows() != 1)
            return FALSE;
                
        // Delete the moved schedule from the pending table by id
        $this->_table_name = $this->_table_name_pending;
        $data = parent::delete($id);
        /* Check to ensure that the schedule was deleted from the pending table
         * If it was not deleted then delete the schedule from active
         * return FALSE
        */
        if($this->db->affected_rows() != 1)
        {
            $this->_table_name = $this->_table_name_active;
            $data = parent::delete($id);
            return FALSE;
        }
        return TRUE;
    }
    
    public function move_active($id = NULL)
    {
        if($id === NULL)
            return FALSE;
        
        // Retrieve the schedule from the active table by id
        $this->_table_name = $this->_table_name_active;
        $data = parent::get($id, TRUE);
        /* Check to be sure that a schedule has been retrieved from the active table
         * return FALSE
        */
        if(empty($data))
            return FALSE;
                
        // Insert the retrieved schedule into the archive table (including the id)
        $this->_table_name = $this->_table_name_archive;
        parent::save_with_id((array)$data);
        //$this->db->set((array)$data);
        //$this->db->insert($this->_table_name_archive);
        /* Check to ensure that the schedule was inserted into the archive table
         * return FALSE
        */
        if($this->db->affected_rows() != 1)
            return FALSE;
                
        // Delete the moved schedule from the active table by id
        $this->_table_name = $this->_table_name_active;
        $data = parent::delete($id);
        /* Check to ensure that the schedule was deleted from the active table
         * If it was not deleted then delete the schedule from archive
         * return FALSE
        */
        if($this->db->affected_rows() != 1)
        {
            $this->_table_name = $this->_table_name_archive;
            $data = parent::delete($id);
            return FALSE;
        }
        return TRUE;
    }
}