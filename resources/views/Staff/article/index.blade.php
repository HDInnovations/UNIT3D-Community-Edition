@extends('layout.default')

@section('title')
    <title>Articles - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff_article_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Articles</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Articles</h2>
        <a href="{{ route('staff_article_add_form') }}" class="btn btn-primary">Add A Article</a>
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
            @foreach($articles as $article)
                <tr>
                    <td>
                        <a href="{{ route('staff_article_edit_form', ['slug' => $article->slug, 'id' => $article->id]) }}">{{ $article->title }}</a>
                    </td>
                    <td>
                        <a href="{{ route('profile', ['username' => $article->user->username, 'id' => $article->user->id]) }}">{{ $article->user->username }}</a>
                    </td>
                    <th>{{ date('d/m/Y', strtotime($article->created_at)) }}</th>
                    <td>
                        <a href="{{ route('staff_article_edit_form', ['slug' => $article->slug, 'id' => $article->id]) }}"
                           class="btn btn-warning">Edit</a>
                        <a href="{{ route('staff_article_delete', ['slug' => $article->slug, 'id' => $article->id]) }}"
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
