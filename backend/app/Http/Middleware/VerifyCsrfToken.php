<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */

    public function handle($request, Closure $next)
    {
        if (
            $this->isReading($request) ||
            $this->runningUnitTests() ||
            $this->inExceptArray($request) ||
            $this->tokensMatch($request)
        ) {
            return tap($next($request), function ($response) use ($request) {
                if ($this->shouldAddXsrfTokenCookie()) {
                    $this->addCookieToResponse($request, $response);
                };
            });
        }

        throw new TokenMismatchException('CSRF Token mismatch');
    }

    protected function tokensMatch($request)
    {
        $tokenFromRequest = $this->getTokenFromRequest($request);
        $tokenFromSession = $request->session()->token();

        //Debug Logging
        \Log::info('CSRF Token from Request: ' . $tokenFromRequest);
        \Log::info('CSRF Token from Session: ' . $tokenFromSession);

        return parent::tokensMatch($request);
    }


    protected $except = [
        'api/news',
    ];
}
