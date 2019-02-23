@component('mail::message')
# @lang('email.bug-header') {{ $input['email'] }}

**@lang('email.contact-name'):** {{ $input['username'] }}

**@lang('email.bug-title'):** {{ $input['title'] }}

**@lang('email.bug-description'):** {{ $input['problem'] }}

**@lang('email.bug-priority'):** {{ $input['priority'] }}

*@lang('email.bug-footer')!!*
@endcomponent
