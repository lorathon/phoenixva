<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Awards extends PVA_Controller
{
    public function index($id = NULL)
    {
	$this->load->helper('url');
	
	// if $id is null then set $id to 1 and start over
	if(is_null($id))
	    redirect('awards/1');
	
	$this->data['meta_title'] = 'Phoenix Virtual Airways Achievements';
	$this->data['title'] = 'Achievements';
	
	$this->data['award_types'] = array();
	$this->data['awards'] = array();
	
        $obj = New Award_type();
        $objs = $obj->find_all();
	
	if($objs)
	{
	    $award = new Award();
	    $award->award_type_id = $id;
	    $awards = $award->find_all();	
	}
	
	if($awards)
	{
	    foreach($awards as $award)
	    {
		$award_type = $award->get_award_type();
		$award->users         = $award->get_user_count();
		$award->img_folder    = $award_type->img_folder;
		$this->data['awards'][] = $award;
	    }
	}
	
        $this->data['award_types'] = $objs;
	$this->session->set_flashdata('return_url','awards/'.$id);
        $this->_render();
    }
    
    public function view($id)
    {
	$this->data['meta_title'] = 'Phoenix Virtual Airways Achievements';
	$this->data['title'] = 'Achievements';
	
	$award = new Award($id);
	
	// send back to index if $award is not found
	if(! $award->name)
	    $this->index();
	
	$award_type = $award->get_award_type();
	
	$users = $award->get_users();
	
	$this->data['award'] = $award;
	$this->data['award_type'] = $award_type;
	$this->data['users'] = $users;
	$this->_render('award_view');	
    }
    
    public function create_award($id = NULL)
    {
	$this->load->library('form_validation');
	$this->load->helper('url');
	
	$this->data['title'] = 'Create Award';
	
        $award = New Award($id);
	
	if($award)
        {
            $this->data['title'] = 'Edit Award';
        }
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('award_image', 'Award Image', 'trim|required|xss_clean');
        $this->form_validation->set_rules('award_type_id', 'Award Type', 'numeric|trim|required|xss_clean');
        
        $award_type = new Award_type();
        $this->data['award_types'] = $award_type->get_dropdown();
        
        $this->data['scripts'][] = base_url('assets/admin/vendor/jquery-validation/jquery.validate.js');
	$this->data['scripts'][] = base_url('assets/js/forms.validation.js');
                
        if ($this->form_validation->run() == FALSE)
	{             
            $this->data['errors'] = validation_errors();;  
            $this->data['record'] = $award;
	    $this->session->keep_flashdata('return_url');
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
	    
	    $url = $this->session->flashdata('return_url');	    
	    if($url)
	    {
		redirect($this->session->flashdata('return_url'));
	    }
	    else
	    {
		$this->index();
	    }	  
	}        
    }
    
    public function create_award_type($id = NULL)
    {
	$this->load->library('form_validation'); 
	$this->load->helper('url');
	
	$this->data['title'] = 'Create Award Type';
	
        $award_type = new Award_type($id);
	
	if($award_type)
        {
            $this->data['title'] = 'Edit Award Type';
        }
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'alpha-numberic|trim|xss_clean');
        $this->form_validation->set_rules('img_folder', 'Description', 'alpha-numberic|trim|xss_clean');
        $this->form_validation->set_rules('img_width', 'Description', 'numberic|trim|xss_clean');
        $this->form_validation->set_rules('img_height', 'Description', 'numberic|trim|xss_clean');
        
        $this->data['scripts'][] = base_url('assets/admin/vendor/jquery-validation/jquery.validate.js');
	$this->data['scripts'][] = base_url('assets/js/forms.validation.js');
                
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
        $this->index();
    }   

}
