@component('mail::message')
# {{ trans('email.invite-header') }} {{ config('other.title') }} !

**{{ trans('email.invite-message') }}:** {{ trans('email.invite-invited') }} {{ config('other.title') }}

@component('mail::button', ['url' => route('register', $invite->code), 'color' => 'blue'])
{{ trans('email.invite-signup') }}
@endcomponent

@endcomponent
