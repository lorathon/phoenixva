<?php

$config['site_name'] = 'Phoenix Virtual Airways';

$config['site_description'] = 'Phoenix Virtual Airways is the largest virtual airline.';

$config['webmaster_email']  = 'helpdesk@phoenixva.org';

/* mi = miles | km = kilometers */
$config['units_distance'] = 'km';

/* lb = pounds | kg = kilograms */
$config['units_fuel'] = 'lb';

/* lb = pounds | kg = kilograms */
$config['units_weight'] = 'kg';

/* ft = feet | m = meter */
$config['units_altitude'] = 'ft';



/* Create random pricing based on a
 * min and max price range.  Price is based on
 * distance of the schedule.
 * TRUE = Will calculate random price on pirep submission and schedule change
 * FALSE = Manual entry of price will need to be done
*/
$config['setting_random_price'] = TRUE;


/* Image Folders */
$config['img_folder_aircraft'] = 'images/aircraft';    
$config['img_folder_rank'] = 'images/rank';
$config['img_folder_icons'] = 'images/icons';
$config['img_folder_airline'] = 'images/airline';
$config['img_folder_regional'] = 'images/regional';
$config['img_folder_avatar'] = 'images/avatar';
$config['img_folder_award'] = 'images/award';

$config['img_folders'] = array (
    'Aircraft'  => './images/aircraft/',
    'Airline'   => './images/airline/',
    'Avatar'    => './images/avatar/',
    'Award'     => './images/award/',
    'Badges'    => './images/badge/',
    'Rank'      => './images/rank/', 
);

$config['user_status'] = array (
		'0' => 'Waiting Activation',
    	'1' => 'New Registration',          // Activated, no PIREPs
    	'2' => 'Probation',                 // After 1st PIREP until accepted or anytime a pilot is warned.
    	'3' => 'Active',
    	'4' => 'Leave of Absence',
    	'5' => 'Retired',
    	'6' => 'Rejected',
    	'7' => 'Banned',
);

$config['pirep_status'] = array (
    '0' => 'Unapproved',
    '1' => 'Approved',
    '2' => 'Rejected',
    '3' => 'Holding',
);

$config['aircraft_cat'] = array(
    '1' => 'A',
    '2' => 'B',
    '3' => 'C',
    '4' => 'D',
    '5' => 'E',
    '6' => 'F',
    '7' => 'G'
);

$config['days_of_week'] = array(
    '0' => 'SUN',
    '1' => 'MON',
    '2' => 'TUE',
    '3' => 'WED',
    '4' => 'THU',
    '5' => 'FRI',
    '6' => 'SAT'
);

$config['flight_type'] = array(
    'P' => 'Passenger',
    'C' => 'Cargo'
);

$config['countries'] = array(    
    "US" => "United States",
    "GB" => "United Kingdom",
    "AF" => "Afghanistan",
    "AL" => "Albania",
    "DZ" => "Algeria",
    "AS" => "American Samoa",
    "AD" => "Andorra",
    "AO" => "Angola",
    "AI" => "Anguilla",
    "AQ" => "Antarctica",
    "AG" => "Antigua And Barbuda",
    "AR" => "Argentina",
    "AM" => "Armenia",
    "AW" => "Aruba",
    "AU" => "Australia",
    "AT" => "Austria",
    "AZ" => "Azerbaijan",
    "BS" => "Bahamas",
    "BH" => "Bahrain",
    "BD" => "Bangladesh",
    "BB" => "Barbados",
    "BY" => "Belarus",
    "BE" => "Belgium",
    "BZ" => "Belize",
    "BJ" => "Benin",
    "BM" => "Bermuda",
    "BT" => "Bhutan",
    "BO" => "Bolivia",
    "BA" => "Bosnia And Herzegowina",
    "BW" => "Botswana",
    "BV" => "Bouvet Island",
    "BR" => "Brazil",
    "IO" => "British Indian Ocean Territory",
    "BN" => "Brunei Darussalam",
    "BG" => "Bulgaria",
    "BF" => "Burkina Faso",
    "BI" => "Burundi",
    "KH" => "Cambodia",
    "CM" => "Cameroon",
    "CA" => "Canada",
    "CV" => "Cape Verde",
    "KY" => "Cayman Islands",
    "CF" => "Central African Republic",
    "TD" => "Chad",
    "CL" => "Chile",
    "CN" => "China",
    "CX" => "Christmas Island",
    "CC" => "Cocos (Keeling) Islands",
    "CO" => "Colombia",
    "KM" => "Comoros",
    "CG" => "Congo",
    "CD" => "Congo, The Democratic Republic Of The",
    "CK" => "Cook Islands",
    "CR" => "Costa Rica",
    "CI" => "Cote D'Ivoire",
    "HR" => "Croatia (Local Name: Hrvatska)",
    "CU" => "Cuba",
    "CY" => "Cyprus",
    "CZ" => "Czech Republic",
    "DK" => "Denmark",
    "DJ" => "Djibouti",
    "DM" => "Dominica",
    "DO" => "Dominican Republic",
    "TP" => "East Timor",
    "EC" => "Ecuador",
    "EG" => "Egypt",
    "SV" => "El Salvador",
    "GQ" => "Equatorial Guinea",
    "ER" => "Eritrea",
    "EE" => "Estonia",
    "ET" => "Ethiopia",
    "FK" => "Falkland Islands (Malvinas)",
    "FO" => "Faroe Islands",
    "FJ" => "Fiji",
    "FI" => "Finland",
    "FR" => "France",
    "FX" => "France, Metropolitan",
    "GF" => "French Guiana",
    "PF" => "French Polynesia",
    "TF" => "French Southern Territories",
    "GA" => "Gabon",
    "GM" => "Gambia",
    "GE" => "Georgia",
    "DE" => "Germany",
    "GH" => "Ghana",
    "GI" => "Gibraltar",
    "GR" => "Greece",
    "GL" => "Greenland",
    "GD" => "Grenada",
    "GP" => "Guadeloupe",
    "GU" => "Guam",
    "GT" => "Guatemala",
    "GN" => "Guinea",
    "GW" => "Guinea-Bissau",
    "GY" => "Guyana",
    "HT" => "Haiti",
    "HM" => "Heard And Mc Donald Islands",
    "VA" => "Holy See (Vatican City State)",
    "HN" => "Honduras",
    "HK" => "Hong Kong",
    "HU" => "Hungary",
    "IS" => "Iceland",
    "IN" => "India",
    "ID" => "Indonesia",
    "IR" => "Iran (Islamic Republic Of)",
    "IQ" => "Iraq",
    "IE" => "Ireland",
    "IL" => "Israel",
    "IT" => "Italy",
    "JM" => "Jamaica",
    "JP" => "Japan",
    "JO" => "Jordan",
    "KZ" => "Kazakhstan",
    "KE" => "Kenya",
    "KI" => "Kiribati",
    "KP" => "Korea, Democratic People's Republic Of",
    "KR" => "Korea, Republic Of",
    "KW" => "Kuwait",
    "KG" => "Kyrgyzstan",
    "LA" => "Lao People's Democratic Republic",
    "LV" => "Latvia",
    "LB" => "Lebanon",
    "LS" => "Lesotho",
    "LR" => "Liberia",
    "LY" => "Libyan Arab Jamahiriya",
    "LI" => "Liechtenstein",
    "LT" => "Lithuania",
    "LU" => "Luxembourg",
    "MO" => "Macau",
    "MK" => "Macedonia, Former Yugoslav Republic Of",
    "MG" => "Madagascar",
    "MW" => "Malawi",
    "MY" => "Malaysia",
    "MV" => "Maldives",
    "ML" => "Mali",
    "MT" => "Malta",
    "MH" => "Marshall Islands",
    "MQ" => "Martinique",
    "MR" => "Mauritania",
    "MU" => "Mauritius",
    "YT" => "Mayotte",
    "MX" => "Mexico",
    "FM" => "Micronesia, Federated States Of",
    "MD" => "Moldova, Republic Of",
    "MC" => "Monaco",
    "MN" => "Mongolia",
    "MS" => "Montserrat",
    "MA" => "Morocco",
    "MZ" => "Mozambique",
    "MM" => "Myanmar",
    "NA" => "Namibia",
    "NR" => "Nauru",
    "NP" => "Nepal",
    "NL" => "Netherlands",
    "AN" => "Netherlands Antilles",
    "NC" => "New Caledonia",
    "NZ" => "New Zealand",
    "NI" => "Nicaragua",
    "NE" => "Niger",
    "NG" => "Nigeria",
    "NU" => "Niue",
    "NF" => "Norfolk Island",
    "MP" => "Northern Mariana Islands",
    "NO" => "Norway",
    "OM" => "Oman",
    "PK" => "Pakistan",
    "PW" => "Palau",
    "PA" => "Panama",
    "PG" => "Papua New Guinea",
    "PY" => "Paraguay",
    "PE" => "Peru",
    "PH" => "Philippines",
    "PN" => "Pitcairn",
    "PL" => "Poland",
    "PT" => "Portugal",
    "PR" => "Puerto Rico",
    "QA" => "Qatar",
    "RE" => "Reunion",
    "RO" => "Romania",
    "RU" => "Russian Federation",
    "RW" => "Rwanda",
    "KN" => "Saint Kitts And Nevis",
    "LC" => "Saint Lucia",
    "VC" => "Saint Vincent And The Grenadines",
    "WS" => "Samoa",
    "SM" => "San Marino",
    "ST" => "Sao Tome And Principe",
    "SA" => "Saudi Arabia",
    "SN" => "Senegal",
    "SC" => "Seychelles",
    "SL" => "Sierra Leone",
    "SG" => "Singapore",
    "SK" => "Slovakia (Slovak Republic)",
    "SI" => "Slovenia",
    "SB" => "Solomon Islands",
    "SO" => "Somalia",
    "ZA" => "South Africa",
    "GS" => "South Georgia, South Sandwich Islands",
    "ES" => "Spain",
    "LK" => "Sri Lanka",
    "SH" => "St. Helena",
    "PM" => "St. Pierre And Miquelon",
    "SD" => "Sudan",
    "SR" => "Suriname",
    "SJ" => "Svalbard And Jan Mayen Islands",
    "SZ" => "Swaziland",
    "SE" => "Sweden",
    "CH" => "Switzerland",
    "SY" => "Syrian Arab Republic",
    "TW" => "Taiwan",
    "TJ" => "Tajikistan",
    "TZ" => "Tanzania, United Republic Of",
    "TH" => "Thailand",
    "TG" => "Togo",
    "TK" => "Tokelau",
    "TO" => "Tonga",
    "TT" => "Trinidad And Tobago",
    "TN" => "Tunisia",
    "TR" => "Turkey",
    "TM" => "Turkmenistan",
    "TC" => "Turks And Caicos Islands",
    "TV" => "Tuvalu",
    "UG" => "Uganda",
    "UA" => "Ukraine",
    "AE" => "United Arab Emirates",
    "UM" => "United States Minor Outlying Islands",
    "UY" => "Uruguay",
    "UZ" => "Uzbekistan",
    "VU" => "Vanuatu",
    "VE" => "Venezuela",
    "VN" => "Viet Nam",
    "VG" => "Virgin Islands (British)",
    "VI" => "Virgin Islands (U.S.)",
    "WF" => "Wallis And Futuna Islands",
    "EH" => "Western Sahara",
    "YE" => "Yemen",
    "YU" => "Yugoslavia",
    "ZM" => "Zambia",
    "ZW" => "Zimbabwe"
);

$config['fuel_discount'] = array(
    "1.0" => "0%",
    ".99" => "1%",
    ".98" => "2%",
    ".97" => "3%",
    ".96" => "4%",
    ".95" => "5%",
    ".94" => "6%",
    ".93" => "7%",
    ".92" => "8%",
    ".91" => "9%",
    ".90" => "10%",
    ".89" => "11%",
    ".88" => "12%",
    ".87" => "13%",
    ".86" => "14%",
    ".85" => "15%",
    ".84" => "16%",
    ".83" => "17%",
    ".82" => "18%",
    ".81" => "19%",
    ".80" => "20%",
    ".79" => "21%",
    ".78" => "22%",
    ".77" => "23%",
    ".76" => "24%",
    ".75" => "25%",    
);

$config['pireps_fees_types'] = array(
    "0"     => "Per Flight",
    "1"     => "Fuel Used",
    "2"     => "Per Aircraft Weight Unit",
    "3"     => "Per Passenger",
    "4"     => "Per Cargo Unit",
    "5"     => "Per Ticket Price",
);

$config['units'] = array(
    "mi"    => "mile",
    "km"    => "kilometer",
    "ft"    => "feet",
    "m"     => "meter",
    "lb"    => "pound",
    "kg"    => "kilogram",
);
