<div class="button-holder">
    <div class="button-left">
        <a href="{{ route('profile', ['username' => auth()->user()->slug, 'id' => auth()->user()->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.profile')
        </a>
        <a href="{{ route('bonus') }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-star-exclamation"></i> @lang('bon.bon')
        </a>
        <a href="{{ route('bonus_store') }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-shopping-cart"></i> @lang('bon.store')
        </a>
        <a href="{{ route('bonus_gifts') }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-star"></i> @lang('bon.gifts')
        </a>
        <a href="{{ route('bonus_tips') }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i> @lang('bon.tips')
        </a>
        <a href="{{ route('bonus_gift') }}" class="btn btn-sm btn-success">
            <i class="{{ config('other.font-awesome') }} fa-gift"></i> @lang('bon.send-gift')
        </a>
    </div>
    <div class="button-right">
        <a href="{{ route('user_settings', ['slug' => auth()->user()->slug, 'id' => auth()->user()->id]) }}" class="btn btn-sm btn-danger">
            @lang('user.settings')
        </a>
        <a href="{{ route('user_edit_profile_form', ['username' => auth()->user()->slug, 'id' => auth()->user()->id]) }}">
            <button class="btn btn-sm btn-danger">@lang('user.edit-profile')</button></a>
    </div>
</div>