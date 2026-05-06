<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\VisitCountModel;

class VisitCounter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $ipAddress = $request->getIPAddress(); // Get the visitor's IP address
        $visitModel = new VisitCountModel();
        $visitModel->incrementVisitCount($ipAddress);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after response
    }
}