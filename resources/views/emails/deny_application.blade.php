@component('mail::message')
# Your {{ config('other.title') }} Application
Your application has been denied for the following reason:
{{ $deniedMessage }}
Thanks,
{{ config('other.title') }}
@endcomponent
