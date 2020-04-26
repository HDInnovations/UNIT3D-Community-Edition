<?php

namespace App\Providers;

use App\Helpers\Bencode;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register the application's response macros.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('torrentClient', function ($message) {
            return Response::make(Bencode::bencode($message))->withHeaders(['Content-Type' => 'text/plain']);
        });
    }
}
