@component('mail::message')
# @lang('email.banned-header')!

**Reason:** {{ $logban->ban_reason }}

*@lang('email.banned-footer')*
@endcomponent
