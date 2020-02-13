@extends('layout.default')

@section('title')
    <title>Articles - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.articles.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.articles')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.articles.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.add') @lang('staff.articles')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>@lang('common.add') @lang('staff.articles')</h2>
        <form role="form" method="POST" action="{{ route('staff.articles.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">@lang('common.title')</label>
                <label>
                    <input type="text" class="form-control" name="title" required>
                </label>
            </div>
    
            <div class="form-group">
                <label for="image">@lang('common.image')</label>
                <input type="file" name="image">
            </div>
    
            <div class="form-group">
                <label for="content">@lang('staff.article-content')</label>
                <textarea name="content" id="content" cols="30" rows="10" class="form-control"></textarea>
            </div>
    
            <button type="submit" class="btn btn-default">@lang('common.submit')</button>
        </form>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
        $(document).ready(function() {
            $('#content').wysibb({});
            emoji.textcomplete()
        })
    
    </script>
@endsection
