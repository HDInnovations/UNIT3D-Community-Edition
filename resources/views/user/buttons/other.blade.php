<div class="button-holder">
    <div class="button-left-large">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.profile')
        </a>
        <a href="{{ route('user_resurrections', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.resurrections')
        </a>
        <a href="{{ route('user_requested', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.requested')
        </a>
        <a href="{{ route('bookmarks.index', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.bookmarks')
        </a>
        <a href="{{ route('wishes.index', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.wishlist')
        </a>
    </div>
    <div class="button-right-small">
        @if(auth()->user()->id == $user->id)
            <a href="{{ route('user_settings', ['username' => $user->username]) }}" class="btn btn-sm btn-danger">
                @lang('user.settings')
            </a>
            <a href="{{ route('user_edit_profile_form', ['username' => $user->username]) }}">
                <button class="btn btn-sm btn-danger">@lang('user.edit-profile')</button></a>
        @endif
    </div>
</div>
