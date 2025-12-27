<?php

use App\Services\API;
use App\Services\APIResponseHandler;
use Illuminate\Http\Client\Response;

if (! function_exists('api')) {
    function api($namespace = null, $service = null)
    {
        return API::instance($namespace, $service);
    }
}

if (! function_exists('apiResponseHandler')) {
    function apiResponseHandler(Response $response, $successCallback = null, $failedCallback = null)
    {
        return new APIResponseHandler($response, $successCallback, $failedCallback);
    }
}

if (! function_exists('toast')) {
    function toast($type = 'success', $message = null) {
        $key = 'toast.' . $type;

        if ($message) {
            session()->flash($key, $message);

            return;
        }

        if (! session()->has($key)) {
            return;
        }

        $options = [
            'text' => session()->get($key),
            'icon' => $type,
            'toast' => true,
            'timer' => 3000,
            'showConfirmButton' => false,
            'position' => 'top-right',
        ];

        return "Swal.fire(JSON.parse('" . json_encode($options) . "'));";
    }
}

if (! function_exists('isProductionMode')) {
    function isProductionMode()
    {
        return strtolower(config('app.mode')) == 'production';
    }
}
