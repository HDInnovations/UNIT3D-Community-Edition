@extends('errors.layout')

@section('title')
    Error 502: Bad Gateway!
@stop

@section('container')
    <h1 class="mt-5 text-center">
        <i class="{{ config('other.font-awesome') }} fa-exclamation-circle text-warning"></i> Error 502: Bad Gateway!
    </h1>

    <div class="separator"></div>

    <p class="text-center">
        {{ $exception->getMessage() ?:
            'The server, while acting as a gateway or proxy, received an invalid response from the
            upstream server it accessed in attempting to fulfill the request.' }}
    </p>
@stop
