@component('mail::message')
    # @lang('email.disabled-header')!
    
    Your account has been flagged as inactive and placed within the disabled group. In order to keep your account you MUST
    login within {{ config('other.soft_delete') }} days of receiving this email. Failure to do so will result in your
    account
    being permanently pruned from use on {{ config('other.title') }}! To avoid this in the future please login atleast one
    time
    every {{ config('other.last_login') }} days.
    
@endcomponent
