<input type="hidden" name="_captcha" value="{{ $token }}"/>
<div style="position:fixed;transform:translateX(-10000px);">
    <label for="{{ $mustBeEmptyField }}">Name</label>
    <input type="text" name="{{ $mustBeEmptyField }}" value=""/>
</div>
<input type="hidden" name="{{ $random }}" value="{{ $ts }}"/>
