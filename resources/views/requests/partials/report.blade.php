<div class="form__group form__group--short-horizontal" x-data="{ open: false }">
    <button class="form__button form__button--filled form__button--centered" x-on:click.stop="open = true; $refs.dialog.showModal();">
        {{ __('common.report') }}
    </button>
    <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
        <h3 class="dialog__heading">
            {{ __('request.report') }}: {{ $torrentRequest->name }}</h4>
        </h3>
        <form
            class="dialog__form"
            method="POST"
            action="{{ route("report_request", ['id' => $torrentRequest->id]) }}"
            x-on:click.outside="open = false; $refs.dialog.close();"
        >
            @csrf
            <input id="type" type="hidden" name="title" value="{{ $torrentRequest->name }}">
            <p class="form__group">
                <textarea
                    id="message"
                    class="form__text"
                    name="message"
                    placeholder=""
                    type="text"
                ></textarea>
                <label for="message" class="form__label form__label--floating">
                    {{ __('request.reason') }}
                </label>
            </p>
            <p class="form__group">
                <button
                    class="form__button form__button--filled"
                    @if ($user->seedbonus < 100)
                        disabled
                        title="{{ __('request.dont-have-bps') }}"
                    @endif
                >
                    {{ __('request.report') }}
                </button>
                <button x-on:click.prevent="open = false; $refs.dialog.close();" class="form__button form__button--outlined">
                    {{ __('common.cancel') }}
                </button>
            </p>
        </form>
    </dialog>
</div>
