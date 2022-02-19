@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.resolutions.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.torrent-resolutions') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ __('common.resolutions') }}</h2>
        <a href="{{ route('staff.resolutions.create') }}" class="btn btn-primary">Add A Torrent Resolution</a>

        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>{{ __('common.position') }}</th>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($resolutions as $resolution)
                    <tr>
                        <td>
                            {{ $resolution->position }}
                        </td>
                        <td>
                            <a href="{{ route('staff.resolutions.edit', ['id' => $resolution->id]) }}">
                                {{ $resolution->name }}
                            </a>
                        </td>
                        <td>
                            <form action="{{ route('staff.resolutions.destroy', ['id' => $resolution->id]) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('staff.resolutions.edit', ['id' => $resolution->id]) }}"
                                   class="btn btn-warning">{{ __('common.edit') }}</a>
                                <button type="submit" class="btn btn-danger">{{ __('common.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
