<?php

	
class Flightstatsschedules extends PVA_Controller
{
	protected $_table_name = 'schedules';
	protected $_order_by = 'dep_airport';

	public function __construct()
	{
		parent::__construct();
	}
	
	
	
	
	// single airport departures - 24 hours of data
	public function apt($apt, $day)
	{
		$year = date('Y');
		$month = date('n');
		
		$this->start($apt, $year, $month, $day, 0);
		$this->start($apt, $year, $month, $day, 6);
		$this->start($apt, $year, $month, $day, 12);
		$this->start($apt, $year, $month, $day, 18);
	}


	
	// main function to loop through 4 times for 24 hours of departures at designated airport
	public function start($apt, $year, $month, $day, $hour, $version)
	{
	
		$json = file_get_contents("https://api.flightstats.com/flex/flightstatus/rest/v2/json/airport/status/$apt/dep/$year/$month/$day/$hour?appId=f48100ea&appKey=217ebf7797870e89ad05a5a69e4f4bf6&utc=false&numHours=6");
		
		$data = json_decode($json, true);
		
		$counter = 0;
		$count_skipped = 0;
		
		
		foreach($data['flightStatuses'] as $stat => $value) {
		
			$equip = isset($value['flightEquipment']['scheduledEquipmentIataCode']) ? $value['flightEquipment']['scheduledEquipmentIataCode'] : '';
			$actualEquip = isset($value['flightEquipment']['actualEquipmentIataCode']) ? $value['flightEquipment']['actualEquipmentIataCode'] : '';
			

			//filter out no scheduled or actual aircraft. Dont add to DB
			if($equip != "" || $actualEquip != "") {
				
				
				if($equip == "") {$equip = $actualEquip;}
				
				$carrier = isset($value['carrierFsCode']) ? $value['carrierFsCode'] : '';				  
			  	
				
				/**
				* REGIONAL FLIGHT CHECK
				* 
				* This will go through and determine if the flight is operated as a mainline or
				* regional flight by checking the codeshares for an X or S relationship (Shared airline 
				* designator or wet lease: Flight is marketed by one airline and operated by another, 
				* but under the name of the marketing carrier. The operating carrier does not sell 
				* tickets under their own name.)
				*/
				
				
				$regional = 0;
				$operator = "";
					
				if(isset($value['codeshares']))
				{
				  	foreach ($value['codeshares'] as $item)
				  	{
				  		// if relationships are listed in codeshare details AND they equal X or S (regional)
				  		if (isset($item['relationship']) && ($item['relationship'] == 'X' || $item['relationship'] == 'S')) 
				  		{
				  								  			
				  			foreach($value['codeshares'] as $cs => $fscode) 
				  			{
				  				if($fscode['relationship'] == 'X' || $fscode['relationship'] == 'S')
				  				{
				  					// set the carrier to the codeshare fsCode (ie US Airways)
				  					$regional = 1;
				  					$operator = $carrier;
				  					$carrier = $fscode['fsCode'];		
				  				}
				  			}
				  			
				  		}
				  	}
				  	// end foreach
				}
				// end regional flight check
				
			  	
				  
				  
			  	// get flight id, number and type (passenger/cargo)
			  	$flightId = isset($value['flightId']) ? $value['flightId'] : '';
			  	$flightNumber = isset($value['flightNumber']) ? $value['flightNumber'] : '';
			  	$flightType = isset($value['schedule']['flightType']) ? $value['schedule']['flightType'] : '';
			  
			  
			  
			  	// departure and arrival airports
			  	$depAirport = isset($value['departureAirportFsCode']) ? $value['departureAirportFsCode'] : '';
			  	$arrAirport = isset($value['arrivalAirportFsCode']) ? $value['arrivalAirportFsCode'] : '';
			  
			  
			  
			  	// departure time local
			  	$deplocal1 = isset($value['departureDate']['dateLocal']) ? $value['departureDate']['dateLocal'] : '';
			  	$deplocal = strtotime($deplocal1);
			  	$depTimeLocal = date("H:i", $deplocal);
			  
			  
			  
		  		// departure time UTC
			  	$deputc1 = isset($value['departureDate']['dateUtc']) ? $value['departureDate']['dateUtc'] : '';
			  	$deputc = strtotime(rtrim($deputc1, "Z"));
			  	$depTimeUtc = date("H:i", $deputc);
			  
			  
			  
			  	// arrival time local
			  	$arrlocal1 = isset($value['arrivalDate']['dateLocal']) ? $value['arrivalDate']['dateLocal'] : '';
			  	$arrlocal = strtotime($arrlocal1);
			  	$arrTimeLocal = date("H:i", $arrlocal);
			  
			  
			  
			  	// arrival time UTC
			  	$arrutc1 = isset($value['arrivalDate']['dateUtc']) ? $value['arrivalDate']['dateUtc'] : '';
			  	$arrutc = strtotime(rtrim($arrutc1, "Z"));
			  	$arrTimeUtc = date("H:i", $arrutc);
			
			  
			  
			  	// total flight time
			  	$flightMinutes = isset($value['flightDurations']['scheduledBlockMinutes']) ? $value['flightDurations']['scheduledBlockMinutes'] : '';
			  	
			  	if($flightMinutes == "") 
			  	{
			  		$flightMinutes = isset($value['flightDurations']['scheduledAirMinutes']) ? $value['flightDurations']['scheduledAirMinutes'] : '';
			  	}
			  		$hours = intval($flightMinutes/60);
					$minutes = sprintf("%02d", ($flightMinutes - ($hours * 60)));		
	  			$flightTime = "$hours:$minutes";
			  
			  
			  
			  	// taxi out minutes  
			  	$taxiOutMinutes = isset($value['flightDurations']['scheduledTaxiOutMinutes']) ? $value['flightDurations']['scheduledTaxiOutMinutes'] : '';
			  		$to_hours = intval($taxiOutMinutes/60);
					$to_minutes = sprintf("%02d", ($taxiOutMinutes - ($to_hours * 60)));		
			  	$taxiOutTime = "$to_hours:$to_minutes";
			
			  
			  
			  	// air time  
				$airMinutes = isset($value['flightDurations']['scheduledAirMinutes']) ? $value['flightDurations']['scheduledAirMinutes'] : '';
			  		$at_hours = intval($airMinutes/60);
					$at_minutes = sprintf("%02d", ($airMinutes - ($at_hours * 60)));		
			  	$airTime = "$at_hours:$at_minutes";
			  
			  
			  
			  	// taxi in minutes
			  	$taxiInMinutes = isset($value['flightDurations']['scheduledTaxiInMinutes']) ? $value['flightDurations']['scheduledTaxiInMinutes'] : '';
			  		$ti_hours = intval($taxiInMinutes/60);
					$ti_minutes = sprintf("%02d", ($taxiInMinutes - ($ti_hours * 60)));		
			  	$taxiInTime = "$ti_hours:$ti_minutes";
			  
			  
			  
			  	// departure and arrival gates
			  	$depTerminal = isset($value['airportResources']['departureTerminal']) ? $value['airportResources']['departureTerminal'] : '';
			  	$depGate = isset($value['airportResources']['departureGate']) ? $value['airportResources']['departureGate'] : '';
			  	$arrTerminal = isset($value['airportResources']['arrivalTerminal']) ? $value['airportResources']['arrivalTerminal'] : '';
			  	$arrGate = isset($value['airportResources']['arrivalGate']) ? $value['airportResources']['arrivalGate'] : '';
			  
			  
			  
			  	// get equipment and tail number for flight.
			  	//$equip = isset($value['flightEquipment']['scheduledEquipmentIataCode']) ? $value['flightEquipment']['scheduledEquipmentIataCode'] : '';
			  	//$actualEquip = isset($value['flightEquipment']['actualEquipmentIataCode']) ? $value['flightEquipment']['actualEquipmentIataCode'] : '';
			  	$tailNumber = isset($value['flightEquipment']['tailNumber']) ? $value['flightEquipment']['tailNumber'] : '';
				
			  	
			  	
			  	
			  	
			  	
			  	
			  	
			  	/**
			  	 * DOWNLINE FLIGHT CHECK
			  	 * 
			  	 * Checks if there are any legs continuing on after this leg for the flight number. (Flights on this route that 
			  	 * occur downstream of this one (in order). For example a route that flies from SFO to LAX to JFK to LHR would 
			  	 * have JFK and LHR downstream of the SFO to LAX flight.
			  	 * 
			  	 */
			    
			  	$downlineapt        = "";
			  	$downlineflightid   = "";
			  	
			  	// check if downlines are present in this flight
			  	if(isset($value['schedule']['downlines']))
			  	{
			  		// if so, go into array and get airport and FS flightId
			  		foreach($value['schedule']['downlines'] as $stat => $item)
			  		{
			  			$downlineapt = $item['fsCode'];
			  			$downlineflightid = $item['flightId'];
			  		}
			  	}
			  	// end downline flight check
			  	
			  	
			  	
			  	
			  	

			  	// if no scheduled equipment listed, use actual equipment
			  	if($equip == "") 
			  	{
			  		$equip = $actualEquip;
			  	}
			  
			  
			  	
			  	
				/**
				 * DATABASE INSERT
				 * 
				 * Checks to see if the unique route already exists in the system. Based on:
				 * 
				 * Carrier, Flight #, Dep Airport, Arr Airport, Equipment
				 * 
				 * This will allow for same flight number but using different airframes, to allow
				 * the most flights as possible without having duplicates. (Operated with 757 on Monday
				 * but 767 on Tuesday).
				 * 
				 * Runs a second query matching the above results against the dep_time_local. It will clip
				 * some flights but will make sure we dont have a lot of the same legs with slightly different
				 * departure times.
				 * 
				 * 
				 * If flight already exists, do not add it to the system.
				 * 
				 */
			  	
			  	
			  	// query DB to see if this route already exists
			  	$query =   $this->db->from('schedules')
			  					  	->where('carrier', $carrier)
			  					  	->where('flight_num', $flightNumber)
			  					  	->where('dep_airport', $depAirport)
                                                                        ->where('arr_airport', $arrAirport)
                                                                        ->where('equip', $equip)
			  					  	->get();
			  	
			  	$query2 =  $this->db->from('schedules')
			  						->where('carrier', $carrier)
			  						->where('dep_airport', $depAirport)
			  						->where('arr_airport', $arrAirport)
			  						->where('equip', $equip)
			  						->where('dep_time_local', $depTimeLocal)
			  						->get();
			  	
			  	
			  	// if queries don't return any results
			  	if($query->num_rows() + $query2->num_rows() == 0) {
			  		
			  					  		
				  		// create object to save to DB
					  	set_time_limit(15);
					  	$sched_obj = new Schedule();
					  
					  	$sched_obj->flight_id        = $flightId;
					  	$sched_obj->carrier          = $carrier;
					  	$sched_obj->operator         = $operator;
					  	$sched_obj->flight_num       = $flightNumber;
					  	$sched_obj->dep_airport      = $depAirport;
					  	$sched_obj->arr_airport      = $arrAirport;
					  	$sched_obj->equip            = $equip;
					  	$sched_obj->tail_number      = $tailNumber;
					  	$sched_obj->flight_type      = $flightType;
					  	$sched_obj->regional         = $regional;
					  	$sched_obj->dep_time_local   = $depTimeLocal;
					  	$sched_obj->dep_time_utc     = $depTimeUtc;
					  	$sched_obj->dep_terminal     = $depTerminal;
					  	$sched_obj->dep_gate         = $depGate;
					  	$sched_obj->block_time       = $flightTime;
					  	$sched_obj->taxi_out_time    = $taxiOutTime;
					  	$sched_obj->air_time         = $airTime;
					  	$sched_obj->taxi_in_time     = $taxiInTime;
					  	$sched_obj->arr_time_local   = $arrTimeLocal;
					  	$sched_obj->arr_time_utc     = $arrTimeUtc;
					  	$sched_obj->arr_terminal     = $arrTerminal;
					  	$sched_obj->arr_gate         = $arrGate;
					  	$sched_obj->downline_apt     = $downlineapt;
					  	$sched_obj->downline_fltId   = $downlineflightid;
                                                $sched_obj->version          = $version;
					
					  
					  	$sched_obj->save();
					  
					  	$counter++;
					  	
					  	// if saved, get rid of object before leaving loop for memory purposes
					  	if ($sched_obj->save())
					  	{
					  		unset($sched_obj);
					  	}
					  	// end clear memory
			  	}
			  	// end query and DB insert
			  	
			  	
			  	
			  	// if route is already in the system, skip it
			  	else 
			  	{
			  		$count_skipped++;
			  	}
			  
			}
			// end insert if there is equipment
		}
		// end foreach loop, go back to beginning.
		
		echo "$counter routes added to the database. $count_skipped routes were already in the system and skipped. \r\n Next: ";
	}
	// end start function
}
// end class