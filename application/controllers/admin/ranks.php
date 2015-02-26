<?php

class Ranks extends PVA_Controller
{
    protected $_table_name = 'ranks';
    
    public function __construct()
    {
        parent::__construct();        
        $this->load->helper(array('form', 'url', 'html'));
	$this->load->library('form_validation');        
    }
    
    public function index()
    {            
        $rank = New Rank();
        $ranks = $rank->find_all();
        $this->data['ranks'] = $ranks;
        $this->data['paths'] = config_item('img_folders');
        $this->_render('admin/ranks');
    } 
    
    public function create_rank($id = NULL)
    {
        $rank = New Rank($id);
        
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('rank', 'Rank', 'trim|required|xss_clean');
        $this->form_validation->set_rules('rank_image', 'Rank Image', 'trim|required|xss_clean');
        $this->form_validation->set_rules('min_hours', 'Min Hours', 'integer|trim|required|xss_clean');
        $this->form_validation->set_rules('pay_rate', 'Pay Rate', 'decimal|trim|required|xss_clean');
        $this->form_validation->set_rules('short', 'Short', 'alpha-numeric|trim|required|xss_clean|strtoupper');
                
        if ($this->form_validation->run() == FALSE)
	{             
            $this->data['errors'] = validation_errors();;  
            $this->data['record'] = $rank;
            $this->_render('admin/rank_form');
	}
	else
	{
            $rank->id           = $this->input->post('id', TRUE);
            $rank->rank         = $this->form_validation->set_value('rank');
            $rank->rank_image   = $this->form_validation->set_value('rank_image');
            $rank->min_hours    = $this->form_validation->set_value('min_hours');
            $rank->pay_rate     = $this->form_validation->set_value('pay_rate');
            $rank->short        = $this->form_validation->set_value('short');
                
            $rank->save();
            $this->_flash_message('success', 'Rank', 'Record Saved');
            redirect('admin/ranks');
	}        
    }
}