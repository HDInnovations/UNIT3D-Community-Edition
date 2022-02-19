@extends('errors.layout')

@section('title')
    Error 500: Internal Server Error
@stop

@section('container')
    <h1 class="mt-5 text-center">
        <i class="{{ config('other.font-awesome') }} fa-cog fa-spin text-danger"></i> Error 500: Internal Server Error!
    </h1>

    <div class="separator"></div>

    <p class="text-center">Our server encountered an internal error. <br>Sorry For The Inconvenience</p>
@stop
