<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Config\Database;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Log page visits for every request except admin dashboard
        $this->logPageVisit();

        //$this->session = \Config\Services::session();

        // Preload any models, libraries, etc, here.
        // E.g.: $this->session = \Config\Services::session();
    }

    /**
     * Logs the current page visit to the visit_data table, excluding admin dashboard pages.
     *
     * @return void
     */
   protected function logPageVisit()
{
    $baseURL = "http://localhost/"; // Replace with your actual base URL
    $currentURL = $_SERVER['REQUEST_URI'];

    // Skip logging if the URL contains /admin anywhere
    if (strpos($currentURL, '/admin') !== false) {
        return;
    }

    //skip logging if the url contains/login anywhere
    if (strpos($currentURL, '/login') !== false) {
        return;
    }

    try {
        $db = Database::connect();

        $data = [
            'page_url' => $currentURL,
            'ip_address' => $this->request->getIPAddress() ?? '0.0.0.0',
            'visit_date' => date('Y-m-d H:i:s'),
        ];

        $db->table('visit_data')->insert($data);
    } catch (\Exception $e) {
        log_message('error', 'Database Error in logPageVisit: ' . $e->getMessage());
    }
}
}