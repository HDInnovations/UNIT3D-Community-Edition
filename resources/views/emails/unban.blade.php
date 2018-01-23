@component('mail::message')
# {{ trans('email.unban-header') }}!

**{{ trans('email.unban-reason') }}:** {{ $ban->unban_reason }}

*{{ trans('email.unban-footer') }}*
@endcomponent
