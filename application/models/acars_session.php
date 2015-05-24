<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Acars_session extends PVA_Model {
    
    public $authToken;
    public $ip_address;
    public $acars_client;
    public $user_id;
    public $created;
    public $modified;
    
    public function __construct($token = NULL)
    {
        $this->_timestamps = TRUE;
        if (! is_null($token))
        {
            $token = array('authToken' => $token);
        }
        parent::__construct($token);
        
        // Update modified date for latest activity
        if (! is_null($this->id))
        {
            $this->save();
        }
        
        log_message('debug', 'ACARS session routines successfully run');
    }
    
    public function create($user_id, $ip_address, $client)
    {
        $this->authToken = md5($user_id.$ip_address.time());
        $this->user_id = $user_id;
        $this->ip_address = $ip_address;
        $this->acars_client = $client;
        
        $this->save();
        
        log_message('debug', 'ACARS session created');
    }
    
    public function destroy()
    {
        $this->delete();

        log_message('debug', 'ACARS session destroyed');
    }
}
