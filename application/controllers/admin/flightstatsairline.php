<?php

class Flightstatsairline extends PVA_Controller
{
	protected $_table_name = 'airlines';
	protected $_order_by = 'fs';

	public function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * getactive function
	 * 
	 * Consumes flightstats airlines records for active airlines. Formats and saves
	 * to DB.
	 * 
	 * @author Dustin
	 * 
	 */

	function getactive()
	{
                echo "Processing...";
                echo "<br />";
                
                // required from Post
                $appid = $this->input->post('appid');
                $appkey = $this->input->post('appkey');
                
		$json = file_get_contents("https://api.flightstats.com/flex/airlines/rest/v1/json/active?appId=$appid&appKey=$appkey&extendedOptions=includeNewFields");
		
		$data = json_decode($json, true);
		
		$counter = 0;
                
                // mark all airports as inactive (except PVA*)
                $airdata = array('active' => 0);
                $this->db->where_not_in('fs', 'PVA*')->update('airlines', $airdata);
		
		foreach($data['airlines'] as $stat => $value) {
					
			$fs = isset($value['fs']) ? $value['fs'] : NULL;
			$iata = isset($value['iata']) ? $value['iata'] : NULL;
			$icao = isset($value['icao']) ? $value['icao'] : NULL;
			$name = isset($value['name']) ? $value['name'] : NULL;
			$active = 1;
			$category = isset($value['category']) ? $value['category'] : NULL;
			
			
			// begin save to DB
			$airline_obj = new Airline(array('fs' => $fs));
			  
			$airline_obj->fs         		= $fs;
			$airline_obj->iata       		= $iata;
			$airline_obj->icao       		= $icao;
			$airline_obj->name		        = $name;
			$airline_obj->active     		= $active;
			$airline_obj->category   		= $category;
			
	
			$airline_obj->save();
			$counter++;
			  
			// remove from memory
			unset($airline_obj);
			
		}
		// end foreach
	 
	echo "$counter airlines now active in the database.";
	 
	}
	// end getactive function
	
	
	
	
	
	
	/**
	 * getlogos function
	 * 
	 * Goes to the airlines table in the DB and for each airline, download the corresponding
	 * 300x100 PNG logo from flightstats and save them to the assets folder. Flightstats stores
	 * the logos in lowercase. Saves the logos to the assets folder in uppercase to match DB.
	 * 
	 * Also gets smaller GIF file if available.
	 * 
	 * folder to save logos may need to be changed depending on environment you are working in.
	 * 
	 * @author Dustin
	 */
	
	function getLogos ()
	{
		// get all active airlines in the DB table
		$this->db->from('airlines')->where('active =', 1)->order_by('fs ASC');
		$query = $this->db->get();
		
		foreach ($query->result() as $row)
		{
			// make lowercase version to get from flightstats
			$fs = $row->fs;
			$fs_lower = strtolower($fs);
			
			/**
			$content_png = file_get_contents("http://dskx8vepkd3ev.cloudfront.net/airline-logos/v2/logos/png/300x100/$fs_lower-logo.png");

			//store in the assets/img/airline-logos folder
			$fp = fopen("/home/phoenix/public_html/zz_dev/gofly02/assets/img/airline_logos_png/$fs.png", "w");
			fwrite($fp, $content_png);
			fclose($fp);
			*/
			
			$content_gif = file_get_contents("http://dem5xqcn61lj8.cloudfront.net/logos/$fs.gif");
			$fp2 = fopen("/home/phoenix/public_html/zz_dev/gofly02/assets/img/airline_logos_gif/$fs.gif", "w");
			fwrite($fp2, $content_gif);
			fclose($fp2);
		}
		
	}
	
	
	
	
	
	/**
	 * writeJsonAirline function
	 *
	 * Goes through airlines table and creates Twitter Typeahead JSON file for
	 * searching of active airlines. Saves to assets/data folder.
	 * 
	 * folder path to save JSON may need to be changed depending on environment you are working in.
	 * 
	 * @author Dustin
	 */
	
	function writeJsonAirline ()
	{
		header('Content-Type: application/json');
		
		$linklist=array();
		$link=array();
		
		// get only active airlines from DB table.
		$this->db->from('airlines')->where('active =', 1)->order_by('fs ASC');
		$query = $this->db->get();
		
		$counter = 0;
		
		foreach ($query->result() as $row)
		{
			$link["fs"]=$row->fs;
			$link["typeAhead"]="$row->fs - $row->name";
			array_push($linklist,$link);
			$counter++;
		}
		// end foreach
		
		// save JSON file to assets folder
		$fp = fopen("/home/phoenix/public_html/zz_dev/gofly02/assets/data/airlines.json", "w");
		fwrite($fp, json_encode($linklist));
		//echo json_encode($linklist);
		
		echo "TypeAhead file created, showing $counter Airlines.";
	}
}

