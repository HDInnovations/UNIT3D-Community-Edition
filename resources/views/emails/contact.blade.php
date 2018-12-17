@component('mail::message')
# @lang('email.contact-header') {{ $input['email'] }}

**@lang('email.contact-name'):** {{ $input['contact-name'] }}

**@lang('email.contact-message'):** {{ $input['message'] }}
@endcomponent
