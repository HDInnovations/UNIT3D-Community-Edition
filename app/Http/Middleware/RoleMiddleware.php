<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if(!$request->user()->hasRole($role)) {

            abort(404);

        }
        return $next($request);

    }
}

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission)
    {
        $perm = Permission::where('slug', $permission)->first();
        if($request->user()->hasPermissionTo($perm)) {
            return $next($request);
        }
        abort(404);


    }
}
