<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Award_admin extends PVA_Controller
{
    
    public function __construct()
    {
        parent::__construct(); 
    }
    
    public function index()
    {   
	$this->load->helper('html');	
	
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
	$this->load->helper('html');	
	
        $award_type = New Award_type();
        $this->data['types'] = $award_type->find_all();
        $this->_render('admin/award_types');
    }       
}