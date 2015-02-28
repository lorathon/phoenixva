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
	 * @see PVA_Model::save()
	 */
	function save()
	{
		$parser = new JBBCode\Parser();
		$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
		$html = $parser->parse($this->body);
		$this->body = $parser->getAsHTML();
		parent::save();
	}
}