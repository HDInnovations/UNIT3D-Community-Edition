@component('mail::message')
# @lang('email.invite-header') {{ config('other.title') }} !
**@lang('email.invite-message'):** @lang('email.invite-invited') {{ config('other.title') }}. {{ $invite->custom }}
@component('mail::button', ['url' => route('register', $invite->code), 'color' => 'blue'])
@lang('email.invite-signup')
@endcomponent
<p>If the button above does not work, copy and paste the link below into your browser's address bar.</p>
<p style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;">{{ route('register', $invite->code) }}</p>
@endcomponent
