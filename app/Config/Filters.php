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

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'visitcounter'  => VisitCounter::class,
    ];

    public array $globals = [
        'before' => [
            'visitcounter' => ['except' => ['admin/*']],
        ],
        'after' => [
     //       'toolbar',
        ],
    ];

    public array $methods = [];

    public array $filters = [];
}