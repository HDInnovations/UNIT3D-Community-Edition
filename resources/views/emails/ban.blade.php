@component('mail::message')
# You Have Been Banned!

**Reason:** {{ $ban->ban_reason }}

*Thats what you get for not following the rules*
@endcomponent
