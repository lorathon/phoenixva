<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Default home page
 * 
 * @author Chuck
 *
 */
class Home extends PVA_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('my_model/article_m');
    }
    
    public function index()
    {
        $this->data['midview'] = 'home_mid';
        $this->data['rightview'] = 'home_right';
        $this->data['articles'] = $this->article_m->get_with_limit(5);
        $this->load->view('_layout_main.php', $this->data);
    }
}