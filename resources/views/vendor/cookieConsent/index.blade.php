@if($cookieConsentConfig['enabled'] && !$alreadyConsentedWithCookies)
    @include('cookieConsent::dialogContents')
@endif
