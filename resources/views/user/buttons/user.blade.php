<div class="mt-20 text-center">
    <a href="{{ route('user_resurrections', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
        @lang('user.resurrections')
    </a>
    <a href="{{ route('user_bookmarks', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
        @lang('user.bookmarks')
    </a>
    <a href="{{ route('user_wishlist', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
        @lang('user.wishlist')
    </a>
    <a href="{{ route('user_clients', ['username' => $user->slug, 'id' => $user->id]) }}">
        <button class="btn btn-sm btn-primary">
            @lang('user.seedboxes')</button>
    </a>
    <a href="{{ route('user_invites', ['slug' => $user->slug, 'id' => $user->id]) }}"><span
                class="btn btn-sm btn-primary">@lang('user.invites')</span></a>
    <a href="{{ route('invite') }}">
        <button class="btn btn-sm btn-primary">@lang('user.send-invite')
        </button>
    </a>
</div>
