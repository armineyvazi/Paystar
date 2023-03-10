<?php

    return[
        'method' => env('PAYMENT_SERVICE'),
        'routeCallback' => 'http://127.0.0.1:8000/checkout/token',
    ];
