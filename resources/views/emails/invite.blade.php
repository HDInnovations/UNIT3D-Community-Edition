@component('mail::message')
# @lang('email.invite-header') {{ config('other.title') }} !
**@lang('email.invite-message'):** @lang('email.invite-invited') {{ config('other.title') }}. {{ $invite->custom }}
@component('mail::button', ['url' => route('register', $invite->code), 'color' => 'blue'])
@lang('email.invite-signup')
@endcomponent
@endcomponent
