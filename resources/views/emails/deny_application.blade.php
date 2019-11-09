@component('mail::message')
    # Your {{ config('other.title') }} Application
    
    Your application has been denied for the following reason:
    
    {{ $denied_message }}
    
    Thanks,
    {{ config('other.title') }}
@endcomponent
