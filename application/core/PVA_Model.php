<?php

class PVA_Model extends CI_Model
{
    protected $_table_name = '';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = '';
    public $_rules = array();
    protected $_timestamps = FALSE;
    
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function array_from_post($fields)
    {
        // Return array from Post data
        $data = array();
        foreach($fields as $field)
        {
            $data[$field] = $this->input->post($field);
        }
        return $data;
    }
    
    public function get($id = NULL, $single = FALSE)
    {
        
        if($id != NULL)
        {
            // Return single row if id is passed
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->where($this->_primary_key, $id);
            $method = 'row';
        }
        elseif($single == TRUE)
        {
            // Return single row if single is TRUE
            $method = 'row';
        }
        else
        {
            // Return array 
            $method = 'result';
        }
        
        // Check if order by is set.
        // If not use class variable
        if(!count($this->db->ar_orderby))
        {
            $this->db->order_by($this->_order_by);                   
        }
        
        // Fetch query
        return $this->db->get($this->_table_name)->$method();
    }
    
    public function get_by($where, $single = FALSE)
    {
        // Get using a where statement Send to get()
        $this->db->where($where);
        return $this->get(NULL, $single);
    }
    
    public function save($data, $id = NULL)
    {
        // Check if Timestamps variable is TRUE
        if($this->_timestamps == TRUE)
        {
            // If TRUE create timestamps
            $now = date('Y-m-d H:i:s');
            $id || $data['created'] = $now;
            $data['modified'] = $now;
        }
        // Insert if id is NOT passed
        if($id === NULL)
        {            
            if(isset($data[$this->_primary_key]))
                $data[$this->_primary_key] = NULL;
                
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        }
        // Update if id is passed
        else
        {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }
        return $id;
    }
    
    public function save_with_id($data)
    {        
        // Check if Timestamps variable is TRUE
        if($this->_timestamps == TRUE)
        {
            // If TRUE create timestamps
            $now = date('Y-m-d H:i:s');
            $data['modified'] = $now;
        }
        
        $this->db->set((array)$data);
        $this->db->insert($this->_table_name);
        
        return $this->db->insert_id();
    }
    
    public function delete($id)
    {
        // Delete row based on id passed
        $filter = $this->_primary_filter;
        $id = $filter($id);
        
        if(!$id)
        {
            return FALSE;
        }
        $this->db->where($this->_primary_key, $id);
        $this->db->limit(1);
        $this->db->delete($this->_table_name);
    }
    
}