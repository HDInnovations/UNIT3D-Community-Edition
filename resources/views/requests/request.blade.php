@extends('layout.default')

@section('title')
<title>Request - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('requests') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('request.requests') }}</span>
  </a>
</li>
<li>
  <a href="{{ route('request', ['id' => $request->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('request.request-details') }}</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
  @if($user->can_request == 0)
  <div class="container">
    <div class="jumbotron shadowed">
      <div class="container">
        <h1 class="mt-5 text-center">
          <i class="fa fa-times text-danger"></i> {{ trans('request.no-privileges') }}
        </h1>
        <div class="separator"></div>
        <p class="text-center">{{ trans('request.no-privileges-desc') }}!</p>
      </div>
    </div>
  </div>
  @else
  <h1 class="title h2">
        {{ $request->name }}
        <span class="text-green">{{ trans('request.for') }} <i class="fa fa-star text-gold">
            </i> <strong>{{ $request->bounty }}</strong> {{ trans('bon.bon') }}</span>
    </h1>
  <div class="block">
    <div class="row mb-10">
      <div class="col-sm-12">
        <div class="pull-right">
          <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal_request_report"><i class="fa fa-eye"></i> {{ trans('request.report') }}</button>
          @if($request->filled_hash == null)
          <button class="btn btn-xs btn-success btn-vote-request" data-toggle="modal" data-target="#vote"><i class="fa fa-thumbs-up">
                        </i> {{ trans('request.vote') }}</button>
          @if($request->claimed == 1 && $requestClaim->username == $user->username || $user->group->is_modo)
          <button id="btn_fulfil_request" class="btn btn-xs btn-info" data-toggle="modal" data-target="#fill"><i class="fa fa-link">
                        </i> {{ trans('request.fulfill') }}</button>
          @elseif($request->claimed == 0)
          <button id="btn_fulfil_request" class="btn btn-xs btn-info" data-toggle="modal" data-target="#fill"><i class="fa fa-link">
                        </i> {{ trans('request.fulfill') }}</button>
          @endif @endif @if($user->group->is_modo && $request->filled_hash != null)
          <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#reset"><i class="fa fa-undo">
                        </i> {{ trans('request.reset-request') }}</button>
          @endif @if($user->group->is_modo || ($request->user->id == $user->id && $request->filled_hash == null))
          <a class="btn btn-warning btn-xs" href="{{ route('edit_request', array('id' => $request->id)) }}" role="button"><i class="fa fa-pencil-square-o" aria-hidden="true"> {{ trans('request.edit-request') }}</i></a>
          <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete"><i class="fa fa-trash-o">
                        </i> {{ trans('common.delete') }}</button>
          @endif
        </div>
      </div>
    </div>
    @if($request->category->meta == 1)
    <div class="movie-wrapper">
      <div class="movie-backdrop" style="background-image: url({{ $movie->backdrop }});">
        <div class="tags">
          {{ $request->category->name }}
        </div>
      </div>
      <div class="movie-overlay"></div>
      <div class="container movie-container">
        <div class="row movie-row ">
          <div class="col-xs-12 col-sm-8 col-md-8 col-sm-push-4 col-md-push-3 movie-heading-box">
            <h1 class="movie-heading">
              				<span class="text-bold">{{ $movie->title }}</span><span class="text-bold"><em> ({{ $movie->releaseYear }})</em></span>
                        <span class="badge-extra text-bold text-gold">{{ trans('torrent.rating') }}:
                          <span class="movie-rating-stars">
                            <i class="fa fa-star"></i>
                          </span>
                        {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} {{ strtolower(trans('torrent.votes')) }})
                       </span>
              				</h1>
            <span class="movie-overview">
                        {{ $movie->plot }}
              				</span>
            <ul class="movie-details">
              <li>
                @if($movie->genres) @foreach($movie->genres as $genre)
                <span class="badge-extra text-bold text-green">{{ $genre }}</span> @endforeach @endif
              </li>
              <li>
                <span class="badge-extra text-bold text-orange">{{ trans('torrent.rated') }}: {{ $movie->rated }} </span> <span class="badge-extra text-bold text-orange">{{ trans('torrent.runtime') }}: {{ $movie->runtime }} {{ trans('common.minute') }}{{ trans('common.plural-suffix') }}</span>
              </li>
              <li>
                <span class="badge-extra text-bold text-orange">
                          <a rel="nofollow" href="https://anon.to?http://www.imdb.com/title/{{ $movie->imdb }}" title="IMDB" target="_blank">IMDB: {{ $movie->imdb }}</a>
                        </span> @if($request->category_id == "2")
                <span class="badge-extra text-bold text-orange">
                            <a rel="nofollow" href="https://anon.to?https://www.themoviedb.org/tv/{{ $movie->tmdb }}" title="TheMovieDatabase" target="_blank">TMDB: {{ $movie->tmdb }}</a>
                          </span> @else
                <span class="badge-extra text-bold text-orange">
                            <a rel="nofollow" href="https://anon.to?https://www.themoviedb.org/movie/{{ $movie->tmdb }}" title="TheMovieDatabase" target="_blank">TMDB: {{ $movie->tmdb }}</a>
                          </span> @endif
              </li>
            </ul>

            {{--
            <br>
            <p>
              <li>
                <legend class="xsmall-legend strong-legend"></legend>
                <div class="row cast-list">
                  @if($movie->actors) @foreach(array_slice($movie->actors, 0,6) as $actor)
                  <div class="col-xs-4 col-md-2 text-center">
                    <a rel="nofollow" href="https://anon.to?https://www.themoviedb.org/person/{{ $actor->tmdb }}" title="TheMovieDatabase" target="_blank">
                      <img class="img-circle img-thumbnail img-responsive" style="height:100px;" src="https://via.placeholder.com/100x100">
                      <span class="badge-user"><strong>{{ $actor->name }}</strong></span>
                    </a>
                  </div>
                  @endforeach @endif
                </div>
              </li>
            </p>--}}
          </div>

          <div class="col-xs-12 col-sm-4 col-md-3 col-sm-pull-8 col-md-pull-8">
            <img src="{{ $movie->poster }}" class="movie-poster img-responsive hidden-xs">
          </div>
        </div>
      </div>
    </div>
    @endif
    <div class="table-responsive">
      <table class="table table-condensed table-bordered table-striped">
        <tbody>
          <tr>
            <td class="col-sm-2">
              <strong>{{ trans('torrent.category') }}</strong>
            </td>
            <td>
              <span class="label label-info" data-toggle="tooltip" title="" data-original-title="{{ $request->category->name }}">{{ $request->category->name }}</span>
            </td>
          </tr>
          <tr>
            <td>
              <strong>{{ trans('torrent.title') }}</strong>
            </td>
            <td>
              {{ $request->name }}
            </td>
          </tr>
          <tr>
            <td>
              <strong>{{ trans('torrent.type') }}</strong>
            </td>
            <td>
              <span class="badge-extra">{{ $request->type }}</span>
            </td>
          </tr>
          <tr>
            <td>
              <strong>{{ trans('bon.bon') }}</strong>
            </td>
            <td>
              <i class="fa fa-star text-gold">
                </i>
              <strong>{{ $request->bounty }}</strong> {{ trans('bon.bon') }} {{ strtolower(trans('request.reward-from')) }}
              <strong>{{ $request->requestBounty->count() }}</strong> {{ strtolower(trans('request.voters')) }}
            </td>
          </tr>
          <tr>
            <td>
              <strong>{{ trans('torrent.description') }}</strong>
            </td>
            <td>
              <div class="panel-body torrent-desc">
                <p>
                  @emojione($request->getDescriptionHtml())
                </p>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <strong>{{ trans('request.requested-by') }}</strong>
            </td>
            <td>
              <span class="badge-user"><a href="{{ route('profil', ['username' => $request->user->username, 'id' => $request->user->id]) }}" title="">{{ $request->user->username }}</a></span>
              <span class="badge-extra">{{ $request->created_at->diffForHumans() }}</span>
            </td>
          </tr>
          <tr>
            <td>
              <strong>{{ trans('request.claim') }}</strong>
            </td>
            <td>
              @if($request->claimed == null && $request->filled_hash == null)
              <button class="btn btn-md btn-success btn-vote-request" data-toggle="modal" data-target="#claim"><i class="fa fa-suitcase">
              </i> {{ trans('request.claim') }}
              </button>
              @elseif($request->filled_hash != null && $request->approved_by == null)
              <button class="btn btn-xs btn-info" disabled><i class="fa fa-question-circle"></i>{{ trans('request.pending') }}</button>
              @elseif($request->filled_hash != null)
              <button class="btn btn-xs btn-success" disabled><i class="fa fa-check-square-o"></i>{{ trans('request.filled') }}</button>
              @else @if($requestClaim->anon == 0)
              <span class="badge-user">{{ $requestClaim->username }}</span> @if($user->group->is_modo || $requestClaim->username == $user->username)
              <a href="{{ route('unclaimRequest', ['id' => $request->id]) }}" class="btn btn-xs btn-danger" role="button" data-toggle="tooltip" title="" data-original-title="{{ trans('request.unclaim') }}">
                <span class="icon"><i class="fa fa-times"></i> {{ trans('request.unclaim') }}</span>
              </a>
              @endif @else
              <span class="badge-user">{{ strtoupper(trans('common.anonymous')) }}</span> @if($user->group->is_modo || $requestClaim->username == $user->username)
              <a href="{{ route('unclaimRequest', ['id' => $request->id]) }}" class="btn btn-xs btn-danger" role="button" data-toggle="tooltip" title="" data-original-title="{{ trans('request.unclaim') }}">
                <span class="icon"><i class="fa fa-times"></i> {{ trans('request.unclaim') }}</span>
              </a>
              @endif @endif @endif
            </td>
          </tr>
          @if($request->filled_hash != null && $request->approved_by != null)
          <tr>
            <td>
              <strong>{{ trans('request.filled-by') }}</strong>
            </td>
            <td>
              <span class="badge-user"><a href="{{ route('profil', ['username' => $request->FillUser->username, 'id' => $request->FillUser->id ]) }}" title="">{{ $request->FillUser->username }}</a></span>
              <span class="badge-extra">{{ $request->approved_when->diffForHumans() }}</span>
            </td>
          </tr>
          <tr>
            <td>
              <strong>{{ trans('torrent.torrent') }}</strong>
            </td>
            <td>
              <a href="{{ route('torrent', ['slug' => $request->torrent->slug, 'id' => $request->torrent->id]) }}">{{ $request->torrent->name }}</a>
            </td>
          </tr>
          @endif @if($request->user_id == $user->id && $request->filled_hash != null && $request->approved_by == null || Auth::user()->group->is_modo && $request->filled_hash != null && $request->approved_by == null)
          <tr>
            <td>
              <strong>{{ trans('request.filled-by') }}</strong>
            </td>
            <td>
              <span class="badge-user"><a href="{{ route('profil', ['username' => $request->FillUser->username, 'id' => $request->FillUser->id ]) }}" title="">{{ $request->FillUser->username }}</a></span>
              <span class="badge-extra">{{ $request->filled_when->diffForHumans() }}</span>
              <span class="badge-extra"><a href="{{ route('approveRequest', ['id' => $request->id]) }}">{{ trans('request.approve') }}</a></span>
              <span class="badge-extra"><a href="{{ route('rejectRequest', ['id' => $request->id]) }}">{{ trans('request.reject') }}</a></span>
            </td>
          </tr>
          <tr>
            <td>
              <strong>{{ trans('torrent.torrent') }}</strong>
            </td>
            <td>
              <a href="{{ route('torrent', ['slug' => $request->torrent->slug, 'id' => $request->torrent->id]) }}">{{ $request->torrent->name }}</a>
            </td>
          </tr>
          @endif
        </tbody>
      </table>
      <div class="panel panel-default panel-collapse">
        <div class="panel-heading collapsed" data-toggle="collapse" data-target="#collapseVoters" aria-expanded="false">
          <strong><a href="#">{{ trans('request.voters') }}</a></strong>
        </div>
        <div id="collapseVoters" class="panel-body collapse" aria-expanded="false">
          <div class="pull-right">
          </div>
          <table class="table table-condensed table-bordered table-striped">
            <thead>
              <tr>
                <th>
                  {{ trans('common.user') }}
                </th>
                <th>
                  {{ trans('bon.bonus') }} {{ strtolower(trans('bon.points')) }}
                </th>
                <th>
                  {{ trans('request.last-vote') }}
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach($voters as $voter)
              <tr>
                <td>
                  <span class="badge-user"><a href="{{ route('profil', ['username' => $voter->user->username, 'id' => $voter->user->id ]) }}" title="">{{ $voter->user->username }}</a></span>
                </td>
                <td>
                  {{ $voter->seedbonus }}
                </td>
                <td>
                  {{ $voter->created_at->diffForHumans() }}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="block">
    <!-- Comments -->
    <div class="clearfix"></div>
    <div class="row ">
      <div class="col-md-12 col-sm-12">
        <div class="panel panel-danger">
          <div class="panel-heading border-light">
            <h4 class="panel-title">
              <i class="livicon" data-name="mail" data-size="18" data-color="white" data-hc="white" data-l="true"></i> {{ trans('common.comments') }}
            </h4>
          </div>
          <div class="panel-body no-padding">
            <ul class="media-list comments-list">
              @if(count($comments) == 0)
              <center>
                <h4 class="text-bold text-danger"><i class="fa fa-frown-o"></i> {{ trans('common.no-comments') }}!</h4></center>
              @else @foreach($comments as $comment)
              <li class="media" style="border-left: 5px solid #01BC8C">
                <div class="media-body">
                  @if($comment->anon == 1)
                  <a href="#" class="pull-left">
                <img src="{{ url('img/profil.png') }}" alt="{{ $comment->user->username }}" class="img-avatar-48">
                <strong>{{ strtoupper(trans('common.anonymous')) }}</strong></a> @if(Auth::user()->id == $comment->user->id || Auth::user()->group->is_modo)<a href="{{ route('profil', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}">({{ $comment->user->username }})</a>
                @endif
                  @else
                  <a href="{{ route('profil', array('username' => $comment->user->username, 'id' => $comment->user->id)) }}" class="pull-left">
                @if($comment->user->image != null)
                <img src="{{ url('files/img/' . $comment->user->image) }}" alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
                @else
                  <img src="{{ url('img/profil.png') }}" alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
                  @endif
                  <strong>{{ trans('common.author') }} <a href="{{ route('profil', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}">{{ $comment->user->username }}</a></strong>
                  @endif
                  <span class="text-muted"><small><em>{{$comment->created_at->diffForHumans() }}</em></small></span>
                  @if($comment->user_id == Auth::id() || Auth::user()->group->is_modo)
                  <a title="{{ trans('common.delete-your-comment') }}" href="{{route('comment_delete',['comment_id'=>$comment->id])}}"><i class="pull-right fa fa-lg fa-times" aria-hidden="true"></i></a>
                  <a title="{{ trans('common.edit-your-comment') }}" data-toggle="modal" data-target="#modal-comment-edit-{{ $comment->id }}"><i class="pull-right fa fa-lg fa-pencil" aria-hidden="true"></i></a>
                  @endif
                  <div class="pt-5">
                    @emojione($comment->getContentHtml())
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

      <div class="col-md-12 home-pagination">
        <center>{{ $comments->links() }}</center>
      </div>
      <br>

      <!-- Add comment -->
      <div class="col-md-12">
        {{ Form::open(array('route' => array('comment_request', 'id' => $request->id))) }}
        <div class="form-group">
          <label for="content">{{ trans('common.your-comment') }}:</label><span class="badge-extra">{{ trans('common.type') }} <strong>:</strong> {{ strtolower(trans('common.for')) }} emoji</span> <span class="badge-extra">BBCode {{ strtolower(trans('common.is-allowed')) }}</span>
          <textarea id="content" name="content" cols="30" rows="5" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-danger">{{ trans('common.submit') }}</button>
        <label class="radio-inline"><strong>{{ trans('common.anonymous') }} {{ strtolower(trans('common.comment')) }}:</strong></label>
        <input type="radio" value="1" name="anonymous"> {{ trans('common.yes') }}
        <input type="radio" value="0" checked="checked" name="anonymous"> {{ trans('common.no') }} {{ Form::close() }}
      </div>
      <!-- /Add comment -->
    </div>
  </div>
</div>
@endif
</div>
@include('requests.request_modals')
@stop
