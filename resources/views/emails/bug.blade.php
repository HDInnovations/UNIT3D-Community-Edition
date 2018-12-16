@component('mail::message')
# @lang('email.bug-header') {{ $input['username'] }}

**@lang('email.bug-title'):** {{ $input['title'] }}

**@lang('email.bug-description'):** {{ $input['problem'] }}

**@lang('email.bug-priority'):** {{ $input['priority'] }}

*@lang('email.bug-footer')!!*
@endcomponent
