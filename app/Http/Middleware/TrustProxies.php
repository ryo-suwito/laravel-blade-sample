<?php

namespace App\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO | Request::HEADER_X_FORWARDED_AWS_ELB;

    public function __construct(Repository $config)
    {
        parent::__construct($config);

        $this->fetchIps();
    }

    protected function fetchIps()
    {
        try {
            $this->proxies = Cache::rememberForever('trust-proxies', function () {
                return collect(Storage::files('/proxies'))
                    ->flatMap(function ($path) {
                        return json_decode(Storage::get($path), true);
                    })
                    ->toArray();
            });
        } catch (Throwable $e) {
            report($e);
        }
    }
}
