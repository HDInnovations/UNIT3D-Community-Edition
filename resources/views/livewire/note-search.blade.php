<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('staff.user-notes') }}</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <select
                            id="quantity"
                            class="form__select"
                            wire:model="perPage"
                            required
                    >
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <label class="form__label form__label--floating">
                        {{ __('common.quantity') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <input
                            id="search"
                            class="form__text"
                            type="text"
                            wire:model="search"
                            placeholder=""
                    />
                    <label class="form__label form__label--floating">
                        Message
                    </label>
                </div>
            </div>
        </div>
    </header>
    <div class="data-table-wrapper">
        <table class="data-table">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __('common.user') }}</th>
                <th>{{ __('common.staff') }}</th>
                <th>{{ __('common.message') }}</th>
                <th>{{ __('user.created-on') }}</th>
                <th>{{ __('common.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($notes as $note)
                <tr>
                    <td>{{ $note->id }}</td>
                    <td>
                        <x-user_tag :anon="false" :user="$note->noteduser" />
                    </td>
                    <td>
                        <x-user_tag :anon="false" :user="$note->staffuser" />
                    </td>
                    <td>{{ $note->message }}</td>
                    <td>
                        <time datetime="{{ $note->created_at }}" title="{{ $note->created_at }}">
                            {{ $note->created_at->diffForHumans() }}
                        </time>
                    </td>
                    <td>
                        <menu class="data-table__actions">
                            <li class="data-table__action">
                                <form
                                        action="{{ route('staff.notes.destroy', ['id' => $note->id]) }}"
                                        method="POST"
                                        x-data
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                            x-on:click.prevent="Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'Are you sure you want to delete this note: {{ $note->message }}?',
                                                    icon: 'warning',
                                                    showConfirmButton: true,
                                                    showCancelButton: true,
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $root.submit();
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
                    <td colspan="6">No notes</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $notes->links('partials.pagination') }}
</section>
