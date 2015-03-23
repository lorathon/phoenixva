<?php

class Articles extends PVA_Controller { 
	      
    public function index()
    {     
        
    }

    public function edit($slug = NULL)
    {
    	log_message('debug','Editing article');
    	$article = new Article();
    	
    	$this->load->library('form_validation');
    	
    	if ($this->form_validation->run())
    	{    		
    		$article->title = $this->form_validation->set_value('pagetitle');
    		$article->slug = $this->form_validation->set_value('slug');
    		$article->body_bbcode = $this->form_validation->set_value('pagebody');
    		$article->save();
    		
    		// Make a note of the update
    		$note = new Note();
    		$note->entity_type = 'article';
    		$note->entity_id = $article->id;
    		$note->user_id = $this->session->userdata('user_id');
    		$note->note = $this->form_validation->set_value('note');
    		$note->private_note = TRUE;
    		$note->save();
    		
    		$this->_alert('The article has been saved','success', TRUE);
    		
    		// Take the user to the page they were editing
    		$this->load->helper('url');
    		redirect('pages/'.$this->form_validation->set_value('slug'));
    	}
    	
    	if (is_null($slug))
    	{
    		$slug = $this->form_validation->set_value('slug');
    	}
    	$this->data['slug'] = $slug;    	
    	$article->slug = $slug;
    	$article->find();
    	
    	if ($article->title)
    	{
    		$this->data['pagetitle'] = $article->title;
    		$this->data['meta_title'] = 'Edit '.$article->title;
    		 
    		$this->data['notes'] = $this->_get_notes('article', $article->id, TRUE);
    	}
    	if ($article->body_bbcode)
    	{
    		$this->data['pagebody'] = $article->body_bbcode;
    	}
    	 
    	$this->_prep_editor();
    	$this->session->keep_flashdata('return_url');
    	$this->_render('admin/page_form');
    }
    
    public function delete($id)
    {
    	log_message('debug','Deleting article');
    	
    	$article = new Article($id);
    	$title = $article->title;
    	$slug = $article->slug;
    	$article->delete();
    	
    	// Make a note of the delete
    	$note = new Note();
    	$note->entity_type = 'article';
    	$note->entity_id = $article->id;
    	$note->user_id = $this->session->userdata('user_id');
    	$note->note = "Deleted article #{$id}: {$title} ({$slug}).";
    	$note->private_note = TRUE;
    	$note->save();
    	
    	$this->_alert("Deleted article #{$id}: {$title} ({$slug}).",'success', TRUE);
    	$this->load->helper('url');
    	redirect('admin');
    }
    
    /**
     * Edits a page for a hub
     *
     * Hubs can have multiple pages.
     * 
     * @param string $icao of the hub
     * @param string $page the page being edited
     */
    public function edit_hub($icao, $page = NULL)
    {
    	log_message('debug', 'Hub page edit called');
    	$this->_check_access('manager');
        
    	$slug = Article::build_slug('hub', array($icao, $page));
    	$return_url = 'hubs/'.$icao;
    	if (!is_null($page))
    	{
    		$return_url .= '/'.$page;
    	}
    	$this->session->set_flashdata('return_url', $return_url);
    
    	$this->edit($slug);
    }
    
    /**
     * Creates a new page for a hub
     * 
     * @param string $icao of the hub
     */
    public function create_hub($icao)
    {
    	log_message('debug', 'Hub page create called');
    	$this->_check_access('manager');
    	$this->edit("hub-{$icao}-");   	 
    }
    
    private function _prep_editor()
    {
    	$this->load->helper('url');
    	$this->data['scripts'][] = base_url('assets/sceditor/jquery.sceditor.bbcode.min.js');
    	$this->data['scripts'][] = base_url('assets/js/custom.sceditor.js');
    	$this->data['stylesheets'][] = base_url('assets/sceditor/themes/default.min.css');
    	$this->data['stylesheets'][] = base_url('assets/css/sceditor-custom.css');
    }
}