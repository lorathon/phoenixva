<?php

class Flightstats_airport extends PVA_Controller
{
	protected $_table_name = 'airports';
	protected $_order_by = 'fs';

	public function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 * getactive function
	 * 
	 * consumes JSON file from flightstats, formats results and saves to DB.
	 * 
	 * $class is required to select which classification level to stop at for importing:
	 * 
	 * 1 - Top 100 airports (volume)
	 * 2 - Next 200 airports
	 * 3 - Next 400 airports
	 * 4 - Airports with flight history (approx 4,700 apts)
	 * 5 - Airports with no flight history (approximately 16,125 apts)
	 * 
	 * array below lists our current hubs' fs code (JFK, LAX, STL, LHR, TXL) and sets airport as hub
	 * when it gets to that airport. May need to be changed if we add or change hubs.
	 *
	 * @author Dustin
	 */
	function getactive()
	{
            
                echo "Processing...";
                echo "<br />";
                
                // required from Post
                $appid = $this->input->post('appid');
                $appkey = $this->input->post('appkey');
                $class = $this->input->post('class');
                $version = $this->input->post('version');
                
		$json = file_get_contents("https://api.flightstats.com/flex/airports/rest/v1/json/active?appId=$appid&appKey=$appkey");
	
		$data = json_decode($json, true);
		
		// counters for results output
		$counter = 0;
		$seaports = 0;
		$heliports = 0;
                
                // mark all airports as inactive
                $airports = new Airport();
                $airports->active = 0;
                $airports->save();
		
                
                 
		foreach($data['airports'] as $stat => $value) {
	
			// first get classification number
			$classification   = isset($value['classification']) ? $value['classification'] : NULL;
			
			
			
			// if classification number matches or is below what we want to import
			if($classification <= $class)
			{
				// get data
				$fs               = isset($value['fs']) ? $value['fs'] : NULL;
				$iata             = isset($value['iata']) ? $value['iata'] : NULL;
				$icao             = isset($value['icao']) ? $value['icao'] : NULL;
				
				/**
				 * Reduce Airport Names (case insenstive):
				 *
				 * International Airport 	-> 	Intl
				 * Internacional Airport	->	Intl
				 * International                ->	Intl
				 * Intl Airport			->	Intl
				 * Regional Airport 		->	Regional
				 *
				 */
				
				$name             = isset($value['name']) ? $value['name'] : NULL;
				$old_name         = array("International Airport", "Internacional Airport", "International", "Intl Airport", "Regional Airport");
				$new_name         = array("Intl", "Intl", "Intl", "Intl", "Regional");
				$name = str_ireplace($old_name, $new_name, $name);
				
				
				$city             = isset($value['city']) ? $value['city'] : NULL;
				$state_code       = isset($value['stateCode']) ? $value['stateCode'] : NULL;
				$country_code     = isset($value['countryCode']) ? $value["countryCode"] : NULL;
				$country_name     = isset($value['countryName']) ? $value['countryName'] : NULL;
				$region_name      = isset($value['regionName']) ? $value['regionName'] : NULL;
				$utc_offset       = isset($value['utcOffsetHours']) ? $value['utcOffsetHours'] : NULL;
				$lat              = isset($value['latitude']) ? $value['latitude'] : NULL;
				$long             = isset($value['longitude']) ? $value['longitude'] : NULL;
				$elevation        = isset($value['elevationFeet']) ? $value['elevationFeet'] : NULL;
				
				// set elevation to 0 if showing a negative number or null
				if ($elevation < 0 || $elevation == null)
				{
					$elevation    = 0;
				}
				
				// set airport to active
				$active           = 1;
				
				
				
				
				/**
				 * PORT TYPE
				 * 
				 * I went through the most common names for seaports and heliports in the flightstats airport records.
				 * This will set the default port type to "land" but look at the airport name and change to sea or heli
				 * depending what the facility is called. It might not get them all but will get the most common ones.
				 */
				
				// default port type to "land"
				$port_type        = "land";
				
				
				// if airport name has common water ports in title, make port type "sea" (case insensitive)
				if (strpos(strtolower($name), "seaplane") !== false || 
                                    strpos(strtolower($name), "sea plane") !== false ||
                                    strpos(strtolower($name), "water aerodrome") !== false ||
                                    strpos(strtolower($name), "water airport") !== false)
				{
					$port_type    = "sea";
					$seaports++;
				}
				
				
				// if airport name has Heliport or Helipad in title, make port type "heli" (case insensitive)
				if (strpos(strtolower($name), "heliport") !== false || 
                                    strpos(strtolower($name), "helipad") !== false)
				{
					$port_type    = "heli";
					$heliports++;
				}

				
				
				/**
				 * HUB FLAG
				 * 
				 * Array lists our current hubs. If the airport currently being interrogated is one
				 * of our hubs, set the hub flag to 1. If not one of the listed hubs, set to 0.
				 * 
				 * Array may need to be changed if we add or change crew centers.
				 */
				
				$hubs = array("jfk","lax","stl","lhr","txl");
				if (in_array(strtolower($fs), $hubs)) {
					$hub = 1;
				} 
				else {
					$hub = 0;
				}
		
				
				
				$delay_url        = isset($value['delayIndexUrl']) ? $value['delayIndexUrl'] : NULL;
				$weather_url      = isset($value['weatherUrl']) ? $value['weatherUrl'] : NULL;
				
				
				// begin save to DB
				set_time_limit(15);
                                
                                $airport_obj = new Airport(array('fs' => $fs));
                                				
				$airport_obj->fs                = $fs;
				$airport_obj->iata              = $iata;
				$airport_obj->icao              = $icao;
				$airport_obj->name              = $name;
				$airport_obj->city              = $city;
				$airport_obj->state_code        = $state_code;
				$airport_obj->country_code      = $country_code;
				$airport_obj->country_name      = $country_name;
				$airport_obj->region_name       = $region_name;
				$airport_obj->utc_offset        = $utc_offset;
				$airport_obj->lat               = $lat;
				$airport_obj->long              = $long;
				$airport_obj->elevation         = $elevation;
				$airport_obj->classification    = $classification;
				$airport_obj->active            = $active;
				$airport_obj->port_type         = $port_type;
				$airport_obj->hub               = $hub;
				$airport_obj->delay_url         = $delay_url;
				$airport_obj->weather_url       = $weather_url;
                                $airport_obj->version           = $version;
				
				$airport_obj->save();

				// remove from memory
				unset($airport_obj);
                                
                                // create flightstats log entry for flight entry
                                $log_obj = new Flightstats_log();

                                $log_obj->type      = "Airport";
                                $log_obj->version   = $version;
                                $log_obj->fs        = $fs;
                                
                                // if no state, omit state code
                                if($state_code == '' || $state_code == null)
                                {
                                    $log_obj->note  = "$name, $city, $country_code activated.";
                                }
                                // note with state code
                                else {
                                    $log_obj->note  = "$name, $city, $state_code, $country_code activated.";
                                }

                                $log_obj->save();
                                $counter++;
				
			}
			// end if classification check, go back to beginning of loop
                        
		}
		// end foreach
		
		echo "$counter airports now active, including $seaports seaports and $heliports heliports.";
	}
	// end getactive function
	
	function deactivate_empty_airport()
        {
            // get list of airport codes
            $airports = new Airport();
            $airports_all = $airports->get_all_airports();
            $counter = 0;
            
            foreach($airports_all as $airport) {
            
                $fs = $airport->fs;
                
                //look in schedules pending table for the id in dep or arr fields
                $query = $this->db->from('schedules_pending')
                                    ->where('dep_airport', $fs)
                                    ->or_where('arr_airport', $fs)
                                    ->limit(1)
                                    ->get();
                
                // if airport has no departures or arrivals, make it inactive
                if($query->num_rows() == 0 )
                {
                    $airport_obj = new Airport();
                    
                    $airport_obj->fs = $fs;
                    $airport_obj->find();
                    
                    $airport_obj->active = 0;
                    $airport_obj->save();
                    
                    $counter++;
                    
                    echo ".";
                }
                
            }
            echo "<br />$counter airports deactivated.";
        }
        
        function get_stranded_flights()
        {
            // get list of active airport codes
            $airports = new Airport();
            $airports->active = 1;
            $airports->get_all_airports();
            
            foreach($airports as $airport) {
            
                $fs = $airport->fs;
                
                //look in schedules pending table for the id in dep or arr fields
                $query = $this->db->from('schedules_pending')
                                    ->where('dep_airport', $fs)
                                    ->get();
                
                // if airport has no departures or arrivals, make it inactive
                if($query->num_rows() == 0 )
                {
                    $airport_obj = new Airport();
                    
                    $airport_obj->fs = $fs;
                    $airport_obj->find();
                    
                    $id = $airport_obj->id;
                    $name = $airport_obj->name;
                    $city = $airport_obj->city;
                    $state = $airport_obj->state_code;
                    $country_code = $airport_obj->country_code;
                    
                    if(isset($state)) 
                    {
                        echo "<br />$fs - $name, $city, $state, $country_code (ID: $id) ";
                    }
                    else {
                        echo "<br />$fs - $name, $city, $country_code (ID: $id) ";
                    }
                    
                }
                
                echo ".";
            }
        }
}