@extends('layout.default')

@section('title')
    <title>Results - {{ trans('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Forum Search Results">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('forum.forums') }}</span>
        </a>
    </li>
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('common.search-results') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="box container">
        <div class="f-display">
            <div class="f-display-info col-md-12">
                <h1 class="f-display-info-title">{{ trans('common.search-results') }}</h1>
                <p class="f-display-info-description">{{ trans('common.search-results-desc') }}</p>
            </div>
            <div class="f-display-table-wrapper col-md-12">
                <table class="f-display-topics table col-md-12">
                    <thead>
                    <tr>
                        <th>{{ trans('forum.forum') }}</th>
                        <th>{{ trans('forum.topic') }}</th>
                        <th>{{ trans('forum.author') }}</th>
                        <th>{{ trans('forum.stats') }}</th>
                        <th>{{ trans('forum.last-post-info') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($results as $r)
                        <tr>
                            <td class="f-display-topic-icon"><span
                                        class="badge-extra text-bold">{{ $r->forum->name }}</span></td>
                            <td class="f-display-topic-title">
                                <strong><a href="{{ route('forum_topic', ['slug' => $r->slug, 'id' => $r->id]) }}">{{ $r->name }}</a></strong>
                                @if($r->state == "close") <span
                                        class='label label-sm label-default'>{{ strtoupper(trans('forum.closed')) }}</span> @endif
                                @if($r->approved == "1") <span
                                        class='label label-sm label-success'>{{ strtoupper(trans('forum.approved')) }}</span> @endif
                                @if($r->denied == "1") <span
                                        class='label label-sm label-danger'>{{ strtoupper(trans('forum.denied')) }}</span> @endif
                                @if($r->solved == "1") <span
                                        class='label label-sm label-info'>{{ strtoupper(trans('forum.solved')) }}</span> @endif
                                @if($r->invalid == "1") <span
                                        class='label label-sm label-warning'>{{ strtoupper(trans('forum.invaild')) }}</span> @endif
                                @if($r->bug == "1") <span
                                        class='label label-sm label-danger'>{{ strtoupper(trans('forum.bug')) }}</span> @endif
                                @if($r->suggestion == "1") <span
                                        class='label label-sm label-primary'>{{ strtoupper(trans('forum.suggestion')) }}</span> @endif
                            </td>
                            <td class="f-display-topic-started"><a
                                        href="{{ route('profile', ['username' => $r->first_post_user_username, 'id' => $r->first_post_user_id]) }}">{{ $r->first_post_user_username }}</a>
                            </td>
                            <td class="f-display-topic-stats">
                                {{ $r->num_post - 1 }} {{ trans('forum.replies') }}
                                \ {{ $r->views }} {{ trans('forum.views') }}
                            </td>
                            <td class="f-display-topic-last-post">
                                <a href="{{ route('profile', ['username' => $r->last_post_user_username, 'id' => $r->last_post_user_id]) }}">{{ $r->last_post_user_username }}</a>,
                                <time datetime="{{ date('d-m-Y h:m', strtotime($r->updated_at)) }}">
                                    {{ date('M d Y', strtotime($r->updated_at)) }}
                                </time>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="f-display-pagination col-md-12">
                {{ $results->links() }}
            </div>
        </div>
    </div>
@endsection
