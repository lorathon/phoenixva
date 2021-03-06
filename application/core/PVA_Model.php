<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PVA Application base model
 * 
 * Uses Kohana-esque ORM features.
 * 
 * @author Chuck
 *
 */
class PVA_Model extends CI_Model
{
    protected $_table_name      = NULL;
    protected $_object_name     = NULL;
    protected $_primary_key     = 'id';
    protected $_primary_filter  = 'intval';
    protected $_order_by        = NULL;
    protected $_timestamps      = FALSE;
    
    // ID
    public $id                  = NULL;
    
    // Default offset
    protected $_offset          = 0;
    
    // Default limit
    protected $_limit           = 25;    
    
    function __construct($id = NULL)
    {
        parent::__construct();
        
        // Connect to data store
        $this->load->database();
        
	
        // Set the object name
	if( is_null($this->_object_name))
	{
	    $this->_object_name = strtolower(get_class($this));	    
	}
	log_message('debug', 'Object name: '.$this->_object_name);
        
	
        // Guess the table name
	if( is_null($this->_table_name))
	{
	    $this->_table_name = strtolower(get_class($this).'s');
	}
        log_message('debug', 'Table name: '.$this->_table_name);
        
	
        // If the id is set, create a populated model (Kohana-esque)
    	if ( ! is_null($id))
		{
			if (is_array($id))
			{
				log_message('debug', 'Object created with array');
				// Passing an array of column => values
				foreach ($id as $field => $value)
				{
					if (substr($field,0,1) != '_')
					{
						$this->$field = $value;
					}
					else 
					{
						log_message('error', "Ignoring illegal property in {$this->_object_name} constructor: {$field}");
					}
				}
			}
			else
			{
				log_message('debug', 'Object created with ID');
				// Passing the primary key
				$key = $this->_primary_key;
				$this->$key = $id;
			}
			$this->find();
		}        
    }
        
    /**
     * 
     * @param unknown $fields
     * @return multitype:NULL
     * @deprecated Models should not get post input directly
     */
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
    
    /**
     * Finds a record in the database based on the current object.
     * 
     * Note that this method will only populate the current object with the 
     * first result from the database (a LIMIT 1). If you are expecting to find
     * multiple records use the find_all method.
     * 
     * @return boolean FALSE if no record was found.
     */
    public function find()
    {
    	if (is_null($this->id))
    	{
    		// Searching with parameters
    		
    		// Prep the data
    		$parms = $this->_prep_data();    		
                
    		if (count($parms) == 0)
    		{
    			// Nothing to find
    			return FALSE;
    		}
    		
    		$this->db->select()
    		         ->from($this->_table_name)
    		         ->where($parms)
    		         ->limit(1);
    	}
    	else
    	{
    		// Getting by ID
    		$this->db->select()
    		         ->from($this->_table_name)
    		         ->where($this->_primary_key, $this->id)
    		         ->limit(1);
    	}
        
    	// Query the database
    	$query = $this->db->get();
    	
    	// Did we get a result?
    	if ($query->num_rows() > 0)
    	{
    		// Populate the current object
    		$result = $query->row_array();
    		$keys = array_keys($result);
    		foreach ($keys as $key)
    		{
    			$this->$key = $result[$key];
    		}
    		return TRUE;
    	}
    	return FALSE;
    }
    
    /**
     * Finds all objects similar to the current object.
     * 
     * Create an object populated with the search criteria, then call find_all on
     * that object to return an array of populated objects of the same type.
     * 
     * This method is subject to limit and offset settings unless $count is TRUE.
     * 
     * @param boolean TRUE to perform a LIKE search. Defaults to WHERE clause.
     * @param boolean TRUE to just count all of the results.
     * @throws Exception if the object id is populated.
     * @return array |int |boolean Array of populated objects if found, an integer
     * representing the number of matching records or FALSE if no records were 
     * found. 
     */
    public function find_all($search = FALSE, $count = FALSE)
    {
    	if (! is_null($this->id))
    	{
    		// Improper usage
    		throw new Exception('Method find_all() cannot be called with id.');
    	}
    	
    	// Prep the data
    	$parms = $this->_prep_data();

    	// Build the query
    	$this->db->select()
    	         ->from($this->_table_name);
    	
    	// Search allows a LIKE. Default is a WHERE.
    	if ($search)
    	{
    		$this->db->like($parms);
    	}
    	else 
    	{
    		$this->db->where($parms);
    	}

    	// Just get the count of the results
    	if ($count)
    	{
    		return $this->db->count_all_results();
    	}
    	 
    	// XXX Creates opportunity for massive performance issue. Remove before production. [CJT]
        /* 
         * Added NULL check to allow for ALL records to be found
         * 03/04/2015
         * JFK
         */        
		if (! is_null($this->_limit) )
	    	$this->db->limit($this->_limit, $this->_offset);
    	
    	if ( ! is_null($this->_order_by))
    	{
    		$this->db->order_by($this->_order_by);
    	}
    	
    	// Query the database
    	$query = $this->db->get();
    	
    	return $this->_get_objects($query);
    }
    
    /**
     * Gets a datatables.net return
     * 
     * @param string $columns The columns to include in the output.
     * @return Ambigous <string, mixed>
     */
    public function get_datatable($columns = NULL)
    {
        log_message('debug', 'PVA_Model get_datatable()');
        $datatable = new Datatables();
        
        if (is_null($columns))
        {
            log_message('debug', 'Getting column list');         
            $props = get_object_vars($this);
            $first = TRUE;
            foreach ($props as $key => $value)
            {
                if (substr($key,0,1) != '_')
                {
                    if (!$first)
                    {
                    	$columns .= ', ';
                    }
                    
                    $columns .= $key;
                    
                    if ($first) $first = FALSE;
                }
            }
        }
        log_message('debug', 'Columns: '.$columns);
        $datatable->select($columns)->from($this->_table_name);
        return $datatable->generate();
    }
        
    /**
     * Allows access to all properties of an object.
     * 
     * Normally this method only needs to be used to access protected or private
     * properties in error messages and stuff. All normal properties should be
     * accessed using $object->property.
     * 
     * @param string $property The object property to get
     * @return mixed The requested property
     */
    public function get_property($prop)
    {
    	return $this->$prop;
    }
    
    /**
     * Saves a record in the database
     * 
     * If the id is populated this method will perform an update. For this reason
     * it is important to make sure that if the id is populated the entire object
     * must also be populated.
     */
    public function save()
    {
        // Check if Timestamps variable is TRUE
        if ($this->_timestamps == TRUE)
        {
            // If TRUE create timestamps
            $now = date('Y-m-d H:i:s');
            if (is_null($this->id) || $this->id == '')
            {
            	$this->created = $now;
            }
            $this->modified = $now;
        }
                
        /*
         * Addition of the '' check.
         * 02/13/2015
         * JFK
         */
        // Insert or update
        if (is_null($this->id) || ($this->id == ''))
        {
            // Insert if id is NOT passed
            $this->id = NULL;  // ensure that id is NULL for insert
            $this->db->insert($this->_table_name, $this->_prep_data());
            $this->id = $this->db->insert_id();
        }
        else
        {
            // Update if id is passed
            $this->db->where($this->_primary_key, $this->id);
            $this->db->update($this->_table_name, $this->_prep_data());            
        }
    }

    /**
     * Deletes the object
     * 
     * The object must have the primary key populated prior to deleting.
     * 
     * @return boolean FALSE if the object ID is not populated.
     */
    public function delete()
    {        
        if (is_null($this->id))
        {
            return FALSE;
        }
        $this->db->where($this->_primary_key, $this->id);
        $this->db->limit(1);
        $this->db->delete($this->_table_name);
    }
    
    /**
     * Sets a note for the current object.
     * 
     * @param string $note to record
     * @param int $user_id of the user making the note
     * @param boolean $private TRUE if the note should only be viewable by admins
     * (defaults to FALSE)
     */
    public function set_note($msg, $user_id, $private = FALSE)
    {
    	$note = new Note();
    	$note->entity_type = $this->_object_name;
    	$key = $this->_primary_key;
    	$note->entity_id = $this->$key;
    	$note->user_id = $user_id;
    	$note->note = $msg;
    	$note->private_note = $private;
    	$note->save();
    }
    
    /**
     * Gets the notes for the current object.
     * 
     * This is subject to limit and offset settings. If $private is set to true
     * then only the private notes will be returned. To get all private and
     * public notes requires too function calls.
     * 
     * @param boolean $private TRUE if the private notes should be returned.
     * (defaults to FALSE)
     * @return Ambigous <boolean, multitype:>
     */
    public function get_notes($private = FALSE)
    {
    	// Caching unnecessary since Note->get_notes already caches
    	$note = new Note();
    	$note->entity_type = $this->_object_name;
    	$key = $this->_primary_key;
    	$note->entity_id = $this->$key;
    	$note->private_note = $private;
    	return $note->get_notes();
    }
    
    /**
     * Returns an array of objects.
     * 
     * Using a standard return will provide objects of stdClass but those won't
     * include the additional methods. This method will create true objects that
     * include the additional methods.
     * 
     * @param object $query Query object for the database.
     * @return array |boolean Array of objects or FALSE if none found.
     */
    protected function _get_objects($query)
    {    	 
    	if ($query->num_rows() > 0)
    	{
    		// Results found, create array for the output
    		$obj_array = array();
    	
    		// Set first run
    		$first = TRUE;
    		foreach ($query->result_array() as $row)
    		{
    			// Create a new object
    			$obj = new $this->_object_name;
    			if ($first)
    			{
    				// First run, get the keys
    				$keys = array_keys($row);
    				$first = FALSE;
    			}
    			foreach ($keys as $key)
    			{
    				// Populate the object
    				$obj->$key = $row[$key];
    			}
    			$obj_array[] = $obj;
    		}
    		return $obj_array;
    	}
    	return FALSE;
    }
    
    /**
     * Uses the current object to get an array suitable for queries.
     * 
     * @return array containing the populated, public properties.
     */
    protected function _prep_data()
    {
    	$parms = array();
    	
    	$props = get_object_vars($this);
    	
    	foreach ($props as $key => $prop)
    	{
    		// If a property is null or starts with underscore, do not include it.
    		if ( ! is_null($prop) && substr($key,0,1) != '_')
    		{
    		    if (strpos($prop, ' '))
    			{
    				// Support for operators in properties
    				$parts = explode(' ', $prop, 2);
    				
    				// Match an operator as the first character
    				if (preg_match('/^(<=|>=|!=|> |< )/', $parts[0]))
    				{
    					$key .= ' '.$parts[0];
    					$prop = $parts[1];
    				}
    			}
    			$parms[$key] = $prop;
    		}
    	}
    	
    	return $parms;
    }
    
    /**
     * Changes hours into minutes.
     * 
     * The hours is expected to use HH.MM format.
     * 
     * @deprecated use Calculations::hours_to_mins(string $time) instead.
     * @param string $time in HH.MM format
     * @return number of minutes
     */
    protected function _hours_to_mins($time)
    {
    	$hours = 0;
    	$mins = 0;
    	$parts = explode('.', $time);
    	if (count($parts) == 2)
    	{
    		list ($hours, $mins) = $parts;
    	} 
    	else
    	{
    		$hours = $time;
    	}
    	return ($hours * 60) + $mins;
    }
}
