@component('mail::message')
# @lang('email.register-header') {{ config('other.title') }} !
**@lang('email.register-code')**
@component('mail::button', ['url' => route('activate', $code), 'color' => 'blue'])
@lang('email.activate-account')
@endcomponent
@endcomponent
