<?php defined('BASEPATH') OR exit('No direct script access allowed');

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
        $obj = New Award();
        $awards = $obj->find_all();
        
        foreach($awards as $award)
        {
            $type = new Award_type($award->award_type_id);
            
            $award->type          = $type->name;
            $award->img_folder    = $type->img_folder;
            $award->img_width     = $type->img_width;
            $award->img_height    = $type->img_height;
            $award->users         = $award->get_user_count();
            
            $this->data['awards'][] = $award;
        }
        
        $this->_render('admin/awards');
    }    
    
     public function award_types()
    {   
        $award = New Award();
        $type = $award->_award_type;
        $this->data['types'] = $type->find_all();
        $this->_render('admin/award_types');
    }       
    
    public function create_award($id = NULL)
    {
        $award = New Award($id);
        $types = $award->_award_type;
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('award_image', 'Award Image', 'trim|required|xss_clean');
        $this->form_validation->set_rules('award_type_id', 'Award Type', 'numeric|trim|required|xss_clean');
        
        $this->data['award_types'] = $types->get_dropdown();
                
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
            $award->award_type_id   = $this->form_validation->set_value('award_type_id');
            $award->description     = $this->form_validation->set_value('description');
            $award->award_image     = $this->form_validation->set_value('award_image');
                
            $award->save();
            $this->_flash_message('success', 'Award', 'Record Saved');
            redirect('admin/awards');
	}        
    }
    
    public function create_award_type($id = NULL)
    {
        $award = new Award();
        $type = $award->_award_type;
        $award_type = $type->get_award_type($id);
                
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
            redirect('admin/awards/award_types');
	}        
    }
    
    public function delete_award($id)
    {
        // Delete record
        
        $this->_flash_message('info', 'Award', 'Record Deleted');
        redirect('admin/awards');
    }
    
    public function delete_award_type()
    {
        // Delete record
        
        $this->_flash_message('info', 'Award Type', 'Record Deleted');
        redirect('admin/awards/award_types');
    }
}