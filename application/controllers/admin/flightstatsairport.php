<?php

class Flightstatsapt extends PVA_Controller
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
	
	function getactive($class)
	{
	
		$json = file_get_contents("https://api.flightstats.com/flex/airports/rest/v1/json/active?appId=f48100ea&appKey=217ebf7797870e89ad05a5a69e4f4bf6");
	
		$data = json_decode($json, true);
		
		// counters for results output
		$counter = 0;
		$seaports = 0;
		$heliports = 0;
		
		foreach($data['airports'] as $stat => $value) {
	
			// first get classification number
			$classification   = isset($value['classification']) ? $value['classification'] : '';
			
			
			
			// if classification number matches or is below what we want to import
			if($classification <= $class)
			{
				// get data
				$fs               = isset($value['fs']) ? $value['fs'] : '';
				$iata             = isset($value['iata']) ? $value['iata'] : '';
				$icao             = isset($value['icao']) ? $value['icao'] : '';
				
				/**
				 * Reduce Airport Names (case insenstive):
				 *
				 * International Airport 	-> 	Intl
				 * Internacional Airport	->	Intl
				 * International 			->	Intl
				 * Intl Airport			    ->	Intl
				 * Regional Airport 		->	Regional
				 *
				 */
				
				$name             = isset($value['name']) ? $value['name'] : '';
				$old_name         = array("International Airport", "Internacional Airport", "International", "Intl Airport", "Regional Airport");
				$new_name         = array("Intl", "Intl", "Intl", "Intl", "Regional");
				$name = str_ireplace($old_name, $new_name, $name);
				
				
				$city             = isset($value['city']) ? $value['city'] : '';
				$state_code       = isset($value['stateCode']) ? $value['stateCode'] : '';
				$country_code     = isset($value['countryCode']) ? $value['countryCode'] : '';
				$country_name     = isset($value['countryName']) ? $value['countryName'] : '';
				$region_name      = isset($value['regionName']) ? $value['regionName'] : '';
				$utc_offset       = isset($value['utcOffsetHours']) ? $value['utcOffsetHours'] : '';
				$lat              = isset($value['latitude']) ? $value['latitude'] : '';
				$long             = isset($value['longitude']) ? $value['longitude'] : '';
				$elevation        = isset($value['elevationFeet']) ? $value['elevationFeet'] : '';
				
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
				
				
				// if airport name has Seaplane or Sea Plane in title, make port type "sea" (case insensitive)
				if (strpos(strtolower($name), "seaplane") !== false || strpos(strtolower($name), "sea plane") !== false)
				{
					$port_type    = "sea";
					$seaports++;
				}
				
				
				// if airport name has Heliport or Helipad in title, make port type "heli" (case insensitive)
				if (strpos(strtolower($name), "heliport") !== false || strpos(strtolower($name), "helipad") !== false)
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
		
				
				
				$delay_url        = isset($value['delayIndexUrl']) ? $value['delayIndexUrl'] : '';
				$weather_url      = isset($value['weatherUrl']) ? $value['weatherUrl'] : '';
				
				
				// begin save to DB
				set_time_limit(15);
				$airport_obj = new Airport();
				
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
				
				$airport_obj->save();
				$counter++;

				// if saved, remove from memory
				if ($airport_obj->save())
				{
					unset($airport_obj);
				}
			}
			// end if classification check, go back to beginning of loop
			
		}
		// end foreach
		
		echo "$counter records added to airports table. Including $seaports seaports and $heliports heliports.";
	}
	// end getactive function
	
	
	
	
	/**
	 * writeJsonApt function
	 *
	 * Goes through airports table and creates Twitter Typeahead JSON file for
	 * searching of airports in class 1-4. Makes typeahead show the highest volume
	 * airports first, ordering by highest volume class and then by fs code. 
	 * Saves to assets folder.
	 * 
	 * Folder path for assets may need to be changed depending on environment you are working in.
	 * 
	 * $class is required to select which classification level to stop at for writing:
	 * 
	 * 1 - Top 100 airports (volume)
	 * 2 - Next 200 airports
	 * 3 - Next 400 airports
	 * 4 - Airports with flight history (approx 4,700 apts)
	 * 5 - Airports with no flight history (approximately 16,125 apts)
	 *
	 * @author Dustin
	 */
	
	function writeJsonApt ($class)
	{
		header('Content-Type: application/json');
	
		$linklist=array();
		$link=array();
		
		// get airports in class at or below $class parameter, sets up order for putting into JSON file.
		$this->db->from('airports')->where('classification <=', $class)->order_by('classification ASC, fs ASC');
		$query = $this->db->get();
		
		$counter = 0;
	
		foreach ($query->result() as $row)
		{
			// set fs code
			$link["fs"] = $row->fs;
			 
			// if there is no state code, do not include it in airport name
			if($row->state_code == "" || null)
			{
				$link["typeAhead"] = "$row->fs - $row->name, $row->city, $row->country_code";
			}
			 
			// if there is a state code, include it in the airport name
			else
			{
				$link["typeAhead"] = "$row->fs - $row->name, $row->city, $row->state_code, $row->country_code";
			}

			$counter++;
			array_push($linklist,$link);
		}
		// end foreach
		
		$fp = fopen("/home/phoenix/public_html/zz_dev/gofly02/assets/data/airports.json", "w");
		fwrite($fp, json_encode($linklist));
		//echo json_encode($linklist);
		
		echo "TypeAhead file created, showing $counter Airports.";
	}
	// end writeJsonApt function
	


}
