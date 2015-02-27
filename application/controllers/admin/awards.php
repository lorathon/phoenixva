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
        $award = New Award();
        $awards = $award->find_all();
        
        foreach($awards as $award)
        {            
            $award->_award_type->id = $award->award_type_id;
            $award->_award_type->find();
            
            $award->type          = $award->_award_type->name;
            $award->img_folder    = $award->_award_type->img_folder;
            $award->img_width     = $award->_award_type->img_width;
            $award->img_height    = $award->_award_type->img_height;
            $award->users         = $award->get_user_count($award->id);
            
            $this->data['awards'][] = $award;
        }
        
        //var_dump($this->data['awards']);
        //return;
        
        $this->_render('admin/awards');
    }    
    
     public function award_types()
    {   
        $award = New Award();
        $this->data['types'] = $award->_award_type->find_all();
        $this->_render('admin/award_types');
    }       
    
    public function create_award($id = NULL)
    {
        $award = New Award($id);
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('award_image', 'Award Image', 'trim|required|xss_clean');
        $this->form_validation->set_rules('award_type_id', 'Award Type', 'numeric|trim|required|xss_clean');
        
        $this->data['award_types'] = $award->_award_type->get_dropdown();
                
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
        $award->_award_type->id = $id;
        $award->_award_type->find();
        $award_type = $award->_award_type;
                
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
    
    public function delete_award($id = NULL)
    {
        // Delete record
        if(is_null($id)) return FALSE;
        
        $award = new Award($id);
        $award->delete();
        
        $this->_flash_message('info', 'Award', 'Record Deleted');
        redirect('admin/awards');
    }
    
    public function delete_award_type($id = NULL)
    {
        // Delete record
        if(is_null($id)) return FALSE;
        
        $award = new Award();
        
        $award->_award_type->id = $id;
        $award->_award_type->delete();
        
        $this->_flash_message('info', 'Award Type', 'Record Deleted');
        redirect('admin/awards/award_types');
    }   
    
}