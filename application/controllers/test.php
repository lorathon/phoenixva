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
	$this->data['scripts'][] = base_url('assets/js/custom.autocomplete.js');
	$this->_render();	
    }
    
    function results()
    {
	
    }
    
    function bids()
    {
	$this->load->helper('url');
        $this->data['scripts'][] = base_url('assets/admin/vendor/jquery-nestable/jquery.nestable.js');
	$this->data['scripts'][] = base_url('assets/js/views/view.test.js');
	
	$bid = new Bid();
	$bid->user_id = 2;
	$bids = $bid->find_all();
	
	$this->data['bids'] = $bids;
	$this->_render('bids');
    }
}
