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
    <li>
        <a href="{{ route('staff.articles.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.articles') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.articles.update', ['id' => $article->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ __('common.article') }} {{ __('common.edit') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ __('common.article') }} {{ __('common.edit') }}</h2>
        <form role="form" method="POST" enctype="multipart/form-data"
              action="{{ route('staff.articles.update', ['id' => $article->id]) }}">
            @csrf
            <div class="form-group">
                <label for="title">{{ __('common.title') }}</label>
                <label>
                    <input type="text" class="form-control" name="title" value="{{ $article->title }}" required>
                </label>
            </div>

            <div class="form-group">
                <label for="image">{{ __('common.image') }}</label>
                <input type="file" name="image">
            </div>

            <div class="form-group">
                <label for="content">{{ __('staff.article-content') }}</label>
                <textarea name="content" id="content" cols="30" rows="10"
                          class="form-control">{{ $article->content }}</textarea>
            </div>

            <button type="submit" class="btn btn-default">{{ __('common.save') }}</button>
        </form>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
      $(document).ready(function () {
        $('#content').wysibb({})
      })

    </script>
@endsection
