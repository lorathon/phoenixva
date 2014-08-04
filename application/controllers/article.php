<?php

class Article extends Public_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('my_model/article_m', '', TRUE);
    }
    
    public function index()
    {
        
    }
    
    public function get_with_limit($limit)
    {
        $this->db->limit($limit);
        $data = $this->article_m->get();
        return $data;
    }
}