@component('mail::message')
# {{ trans('email.contact-header') }} {{ $input['email'] }}

**{{ trans('email.contact-name') }}:** {{ $input['contact-name'] }}

**{{ trans('email.contact-message') }}:** {{ $input['message'] }}
@endcomponent
