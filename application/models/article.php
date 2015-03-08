<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends PVA_Model {
	
	/* Article properties */
	public $title    = NULL;
	public $slug     = NULL;
	public $pubdate  = NULL;
	public $body     = NULL;
	public $created  = NULL;
	public $modified = NULL;
	
	function __construct($id = NULL)
	{
		parent::__construct($id);
		
		// Set defaults
		$this->_order_by = 'pubdate desc';
		$this->_timestamps = TRUE;
		log_message('debug', 'Article model Initialized');
	}
	
	/**
	 * Override parent save method to support BBCode
	 * 
	 * Article is stored in the database in HTML format so the parsing hit is
	 * only needed when actually saving and editing.
	 * 
	 * @see PVA_Model::save()
	 */
	public function save()
	{
		// Support updates
		$article = new Article();
		$article->slug = $this->slug;
		$article->find();
		$this->id = $article->id;
		
		$parser = $this->_prep_body();
		$this->body = $parser->getAsHTML();
		parent::save();
	}
	
	/**
	 * Change HTML to BBCode
	 * 
	 * This is intended for editing the page. Articles are stored in HTML format
	 * for performance.
	 * 
	 * @return string
	 */
	public function to_bbcode()
	{
		$parser = $this->_prep_body();		
		return $parser->getAsBBCode();
	}
	
	private function _prep_body()
	{
		require_once APPPATH.'/libraries/JBBCode/Parser.php';
		$parser = new JBBCode\Parser();
		$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());		
		$parser->parse($this->body);		
		return $parser;
	}
}