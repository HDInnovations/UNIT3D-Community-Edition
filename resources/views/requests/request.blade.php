@extends('layout.default')
@section('title')
<title>
    Request - {{ Config::get('other.title') }}
</title>
@stop
@section('breadcrumb')
<li>
    <a href="{{ route('requests') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Requests</span>
    </a>
</li>
<li>
    <a href="{{ route('request', ['id' => $request->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Request Details</span>
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
          <i class="fa fa-times text-danger"></i> Error: Your Request Rights Have Been Disabled
        </h1>
      <div class="separator"></div>
    <p class="text-center">If You Feel This Is In Error, Please Contact Staff!</p>
  </div>
  </div>
  </div>
  @else
    <h1 class="title h2">
        {{ $request->name }}
        <span class="text-green">for <i class="fa fa-star text-gold">
            </i> <strong>{{ $request->bounty }}</strong> BON</span>
    </h1>
    <div class="block">
        <div class="row mb-10">
            <div class="col-sm-12">
                <div class="pull-right">
                    <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal_request_report"><i class="fa fa-eye"></i> Report Request</button>
                    @if($request->filled_hash == null)
                    <button class="btn btn-xs btn-success btn-vote-request" data-toggle="modal" data-target="#vote"><i class="fa fa-thumbs-up">
                        </i> Vote Up</button>
                    @if($request->claimed == 1 && $requestClaim->username == $user->username || $user->group->is_modo)
                    <button id="btn_fulfil_request" class="btn btn-xs btn-info" data-toggle="modal" data-target="#fill"><i class="fa fa-link">
                        </i> Fulfill</button>
                    @elseif($request->claimed == 0)
                    <button id="btn_fulfil_request" class="btn btn-xs btn-info" data-toggle="modal" data-target="#fill"><i class="fa fa-link">
                        </i> Fulfill</button>
                    @endif
                    @endif

                    @if($user->group->is_modo && $request->filled_hash != null)
                    <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#reset"><i class="fa fa-undo">
                        </i> Reset</button>
                    @endif
                    @if($user->group->is_modo || ($request->user->id == $user->id && $request->filled_hash == null))
                    <a class="btn btn-warning btn-xs" href="{{ route('edit_request', array('id' => $request->id)) }}" role="button"><i class="fa fa-pencil-square-o" aria-hidden="true"> Edit</i></a>
                    <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete"><i class="fa fa-trash-o">
                        </i> Delete</button>
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
                        <span class="badge-extra text-bold text-gold">Rating:
                          <span class="movie-rating-stars">
                            <i class="fa fa-star"></i>
                          </span>
                        {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} votes)
                       </span>
              				</h1>
              				<span class="movie-overview">
                        {{ $movie->plot }}
              				</span>
              				<ul class="movie-details">
                        <li>
                          @if($movie->genres)
                          @foreach($movie->genres as $genre)
                          <span class="badge-extra text-bold text-green">{{ $genre }}</span>
                          @endforeach
                          @endif
                        </li>
                        <li>
                          <span class="badge-extra text-bold text-orange">Rated: {{ $movie->rated }} </span> <span class="badge-extra text-bold text-orange">Runtime: {{ $movie->runtime }} minutes</span>
                        </li>
                        <li>
                        <span class="badge-extra text-bold text-orange">
                          <a rel="nofollow" href="https://anon.to?http://www.imdb.com/title/{{ $movie->imdb }}" title="IMDB" target="_blank">IMDB: {{ $movie->imdb }}</a>
                        </span>
                          @if($request->category_id == "2")
                          <span class="badge-extra text-bold text-orange">
                            <a rel="nofollow" href="https://anon.to?https://www.themoviedb.org/tv/{{ $movie->tmdb }}" title="TheMovieDatabase" target="_blank">TMDB: {{ $movie->tmdb }}</a>
                          </span>
                          @else
                          <span class="badge-extra text-bold text-orange">
                            <a rel="nofollow" href="https://anon.to?https://www.themoviedb.org/movie/{{ $movie->tmdb }}" title="TheMovieDatabase" target="_blank">TMDB: {{ $movie->tmdb }}</a>
                          </span>
                          @endif
                        </li>
                      </ul>

                      {{--<br>
                      <p>
                        <li>
                          <legend class="xsmall-legend strong-legend"></legend>
                          <div class="row cast-list">
                          @if($movie->actors)
                            @foreach(array_slice($movie->actors, 0,6) as $actor)
                              <div class="col-xs-4 col-md-2 text-center">
                                <a rel="nofollow" href="https://anon.to?https://www.themoviedb.org/person/{{ $actor->tmdb }}" title="TheMovieDatabase" target="_blank">
                                  <img class="img-circle img-thumbnail img-responsive" style="height:100px;" src="https://via.placeholder.com/100x100">
                                  <span class="badge-user"><strong>{{ $actor->name }}</strong></span>
                                </a>
                              </div>
                            @endforeach
                            @endif
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
                            <strong>Category</strong>
                        </td>
                        <td>
                            <span class="label label-info" data-toggle="tooltip" title="" data-original-title="{{ $request->category->name }}">{{ $request->category->name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Title</strong>
                        </td>
                        <td>
                            {{ $request->name }}
                </td>
        </tr>
        <tr>
            <td>
                <strong>Type</strong>
            </td>
            <td>
                <span class="badge-extra">{{ $request->type }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Bonus Point Rewards</strong>
            </td>
            <td>
                <i class="fa fa-star text-gold">
                </i>
                <strong>{{ $request->bounty }}</strong>
                BON from
                <strong>{{ $request->requestBounty->count() }}</strong>
                Voters
            </td>
        </tr>
        <tr>
            <td>
                <strong>Description</strong>
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
                <strong>Requested By</strong>
            </td>
            <td>
                <span class="badge-user"><a href="{{ route('profil', ['username' => $request->user->username, 'id' => $request->user->id]) }}" title="">{{ $request->user->username }}</a></span>
                <span class="badge-extra">{{ $request->created_at->diffForHumans() }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Claim</strong>
            </td>
            <td>
              @if($request->claimed == null && $request->filled_hash == null)
              <button class="btn btn-md btn-success btn-vote-request" data-toggle="modal" data-target="#claim"><i class="fa fa-suitcase">
              </i> Claim Request
              </button>
              @elseif($request->filled_hash != null && $request->approved_by == null)
                <button class="btn btn-xs btn-info" disabled><i class="fa fa-question-circle"></i>Pending</button>
              @elseif($request->filled_hash != null)
                <button class="btn btn-xs btn-success" disabled><i class="fa fa-check-square-o"></i>Filled</button>
              @else
                @if($requestClaim->anon == 0)
                  <span class="badge-user">{{ $requestClaim->username }}</span>
                @if($user->group->is_modo || $requestClaim->username == $user->username)
                  <a href="{{ route('unclaimRequest', ['id' => $request->id]) }}"class="btn btn-xs btn-danger" role="button" data-toggle="tooltip" title="" data-original-title="Un-Claim This Request!">
                    <span class="icon"><i class="fa fa-times"></i> Reset Claim</span>
                  </a>
                @endif
                @else
                <span class="badge-user">ANONYMOUS</span>
                @if($user->group->is_modo || $requestClaim->username == $user->username)
                  <a href="{{ route('unclaimRequest', ['id' => $request->id]) }}"class="btn btn-xs btn-danger" role="button" data-toggle="tooltip" title="" data-original-title="Un-Claim This Request!">
                    <span class="icon"><i class="fa fa-times"></i> Reset Claim</span>
                  </a>
                @endif
                @endif
              @endif
            </td>
        </tr>
        @if($request->filled_hash != null && $request->approved_by != null)
        <tr>
            <td>
                <strong>Filled By</strong>
            </td>
            <td>
                <span class="badge-user"><a href="{{ route('profil', ['username' => $request->FillUser->username, 'id' => $request->FillUser->id ]) }}" title="">{{ $request->FillUser->username }}</a></span>
                <span class="badge-extra">{{ $request->approved_when->diffForHumans() }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Torrent</strong>
            </td>
            <td>
                <a href="{{ route('torrent', ['slug' => $request->torrent->slug, 'id' => $request->torrent->id]) }}">{{ $request->torrent->name }}</a>
            </td>
        </tr>
        @endif
        @if($request->user_id == $user->id && $request->filled_hash != null && $request->approved_by == null || Auth::user()->group->is_modo && $request->filled_hash != null && $request->approved_by == null)
        <tr>
            <td>
                <strong>Filled By</strong>
            </td>
            <td>
                <span class="badge-user"><a href="{{ route('profil', ['username' => $request->FillUser->username, 'id' => $request->FillUser->id ]) }}" title="">{{ $request->FillUser->username }}</a></span>
                <span class="badge-extra">{{ $request->filled_when->diffForHumans() }}</span>
                <span class="badge-extra"><a href="{{ route('approveRequest', ['id' => $request->id]) }}">Approve</a></span>
                <span class="badge-extra"><a href="{{ route('rejectRequest', ['id' => $request->id]) }}">Reject</a></span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Torrent</strong>
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
        <strong><a href="#">Voters</a></strong>
    </div>
    <div id="collapseVoters" class="panel-body collapse" aria-expanded="false">
        <div class="pull-right">
        </div>
        <table class="table table-condensed table-bordered table-striped">
            <thead>
                <tr>
                    <th>
                        User
                    </th>
                    <th>
                        Bonus Points
                    </th>
                    <th>
                        Last Vote
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
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><a href="#">Comments</a></strong>
    </div>
    <div class="panel-body">
        <label for="comment" class="sr-only">Comment</label>
        {{ Form::open(array('route' => array('comment_request', 'id' => $request->id))) }}
        {{ csrf_field() }}
        <textarea rows="2" class="form-control" name="content" cols="50" id="content"></textarea>
        <div class="form-group">
            <div class="col-sm-12">
                <input class="btn btn-sm btn-primary" type="submit" value="Post Comment">
                {{ Form::close() }}
                <span class="badge-extra">Type <strong>:</strong> for emoji</span> <span class="badge-extra">BBCode is allowed</span>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div id="commentsBlock">
        <div class="comments">
          <ul class="media-list comments-list">
          @foreach($comments as $comment)
          <li class="media" style="border-left: 5px solid #01BC8C">
            <div class="media-body">
            @if($comment->anon == 1)
            <a href="#" class="pull-left">
            <img src="{{ url('img/profil.png') }}" alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
            <strong>ANONYMOUS @if(Auth::user()->group->is_modo)<a href="{{ route('profil', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}">({{ $comment->user->username }})</a>@endif</strong>
            @else
            <a href="{{ route('profil', array('username' => $comment->user->username, 'id' => $comment->user->id)) }}" class="pull-left">
            @if($comment->user->image != null)
            <img src="{{ url('files/img/' . $comment->user->image) }}" alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
            @else
            <img src="{{ url('img/profil.png') }}" alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
            @endif
            <strong>By <a href="{{ route('profil', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}">{{ $comment->user->username }}</a></strong> @endif
            <span class="text-muted"><small><em>{{$comment->created_at->diffForHumans() }}</em></small></span>
            @if($comment->user_id == Auth::id() || Auth::user()->group->is_modo)
            <a title="Delete your comment" href="{{route('comment_delete',['comment_id'=>$comment->id])}}"><i class="pull-right fa fa-lg fa-times" aria-hidden="true"></i></a>
            @endif
            <div class="pt-5">
            @emojione($comment->getContentHtml())
            </div>
          </div>
          </li>
          @endforeach
          </ul>
            </div>
            <div class="pull-left">
            </div>
        </div>
    </div>
    @endif
</div>
@include('requests.request_modals')
@stop
