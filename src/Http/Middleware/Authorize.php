<?php

namespace Whitecube\NovaPage\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authorize
{
    public function handle(Request $request, Closure $next): Response
    {
        // Authorization is handled by Nova's built-in middleware
        // No additional authorization needed here since the 'nova' middleware
        // already checks if the user can access Nova
        return $next($request);
    }
}
