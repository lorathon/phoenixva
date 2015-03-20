<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ranks extends PVA_Controller
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {   
	$this->data['meta_title'] = 'Phoenix Virtual Airways Ranks';
	$this->data['title'] = 'Ranks';
	
        $rank = New Rank();
        $ranks = $rank->find_all();
        $this->data['ranks'] = $ranks;
        $this->_render();
    } 
    
    public function view($id)
    {
	if(is_null($id))
	{
	    $this->index();
	}
	
	$rank = new Rank($id);
	
	if($rank)
	{
	    $this->data['meta_title'] = 'Phoenix Virtual Airways Ranks';
	    $this->data['title'] = $rank->rank;
	    $this->data['breadcrumb']['ranks'] = 'Ranks';	    
	    
	    $this->data['rank'] = $rank;
	    $this->data['users'] = $rank->get_users();
	    $this->data['img_folders'] = config_item('img_folders');
	    $this->session->set_flashdata('return_url','/ranks/'.$id);
	    $this->_render();
	}
	else
	{
	    $this->index();
	}
    }
    
    public function create_rank($id = NULL)
    {
	$this->_check_access('manager');
	
        $rank = New Rank($id);
	
	$this->data['title'] = 'Create Rank';
	$this->data['breadcrumb']['ranks'] = 'Ranks';
	
        $rank = New rank($id);
	
	if($rank->rank)
        {
            $this->data['title'] = 'Edit Rank';
	    $this->data['breadcrumb']['ranks/' . $id] = $rank->rank;
        }
	
	$this->load->library('form_validation'); 
        
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
	    $this->session->keep_flashdata('return_url');
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
	    
	    $this->_alert('Rank - Record Saved', 'success', TRUE);
	    redirect($this->session->flashdata('return_url'));
	}        
    }
    
    public function delete_rank($id)
    {
	$this->_check_access('manager');
	
	if(is_null($id))
	{
	    $this->index();
	}
	
	$rank = new Rank($id);
	$users = $rank->get_user_count();
	
	// If there are not any users with this rank then allow deletion of rank
	if($users == 0)
	{
	    //$rank->delete();
	    $this->_alert('Rank - Record Deleted', 'info', FALSE);
	    $this->index();
	}
	// If users with this rank are found stop deletion and inform user
        else
	{
	    $this->load->helper('url');
	    $this->_alert('Rank - There are users with this rank!', 'danger', TRUE);
	    redirect($this->session->flashdata('return_url'));
	}
    }
}
