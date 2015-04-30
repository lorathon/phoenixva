<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bid extends Schedule
{
    /* Bid properties */
    public $user_id = NULL;
    public $sort = NULL;   
    
    
    function __construct($id = NULL)
    {
	$this->_order_by = 'sort asc';	
	parent::__construct($id);	
    }
    
    function create_bid($schedule_id = NULL)
    {
	if(is_null($schedule_id))
	    return;
	
	$this->user_id = 2;
	
	if(is_null($this->user_id))
	    return;
	
	$this->_object_name = 'Schedule';
	PVA_Model::__construct('2');	
	//$this->sort = $this->find_next_sort();
	
	print_r($this);
	
	//$this->save();
    }
    
    function find_next_sort()
    {
	if(is_null($this->user_id))
	    return;
	
	$this->sort_bids();
	
	$this->id = NULL;
	
	return $this->find_all(FALSE, TRUE);
    }
    
    function sort_bids()
    {
	if(is_null($this->user_id))
	    return;
	
	$bid = new Bid();
	$bid->user_id = $this->user_id;
	
	$bids = $bid->find_all();
	
	$i = 0;
	
	if($bids)
	{
	    foreach($bids as $bid)
	    {
		$bid->sort = $i;
		$bid->save();
		$i++;
	    }
	}
    }
}
