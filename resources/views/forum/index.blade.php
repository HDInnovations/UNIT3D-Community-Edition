@extends('layout.default')

@section('title')
    <title>{{ __('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ config('other.title') }} - {{ __('forum.forums') }}">
@endsection


@section('breadcrumb')
    <li class="active">
        <a href="{{ route('forums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('forum.forums') }}</span>
        </a>
    </li>
@endsection


@section('content')
    <div class="box container">
        <div class="button-holder">
            @include('forum.buttons')
            <div class="button-right">
                <form role="form" method="GET" action="{{ route('forum_search_form') }}" class="form-inline">
                    <input type="hidden" name="sorting" value="created_at">
                    <input type="hidden" name="direction" value="desc">
                    <label for="name"></label>
                    <input type="text" name="name" id="name"
                           value="{{ isset($params) && is_array($params) && array_key_exists('name', $params) ? $params['name'] : '' }}"
                           placeholder="{{ __('forum.topic-quick-search') }}" class="form-control">
                    <button type="submit" class="btn btn-success">
                        <i class="{{ config('other.font-awesome') }} fa-search"></i> {{ __('common.search') }}
                    </button>
                </form>
            </div>
        </div>
        <div class="forum-categories">
            <table class="table table-bordered table-hover">
                @foreach ($categories as $category)
                    @if ($category->getPermission() != null && $category->getPermission()->show_forum == true &&
                        $category->getForumsInCategory()->count() > 0)
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
                                        <a href="{{ route('forums.categories.show', ['id' => $category->id]) }}"
                                           class="btn btn-sm btn-primary">{{ __('forum.view-all') }}</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </thead>
                        <thead>
                        <tr>
                            <th></th>
                            <th class="text-left">{{ strtoupper(__('forum.name')) }}</th>
                            <th class="text-left">{{ strtoupper(__('forum.posts')) }}</th>
                            <th class="text-left">{{ strtoupper(__('forum.topics')) }}</th>
                            <th class="text-left">{{ strtoupper(__('forum.latest')) }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($category->getForumsInCategory()->sortBy('position') as $categoryChild)
                            @if ($categoryChild->getPermission() != null && $categoryChild->getPermission()->show_forum == true)
                                <tr>
                                    <td><img src="{{ url('img/forum.png') }}" alt="forum"></td>
                                    <td>
                                            <span>
                                                <h4><a href="{{ route('forums.show', ['id' => $categoryChild->id]) }}"><span
                                                                class="text-bold">{{ $categoryChild->name }}</span></a></h4>
                                            </span>
                                        <span class="">{{ $categoryChild->description }}</span>
                                    </td>
                                    <td>{{ $categoryChild->num_post }}</td>
                                    <td>{{ $categoryChild->num_topic }}</td>
                                    <td>
                                            <span>
                                                <span>{{ __('forum.last-message') }} - {{ strtolower(__('forum.author')) }}</span>
                                                <i class="{{ config('other.font-awesome') }} fa-user"></i>
                                                @if ($categoryChild->last_post_user_username !== null)
                                                    <a href="{{ route('users.show', ['username' => $categoryChild->last_post_user_username]) }}">
                                                        {{ $categoryChild->last_post_user_username }}
                                                    </a>
                                                @endif
                                            </span>
                                        <br>
                                        <span>
                                                <span>{{ __('forum.topic') }}</span>
                                                <i class="{{ config('other.font-awesome') }} fa-chevron-right"></i>
                                                @if ($categoryChild->last_topic_id !== null)
                                                <a href="{{ route('forum_topic', ['id' => $categoryChild->last_topic_id]) }}">
                                                    {{ $categoryChild->last_topic_name }}</a>
                                            @endif
                                            </span>
                                        <br>
                                        <span>
                                                <span><i class="{{ config('other.font-awesome') }} fa-clock"></i></span>
                                                {{ $categoryChild->updated_at->diffForHumans() }}
                                            </span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    @endif
                @endforeach
            </table>
        </div>
        @include('forum.stats')
    </div>
@endsection
