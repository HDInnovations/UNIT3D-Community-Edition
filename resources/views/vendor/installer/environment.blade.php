@extends('vendor.installer.layouts.master')

@section('title', trans('installer_messages.environment.title'))
@section('container')
    @if (session('message'))
        <p class="alert">{{ session('message') }}</p>
    @endif
    <form method="post" action="{{ route('LaravelInstaller::environmentSave') }}">
        {!! csrf_field() !!}
        <textarea class="textarea" name="envConfig">{{ $envConfig }}</textarea>
        <div class="buttons buttons--right">
             <button class="button button--light" type="submit">{{ trans('installer_messages.environment.save') }}</button>
        </div>
    </form>
    @if( ! isset($environment['errors']))
        <div class="buttons">
            <a class="button" href="{{ route('LaravelInstaller::requirements') }}">
                {{ trans('installer_messages.next') }}
            </a>
        </div>
    @endif
@stop
