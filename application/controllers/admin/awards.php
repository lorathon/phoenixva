<?php

class Awards extends PVA_Controller
{
    protected $_table_name = 'awards';
    protected $_order_by = 'id';
    
    public function __construct()
    {
        parent::__construct();        
        $this->load->helper(array('form', 'url', 'html'));
	$this->load->library('form_validation'); 
    }
    
    public function index()
    {    
        $award = New Award();
        $awards = $award->find_all();
        $this->data['awards'] = $awards;
        $this->data['types'] = config_item('award_types');
        $this->data['paths'] = config_item('img_folders');
        $this->_render('admin/awards');
    }    
    
    public function create_award($id = NULL)
    {
        $award = New Award($id);
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('descrip', 'Description', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('award_image', 'Award Image', 'trim|required|xss_clean');
        $this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
        
        $this->data['types'] = config_item('award_types');
                
        if ($this->form_validation->run() == FALSE)
	{             
            $this->data['errors'] = validation_errors();;  
            $this->data['record'] = $award;
            $this->_render('admin/award_form');
	}
	else
	{
            $award->id              = $this->input->post('id', TRUE);
            $award->name            = $this->form_validation->set_value('name');
            $award->type            = $this->form_validation->set_value('type');
            $award->descrip         = $this->form_validation->set_value('descrip');
            $award->award_image     = $this->form_validation->set_value('award_image');
                
            $award->save();
            $this->_flash_message('success', 'Award', 'Record Saved');
            redirect('admin/awards');
	}        
    }
}