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
	
        $award_type = New Award_type();
        $award_types = $award_type->find_all();
	
	if($award_types)
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
	
        $this->data['award_types'] = $award_types;
	$this->session->set_flashdata('return_url','awards/'.$id);
        $this->_render();
    }
    
    public function view($id)
    {
	$this->data['meta_title'] = 'Phoenix Virtual Airways Achievements';
	$this->data['title'] = 'Achievements';
	$this->data['breadcrumb']['awards'] = 'Achievements';
	
	$award = new Award($id);
	
	// send back to index if $award is not found
	if(! $award->name)
	    $this->index();
	
	$award_type = $award->get_award_type();
	
	$users = $award->get_users();
	
	$this->data['award'] = $award;
	$this->data['award_type'] = $award_type;
	$this->data['users'] = $users;
	$this->session->set_flashdata('return_url','awards/view/'.$id);
	$this->_render();
    }
    
    public function create_award($id = NULL)
    {
	$this->_check_access('manager');
	$this->load->library('form_validation');
	$this->load->helper('url');
	
	$this->data['title'] = 'Create Achievement';
	$this->data['breadcrumb']['awards'] = 'Achievements';
	
        $award = New Award($id);
	
	if($award->name)
        {
            $this->data['title'] = 'Edit Achievement';
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
	    
	    $this->_alert('Achievement - Record Saved', 'success', TRUE);
	    redirect($this->session->flashdata('return_url'));
	}        
    }
    
    public function create_award_type($id = NULL)
    {
	$this->_check_access('manager');
	$this->load->library('form_validation'); 
	$this->load->helper('url');
	
	$this->data['title'] = 'Create Achievement Type';
	$this->data['breadcrumb']['awards'] = 'Achievements';
	
        $award_type = new Award_type($id);
	
	if($award_type->name)
        {
            $this->data['title'] = 'Edit Achievement Type';
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
	    
	    $this->_alert('Achievement Type - Record Saved', 'success', FALSE);
	    $this->index();
	}        
    }
       
    public function delete_award($id = NULL)
    {
	$this->_check_access('manager');
        // Delete record
        if(is_null($id)) 
	    return FALSE;
        
	// Determine if there are any user_awards of this award
        $award = new Award($id);	
	$users = $award->get_user_count();
	
	// If there are not any user_awards then allow deletion of award
	if($users == 0)
	{
	    $award->delete();
	    $this->_alert('Achievement - Record Deleted', 'info', FALSE);
	    $this->index();
	}
	// If user_awards of this award are found stop deletion and inform user
        else
	{
	    $this->load->helper('url');
	    $this->_alert('Achievement - All user awards must be deleted first!', 'danger', TRUE);
	    redirect($this->session->flashdata('return_url'));
	}
    }
    
    public function delete_award_type($id = NULL)
    {
	$this->_check_access('manager');
        if(is_null($id)) 
	    return FALSE;
	
	$award_type = new Award_type($id);
        
	// Determine if there are any awards of this type
        $award = new Award();
	$award->award_type_id = $id;	
	$awards = $award->get_awards_count();
		
	// If there are not any awards of this type allow deletion of type
	if($awards == 0)
	{
	    $award_type->delete();
	    $this->_alert('Achievement - Record Deleted', 'info', FALSE);
	    $this->index();
	}
	// If awards of this type are found stop deletion and inform user
        else
	{
	    $this->load->helper('url');
	    $this->_alert('Achievement - All awards must be deleted first!', 'danger', TRUE);
	    redirect($this->session->flashdata('return_url'));
	}
    }   

}
