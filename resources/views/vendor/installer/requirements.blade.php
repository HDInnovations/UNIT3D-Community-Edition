@extends('vendor.installer.layouts.master')

@section('title', trans('installer_messages.requirements.title'))
@section('container')
    @foreach($requirements['requirements'] as $type => $requirement)
        <h5>{{ ucfirst($type) }}</h5>
        <ul class="list">
            @foreach($requirements['requirements'][$type] as $extention => $enabled)
                <li class="list__item {{ $enabled ? 'success' : 'error' }}">{{ $extention }}</li>
            @endforeach
        </ul>
    @endforeach

    @if ( ! isset($requirements['errors']))
        <div class="buttons">
            <a class="button" href="{{ route('LaravelInstaller::permissions') }}">
                {{ trans('installer_messages.next') }}
            </a>
        </div>
    @endif
@stop
