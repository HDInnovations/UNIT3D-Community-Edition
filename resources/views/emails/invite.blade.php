@component('mail::message')
# {{ trans('email.invite-header') }} {{ Config::get('other.title') }} !

**{{ trans('email.invite-message') }}:** {{ trans('email.invite-invited') }} {{ Config::get('other.title') }}

@component('mail::button', ['url' => route('register', $invite->code), 'color' => 'blue'])
{{ trans('email.invite-signup') }}
@endcomponent

@endcomponent
