@if($route == 'profile' && auth()->user()->id != $user->id)

@else
<div class="button-holder">
    <div class="button-left">
        <a href="{{ route('profile', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.profile')
        </a>
        <a href="{{ route('user_posts', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.posts')
        </a>
        <a href="{{ route('user_topics', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-primary">
            @lang('user.topics')
        </a>
    </div>
    <div class="button-right">
        @if(auth()->user()->id == $user->id)
        <a href="{{ route('user_settings', ['slug' => $user->slug, 'id' => $user->id]) }}" class="btn btn-sm btn-danger">
            @lang('user.settings')
        </a>
        <a href="{{ route('user_edit_profile_form', ['username' => $user->slug, 'id' => $user->id]) }}">
            <button class="btn btn-sm btn-danger">@lang('user.edit-profile')</button></a>
            @endif
    </div>
</div>
@endif