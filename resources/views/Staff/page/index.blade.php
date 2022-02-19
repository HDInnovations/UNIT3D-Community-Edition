@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.pages.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.pages') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ __('staff.pages') }}</h2>
        <a href="{{ route('staff.pages.create') }}" class="btn btn-primary">
            {{ __('common.add') }}
            {{ trans_choice('common.a-an-art',false) }}
            {{ __('common.new-adj') }}
            {{ __('staff.page') }}
        </a>

        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>{{ __('common.title') }}</th>
                    <th>{{ __('common.date') }}</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($pages as $page)
                    <tr>
                        <td>
                            <a href="{{ route('pages.show', ['id' => $page->id]) }}">
                                {{ $page->name }}
                            </a>
                        </td>
                        <td>
                            {{ $page->created_at }} ({{ $page->created_at->diffForHumans() }})
                        </td>
                        <td>
                            <form action="{{ route('staff.pages.destroy', ['id' => $page->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('staff.pages.edit', ['id' => $page->id]) }}"
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
