<div class="button-holder">
    <div class="button-left-large">
        <a href="{{ route('profile', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.profile')
        </a>
        <a href="{{ route('user_resurrections', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.resurrections')
        </a>
        <a href="{{ route('user_requested', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.requested')
        </a>
        <a href="{{ route('user_bookmarks', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.bookmarks')
        </a>
        <a href="{{ route('user_wishlist', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.wishlist')
        </a>
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