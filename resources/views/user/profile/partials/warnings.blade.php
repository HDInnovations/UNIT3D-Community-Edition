<section class="panelV2" x-data="{ tab: 'automated'}">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('user.warnings') }}</h2>
        <div class="panel__actions">
            <div class="panel__action" x-data="{ open: false }">
                <button class="form__button form__button--text" x-on:click.stop="open = true; $refs.dialog.showModal();">
                    {{ __('common.add') }}
                </button>
                <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
                    <h3 class="dialog__heading">
                        Warn user: {{ $user->username }}
                    </h3>
                    <form
                        class="dialog__form"
                        method="POST"
                        action="{{ route('user_warn', ['username' => $user->username]) }}"
                        x-on:click.outside="open = false; $refs.dialog.close();"
                    >
                        @csrf
                        <p class="form__group">
                            <textarea
                                id="warn_reason"
                                class="form__textarea"
                                name="message"
                                required
                            ></textarea>
                            <label class="form__label form__label--floating" for="warn_reason">Reason</label>
                        </p>
                        <p class="form__group">
                            <button class="form__button form__button--filled">
                                {{ __('common.save') }}
                            </button>
                            <button x-on:click.prevent="open = false; $refs.dialog.close();" class="form__button form__button--outlined">
                                {{ __('common.cancel') }}
                            </button>
                        </p>
                    </form>
                </dialog>
            </div>
            <form
                class="panel__action"
                action="{{ route('massDeleteWarnings', ['username' => $user->username]) }}"
                method="POST"
                x-data
            >
                @csrf
                @method('DELETE')
                <button
                    x-on:click.prevent="Swal.fire({
                        title: 'Are you sure?',
                        text: 'Are you sure you want to delete all warnings?',
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
                    {{ __('user.delete-all') }}
                </button>
            </form>
        </div>
    </header>
    <menu class="panel__tabs">
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'automated' && 'panel__tab--active'"
            x-on:click="tab = 'automated'"
        >
            Automated ({{ $user->auto_warnings_count ?? 0 }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'manual' && 'panel__tab--active'"
            x-on:click="tab = 'manual'"
        >
            Manual ({{ $user->manual_warnings_count ?? 0 }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'deleted' && 'panel__tab--active'"
            x-on:click="tab = 'deleted'"
        >
            Soft Deleted ({{ $user->soft_deleted_warnings_count ?? 0 }})
        </li>
    </menu>
    <div class="data-table-wrapper" x-show="tab === 'automated'">
        <table class="data-table">
            <thead>
                <tr>
                    <th>{{ __('user.warned-by') }}</th>
                    <th>{{ __('torrent.torrent') }}</th>
                    <th>{{ __('common.reason') }}</th>
                    <th>{{ __('user.created-on') }}</th>
                    <th>{{ __('user.expires-on') }}</th>
                    <th>{{ __('user.active') }}</th>
                    <th>{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($autoWarnings as $warning)
                    <tr>
                        <td>
                            <x-user_tag :user="$warning->staffuser" :anon="false" />
                        </td>
                        <td>
                            @isset($warning->torrenttitle)
                                <a href="{{ route('torrent', ['id' => $warning->torrenttitle->id]) }}">
                                    {{ $warning->torrenttitle->name }}
                                </a>
                            @else
                                n/a
                            @endif
                        </td>
                        <td>{{ $warning->reason }}</td>
                        <td>
                            <time datetime="{{ $warning->created_at }}" title="{{ $warning->created_at }}">
                                {{ $warning->created_at }}
                            </time>
                        </td>
                        <td>
                            <time datetime="{{ $warning->expires_on }}" title="{{ $warning->expires_on }}">
                                {{ $warning->expires_on }}
                            </time>
                        </td>
                        <td>
                            @if ($warning->active)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                        <td>
                            <menu class="data-table__actions">
                                <li class="data-table__action">
                                    <form
                                        action="{{ route('deleteWarning', ['id' => $warning->id]) }}"
                                        method="POST"
                                        x-data
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            x-on:click.prevent="Swal.fire({
                                                title: 'Are you sure?',
                                                text: `Are you sure you want to delete this warning: ${atob('{{ base64_encode($warning->reason) }}')}?`,
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
                        <td colspan="7">{{ __('user.no-warning') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $autoWarnings->links('partials.pagination') }}
    </div>
    <div class="data-table-wrapper" x-show="tab === 'manual'">
        <table class="data-table">
            <thead>
            <tr>
                <th>{{ __('user.warned-by') }}</th>
                <th>{{ __('common.reason') }}</th>
                <th>{{ __('user.created-on') }}</th>
                <th>{{ __('user.expires-on') }}</th>
                <th>{{ __('user.active') }}</th>
                <th>{{ __('common.actions') }}</th>
            </tr>
            </thead>
        <tbody>
        @forelse ($manualWarnings as $warning)
            <tr>
                <td>
                    <x-user_tag :user="$warning->staffuser" :anon="false" />
                </td>
                <td>{{ $warning->reason }}</td>
                <td>
                    <time datetime="{{ $warning->created_at }}" title="{{ $warning->created_at }}">
                        {{ $warning->created_at }}
                    </time>
                </td>
                <td>
                    <time datetime="{{ $warning->expires_on }}" title="{{ $warning->expires_on }}">
                        {{ $warning->expires_on }}
                    </time>
                </td>
                <td>
                    @if ($warning->active)
                        <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                    @else
                        <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                    @endif
                </td>
                <td>
                    <menu class="data-table__actions">
                        <li class="data-table__action">
                            <form
                                    action="{{ route('deleteWarning', ['id' => $warning->id]) }}"
                                    method="POST"
                                    x-data
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                        x-on:click.prevent="Swal.fire({
                                                title: 'Are you sure?',
                                                text: `Are you sure you want to delete this warning: ${atob('{{ base64_encode($warning->reason) }}')}?`,
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
                <td colspan="7">{{ __('user.no-warning') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $manualWarnings->links('partials.pagination') }}
</div>
    <div class="data-table-wrapper" x-show="tab === 'deleted'" x-cloak>
        <table class="data-table">
            <thead>
                <tr>
                    <th>{{ __('user.warned-by') }}</th>
                    <th>{{ __('torrent.torrent') }}</th>
                    <th>{{ __('common.reason') }}</th>
                    <th>{{ __('user.created-on') }}</th>
                    <th>{{ __('user.deleted-on') }}</th>
                    <th>{{ __('user.deleted-by') }}</th>
                    <th>{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($softDeletedWarnings as $warning)
                    <tr>
                        <td>
                            <x-user_tag :user="$warning->staffuser" :anon="false" />
                        </td>
                        <td>
                            @isset($warning->torrenttitle)
                                <a href="{{ route('torrent', ['id' => $warning->torrenttitle->id]) }}">
                                    {{ $warning->torrenttitle->name }}
                                </a>
                            @else
                                n/a
                            @endif
                        </td>
                        <td>{{ $warning->reason }}</td>
                        <td>{{ $warning->created_at }}</td>
                        <td>{{ $warning->deleted_at }}</td>
                        <td>
                            <x-user_tag :user="$warning->deletedBy" :anon="false" />
                        </td>
                        <td>
                            <menu class="data-table__actions">
                                <li class="data-table__action">
                                    <form
                                        action="{{ route('restoreWarning', ['id' => $warning->id]) }}"
                                        method="POST"
                                        x-data
                                    >
                                        @csrf
                                        <button
                                            x-on:click.prevent="Swal.fire({
                                                title: 'Are you sure?',
                                                text: `Are you sure you want to restore this warning: ${atob('{{ base64_encode($warning->reason) }}')}?`,
                                                icon: 'warning',
                                                showConfirmButton: true,
                                                showCancelButton: true,
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $root.submit();
                                                }
                                            })"
                                            class="form__button form__button--text"
                                            @disabled(! $warning->active)
                                        >
                                            {{ __('user.restore') }}
                                        </button>
                                    </form>
                                </li>
                            </menu>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">{{ __('user.no-soft-warning') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $softDeletedWarnings->links('partials.pagination') }}
    </div>
</section>
