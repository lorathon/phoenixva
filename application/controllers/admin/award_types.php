<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Award_types extends PVA_Controller
{
    protected $_table_name = 'award_types';
    protected $_order_by = 'id';
    
    public function __construct()
    {
        parent::__construct();        
        $this->load->helper(array('form', 'url', 'html'));
	$this->load->library('form_validation'); 
    }
    
    public function index()
    {   
        $type = New Award_type();
        $types = $type->find_all();
        $this->data['types'] = $types;
        $this->_render('admin/award_types');
    }        
    
    public function create_award_type($id = NULL)
    {
        $award_type = New Award_type($id);    
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'alpha-numberic|trim|xss_clean');
        $this->form_validation->set_rules('img_folder', 'Description', 'alpha-numberic|trim|xss_clean');
        $this->form_validation->set_rules('img_width', 'Description', 'numberic|trim|xss_clean');
        $this->form_validation->set_rules('img_height', 'Description', 'numberic|trim|xss_clean');
                
        if ($this->form_validation->run() == FALSE)
	{             
            $this->data['errors'] = validation_errors();;  
            $this->data['record'] = $award_type;
            $this->_render('admin/award_type_form');
	}
	else
	{
            $award_type->id              = $this->input->post('id', TRUE);
            $award_type->name            = $this->form_validation->set_value('name');
            $award_type->description     = $this->form_validation->set_value('description');
            $award_type->img_folder      = $this->form_validation->set_value('img_folder');
            $award_type->img_width       = $this->form_validation->set_value('img_width');
            $award_type->img_height      = $this->form_validation->set_value('img_height');
                
            $award_type->save();
            $this->_flash_message('success', 'Award Type', 'Record Saved');
            redirect('admin/award_types');
	}        
    }
    
    public function delete_award_type()
    {
        // Delete record
        
        $this->_flash_message('info', 'Award Type', 'Record Deleted');
        redirect('admin/award_types');
    }
}

