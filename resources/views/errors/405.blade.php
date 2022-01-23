@extends('errors.layout')

@section('title')
    Error 405: Method Not Allowed!
@stop

@section('container')
    <h1 class="mt-5 text-center">
        <i class="{{ config('other.font-awesome') }} fa-exclamation-circle text-warning"></i> Error 405: Method Not
        Allowed!
    </h1>

    <div class="separator"></div>

    <p class="text-center">
        {{ $exception->getMessage() ?: 'The method you are trying is use, is not allowed by this server.' }}</p>
@stop
