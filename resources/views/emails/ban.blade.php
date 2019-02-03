@component('mail::message')
# @lang('email.banned-header')!

**Reason:** {{ $ban->ban_reason }}

*@lang('email.banned-footer')*
@endcomponent
