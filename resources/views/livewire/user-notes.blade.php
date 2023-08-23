<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('staff.user-notes') }}</h2>
        <div class="panel__actions">
            <div class="panel__action" x-data>
                <button
                    class="form__button form__button--text"
                    x-on:click.stop="$refs.dialog.showModal()"
                >
                    {{ __('common.add') }}
                </button>
                <dialog class="dialog" x-ref="dialog">
                    <h3 class="dialog__heading">
                        Note user: {{ $user->username }}
                    </h3>
                    <form
                        class="dialog__form"
                        x-on:click.outside="$refs.dialog.close()"
                        x-on:submit.prevent="$refs.dialog.close()"
                    >
                        <p class="form__group">
                            <textarea
                                id="message"
                                class="form__textarea"
                                name="message"
                                placeholder=" "
                                wire:model.defer="message"
                            ></textarea>
                            <label class="form__label form__label--floating" for="message">
                                Note
                            </label>
                        </p>
                        <p class="form__group">
                            <button
                                class="form__button form__button--filled"
                                wire:click="store"
                                x-on:click="$refs.dialog.close()"
                            >
                                {{ __('common.save') }}
                            </button>
                            <button formmethod="dialog" formnovalidate class="form__button form__button--outlined">
                                {{ __('common.cancel') }}
                            </button>
                        </p>
                    </form>
                </dialog>
            </div>
        </div>
    </header>
    <div class="data-table-wrapper">
        <table class="data-table">
            <thead>
            <tr>
                <th>{{ __('common.staff') }}</th>
                <th>{{ __('user.note') }}</th>
                <th>{{ __('user.created-on') }}</th>
                <th>{{ __('common.action') }}</th>
            </tr>
            </thead>
            <tbody>
                @forelse ($notes as $note)
                    <tr>
                        <td>
                            <x-user_tag :anon="false" :user="$note->staffuser" />
                        </td>
                        <td style="white-space: pre-wrap">
                            {{ $note->message }}
                        </td>
                        <td>
                            <time datetime="{{ $note->created_at }}" title="{{ $note->created_at }}">
                                {{ $note->created_at->diffForHumans() }}
                            </time>
                        </td>
                        <td>
                            <menu class="data-table__actions">
                                <li class="data-table__action">
                                    <form x-data>
                                        <button
                                            x-on:click.prevent="Swal.fire({
                                                title: 'Are you sure?',
                                                text: `Are you sure you want to delete this note: ${atob('{{ base64_encode($note->message) }}')}?`,
                                                icon: 'warning',
                                                showConfirmButton: true,
                                                showCancelButton: true,
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    @this.destroy({{ $note->id }})
                                                }
                                            })"
                                            class="form__button form__button--text"
                                        >
                                            {{ __('common.delete') }}
                                        </button>
                                    </form>
                                </li>
                            </menu>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No notes</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>