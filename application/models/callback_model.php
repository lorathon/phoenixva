<?php
/*
 * Call back function for Grocery CRUD
 * Version 1.0.0
 * Jeffrey F. Kobus
 * 
*/

class Callback_model extends MY_Model
{	
    function __construct()
    {
        parent::__construct();
        //$this->load->library('Grocery_CRUD');
    }
    
	/* Check uploaded file
	 * Make sure that file matches set types
	 * Check file for set max uploadable size
	 *
	 * File Types
	 * Default Location: application/config/grocery_crud.php
	 * grocery_crud_file_upload_allow_file_types
	 * Example:
	 * $this->config->grocery_crud_file_upload_allow_file_types = 'jpeg|jpg|png|gif';
	 *
	 * File Size
	 * Default Location: application/config/grocery_crud.php
	 * grocery_crid_file_upload_max_file_size
	 * Data: '10MB' (Mega Bytes), '1067KB' (Kilo Bytes), '5000B' (Bytes)
	 * Example:
	 * $this->config->grocery_crud_file_upload_max_file_size = '1MB';
	 *
	 * Return string for an error. 
	 * Return FALSE for standard error.
	 * Returned string or FALSE will stop upload
	*/	
    public function _valid_file($files,$field_info)    {
        
        // Check for valid file types
        $vtypes   = $this->config->grocery_crud_file_upload_allow_file_types;
        $mtype    = $files[$field_info->encrypted_field_name]['type'];
        $errorstr ="Sorry - Only Image files allowed ($vtypes)";
        if ( stripos($mtype,'image') !== False ) {
           if (stripos($vtypes,substr($mtype,6)) === False) {
                return $errorstr;
            }
        } else {
            return $errorstr;
        }
        
        // Check for valid file size
        $_maxsize   = $this->config->grocery_crud_file_upload_max_file_size;
        $_filesize  = $files[$field_info->encrypted_field_name]['size'];		
        $_maxsize = $this->_convert_bytes_ui_to_bytes($_maxsize);
    
        if($_filesize > $_maxsize)
            return "Sorry - File size is limited to ".$_maxsize;
    }      
    
	/* Resize uploaded image
	 * check image against maxW and maxH
	 * scale to ensure image is under max
	 *
	 * Image Size
	 * Default Location: application/config/admin/admin_config.php
	 * img_size
	 * $this->config->img_size = array('X' => 130, 'Y' => 35);
	 *
	 * Return string for an error. 
	 * Return FALSE for standard error.
	 * Returned string or FALSE will stop upload
	*/	
    public function _image_resize($uploader_response, $field_info, $files_to_upload)
    {    
        $this->load->library('image_moo');
        
        $_size = $this->config->img_size;
        
        //Is only one file uploaded so it ok to use it with $uploader_response[0].
        $file_uploaded = $field_info->upload_path.'/'.$uploader_response[0]->name;
        
        $this->image_moo->load($file_uploaded)->save($file_uploaded,true);
        $this->image_moo->load($file_uploaded)->resize($_size['X'],$_size['Y'])->save($file_uploaded, true);
        
        return TRUE;
    }
	
	
	/* Common Callbacks */
	public function _readonly($value)
    {
        //return '<span class="input uneditable-input">' . $value . '</span>';
		return '<input type="text" value="'.$value.'" name="" readonly>';
    }
	
	public function _readonly_textarea($value)
    {
		return '<textarea readonly style="height: 200px">' . $value .'</textarea>';
    }
	
    
    /* Aircraft Callbacks */
    public function _add_miles_range($value)
    {
		return '<div class="input-append"><input type="text" class="numeric" value="'.$value.'" name="range" style="width:178px"><span class="add-on"> '.config_item('units_distance').'</span></div>';
	}
    
    public function _add_pounds_oew($value)
    {
        return '<div class="input-append"><input type="text" class="numeric" value="'.$value.'" name="oew" style="width:176px"><span class="add-on"> '.config_item('units_weight').'s</span></div>';
    }
    
    public function _add_pounds_mzfw($value)
    {
        return '<div class="input-append"><input type="text" class="numeric" value="'.$value.'" name="mzfw" style="width:176px"><span class="add-on"> '.config_item('units_weight').'s</span></div>';
    }
    
    public function _add_pounds_mlw($value)
    {
        return '<div class="input-append"><input type="text" class="numeric" value="'.$value.'" name="mlw" style="width:176px"><span class="add-on"> '.config_item('units_weight').'s</span></div>';
    }
    
    public function _add_pounds_mtow($value)
    {
        return '<div class="input-append"><input type="text" class="numeric" value="'.$value.'" name="mtow" style="width:176px"><span class="add-on"> '.config_item('units_weight').'s</span></div>';
    }
    
    public function _add_pounds_cargo($value)
    {
        return '<div class="input-append"><input type="text" class="numeric" value="'.$value.'" name="cargo" style="width:176px"><span class="add-on"> '.config_item('units_weight').'s</span></div>';
    }
	
	 public function _count_pax_first($value)
    {
        return '<div class="input-append"><input type="text" class="numeric" value="'.$value.'" name="pax_first" style="width:161px"><span class="add-on">seats</span></div>';
    }
    
    public function _count_pax_business($value)
    {
        return '<div class="input-append"><input type="text" class="numeric" value="'.$value.'" name="pax_business" style="width:161px"><span class="add-on">seats</span></div>';
    }
    
    public function _count_pax_economy($value)
    {
        return '<div class="input-append"><input type="text" class="numeric" value="'.$value.'" name="pax_economy" style="width:161px"><span class="add-on">seats</span></div>';
    }    
    
    
    /* Schedule Callbacks */
    public function _add_miles_distance($value)
    {
		return '<div class="input-append"><input readonly type="text" value="'.$value.'" name="distance" style="width:178px"><span class="add-on"> '.config_item('units_distance').'</span></div>';
    }
	
	public function _add_hours_flighttime($value)
    {
		$value = str_replace('.', ':', $value);
		return '<div class="input-append"><input readonly type="text" value="'.$value.'" name="flight_time" style="width:160px"><span class="add-on">hours</span></div>';
    }
	
	public function _add_feet_flightlevel($value)
    {
        return '<div class="input-append"><input type="text" value="'.$value.'" name="flight_level" style="width:178px"><span class="add-on"> '.config_item('units_altitude').'</span></div>';
    }
    
    public function _add_gmt_deptime($value)
    {
        return '<div class="input-append"><input type="text" value="'.$value.'" name="dep_time" style="width:165px"><span class="add-on">GMT</span></div>';
    }
    
    public function _add_gmt_arrtime($value)
    {
        return '<div class="input-append"><input type="text" value="'.$value.'" name="arr_time" style="width:165px"><span class="add-on">GMT</span></div>';
    }
    
    public function _dollar_pax_first($value)
    {
		$value = (empty($value)) ? '0.00' : $value;
		$value = number_format($value, 2, '.', '');		
		if(config_item('setting_random_price'))		
			return '<div class="input-prepend"><span class="add-on">$</span><input type="text" readonly class="numeric" value="'.$value.'" name="price_first" style="width:180px"></div>';
		else
			return '<div class="input-prepend"><span class="add-on">$</span><input type="text" class="numeric" value="'.$value.'" name="price_first" style="width:180px"></div>';
	}
    
    public function _dollar_pax_business($value)
    {
		$value = (empty($value)) ? '0.00' : $value;
		$value = number_format($value, 2, '.', '');
		if(config_item('setting_random_price'))
			return '<div class="input-prepend"><span class="add-on">$</span><input type="text" readonly class="numeric" value="'.$value.'" name="price_business" style="width:180px"></div>';
		else
			return '<div class="input-prepend"><span class="add-on">$</span><input type="text" class="numeric" value="'.$value.'" name="price_business" style="width:180px"></div>';
	}
    
    public function _dollar_pax_economy($value)
    {
		$value = (empty($value)) ? '0.00' : $value;
		$value = number_format($value, 2, '.', '');
		if(config_item('setting_random_price'))
			return '<div class="input-prepend"><span class="add-on">$</span><input type="text" readonly class="numeric" value="'.$value.'" name="price_economy" style="width:180px"></div>';
		else
			return '<div class="input-prepend"><span class="add-on">$</span><input type="text" class="numeric" value="'.$value.'" name="price_economy" style="width:180px"></div>';
	}
    
    public function _dollar_cargo($value)
    {
		$value = (empty($value)) ? '0.00' : $value;
		$value = number_format($value, 2, '.', '');
		if(config_item('setting_random_price'))
			return '<div class="input-append input-prepend"><span class="add-on">$</span><input type="text" readonly class="numeric" value="'.$value.'" name="price_cargo" style="width:135px"><span class="add-on">per '.config_item('units_weight').'</span></div>';
		else
			return '<div class="input-append input-prepend"><span class="add-on">$</span><input type="text" class="numeric" value="'.$value.'" name="price_cargo" style="width:135px"><span class="add-on">per  '.config_item('units_weight').'</span></div>';
	}
    
    public function _callback_enabled($value)
    {
        if($value == 0)
            $img = 'fail_16x16.png';
        else
            $img = 'success_16x16.png';
            
        return '<img src="' . base_url('images/icons/'.$img) .'" />';
    }
	
	public function _callback_daysofweek($value)
    {
        $i = 0;
        $ret = '';        
        $_d = config_item('days_of_week');        
        while($i < 7)
        {
            if(strpos($value, (string)$i) === FALSE)
            {
                $ret .= '<label class="checkbox inline">';
                $ret .= '<input type="checkbox" name="days_of_week[]" value="'.$i.'" />'.$_d[$i];
                $ret .= '</label>';
            }
            else
            {
                $ret .= '<label class="checkbox inline">';
                $ret .= '<input type="checkbox" name="days_of_week[]" value="'.$i.'" checked="checked"/>'.$_d[$i];
                $ret .= '</label>';
            }            
            $i++;
        }        
        return $ret;         
    }
    
    public function _callback_times_flown($value)
    {
        return '<span class="input uneditable-input">' . $value . '</span>';
    }
	   
	
	
	/* Fuel Price Callbacks */
	public function _dollar_crude_price($value)
    {
        return '<div class="input-append input-prepend"><span class="add-on">$</span><input type="text" class="numeric" value="'.$value.'" name="crude_price" style="width:107px"><span class="add-on">per barrel</span></div>';
    }
	
	public function _dollar_fuel_price($value)
    {
        return '<div class="input-prepend"><span class="add-on">$</span><span class="input uneditable-input" style="width:177px">' . $value . '</span></div>';
    }
	
	/* General Callbacks */
	public function _dollar_price($value)
    {
        return '<div class="input-prepend"><span class="add-on">$</span><input type="text" class="numeric" value="'.$value.'" name="price" style="width:180px"></div>';
    }
    	
	
	/* DB Callbacks */
    public function _callback_insert($post_array)
    {
		// Load required libraries
		$this->load->library('calculations');
		$this->load->model('my_model/airport_m');
		
        if(isset($post_array['days_of_week']))$post_array['days_of_week'] = implode('', $post_array['days_of_week']);
        if(isset($post_array['icao'])) $post_array['icao'] = strtoupper($post_array['icao']);
        if(isset($post_array['iata'])) $post_array['iata'] = strtoupper($post_array['iata']);
        if(isset($post_array['abbr'])) $post_array['abbr'] = strtoupper($post_array['abbr']);
        if(isset($post_array['route'])) $post_array['route'] = preg_replace("/[^0-9a-zA-Z ]/", "", strtoupper($post_array['route']));
		if(isset($post_array['notes'])) $post_array['notes'] = $this->_remove_special_characters($post_array['notes']);
		if(isset($post_array['ver'])) $post_array['ver'] = preg_replace("/[^0-9a-zA-Z]/", "", strtoupper($post_array['ver']));
		if(isset($post_array['crude_price']))
		{
			$post_array['crude_price'] = round($post_array['crude_price'], 2);
			$post_array['fuel_price'] = round(($post_array['crude_price'] / 15), 2);
		}
		
		if(isset($post_array['distance']))
		{
			$depicao = $post_array['dep_icao'];
			$arricao = $post_array['arr_icao'];
			$dep = $this->airport_m->get_by("airports.icao = '$depicao'", true);
			$arr = $this->airport_m->get_by("airports.icao = '$arricao'", true);
			$post_array['distance'] = $this->calculations->calculate_airport_distance($dep, $arr);
		}
		
		if(config_item('setting_random_price'))
		{
			if(isset($post_array['price_first']))
			{
				$this->load->model('my_model/finance_prices_m');
				$post_array['price_economy'] = $this->finance_prices_m->economy_price($post_array['distance']);
				$post_array['price_business'] = $this->finance_prices_m->business_price($post_array['distance']);
				$post_array['price_first'] = $this->finance_prices_m->first_price($post_array['distance']);
				$post_array['price_cargo'] = $this->finance_prices_m->cargo_price($post_array['distance']);	
			}
		}
		
        return $post_array;
    }
    
    public function _callback_update($post_array)
    {
		// Load required libraries
		$this->load->library('calculations');
		$this->load->model('my_model/airport_m');
		
        if(isset($post_array['days_of_week']))$post_array['days_of_week'] = implode('', $post_array['days_of_week']);
        if(isset($post_array['icao'])) $post_array['icao'] = strtoupper($post_array['icao']);
        if(isset($post_array['iata'])) $post_array['iata'] = strtoupper($post_array['iata']);
		if(isset($post_array['abbr'])) $post_array['abbr'] = strtoupper($post_array['abbr']);
        if(isset($post_array['route'])) $post_array['route'] = preg_replace("/[^0-9a-zA-Z ]/", "", strtoupper($post_array['route']));
		if(isset($post_array['notes'])) $post_array['notes'] = $this->_remove_special_characters($post_array['notes']);
		if(isset($post_array['ver'])) $post_array['ver'] = preg_replace("/[^0-9a-zA-Z]/", "", strtoupper($post_array['ver']));
		if(isset($post_array['crude_price']))
		{
			$post_array['crude_price'] = round($post_array['crude_price'], 2);
			$post_array['fuel_price'] = round(($post_array['crude_price'] / 15), 2);
		}
		if(isset($post_array['banned']))
		{
			if($post_array['banned'] == 1)
			{
				$post_array['status'] = array_search('Banned', config_item('user_status'));				
			}
			else
			{
				if($post_array['status'] == array_search('Banned', config_item('user_status')))
				{
					$post_array['status'] = array_search('Probation', config_item('user_status'));
				}
			}			
		}
		if(isset($post_array['flight_time'])) $post_array['flight_time'] =  $this->calculations->calculate_flighttime($post_array['dep_time'], $post_array['arr_time']);
		if(isset($post_array['distance']))
		{
			$depicao = $post_array['dep_icao'];
			$arricao = $post_array['arr_icao'];
			$dep = $this->airport_m->get_by("airports.icao = '$depicao'", true);
			$arr = $this->airport_m->get_by("airports.icao = '$arricao'", true);
			$post_array['distance'] = $this->calculations->calculate_airport_distance($dep, $arr);
		}
		
		if(config_item('setting_random_price'))
		{
			if(isset($post_array['price_first']))
			{
				$this->load->model('my_model/finance_prices_m');
				$post_array['price_economy'] = $this->finance_prices_m->economy_price($post_array['distance']);
				$post_array['price_business'] = $this->finance_prices_m->business_price($post_array['distance']);
				$post_array['price_first'] = $this->finance_prices_m->first_price($post_array['distance']);
				$post_array['price_cargo'] = $this->finance_prices_m->cargo_price($post_array['distance']);				
			}
		}		
        return $post_array;
    }
	
	protected function _convert_bytes_ui_to_bytes($bytes_ui)
	{
		$bytes_ui = str_replace(' ','',$bytes_ui);
		if(strstr($bytes_ui,'MB'))
			$bytes = (int)(str_replace('MB','',$bytes_ui))*1024*1024;
		elseif(strstr($bytes_ui,'KB'))
			$bytes = (int)(str_replace('KB','',$bytes_ui))*1024;
		elseif(strstr($bytes_ui,'B'))
			$bytes = (int)(str_replace('B','',$bytes_ui));
		else
			$bytes = (int)($bytes_ui);

		return $bytes;
	}
	
	protected function _remove_special_characters($string)
	{
		// Strip HTML Tags
		$clear = strip_tags($string);
		// Clean up things like &amp;
		$clear = html_entity_decode($clear);
		// Strip out any url-encoded stuff
		$clear = urldecode($clear);
		// Replace Multiple spaces with single space
		$clear = preg_replace('/ +/', ' ', $clear);
		// Trim the string of leading/trailing space
		$clear = trim($clear);		
		return $clear;
	}
    
}