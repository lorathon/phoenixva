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
	public $user_id;
	
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
	 * Flag whether the note is only visible to staff
	 * 
	 * @var boolean
	 */
	public $private_note;
	
	/**
	 * Date the note was added (only needed when transferring legacy pilots)
	 * 
	 * @var string
	 */
	public $date;
	
	/**
	 * Array of notes for the desired entity.
	 * 
	 * @var array
	 */
	protected $_notes = array();
	
	/**
	 * User object corresponding to the user that made the note
	 * 
	 * @var User object
	 */
	protected $_user_object;
	
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
	 * @return boolean|array FALSE if no notes found or an array of notes objects.
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
	 * Override base find_all.
	 * 
	 * Translates properties and controls the where clause based on business
	 * logic.
	 * 
	 * @see PVA_Model::find_all()
	 */
	function find_all()
	{
		// Where
		($this->private_note) ? $staff = 1 : $staff = 0;
		$parms = array(
				'table_name'  => $this->entity_type,
				'table_entry' => $this->entity_id,
				'staff'       => $staff,
				);
		
		// Build the query
		$this->db->select()
		         ->from($this->_table_name)
		         ->where($parms)
		         ->order_by($this->_order_by);
		 
		// Query the database
		$query = $this->db->get();
		 
		return $this->_get_objects($query);
	}
	
	/**
	 * Gets the user object of the user who added the note
	 * 
	 * @return User
	 */
	function get_user()
	{
		if (is_null($this->_user_object))
		{
			$this->_user_object = new User($this->user_id);
		}
		return $this->_user_object;
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
		if (is_null($this->user_id) OR is_null($this->note))
		{
			return FALSE;
		}
		
		// Create object for the insert
		$obj = new stdClass();
		
		// Timestamp
		(is_null($this->date)) ? $obj->modified = date('Y-m-d H:i:s') : $obj->modified = $this->date;
		
		$obj->user_id = $this->user_id;
		$obj->note = $this->note;
		
		// Set defaults as necessary
		(is_null($this->entity_type)) ? $obj->table_name = 'users' : $obj->table_name = $this->entity_type;
		(is_null($this->entity_id)) ? $obj->table_entry = $this->user_id : $obj->table_entry = $this->entity_id;
		($this->private_note) ? $obj->staff = 1 : $obj->staff = 0;
		
		// Insert note
		return $this->db->insert($this->_table_name,$obj);
	}
}