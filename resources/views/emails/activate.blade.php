@component('mail::message')
# @lang('email.register-header') {{ config('other.title') }} !
**@lang('email.register-code')**
@component('mail::button', ['url' => route('activate', $code), 'color' => 'blue'])
@lang('email.activate-account')
@endcomponent
<p>If the button above does not work, copy and paste the link below into your browser's address bar.</p>
<p style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;">{{ route('activate', $code) }}</p>
@endcomponent
