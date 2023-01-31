<li class="data-table__action" x-data="{ open: false }">
    <button class="form__button form__button--filled" x-on:click.stop="open = true; $refs.dialog.showModal();">
        <i class="{{ config('other.font-awesome') }} fa-pause"></i>
        {{ __('common.moderation-postpone') }}
    </button>
    <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
        <h4 class="dialog__heading">
            {{ __('common.moderation-postpone') }} {{ __('torrent.torrent') }}: {{ $torrent->name }}
        </h4>
        <form
            class="dialog__form"
            method="POST"
            action="{{ route('staff.moderation.update', ['id' => $torrent->id]) }}"
            x-on:click.outside="open = false; $refs.dialog.close();"
        >
            @csrf
            <input type="hidden" name="type" value="{{ __('torrent.torrent') }}">
            <input type="hidden" name="id" value="{{ $torrent->id }}">
            <input type="hidden" name="old_status" value="{{ $torrent->status }}">
            <input type="hidden" name="status" value="3">
            <p class="form__group">
                <textarea class="form__textarea" name="message" id="message">{{ old('message') }}</textarea>
                <label class="form__label form__label--floating" for="message">Postpone Message</label>
            </p>
            <p class="form__group">
                <button class="form__button form__button--filled">
                    {{ __('common.moderation-postpone') }}
                </button>
                <button class="form__button form__button--outlined" x-on:click.prevent="open = false; $refs.dialog.close()">
                    {{ __('common.cancel') }}
                </button>
            </p>
        </form>
    </dialog>
</li>
