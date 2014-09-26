<?php

class Dashboard extends PVA_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['sidebar'] = 'admin/dashboard/sidebar';
    }
    
    public function index()
    {
        // Display Standard View
        $user_lku = new User();
        
        if ($users = $user_lku->find_all())
        {
        	// Users to display
        	$this->load->helper('url');
        	$this->load->helper('html');
        	$this->load->library('table');
        	
        	// Set the template using the standard
        	$this->table->set_template(table_layout());
        	
        	// Set the headings
        	$headings = array(
        			'Pilot',
        			'Email',
        			'Status',
        			'Rank',
        			'Flights',
        			'Hours',
        			'Hub',
        			);
        	
        	$this->table->set_heading($headings);
        	
        	foreach ($users as $user)
        	{
        		$stats = $user->get_user_stats();
        		
        		// Get the user's rank name
        		$rank = new Rank();
        		$rank->id = $user->rank_id;
        		$rank->find();
        		
        		// Get the user's hub name
        		$hub = new Airport();
        		$hub->id = $user->hub;
        		$hub->find();
        		
        		$row = array(
        				user($user),
        				$user->email,
        				$user->status,
        				$rank->short,
        				$stats->total_flights(),
        				$stats->total_hours(),
        				$hub->icao.' '.$hub->name,
        				);
        		$this->table->add_row($row);
        	}
        	$this->data['user_table'] = $this->table->generate();
        }
        
        $this->_render('admin/dashboard');   
    }        
}