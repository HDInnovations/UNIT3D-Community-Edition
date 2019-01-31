<div class="button-holder">
    <div class="button-left">
        @if(auth()->user()->id == $user->id)
                @if((!auth()->user()->hidden || auth()->user()->hidden == 0))
                    <a href="{{ route('user_hidden', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-danger">
                        <i class='{{ config("other.font-awesome") }} fa-eye-slash'></i> @lang('user.become-hidden')
                    </a>
                @else
                    <a href="{{ route('user_visible', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-success">
                        <i class='{{ config("other.font-awesome") }} fa-eye'></i> @lang('user.become-visible')
                    </a>
                @endif
                @if((auth()->user()->private_profile == 0 || auth()->user()->private_profile == 0))
                    <a href="{{ route('user_private', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-danger">
                        <i class='{{ config("other.font-awesome") }} fa-lock'></i> @lang('user.go-private')
                    </a>
                @else
                    <a href="{{ route('user_public', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-success">
                        <i class='{{ config("other.font-awesome") }} fa-lock-open'></i> @lang('user.go-public')
                    </a>
                @endif
                @if((auth()->user()->block_notifications == 0 || auth()->user()->block_notifications == 0))
                    <a href="{{ route('notification_disable', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-danger">
                        <i class='{{ config("other.font-awesome") }} fa-bell-slash'></i> @lang('user.disable-notifications')
                    </a>
                @else
                    <a href="{{ route('notification_enable', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-success">
                        <i class='{{ config("other.font-awesome") }} fa-bell'></i> @lang('user.enable-notifications')
                    </a>
                @endif
        @endif
    </div>
    <div class="button-right">
        @if(auth()->user()->id == $user->id)
            <a href="{{ route('user_settings', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-danger">
                @lang('user.settings')
            </a>
            <a href="{{ route('user_edit_profile_form', ['username' => $user->slug, 'id' => $user->id]) }}">
                <button class="btn btn-sm btn-danger">@lang('user.edit-profile')</button></a>
        @endif
    </div>
</div>
<div class="button-holder">
    <div class="button-left">
        <a href="{{ route('profile', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.profile')
        </a>
        @if(auth()->user()->id == $user->id)
            <a href="{{ route('achievements') }}" class="btn btn-sm btn-primary">
                @lang('user.achievements')
            </a>
        @else
            <a href="{{ route('user_achievements', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
                @lang('user.achievements')
            </a>
        @endif
        <a href="{{ route('user_followers', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.followers')
        </a>
        <a href="{{ route('user_uploads', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.uploads')
        </a>
        <a href="{{ route('user_downloads', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.downloads')
        </a>
        <a href="{{ route('user_topics', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.topics')
        </a>
        <a href="{{ route('user_posts', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.posts')
        </a>
    </div>
    <div class="button-right">
            <a href="{{ route('user_torrents', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
                @lang('user.torrents')
            </a>
            <a href="{{ route('user_active', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
                @lang('user.active')
            </a>
            <a href="{{ route('user_seeds', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
                @lang('user.seeds')
            </a>
        @if(auth()->user()->id == $user->id)
            <a href="{{ route('bonus') }}" class="btn btn-sm btn-primary">
                @lang('user.bon')
            </a>
        @else
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