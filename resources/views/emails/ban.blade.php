@component('mail::message')
# {{ trans('email.banned-header') }}!

**{{ trans('email.ban-reason') }}:** {{ $ban->ban_reason }}

*{{ trans('email.ban-footer') }}*
@endcomponent
