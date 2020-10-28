<?php


namespace App\Http\Middleware;


use App\Models\RBACPermissions;
use Closure;
use Illuminate\Http\Request;

class PermissionsMiddleware
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $perm = RBACPermissions::where('slug', $permission)->first();
        if($request->user()->hasPermissionTo($perm)) {
            return $next($request);
        }
        abort(403);

    }
}