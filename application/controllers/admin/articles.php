<?php

class Articles extends PVA_Controller { 
	      
    public function index()
    {     
        
    }

    public function edit()
    {
    	log_message('debug','Editing article');
    	
    	$this->load->library('form_validation');
    	
    	if ($this->form_validation->run())
    	{
    		$article = new Article();
    		$article->title = $this->form_validation->set_value('pagetitle');
    		$article->slug = $this->form_validation->set_value('slug');
    		$article->body_bbcode = $this->form_validation->set_value('pagebody');
    		$article->save();
    		
    		$this->_flash_message('success','Article Saved','The article has been saved');
    		
    		// Take the user to the page they were editing
    		$this->load->helper('url');
    		redirect('pages/'.$this->form_validation->set_value('slug'));
    	}
    	
    	$this->load->helper('url');
    	$this->data['scripts'][] = base_url('assets/sceditor/jquery.sceditor.bbcode.min.js');
    	$this->data['stylesheets'][] = base_url('assets/sceditor/themes/default.min.css');

    	$this->session->keep_flashdata('return_url');
    	$this->_render('admin/page_form');
    }
}