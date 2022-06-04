@component('mail::message')
# {{ __('email.register-header') }} {{ config('other.title') }} !
**{{ __('email.register-code') }}**
@component('mail::button', ['url' => route('activate', $code), 'color' => 'blue'])
{{ __('email.activate-account') }}
@endcomponent
<p>{{ __('email.register-footer') }}</p>
<p style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;">{{ route('activate', $code) }}</p>
@endcomponent
