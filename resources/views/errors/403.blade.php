@extends('errors.layout')

@section('title')
    Error 403: Forbidden!
@stop

@section('container')
    <h1 class="mt-5 text-center">
        <i class="{{ config('other.font-awesome') }} fa-exclamation-circle text-danger"></i> Permission Denied!
    </h1>

    <div class="separator"></div>

    <p class="text-center"> {{ $exception->getMessage() ?: 'You do not have permission to perform this action!' }}</p>
@stop
