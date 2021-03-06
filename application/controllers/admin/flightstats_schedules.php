<?php

	
class Flightstats_schedules extends PVA_Controller
{
	protected $_table_name = 'schedules_pending';
	protected $_order_by = 'dep_airport';

	public function __construct()
	{
		parent::__construct();
                $this->_timestamps = TRUE;
	}
	
        //multiple airports flight pull
	public function aptwhere()
        {
                $appid      = $this->input->post('appid');
                $appkey     = $this->input->post('appkey');
                $idstart    = $this->input->post('idstart');
                $idstop     = $this->input->post('idstop');
                $version    = $this->input->post('version');
                $date       = $this->input->post('date');
                
                $year = date('Y', strtotime($date));
                $month = date('n', strtotime($date));
                $day = date('j', strtotime($date));
                $dayofweek = date('D', strtotime($date));
                
                // get list of all active airports between POST IDs
                $airports = new Airport();
                $airports->id = ">= $idstart";
                $airports->id = "<= $idstop";
                $airports->active = 1;
                $airports_all = $airports->get_all_airports();
                
                foreach ($airports_all as $row)
                {
                    $apt = $row->fs;
                    $this->apt($apt, $year, $month, $day, $dayofweek);
                }
        }
	
        //single airport flight pull
	public function aptsingle()
        {
                $appid      = $this->input->post('appid');
                $appkey     = $this->input->post('appkey');
                $apt        = $this->input->post('apt');
                $version    = $this->input->post('version');
                $date       = $this->input->post('date');
                
                $year = date('Y', strtotime($date));
                $month = date('n', strtotime($date));
                $day = date('j', strtotime($date));
                $dayofweek = date('D', strtotime($date));
                
                $this->apt($apt, $year, $month, $day, $dayofweek);
        }
        
	// Airport pull setup
	public function apt($apt, $year, $month, $day, $dayofweek)
	{            
                // required from Post
                $appid = $this->input->post('appid');
                $appkey = $this->input->post('appkey');
                $version = $this->input->post('version');
                                        
                $counter = 0;
                $processed = 0;
                echo "$apt processing";
                
                for ($hour = 0; $hour < 24; $hour++) 
                {
                    $processed += $this->start($apt, $year, $month, $day, $hour, $appid, $appkey, $version, $dayofweek, $counter);
		}
                
                echo "Version $version - $processed flights processed. <br />";
                
                // create flightstats log entry for flight entry
                $log_obj = new Flightstats_log();
                
                $log_obj->type      = "Schedule";
                $log_obj->version   = $version;
                $log_obj->fs        = strtoupper($apt);
                $log_obj->note      = "$processed flights processed.";
                
                $log_obj->save();
                
                unset($log_obj);
	}


	
	// main function to loop through 4 times for 24 hours of departures at designated airport
	public function start($apt, $year, $month, $day, $hour, $appid, $appkey, $version, $dayofweek, $counter)
	{
	
		$json = file_get_contents("https://api.flightstats.com/flex/schedules/rest/v1/json/from/$apt/departing/$year/$month/$day/$hour?appId=$appid&appKey=$appkey&extendedOptions=includeCargo");
		$decode = json_decode($json);
		
		$counter = 0;
                echo ".";
               
                // GET AIRPORT UTC OFFSET, ADD TO ARRAY FOR LATER
                foreach($decode->appendix->airports as $airport) {
                    $fs = $airport->fs;
                    $UTC[$fs] = -1 * $airport->utcOffsetHours;
                }
                
                // GET EQUIPMENT CODES, ADD TO / UPDATE PENDING AIRCRAFT TABLE
                if(isset($decode->appendix->equipments)) {
                    foreach($decode->appendix->equipments as $equipments) {
                        
                        $equip_obj = new Airframe();
                        
                        // look up IATA
                        $equip_obj->iata       = $equipments->iata;
                        $equip_obj->find();
                        
                        // only insert if adding new airframe.
                        if( ! $equip_obj->id )
                        {
                            $equip_obj->name        = $equipments->name;
                            $equip_obj->regional    = $equipments->regional == true ? 1 : 0;
                            $equip_obj->turboprop   = $equipments->turboProp == true ? 1 : 0;
                            $equip_obj->jet         = $equipments->jet == true ? 1 : 0;
                            $equip_obj->widebody    = $equipments->widebody == true ? 1 : 0;

                            $equip_obj->save();
                        }
                        unset($equip_obj);
                    }
                    
                } 
                    
                
                // BUILD FLIGHTS AND ADD TO / UPDATE PENDING SCHEDULES TABLE
                foreach($decode->scheduledFlights as $flight) {
                    
                    $operator = NULL;
                    $regional = 0;
                    $brand = NULL;
                    $classes = NULL;
                    
                    // ONLY BUILD FLIGHT THAT IS NOT A CODESHARE
                    if($flight->isCodeshare == 0) {
                        
                        $carrier = $flight->carrierFsCode;
                        
                        // IF REGIONAL FLIGHT, SET VALUES
                        if($flight->isWetlease == 1) {
                            $operator = isset($flight->wetleaseOperatorFsCode) ? $flight->wetleaseOperatorFsCode : NULL;
                            $regional = 1;
                            $brand = isset($flight->brand) ? $flight->brand : NULL;
                        }
                        
                        // GET SERVICE CLASS
                        if(isset($flight->serviceClasses)) {
                            $classes = join(',', $flight->serviceClasses);
                        }
                        
                        $flight_num = $flight->flightNumber;
                        $dep_airport = $flight->departureAirportFsCode;
                        $arr_airport = $flight->arrivalAirportFsCode;
                        $equip = isset($flight->flightEquipmentIataCode) ? $flight->flightEquipmentIataCode : NULL;
                        $service_type = isset($flight->serviceType) ? $flight->serviceType : NULL;
                        $arr_terminal = isset($flight->arrivalTerminal) ? $flight->arrivalTerminal : NULL;
                        $dep_time_local = date("H:i", strtotime($flight->departureTime));
                        $dep_time_utc = date("H:i", strtotime("$flight->departureTime $UTC[$dep_airport] hour"));
                        $dep_terminal = isset($flight->departureTerminal) ? $flight->departureTerminal : NULL;
                        $block_time = date("H:i", (strtotime("$flight->arrivalTime $UTC[$arr_airport] hour") - strtotime("$flight->departureTime $UTC[$dep_airport] hour")));
                        $arr_time_local = date("H:i", strtotime($flight->arrivalTime));
                        $arr_time_utc = date("H:i", strtotime("$flight->arrivalTime $UTC[$arr_airport] hour"));
                        
                        
                        // ADD TO DB
                        set_time_limit(15);
                        $sched_obj = new Schedules_pending();
                        
                        $sched_obj->carrier          = $carrier;
                        $sched_obj->flight_num       = $flight_num;
                        $sched_obj->dep_airport      = $dep_airport;
                        $sched_obj->arr_airport      = $arr_airport;
                        $sched_obj->equip            = $equip;
                        $sched_obj->version          = $version;
                        $sched_obj->find();

                        $sched_obj->operator         = $operator;
                        $sched_obj->service_type     = $service_type;
                        $sched_obj->service_classes  = $classes;
                        $sched_obj->regional         = $regional;
                        $sched_obj->brand            = $brand;
                        $sched_obj->dep_time_local   = $dep_time_local;
                        $sched_obj->dep_time_utc     = $dep_time_utc;
                        $sched_obj->dep_terminal     = $dep_terminal;
                        $sched_obj->block_time       = $block_time;
                        $sched_obj->arr_time_local   = $arr_time_local;
                        $sched_obj->arr_time_utc     = $arr_time_utc;
                        $sched_obj->arr_terminal     = $arr_terminal;
                        
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
                        
                        unset($sched_obj);
                    }
                    // END IF CODESHARE IS FALSE
                    unset($flight);
                }
                // END FOR EACH FLIGHT IN JSON
                return $counter;
        }
	// end start function
}
// end class