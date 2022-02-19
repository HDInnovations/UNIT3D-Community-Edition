@extends('errors.layout')

@section('title')
    Error 503: Service Unavailable!
@stop

@section('container')
    <h1 class="mt-5 text-center">
        <i class="{{ config('other.font-awesome') }} fa-exclamation-circle text-warning"></i> Error 503: Service
        Unavailable!
    </h1>

    <div class="separator"></div>

    <p class="text-center">
        {{ $exception->getMessage() ?: 'Sorry, we are doing some maintenance. Please check back soon.' }}
    </p>
@stop
