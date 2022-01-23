<div class="button-holder">
    <div class="button-left">
        <a href="{{ route('users.show', ['username' => auth()->user()->username]) }}" class="btn btn-sm btn-primary">
            {{ __('user.profile') }}
        </a>
        <a href="{{ route('bonus') }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-coins"></i> {{ __('bon.bon') }}
        </a>
        <a href="{{ route('bonus_store') }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-shopping-cart"></i> {{ __('bon.store') }}
        </a>
        <a href="{{ route('bonus_gifts') }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-star"></i> {{ __('bon.gifts') }}
        </a>
        <a href="{{ route('bonus_tips') }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i> {{ __('bon.tips') }}
        </a>
        <a href="{{ route('bonus_gift') }}" class="btn btn-sm btn-success">
            <i class="{{ config('other.font-awesome') }} fa-gift"></i> {{ __('bon.send-gift') }}
        </a>
    </div>
    <div class="button-right">
        <a href="{{ route('user_settings', ['username' => auth()->user()->username]) }}" class="btn btn-sm btn-danger">
            {{ __('user.settings') }}
        </a>
        <a href="{{ route('user_edit_profile_form', ['username' => auth()->user()->username]) }}">
            <button class="btn btn-sm btn-danger">{{ __('user.edit-profile') }}</button>
        </a>
    </div>
</div>
