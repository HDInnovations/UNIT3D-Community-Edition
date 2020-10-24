<?php

namespace App\Http\Middleware;

use App\Models\RBACPermissions;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if(!$request->user()->hasRole($role)) {

            abort(403);

        }
        return $next($request);

    }
}


