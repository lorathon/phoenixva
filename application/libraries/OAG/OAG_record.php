<?php

class OAG_record {
	
	// Properties of the object are derived from 
	// OAG OnDemand Web Service - User Guide V1.4a.doc
	public $flight_ID = '';
	public $flight_ElapsedTime = '';
	public $flight_EffFrom = '';
	public $flight_EffTo = '';
	public $flight_DaysOfOp = '';
	public $flight_ArrDayOffset = '';
	
	public $dep_Stops = '';
	public $dep_LegID = '';
	public $dep_Frequency = '';
	public $dep_DepTime = '';
	public $dep_ElapsedTime = '';
	public $dep_OAG_OpByCarrier = '';
	public $dep_OAG_OpByCarrierName = '';
	public $dep_OAG_OpByFlightNo = '';
	
	public $effFrom = '';
	
	public $effTo = '';
	
	public $equipment_Code = '';
	public $equipment_BodyType = '';
	public $equipment_BodyTypeCode = '';
	public $equipment_EnrouteChangeOfAcft = '';
	public $equipment_AircraftOwner = '';
	public $equipment = '';
	
	public $carrier_Code = '';
	public $carrier_ServiceNumber = '';
	public $carrier_LowCost = '';
	public $carrier = '';
	
	public $dep_city_CityCode = '';
	public $dep_city = '';
	
	public $dep_port_Portcode = '';
	public $dep_port = '';
	
	public $dep_country_CountryCode = '';
	public $dep_country = '';
	
	public $dep_state_Statecode = '';
	public $dep_state = '';
	
	public $dep_term_TermCode = '';
	public $dep_term = '';
	
	// TODO: Add classes - probably as arrays or something
	
	public $sad_SADIndicator = '';
	public $sad_SADCode = '';
	public $sad_SADName = '';
	
	public $arr_ArrTime = '';
	public $arr_city_CityCode = '';
	public $arr_city = '';
	
	public $arr_port_Portcode = '';
	public $arr_port = '';
	
	public $arr_country_CountryCode = '';
	public $arr_country = '';
	
	public $arr_state_Statecode = '';
	public $arr_state = '';
	
	public $arr_term_TermCode = '';
	public $arr_term = '';
		
	
	public function __construct()
	{
		// Set defaults
	}
	
}