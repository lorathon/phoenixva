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

}
