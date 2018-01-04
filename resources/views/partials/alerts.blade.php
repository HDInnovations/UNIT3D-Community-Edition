@if (config('other.freeleech') == true || config('other.invite-only') == false || config('other.doubleup') == true)
<div class="alert alert-info" id="alert1">
  <center>
    <span>
      @if(config('other.freeleech') == true) Global Freeleech Mode Activated! @endif
      @if(config('other.invite-only') == false) Open Registration Activated! @endif
      @if(config('other.doubleup') == true) Global Double Upload Activated! @endif
    </span>
    <strong><div id="promotions"></div></strong></center>
</div>
@endif
