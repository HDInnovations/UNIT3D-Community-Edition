@component('mail::message')
# {{ trans('email.banned-header') }}!

**Reason:** {{ $ban->ban_reason }}

*{{ trans('email.banned-footer') }}*
@endcomponent
