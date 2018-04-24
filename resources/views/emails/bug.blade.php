@component('mail::message')
# {{ trans('email.bug-header') }} {{ $input['username'] }}

**{{ trans('email.bug-title') }}:** {{ $input['title'] }}

**{{ trans('email.bug-description') }}:** {{ $input['problem'] }}

**{{ trans('email.bug-priority') }}:** {{ $input['priority'] }}

*{{ trans('email.bug-footer') }}!!*
@endcomponent
