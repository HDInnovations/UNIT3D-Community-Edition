<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use \Toastr;

class LockAccount
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('locked')) {

            return Redirect::route('lock')->with(Toastr::info('Your Account Is Locked', 'Info', ['options']));

        }

        return $next($request);

    }

}
