<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * OAG Class
 * Manages SOAP connections to OAG flight schedule data.
 * Usage example:
 * Controller
 * <code>
 * $this->load->driver('OAG');
 * $this->OAG->query(stuff);
 * print $this->OAG->raw_result();
 * </code>
 * 
 * @package OAG Driver
 * @author Chuck Topinka
 *
 */
class OAG {
	
	const DIRECT_FLIGHTS = 1;
	const CONNECTIONS = 2;
	const FLIGHT_LOOKUP = 3;
	
	public $raw_result = NULL;

	public function __construct($params)
	{
		$this->config->load('oag');
	}
	
	
	/**
	 * Runs a query of the specified $type against the OAG system.
	 * 
	 * @param int $type
	 * @param array $parms
	 * @param array $soapConfig
	 */
	public function query($type,$parms,$soapConfig = array('trace'=>1,))
	{
		// If we don't get anything, the output will be FALSE
		$output = FALSE;
		
		// Create soap client
		$client = new SoapClient($this->config->item('oag_wsdl'),$soapConfig);
		
		// Populate arguments, setting defaults as necessary
		$arg = array('arg0'=>array(
				'destinationCriteria'=>$parms->destinationCriteria,
				'destinationCriteriaLocationType'=>$parms->destinationCriteriaLocationType,
				'originCriteria'=>$parms->originCriteria,
				'originCriteriaLocationType'=>$parms->originCriteriaLocationType,
				'requestDate'=>$parms->requestDate,
				'requestTime'=>$parms->requestTime,
				'requestDateEffectiveFrom'=>$parms->requestDateEffectiveFrom,
				'requestDateEffectiveTo'=>$parms->requestDateEffectiveTo,
				'password'=>$this->config->item('oag_password'),
				'username'=> $this->config->item('oag_username'),
				)
		);
		
		// Call appropriate sub class based on query type
		switch ($type)
		{
			case DIRECT_FLIGHTS:
				// do stuff
				$this->raw_result = $this->OAG_direct_flights->query($client,$arg);
				$output = TRUE;
				break;
			case CONNECTIONS:
				// do stuff
				break;
			case FLIGHT_LOOKUP:
				// do stuff
				break;
			default:
				show_error('Improper $type specified in OAG query call.');
		}
		
		return $output;
	}
	
	/**
	 * Returns the raw result from the previous query or NULL if no query has
	 * been run.
	 */
	public function raw_result()
	{
		return $this->raw_result;
	}
	
	/**
	 * Returns an array of OAG_record objects for the last query.
	 * @return multitype:OAG_record
	 */
	public function response()
	{
		require_once(BASEPATH.'libraries/OAG/OAG_record.php');
		$response = array();
		
		if ( $schedules = simplexml_load_string($this->raw_result) ) {
			foreach ( $schedules as $flight ) {
				$oag_out = new OAG_record();
				$oag_out->flight_ID = $flight->flight['ID'];
				$oag_out->flight_ElapsedTime = $flight->flight['ElapsedTime'];
				$oag_out->flight_EffFrom = $flight->flight['EffFrom'];
				$oag_out->flight_EffTo = $flight->flight['EffTo'];
				$oag_out->flight_DaysOfOp = $flight->flight['DaysOfOp'];
				$oag_out->flight_ArrDayOffset = $flight->flight['ArrDayOffset'];
				
				$response[] = $oag_out;
			}
		}
		
		return $response;
	}
}