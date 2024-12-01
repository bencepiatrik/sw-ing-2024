<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected function tokensMatch($request)
    {
        $tokenFromRequest = $this->getTokenFromRequest($request);
        $tokenFromSession = $request->session()->token();

        \Log::info('CSRF Token from Request: ' . $tokenFromRequest);
        \Log::info('CSRF Token from Session: ' . $tokenFromSession);

        return parent::tokensMatch($request);
    }


    protected $except = [
    //    'register',
    //    'login'
    ];
}
