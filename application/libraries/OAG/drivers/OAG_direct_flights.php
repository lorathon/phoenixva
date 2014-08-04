<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OAG_direct_flights {
	
	/**
	 * Validates required input is provided and then makes a soap call to OAG.
	 * @param SoapClient $client Previously configured SoapClient
	 * @param array $arg Arguments to check
	 * @return SoapClient response
	 */
	function query($client,$arg)
	{	
		// Check to make sure we have everything needed so we don't waste a query
		
		// Try to make connection and get results
		try{
			$result = $client->getDirectFlights($arg);
			return $client->__getLastResponse();
		}
		catch(Exception $e){
			show_error('Error getting direct flights: '.$e);
		}
	}	
}