<?php

	
class Flightstatsschedules extends PVA_Controller
{
	protected $_table_name = 'schedules';
	protected $_order_by = 'dep_airport';

	public function __construct()
	{
		parent::__construct();
	}
	
        //multiple airports flight pull
	public function aptwhere()
        {
                $appid      = $this->input->post('appid');
                $appkey     = $this->input->post('appkey');
                $idstart    = $this->input->post('idstart');
                $idstop     = $this->input->post('idstop');
                $version    = $this->input->post('version');
                
                
                $query = $this->db->select('fs')
                                    ->from('airports')
                                    ->where('id >=', $idstart)
                                    ->where('id <=', $idstop)
                                    ->where('active', 1)
                                    ->get();
                
                foreach ($query->result() as $row)
                {
                    $apt = $row->fs;
                    $this->apt($apt);
                    }
                
        }
	
        //single airport flight pull
	public function aptsingle()
        {
                $appid      = $this->input->post('appid');
                $appkey     = $this->input->post('appkey');
                $apt        = $this->input->post('apt');
                $version    = $this->input->post('version');
                
                $this->apt($apt);
        }
        
	// single airport flight pull
	public function apt($apt)
	{            
                // required from Post
                $appid = $this->input->post('appid');
                $appkey = $this->input->post('appkey');
                $version = $this->input->post('version');
                
                // setup dates for the previous week
                $date = date('D Y-n-j');
                
                $date1 = date('D Y-n-j', strtotime("$date -7 day"));
                $date2 = date('D Y-n-j', strtotime("$date -6 day"));
                $date3 = date('D Y-n-j', strtotime("$date -5 day"));
                $date4 = date('D Y-n-j', strtotime("$date -4 day"));
                $date5 = date('D Y-n-j', strtotime("$date -3 day"));
                $date6 = date('D Y-n-j', strtotime("$date -2 day"));
                $date7 = date('D Y-n-j', strtotime("$date -1 day"));
                
                $year1 = date('Y', strtotime($date1)); $month1 = date('n', strtotime($date1)); $day1 = date('j', strtotime($date1)); $dow1 = date('D', strtotime($date1));
                $year2 = date('Y', strtotime($date2)); $month2 = date('n', strtotime($date2)); $day2 = date('j', strtotime($date2)); $dow2 = date('D', strtotime($date2));
                $year3 = date('Y', strtotime($date3)); $month3 = date('n', strtotime($date3)); $day3 = date('j', strtotime($date3)); $dow3 = date('D', strtotime($date3));
                $year4 = date('Y', strtotime($date4)); $month4 = date('n', strtotime($date4)); $day4 = date('j', strtotime($date4)); $dow4 = date('D', strtotime($date4));
                $year5 = date('Y', strtotime($date5)); $month5 = date('n', strtotime($date5)); $day5 = date('j', strtotime($date5)); $dow5 = date('D', strtotime($date5));
                $year6 = date('Y', strtotime($date6)); $month6 = date('n', strtotime($date6)); $day6 = date('j', strtotime($date6)); $dow6 = date('D', strtotime($date6));
                $year7 = date('Y', strtotime($date7)); $month7 = date('n', strtotime($date7)); $day7 = date('j', strtotime($date7)); $dow7 = date('D', strtotime($date7));
		
                
                $counter = 0;
                
                // Day 1 (7 days ago, 0000-0600 moved to end using todays date. Possibly not in flightstats DB anymore otherwise)
		
		$processed = $this->start($apt, $year1, $month1, $day1, 6, $appid, $appkey, $version, $dow1, $counter);
		//$processed += $this->start($apt, $year1, $month1, $day1, 12, $appid, $appkey, $version, $dow1, $counter);
		//$processed += $this->start($apt, $year1, $month1, $day1, 18, $appid, $appkey, $version, $dow1, $counter);
                
                /* Day 2 (6 days ago)
		$processed += $this->start($apt, $year2, $month2, $day2, 0, $appid, $appkey, $version, $dow2, $counter);
		$processed += $this->start($apt, $year2, $month2, $day2, 6, $appid, $appkey, $version, $dow2, $counter);
		$processed += $this->start($apt, $year2, $month2, $day2, 12, $appid, $appkey, $version, $dow2, $counter);
		$processed += $this->start($apt, $year2, $month2, $day2, 18, $appid, $appkey, $version, $dow2, $counter);
                      
                // Day 3 (5 days ago)
		$processed += $this->start($apt, $year3, $month3, $day3, 0, $appid, $appkey, $version, $dow3, $counter);
		$processed += $this->start($apt, $year3, $month3, $day3, 6, $appid, $appkey, $version, $dow3, $counter);
		$processed += $this->start($apt, $year3, $month3, $day3, 12, $appid, $appkey, $version, $dow3, $counter);
		$processed += $this->start($apt, $year3, $month3, $day3, 18, $appid, $appkey, $version, $dow3, $counter);
                
                // Day 4 (4 days ago)
		$processed += $this->start($apt, $year4, $month4, $day4, 0, $appid, $appkey, $version, $dow4, $counter);
		$processed += $this->start($apt, $year4, $month4, $day4, 6, $appid, $appkey, $version, $dow4, $counter);
		$processed += $this->start($apt, $year4, $month4, $day4, 12, $appid, $appkey, $version, $dow4, $counter);
		$processed += $this->start($apt, $year4, $month4, $day4, 18, $appid, $appkey, $version, $dow4, $counter);
                
                // Day 5 (3 days ago)
		$processed += $this->start($apt, $year5, $month5, $day5, 0, $appid, $appkey, $version, $dow5, $counter);
		$processed += $this->start($apt, $year5, $month5, $day5, 6, $appid, $appkey, $version, $dow5, $counter);
		$processed += $this->start($apt, $year5, $month5, $day5, 12, $appid, $appkey, $version, $dow5, $counter);
		$processed += $this->start($apt, $year5, $month5, $day5, 18, $appid, $appkey, $version, $dow5, $counter);
                
                // Day 6 (2 day ago)
		$processed += $this->start($apt, $year6, $month6, $day6, 0, $appid, $appkey, $version, $dow6, $counter);
		$processed += $this->start($apt, $year6, $month6, $day6, 6, $appid, $appkey, $version, $dow6, $counter);
		$processed += $this->start($apt, $year6, $month6, $day6, 12, $appid, $appkey, $version, $dow6, $counter);
		$processed += $this->start($apt, $year6, $month6, $day6, 18, $appid, $appkey, $version, $dow6, $counter);
                
                // Day 7 (1 day ago)
		$processed += $this->start($apt, $year7, $month7, $day7, 0, $appid, $appkey, $version, $dow7, $counter);
		$processed += $this->start($apt, $year7, $month7, $day7, 6, $appid, $appkey, $version, $dow7, $counter);
		$processed += $this->start($apt, $year7, $month7, $day7, 12, $appid, $appkey, $version, $dow7, $counter);
		$processed += $this->start($apt, $year7, $month7, $day7, 18, $appid, $appkey, $version, $dow7, $counter);
                */
                // Today
                $processed += $this->start($apt, date('Y'), date('n'), date('j'), 0, $appid, $appkey, $version, date('D'), $counter);
                
                echo "$apt - Version $version - $processed flights processed. <br />";
                
                // create flightstats log entry for flight entry
                $log_obj = new Flightstats_log();
                
                $log_obj->type      = "Schedule";
                $log_obj->version   = $version;
                $log_obj->fs        = $apt;
                $log_obj->note      = "$processed flights processed.";
                
                $log_obj->save();
	}


	
	// main function to loop through 4 times for 24 hours of departures at designated airport
	public function start($apt, $year, $month, $day, $hour, $appid, $appkey, $version, $dayofweek, $counter)
	{
	
		$json = file_get_contents("https://api.flightstats.com/flex/flightstatus/rest/v2/json/airport/status/$apt/dep/$year/$month/$day/$hour?appId=$appid&appKey=$appkey&utc=false&numHours=6");
		
		$data = json_decode($json, true);
		
		//$counter = 0;
		
		
		foreach($data['flightStatuses'] as $stat => $value) {
		
			$equip = isset($value['flightEquipment']['scheduledEquipmentIataCode']) ? $value['flightEquipment']['scheduledEquipmentIataCode'] : '';
			$actualEquip = isset($value['flightEquipment']['actualEquipmentIataCode']) ? $value['flightEquipment']['actualEquipmentIataCode'] : '';
			

			//filter out no scheduled or actual aircraft. Dont add to DB
			if($equip != "" || $actualEquip != "") {
				
				
				if($equip == "") {$equip = $actualEquip;}
				
				$carrier = isset($value['carrierFsCode']) ? $value['carrierFsCode'] : NULL;				  
			  	
				
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
				$operator = NULL;
					
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
			  	$flightId = isset($value['flightId']) ? $value['flightId'] : NULL;
			  	$flightNumber = isset($value['flightNumber']) ? $value['flightNumber'] : NULL;
			  	$flightType = isset($value['schedule']['flightType']) ? $value['schedule']['flightType'] : NULL;
			  
			  
			  
			  	// departure and arrival airports
			  	$depAirport = isset($value['departureAirportFsCode']) ? $value['departureAirportFsCode'] : NULL;
			  	$arrAirport = isset($value['arrivalAirportFsCode']) ? $value['arrivalAirportFsCode'] : NULL;
			  
			  
			  
			  	// departure time local
			  	$deplocal1 = isset($value['departureDate']['dateLocal']) ? $value['departureDate']['dateLocal'] : NULL;
			  	$deplocal = strtotime($deplocal1);
			  	$depTimeLocal = date("H:i", $deplocal);
			  
			  
			  
		  		// departure time UTC
			  	$deputc1 = isset($value['departureDate']['dateUtc']) ? $value['departureDate']['dateUtc'] : NULL;
			  	$deputc = strtotime(rtrim($deputc1, "Z"));
			  	$depTimeUtc = date("H:i", $deputc);
			  
			  
			  
			  	// arrival time local
			  	$arrlocal1 = isset($value['arrivalDate']['dateLocal']) ? $value['arrivalDate']['dateLocal'] : NULL;
			  	$arrlocal = strtotime($arrlocal1);
			  	$arrTimeLocal = date("H:i", $arrlocal);
			  
			  
			  
			  	// arrival time UTC
			  	$arrutc1 = isset($value['arrivalDate']['dateUtc']) ? $value['arrivalDate']['dateUtc'] : NULL;
			  	$arrutc = strtotime(rtrim($arrutc1, "Z"));
			  	$arrTimeUtc = date("H:i", $arrutc);
			
			  
			  
			  	// total flight time
			  	$flightMinutes = isset($value['flightDurations']['scheduledBlockMinutes']) ? $value['flightDurations']['scheduledBlockMinutes'] : NULL;
			  	
			  	if($flightMinutes == "") 
			  	{
			  		$flightMinutes = isset($value['flightDurations']['scheduledAirMinutes']) ? $value['flightDurations']['scheduledAirMinutes'] : NULL;
			  	}
			  		$hours = intval($flightMinutes/60);
					$minutes = sprintf("%02d", ($flightMinutes - ($hours * 60)));		
	  			$flightTime = "$hours:$minutes";
			  
			  
			  
			  	// taxi out minutes  
			  	$taxiOutMinutes = isset($value['flightDurations']['scheduledTaxiOutMinutes']) ? $value['flightDurations']['scheduledTaxiOutMinutes'] : NULL;
			  		$to_hours = intval($taxiOutMinutes/60);
					$to_minutes = sprintf("%02d", ($taxiOutMinutes - ($to_hours * 60)));		
			  	$taxiOutTime = "$to_hours:$to_minutes";
			
			  
			  
			  	// air time  
				$airMinutes = isset($value['flightDurations']['scheduledAirMinutes']) ? $value['flightDurations']['scheduledAirMinutes'] : NULL;
			  		$at_hours = intval($airMinutes/60);
					$at_minutes = sprintf("%02d", ($airMinutes - ($at_hours * 60)));		
			  	$airTime = "$at_hours:$at_minutes";
			  
			  
			  
			  	// taxi in minutes
			  	$taxiInMinutes = isset($value['flightDurations']['scheduledTaxiInMinutes']) ? $value['flightDurations']['scheduledTaxiInMinutes'] : NULL;
			  		$ti_hours = intval($taxiInMinutes/60);
					$ti_minutes = sprintf("%02d", ($taxiInMinutes - ($ti_hours * 60)));		
			  	$taxiInTime = "$ti_hours:$ti_minutes";
			  
			  
			  
			  	// departure and arrival gates
			  	$depTerminal = isset($value['airportResources']['departureTerminal']) ? $value['airportResources']['departureTerminal'] : NULL;
			  	$depGate = isset($value['airportResources']['departureGate']) ? $value['airportResources']['departureGate'] : NULL;
			  	$arrTerminal = isset($value['airportResources']['arrivalTerminal']) ? $value['airportResources']['arrivalTerminal'] : NULL;
			  	$arrGate = isset($value['airportResources']['arrivalGate']) ? $value['airportResources']['arrivalGate'] : NULL;
			  
			  
			  
			  	// get equipment and tail number for flight.
			  	//$equip = isset($value['flightEquipment']['scheduledEquipmentIataCode']) ? $value['flightEquipment']['scheduledEquipmentIataCode'] : '';
			  	//$actualEquip = isset($value['flightEquipment']['actualEquipmentIataCode']) ? $value['flightEquipment']['actualEquipmentIataCode'] : '';
			  	$tailNumber = isset($value['flightEquipment']['tailNumber']) ? $value['flightEquipment']['tailNumber'] : NULL;
				
			  	
			  	
			  	
			  	
			  	
			  	
			  	
			  	/**
			  	 * DOWNLINE FLIGHT CHECK
			  	 * 
			  	 * Checks if there are any legs continuing on after this leg for the flight number. (Flights on this route that 
			  	 * occur downstream of this one (in order). For example a route that flies from SFO to LAX to JFK to LHR would 
			  	 * have JFK and LHR downstream of the SFO to LAX flight.
			  	 * 
			  	 */
			    
			  	$downlineapt        = NULL;
			  	$downlineflightid   = NULL;
			  	
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
			  	if($equip == "" || $equip == NULL) 
			  	{
			  		$equip = $actualEquip;
			  	}
			  
			  
			  	
			  	
				/**
				 * DATABASE INSERT
				 * 
				 * Checks to see if the unique route already exists in the system. Based on:
				 * 
				 * Carrier, Flight #, Dep Airport, Arr Airport, Equipment, Schedule Version (added 20150209)
				 * 
				 * If flight already exists, it will update the day of week of the existing flight.
				 * 
				 */
			  	                    
                                // create object to save to DB
                                set_time_limit(15);
                                $sched_obj = new Schedules_pending();

                                $sched_obj->carrier          = $carrier;
                                $sched_obj->flight_num       = $flightNumber;
                                $sched_obj->dep_airport      = $depAirport;
                                $sched_obj->arr_airport      = $arrAirport;
                                $sched_obj->equip            = $equip;
                                $sched_obj->version          = $version;

                                $sched_obj->find();

                                $sched_obj->operator         = $operator;
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

                                switch ($dayofweek) {
                                    case "Sun":
                                        $sched_obj->sun   = 1;
                                        break;
                                    case "Mon":
                                        $sched_obj->mon   = 1;
                                        break;
                                    case "Tue":
                                        $sched_obj->tue   = 1;
                                        break;
                                    case "Wed":
                                        $sched_obj->wed   = 1;
                                        break;
                                    case "Thu":
                                        $sched_obj->thu   = 1;
                                        break;
                                    case "Fri":
                                        $sched_obj->fri   = 1;
                                        break;
                                    case "Sat":
                                        $sched_obj->sat   = 1;
                                        break;
                                    default:
                                        break;
                                }

                                $sched_obj->save();
                                $counter++;

                                // remove from memory
                                unset($sched_obj);
                                					  	
			  	}
		}
		// end foreach loop, go back to beginning.
                return $counter;
	}
	// end start function
}
// end class