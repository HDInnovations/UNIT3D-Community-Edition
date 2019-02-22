<div class="button-holder">
    <div class="button-left">
        <a href="{{ route('profile', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.profile')
        </a>
        @if (auth()->user()->isAllowed($user,'achievement','show_achievement'))
            <a href="{{ route('user_achievements', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
                @lang('user.achievements')
            </a>
        @endif
        @if (auth()->user()->isAllowed($user,'follower','show_follower'))
            <a href="{{ route('user_followers', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
                @lang('user.followers')
            </a>
        @endif
        @if (auth()->user()->isAllowed($user,'torrent','show_upload'))
        <a href="{{ route('user_uploads', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.uploads')
        </a>
        @endif
        @if (auth()->user()->isAllowed($user,'torrent','show_download'))
        <a href="{{ route('user_downloads', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.downloads')
        </a>
        @endif
        @if (auth()->user()->isAllowed($user,'forum','show_post'))
            <a href="{{ route('user_posts', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
                @lang('user.posts')
            </a>
        @endif
        @if (auth()->user()->isAllowed($user,'forum','show_topic'))
            <a href="{{ route('user_topics', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
                @lang('user.topics')
            </a>
        @endif
        @if (auth()->user()->isAllowed($user,'forum','show_requested'))
            <a href="{{ route('user_requested', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
                @lang('user.requested')
            </a>
        @endif
    </div>
    <div class="button-right">
        @if (auth()->user()->id != $user->id)
            @if (auth()->user()->isFollowing($user->id))
                <a href="{{ route('unfollow', ['user' => $user->id]) }}"
                   id="delete-follow-{{ $user->target_id }}" class="btn btn-sm btn-danger"
                   title="@lang('user.unfollow')"><i
                            class="{{ config('other.font-awesome') }} fa-user"></i> @lang('user.unfollow')</a>
            @else
                <a href="{{ route('follow', ['user' => $user->id]) }}"
                   id="follow-user-{{ $user->id }}" class="btn btn-sm btn-warning"
                   title="@lang('user.follow')"><i
                            class="{{ config('other.font-awesome') }} fa-user"></i> @lang('user.follow')</a>
            @endif
            <button class="btn btn-sm btn-danger" data-toggle="modal"
                    data-target="#modal_user_report"><i
                        class="{{ config('other.font-awesome') }} fa-eye"></i> @lang('user.report')</button>
        @endif
    </div>
</div>