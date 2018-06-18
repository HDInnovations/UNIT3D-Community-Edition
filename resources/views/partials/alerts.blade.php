@if (config('other.freeleech') === true || config('other.invite-only') === false || config('other.doubleup') === true)
    <div class="alert alert-info" id="alert1">
        <div class="text-center">
    <span>
      @if(config('other.freeleech') === true) {{ trans('common.freeleech_activated') }}! @endif
        @if(config('other.invite-only') === false) {{ trans('common.openreg_activated') }}! @endif
        @if(config('other.doubleup') === true) {{ trans('common.doubleup_activated') }}! @endif
    </span>
            <strong>
                <div id="promotions"></div>
            </strong></div>
    </div>
@endif
