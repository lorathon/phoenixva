<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends PVA_Controller
{

    public function __construct()
    {
	parent::__construct();
    }

    public function index()
    {
	$this->load->helper('url');
        $this->data['scripts'][] = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js';
	//$this->data['scripts'][] = base_url('assets/js/views/view.test.js');
	
	$bid = new Schedule();
	$bids = $bid->get_bids(2, TRUE);
	$this->data['bids'] = $bids;
	$this->_render();	
    }
    
    function results()
    {
	$post = $this->input->post('item', TRUE);	
	$bid = new Schedule();
	$bid->reorder_bids($post);	
    }
}
