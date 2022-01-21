@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.types.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.torrent-types') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ __('common.types') }}</h2>
        <a href="{{ route('staff.types.create') }}" class="btn btn-primary">
            {{ __('common.add') }}
            {{ trans_choice('common.a-an-art',false) }}
            {{ __('common.type') }}
        </a>

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
                @foreach ($types as $type)
                    <tr>
                        <td>
                            {{ $type->position }}
                        </td>
                        <td>
                            <a href="{{ route('staff.types.edit', ['id' => $type->id]) }}">
                                {{ $type->name }}
                            </a>
                        </td>
                        <td>
                            <form action="{{ route('staff.types.destroy', ['id' => $type->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('staff.types.edit', ['id' => $type->id]) }}"
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
