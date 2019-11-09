@if ($cookieConsentConfig['enabled'] && !$alreadyConsentedWithCookies)
    @include('cookieConsent::dialogContents')
    
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
        laravelCookieConsent = (function() {
    
            let COOKIE_VALUE = 1;
    
            function consentWithCookies() {
                setCookie(`{{ $cookieConsentConfig['cookie_name'] }}`, COOKIE_VALUE, 365 * 20);
                hideCookieDialog();
            }
    
            function cookieExists(name) {
                return (document.cookie.split('; ').indexOf(name + '=' + COOKIE_VALUE) !== -1);
            }
    
            function hideCookieDialog() {
                let dialogs = document.getElementsByClassName('alert alert-danger alert-dismissable');
    
                for (let i = 0; i < dialogs.length; ++i) {
                    dialogs[i].style.display = 'none';
                }
            }
    
            function setCookie(name, value, expirationInDays) {
                let date = new Date();
                date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
                document.cookie = name + '=' + value + '; ' + 'expires=' + date.toUTCString() + ';path=/';
            }
    
            if (cookieExists(`{{ $cookieConsentConfig['cookie_name'] }}`)) {
                hideCookieDialog();
            }
    
            let buttons = document.getElementsByClassName('btn btn-sm btn-primary');
    
            for (let i = 0; i < buttons.length; ++i) {
                buttons[i].addEventListener('click', consentWithCookies);
            }
    
            return {
                consentWithCookies: consentWithCookies,
                hideCookieDialog: hideCookieDialog
            };
        })();
    
    </script>
@endif
