<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Note model
 * 
 * Provides all business logic for adding notes to items in the system. Notes
 * can be applied to any entity in the system. An entity is usually a table.
 * Unlike other PVA models/objects, the Note model does not use database named
 * properties directly. This was done on purpose since a note can have special
 * handling.
 * 
 * @author Chuck
 *
 */
class Note extends PVA_Model {
	
	/**
	 * The ID of the user making the note.
	 * 
	 * @var number
	 */
	public $user;
	
	/**
	 * The entity this note applies to.
	 * 
	 * @var string
	 */
	public $entity_type;
	
	/**
	 * The specific identifier for the entity being noted.
	 * 
	 * @var number
	 */
	public $entity_id;
	
	/**
	 * The note to add.
	 * 
	 * @var string
	 */
	public $note;
	
	/**
	 * Array of notes for the desired entity.
	 * 
	 * @var array
	 */
	protected $_notes = array();
	
	/**
	 * Creates a new note model.
	 * 
	 * Optionally takes the entity type and identifier to populate all notes for
	 * that specific entity.
	 * 
	 * @param string $type
	 * @param number $id
	 */
	function __construct($type = NULL, $id = NULL)
	{
		parent::__construct();
		
		// Default sort
		$this->_order_by = 'modified desc';
		
		// If the id is set, create a populated model (Kohana-esque)
		if ( ! is_null($type) && ! is_null($id))
		{
			$this->entity_type = $type;
			$this->entity_id = $id;
			$this->_notes = $this->find_all();
		}
	}
	
	/**
	 * Gets the notes for the current model
	 * 
	 * The model should have been created with entity type and id specified or
	 * otherwise had those parameters set prior to calling this method.
	 * 
	 * @return array of notes objects.
	 */
	function get_notes()
	{
		if (count($this->_notes) == 0 && 
				! is_null($this->entity_type) && 
				! is_null($this->entity_id))
		{
			$this->_notes = $this->find_all();
		}
		return $this->_notes;
	}
	
	/**
	 * Override base save method
	 * 
	 * Ensures Note integrity and provides defaulting for entity and ID. If not
	 * provided, the note will apply to the same user that is adding the note.
	 * 
	 * @see PVA_Model::save()
	 */
	function save()
	{
		// Required info
		if (isnull($this->user) OR isnull($this->note))
		{
			return FALSE;
		}
		
		// Create object for the insert
		$obj = new stdClass();
		
		// Timestamp
		$obj->modified = date('Y-m-d H:i:s');
		
		$obj->user_id = $this->user;
		$obj->note = $this->note;
		
		// Set defaults as necessary
		(isnull($this->entity_type)) ? $obj->table_name = 'users' : $obj->table_name = $this->entity_type;
		(isnull($this->entity_id)) ? $obj->table_entry = $this->user : $obj->table_entry = $this->entity_id;
		
		// Insert note
		return $this->db->insert($this->_table_name,$obj);
	}
}