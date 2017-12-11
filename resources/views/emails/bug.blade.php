@component('mail::message')
# New bug report from {{ $input['username'] }}

**Bug Title:** {{ $input['title'] }}

**Problem:** {{ $input['problem'] }}

**Priority:** {{ $input['priority'] }}

*Fix That Shit!!*
@endcomponent
