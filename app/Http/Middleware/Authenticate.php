<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use App\Utils\HttpResponse;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    use HttpResponse;
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson()
            ? null
            : env('APP_URL_FRONTEND').'/login';
    }
}
