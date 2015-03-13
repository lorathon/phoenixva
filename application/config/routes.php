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
$route['private/hubs/create-page/(:any)'] = "hubs/create_page/$1";
$route['private/hubs/edit-page/(:any)'] = "hubs/edit_page/$1";
$route['hubs/(:any)'] = "hubs/view/$1";

// Route all event related stuff
$route['private/events/create-page/(:any)'] = "events/create_page/$1";
$route['private/events/create-event/(:any)'] = "events/create_event/$1";
$route['private/events/create-event'] = "events/create_event";
$route['private/events/edit-page/(:any)'] = "events/edit_page/$1";
$route['events/get-json'] = "events/get_json";
$route['events/(:any)'] = "events/view/$1";
$route['private/events/get-json'] = "events/get_json";
$route['admin/events'] = "admin/event_admin";
$route['admin/event-types'] = "admin/event_admin/event_types";
$route['admin/events/create-event/(:any)'] = "events/create_event/$1";
$route['admin/events/create-event'] = "events/create_event";

// Default route
$route['default_controller'] = 'pages/view';

// 404 Error routing
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */