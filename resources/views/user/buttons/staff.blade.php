<div class="text-center mt-20">
    <a href="{{ route('user_resurrections', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
        @lang('user.resurrections')
    </a>
    <a href="{{ route('bookmarks.index', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
        @lang('user.bookmarks')
    </a>
    <a href="{{ route('user_wishlist', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
        @lang('user.wishlist')
    </a>
    <a href="{{ route('seedboxes.index', ['username' => $user->username]) }}">
        <button class="btn btn-sm btn-primary">
            @lang('user.seedboxes')</button>
    </a>
    <a href="{{ route('invites.index', ['username' => $user->username]) }}"><span
                class="btn btn-sm btn-primary">@lang('user.invites')</span></a>
</div>
