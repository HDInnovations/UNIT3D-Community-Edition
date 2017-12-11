@extends('vendor.installer.layouts.master')

@section('title', trans('installer_messages.welcome.title'))
@section('container')
    <p class="paragraph">{{ trans('installer_messages.welcome.message') }}</p>
    <div class="buttons">
        <a href="{{ route('LaravelInstaller::environment') }}" class="button">{{ trans('installer_messages.next') }}</a>
    </div>
@stop
