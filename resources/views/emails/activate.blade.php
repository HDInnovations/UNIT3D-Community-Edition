@component('mail::message')
# {{ trans('email.register-header') }} {{ config('other.title') }} !
**{{ trans('email.register-code') }}::**
@component('mail::button', ['url' => route('activate', $code), 'color' => 'blue'])
{{ trans('email.activate-account') }}
@endcomponent
@endcomponent