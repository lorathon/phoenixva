<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends PVA_Controller
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $views = array();
        
        $this->load->helper('html');
	$this->load->helper('url');
                        
        $award      = new Award();
        $types      = $award->_award_type->find_all();
        
        foreach ($types as $type) {
            
            $user_awards = array();
            $this->data['awards'] = array();
            $user_awards = $award->_user_award->get_by_type(2, $type->id);

            if ($user_awards) {

                foreach ($user_awards as $user_award) {

                    $award = new Award($user_award->award_id);
                    //$type = $award->get_award_type();

                    $user_award->award_image    = $award->award_image;
                    $user_award->name           = $award->name;
                    $user_award->description    = $award->description;
                    $user_award->img_folder     = $type->img_folder;
                    $user_award->img_width      = $type->img_width;
                    $user_award->img_height     = $type->img_height;

                    $this->data['awards'][] = $user_award;
                    
                }
            }
            
            $this->data['heading'] = $type->name;
            $this->_render('testing');
        }
    }     
    
    public function test($user_id = NULL)
    {
        $user_id = 2;
        $award = new Award();        
        $user_awards = $award->_user_award->get_not_granted($user_id);        
        var_dump($user_awards);
                
    }
}

