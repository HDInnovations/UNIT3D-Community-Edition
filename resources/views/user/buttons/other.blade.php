<div class="button-holder">
    <div class="button-left-large">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.profile') }}
        </a>
        <a href="{{ route('user_resurrections', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.resurrections') }}
        </a>
        <a href="{{ route('user_requested', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.requested') }}
        </a>
        <a href="{{ route('torrents') }}?bookmarked=1" class="btn btn-sm btn-primary">
            {{ __('user.bookmarks') }}
        </a>
        <a href="{{ route('wishes.index', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.wishlist') }}
        </a>
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
