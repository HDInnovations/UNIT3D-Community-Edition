@extends('layout.default')

@section('title')
    <title>@lang('forum.forums') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ config('other.title') }} - @lang('forum.forums')">
@endsection


@section('breadcrumb')
    <li class="active">
        <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('forum.forums')</span>
        </a>
    </li>
@endsection


@section('content')
    <div class="box container">
        @include('forum.buttons')
        <form role="form" method="GET" action="{{ route('forum_search_form') }}">
            <input type="hidden" name="sorting" value="created_at">
            <input type="hidden" name="direction" value="2">
            <input type="text" name="name" id="name" value="{{ (isset($params) && is_array($params) && array_key_exists('name',$params) ? $params['name'] : '') }}" placeholder="@lang('forum.topic-quick-search')"
                   class="form-control">
        </form>
        <div class="forum-categories">
            <table class="table table-bordered table-hover">
            @foreach ($categories as $category)
                @if ($category->getPermission() != null && $category->getPermission()->show_forum == true && $category->getForumsInCategory()->count() > 0)
                        <thead class="no-space">
                        <tr class="no-space">
                            <td colspan="5" class="no-space">
                                <div class="header gradient teal some-padding">
                                    <div class="inner_content">
                                        <h1 class="no-space">{{ $category->name }}</h1>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </thead>
                        <thead>
                        <tr>
                            <td colspan="5">
                                <div class="button-holder">
                                    <div class="button-left"></div>
                                    <div class="button-right">
                                        <a href="{{ route('forum_category', ['slug' => $category->slug, 'id' => $category->id]) }}"
                                           class="btn btn-sm btn-primary">@lang('forum.view-all')</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </thead>
                        <thead>
                        <tr>
                            <th></th>
                            <th class="text-left">{{ strtoupper(trans('forum.name')) }}</th>
                            <th class="text-left">{{ strtoupper(trans('forum.posts')) }}</th>
                            <th class="text-left">{{ strtoupper(trans('forum.topics')) }}</th>
                            <th class="text-left">{{ strtoupper(trans('forum.latest')) }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($category->getForumsInCategory()->sortBy('position') as $categoryChild)
                            @if ($categoryChild->getPermission() != null && $categoryChild->getPermission()->show_forum == true)
                            <tr>
                                <td><img src="{{ url('img/forum.png') }}"></td>
                                <td>
                                    <span><h4><a href="{{ route('forum_display', ['slug' => $categoryChild->slug, 'id' => $categoryChild->id]) }}"><span
                                                        class="text-bold">{{ $categoryChild->name }}</span></a><h4></span>
                                    <span class="">{{ $categoryChild->description }}</span>
                                </td>
                                <td>{{ $categoryChild->num_post }}</td>
                                <td>{{ $categoryChild->num_topic }}</td>
                                <td>
                                    <span>@lang('forum.last-message') - {{ strtolower(trans('forum.author')) }} <i
                                                class="{{ config('other.font-awesome') }} fa-user"></i> <a
                                                href="{{ route('profile', ['username' => $categoryChild->last_post_user_username, 'id' => $categoryChild->last_post_user_id]) }}"> {{ $categoryChild->last_post_user_username }}</a></span>
                                    <br>
                                    <span>@lang('forum.topic') <i class="{{ config('other.font-awesome') }} fa-chevron-right"></i><a
                                                href="{{ route('forum_topic', ['slug' => $categoryChild->last_topic_slug, 'id' => $categoryChild->last_topic_id]) }}"> {{ $categoryChild->last_topic_name }}</a></span>
                                    <br>
                                    <span><i class="{{ config('other.font-awesome') }} fa-clock"></i> {{ $categoryChild->updated_at->diffForHumans() }}</span>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                @endif
            @endforeach
            </table>
        </div>
    </div>
@endsection
