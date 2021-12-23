@extends('errors.layout')

@section('title')
    Error 444
@stop

@section('container')
    <h1 class="mt-5 text-center">
        <i class="{{ config('other.font-awesome') }} fa-exclamation-circle text-warning"></i> Error 444
    </h1>

    <div class="separator"></div>

    <p class="text-center">{{ $exception->getMessage() ?: 'CONNECTION CLOSED WITHOUT RESPONSE.' }}</p>
@stop
