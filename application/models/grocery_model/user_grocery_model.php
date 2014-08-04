<?php

/*
 * The following settings will allow the grocery CRUD to accept a join
 * function. And also allow specialty SELECT statements
 * 
 * MUST SET MODEL: grocery_model/user_grocery_model
 * $crud->set_model('grocery_model/user_grocery_Model');
 *
 * grocery_select: SELECT String
 * Example 1 (JOIN usage): $this->config->grocery_select = ', users.id as id, user_profiles.*';
 * Example 2 (Special SELECT): $this->config->grocery_select = ', (SELECT 1 FROM user_awards as ua WHERE ua.award_id = awards.id AND ua.user_id = '.$user_id.') as granted';
 * REQUIRED
 * 
 * grocery_join_table: JOIN string
 * Example: $this->config->grocery_join_table = 'user_profiles';
 * OPTIONAL
 *
 * grocery_join_on: ON portion of JOIN
 * Example: $this->config->grocery_join_on = 'user_profiles.user_id = users.id';
 * REQUIRED IF JOIN TABLE USED
 *
 * grocery_join_type: TYPE portion of JOIN
 * Example: $this->config->grocery_join_type = 'LEFT';
 * OPTIONAL
 *
 * The above examples would produce
 * SELECT users.*, users.id as id, user_profiles.*
 * LEFT JOIN user_profiles ON user_profiles.user_id = users_id
 *
 * Version 1.0.0
 * Jeffrey F. Kobus
*/

class User_Grocery_model extends grocery_CRUD_Model
{

    function get_list()
    {
        $_join = false;
        
		$_select 		= $this->config->grocery_select;
		
		
		if(isset($this->config->grocery_join_type))
			$_join_type = $this->config->grocery_join_type;
		else
			$_join_type = '';
            
        if(isset($this->config->grocery_join_table))
        {
            $_join_table 	= $this->config->grocery_join_table;
            $_join_on 		= $this->config->grocery_join_on;
            $_join = TRUE;
        }
		
		
    	if($this->table_name === null)
    		return false;

    	$select = "`{$this->table_name}`.*";
		
		// Select all from joined table        
		$select .= $_select;
        
    	//set_relation special queries
    	if(!empty($this->relation))
    	{
    		foreach($this->relation as $relation)
    		{
    			list($field_name , $related_table , $related_field_title) = $relation;
    			$unique_join_name = $this->_unique_join_name($field_name);
    			$unique_field_name = $this->_unique_field_name($field_name);

				if(strstr($related_field_title,'{'))
				{
					$related_field_title = str_replace(" ","&nbsp;",$related_field_title);
    				$select .= ", CONCAT('".str_replace(array('{','}'),array("',COALESCE({$unique_join_name}.",", ''),'"),str_replace("'","\\'",$related_field_title))."') as $unique_field_name";
				}
    			else
    			{
    				$select .= ", $unique_join_name.$related_field_title AS $unique_field_name";
    			}

    			if($this->field_exists($related_field_title))
    				$select .= ", `{$this->table_name}`.$related_field_title AS '{$this->table_name}.$related_field_title'";
    		}
    	}

    	//set_relation_n_n special queries. We prefer sub queries from a simple join for the relation_n_n as it is faster and more stable on big tables.
    	if(!empty($this->relation_n_n))
    	{
			$select = $this->relation_n_n_queries($select);
    	}
        
        // Join to table
        if($_join)
            $this->db->join($_join_table, $_join_on, $_join_type);
        
    	$this->db->select($select, false);

    	$results = $this->db->get($this->table_name)->result();

    	return $results;
    }
}