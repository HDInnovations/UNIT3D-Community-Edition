@extends('layout.default')

@section('title')
    <title>Articles - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.articles.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Articles</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Articles</h2>
        <a href="{{ route('staff.articles.create') }}" class="btn btn-primary">Add A Article</a>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($articles as $article)
                <tr>
                    <td>
                        <a href="{{ route('staff.articles.edit', ['id' => $article->id]) }}">{{ $article->title }}</a>
                    </td>
                    <td>
                        <a href="{{ route('users.show', ['username' => $article->user->username]) }}">{{ $article->user->username }}</a>
                    </td>
                    <td>{{ $article->created_at->toDayDateTimeString() }}</td>
                    <td>
                        <a href="{{ route('staff.articles.edit', ['id' => $article->id]) }}"
                           class="btn btn-warning">Edit</a>
                        <a href="{{ route('staff.articles.destroy', ['id' => $article->id]) }}"
                           class="btn btn-danger">Delete</a>
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
