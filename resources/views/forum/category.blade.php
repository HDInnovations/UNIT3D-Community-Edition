@extends('layout.default')

@section('title')
    <title>{{ $c->name }} - {{ trans('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ trans('forum.meta-category') . ' ' . $c->name }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('forum.forums') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_category') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $c->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="box container">
        <div class="f-category" id="category_{{ $c->id }}">
            <div class="f-category-title col-md-12">
                <h2>
                    <a href="{{ route('forum_category', ['slug' => $c->slug, 'id' => $c->id]) }}">{{ $c->name }}</a>
                </h2>
            </div>

            <div class="f-category-table-wrapper col-md-12">
                <table class="f-category-forums table col-md-12">
                    <thead>
                    <tr>
                        <th></th>
                        <th>{{ trans('forum.forum') }}</th>
                        <th>{{ trans('forum.stats') }}</th>
                        <th>{{ trans('forum.last-message') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($c->getForumsInCategory() as $f)
                        <tr>
                            <!-- Icon -->
                            <td class="f-category-forums-icon">
                                <img src="{{ url('img/f_icon_read.png') }}">
                            </td><!-- /Icon -->
                            <!-- Forum title -->
                            <td>
                                <h4 class="f-category-forums-title">
                                    <a href="{{ route('forum_display', ['slug' => $f->slug, 'id' => $f->id]) }}">{{ $f->name }}</a>
                                </h4>
                                <br>
                                <p class="f-category-forums-description">{{ $f->description }}</p>
                            </td><!-- /Forum title -->
                            <!-- Stats -->
                            <td class="f-category-forums-stats">
                                <ul>
                                    <li class="f-category-forums-item">{{ $f->num_topic }} {{ trans('forum.topics') }}</li>
                                    <li class="f-category-forums-item">{{ $f->num_post }} {{ trans('forum.replies') }}</li>
                                </ul>
                            </td><!-- /Stats -->
                            <!-- Last post -->
                            <td>
                                <ul class="f-category-forums-last-post">
                                    <li class="f-category-forums-last-post-item"><a
                                                href="{{ route('forum_topic', ['slug' => $f->last_topic_slug, 'id' => $f->last_topic_id]) }}">{{ $f->last_topic_name }}</a>
                                    </li>
                                    <li class="f-category-forums-last-post-item">
                                        By
                                        <a href="{{ route('profile', ['username' => $f->last_post_user_username, 'id' => $f->last_post_user_id]) }}">{{ $f->last_post_user_username }}</a>
                                    </li>
                                    <li class="f-category-forums-last-post-item">
                                        <time datetime="{{ date('d-m-Y h:m', strtotime($f->updated_at)) }}">
                                            {{ date('d M Y', strtotime($f->updated_at)) }}
                                        </time>
                                    </li>
                                </ul>
                            </td><!-- /Last post -->
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
