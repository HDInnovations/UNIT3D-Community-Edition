<div class="button-holder">
    <div class="button-left">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.profile') }}
        </a>
        @if(auth()->user()->id == $user->id)
            <a href="{{ route('invites.index', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                {{ __('user.invites') }}
            </a>
            <a href="{{ route('invites.create') }}" class="btn btn-sm btn-success">
                <i class="{{ config('other.font-awesome') }} fa-gift"></i> {{ __('user.send-invite') }}
            </a>
        @endif
    </div>
    <div class="button-right">
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
