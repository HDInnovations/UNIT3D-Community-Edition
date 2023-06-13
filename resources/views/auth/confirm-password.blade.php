<title>Password Confirmation - {{ config('other.title') }}</title>
<section class="panelV2">
    <h2 class="panel__heading">Password Confirmation</h2>
    <div class="panel__body">
        <form
            class="form"
            action="{{ route('auth.confirm-password') }}"
            method="POST"
        >
            @csrf
            @method('PATCH')
            <p>Please confirm your password before continuing.</p>
            <p class="form__group">
                <input
                    type="password"
                    class="form__text"
                    id="password"
                    name="password"
                >
                <label class="form__label" for="password">Password</label>
                @error('error')
                    <span class="form__hint">{{ $error }}</span>
                @enderror
            </p>
            <p class="form__group">
                <button class="form__button form__button--filled">
                    {{ __('common.confirm') }}
                </button>
            </p>
        </form>
</section>
