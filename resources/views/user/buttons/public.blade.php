<div class="button-holder">
    <div class="button-left-increased">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.profile') }}
        </a>
        @if (auth()->user()->isAllowed($user,'achievement','show_achievement'))
            <a href="{{ route('achievements.show', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                {{ __('user.achievements') }}
            </a>
        @endif
        @if (auth()->user()->isAllowed($user,'follower','show_follower'))
            <a href="{{ route('user_followers', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                {{ __('user.followers') }}
            </a>
        @endif
        @if (auth()->user()->isAllowed($user,'torrent','show_upload'))
            <a href="{{ route('user_uploads', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                {{ __('user.uploads') }}
            </a>
        @endif
        @if (auth()->user()->isAllowed($user,'torrent','show_download'))
            <a href="{{ route('user_downloads', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                {{ __('user.downloads') }}
            </a>
        @endif
        @if (auth()->user()->isAllowed($user,'forum','show_post'))
            <a href="{{ route('user_posts', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                {{ __('user.posts') }}
            </a>
        @endif
        @if (auth()->user()->isAllowed($user,'forum','show_topic'))
            <a href="{{ route('user_topics', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                {{ __('user.topics') }}
            </a>
        @endif
        @if (auth()->user()->isAllowed($user,'forum','show_requested'))
            <a href="{{ route('user_requested', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                {{ __('user.requested') }}
            </a>
        @endif
    </div>
    <div class="button-right-decreased">
        @if (auth()->user()->id != $user->id)
            @if (auth()->user()->isFollowing($user->id))
                <form class="form-inline" role="form"
                      action="{{ route('follow.destroy', ['username' => $user->username]) }}"
                      style="display: inline-block;" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <button type="submit" id="delete-follow-{{ $user->target_id }}" class="btn btn-sm btn-danger"
                                title="{{ __('user.unfollow') }}">
                            <i class="{{ config('other.font-awesome') }} fa-user"></i> {{ __('user.unfollow') }}
                        </button>
                    </div>
                </form>
            @else
                <form class="form-inline" role="form"
                      action="{{ route('follow.store', ['username' => $user->username]) }}"
                      style="display: inline-block;" method="POST">
                    @csrf
                    <div class="form-group">
                        <button type="submit" id="follow-user-{{ $user->id }}" class="btn btn-sm btn-success"
                                title="{{ __('user.follow') }}">
                            <i class="{{ config('other.font-awesome') }} fa-user"></i> {{ __('user.follow') }}
                        </button>
                    </div>
                </form>
            @endif
            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal_user_report"><i
                        class="{{ config('other.font-awesome') }} fa-eye"></i> {{ __('user.report') }}</button>
        @endif
    </div>
</div>
