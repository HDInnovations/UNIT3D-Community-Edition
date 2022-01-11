<div class="button-holder">
    <div class="button-left-large">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.profile') }}
        </a>
        @if(!$user->group || !$user->group->is_immune)
            <a href="{{ route('user_unsatisfieds', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                <i class="{{ config('other.font-awesome') }} fa-exclamation"></i> {{ __('user.unsatisfieds') }}
            </a>
        @endif
        <a href="{{ route('user_torrents', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.torrents') }}
        </a>
        <a href="{{ route('user_active', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.active') }}
        </a>
        <a href="{{ route('user_uploads', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.uploads') }}
        </a>
        <a href="{{ route('user_downloads', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.downloads') }}
        </a>
        <a href="{{ route('user_seeds', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.seeds') }}
        </a>
        <form role="form" method="POST" action="{{ route('flush_own_ghost_peers', ['username' => $user->username]) }}"
              style="display: inline-block;">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger">
                {{ __('staff.flush-ghost-peers') }}
            </button>
        </form>
        @if(auth()->user()->id == $user->id)
            @if(!$route || $route != 'profile')
                <a href="{{ route('download_history_torrents', ['username' => $user->username]) }}" role="button"
                   class="btn btn-sm btn-labeled btn-success">
                    <span class='btn-label'>
                        <i class='{{ config('other.font-awesome') }} fa-download'></i> {{ __('torrent.download-all') }}
                    </span>
                </a>
            @endif
        @endif
    </div>
    <div class="button-right-small">
        @if(auth()->user()->id == $user->id)
            <a href="{{ route('user_settings', ['username' => $user->username]) }}" class="btn btn-sm btn-danger">
                {{ __('user.settings') }}
            </a>
            <a href="{{ route('user_edit_profile_form', ['username' => $user->username]) }}">
                <button class="btn btn-sm btn-danger">{{ __('user.edit-profile') }}</button>
            </a>
        @endif
    </div>
</div>
