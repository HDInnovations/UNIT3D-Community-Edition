@extends('errors.layout')

@section('title')
    Error 400: Bad Request!
@stop

@section('container')
    <h1 class="mt-5 text-center">
        <i class="{{ config('other.font-awesome') }} fa-exclamation-circle text-warning"></i> Error 400: Bad Request!
    </h1>

    <div class="separator"></div>

    <p class="text-center">
    {{ $exception->getMessage() ?:
        'The request could not be understood by the server due to malformed syntax.
            <br> The client SHOULD NOT repeat the request without modifications.
        </p>' }}
@stop
