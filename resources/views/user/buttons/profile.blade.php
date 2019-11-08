<div class="button-holder">
    <div class="button-left">
        @if(auth()->user()->id == $user->id)
                @if((!auth()->user()->hidden || auth()->user()->hidden == 0))
                    <a href="{{ route('user_hidden', ['username' => $user->username]) }}" class="btn btn-sm btn-danger">
                        <i class='{{ config("other.font-awesome") }} fa-eye-slash'></i> @lang('user.become-hidden')
                    </a>
                @else
                    <a href="{{ route('user_visible', ['username' => $user->username]) }}" class="btn btn-sm btn-success">
                        <i class='{{ config("other.font-awesome") }} fa-eye'></i> @lang('user.become-visible')
                    </a>
                @endif
                @if((auth()->user()->private_profile == 0 || auth()->user()->private_profile == 0))
                    <a href="{{ route('user_private', ['username' => $user->username]) }}" class="btn btn-sm btn-danger">
                        <i class='{{ config("other.font-awesome") }} fa-lock'></i> @lang('user.go-private')
                    </a>
                @else
                    <a href="{{ route('user_public', ['username' => $user->username]) }}" class="btn btn-sm btn-success">
                        <i class='{{ config("other.font-awesome") }} fa-lock-open'></i> @lang('user.go-public')
                    </a>
                @endif
                @if((auth()->user()->block_notifications == 0 || auth()->user()->block_notifications == 0))
                    <a href="{{ route('notification_disable', ['username' => $user->username]) }}" class="btn btn-sm btn-danger">
                        <i class='{{ config("other.font-awesome") }} fa-bell-slash'></i> @lang('user.disable-notifications')
                    </a>
                @else
                    <a href="{{ route('notification_enable', ['username' => $user->username]) }}" class="btn btn-sm btn-success">
                        <i class='{{ config("other.font-awesome") }} fa-bell'></i> @lang('user.enable-notifications')
                    </a>
                @endif
        @endif
    </div>
    <div class="button-right">
        @if(auth()->user()->id == $user->id)
            <a href="{{ route('user_settings', ['username' => $user->username]) }}" class="btn btn-sm btn-danger">
                @lang('user.settings')
            </a>
            <a href="{{ route('user_edit_profile_form', ['username' => $user->username]) }}">
                <button class="btn btn-sm btn-danger">@lang('user.edit-profile')</button></a>
        @endif
    </div>
</div>
<div class="button-holder">
    <div class="button-left-increased">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.profile')
        </a>
        @if(auth()->user()->id == $user->id)
            <a href="{{ route('achievements.index') }}" class="btn btn-sm btn-primary">
                @lang('user.achievements')
            </a>
        @else
            <a href="{{ route('achievements.show', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                @lang('user.achievements')
            </a>
        @endif
        <a href="{{ route('user_followers', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.followers')
        </a>
        <a href="{{ route('user_uploads', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
           @lang('user.uploads')
        </a>
        <a href="{{ route('user_downloads', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.downloads')
        </a>
        <a href="{{ route('user_posts', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.posts')
        </a>
        <a href="{{ route('user_topics', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.topics')
        </a>
        <a href="{{ route('user_requested', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.requested')
        </a>
    </div>
    <div class="button-right-decreased">
            @if(!$user->group || !$user->group->is_immune)
            <a href="{{ route('user_unsatisfieds', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                <i class="{{ config('other.font-awesome') }} fa-exclamation"></i> @lang('user.unsatisfieds')
            </a>
            @endif
            <a href="{{ route('user_torrents', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                @lang('user.torrents')
            </a>
            <a href="{{ route('user_active', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                @lang('user.active')
            </a>
            <a href="{{ route('user_seeds', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                @lang('user.seeds')
            </a>
        @if(auth()->user()->id == $user->id)
            <a href="{{ route('bonus') }}" class="btn btn-sm btn-primary">
                @lang('user.bon')
            </a>
        @else
            @if (auth()->user()->isFollowing($user->id))
                <a href="{{ route('follow.destroy', ['username' => $user->username]) }}"
                   id="delete-follow-{{ $user->target_id }}" class="btn btn-sm btn-danger"
                   title="@lang('user.unfollow')"><i
                            class="{{ config('other.font-awesome') }} fa-user"></i> @lang('user.unfollow')</a>
            @else
                <a href="{{ route('follow.store', ['username' => $user->username]) }}"
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
