<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionsMiddleware
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if ($request->user()->hasPermissionTo($permission)) {
            return $next($request);
        }
        abort(403);
    }
}
