<div style="display: contents; background-color: inherit;">
    <p class="form__group">
        <input
                id="new_password"
                class="form__text"
                autocomplete="new-password"
                minlength="12"
                name="new_password"
                placeholder=""
                required
                type="password"
                value="{{ old('new_password') }}"
                wire:model="password"
        >
        <label class="form__label form__label--floating" for="new_password">New Password</label>
    </p>
    <p class="form__group">
        <input
                id="new_password_confirmation"
                class="form__text"
                autocomplete="new-password"
                minlength="12"
                name="new_password_confirmation"
                placeholder=""
                required
                type="password"
                value="{{ old('new_password') }}"
        >
        <label class="form__label form__label--floating" for="new_password_confirmation">Repeat Password</label>
    </p>

    <p>Password strength:</p> {{ $strengthLevels[$strengthScore] ?? 'Weak' }}

    <progress value="{{ $strengthScore }}" max="4"></progress>
</div>