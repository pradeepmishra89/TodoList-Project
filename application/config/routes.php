<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
//     echo "Hello";
//     header("Access-Control-Allow-Origin: *");
//     header("Access-Control-Allow-Headers: *");
//     header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
//     exit();
// }

$route['api/getall'] = 'api/RegisterController/getAllUser';
$route['api/add'] = 'api/RegisterController/insertData';
$route['api/login'] = 'api/RegisterController/login';
$route['api/update/(:any)'] = 'api/RegisterController/updateData/$1';
$route['api/fetch/(:any)'] = 'api/RegisterController/fetchDataById/$1';

//Home Controller

$route['api/login'] = 'todoApi/Home/login';
$route['api/logout/(:any)'] = 'todoApi/Home/logOut/$1';
$route['api/updatepass'] = 'todoApi/Home/updatePassword';
$route['api/logout'] = 'todoApi/Home/logout';




//User Controller
$route['api/register'] = 'todoApi/User/register';
$route['api/fetch'] = 'todoApi/User/fetchData';
$route['api/updates/(:any)'] = 'todoApi/User/update/$1';
$route['api/update/(:any)'] = 'todoApi/User/updateById/$1';
$route['api/fetch/(:any)'] = 'todoApi/User/fetchByid/$1';
$route['api/delete/(:any)'] = 'todoApi/User/delete/$1';

//Task Controller

$route['api/createtask'] = 'todoApi/Task/addTask';
$route['api/updatetask'] = 'todoApi/Task/updateTask';
$route['api/fetchtask'] = 'todoApi/Task/getMyTasks';
$route['api/dlttask'] = 'todoApi/Task/dlttask';
$route['api/fetchtaskbyid']='todoApi/Task/getTaskbyId';

$route['test'] = 'Test/testJwt';
