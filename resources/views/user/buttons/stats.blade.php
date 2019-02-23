<div class="button-holder">
    <div class="button-left-large">
        <a href="{{ route('profile', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.profile')
        </a>
        <a href="{{ route('user_unsatisfieds', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-exclamation"></i> @lang('user.unsatisfieds')
        </a>
        <a href="{{ route('user_torrents', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.torrents')
        </a>
        <a href="{{ route('user_active', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.active')
        </a>
        <a href="{{ route('user_uploads', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.uploads')
        </a>
        <a href="{{ route('user_downloads', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.downloads')
        </a>
        <a href="{{ route('user_seeds', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.seeds')
        </a>
        @if(auth()->user()->id == $user->id)
        @if(!$route || $route != 'profile')
            <a href="{{ route('download_history_torrents', ['username' => $user->username, 'id' => $user->id]) }}" role="button" class="btn btn-sm btn-labeled btn-success">
                    <span class='btn-label'>
                        <i class='{{ config("other.font-awesome") }} fa-download'></i> @lang('torrent.download-all')
                    </span>
            </a>
        @endif
        @endif
    </div>
    <div class="button-right-small">
            @if(auth()->user()->id == $user->id)
            <a href="{{ route('user_settings', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-danger">
                @lang('user.settings')
            </a>
            <a href="{{ route('user_edit_profile_form', ['username' => $user->slug, 'id' => $user->id]) }}">
                <button class="btn btn-sm btn-danger">@lang('user.edit-profile')</button></a>
            @endif
    </div>
</div>