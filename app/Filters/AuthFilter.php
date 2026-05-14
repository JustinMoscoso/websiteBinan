<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter
 *
 * Guards all admin/* routes.
 * - Browser requests: redirects to login page
 * - AJAX requests: returns a 401 JSON response
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('user')) {
            // AJAX / JSON request → return 401
            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(401)
                    ->setContentType('application/json')
                    ->setBody(json_encode([
                        'status'  => 0,
                        'message' => 'Unauthorized. Please log in.',
                    ]));
            }

            // Regular browser request → redirect to login
            return redirect()->to(base_url('auth/login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after response
    }
}
