<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Awards extends PVA_Controller
{
    
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
            $award_type = $award->get_award_type();
            
            $award->type          = $award_type->name;
            $award->img_folder    = $award_type->img_folder;
            $award->img_width     = $award_type->img_width;
            $award->img_height    = $award_type->img_height;
            $award->users         = $award->get_user_count();
            
            $this->data['awards'][] = $award;
        }
        
        $this->_render('admin/awards');
    }    
    
     public function award_types()
    {   
        $award_type = New Award_type();
        $this->data['types'] = $award_type->find_all();
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
        
        $award_type = new Award_type();
        $this->data['award_types'] = $award_type->get_dropdown();
                
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
	    $this->_alert_message('success', 'Award - Record Saved');
            $this->index();
	}        
    }
    
    public function create_award_type($id = NULL)
    {
        $award_type = new Award_type($id); 
                
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
            $this->_alert_message('success', 'Award Type - Record Saved');
            $this->award_types();
	}        
    }
       
    public function delete_award($id = NULL)
    {
        // Delete record
        if(is_null($id)) 
	    return FALSE;
        
        $award = new Award($id);
        $award->delete();
        
	$this->_alert_message('info', 'Award - Record Deleted');
        $this->index();
    }
    
    public function delete_award_type($id = NULL)
    {
        // Delete record
        if(is_null($id)) 
	    return FALSE;
        
        $award = new Award();
        
        $award->_award_type->id = $id;
        $award->_award_type->delete();
        
	$this->_alert_message('info', 'Award Type - Record Deleted');
        $this->award_types();
    }   
    
}