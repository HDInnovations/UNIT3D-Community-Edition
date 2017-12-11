<?php

namespace App\Http\Middleware;

use Closure;
use Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckIfOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if(Auth::check()) {
        $expiresAt = Carbon::now()->addMinutes(60);
          Cache::put('user-is-online-' . Auth::user()->id, true, $expiresAt);
        }
        return $next($request);
    }
}
