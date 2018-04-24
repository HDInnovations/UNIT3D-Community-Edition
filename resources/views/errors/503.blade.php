@extends('errors.layout')

@section('title')
    Error 503: Service Unavailable!
@stop

@section('container')
    <h1 class="mt-5 text-center">
        <i class="fa fa-exclamation-circle text-warning"></i> Error 503: Service Unavailable!
    </h1>

    <div class="separator"></div>

    <p class="text-center">
        The server is currently unavailable (because it is overloaded or down for
        maintenance). Generally, this is a temporary state.
    </p>
@stop