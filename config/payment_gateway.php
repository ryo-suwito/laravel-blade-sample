<?php

return [

    'host' => env('PAYMENT_GATEWAY_HOST', 'http://localhost:8081'),

    'urls' => [
        'webhook' => env('PAYMENT_GATEWAY_HOST', 'http://localhost:8081').'/yukk/notification',
    ],
    
    'documents' => [
        'postman' => env('PAYMENT_GATEWAY_DOCUMENT_POSTMAN', 'https://documenter.getpostman.com/view/23209878/2sAYBYgqNf'),
    ],
];
