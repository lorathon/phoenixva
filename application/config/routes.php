<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
// Admin routing
$route['admin'] = "admin/dashboard";

// Remove controller action for pages calls
$route['pages/(:any)'] = "pages/view/$1";

// Route all hub related stuff
$route['hubs/transfer/(:any)'] = "hubs/transfer/$1";
$route['hubs/transfer_approve/(:any)'] = "hubs/transfer_approve/$1";
$route['hubs/transfer_reject/(:any)'] = "hubs/transfer_reject/$1";
$route['hubs/(:any)'] = "hubs/view/$1";

// Route all event related stuff
$route['private/event-types'] = "events/event_types";
$route['events/(:any)'] = "events/view/$1";
$route['private/events/create-page/(:any)'] = "events/create_page/$1";
$route['private/events/edit-page/(:any)'] = "events/edit_page/$1";
$route['private/events/create-event/(:any)'] = "events/create_event/$1";
$route['private/events/create-event'] = "events/create_event";
$route['private/events/create-type/(:any)'] = "events/create_event_type/$1";
$route['private/events/create-type'] = "events/create_event_type";
$route['private/events/delete-event/(:any)'] = "events/delete_event/$1";
$route['private/events/delete-type/(:any)'] = "events/delete_event_type/$1";
$route['private/events/add-user/(:any)'] = "events/add_user/$1";
$route['private/events/create-award/(:any)'] = "events/create_award/$1";
$route['private/events/delete-award/(:any)'] = "events/delete_award/$1";
$route['events/get-json'] = "events/get_json";
$route['private/events/get-json'] = "events/get_json";

// Route all award related stuff
$route['awards/view/(:any)'] = "awards/view/$1";
$route['awards/(:any)'] = "awards/index/$1";
$route['private/awards/create-award/(:any)'] = "awards/create_award/$1";
$route['private/awards/create-award'] = "awards/create_award";
$route['private/awards/create-type/(:any)'] = "awards/create_award_type/$1";
$route['private/awards/create-type'] = "awards/create_award_type";
$route['private/awards/delete-award/(:any)'] = "awards/delete_award/$1";
$route['private/awards/delete-type/(:any)'] = "awards/delete_award_type/$1";

// Route all rank related stuff
$route['ranks/(:any)'] = "ranks/view/$1";
$route['private/ranks/create-rank/(:any)'] = "ranks/create_rank/$1";
$route['private/ranks/create-rank'] = "ranks/create_rank";
$route['private/ranks/delete-rank/(:any)'] = "ranks/delete_rank/$1";

// Route all fleet related stuff
$route['fleet/view/(:any)'] = "fleet/view/$1";
$route['fleet/view-sub/(:any)'] = "fleet/view_sub/$1";
$route['fleet/(:any)'] = "fleet/index/$1";

// Route all airlines related stuff
$route['airlines/view/(:any)'] = "airlines/view/$1";
$route['airlines/autocomplete'] = "airlines/autocomplete";
$route['airlines/(:any)'] = "airlines/index/$1";
$route['private/airlines/edit-airline/(:any)'] = "airlines/edit_airline/$1";
$route['private/airlines/edit-airline'] = "airlines/edit_airline";
$route['private/airlines/edit-aircraft/(:any)'] = "airlines/edit_aircraft/$1";
$route['private/airlines/edit-aircraft'] = "airlines/edit_aircraft";

// Route all airports related stuff
$route['airports/view/(:any)'] = "airports/view/$1";
$route['airports/autocomplete'] = "airports/autocomplete";
$route['airports/(:any)'] = "airports/index/$1";
$route['private/airports/edit-airport/(:any)'] = "airports/edit_airport/$1";
$route['private/airports/edit-airport'] = "airports/edit_airport";

// Route all schedules/bids related stuff
$route['private/schedules'] = "schedules";
$route['private/schedules/search-schedules'] = "schedules/search_schedules";
$route['private/bids/(:any)'] = "schedules/bids/$1";
$route['private/bids'] = "schedules/bids";
$route['private/create-bid/(:any)'] = "schedules/create_bid/$1";
$route['private/delete-bid/(:any)'] = "schedules/delete_bid/$1";

// Default route
$route['default_controller'] = 'pages/view';

// 404 Error routing
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */
