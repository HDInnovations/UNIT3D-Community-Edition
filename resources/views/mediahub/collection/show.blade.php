@extends('layout.default')

@section('title')
    <title>{{ $collection->name }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $collection->name }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">MediaHub</span>
        </a>
    </li>
    <li>
        <a href="{{ route('mediahub.collections.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Collections</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('mediahub.collections.show', ['id' => $collection->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $collection->name }}</span>
        </a>
    </li>
@endsection

@section('content')
<div class="torrent box container">
    <div class="movie-wrapper">
        <div class="movie-backdrop" style="background-image: url({{ $collection->backdrop ?? 'https://via.placeholder.com/1400x800' }});">
            <div class="tags">
                Collection
            </div>
        </div>
        <div class="movie-overlay"></div>
        <div class="container movie-container">
            <div class="row movie-row ">
                <div class="col-xs-12 col-sm-8 col-md-8 col-sm-push-4 col-md-push-3 movie-heading-box">
                    <h1 class="movie-heading">
                        <span class="text-bold">{{ $collection->name }}</span>
                    </h1>
                    <br>
                    <span class="movie-overview">
                        {{ $collection->overview }}
                    </span>
                    <span class="movie-details">

                    </span>
                </div>

                <div class="col-xs-12 col-sm-4 col-md-3 col-sm-pull-8 col-md-pull-8">
                    <img src="{{ $collection->poster }}" class="movie-poster img-responsive hidden-xs">
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="panel panel-chat shoutbox">
        <div class="panel-heading">
            <h4><i class="{{ config("other.font-awesome") }} fa-film"></i> Movies</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-bordered table-striped">
                <tbody>
                <tr>
                    <td>
                        <section class="recommendations">
                        @foreach($collection->movie as $movie)
                            <div class="item mini backdrop mini_card col-md-3">
                                <div class="image_content">
                                    <a href="{{ route('torrents.similar', ['category_id' => '1', 'tmdb' => $movie->id]) }}">
                                        <div>
                                            <img class="backdrop" src="{{ $movie->poster }}">
                                        </div>
                                        <div style=" margin-top: 8px;">
                                            <span class="badge-extra"><i class="fas fa-calendar text-purple"></i> Year: {{ substr($movie->release_date, 0, 4) }}</span>
                                            <span class="badge-extra"><i class="fas fa-star text-gold"></i> Rating: {{ $movie->vote_average }}</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        </section>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="torrent box container" id="comments">
    <!-- Comments -->
    <div class="clearfix"></div>
    <div class="row ">
        <div class="col-md-12 col-sm-12">
            <div class="panel panel-chat shoutbox">
                <div class="panel-heading">
                    <h4>
                        <i class="{{ config('other.font-awesome') }} fa-comment"></i> @lang('common.comments')
                    </h4>
                </div>
                <div class="panel-body no-padding">
                    <ul class="media-list comments-list">
                        @if (count($collection->comments) == 0)
                            <div class="text-center"><h4 class="text-bold text-danger"><i
                                            class="{{ config('other.font-awesome') }} fa-frown"></i> @lang('common.no-comments')!</h4>
                            </div>
                        @else
                            @foreach ($collection->comments as $comment)
                                <li class="media" style="border-left: 5px solid #01BC8C;">
                                    <div class="media-body">
                                        @if ($comment->anon == 1)
                                            <a href="#" class="pull-left" style="padding-right: 10px;">
                                                <img src="{{ url('img/profile.png') }}"
                                                     alt="{{ $comment->user->username }}" class="img-avatar-48">
                                                <strong>{{ strtoupper(trans('common.anonymous')) }}</strong></a> @if (auth()->user()->id == $comment->user->id || auth()->user()->group->is_modo)
                                                <a href="{{ route('users.show', ['username' => $comment->user->username]) }}" style="color:{{ $comment->user->group->color }};">(<span><i class="{{ $comment->user->group->icon }}"></i> {{ $comment->user->username }}</span>)</a> @endif
                                        @else
                                            <a href="{{ route('users.show', ['username' => $comment->user->username]) }}"
                                               class="pull-left" style="padding-right: 10px;">
                                                @if ($comment->user->image != null)
                                                    <img src="{{ url('files/img/' . $comment->user->image) }}"
                                                         alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
                                            @else
                                                <img src="{{ url('img/profile.png') }}"
                                                     alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
                                            @endif
                                            <strong><a
                                                        href="{{ route('users.show', ['username' => $comment->user->username]) }}" style="color:{{ $comment->user->group->color }};"><span><i class="{{ $comment->user->group->icon }}"></i> {{ $comment->user->username }}</span></a></strong> @endif
                                        <span class="text-muted"><small><em>{{ $comment->created_at->toDayDateTimeString() }} ({{ $comment->created_at->diffForHumans() }})</em></small></span>
                                        @if ($comment->user_id == auth()->id() || auth()->user()->group->is_modo)
                                            <a title="@lang('common.delete-comment')"
                                               href="{{route('comment_delete',['comment_id'=>$comment->id])}}"><i
                                                        class="pull-right {{ config('other.font-awesome') }} fa fa-times" aria-hidden="true"></i></a>
                                            <a title="@lang('common.edit-comment')" data-toggle="modal"
                                               data-target="#modal-comment-edit-{{ $comment->id }}"><i
                                                        class="pull-right {{ config('other.font-awesome') }} fa-pencil"
                                                        aria-hidden="true"></i></a>
                                        @endif
                                        <div class="pt-5">
                                            @joypixels($comment->getContentHtml())
                                        </div>
                                    </div>
                                </li>
                                @include('partials.modals', ['comment' => $comment])
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Comments -->
        <br>
        <!-- Add comment -->
        <div class="col-md-12">
            <form role="form" method="POST" action="{{ route('comment_collection', ['id' => $collection->id]) }}">
                @csrf
                <div class="form-group">
                    <label for="content">@lang('common.your-comment'):</label><span class="badge-extra">@lang('common.type')
                        <strong>:</strong> @lang('common.for') emoji</span> <span
                            class="badge-extra">BBCode @lang('common.is-allowed')</span>
                    <textarea id="content" name="content" cols="30" rows="5" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-danger">@lang('common.submit')</button>
                <label class="radio-inline"><strong>@lang('common.anonymous') @lang('common.comment')
                        :</strong></label>
                <input type="radio" value="1" name="anonymous"> @lang('common.yes')
                <input type="radio" value="0" checked="checked" name="anonymous"> @lang('common.no')
            </form>
        </div>
        <!-- /Add comment -->
    </div>
</div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $(document).ready(function () {
        $('#content').wysibb({});
        emoji.textcomplete()
      })
    </script>
@endsection