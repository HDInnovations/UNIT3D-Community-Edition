@extends('layout.default')

@section('title')
    <title>Articles - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.articles.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.articles') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ __('staff.articles') }}</h2>
        <a href="{{ route('staff.articles.create') }}" class="btn btn-primary">
            {{ __('common.add') }} {{ __('staff.articles') }}
        </a>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($articles as $article)
                    <tr>
                        <td>
                            <a href="{{ route('staff.articles.edit', ['id' => $article->id]) }}">{{ $article->title }}</a>
                        </td>
                        <td>
                            <a
                                    href="{{ route('users.show', ['username' => $article->user->username]) }}">{{ $article->user->username }}</a>
                        </td>
                        <td>{{ $article->created_at->toDayDateTimeString() }}</td>
                        <td>
                            <form action="{{ route('staff.articles.destroy', ['id' => $article->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('staff.articles.edit', ['id' => $article->id]) }}"
                                   class="btn btn-warning">{{ __('common.edit') }}</a>
                                <button type="submit" class="btn btn-danger">{{ __('common.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
