<?php

namespace Config;

use CodeIgniter\CodeIgniter;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\VisitCounter;
use App\Filters\AuthFilter;
use App\Filters\LoginThrottle;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'visitcounter'  => VisitCounter::class,
        'auth'          => AuthFilter::class,
        'loginThrottle' => LoginThrottle::class,
    ];

    public array $globals = [
        'before' => [
            // Apply security headers on every response
            'secureheaders',
            // Count visits on all public pages (excluding admin)
            'visitcounter' => ['except' => ['admin/*']],
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        // Protect ALL admin routes — redirects browsers, returns 401 JSON for AJAX
        'auth' => [
            'before' => ['admin/*'],
        ],
        // Rate-limit the login AJAX endpoint
        'loginThrottle' => [
            'before' => ['auth/ajax/*'],
        ],
    ];
}