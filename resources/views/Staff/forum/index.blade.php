@extends('layout.default')

@section('title')
    <title>Forums - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Forums - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.forums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Forums</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Forums</h2>
        <a href="{{ route('staff.forums.create') }}" class="btn btn-primary">Add New Category/Forum</a>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>{{ __('common.name') }}</th>
                    <th>Type</th>
                    <th>{{ __('common.position') }}</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($categories as $category)
                    <tr class="success">
                        <td>
                            <a href="{{ route('staff.forums.edit', ['id' => $category->id]) }}">{{ $category->name }}</a>
                        </td>
                        <td>
                            Category
                        </td>
                        <td>
                            {{ $category->position }}
                        </td>
                        <td>
                            <form action="{{ route('staff.forums.destroy', ['id' => $category->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ __('common.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @foreach ($category->getForumsInCategory()->sortBy('position') as $forum)
                        <tr>
                            <td>
                                <a href="{{ route('staff.forums.edit', ['id' => $forum->id]) }}">---- {{ $forum->name }}</a>
                            </td>
                            <td>
                                Forum
                            </td>
                            <td>
                                {{ $forum->position }}
                            </td>
                            <td>
                                <form action="{{ route('staff.forums.destroy', ['id' => $forum->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">{{ __('common.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
