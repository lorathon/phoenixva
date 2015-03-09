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
	
	/**
	 * Parses the BBCode
	 * 
	 * @return \JBBCode\Parser
	 */
	private function _prep_body()
	{
		require_once APPPATH.'/libraries/JBBCode/Parser.php';
		$parser = new JBBCode\Parser();
		$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
		
		// Left text
		$builder = new JBBCode\CodeDefinitionBuilder('left', '<p class="text-left">{param}</p>');
		$parser->addCodeDefinition($builder->build());
		
		// Centered text
		$builder = new JBBCode\CodeDefinitionBuilder('center', '<p class="text-center">{param}</p>');
		$parser->addCodeDefinition($builder->build());
		
		// Right text
		$builder = new JBBCode\CodeDefinitionBuilder('right', '<p class="text-right">{param}</p>');
		$parser->addCodeDefinition($builder->build());
		
		// Justified text
		$builder = new JBBCode\CodeDefinitionBuilder('justify', '<p class="text-justify">{param}</p>');
		$parser->addCodeDefinition($builder->build());
		
		// Strikethrough text
		$builder = new JBBCode\CodeDefinitionBuilder('s', '<s>{param}</s>');
		$parser->addCodeDefinition($builder->build());
		
		// Subscript text
		$builder = new JBBCode\CodeDefinitionBuilder('sub', '<sub>{param}</sub>');
		$parser->addCodeDefinition($builder->build());
		
		// Superscript text
		$builder = new JBBCode\CodeDefinitionBuilder('sup', '<sup>{param}</sup>');
		$parser->addCodeDefinition($builder->build());
		
		// Lists
		$builder = new JBBCode\CodeDefinitionBuilder('ol', '<ol>{param}</ol>');
		$parser->addCodeDefinition($builder->build());
		$builder = new JBBCode\CodeDefinitionBuilder('ul', '<ul>{param}</ul>');
		$parser->addCodeDefinition($builder->build());
		$builder = new JBBCode\CodeDefinitionBuilder('li', '<li>{param}</li>');
		$parser->addCodeDefinition($builder->build());
		
		// Horizontal line
		$builder = new JBBCode\CodeDefinitionBuilder('hr', '<hr />');
		$parser->addCodeDefinition($builder->build());
		
		// Code
		$builder = new JBBCode\CodeDefinitionBuilder('code', '<pre class="pre-scrollable">{param}</pre>');
		$parser->addCodeDefinition($builder->build());
		
		// Quotes
		$builder = new JBBCode\CodeDefinitionBuilder('quote', '<blockquote>{param}</blockquote>');
		$parser->addCodeDefinition($builder->build());
				
		
		// Parse it
		$parser->parse($this->body);		
		return $parser;
	}
}