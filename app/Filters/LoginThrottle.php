<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * LoginThrottle
 *
 * Rate-limits login attempts to 5 per minute per IP address.
 * Applied to auth/ajax/* routes.
 */
class LoginThrottle implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = service('throttler');

        // Allow 5 attempts per minute per IP
        if (! $throttler->check(md5($request->getIPAddress()), 5, MINUTE)) {
            return service('response')
                ->setStatusCode(429)
                ->setContentType('application/json')
                ->setBody(json_encode([
                    'status'  => 0,
                    'message' => 'Too many login attempts. Please wait a moment and try again.',
                ]));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after response
    }
}
