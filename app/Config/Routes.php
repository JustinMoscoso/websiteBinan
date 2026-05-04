<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::home_page');
$routes->get('/mayor', 'Home::mayor');
$routes->get('/barangays', 'Home::barangays');
$routes->get('/barangay', 'Home::barangay');
$routes->get('/barangaycontent/(:num)', 'Home::barangaycontent/$1');
$routes->get('/department', 'Home::department');
$routes->get('/departmentcontent/(:num)', 'Home::departmentcontent/$1');
$routes->get('/services', 'Home::services');
$routes->get('/servicescontent', 'Home::servicescontent');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');
$routes->get('/fulldisc', 'Home::fulldisc');
// $routes->get('/jobs', 'Home::jobs');
// $routes->get('/jobdetails/(:num)', 'Home::jobDetails/$1');
// $routes->get('/getalljobs', 'Home::getAllJobs');
$routes->get('/officials', 'Home::officials');
$routes->get('/cityofficials', 'Home::cityofficials');
$routes->get('/career', 'Home::career');
$routes->get('/careers', 'Home::careers');

$routes->get('admin/getOfficialDetails/(:num)', 'Admin::getOfficialDetails/$1');

$routes->get('/newsevents/(:num)', 'Home::newsevents/$1');
$routes->get('/newseventscontent/(:num)', 'Home::newseventscontent/$1');
$routes->get('/announcements/(:num)', 'Home::announcements/$1');
$routes->get('/announcementcontent/(:num)', 'Home::announcementcontent/$1');
$route['announcements/(:num)'] = 'announcements_page/index/$1';

$routes->get('/invest', 'Home::invest');
$routes->get('/investmentopp', 'Home::investmentopp');
$routes->get('/safetyseal', 'Home::safetyseal');
$routes->get('/safetysealprocess', 'Home::safetysealprocess');
$routes->get('/login', 'Auther::login');
$routes->get('/logout', 'Auther::logout');
$routes->get('/history', 'Home::history');
$routes->get('/hotlines', 'Home::hotlines');

$routes->get('/map', 'MapController::index');
$routes->get('/contact', 'ContactController::index');
$routes->post('contact/send', 'ContactController::send');

$routes->get('newsevents', 'Home::newsevents');
$routes->get('/newsevents/(:num)', 'Home::newsevents/$1');
$routes->get('/newseventscontent/(:num)', 'Home::newseventscontent/$1');
$routes->get('/announcements/(:num)', 'Home::announcements/$1');
$routes->get('announcements', 'Home::announcements');
$routes->get('/announcementcontent/(:num)', 'Home::announcementcontent/$1');

$routes->post('/process/(:any)', 'Home::process/$1');
$routes->get('/process/(:any)', 'Home::process/$1');

$routes->get('jobpostings', 'Home::jobpostings');
$routes->get('jobpostings/(:num)', 'Home::jobpostings/$1');

$routes->get('/jobs', 'Home::jobs');
$routes->get('/test-jobs', 'Home::test_jobs');

// Define a group of routes with common prefix ('/admin')
$routes->group('admin', function ($routes) {
    $routes->get('dashboard', 'Admin::mode/dashboard');
    $routes->get('accounts_mgmt', 'Admin::mode/accounts_mgmt');
    $routes->get('postcontent', 'Admin::mode/postcontent');
    $routes->get('announcements', 'Admin::mode/announcements');
    $routes->get('mayor', 'Admin::mode/mayor');
    $routes->get('brgy', 'Admin::mode/brgy');
    $routes->get('services', 'Admin::mode/services');
    $routes->get('dept', 'Admin::mode/dept');
    $routes->get('cityOff', 'Admin::mode/cityOff');
    $routes->get('fullDisc', 'Admin::mode/fullDisc');
    $routes->get('careers', 'Admin::mode/careers');
    $routes->get('invest', 'Admin::mode/invest');
    $routes->get('profile', 'Admin::mode/profile');
    $routes->get('audit', 'Admin::mode/audit');
    $routes->get('contacts', 'Admin::mode/contacts');
    $routes->get('about', 'Admin::mode/about');

    $routes->get('map', 'Admin::mode/map');
    
    $routes->get('getUserCount', 'Admin::getUserCount'); 
    $routes->get('getRecentNews', 'Admin::getRecentNews');
    $routes->get('getRecentAnns', 'Admin::getRecentAnns');

    $routes->get('getVisitCount', 'Admin::getVisitCount');

    // Define AJAX routes within the admin group
    $routes->get('ajax/(:any)', 'Admin::ajax/$1');
    $routes->post('ajax/(:any)', 'Admin::ajax/$1');

    $routes->get('image/(:segment)/(:segment)', 'Admin::image/$1/$2');
    $routes->get('preview_file/(:segment)/(:segment)', 'Admin::preview_file/$1/$2');

    $routes->get('jobs', 'Admin::mode/jobs');
    $routes->post('ajax/get_jobs', 'Admin::ajax/get_jobs');
    $routes->post('ajax/get_job', 'Admin::ajax/get_job');
    $routes->post('ajax/create_job', 'Admin::ajax/create_job');
    $routes->post('ajax/update_job', 'Admin::ajax/update_job');
    $routes->post('ajax/set_status_job', 'Admin::ajax/set_status_job');
    $routes->post('ajax/delete_job', 'Admin::ajax/delete_job');

    $routes->get('image/(:segment)/(:segment)', 'Admin::image/$1/$2');
    $routes->get('preview_file/(:segment)/(:segment)', 'Admin::preview_file/$1/$2');
});

$routes->get('auth/login', 'Auther::login');
$routes->get('auth/logout', 'Auther::logout');
$routes->post('auth/ajax/(:any)', 'Auther::ajax/$1');



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
