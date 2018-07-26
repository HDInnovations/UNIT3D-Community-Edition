@extends('errors.layout')

@section('title')
    Error 404: Page Not Found
@stop

@section('container')
    <h1 class="mt-5 text-center">
        <i class="{{ config('other.font-awesome') }} fa-question-circle text-warning"></i> Error 404: Page Not Found
    </h1>

    <div class="separator"></div>

    <p class="text-center">
        The Requested Page Cannot Be Found! <br>Not Sure What Your Looking For But Check The
        Address And Try Again!
    </p>
@stop