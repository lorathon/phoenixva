<?php

class Calculations
{
    function __construct()
	{
	}
    
    function calculate_airport_distance($dep, $arr)
    {
        return $this->calculate_distance($dep->lat, $dep->lng, $arr->lat, $arr->lng);
    }    
    
    function calculate_distance($deplat = 0, $deplon = 0, $arrlat = 0, $arrlon = 0)
    {
        if(strtolower(config_item('units_distance')) === 'km') 
        {
			$radius = 6378.14;
            $miles = FALSE;
        }
		else
        {
			$radius = 3963.192;
            $miles = TRUE;
        }
            
        $pi80 = M_PI / 180;
        $deplat *= $pi80;
        $deplon *= $pi80;
        $arrlat *= $pi80;
        $arrlon *= $pi80;
        
        $dlat = $arrlat - $deplat;
        $dlng = $arrlon - $deplon;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($deplat) * cos($arrlat) * sin($dlng / 2) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $radius * $c;
     
        return ($miles ? ($km * 0.621371192) : $km); 
    }
    
    /**
	 * Calculate the distance between two coordinates
	 * Using a revised equation found on http://www.movable-type.co.uk/scripts/latlong.html
	 * 
	 * Also converts to proper type based on UNIT setting
	 * http://jan.ucc.nau.edu/~cvm/latlon_formula.html
	 */
	public function distance_between_points($lat1, $lng1, $lat2, $lng2)
	{
		if(strtolower(config_item('units_distance')) === 'mi') # miles
			$radius = 3963.192;
		elseif(strtolower(config_item('units_distance')) === 'km') # Convert to km
			$radius = 6378.14;
		else
			$radius = 3963.192;
		
		$lat1 = deg2rad(floatval($lat1));
		$lat2 = deg2rad(floatval($lat2));
		$lng1 = deg2rad(floatval($lng1));
		$lng2 = deg2rad(floatval($lng2));
		
		$a = sin(($lat2 - $lat1)/2.0);
		$b = sin(($lng2 - $lng1)/2.0);
		$h = ($a*$a) + cos($lat1) * cos($lat2) * ($b*$b);
		$theta = 2 * asin(sqrt($h)); # distance in radians
		
		$distance = $theta * $radius;
		
		return $distance;
	}
    
    function calculate_flighttime($deptime = 0, $arrtime = 0)
    {        
        $arrCheck = str_replace(':', '.', $arrtime);
		$depCheck = str_replace(':', '.', $deptime);

		if($arrCheck < $depCheck)
		{
			$deptime = '2010-01-01 '.$deptime;
			$arrtime = '2010-01-02 '.$arrtime;
		}
		
		$diff = $this->timeDifference($deptime, $arrtime);
		
		if($diff)
		{			
			return sprintf( '%02d.%02d', $diff['hours'], $diff['minutes'] );
		}
        else
        {
            return "00.00";        
        }
    }    
    
    function timeDifference( $start, $end )
	{
	    $uts['start']      =    strtotime( $start );
	    $uts['end']        =    strtotime( $end );
	    if( $uts['start']!==-1 && $uts['end']!==-1 )
	    {
	        if( $uts['end'] >= $uts['start'] )
	        {
	            $diff    =    $uts['end'] - $uts['start'];
	            if( $days=intval((floor($diff/86400))) )
	                $diff = $diff % 86400;
	            if( $hours=intval((floor($diff/3600))) )
	                $diff = $diff % 3600;
	            if( $minutes=intval((floor($diff/60))) )
	                $diff = $diff % 60;
	            $diff    =    intval( $diff );            
	            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
	        }
	        else
	        {
	            return false;
	        }
	    }
	    else
	    {
	        return false;
	    }
	    return false;
	}
    
    /**
     * Calculate a random pricing based on the distance of
     * the schedule.
     * $minPrice & $maxPrice (int) Should be part of a site settings
     * $minDist and $maDist are based on flight length (shortest and longest)
     * $distance is scaled between the above to calulate a price.
     * This price ($price) is then multipled by a random percentage between 1-3%
     * This gives the pricing a random feel.
     * Return of two digit decimal (1234.56)
    */    
    public function random_pricing($distance, $minPrice = 125, $maxPrice = 2000)
    {
        // Min and Max Distances
        $minDist = 0;
        $maxDist = 8500;
        
        // Calculate a price based on the distance and the min and max values for the scale
        $price = ((($maxPrice - $minPrice)*($distance - $minDist)) / ($maxDist - $minDist)) + $minDist;
        
        // Create a random % between 1% and 3% to vary the price by
        $percent = (mt_rand(1000, 3000) * .00001) + 1;
        
        return round(($price * $percent), 2);
    }
		
}