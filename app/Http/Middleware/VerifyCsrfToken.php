<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/checkout/payment/notify',
        '/user/payment/notify',
        '/paytm-callback',
        '/user/paytm/notify',
        '/razorpay-callback',
        '/user/razorpay/notify',
        '/gv/callback'
    ];
}
