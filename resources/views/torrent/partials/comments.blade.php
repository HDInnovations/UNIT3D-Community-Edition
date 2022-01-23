<div class="clearfix"></div>
<div class="row ">
    <div class="col-md-12 col-sm-12">
        <div class="panel panel-chat shoutbox">
            <div class="panel-heading">
                <h4>
                    <i class="{{ config('other.font-awesome') }} fa-comment"></i> {{ __('common.comments') }}
                </h4>
            </div>
            <div class="panel-body no-padding">
                <ul class="media-list comments-list">
                    @if (count($comments) == 0)
                        <div class="text-center"><h4 class="text-bold text-danger"><i
                                        class="{{ config('other.font-awesome') }} fa-frown"></i> {{ __('common.no-comments') }}
                                !</h4>
                        </div>
                    @else
                        @foreach ($comments as $comment)
                            <li class="media" style="border-left: 5px solid rgb(1,188,140);">
                                <div class="media-body">
                                    @if ($comment->anon == 1)
                                        <a href="#" class="pull-left" style="padding-right: 10px;">
                                            <img src="{{ url('img/profile.png') }}" class="img-avatar-48">
                                            <strong>{{ strtoupper(__('common.anonymous')) }}</strong></a> @if (auth()->user()->id == $comment->user->id || auth()->user()->group->is_modo)
                                            <a href="{{ route('users.show', ['username' => $comment->user->username]) }}"
                                               style="color:{{ $comment->user->group->color }};">(<span><i
                                                            class="{{ $comment->user->group->icon }}"></i> {{ $comment->user->username }}</span>)</a> @endif
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
                                                    href="{{ route('users.show', ['username' => $comment->user->username]) }}"
                                                    style="color:{{ $comment->user->group->color }};"><span><i
                                                            class="{{ $comment->user->group->icon }}"></i> {{ $comment->user->username }}</span></a></strong> @endif
                                    <span class="text-muted"><small><em>{{ $comment->created_at->toDayDateTimeString() }} ({{ $comment->created_at->diffForHumans() }})</em></small></span>
                                    @if ($comment->user_id == auth()->id() || auth()->user()->group->is_modo)
                                        <div class="pull-right" style="display: inline-block;">
                                            <a data-toggle="modal" data-target="#modal-comment-edit-{{ $comment->id }}">
                                                <button class="btn btn-circle btn-info">
                                                    <i class="{{ config('other.font-awesome') }} fa-pencil"></i>
                                                </button>
                                            </a>
                                            <form action="{{ route('comment_delete', ['comment_id' => $comment->id]) }}"
                                                  method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-circle btn-danger">
                                                    <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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


    <div class="clearfix"></div>
    <div class="col-md-12 home-pagination">
        <div class="text-center">{{ $comments->links() }}</div>
    </div>
    <br>

    <div class="col-md-12">
        <form role="form" method="POST"
              action="{{ route('comment_torrent', ['id' => $torrent->id]) }}">
            @csrf
            <div class="form-group">
                <label for="content">{{ __('common.your-comment') }}:</label><span class="badge-extra">{{ __('common.type-verb') }}
		        			<strong>":"</strong> {{ __('common.for') }} emoji</span> <span
                        class="badge-extra">BBCode {{ __('common.is-allowed') }}</span>
                <textarea id="content" name="content" cols="30" rows="5" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-danger">{{ __('common.submit') }}</button>
            <label class="radio-inline"><strong>{{ __('common.anonymous') }} {{ __('common.comment') }}
                    :</strong></label>
            <label>
                <input type="radio" value="1" name="anonymous">
            </label> {{ __('common.yes') }}
            <label>
                <input type="radio" value="0" checked="checked" name="anonymous">
            </label> {{ __('common.no') }}
        </form>
    </div>
</div>