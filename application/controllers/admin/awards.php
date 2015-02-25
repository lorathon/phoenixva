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
        $awards = $obj->get_all();
        
        foreach($awards as $award)
        {
            // Commented out to allow join function to work
            /*
            $type = new Award_type($award->award_type_id);            
            
            $award->type          = $type->name;
            $award->img_folder    = $type->img_folder;
            $award->img_width     = $type->img_width;
            $award->img_height    = $type->img_height;
            */
            $this->data['awards'][] = $award;
        }
        
        $this->_render('admin/awards');
    }    
    
    public function create_award($id = NULL)
    {
        $award = New Award($id);
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('award_image', 'Award Image', 'trim|required|xss_clean');
        $this->form_validation->set_rules('award_type_id', 'Award Type', 'numeric|trim|required|xss_clean');
        
        $award_types = new Award_type();
        $this->data['award_types'] = $award_types->get_dropdown();
                
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
    
    public function delete_award($id)
    {
        // Delete record
        
        $this->_flash_message('info', 'Award', 'Record Deleted');
        redirect('admin/awards');
    }
}